<?php

/**
 * Class FMViewForm_maker
 */
class FMViewForm_maker {
  private $model;
  /**
   * FMViewForm_maker constructor.
   *
   * @param $model
   */
  public function __construct( $model ) {
    $this->model = $model;

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-widget');
    wp_enqueue_script('jquery-effects-shake');
    wp_enqueue_script('fm-frontend');

    wp_enqueue_style('fm-jquery-ui');
    wp_enqueue_style('fm-frontend');
    wp_enqueue_style('fm-googlefonts');
    wp_enqueue_style('fm-animate');
    wp_enqueue_style('dashicons');
  }

  /**
   * Display.
   *
   * @param        $result
   * @param        $fm_settings
   * @param        $form_id
   * @param string $formType
   *
   * @return string
   */
  public function display( $result, $fm_settings, $form_id, $formType = 'embedded' ) {
  	$current_user = wp_get_current_user();
    if ( $current_user->ID != 0 ) {
      $wp_userid = $current_user->ID;
      $wp_username = $current_user->display_name;
      $wp_useremail = $current_user->user_email;
    }
    else {
      $wp_userid = '';
      $wp_username = '';
      $wp_useremail = '';
    }
    $current_url = htmlentities($_SERVER['REQUEST_URI']);

    $row = $result[0];
    $label_id = $result[2];
    $label_type = $result[3];
    $form_theme = $result[4];

    $theme_id = WDW_FM_Library::get('test_theme', $row->theme);
    if ( $theme_id == '' ) {
      $theme_id = $row->theme;
    }

    $article = $row->article_id;
    $header_pos = isset($form_theme['HPAlign']) && ($form_theme['HPAlign'] == 'left' || $form_theme['HPAlign'] == 'right') ? (($row->header_title || $row->header_description || $row->header_image_url) ? 'header_left_right' : 'no_header') : '';
    $pagination_align = $row->pagination == 'steps' && isset($form_theme['PSAPAlign']) ? 'fm-align-' . $form_theme['PSAPAlign'] : '';
    $form_currency = '$';
    $currency_code = array(
      'USD',
      'EUR',
      'GBP',
      'JPY',
      'CAD',
      'MXN',
      'HKD',
      'HUF',
      'NOK',
      'NZD',
      'SGD',
      'SEK',
      'PLN',
      'AUD',
      'DKK',
      'CHF',
      'CZK',
      'ILS',
      'BRL',
      'TWD',
      'MYR',
      'PHP',
      'THB',
    );
    $currency_sign = array(
      '$',
      '&#8364;',
      '&#163;',
      '&#165;',
      'C$',
      'Mex$',
      'HK$',
      'Ft',
      'kr',
      'NZ$',
      'S$',
      'kr',
      'zl',
      'A$',
      'kr',
      'CHF',
      'Kc',
      '&#8362;',
      'R$',
      'NT$',
      'RM',
      '&#8369;',
      '&#xe3f;',
    );
    if ( $row->payment_currency ) {
      $form_currency = $currency_sign[array_search($row->payment_currency, $currency_code)];
    }

    $form_paypal_tax = $row->tax;

    $form_maker_front_end = '';
    $form_maker_front_end .= '<div id="fm-pages' . $form_id . '" class="fm-pages wdform_page_navigation ' . $pagination_align . '" show_title="' . $row->show_title . '" show_numbers="' . $row->show_numbers . '" type="' . $row->pagination . '"></div>';
    $form_maker_front_end .= '<form name="form' . $form_id . '" action="' . $current_url . '" method="post" id="form' . $form_id . '" class="fm-form form' . $form_id . ' ' . $header_pos . '" enctype="multipart/form-data">
    <input type="hidden" id="counter' . $form_id . '" value="' . $row->counter . '" name="counter' . $form_id . '" />
    <input type="hidden" id="Itemid' . $form_id . '" value="" name="Itemid' . $form_id . '" />';
    $image_pos = isset($form_theme['HIPAlign']) && ($form_theme['HIPAlign'] == 'left' || $form_theme['HIPAlign'] == 'right') ? 'image_left_right' : '';
    $image_width = isset($form_theme['HIPWidth']) && $form_theme['HIPWidth'] ? 'width="' . $form_theme['HIPWidth'] . 'px"' : '';
    $image_height = isset($form_theme['HIPHeight']) && $form_theme['HIPHeight'] ? 'height="' . $form_theme['HIPHeight'] . 'px"' : '';
    $hide_header_image_class = wp_is_mobile() && $row->header_hide_image ? 'fm_hide_mobile' : '';
    $header_image_animation = $formType == 'embedded' ? $row->header_image_animation : '';
    if ( !isset($form_theme['HPAlign']) || ($form_theme['HPAlign'] == 'left' || $form_theme['HPAlign'] == 'top') ) {
      if ( $row->header_title || $row->header_description || $row->header_image_url ) {
        $form_maker_front_end .= '<div class="fm-header-bg"><div class="fm-header ' . $image_pos . '">';
        if ( !isset($form_theme['HIPAlign']) || $form_theme['HIPAlign'] == 'left' || $form_theme['HIPAlign'] == 'top' ) {
          if ( $row->header_image_url ) {
            $form_maker_front_end .= '<div class="fm-header-img ' . $hide_header_image_class . ' fm-animated ' . $header_image_animation . '"><img src="' . $row->header_image_url . '" ' . $image_width . ' ' . $image_height . '/></div>';
          }
        }
        if ( $row->header_title || $row->header_description ) {
          $form_maker_front_end .= '<div class="fm-header-text">
            <div class="fm-header-title">
              ' . $row->header_title . '
            </div>
            <div class="fm-header-description">
              ' . $row->header_description . '
            </div>
          </div>';
        }
        if ( isset($form_theme['HIPAlign']) && ($form_theme['HIPAlign'] == 'right' || $form_theme['HIPAlign'] == 'bottom') ) {
          if ( $row->header_image_url ) {
            $form_maker_front_end .= '<div class="fm-header-img"><img src="' . $row->header_image_url . '" ' . $image_width . ' ' . $image_height . '/></div>';
          }
        }
        $form_maker_front_end .= '</div></div>';
      }
    }
    $fm_hide_form_after_submit = 0;
    if ( isset($_SESSION['form_submit_type' . $form_id]) ) {
      $type_and_id = $_SESSION['form_submit_type' . $form_id];
      $type_and_id = explode(',', $type_and_id);
      $form_get_type = $type_and_id[0];
      $form_get_id = isset($type_and_id[1]) ? $type_and_id[1] : '';
      $_SESSION['form_submit_type' . $form_id] = 0;
      if ( $form_get_type == 3 ) {
        $_SESSION['massage_after_submit' . $form_id] = "";
        $after_submission_text = $this->model->get_after_submission_text($form_get_id);
        $form_maker_front_end .= WDW_FM_Library::message(wpautop(html_entity_decode($after_submission_text)), 'warning', $form_id);
        $fm_hide_form_after_submit = 1;
      }
    }
    if ( isset($_SESSION['redirect_paypal' . $form_id]) && ($_SESSION['redirect_paypal' . $form_id] == 1) ) {
      $_SESSION['redirect_paypal' . $form_id] = 0;
      if ( isset($_GET['succes']) ) {
        if ( $_GET['succes'] == 0 ) {
          $form_maker_front_end .= WDW_FM_Library::message(__('Error, email was not sent.', WDFM()->prefix), 'error');
        }
        else {
          $form_maker_front_end .= WDW_FM_Library::message(__('Your form was successfully submitted.', WDFM()->prefix), 'warning');
        }
      }
    }
    elseif ( isset($_SESSION['massage_after_submit' . $form_id]) && $_SESSION['massage_after_submit' . $form_id] != "" ) {
      $message = $_SESSION['massage_after_submit' . $form_id];
      $_SESSION['massage_after_submit' . $form_id] = "";
      if ( $_SESSION['error_or_no' . $form_id] ) {
        $error = 'error';
      }
      else {
        $error = 'warning';
      }
      $form_maker_front_end .= WDW_FM_Library::message($message, $error, $form_id);
    }
    if ( isset($_SESSION['massage_after_save' . $form_id]) && $_SESSION['massage_after_save' . $form_id] != "" ) {
      $save_message = $_SESSION['massage_after_save' . $form_id];
      $_SESSION['massage_after_save' . $form_id] = '';
      if ( isset($_SESSION['save_error' . $form_id]) && $_SESSION['save_error' . $form_id] == 2 ) {
        echo $save_message;
      }
      else {
        $save_error = $_SESSION['save_error' . $form_id] ? 'error' : 'warning';
        $form_maker_front_end .= WDW_FM_Library::message($save_message, $save_error, $form_id);
      }
    }
    if ( isset($_SESSION['show_submit_text' . $form_id]) ) {
      if ( $_SESSION['show_submit_text' . $form_id] == 1 ) {
        $_SESSION['show_submit_text' . $form_id] = 0;
        $form_maker_front_end .= $row->submit_text;
      }
    }
    if ( isset($_SESSION['fm_hide_form_after_submit' . $form_id]) && $_SESSION['fm_hide_form_after_submit' . $form_id] == 1 ) {
      $_SESSION['fm_hide_form_after_submit' . $form_id] = 0;
      $fm_hide_form_after_submit = 1;
    }
    $stripe_enable = 0;
    $is_type = array();
    $id1s = array();
    $types = array();
    $labels = array();
    $paramss = array();
    $required_sym = $row->requiredmark;
    $fields = explode('*:*new_field*:*', $row->form_fields);
    $fields = array_slice($fields, 0, count($fields) - 1);
    foreach ( $fields as $field ) {
      $temp = explode('*:*id*:*', $field);
      array_push($id1s, $temp[0]);
      $temp = explode('*:*type*:*', $temp[1]);
      array_push($types, $temp[0]);
      $temp = explode('*:*w_field_label*:*', $temp[1]);
      array_push($labels, $temp[0]);
      array_push($paramss, $temp[1]);
    }
	
    $symbol_begin = array();
    $symbol_end = array();

    // Get Add-on Calculator data.
    $calculator_data = apply_filters('fm_calculator_get_data_init', array(), $form_id);
    if ( !empty($calculator_data) ) {
      $symbol_end = json_decode($calculator_data->symbol_end, TRUE);
      $symbol_begin = json_decode($calculator_data->symbol_begin, TRUE);
    }

    $show_hide = array();
    $field_label = array();
    $all_any = array();
    $condition_params = array();
    $type_and_id = array();
    if ( $row->autogen_layout == 0 ) {
      $form = $row->custom_front;
    }
    else {
      $form = $row->form_front;
    }
    foreach ( $id1s as $id1s_key => $id1 ) {
      $label = $labels[$id1s_key];
      $type = $types[$id1s_key];
      $params = $paramss[$id1s_key];
      if ( strpos($form, '%' . $id1 . ' - ' . $label . '%') || strpos($form, '%' . $id1 . ' -' . $label . '%') ) {
        $rep = '';
        $required = FALSE;
        $param = array();
        $param['attributes'] = '';
        $is_type[$type] = TRUE;
        switch ( $type ) {
          case 'type_section_break': {
            $params_names = array( 'w_editor' );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            $rep = '<div type="type_section_break" class="wdform-field-section-break"><div class="wdform_section_break">' . html_entity_decode($param['w_editor']) . '</div></div>';
            break;
          }
          case 'type_editor': {
            $params_names = array( 'w_editor' );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            $rep = '<div type="type_editor" class="wdform-field">' . html_entity_decode($param['w_editor']) . '</div>';
            break;
          }
          case 'type_send_copy': {
            $params_names = array( 'w_field_label_size', 'w_field_label_pos', 'w_first_val', 'w_required' );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_first_val',
                'w_required',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $input_active = ($param['w_first_val'] == 'true' ? "checked='checked'" : "");
            $post_value = isset($_POST["counter" . $form_id]) ? esc_html($_POST["counter" . $form_id]) : NULL;
            if ( isset($post_value) ) {
              $post_temp = isset($_POST['wdform_' . $id1 . '_element' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element' . $form_id])) : NULL;
              $input_active = (isset($post_temp) ? "checked='checked'" : "");
            }
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $rep = '<div type="type_send_copy" class="wdform-field"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . ' width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label"><label for="wdform_' . $id1 . '_element' . $form_id . '">' . $label . '</label></span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div>
            <div class="wdform-element-section" style="' . $param['w_field_label_pos2'] . '" >
              <div class="checkbox-div" style="left:3px">
              <input type="checkbox" id="wdform_' . $id1 . '_element' . $form_id . '" name="wdform_' . $id1 . '_element' . $form_id . '" ' . $input_active . ' ' . $param['attributes'] . '/>
              <label for="wdform_' . $id1 . '_element' . $form_id . '"><span></span></label>
              </div>
            </div></div>';
            break;
          }
          case 'type_text': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_first_val',
              'w_title',
              'w_required',
              'w_unique',
            );
            $temp = $params;
            if ( strpos($temp, 'w_regExp_status') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_size',
                'w_first_val',
                'w_title',
                'w_required',
                'w_regExp_status',
                'w_regExp_value',
                'w_regExp_common',
                'w_regExp_arg',
                'w_regExp_alert',
                'w_unique',
              );
            }
            if ( strpos($temp, 'w_readonly') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_size',
                'w_first_val',
                'w_title',
                'w_required',
                'w_regExp_status',
                'w_regExp_value',
                'w_regExp_common',
                'w_regExp_arg',
                'w_regExp_alert',
                'w_unique',
                'w_readonly',
              );
            }
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_first_val',
                'w_title',
                'w_required',
                'w_regExp_status',
                'w_regExp_value',
                'w_regExp_common',
                'w_regExp_arg',
                'w_regExp_alert',
                'w_unique',
                'w_readonly',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_first_val'] = (isset($_POST['wdform_' . $id1 . '_element' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element' . $form_id])) : $param['w_first_val']);
            $wdformfieldsize = ($param['w_field_label_pos'] == "left" ? $param['w_field_label_size'] + $param['w_size'] + 50 : max($param['w_field_label_size'], $param['w_size']));
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $input_active = ($param['w_first_val'] == $param['w_title'] ? "input_deactive" : "input_active");
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $param['w_regExp_status'] = (isset($param['w_regExp_status']) ? $param['w_regExp_status'] : "no");
            $readonly = (isset($param['w_readonly']) && $param['w_readonly'] == "yes" ? "readonly='readonly'" : '');
            $symbol_begin_text = isset($symbol_begin[$id1]) ? $symbol_begin[$id1] : '';
            $symbol_end_text = isset($symbol_end[$id1]) ? $symbol_end[$id1] : '';
            $display_begin = $symbol_begin ? 'display:table-cell' : 'display:none;';
            $display_end = $symbol_end != '' ? 'display:table-cell' : 'display:none;';
            $input_width = $symbol_begin_text || $symbol_end_text ? '' : 'width:100%';
            $check_regExp = '';
            $rep = '<div type="type_text" class="wdform-field" style="width:' . $wdformfieldsize . 'px"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . ' width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section" style="' . $param['w_field_label_pos2'] . '; width:' . $param['w_size'] . 'px;">
              <div style="display:table; width: 100%;">
              <div style="display:table-row;">
                <div style="' . $display_begin . ';"><span style="vertical-align:middle;">' . $symbol_begin_text . '</span></div>
                 <div style="display:table-cell;">
              <input type="text" class="' . $input_active . '" id="wdform_' . $id1 . '_element' . $form_id . '" name="wdform_' . $id1 . '_element' . $form_id . '" value="' . $param['w_first_val'] . '" title="' . $param['w_title'] . '" placeholder="' . $param['w_title'] . '" ' . $readonly . '   ' . $param['attributes'] . ' style="' . $input_width . '"></div><div style="' . $display_end . ';"><span style="vertical-align:middle;">' . $symbol_end_text . '</span></div>
              </div>			  
              </div>	
              </div></div>';
            break;
          }
          case 'type_number': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_first_val',
              'w_title',
              'w_required',
              'w_unique',
              'w_class',
            );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_first_val'] = (isset($_POST['wdform_' . $id1 . '_element' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element' . $form_id])) : $param['w_first_val']);
            $wdformfieldsize = ($param['w_field_label_pos'] == "left" ? $param['w_field_label_size'] + $param['w_size'] + 10 : max($param['w_field_label_size'], $param['w_size']));
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $input_active = ($param['w_first_val'] == $param['w_title'] ? "input_deactive" : "input_active");
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $rep = '<div type="type_number" class="wdform-field" style="width:' . $wdformfieldsize . 'px"><div class="wdform-label-section"  class="' . $param['w_class'] . '" style="' . $param['w_field_label_pos1'] . ' width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ' width: ' . $param['w_size'] . 'px;"><input type="text" class="' . $input_active . '" id="wdform_' . $id1 . '_element' . $form_id . '" name="wdform_' . $id1 . '_element' . $form_id . '" value="' . $param['w_first_val'] . '" title="' . $param['w_title'] . '" style="width: 100%;" ' . $param['attributes'] . '></div></div>';
            break;
          }
          case 'type_password': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_required',
              'w_unique',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_required',
                'w_unique',
                'w_class',
              );
            }
            if ( strpos($temp, 'w_verification') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_required',
                'w_unique',
                'w_class',
                'w_verification',
                'w_verification_label',
              );
            }
            if ( strpos($temp, 'w_placeholder') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_required',
                'w_unique',
                'w_class',
                'w_verification',
                'w_verification_label',
                'w_placeholder',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $wdformfieldsize = ($param['w_field_label_pos'] == "left" ? $param['w_field_label_size'] + $param['w_size'] + 10 : max($param['w_field_label_size'], $param['w_size']));
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $rep = '<div type="type_password" class="wdform-field" style="width:' . $wdformfieldsize . 'px"><div class="wdform-label-section"  class="' . $param['w_class'] . '" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $message_confirm = addslashes(__("Password values don't match", WDFM()->prefix));
            $onchange = (isset($param['w_verification']) && $param['w_verification'] == "yes") ? ' onchange="wd_check_confirmation_pass(\'' . $id1 . '\', \'' . $form_id . '\', \'' . $message_confirm . '\')"' : "";
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ' width: ' . $param['w_size'] . 'px;"><input type="password" id="wdform_' . $id1 . '_element' . $form_id . '" name="wdform_' . $id1 . '_element' . $form_id . '" placeholder="' . $param['w_placeholder'] . '" style="width: 100%;" ' . $param['attributes'] . $onchange . '></div></div>';
            if ( isset($param['w_verification']) && $param['w_verification'] == "yes" ) {
              $rep .= '<div><div type="type_password_confirmation" class="wdform-field"  style="width:' . $wdformfieldsize . 'px"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $param['w_verification_label'] . '</span>';
              if ( $required ) {
                $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
              }
              $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ' width: ' . $param['w_size'] . 'px;"><input  type="password"  id="wdform_' . $id1 . '_1_element' . $form_id . '" name="wdform_' . $id1 . '_1_element' . $form_id . '" style="width: 100%;" ' . $param['attributes'] . ' onchange="wd_check_confirmation_pass(\'' . $id1 . '\', \'' . $form_id . '\', \'' . $message_confirm . '\')"></div></div></div>';
            }
            break;
          }
          case 'type_textarea': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size_w',
              'w_size_h',
              'w_first_val',
              'w_title',
              'w_required',
              'w_unique',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size_w',
                'w_size_h',
                'w_first_val',
                'w_title',
                'w_required',
                'w_unique',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_first_val'] = (isset($_POST['wdform_' . $id1 . '_element' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element' . $form_id])) : $param['w_first_val']);
            $wdformfieldsize = ($param['w_field_label_pos'] == "left" ? $param['w_field_label_size'] + $param['w_size_w'] + 10 : max($param['w_field_label_size'], $param['w_size_w']));
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $input_active = ($param['w_first_val'] == $param['w_title'] ? "input_deactive" : "input_active");
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $rep = '<div type="type_textarea" class="wdform-field"  style="width:' . $wdformfieldsize . 'px"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $textarea_value = str_replace(array( "\r\n", "\n\r", "\n", "\r" ), "&#13;", $param['w_first_val']);
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ' width: ' . $param['w_size_w'] . 'px"><textarea class="' . $input_active . '" id="wdform_' . $id1 . '_element' . $form_id . '" name="wdform_' . $id1 . '_element' . $form_id . '" placeholder="' . $param['w_title'] . '"  style="width: 100%; height: ' . $param['w_size_h'] . 'px;" ' . $param['attributes'] . '>' . $textarea_value . '</textarea></div></div>';
            break;
          }
          case 'type_phone': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_first_val',
              'w_title',
              'w_mini_labels',
              'w_required',
              'w_unique',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_first_val',
                'w_title',
                'w_mini_labels',
                'w_required',
                'w_unique',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $w_first_val = explode('***', $param['w_first_val']);
            $w_title = explode('***', $param['w_title']);
            $param['w_first_val'] = (isset($_POST['wdform_' . $id1 . '_element_first' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element_first' . $form_id])) : $w_first_val[0]) . '***' . (isset($_POST['wdform_' . $id1 . '_element_last' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element_last' . $form_id])) : $w_first_val[1]);
            $wdformfieldsize = ($param['w_field_label_pos'] == "left" ? ($param['w_field_label_size'] + $param['w_size'] + 65) : max($param['w_field_label_size'], ($param['w_size'] + 65)));
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $input_active = ($param['w_first_val'] == $param['w_title'] ? "input_deactive" : "input_active");
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $w_first_val = explode('***', $param['w_first_val']);
            $w_title = explode('***', $param['w_title']);
            $w_mini_labels = explode('***', $param['w_mini_labels']);
            $rep = '<div type="type_phone" class="wdform-field" style="width:' . $wdformfieldsize . 'px"><div class="wdform-label-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label" >' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '
            </div>
            <div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ' width: ' . ($param['w_size'] + 65) . 'px;">
              <div style="display: table-cell;">
                <div><input type="text" class="' . $input_active . '" id="wdform_' . $id1 . '_element_first' . $form_id . '" name="wdform_' . $id1 . '_element_first' . $form_id . '" value="' . $w_first_val[0] . '" title="' . $w_title[0] . '" placeholder="' . $w_title[0] . '" style="width: 52px;" ' . $param['attributes'] . '></div>
                <div><label class="mini_label">' . $w_mini_labels[0] . '</label></div>
              </div>
              <div style="display: table-cell;">
                <div class="wdform_line" style="margin: 0px 4px 10px 4px; padding: 0px;">-</div>
              </div>
              <div style="display: table-cell; width:100%;">
                <div><input type="text" class="' . $input_active . '" id="wdform_' . $id1 . '_element_last' . $form_id . '" name="wdform_' . $id1 . '_element_last' . $form_id . '" value="' . $w_first_val[1] . '" title="' . $w_title[1] . '" placeholder="' . $w_title[1] . '" style="width: 100%;" ' . $param['attributes'] . '></div>
                <div><label class="mini_label">' . $w_mini_labels[1] . '</label></div>
              </div>
            </div>
            </div>';
            break;
          }
          case 'type_phone_new': {
			wp_enqueue_script('fm-phone_field');
			wp_enqueue_style('fm-phone_field_css');
        
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_hide_label',
              'w_size',
              'w_first_val',
              'w_top_country',
              'w_required',
              'w_unique',
              'w_class',
            );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_first_val'] = (isset($_POST['wdform_' . $id1 . '_element' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element' . $form_id])) : $param['w_first_val']);
            $wdformfieldsize = ($param['w_field_label_pos'] == "left" ? ($param['w_field_label_size'] + $param['w_size'] + 10) : max($param['w_field_label_size'], ($param['w_size'])));
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $rep = '<div type="type_phone_new" class="wdform-field" style="width:' . $wdformfieldsize . 'px"><div class="wdform-label-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label" >' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '
              </div>
              <div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ' width: ' . $param['w_size'] . 'px;">
                <input type="text" class="input_active" id="wdform_' . $id1 . '_element' . $form_id . '" name="wdform_' . $id1 . '_element' . $form_id . '" value="' . $param['w_first_val'] . '"  style="width: 100%;" placeholder="" ' . $param['attributes'] . '>
              </div>
              </div>';
            break;
          }
          case 'type_name': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_first_val',
              'w_title',
              'w_mini_labels',
              'w_size',
              'w_name_format',
              'w_required',
              'w_unique',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_name_fields') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_first_val',
                'w_title',
                'w_mini_labels',
                'w_size',
                'w_name_format',
                'w_required',
                'w_unique',
                'w_class',
                'w_name_fields',
              );
            }
            if ( strpos($temp, 'w_autofill') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_first_val',
                'w_title',
                'w_mini_labels',
                'w_size',
                'w_name_format',
                'w_required',
                'w_unique',
                'w_class',
                'w_name_fields',
                'w_autofill',
              );
            }
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_first_val',
                'w_title',
                'w_mini_labels',
                'w_size',
                'w_name_format',
                'w_required',
                'w_unique',
                'w_class',
                'w_name_fields',
                'w_autofill',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $w_first_val = explode('***', $param['w_first_val']);
            $w_title = explode('***', $param['w_title']);
            $w_mini_labels = explode('***', $param['w_mini_labels']);
            $param['w_name_fields'] = isset($param['w_name_fields']) ? $param['w_name_fields'] : ($param['w_name_format'] == 'normal' ? 'no***no' : 'yes***yes');
            $w_name_fields = explode('***', $param['w_name_fields']);
            $param['w_autofill'] = isset($param['w_autofill']) ? $param['w_autofill'] : 'no';
            $element_title = isset($_POST['wdform_' . $id1 . '_element_title' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element_title' . $form_id])) : NULL;
            $element_middle = isset($_POST['wdform_' . $id1 . '_element_middle' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element_middle' . $form_id])) : NULL;
            $element_first = isset($_POST['wdform_' . $id1 . '_element_first' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element_first' . $form_id])) : NULL;
            if ( isset($element_title) || isset($element_middle) ) {
              $param['w_first_val'] = (isset($_POST['wdform_' . $id1 . '_element_first' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element_first' . $form_id])) : $w_first_val[0]) . '***' . (isset($_POST['wdform_' . $id1 . '_element_last' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element_last' . $form_id])) : $w_first_val[1]) . '***' . (isset($_POST['wdform_' . $id1 . '_element_title' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element_title' . $form_id])) : $w_first_val[2]) . '***' . (isset($_POST['wdform_' . $id1 . '_element_middle' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element_middle' . $form_id])) : $w_first_val[3]);
            }
            else {
              if ( isset($element_first) ) {
                $param['w_first_val'] = (isset($_POST['wdform_' . $id1 . '_element_first' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element_first' . $form_id])) : $w_first_val[0]) . '***' . (isset($_POST['wdform_' . $id1 . '_element_last' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element_last' . $form_id])) : $w_first_val[1]);
              }
            }
            $w_first_val = explode('***', $param['w_first_val']);
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $param['w_autofill'] == 'yes' && $wp_username ) {
              $user_display_name = explode(' ', $wp_username);
              $w_first_val[0] = $user_display_name[0];
              $w_first_val[1] = isset($user_display_name[1]) ? $user_display_name[1] : $w_first_val[1];
            }
            if ( $w_name_fields[0] == 'no' && $w_name_fields[1] == 'no' ) {
              $w_name_format = '
                <div style="display: table-cell; width:50%">
                  <div><input type="text" class="' . ($w_first_val[0] == $w_title[0] ? "input_deactive" : "input_active") . '" id="wdform_' . $id1 . '_element_first' . $form_id . '" name="wdform_' . $id1 . '_element_first' . $form_id . '" value="' . $w_first_val[0] . '" title="' . $w_title[0] . '" placeholder="' . $w_title[0] . '"  style="width: 100%;"' . $param['attributes'] . '></div>
                  <div><label class="mini_label">' . $w_mini_labels[1] . '</label></div>
                </div>
                <div style="display:table-cell;"><div style="margin: 0px 8px; padding: 0px;"></div></div>
                <div  style="display: table-cell; width:50%">
                  <div><input type="text" class="' . ($w_first_val[1] == $w_title[1] ? "input_deactive" : "input_active") . '" id="wdform_' . $id1 . '_element_last' . $form_id . '" name="wdform_' . $id1 . '_element_last' . $form_id . '" value="' . $w_first_val[1] . '" title="' . $w_title[1] . '" placeholder="' . $w_title[1] . '" style="width: 100%;" ' . $param['attributes'] . '></div>
                  <div><label class="mini_label">' . $w_mini_labels[2] . '</label></div>
                </div>
                ';
              $w_size = 2 * $param['w_size'];
            }
            else {
              $first_last_size = $w_name_fields[0] == 'yes' && $w_name_fields[1] == 'no' ? 45 : 30;
              $w_name_format = '
                <div style="display: table-cell; width:' . $first_last_size . '%">
                  <div><input type="text" class="' . ($w_first_val[0] == $w_title[0] ? "input_deactive" : "input_active") . '" id="wdform_' . $id1 . '_element_first' . $form_id . '" name="wdform_' . $id1 . '_element_first' . $form_id . '" value="' . $w_first_val[0] . '" title="' . $w_title[0] . '" placeholder="' . $w_title[0] . '" style="width:100%;"></div>
                  <div><label class="mini_label">' . $w_mini_labels[1] . '</label></div>
                </div>
                <div style="display:table-cell;"><div style="margin: 0px 4px; padding: 0px;"></div></div>
                <div style="display: table-cell; width:' . $first_last_size . '%">
                  <div><input type="text" class="' . ($w_first_val[1] == $w_title[1] ? "input_deactive" : "input_active") . '" id="wdform_' . $id1 . '_element_last' . $form_id . '" name="wdform_' . $id1 . '_element_last' . $form_id . '" value="' . $w_first_val[1] . '" title="' . $w_title[1] . '" placeholder="' . $w_title[1] . '" style="width:  100%;"></div>
                  <div><label class="mini_label">' . $w_mini_labels[2] . '</label></div>
                </div>';
              $w_size = 2 * $param['w_size'];
              if ( $w_name_fields[0] == 'yes' ) {
                $w_name_format = '
                  <div style="display: table-cell;">
                    <div><input type="text" class="' . ($w_first_val[2] == $w_title[2] ? "input_deactive" : "input_active") . '" id="wdform_' . $id1 . '_element_title' . $form_id . '" name="wdform_' . $id1 . '_element_title' . $form_id . '" value="' . $w_first_val[2] . '" title="' . $w_title[2] . '" placeholder="' . $w_title[2] . '" style="width: 40px;"></div>
                    <div><label class="mini_label">' . $w_mini_labels[0] . '</label></div>
                  </div>
                  <div style="display:table-cell;"><div style="margin: 0px 1px; padding: 0px;"></div></div>' . $w_name_format;
                $w_size += 80;
              }
              if ( $w_name_fields[1] == 'yes' ) {
                $w_name_format = $w_name_format . '
                  <div style="display:table-cell;"><div style="margin: 0px 4px; padding: 0px;"></div></div>
                  <div style="display: table-cell; width:30%">
                    <div><input type="text" class="' . ($w_first_val[3] == $w_title[3] ? "input_deactive" : "input_active") . '" id="wdform_' . $id1 . '_element_middle' . $form_id . '" name="wdform_' . $id1 . '_element_middle' . $form_id . '" value="' . $w_first_val[3] . '" title="' . $w_title[3] . '" placeholder="' . $w_title[3] . '" style="width: 100%;"></div>
                    <div><label class="mini_label">' . $w_mini_labels[3] . '</label></div>
                  </div>						
                  ';
                $w_size += $param['w_size'];
              }
            }
            $wdformfieldsize = ($param['w_field_label_pos'] == "left" ? ($param['w_field_label_size'] + $w_size) : max($param['w_field_label_size'], $w_size));
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $rep = '<div type="type_name" class="wdform-field"  style="width:' . $wdformfieldsize . 'px"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div>
            <div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ' width: ' . $w_size . 'px;">' . $w_name_format . '</div></div>';
            break;
          }
          case 'type_address': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_mini_labels',
              'w_disabled_fields',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_mini_labels',
                'w_disabled_fields',
                'w_required',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $wdformfieldsize = ($param['w_field_label_pos'] == "left" ? ($param['w_field_label_size'] + $param['w_size']) : max($param['w_field_label_size'], $param['w_size']));
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $w_mini_labels = explode('***', $param['w_mini_labels']);
            $w_disabled_fields = explode('***', $param['w_disabled_fields']);
            $rep = '<div type="type_address" class="wdform-field"  style="width:' . $wdformfieldsize . 'px"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $address_fields = '';
            $g = 0;
            if ( isset($w_disabled_fields[0]) && $w_disabled_fields[0] == 'no' ) {
              $g += 2;
              $address_fields .= '<span style="float: left; width: 100%; padding-bottom: 8px; display: block;"><input type="text" id="wdform_' . $id1 . '_street1' . $form_id . '" name="wdform_' . $id1 . '_street1' . $form_id . '" value="' . (isset($_POST['wdform_' . $id1 . '_street1' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_street1' . $form_id])) : "") . '" style="width: 100%;" ' . $param['attributes'] . '><label class="mini_label" >' . $w_mini_labels[0] . '</label></span>';
            }
            if ( isset($w_disabled_fields[1]) && $w_disabled_fields[1] == 'no' ) {
              $g += 2;
              $address_fields .= '<span style="float: left; width: 100%; padding-bottom: 8px; display: block;"><input type="text" id="wdform_' . $id1 . '_street2' . $form_id . '" name="wdform_' . ($id1 + 1) . '_street2' . $form_id . '" value="' . (isset($_POST['wdform_' . ($id1 + 1) . '_street2' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . ($id1 + 1) . '_street2' . $form_id])) : "") . '" style="width: 100%;" ' . $param['attributes'] . '><label class="mini_label" >' . $w_mini_labels[1] . '</label></span>';
            }
            if ( isset($w_disabled_fields[2]) && $w_disabled_fields[2] == 'no' ) {
              $g++;
              $address_fields .= '<span style="float: left; width: 48%; padding-bottom: 8px;"><input type="text" id="wdform_' . $id1 . '_city' . $form_id . '" name="wdform_' . ($id1 + 2) . '_city' . $form_id . '" value="' . (isset($_POST['wdform_' . ($id1 + 2) . '_city' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . ($id1 + 2) . '_city' . $form_id])) : "") . '" style="width: 100%;" ' . $param['attributes'] . '><label class="mini_label" >' . $w_mini_labels[2] . '</label></span>';
            }
            if ( isset($w_disabled_fields[3]) && $w_disabled_fields[3] == 'no' ) {
              $g++;
              $w_states = array(
                "",
                "Alabama",
                "Alaska",
                "Arizona",
                "Arkansas",
                "California",
                "Colorado",
                "Connecticut",
                "Delaware",
                "District Of Columbia",
                "Florida",
                "Georgia",
                "Hawaii",
                "Idaho",
                "Illinois",
                "Indiana",
                "Iowa",
                "Kansas",
                "Kentucky",
                "Louisiana",
                "Maine",
                "Maryland",
                "Massachusetts",
                "Michigan",
                "Minnesota",
                "Mississippi",
                "Missouri",
                "Montana",
                "Nebraska",
                "Nevada",
                "New Hampshire",
                "New Jersey",
                "New Mexico",
                "New York",
                "North Carolina",
                "North Dakota",
                "Ohio",
                "Oklahoma",
                "Oregon",
                "Pennsylvania",
                "Rhode Island",
                "South Carolina",
                "South Dakota",
                "Tennessee",
                "Texas",
                "Utah",
                "Vermont",
                "Virginia",
                "Washington",
                "West Virginia",
                "Wisconsin",
                "Wyoming",
              );
              $w_state_options = '';
              $post_state = isset($_POST['wdform_' . ($id1 + 3) . '_state' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . ($id1 + 3) . '_state' . $form_id])) : "";
              foreach ( $w_states as $w_state ) {
                if ( $w_state == $post_state ) {
                  $selected = 'selected="selected"';
                }
                else {
                  $selected = '';
                }
                $w_state_options .= '<option value="' . $w_state . '" ' . $selected . '>' . $w_state . '</option>';
              }
              if ( isset($w_disabled_fields[5]) && $w_disabled_fields[5] == 'yes' && isset($w_disabled_fields[6]) && $w_disabled_fields[6] == 'yes' ) {
                $address_fields .= '<span style="float: ' . (($g % 2 == 0) ? 'right' : 'left') . '; width: 48%; padding-bottom: 8px;"><select type="text" id="wdform_' . $id1 . '_state' . $form_id . '" name="wdform_' . ($id1 + 3) . '_state' . $form_id . '" style="width: 100%;" ' . $param['attributes'] . '>' . $w_state_options . '</select><label class="mini_label" style="display: block;" id="' . $id1 . '_mini_label_state">' . $w_mini_labels[3] . '</label></span>';
              }
              else {
                $address_fields .= '<span style="float: ' . (($g % 2 == 0) ? 'right' : 'left') . '; width: 48%; padding-bottom: 8px;"><input type="text" id="wdform_' . $id1 . '_state' . $form_id . '" name="wdform_' . ($id1 + 3) . '_state' . $form_id . '" value="' . (isset($_POST['wdform_' . ($id1 + 3) . '_state' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . ($id1 + 3) . '_state' . $form_id])) : "") . '" style="width: 100%;" ' . $param['attributes'] . '><label class="mini_label">' . $w_mini_labels[3] . '</label></span>';
              }
            }
            if ( isset($w_disabled_fields[4]) && $w_disabled_fields[4] == 'no' ) {
              $g++;
              $address_fields .= '<span style="float: ' . (($g % 2 == 0) ? 'right' : 'left') . '; width: 48%; padding-bottom: 8px;"><input type="text" id="wdform_' . $id1 . '_postal' . $form_id . '" name="wdform_' . ($id1 + 4) . '_postal' . $form_id . '" value="' . (isset($_POST['wdform_' . ($id1 + 4) . '_postal' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . ($id1 + 4) . '_postal' . $form_id])) : "") . '" style="width: 100%;" ' . $param['attributes'] . '><label class="mini_label">' . $w_mini_labels[4] . '</label></span>';
            }
            $w_countries = array(
              "",
              "Afghanistan",
              "Albania",
              "Algeria",
              "Andorra",
              "Angola",
              "Antigua and Barbuda",
              "Argentina",
              "Armenia",
              "Australia",
              "Austria",
              "Azerbaijan",
              "Bahamas",
              "Bahrain",
              "Bangladesh",
              "Barbados",
              "Belarus",
              "Belgium",
              "Belize",
              "Benin",
              "Bhutan",
              "Bolivia",
              "Bosnia and Herzegovina",
              "Botswana",
              "Brazil",
              "Brunei",
              "Bulgaria",
              "Burkina Faso",
              "Burundi",
              "Cambodia",
              "Cameroon",
              "Canada",
              "Cape Verde",
              "Central African Republic",
              "Chad",
              "Chile",
              "China",
              "Colombia",
              "Comoros",
              "Congo (Brazzaville)",
              "Congo",
              "Costa Rica",
              "Cote d'Ivoire",
              "Croatia",
              "Cuba",
              "Curacao",
              "Cyprus",
              "Czech Republic",
              "Denmark",
              "Djibouti",
              "Dominica",
              "Dominican Republic",
              "East Timor (Timor Timur)",
              "Ecuador",
              "Egypt",
              "El Salvador",
              "Equatorial Guinea",
              "Eritrea",
              "Estonia",
              "Ethiopia",
              "Fiji",
              "Finland",
              "France",
              "Gabon",
              "Gambia, The",
              "Georgia",
              "Germany",
              "Ghana",
              "Greece",
              "Grenada",
              "Guatemala",
              "Guinea",
              "Guinea-Bissau",
              "Guyana",
              "Haiti",
              "Honduras",
              "Hungary",
              "Iceland",
              "India",
              "Indonesia",
              "Iran",
              "Iraq",
              "Ireland",
              "Israel",
              "Italy",
              "Jamaica",
              "Japan",
              "Jordan",
              "Kazakhstan",
              "Kenya",
              "Kiribati",
              "Korea, North",
              "Korea, South",
              "Kuwait",
              "Kyrgyzstan",
              "Laos",
              "Latvia",
              "Lebanon",
              "Lesotho",
              "Liberia",
              "Libya",
              "Liechtenstein",
              "Lithuania",
              "Luxembourg",
              "Macedonia",
              "Madagascar",
              "Malawi",
              "Malaysia",
              "Maldives",
              "Mali",
              "Malta",
              "Marshall Islands",
              "Mauritania",
              "Mauritius",
              "Mexico",
              "Micronesia",
              "Moldova",
              "Monaco",
              "Mongolia",
              "Morocco",
              "Mozambique",
              "Myanmar",
              "Namibia",
              "Nauru",
              "Nepal",
              "Netherlands",
              "New Zealand",
              "Nicaragua",
              "Niger",
              "Nigeria",
              "Norway",
              "Oman",
              "Pakistan",
              "Palau",
              "Panama",
              "Papua New Guinea",
              "Paraguay",
              "Peru",
              "Philippines",
              "Poland",
              "Portugal",
              "Qatar",
              "Romania",
              "Russia",
              "Rwanda",
              "Saint Kitts and Nevis",
              "Saint Lucia",
              "Saint Vincent",
              "Samoa",
              "San Marino",
              "Sao Tome and Principe",
              "Saudi Arabia",
              "Senegal",
              "Serbia and Montenegro",
              "Seychelles",
              "Sierra Leone",
              "Singapore",
              "Slovakia",
              "Slovenia",
              "Solomon Islands",
              "Somalia",
              "South Africa",
              "Spain",
              "Sri Lanka",
              "Sudan",
              "Suriname",
              "Swaziland",
              "Sweden",
              "Switzerland",
              "Syria",
              "Taiwan",
              "Tajikistan",
              "Tanzania",
              "Thailand",
              "Togo",
              "Tonga",
              "Trinidad and Tobago",
              "Tunisia",
              "Turkey",
              "Turkmenistan",
              "Tuvalu",
              "Uganda",
              "Ukraine",
              "United Arab Emirates",
              "United Kingdom",
              "United States",
              "Uruguay",
              "Uzbekistan",
              "Vanuatu",
              "Vatican City",
              "Venezuela",
              "Vietnam",
              "Yemen",
              "Zambia",
              "Zimbabwe",
            );
            $w_options = '';
            $post_country = isset($_POST['wdform_' . ($id1 + 5) . '_country' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . ($id1 + 5) . '_country' . $form_id])) : "";
            foreach ( $w_countries as $w_country ) {
              if ( $w_country == $post_country ) {
                $selected = 'selected="selected"';
              }
              else {
                $selected = '';
              }
              $w_options .= '<option value="' . $w_country . '" ' . $selected . '>' . $w_country . '</option>';
            }
            if ( isset($w_disabled_fields[5]) && $w_disabled_fields[5] == 'no' ) {
              $g++;
              $address_fields .= '<span style="float: ' . (($g % 2 == 0) ? 'right' : 'left') . '; width: 48%; padding-bottom: 8px;display: inline-block;"><select type="text" id="wdform_' . $id1 . '_country' . $form_id . '" name="wdform_' . ($id1 + 5) . '_country' . $form_id . '" style="width:100%" ' . $param['attributes'] . '>' . $w_options . '</select><label class="mini_label">' . $w_mini_labels[5] . '</label></span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ' width: ' . $param['w_size'] . 'px;"><div>
            ' . $address_fields . '</div><div style="clear:both;"></div></div></div>';
            break;
          }
          case 'type_submitter_mail': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_first_val',
              'w_title',
              'w_required',
              'w_unique',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_autofill') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_size',
                'w_first_val',
                'w_title',
                'w_required',
                'w_unique',
                'w_class',
                'w_autofill',
              );
            }
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_first_val',
                'w_title',
                'w_required',
                'w_unique',
                'w_class',
                'w_autofill',
              );
            }
            if ( strpos($temp, 'w_verification') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_first_val',
                'w_title',
                'w_required',
                'w_unique',
                'w_class',
                'w_verification',
                'w_verification_label',
                'w_verification_placeholder',
                'w_autofill',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $wdformfieldsize = ($param['w_field_label_pos'] == "left" ? ($param['w_field_label_size'] + $param['w_size']) : max($param['w_field_label_size'], $param['w_size']));
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $param['w_autofill'] = isset($param['w_autofill']) ? $param['w_autofill'] : 'no';
            if ( $param['w_autofill'] == 'yes' && $wp_useremail ) {
              $param['w_first_val'] = (isset($_POST['wdform_' . $id1 . '_element' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element' . $form_id])) : $wp_useremail);
              $input_active = "input_active";
            }
            else {
              $param['w_first_val'] = (isset($_POST['wdform_' . $id1 . '_element' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element' . $form_id])) : $param['w_first_val']);
              $input_active = ($param['w_first_val'] == $param['w_title'] ? "input_deactive" : "input_active");
            }
            $rep = '<div type="type_submitter_mail" class="wdform-field"  style="width:' . $wdformfieldsize . 'px"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $message_confirm = addslashes(__("The email addresses don't match", WDFM()->prefix));
            $message_check_email = addslashes(__('This is not a valid email address.', WDFM()->prefix));
            $onchange = (isset($param['w_verification']) && $param['w_verification'] == "yes") ? '; wd_check_confirmation_email(\'' . $id1 . '\', \'' . $form_id . '\', \'' . $message_confirm . '\')' : '';
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ' width: ' . $param['w_size'] . 'px;"><input type="text" class="' . $input_active . '" id="wdform_' . $id1 . '_element' . $form_id . '" name="wdform_' . $id1 . '_element' . $form_id . '" value="' . $param['w_first_val'] . '" title="' . $param['w_title'] . '" placeholder="' . $param['w_title'] . '"  style="width: 100%;" ' . $param['attributes'] . ' onchange="wd_check_email(\'' . $id1 . '\', \'' . $form_id . '\', \'' . $message_check_email . '\', \'' . $message_confirm . '\')' . $onchange . '"></div></div>';
            if ( isset($param['w_verification']) && $param['w_verification'] == "yes" ) {
              $param['w_verification_placeholder'] = (isset($_POST['wdform_' . $id1 . '_1_element' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_1_element' . $form_id])) : $param['w_verification_placeholder']);
              $rep .= '<div><div type="type_submitter_mail_confirmation" class="wdform-field"  style="width:' . $wdformfieldsize . 'px"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $param['w_verification_label'] . '</span>';
              if ( $required ) {
                $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
              }
              $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ' width: ' . $param['w_size'] . 'px;"><input type="text" class="' . $input_active . '" id="wdform_' . $id1 . '_1_element' . $form_id . '" name="wdform_' . $id1 . '_1_element' . $form_id . '" placeholder="' . $param['w_verification_placeholder'] . '" title="' . $param['w_verification_placeholder'] . '"  style="width: 100%;" ' . $param['attributes'] . 'onchange="wd_check_confirmation_email(\'' . $id1 . '\', \'' . $form_id . '\', \'' . $message_confirm . '\')"></div></div></div>';
            }
            break;
          }
          case 'type_checkbox': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_flow',
              'w_choices',
              'w_choices_checked',
              'w_rowcol',
              'w_required',
              'w_randomize',
              'w_allow_other',
              'w_allow_other_num',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_field_option_pos') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_field_option_pos',
                'w_flow',
                'w_choices',
                'w_choices_checked',
                'w_rowcol',
                'w_required',
                'w_randomize',
                'w_allow_other',
                'w_allow_other_num',
                'w_value_disabled',
                'w_choices_value',
                'w_choices_params',
                'w_class',
              );
            }
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_field_option_pos',
                'w_hide_label',
                'w_flow',
                'w_choices',
                'w_choices_checked',
                'w_rowcol',
                'w_required',
                'w_randomize',
                'w_allow_other',
                'w_allow_other_num',
                'w_value_disabled',
                'w_choices_value',
                'w_choices_params',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            if ( !isset($param['w_value_disabled']) ) {
              $param['w_value_disabled'] = 'no';
            }
            if ( !isset($param['w_field_option_pos']) ) {
              $param['w_field_option_pos'] = 'left';
            }
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_field_option_pos1'] = ($param['w_field_option_pos'] == "right" ? "style='float: none !important;'" : "");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $class_right = $param['w_field_option_pos'] == 'left' ? 'fm-right' : '';
            $param['w_field_option_pos2'] = ($param['w_field_option_pos'] == "right" ? "style='float: left !important; margin:3px 8px 0 0 !important; display: inline-block !important;'" : "");
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $param['w_choices'] = explode('***', $param['w_choices']);
            $param['w_choices_checked'] = explode('***', $param['w_choices_checked']);
            if ( isset($param['w_choices_value']) ) {
              $param['w_choices_value'] = explode('***', $param['w_choices_value']);
              $param['w_choices_params'] = explode('***', $param['w_choices_params']);
            }
            $post_value = isset($_POST["counter" . $form_id]) ? esc_html($_POST["counter" . $form_id]) : NULL;
            $is_other = FALSE;
            if ( isset($post_value) ) {
              if ( $param['w_allow_other'] == "yes" ) {
                $is_other = FALSE;
                $other_element = isset($_POST['wdform_' . $id1 . "_other_input" . $form_id]) ? esc_html($_POST['wdform_' . $id1 . "_other_input" . $form_id]) : NULL;
                if ( isset($other_element) ) {
                  $is_other = TRUE;
                }
              }
            }
            else {
              $is_other = ($param['w_allow_other'] == "yes" && $param['w_choices_checked'][$param['w_allow_other_num']] == 'true');
            }
            $rep = '<div type="type_checkbox" class="wdform-field"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ';">';
            $rep .= '<div style="display: ' . ($param['w_flow'] == 'hor' ? 'inline-block' : 'table-row') . '; vertical-align:top">';
            $total_queries = 0;
            foreach ( $param['w_choices'] as $key => $choice ) {
              $key1 = $key + $total_queries;
              if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
                $choices_labels = array();
                $choices_values = array();
                $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
                $where = (str_replace(array( '[', ']' ), '', $w_choices_params[0]) ? ' WHERE ' . str_replace(array(
                                                                                                               '[',
                                                                                                               ']',
                                                                                                             ), '', $w_choices_params[0]) : '');
                $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                $order_by = str_replace(array( '[', ']' ), '', $w_choices_params[0]);
                $db_info = str_replace(array( '[', ']' ), '', $w_choices_params[1]);
                $label_table_and_column = explode(':', str_replace(array( '[', ']' ), '', $choice));
                $table = $label_table_and_column[0];
                $label_column = $label_table_and_column[1];
                if ( $label_column ) {
                  $choices_labels = $this->model->select_data_from_db_for_labels($db_info, $label_column, $table, $where, $order_by);
                }
                $value_table_and_column = explode(':', str_replace(array(
                                                                     '[',
                                                                     ']',
                                                                   ), '', $param['w_choices_value'][$key]));
                $value_column = $value_table_and_column[1];
                if ( $value_column ) {
                  $choices_values = $this->model->select_data_from_db_for_values($db_info, $value_column, $table, $where, $order_by);
                }
                $columns_count_checkbox = count($choices_labels) > 0 ? count($choices_labels) : count($choices_values);
                if ( array_filter($choices_labels) || array_filter($choices_values) ) {
                  $total_queries = $total_queries + $columns_count_checkbox - 1;
                  if ( !isset($post_value) ) {
                    $param['w_choices_checked'][$key] = ($param['w_choices_checked'][$key] == 'true' ? 'checked="checked"' : '');
                  }
                  for ( $k = 0; $k < $columns_count_checkbox; $k++ ) {
                    $choice_label = isset($choices_labels[$k]) ? $choices_labels[$k] : '';
                    $choice_value = isset($choices_values[$k]) ? $choices_values[$k] : $choice_label;
                    if ( ($key1 + $k) % $param['w_rowcol'] == 0 && ($key1 + $k) > 0 ) {
                      $rep .= '</div><div style="display: ' . ($param['w_flow'] == 'hor' ? 'inline-block' : 'table-row') . ';  vertical-align:top">';
                    }
                    if ( isset($post_value) ) {
                      $post_valuetemp = $_POST['wdform_' . $id1 . "_element" . $form_id . ($key1 + $k)];
                      $param['w_choices_checked'][$key] = (isset($post_valuetemp) ? 'checked="checked"' : '');
                    }
                    $rep .= '<div style="display: ' . ($param['w_flow'] != 'hor' ? 'table-cell' : 'table-row') . ';"><div class="checkbox-div forlabs ' . $class_right . '" ' . $param['w_field_option_pos2'] . '><input type="checkbox" ' . (($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $key) ? 'other="1"' : '') . ' id="wdform_' . $id1 . '_element' . $form_id . '' . ($key1 + $k) . '" name="wdform_' . $id1 . '_element' . $form_id . '' . ($key1 + $k) . '" value="' . htmlspecialchars($choice_value[0]) . '" ' . (($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $key) ? 'onclick="if(set_checked(&quot;wdform_' . $id1 . '&quot;,&quot;' . ($key1 + $k) . '&quot;,&quot;' . $form_id . '&quot;)) show_other_input(&quot;wdform_' . $id1 . '&quot;,&quot;' . $form_id . '&quot;);"' : '') . ' ' . $param['w_choices_checked'][$key] . ' ' . $param['attributes'] . '><label for="wdform_' . $id1 . '_element' . $form_id . '' . ($key1 + $k) . '"><span></span>' . $choice_label[0] . '</label></div></div>';
                  }
                }
              }
              else {
                if ( $key1 % $param['w_rowcol'] == 0 && $key1 > 0 ) {
                  $rep .= '</div><div style="display: ' . ($param['w_flow'] == 'hor' ? 'inline-block' : 'table-row') . ';  vertical-align:top">';
                }
                if ( !isset($post_value) ) {
                  $param['w_choices_checked'][$key] = ($param['w_choices_checked'][$key] == 'true' ? 'checked="checked"' : '');
                }
                else {
                  $post_valuetemp = isset($_POST['wdform_' . $id1 . "_element" . $form_id . $key]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . "_element" . $form_id . $key])) : NULL;
                  $param['w_choices_checked'][$key] = (isset($post_valuetemp) ? 'checked="checked"' : '');
                }
                $choice_value = isset($param['w_choices_value']) ? $param['w_choices_value'][$key] : $choice;
                $rep .= '<div style="display: ' . ($param['w_flow'] != 'hor' ? 'table-cell' : 'table-row') . ';"><div class="checkbox-div forlabs ' . $class_right . '" ' . $param['w_field_option_pos2'] . '><input type="checkbox" ' . (($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $key) ? 'other="1"' : '') . ' id="wdform_' . $id1 . '_element' . $form_id . '' . $key1 . '" name="wdform_' . $id1 . '_element' . $form_id . '' . $key1 . '" value="' . htmlspecialchars($choice_value) . '" ' . (($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $key) ? 'onclick="if(set_checked(&quot;wdform_' . $id1 . '&quot;,&quot;' . $key1 . '&quot;,&quot;' . $form_id . '&quot;)) show_other_input(&quot;wdform_' . $id1 . '&quot;,&quot;' . $form_id . '&quot;);"' : '') . ' ' . $param['w_choices_checked'][$key] . ' ' . $param['attributes'] . '><label for="wdform_' . $id1 . '_element' . $form_id . '' . $key1 . '"><span></span>' . $choice . '</label></div></div>';
                $param['w_allow_other_num'] = $param['w_allow_other_num'] == $key ? $key1 : $param['w_allow_other_num'];
              }
            }
            $rep .= '</div>';
            $rep .= '</div></div>';
            break;
          }
          case 'type_radio': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_flow',
              'w_choices',
              'w_choices_checked',
              'w_rowcol',
              'w_required',
              'w_randomize',
              'w_allow_other',
              'w_allow_other_num',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_field_option_pos') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_field_option_pos',
                'w_flow',
                'w_choices',
                'w_choices_checked',
                'w_rowcol',
                'w_required',
                'w_randomize',
                'w_allow_other',
                'w_allow_other_num',
                'w_value_disabled',
                'w_choices_value',
                'w_choices_params',
                'w_class',
              );
            }
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_field_option_pos',
                'w_hide_label',
                'w_flow',
                'w_choices',
                'w_choices_checked',
                'w_rowcol',
                'w_required',
                'w_randomize',
                'w_allow_other',
                'w_allow_other_num',
                'w_value_disabled',
                'w_choices_value',
                'w_choices_params',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            if ( !isset($param['w_value_disabled']) ) {
              $param['w_value_disabled'] = 'no';
            }
            if ( !isset($param['w_field_option_pos']) ) {
              $param['w_field_option_pos'] = 'left';
            }
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_field_option_pos1'] = ($param['w_field_option_pos'] == "right" ? "style='float: none !important;'" : "");
            $param['w_field_option_pos2'] = ($param['w_field_option_pos'] == "right" ? "style='float: left !important; margin:3px 8px 0 0 !important; display: inline-block !important;'" : "");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $class_right = $param['w_field_option_pos'] == 'left' ? 'fm-right' : '';
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $param['w_choices'] = explode('***', $param['w_choices']);
            $param['w_choices_checked'] = explode('***', $param['w_choices_checked']);
            if ( isset($param['w_choices_value']) ) {
              $param['w_choices_value'] = explode('***', $param['w_choices_value']);
              $param['w_choices_params'] = explode('***', $param['w_choices_params']);
            }
            $post_value = isset($_POST["counter" . $form_id]) ? esc_html($_POST["counter" . $form_id]) : NULL;
            $is_other = FALSE;
            if ( isset($post_value) ) {
              if ( $param['w_allow_other'] == "yes" ) {
                $is_other = FALSE;
                $other_element = isset($_POST['wdform_' . $id1 . "_other_input" . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . "_other_input" . $form_id])) : NULL;
                if ( isset($other_element) ) {
                  $is_other = TRUE;
                }
              }
            }
            else {
              $is_other = ($param['w_allow_other'] == "yes" && $param['w_choices_checked'][$param['w_allow_other_num']] == 'true');
            }
            $rep = '<div type="type_radio" class="wdform-field"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ';">';
            $rep .= '<div style="display: ' . ($param['w_flow'] == 'hor' ? 'inline-block' : 'table-row') . '; vertical-align:top">';
            $total_queries = 0;
            foreach ( $param['w_choices'] as $key => $choice ) {
              $key1 = $key + $total_queries;
              if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
                $choices_labels = array();
                $choices_values = array();
                $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
                $where = (str_replace(array( '[', ']' ), '', $w_choices_params[0]) ? ' WHERE ' . str_replace(array(
                                                                                                               '[',
                                                                                                               ']',
                                                                                                             ), '', $w_choices_params[0]) : '');
                $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                $order_by = str_replace(array( '[', ']' ), '', $w_choices_params[0]);
                $db_info = str_replace(array( '[', ']' ), '', $w_choices_params[1]);
                $label_table_and_column = explode(':', str_replace(array( '[', ']' ), '', $choice));
                $table = $label_table_and_column[0];
                $label_column = $label_table_and_column[1];
                if ( $label_column ) {
                  $choices_labels = $this->model->select_data_from_db_for_labels($db_info, $label_column, $table, $where, $order_by);
                }
                $value_table_and_column = explode(':', str_replace(array(
                                                                     '[',
                                                                     ']',
                                                                   ), '', $param['w_choices_value'][$key]));
                $value_column = $value_table_and_column[1];
                if ( $value_column ) {
                  $choices_values = $this->model->select_data_from_db_for_values($db_info, $value_column, $table, $where, $order_by);
                }
                $columns_count_radio = count($choices_labels) > 0 ? count($choices_labels) : count($choices_values);
                if ( array_filter($choices_labels) || array_filter($choices_values) ) {
                  $total_queries = $total_queries + $columns_count_radio - 1;
                  if ( !isset($post_value) ) {
                    $param['w_choices_checked'][$key] = ($param['w_choices_checked'][$key] == 'true' ? 'checked="checked"' : '');
                  }
                  for ( $k = 0; $k < $columns_count_radio; $k++ ) {
                    $choice_label = isset($choices_labels[$k]) ? $choices_labels[$k] : '';
                    $choice_value = isset($choices_values[$k]) ? $choices_values[$k] : $choice_label;
                    if ( ($key1 + $k) % $param['w_rowcol'] == 0 && ($key1 + $k) > 0 ) {
                      $rep .= '</div><div style="display: ' . ($param['w_flow'] == 'hor' ? 'inline-block' : 'table-row') . ';  vertical-align:top">';
                    }
                    if ( isset($post_value) ) {
                      $post_valuetemp = $_POST['wdform_' . $id1 . "_element" . $form_id];
                      $param['w_choices_checked'][$key] = (isset($post_valuetemp) ? 'checked="checked"' : '');
                    }
                    $rep .= '<div style="display: ' . ($param['w_flow'] != 'hor' ? 'table-cell' : 'table-row') . ';"><div class="radio-div forlabs ' . $class_right . '" ' . $param['w_field_option_pos2'] . '><input type="radio" ' . (($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $key) ? 'other="1"' : '') . ' id="wdform_' . $id1 . '_element' . $form_id . '' . ($key1 + $k) . '" name="wdform_' . $id1 . '_element' . $form_id . '" value="' . htmlspecialchars($choice_value[0]) . '" onclick="set_default(&quot;wdform_' . $id1 . '&quot;,&quot;' . ($key1 + $k) . '&quot;,&quot;' . $form_id . '&quot;); ' . (($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $key) ? 'show_other_input(&quot;wdform_' . $id1 . '&quot;,&quot;' . $form_id . '&quot;);' : '') . '" ' . $param['w_choices_checked'][$key] . ' ' . $param['attributes'] . '><label for="wdform_' . $id1 . '_element' . $form_id . '' . ($key1 + $k) . '"><span></span>' . $choice_label[0] . '</label></div></div>';
                  }
                }
              }
              else {
                if ( $key1 % $param['w_rowcol'] == 0 && $key1 > 0 ) {
                  $rep .= '</div><div style="display: ' . ($param['w_flow'] == 'hor' ? 'inline-block' : 'table-row') . ';  vertical-align:top">';
                }
                if ( !isset($post_value) ) {
                  $param['w_choices_checked'][$key] = ($param['w_choices_checked'][$key] == 'true' ? 'checked="checked"' : '');
                }
                else {
                  $param['w_choices_checked'][$key] = (htmlspecialchars($choice) == htmlspecialchars(isset($_POST['wdform_' . $id1 . "_element" . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . "_element" . $form_id])) : "") ? 'checked="checked"' : '');
                }
                $choice_value = isset($param['w_choices_value']) ? $param['w_choices_value'][$key] : $choice;
                $rep .= '<div style="display: ' . ($param['w_flow'] != 'hor' ? 'table-cell' : 'table-row') . ';"><div class="radio-div forlabs ' . $class_right . '" ' . $param['w_field_option_pos2'] . '><input type="radio" ' . (($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $key) ? 'other="1"' : '') . ' id="wdform_' . $id1 . '_element' . $form_id . '' . $key1 . '" name="wdform_' . $id1 . '_element' . $form_id . '" value="' . htmlspecialchars($choice_value) . '" onclick="set_default(&quot;wdform_' . $id1 . '&quot;,&quot;' . $key1 . '&quot;,&quot;' . $form_id . '&quot;); ' . (($param['w_allow_other'] == "yes" && $param['w_allow_other_num'] == $key) ? 'show_other_input(&quot;wdform_' . $id1 . '&quot;,&quot;' . $form_id . '&quot;);' : '') . '" ' . $param['w_choices_checked'][$key] . ' ' . $param['attributes'] . '><label for="wdform_' . $id1 . '_element' . $form_id . '' . $key1 . '"><span></span>' . $choice . '</label></div></div>';
                $param['w_allow_other_num'] = $param['w_allow_other_num'] == $key ? $key1 : $param['w_allow_other_num'];
              }
            }
            $rep .= '</div>';
            $rep .= '</div></div>';
            break;
          }
          case 'type_own_select': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_choices',
              'w_choices_checked',
              'w_choices_disabled',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_choices_value') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_size',
                'w_choices',
                'w_choices_checked',
                'w_choices_disabled',
                'w_required',
                'w_value_disabled',
                'w_choices_value',
                'w_choices_params',
                'w_class',
              );
            }
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_choices',
                'w_choices_checked',
                'w_choices_disabled',
                'w_required',
                'w_value_disabled',
                'w_choices_value',
                'w_choices_params',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $wdformfieldsize = ($param['w_field_label_pos'] == "left" ? ($param['w_field_label_size'] + $param['w_size']) : max($param['w_field_label_size'], $param['w_size']));
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $param['w_choices'] = explode('***', $param['w_choices']);
            $param['w_choices_checked'] = explode('***', $param['w_choices_checked']);
            $param['w_choices_disabled'] = explode('***', $param['w_choices_disabled']);
            if ( isset($param['w_choices_value']) ) {
              $param['w_choices_value'] = explode('***', $param['w_choices_value']);
              $param['w_choices_params'] = explode('***', $param['w_choices_params']);
            }
            if ( !isset($param['w_value_disabled']) ) {
              $param['w_value_disabled'] = 'no';
            }
            $post_value = isset($_POST["counter" . $form_id]) ? esc_html($_POST["counter" . $form_id]) : NULL;
            $rep = '<div type="type_own_select" class="wdform-field"  style="width:' . $wdformfieldsize . 'px"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ' width: ' . ($param['w_size']) . 'px; "><select id="wdform_' . $id1 . '_element' . $form_id . '" name="wdform_' . $id1 . '_element' . $form_id . '" style="width: 100%"  ' . $param['attributes'] . '>';
            foreach ( $param['w_choices'] as $key => $choice ) {
              if ( isset($param['w_choices_params']) && $param['w_choices_params'][$key] ) {
                $choices_labels = array();
                $choices_values = array();
                $w_choices_params = explode('[where_order_by]', $param['w_choices_params'][$key]);
                $where = (str_replace(array( '[', ']' ), '', $w_choices_params[0]) ? ' WHERE ' . str_replace(array(
                                                                                                               '[',
                                                                                                               ']',
                                                                                                             ), '', $w_choices_params[0]) : '');
                $w_choices_params = explode('[db_info]', $w_choices_params[1]);
                $order_by = str_replace(array( '[', ']' ), '', $w_choices_params[0]);
                $db_info = str_replace(array( '[', ']' ), '', $w_choices_params[1]);
                $label_table_and_column = explode(':', str_replace(array( '[', ']' ), '', $choice));
                $table = $label_table_and_column[0];
                $label_column = $label_table_and_column[1];
                if ( $label_column ) {
                  $choices_labels = $this->model->select_data_from_db_for_labels($db_info, $label_column, $table, $where, $order_by);
                }
                $value_table_and_column = explode(':', str_replace(array(
                                                                     '[',
                                                                     ']',
                                                                   ), '', $param['w_choices_value'][$key]));
                $value_column = $param['w_choices_disabled'][$key] == "true" ? '' : $value_table_and_column[1];
                if ( $value_column ) {
                  $choices_values = $this->model->select_data_from_db_for_values($db_info, $value_column, $table, $where, $order_by);
                }
                $columns_count = count($choices_labels) > 0 ? count($choices_labels) : count($choices_values);
                if ( array_filter($choices_labels) || array_filter($choices_values) ) {
                  for ( $k = 0; $k < $columns_count; $k++ ) {
                    $choice_label = isset($choices_labels[$k]) ? $choices_labels[$k] : '';
                    $choice_value = isset($choices_values[$k]) ? $choices_values[$k] : ($param['w_choices_disabled'][$key] == "true" ? '' : $choice_label);
                    if ( !isset($post_value) ) {
                      $param['w_choices_checked'][$key] = (($param['w_choices_checked'][$key] == 'true' && $k == 0) ? 'selected="selected"' : '');
                    }
                    else {
                      $param['w_choices_checked'][$key] = ($choice_value == htmlspecialchars($_POST['wdform_' . $id1 . "_element" . $form_id]) ? 'selected="selected"' : '');
                    }
                    $rep .= '<option value="' . htmlspecialchars($choice_value[0]) . '" ' . $param['w_choices_checked'][$key] . '>' . $choice_label[0] . '</option>';
                  }
                }
              }
              else {
                if ( !isset($post_value) ) {
                  $param['w_choices_checked'][$key] = ($param['w_choices_checked'][$key] == 'true' ? 'selected="selected"' : '');
                }
                else {
                  $param['w_choices_checked'][$key] = (htmlspecialchars($choice) == htmlspecialchars($_POST['wdform_' . $id1 . "_element" . $form_id]) ? 'selected="selected"' : '');
                }
                $choice_value = $param['w_choices_disabled'][$key] == "true" ? '' : (isset($param['w_choices_value']) ? $param['w_choices_value'][$key] : $choice);
                $rep .= '<option value="' . htmlspecialchars($choice_value) . '" ' . $param['w_choices_checked'][$key] . '>' . $choice . '</option>';
              }
            }
            $rep .= '</select></div></div>';
            break;
          }
          case 'type_country': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_countries',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_countries',
                'w_required',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $wdformfieldsize = ($param['w_field_label_pos'] == "left" ? ($param['w_field_label_size'] + $param['w_size']) : max($param['w_field_label_size'], $param['w_size']));
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $param['w_countries'] = explode('***', $param['w_countries']);
            $post_value = isset($_POST["counter" . $form_id]) ? esc_html($_POST["counter" . $form_id]) : NULL;
            $selected = '';
            $rep = '<div type="type_country" class="wdform-field"  style="width:' . $wdformfieldsize . 'px"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ' width: ' . $param['w_size'] . 'px;"><select id="wdform_' . $id1 . '_element' . $form_id . '" name="wdform_' . $id1 . '_element' . $form_id . '" style="width: 100%;"  ' . $param['attributes'] . '>';
            foreach ( $param['w_countries'] as $key => $choice ) {
              if ( isset($post_value) ) {
                $selected = (htmlspecialchars($choice) == htmlspecialchars(isset($_POST['wdform_' . $id1 . "_element" . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . "_element" . $form_id])) : "") ? 'selected="selected"' : '');
              }
              $choice_value = $choice;
              $rep .= '<option value="' . $choice_value . '" ' . $selected . '>' . $choice . '</option>';
            }
            $rep .= '</select></div></div>';
            break;
          }
          case 'type_time': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_time_type',
              'w_am_pm',
              'w_sec',
              'w_hh',
              'w_mm',
              'w_ss',
              'w_mini_labels',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_time_type',
                'w_am_pm',
                'w_sec',
                'w_hh',
                'w_mm',
                'w_ss',
                'w_mini_labels',
                'w_required',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $w_mini_labels = explode('***', $param['w_mini_labels']);
            $w_sec = '';
            $w_sec_label = '';
            if ( $param['w_sec'] == '1' ) {
              $w_sec = '<div align="center" style="display: table-cell;"><span class="wdform_colon" style="vertical-align: middle;">&nbsp;:&nbsp;</span></div><div style="display: table-cell;"><input type="text" value="' . (isset($_POST['wdform_' . $id1 . "_ss" . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . "_ss" . $form_id])) : $param['w_ss']) . '" class="time_box" id="wdform_' . $id1 . '_ss' . $form_id . '" name="wdform_' . $id1 . '_ss' . $form_id . '" onkeypress="return check_second(event, &quot;wdform_' . $id1 . '_ss' . $form_id . '&quot;)" ' . $param['attributes'] . '></div>';
              $w_sec_label = '<div style="display: table-cell;"></div><div style="display: table-cell;"><label class="mini_label">' . $w_mini_labels[2] . '</label></div>';
            }
            if ( $param['w_time_type'] == '12' ) {
              if ( (isset($_POST['wdform_' . $id1 . "_am_pm" . $form_id]) ? esc_html($_POST['wdform_' . $id1 . "_am_pm" . $form_id]) : $param['w_am_pm']) == 'am' ) {
                $am_ = "selected=\"selected\"";
                $pm_ = "";
              }
              else {
                $am_ = "";
                $pm_ = "selected=\"selected\"";
              }
              $w_time_type = '<div style="display: table-cell;"><select class="am_pm_select" name="wdform_' . $id1 . '_am_pm' . $form_id . '" id="wdform_' . $id1 . '_am_pm' . $form_id . '" ' . $param['attributes'] . '><option value="am" ' . $am_ . '>AM</option><option value="pm" ' . $pm_ . '>PM</option></select></div>';
              $w_time_type_label = '<div ><label class="mini_label">' . $w_mini_labels[3] . '</label></div>';
            }
            else {
              $w_time_type = '';
              $w_time_type_label = '';
            }
            $rep = '<div type="type_time" class="wdform-field"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ';"><div style="display: table;"><div style="display: table-row;"><div style="display: table-cell;"><input type="text" value="' . (isset($_POST['wdform_' . $id1 . "_hh" . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . "_hh" . $form_id])) : $param['w_hh']) . '" class="time_box" id="wdform_' . $id1 . '_hh' . $form_id . '" name="wdform_' . $id1 . '_hh' . $form_id . '" onkeypress="return check_hour(event, &quot;wdform_' . $id1 . '_hh' . $form_id . '&quot;, &quot;23&quot;)" ' . $param['attributes'] . '></div><div align="center" style="display: table-cell;"><span class="wdform_colon" style="vertical-align: middle;">&nbsp;:&nbsp;</span></div><div style="display: table-cell;"><input type="text" value="' . (isset($_POST['wdform_' . $id1 . "_mm" . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . "_mm" . $form_id])) : $param['w_mm']) . '" class="time_box" id="wdform_' . $id1 . '_mm' . $form_id . '" name="wdform_' . $id1 . '_mm' . $form_id . '" onkeypress="return check_minute(event, &quot;wdform_' . $id1 . '_mm' . $form_id . '&quot;)" ' . $param['attributes'] . '></div>' . $w_sec . $w_time_type . '</div><div style="display: table-row;"><div style="display: table-cell;"><label class="mini_label">' . $w_mini_labels[0] . '</label></div><div style="display: table-cell;"></div><div style="display: table-cell;"><label class="mini_label">' . $w_mini_labels[1] . '</label></div>' . $w_sec_label . $w_time_type_label . '</div></div></div></div>';
            break;
          }
          case 'type_date': {
			wp_enqueue_script('jquery-ui-datepicker');
			
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_date',
              'w_required',
              'w_class',
              'w_format',
              'w_but_val',
            );
            $temp = $params;
            if ( strpos($temp, 'w_disable_past_days') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_date',
                'w_required',
                'w_class',
                'w_format',
                'w_but_val',
                'w_disable_past_days',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $param['w_disable_past_days'] = isset($param['w_disable_past_days']) ? $param['w_disable_past_days'] : 'no';
            $disable_past_days = $param['w_disable_past_days'] == 'yes' ? 'true' : 'false';
            $param['w_date'] = (isset($_POST['wdform_' . $id1 . "_element" . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . "_element" . $form_id])) : $param['w_date']);
            $rep = '<div type="type_date" class="wdform-field"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . ' width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ';"><input type="text" value="' . $param['w_date'] . '" class="wdform-date wd-datepicker" data-format="' . $param['w_format'] . '" id="wdform_' . $id1 . '_element' . $form_id . '" name="wdform_' . $id1 . '_element' . $form_id . '" maxlength="10" ' . $param['attributes'] . '></div></div>';
            break;
          }
          case 'type_date_new': {
			wp_enqueue_script('jquery-ui-datepicker');
            
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_date',
              'w_required',
              'w_show_image',
              'w_class',
              'w_format',
              'w_start_day',
              'w_default_date',
              'w_min_date',
              'w_max_date',
              'w_invalid_dates',
              'w_show_days',
              'w_hide_time',
              'w_but_val',
              'w_disable_past_days',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_date',
                'w_required',
                'w_show_image',
                'w_class',
                'w_format',
                'w_start_day',
                'w_default_date',
                'w_min_date',
                'w_max_date',
                'w_invalid_dates',
                'w_show_days',
                'w_hide_time',
                'w_but_val',
                'w_disable_past_days',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $show_image = ($param['w_show_image'] == "yes" ? "inline-block" : "none");
            $div_size = ($show_image == "inline-block" ? $param['w_size'] + 22 : $param['w_size']);
            $input_size = ($show_image == "inline-block" ? "calc(100% - 22px)" : "100%");
            $default_date = (isset($_POST['wdform_' . $id1 . "_element" . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . "_element" . $form_id])) : $param['w_default_date']);
            $rep = '<div type="type_date_new" class="wdform-field"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . ' width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '" style="width:' . $div_size . 'px; ' . $param['w_field_label_pos2'] . ' "><input type="text"  id="wdform_' . $id1 . '_element' . $form_id . '" class="input_active" style="width:' . $input_size . '" name="wdform_' . $id1 . '_element' . $form_id . '"  ' . $param['attributes'] . '><span id="fm-calendar-' . $id1 . '" class="wdform-calendar-button" style="display:' . $show_image . ' "></span><input type="hidden"  format="' . $param['w_format'] . '" id="wdform_' . $id1 . '_button' . $form_id . '" value="' . $default_date . '"/></div></div>';
            break;
          }
          case 'type_date_range': {
           	wp_enqueue_script('jquery-ui-datepicker');
			
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_size',
              'w_date',
              'w_required',
              'w_show_image',
              'w_class',
              'w_format',
              'w_start_day',
              'w_default_date_start',
              'w_default_date_end',
              'w_min_date',
              'w_max_date',
              'w_invalid_dates',
              'w_show_days',
              'w_hide_time',
              'w_but_val',
              'w_disable_past_days',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_size',
                'w_date',
                'w_required',
                'w_show_image',
                'w_class',
                'w_format',
                'w_start_day',
                'w_default_date_start',
                'w_default_date_end',
                'w_min_date',
                'w_max_date',
                'w_invalid_dates',
                'w_show_days',
                'w_hide_time',
                'w_but_val',
                'w_disable_past_days',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $show_image = ($param['w_show_image'] == "yes" ? "inline-block" : "none");
            $input_size = $param['w_size'];
            $param['w_size'] = ($show_image == "inline-block" ? $param['w_size'] * 2 + 44 : $param['w_size'] * 2 + 8);
            $input_size = ($show_image == "inline-block" ? "calc(50% - 26px)" : "calc(50% - 4px)");
            if ( $param['w_default_date_start'] == 'today' ) {
              $default_date_start = 'new Date()';
            }
            else {
              $default_date_start = $param['w_default_date_start'];
            }
            $rep = '<div type="type_date_range" class="wdform-field"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . ' width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '" style="width:' . $param['w_size'] . 'px; ' . $param['w_field_label_pos2'] . ' "><input type="text"   class="input_active"  id="wdform_' . $id1 . '_element' . $form_id . '0" style="width:' . $input_size . '" name="wdform_' . $id1 . '_element' . $form_id . '0"  ' . $param['attributes'] . ' onchange="change_value_range(\'wdform_' . $id1 . '_element' . $form_id . '1\', \'minDate\', this.value, \'' . $param['w_min_date'] . '\', \'' . $param['w_format'] . '\')"><img src="' . WDFM()->plugin_url . '/images/date.png" style="display:' . $show_image . '; vertical-align:sub" id="button_calendar_' . $id1 . '0" /><span>-</span><input type="text"  class="input_active"  id="wdform_' . $id1 . '_element' . $form_id . '1" style="width:' . $input_size . '" name="wdform_' . $id1 . '_element' . $form_id . '1"  ' . $param['attributes'] . ' onchange="change_value_range(\'wdform_' . $id1 . '_element' . $form_id . '0\', \'maxDate\', this.value, \'' . $param['w_max_date'] . '\', \'' . $param['w_format'] . '\')"><img src="' . WDFM()->plugin_url . '/images/date.png" style="display:' . $show_image . '; vertical-align:sub" id="button_calendar_' . $id1 . '1" /><input type="hidden" format="' . $param['w_format'] . '" id="wdform_' . $id1 . '_button' . $form_id . '" default_date_start="' . $param['w_default_date_start'] . '" default_date_end="' . $param['w_default_date_end'] . '"/></div></div>';
            break;
          }
          case 'type_date_fields': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_day',
              'w_month',
              'w_year',
              'w_day_type',
              'w_month_type',
              'w_year_type',
              'w_day_label',
              'w_month_label',
              'w_year_label',
              'w_day_size',
              'w_month_size',
              'w_year_size',
              'w_required',
              'w_class',
              'w_from',
              'w_to',
              'w_divider',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_day',
                'w_month',
                'w_year',
                'w_day_type',
                'w_month_type',
                'w_year_type',
                'w_day_label',
                'w_month_label',
                'w_year_label',
                'w_day_size',
                'w_month_size',
                'w_year_size',
                'w_required',
                'w_class',
                'w_from',
                'w_to',
                'w_divider',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_day'] = (isset($_POST['wdform_' . $id1 . "_day" . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . "_day" . $form_id])) : $param['w_day']);
            $param['w_month'] = (isset($_POST['wdform_' . $id1 . "_month" . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . "_month" . $form_id])) : $param['w_month']);
            $param['w_year'] = (isset($_POST['wdform_' . $id1 . "_year" . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . "_year" . $form_id])) : $param['w_year']);
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            if ( $param['w_day_type'] == "SELECT" ) {
              $w_day_type = '<select id="wdform_' . $id1 . '_day' . $form_id . '" name="wdform_' . $id1 . '_day' . $form_id . '" style="width: ' . $param['w_day_size'] . 'px;" ' . $param['attributes'] . '><option value=""></option>';
              for ( $k = 1; $k <= 31; $k++ ) {
                if ( $k < 10 ) {
                  if ( $param['w_day'] == '0' . $k ) {
                    $selected = "selected=\"selected\"";
                  }
                  else {
                    $selected = "";
                  }
                  $w_day_type .= '<option value="0' . $k . '" ' . $selected . '>0' . $k . '</option>';
                }
                else {
                  if ( $param['w_day'] == '' . $k ) {
                    $selected = "selected=\"selected\"";
                  }
                  else {
                    $selected = "";
                  }
                  $w_day_type .= '<option value="' . $k . '" ' . $selected . '>' . $k . '</option>';
                }
              }
              $w_day_type .= '</select>';
            }
            else {
              $w_day_type = '<input type="text" value="' . $param['w_day'] . '" id="wdform_' . $id1 . '_day' . $form_id . '" name="wdform_' . $id1 . '_day' . $form_id . '" style="width: ' . $param['w_day_size'] . 'px;" ' . $param['attributes'] . '>';
            }
            if ( $param['w_month_type'] == "SELECT" ) {
              $w_month_type = '<select id="wdform_' . $id1 . '_month' . $form_id . '" name="wdform_' . $id1 . '_month' . $form_id . '" style="width: ' . $param['w_month_size'] . 'px;" ' . $param['attributes'] . '><option value=""></option><option value="01" ' . ($param['w_month'] == "01" ? "selected=\"selected\"" : "") . '  >' . (__("January", WDFM()->prefix)) . '</option><option value="02" ' . ($param['w_month'] == "02" ? "selected=\"selected\"" : "") . '>' . (__("February", WDFM()->prefix)) . '</option><option value="03" ' . ($param['w_month'] == "03" ? "selected=\"selected\"" : "") . '>' . (__("March", WDFM()->prefix)) . '</option><option value="04" ' . ($param['w_month'] == "04" ? "selected=\"selected\"" : "") . ' >' . (__("April", WDFM()->prefix)) . '</option><option value="05" ' . ($param['w_month'] == "05" ? "selected=\"selected\"" : "") . ' >' . (__("May", WDFM()->prefix)) . '</option><option value="06" ' . ($param['w_month'] == "06" ? "selected=\"selected\"" : "") . ' >' . (__("June", WDFM()->prefix)) . '</option><option value="07" ' . ($param['w_month'] == "07" ? "selected=\"selected\"" : "") . ' >' . (__("July", WDFM()->prefix)) . '</option><option value="08" ' . ($param['w_month'] == "08" ? "selected=\"selected\"" : "") . ' >' . (__("August", WDFM()->prefix)) . '</option><option value="09" ' . ($param['w_month'] == "09" ? "selected=\"selected\"" : "") . ' >' . (__("September", WDFM()->prefix)) . '</option><option value="10" ' . ($param['w_month'] == "10" ? "selected=\"selected\"" : "") . ' >' . (__("October", WDFM()->prefix)) . '</option><option value="11" ' . ($param['w_month'] == "11" ? "selected=\"selected\"" : "") . '>' . (__("November", WDFM()->prefix)) . '</option><option value="12" ' . ($param['w_month'] == "12" ? "selected=\"selected\"" : "") . ' >' . (__("December", WDFM()->prefix)) . '</option></select>';
            }
            else {
              $w_month_type = '<input type="text" value="' . $param['w_month'] . '" id="wdform_' . $id1 . '_month' . $form_id . '" name="wdform_' . $id1 . '_month' . $form_id . '"  style="width: ' . $param['w_day_size'] . 'px;" ' . $param['attributes'] . '>';
            }
            $param['w_to'] = isset($param['w_to']) && $param['w_to'] != "NaN" ? $param['w_to'] : date("Y");
            if ( $param['w_year_type'] == "SELECT" ) {
              $w_year_type = '<select id="wdform_' . $id1 . '_year' . $form_id . '" name="wdform_' . $id1 . '_year' . $form_id . '"  from="' . $param['w_from'] . '" to="' . $param['w_to'] . '" style="width: ' . $param['w_year_size'] . 'px;" ' . $param['attributes'] . '><option value=""></option>';
              for ( $k = $param['w_to']; $k >= $param['w_from']; $k-- ) {
                if ( $param['w_year'] == $k ) {
                  $selected = "selected=\"selected\"";
                }
                else {
                  $selected = "";
                }
                $w_year_type .= '<option value="' . $k . '" ' . $selected . '>' . $k . '</option>';
              }
              $w_year_type .= '</select>';
            }
            else {
              $w_year_type = '<input type="text" value="' . $param['w_year'] . '" id="wdform_' . $id1 . '_year' . $form_id . '" name="wdform_' . $id1 . '_year' . $form_id . '" from="' . $param['w_from'] . '" to="' . $param['w_to'] . '" style="width: ' . $param['w_day_size'] . 'px;" ' . $param['attributes'] . '>';
            }
            $rep = '<div type="type_date_fields" class="wdform-field"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ';"><div style="display: table;"><div style="display: table-row;"><div style="display: table-cell;">' . $w_day_type . '</div><div style="display: table-cell;"><span class="wdform_separator">' . $param['w_divider'] . '</span></div><div style="display: table-cell;">' . $w_month_type . '</div><div style="display: table-cell;"><span class="wdform_separator">' . $param['w_divider'] . '</span></div><div style="display: table-cell;">' . $w_year_type . '</div></div><div style="display: table-row;"><div style="display: table-cell;"><label class="mini_label">' . $param['w_day_label'] . '</label></div><div style="display: table-cell;"></div><div style="display: table-cell;"><label class="mini_label" >' . $param['w_month_label'] . '</label></div><div style="display: table-cell;"></div><div style="display: table-cell;"><label class="mini_label">' . $param['w_year_label'] . '</label></div></div></div></div></div>';
            break;
          }
          case 'type_file_upload': {
            $rep = $this->type_file_upload($params, $label, $required_sym, $id1, $form_id, $param);
            break;
          }
          case 'type_captcha': {
            $params_names = array( 'w_field_label_size', 'w_field_label_pos', 'w_digit', 'w_class' );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array( 'w_field_label_size', 'w_field_label_pos', 'w_hide_label', 'w_digit', 'w_class' );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $rep = '<div type="type_captcha" class="wdform-field"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . ' width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span></div><div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . '"><div style="display: table;"><div style="display: table-cell;vertical-align: middle;"><div valign="middle" style="display: table-cell; text-align: center;"><img type="captcha" digit="' . $param['w_digit'] . '" src=" ' . add_query_arg(array(
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             'action' => 'formmakerwdcaptcha',
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             'digit' => $param['w_digit'],
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             'i' => $form_id,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           ), admin_url('admin-ajax.php')) . '" id="wd_captcha' . $form_id . '" class="captcha_img" style="display:none" ' . $param['attributes'] . '></div><div valign="middle" style="display: table-cell;"><div class="captcha_refresh" id="_element_refresh' . $form_id . '" ' . $param['attributes'] . '></div></div></div><div style="display: table-cell;vertical-align: middle;"><div style="display: table-cell;"><input type="text" class="captcha_input" id="wd_captcha_input' . $form_id . '" name="captcha_input" style="width: ' . ($param['w_digit'] * 10 + 15) . 'px;" ' . $param['attributes'] . '></div></div></div></div></div>';
            break;
          }
          case 'type_arithmetic_captcha': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_count',
              'w_operations',
              'w_class',
              'w_input_size',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_count',
                'w_operations',
                'w_class',
                'w_input_size',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' add_' . $attr;
              }
            }
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $param['w_count'] = $param['w_count'] ? $param['w_count'] : 1;
            $param['w_operations'] = $param['w_operations'] ? $param['w_operations'] : '+, -, *, /';
            $param['w_input_size'] = $param['w_input_size'] ? $param['w_input_size'] : 60;
            $rep = '<div type="type_arithmetic_captcha" class="wdform-field"><div align="left" class="wdform-label-section" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label" style="vertical-align: top;">' . $label . '</span></div><div class="wdform-element-section ' . $param['w_class'] . '" style="display: ' . $param['w_field_label_pos2'] . ';"><div style="display: table;"><div style="display: table-row;"><div style="display: table-cell; vertical-align: middle;"><img type="captcha" operations_count="' . $param['w_count'] . '" operations="' . $param['w_operations'] . '" src="' . add_query_arg(array(
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               'action' => 'formmakerwdmathcaptcha',
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               'operations_count' => $param['w_count'],
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               'operations' => urlencode($param['w_operations']),
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               'i' => $form_id,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             ), admin_url('admin-ajax.php')) . '" id="wd_arithmetic_captcha' . $form_id . '" class="arithmetic_captcha_img" ' . $param['attributes'] . '></div><div style="display: table-cell;"><input type="text" class="arithmetic_captcha_input" id="wd_arithmetic_captcha_input' . $form_id . '" name="arithmetic_captcha_input" onkeypress="return check_isnum(event)" style="width: ' . $param['w_input_size'] . 'px;" ' . $param['attributes'] . '/></div><div style="display: table-cell; vertical-align: middle;"><div class="captcha_refresh" id="_element_refresh' . $form_id . '" ' . $param['attributes'] . '></div></div></div></div></div></div>';
            break;
          }
          case 'type_recaptcha': {
            $params_names = array( 'w_field_label_size', 'w_field_label_pos', 'w_public', 'w_private', 'w_class' );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_public',
                'w_private',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $publickey = isset($fm_settings['public_key']) ? $fm_settings['public_key'] : '0';
			wp_enqueue_script('fm-g-recaptcha');
                   
            $rep = '<div type="type_recaptcha" class="wdform-field">
<div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;">
<span class="wdform-label">' . $label . '</span>
</div>
<div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ';">
<div id="recaptcha' . $id1 . '" class="g-recaptcha" data-sitekey="' . $publickey . '">
</div>
</div>
</div>';
            break;
          }
          case 'type_hidden': {
            $params_names = array( 'w_name', 'w_value' );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $rep = '<div type="type_hidden" class="wdform-field"><div class="wdform-label-section" style="display: table-cell;"></div><div class="wdform-element-section" style="display: table-cell;"><input type="hidden" value="' . $param['w_value'] . '" id="wdform_' . $id1 . '_element' . $form_id . '" name="' . $param['w_name'] . '" ' . $param['attributes'] . '></div></div>';
            break;
          }
          case 'type_mark_map': {
            
			wp_enqueue_script('google-maps');
			wp_enqueue_script('fm-gmap_form');
			
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_center_x',
              'w_center_y',
              'w_long',
              'w_lat',
              'w_zoom',
              'w_width',
              'w_height',
              'w_info',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_center_x',
                'w_center_y',
                'w_long',
                'w_lat',
                'w_zoom',
                'w_width',
                'w_height',
                'w_info',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $wdformfieldsize = ($param['w_field_label_pos'] == "left" ? ($param['w_field_label_size'] + $param['w_width']) : max($param['w_field_label_size'], $param['w_width']));
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $rep = '<div type="type_mark_map" class="wdform-field" style="width:' . $wdformfieldsize . 'px"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span></div><div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ' width: ' . $param['w_width'] . 'px;"><input type="hidden" id="wdform_' . $id1 . '_long' . $form_id . '" name="wdform_' . $id1 . '_long' . $form_id . '" value="' . $param['w_long'] . '"><input type="hidden" id="wdform_' . $id1 . '_lat' . $form_id . '" name="wdform_' . $id1 . '_lat' . $form_id . '" value="' . $param['w_lat'] . '"><div id="wdform_' . $id1 . '_element' . $form_id . '" long0="' . $param['w_long'] . '" lat0="' . $param['w_lat'] . '" zoom="' . $param['w_zoom'] . '" info0="' . str_replace(array(
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    "\r\n",
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    "\n",
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    "\r",
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  ), '<br />', $param['w_info']) . '" center_x="' . $param['w_center_x'] . '" center_y="' . $param['w_center_y'] . '" style="width: 100%; height: ' . $param['w_height'] . 'px;" ' . $param['attributes'] . '></div></div></div>	';
            break;
          }
          case 'type_map': {
            $rep = $this->type_map($params, $label, $id1, $form_id, $param);
            break;
          }
          case 'type_paypal_price': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_first_val',
              'w_title',
              'w_mini_labels',
              'w_size',
              'w_required',
              'w_hide_cents',
              'w_class',
              'w_range_min',
              'w_range_max',
            );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $w_first_val = explode('***', $param['w_first_val']);
            $w_title = explode('***', $param['w_title']);
            $param['w_first_val'] = (isset($_POST['wdform_' . $id1 . '_element_dollars' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element_dollars' . $form_id])) : $w_first_val[0]) . '***' . (isset($_POST['wdform_' . $id1 . '_element_cents' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element_cents' . $form_id])) : $w_first_val[1]);
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $input_active = ($param['w_first_val'] == $param['w_title'] ? "input_deactive" : "input_active");
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $hide_cents = ($param['w_hide_cents'] == "yes" ? "none;" : "table-cell;");
            $w_first_val = explode('***', $param['w_first_val']);
            $w_title = explode('***', $param['w_title']);
            $w_mini_labels = explode('***', $param['w_mini_labels']);
            $rep = '<div type="type_paypal_price" class="wdform-field"><div class="wdform-label-section" style="' . $param['w_field_label_pos1'] . '; width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos2'] . ';"><input type="hidden" value="' . $param['w_range_min'] . '" name="wdform_' . $id1 . '_range_min' . $form_id . '" id="wdform_' . $id1 . '_range_min' . $form_id . '"><input type="hidden" value="' . $param['w_range_max'] . '" name="wdform_' . $id1 . '_range_max' . $form_id . '" id="wdform_' . $id1 . '_range_max' . $form_id . '"><div id="wdform_' . $id1 . '_table_price" style="display: table;"><div id="wdform_' . $id1 . '_tr_price1" style="display: table-row;"><div id="wdform_' . $id1 . '_td_name_currency" style="display: table-cell;"><span class="wdform_colon" style="vertical-align: middle;"><!--repstart-->&nbsp;' . $form_currency . '&nbsp;<!--repend--></span></div><div id="wdform_' . $id1 . '_td_name_dollars" style="display: table-cell;"><input type="text" class="' . $input_active . '" id="wdform_' . $id1 . '_element_dollars' . $form_id . '" name="wdform_' . $id1 . '_element_dollars' . $form_id . '" value="' . $w_first_val[0] . '" title="' . $w_title[0] . '" onkeypress="return check_isnum(event)" style="width: ' . $param['w_size'] . 'px;" ' . $param['attributes'] . '></div><div id="wdform_' . $id1 . '_td_name_divider" style="display: ' . $hide_cents . ';"><span class="wdform_colon" style="vertical-align: middle;">&nbsp;.&nbsp;</span></div><div id="wdform_' . $id1 . '_td_name_cents" style="display: ' . $hide_cents . '"><input type="text" class="' . $input_active . '" id="wdform_' . $id1 . '_element_cents' . $form_id . '" name="wdform_' . $id1 . '_element_cents' . $form_id . '" value="' . $w_first_val[1] . '" title="' . $w_title[1] . '" style="width: 30px;" ' . $param['attributes'] . '></div></div><div id="wdform_' . $id1 . '_tr_price2" style="display: table-row;"><div style="display: table-cell;"><label class="mini_label"></label></div><div align="left" style="display: table-cell;"><label class="mini_label">' . $w_mini_labels[0] . '</label></div><div id="wdform_' . $id1 . '_td_name_label_divider" style="display: ' . $hide_cents . '"><label class="mini_label"></label></div><div align="left" id="wdform_' . $id1 . '_td_name_label_cents" style="display: ' . $hide_cents . '"><label class="mini_label">' . $w_mini_labels[1] . '</label></div></div></div></div></div>';
            break;
          }
          case 'type_paypal_price_new': {
            $rep = $this->type_paypal_price_new($params, $label, $required_sym, $id1, $form_id, $param, $form_currency, $symbol_begin, $symbol_end);
            break;
          }
          case 'type_paypal_select': {
            $rep = $this->type_paypal_select($params, $label, $required_sym, $id1, $form_id, $param);
            break;
          }
          case 'type_paypal_checkbox': {
            $rep = $this->type_paypal_checkbox($params, $label, $required_sym, $id1, $form_id, $param);
            break;
          }
          case 'type_paypal_radio': {
            $rep = $this->type_paypal_radio($params, $label, $required_sym, $id1, $form_id, $param);
            break;
          }
          case 'type_paypal_shipping': {
            $rep = $this->type_paypal_shipping($params, $label, $required_sym, $id1, $form_id, $param);
            break;
          }
          case 'type_submit_reset': {
            $params_names = array( 'w_submit_title', 'w_reset_title', 'w_class', 'w_act' );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_act'] = ($param['w_act'] == "false" ? 'style="display: none;"' : "");
            $rep = '<div type="type_submit_reset" class="wdform-field fm-subscribe-reset"><div class="wdform-label-section" style="display: table-cell;"></div><div class="wdform-element-section ' . $param['w_class'] . '" style="display: table-cell;"><button type="button" class="button-submit" onclick="fm_submit_form(\'' . $form_id . '\');" ' . $param['attributes'] . '>' . $param['w_submit_title'] . '</button><button type="button" class="button-reset" onclick="fm_reset_form(' . $form_id . ');" ' . $param['w_act'] . ' ' . $param['attributes'] . '>' . $param['w_reset_title'] . '</button></div></div>';
            break;
          }
          case 'type_button': {
            $params_names = array( 'w_title', 'w_func', 'w_class' );
            $temp = $params;
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_title'] = explode('***', $param['w_title']);
            $param['w_func'] = explode('***', $param['w_func']);
            $rep .= '<div type="type_button" class="wdform-field"><div class="wdform-label-section" style="display: table-cell;"><span style="display: none;">button_' . $id1 . '</span></div><div class="wdform-element-section ' . $param['w_class'] . '" style="display: table-cell;">';
            foreach ( $param['w_title'] as $key => $title ) {
              $rep .= '<button type="button" name="wdform_' . $id1 . '_element' . $form_id . '' . $key . '" onclick="' . $param['w_func'][$key] . '" ' . $param['attributes'] . '>' . $title . '</button>';
            }
            $rep .= '</div></div>';
            break;
          }
          case 'type_star_rating': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_field_label_col',
              'w_star_amount',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_field_label_col',
                'w_star_amount',
                'w_required',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $images = '';
            for ( $i = 0; $i < $param['w_star_amount']; $i++ ) {
              $images .= '<img id="wdform_' . $id1 . '_star_' . $i . '_' . $form_id . '" src="' . WDFM()->plugin_url . '/images/star.png" >';
            }
            $rep = '<div type="type_star_rating" class="wdform-field"><div class="wdform-label-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos1'] . ' width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '"  style="' . $param['w_field_label_pos2'] . '"><div id="wdform_' . $id1 . '_element' . $form_id . '" ' . $param['attributes'] . '>' . $images . '</div><input type="hidden" value="" id="wdform_' . $id1 . '_selected_star_amount' . $form_id . '" name="wdform_' . $id1 . '_selected_star_amount' . $form_id . '"></div></div>';
            break;
          }
          case 'type_scale_rating': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_mini_labels',
              'w_scale_amount',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_mini_labels',
                'w_scale_amount',
                'w_required',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $w_mini_labels = explode('***', $param['w_mini_labels']);
            $numbers = '';
            $radio_buttons = '';
            $to_check = 0;
            $post_value = isset($_POST['wdform_' . $id1 . '_scale_radio' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_scale_radio' . $form_id])) : NULL;
            if ( isset($post_value) ) {
              $to_check = $post_value;
            }
            for ( $i = 1; $i <= $param['w_scale_amount']; $i++ ) {
              $numbers .= '<div  style="text-align: center; display: table-cell;"><span>' . $i . '</span></div>';
              $radio_buttons .= '<div style="text-align: center; display: table-cell;"><div class="radio-div"><input id="wdform_' . $id1 . '_scale_radio' . $form_id . '_' . $i . '" name="wdform_' . $id1 . '_scale_radio' . $form_id . '" value="' . $i . '" type="radio" ' . ($to_check == $i ? 'checked="checked"' : '') . '><label for="wdform_' . $id1 . '_scale_radio' . $form_id . '_' . $i . '"><span></span></label></div></div>';
            }
            $rep = '<div type="type_scale_rating" class="wdform-field"><div class="wdform-label-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos1'] . ' width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '"  style="' . $param['w_field_label_pos2'] . '"><div id="wdform_' . $id1 . '_element' . $form_id . '" style="float: left;" ' . $param['attributes'] . '><label class="mini_label">' . $w_mini_labels[0] . '</label><div  style="display: inline-table; vertical-align: middle;border-spacing: 7px;"><div style="display: table-row;">' . $numbers . '</div><div style="display: table-row;">' . $radio_buttons . '</div></div><label class="mini_label" >' . $w_mini_labels[1] . '</label></div></div></div>';
            break;
          }
          case 'type_spinner': {
            wp_enqueue_script('jquery-ui-spinner');
            wp_enqueue_style('fm-jquery-ui-spinner');
            
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_field_width',
              'w_field_min_value',
              'w_field_max_value',
              'w_field_step',
              'w_field_value',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_field_width',
                'w_field_min_value',
                'w_field_max_value',
                'w_field_step',
                'w_field_value',
                'w_required',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $param['w_field_value'] = (isset($_POST['wdform_' . $id1 . '_element' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element' . $form_id])) : $param['w_field_value']);
            $rep = '<div type="type_spinner" class="wdform-field"><div class="wdform-label-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos1'] . ' width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '"  style="' . $param['w_field_label_pos2'] . '"><input type="text" value="' . ($param['w_field_value'] != 'null' ? $param['w_field_value'] : '') . '" name="wdform_' . $id1 . '_element' . $form_id . '" id="wdform_' . $id1 . '_element' . $form_id . '" style="width: ' . $param['w_field_width'] . 'px;" ' . $param['attributes'] . '></div></div>';
            break;
          }
          case 'type_slider': {
            
			wp_enqueue_script('jquery-ui-slider');
            
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_field_width',
              'w_field_min_value',
              'w_field_max_value',
              'w_field_value',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_field_width',
                'w_field_min_value',
                'w_field_max_value',
                'w_field_value',
                'w_required',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $param['w_field_value'] = (isset($_POST['wdform_' . $id1 . '_slider_value' . $form_id]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_slider_value' . $form_id])) : $param['w_field_value']);
            $rep = '<div type="type_slider" class="wdform-field"><div class="wdform-label-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos1'] . ' width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '"  style="' . $param['w_field_label_pos2'] . '"><input type="hidden" value="' . $param['w_field_value'] . '" id="wdform_' . $id1 . '_slider_value' . $form_id . '" name="wdform_' . $id1 . '_slider_value' . $form_id . '"><div name="' . $id1 . '_element' . $form_id . '" id="wdform_' . $id1 . '_element' . $form_id . '" style="width: ' . $param['w_field_width'] . 'px;" ' . $param['attributes'] . '"></div><div align="left" style="display: inline-block; width: 33.3%; text-align: left;"><span id="wdform_' . $id1 . '_element_min' . $form_id . '" class="label">' . $param['w_field_min_value'] . '</span></div><div align="right" style="display: inline-block; width: 33.3%; text-align: center;"><span id="wdform_' . $id1 . '_element_value' . $form_id . '" class="label">' . $param['w_field_value'] . '</span></div><div align="right" style="display: inline-block; width: 33.3%; text-align: right;"><span id="wdform_' . $id1 . '_element_max' . $form_id . '" class="label">' . $param['w_field_max_value'] . '</span></div></div></div>';
            break;
          }
          case 'type_range': {
            wp_enqueue_script('jquery-ui-spinner');
            wp_enqueue_style('fm-jquery-ui-spinner');

            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_field_range_width',
              'w_field_range_step',
              'w_field_value1',
              'w_field_value2',
              'w_mini_labels',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_field_range_width',
                'w_field_range_step',
                'w_field_value1',
                'w_field_value2',
                'w_mini_labels',
                'w_required',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $param['w_field_value1'] = (isset($_POST['wdform_' . $id1 . '_element' . $form_id . '0']) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element' . $form_id . '0'])) : $param['w_field_value1']);
            $param['w_field_value2'] = (isset($_POST['wdform_' . $id1 . '_element' . $form_id . '1']) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element' . $form_id . '1'])) : $param['w_field_value2']);
            $w_mini_labels = explode('***', $param['w_mini_labels']);
            $rep = '<div type="type_range" class="wdform-field"><div class="wdform-label-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos1'] . ' width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '"  style="' . $param['w_field_label_pos2'] . '"><div style="display: table;"><div style="display: table-row;"><div valign="middle" align="left" style="display: table-cell;"><input type="text" value="' . ($param['w_field_value1'] != 'null' ? $param['w_field_value1'] : '') . '" name="wdform_' . $id1 . '_element' . $form_id . '0" id="wdform_' . $id1 . '_element' . $form_id . '0" style="width: ' . $param['w_field_range_width'] . 'px;"  ' . $param['attributes'] . '></div><div valign="middle" align="left" style="display: table-cell; padding-left: 4px;"><input type="text" value="' . ($param['w_field_value2'] != 'null' ? $param['w_field_value2'] : '') . '" name="wdform_' . $id1 . '_element' . $form_id . '1" id="wdform_' . $id1 . '_element' . $form_id . '1" style="width: ' . $param['w_field_range_width'] . 'px;" ' . $param['attributes'] . '></div></div><div style="display: table-row;"><div valign="top" align="left" style="display: table-cell;"><label class="mini_label" id="wdform_' . $id1 . '_mini_label_from">' . $w_mini_labels[0] . '</label></div><div valign="top" align="left" style="display: table-cell;"><label class="mini_label" id="wdform_' . $id1 . '_mini_label_to">' . $w_mini_labels[1] . '</label></div></div></div></div></div>';
            break;
          }
          case 'type_grading': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_items',
              'w_total',
              'w_required',
              'w_class',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_items',
                'w_total',
                'w_required',
                'w_class',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $w_items = explode('***', $param['w_items']);
            $w_items_labels = implode(':', $w_items);
            $grading_items = '';
            for ( $i = 0; $i < count($w_items); $i++ ) {
              $value = (isset($_POST['wdform_' . $id1 . '_element' . $form_id . '_' . $i]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_element' . $form_id . '_' . $i])) : '');
              $grading_items .= '<div class="wdform_grading"><input type="text" id="wdform_' . $id1 . '_element' . $form_id . '_' . $i . '" name="wdform_' . $id1 . '_element' . $form_id . '_' . $i . '"  value="' . $value . '" ' . $param['attributes'] . '><label class="wdform-ch-rad-label" for="wdform_' . $id1 . '_element' . $form_id . '_' . $i . '">' . $w_items[$i] . '</label></div>';
            }
            $rep = '<div type="type_grading" class="wdform-field"><div class="wdform-label-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos1'] . ' width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '"  style="' . $param['w_field_label_pos2'] . '"><input type="hidden" value="' . $param['w_total'] . '" name="wdform_' . $id1 . '_grading_total' . $form_id . '" id="wdform_' . $id1 . '_grading_total' . $form_id . '"><div id="wdform_' . $id1 . '_element' . $form_id . '">' . $grading_items . '<div id="wdform_' . $id1 . '_element_total_div' . $form_id . '" class="grading_div">Total: <span id="wdform_' . $id1 . '_sum_element' . $form_id . '">0</span>/<span id="wdform_' . $id1 . '_total_element' . $form_id . '">' . $param['w_total'] . '</span><span id="wdform_' . $id1 . '_text_element' . $form_id . '"></span></div></div></div></div>';
            break;
          }
          case 'type_matrix': {
            $params_names = array(
              'w_field_label_size',
              'w_field_label_pos',
              'w_field_input_type',
              'w_rows',
              'w_columns',
              'w_required',
              'w_class',
              'w_textbox_size',
            );
            $temp = $params;
            if ( strpos($temp, 'w_hide_label') > -1 ) {
              $params_names = array(
                'w_field_label_size',
                'w_field_label_pos',
                'w_hide_label',
                'w_field_input_type',
                'w_rows',
                'w_columns',
                'w_required',
                'w_class',
                'w_textbox_size',
              );
            }
            foreach ( $params_names as $params_name ) {
              $temp = explode('*:*' . $params_name . '*:*', $temp);
              $param[$params_name] = $temp[0];
              $temp = $temp[1];
            }
            if ( $temp ) {
              $temp = explode('*:*w_attr_name*:*', $temp);
              $attrs = array_slice($temp, 0, count($temp) - 1);
              foreach ( $attrs as $attr ) {
                $param['attributes'] = $param['attributes'] . ' ' . $attr;
              }
            }
            $param['w_field_label_pos1'] = ($param['w_field_label_pos'] == "left" ? "float: left;" : "");
            $param['w_field_label_pos2'] = ($param['w_field_label_pos'] == "left" ? "" : "display:block;");
            $param['w_hide_label'] = (isset($param['w_hide_label']) ? $param['w_hide_label'] : "no");
            if ( $param['w_hide_label'] == "yes" ) {
              $param['w_field_label_pos1'] = "display:none;";
            }
            $required = ($param['w_required'] == "yes" ? TRUE : FALSE);
            $param['w_textbox_size'] = isset($param['w_textbox_size']) ? $param['w_textbox_size'] : '120';
            $w_rows = explode('***', $param['w_rows']);
            $w_columns = explode('***', $param['w_columns']);
            $column_labels = '';
            for ( $i = 1; $i < count($w_columns); $i++ ) {
              $column_labels .= '<div><label class="wdform-ch-rad-label">' . $w_columns[$i] . '</label></div>';
            }
            $rows_columns = '';
            for ( $i = 1; $i < count($w_rows); $i++ ) {
              $rows_columns .= '<div class="wdform-matrix-row' . ($i % 2) . '" row="' . $i . '"><div class="wdform-matrix-column"><label class="wdform-ch-rad-label" >' . $w_rows[$i] . '</label></div>';
              for ( $k = 1; $k < count($w_columns); $k++ ) {
                $rows_columns .= '<div class="wdform-matrix-cell">';
                if ( $param['w_field_input_type'] == 'radio' ) {
                  $to_check = 0;
                  $post_value = isset($_POST['wdform_' . $id1 . '_input_element' . $form_id . '' . $i]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_input_element' . $form_id . '' . $i])) : NULL;
                  if ( isset($post_value) ) {
                    $to_check = $post_value;
                  }
                  $rows_columns .= '<div class="radio-div"><input id="wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k . '"  type="radio" name="wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '" value="' . $i . '_' . $k . '" ' . ($to_check == $i . '_' . $k ? 'checked="checked"' : '') . '><label for="wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k . '"><span></span></label></div>';
                }
                else {
                  if ( $param['w_field_input_type'] == 'checkbox' ) {
                    $to_check = 0;
                    $post_value = isset($_POST['wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k])) : NULL;
                    if ( isset($post_value) ) {
                      $to_check = $post_value;
                    }
                    $rows_columns .= '<div class="checkbox-div"><input id="wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k . '" type="checkbox" name="wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k . '" value="1" ' . ($to_check == "1" ? 'checked="checked"' : '') . '><label for="wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k . '"><span></span></label></div>';
                  }
                  else {
                    if ( $param['w_field_input_type'] == 'text' ) {
                      $rows_columns .= '<input id="wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k . '" type="text" name="wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k . '" value="' . (isset($_POST['wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k]) ? esc_html(stripslashes($_POST['wdform_' . $id1 . '_input_element' . $form_id . '' . $i . '_' . $k])) : "") . '" style="width:' . $param['w_textbox_size'] . 'px">';
                    }
                    else {
                      if ( $param['w_field_input_type'] == 'select' ) {
                        $rows_columns .= '<select id="wdform_' . $id1 . '_select_yes_no' . $form_id . '' . $i . '_' . $k . '" name="wdform_' . $id1 . '_select_yes_no' . $form_id . '' . $i . '_' . $k . '" ><option value="" ' . (isset($_POST['wdform_' . $id1 . '_select_yes_no' . $form_id . '' . $i . '_' . $k]) && esc_html($_POST['wdform_' . $id1 . '_select_yes_no' . $form_id . '' . $i . '_' . $k]) == "" ? "selected=\"selected\"" : "") . '> </option><option value="yes" ' . (isset($_POST['wdform_' . $id1 . '_select_yes_no' . $form_id . '' . $i . '_' . $k]) && esc_html($_POST['wdform_' . $id1 . '_select_yes_no' . $form_id . '' . $i . '_' . $k]) == "yes" ? "selected=\"selected\"" : "") . '>Yes</option><option value="no" ' . (isset($_POST['wdform_' . $id1 . '_select_yes_no' . $form_id . '' . $i . '_' . $k]) && esc_html($_POST['wdform_' . $id1 . '_select_yes_no' . $form_id . '' . $i . '_' . $k]) == "no" ? "selected=\"selected\"" : "") . '>No</option></select>';
                      }
                    }
                  }
                }
                $rows_columns .= '</div>';
              }
              $rows_columns .= '</div>';
            }
            $rep = '<div type="type_matrix" class="wdform-field"><div class="wdform-label-section ' . $param['w_class'] . '" style="' . $param['w_field_label_pos1'] . ' width: ' . $param['w_field_label_size'] . 'px;"><span class="wdform-label">' . $label . '</span>';
            if ( $required ) {
              $rep .= '<span class="wdform-required">' . $required_sym . '</span>';
            }
            $rep .= '</div><div class="wdform-element-section ' . $param['w_class'] . '"  style="' . $param['w_field_label_pos2'] . '"><div id="wdform_' . $id1 . '_element' . $form_id . '" class="wdform-matrix-table" ' . $param['attributes'] . '><div style="display: table-row-group;"><div class="wdform-matrix-head"><div style="display: table-cell;"></div>' . $column_labels . '</div>' . $rows_columns . '</div></div></div></div>';
            break;
          }
          case 'type_paypal_total': {
            $rep = $this->type_paypal_total($params, $label, $id1, $form_id, $param);
            break;
          }
          case 'type_stripe': {
            /* get stripe add-on form */
            $stripe_data = apply_filters('fm_addon_stripe_form_init', array('form_id' => $form_id, 'attributes' => $params, 'input_index' => $id1, 'required_sym' => $required_sym));
            $rep .= !empty($stripe_data['html']) ? $stripe_data['html'] : '';
          }
            break;
        }
        $form = str_replace('%' . $id1 . ' - ' . $labels[$id1s_key] . '%', $rep, $form);
        $form = str_replace('%' . $id1 . ' -' . $labels[$id1s_key] . '%', $rep, $form);
      }
    }
    $rep1 = array( 'form_id_temp' );
    $rep2 = array( $form_id );
    $form = str_replace($rep1, $rep2, $form);
    if ( !$fm_hide_form_after_submit ) {
      $form_maker_front_end .= $form;
    }
    if ( isset($form_theme['HPAlign']) && ($form_theme['HPAlign'] == 'right' || $form_theme['HPAlign'] == 'bottom') ) {
      if ( $row->header_title || $row->header_description || $row->header_image_url ) {
        $form_maker_front_end .= '<div class="fm-header-bg"><div class="fm-header ' . $image_pos . '">';
        if ( $form_theme['HIPAlign'] == 'left' || $form_theme['HIPAlign'] == 'top' ) {
          if ( $row->header_image_url ) {
            $form_maker_front_end .= '<div class="fm-header-img ' . $hide_header_image_class . ' fm-animated ' . $header_image_animation . '"><img src="' . $row->header_image_url . '" ' . $image_width . ' ' . $image_height . '/></div>';
          }
        }
        if ( $row->header_title || $row->header_description ) {
          $form_maker_front_end .= '<div class="fm-header-text">
              <div class="fm-header-title">
                ' . $row->header_title . '
              </div>
              <div class="fm-header-description">
                ' . $row->header_description . '
              </div>
            </div>';
        }
        if ( $form_theme['HIPAlign'] == 'right' || $form_theme['HIPAlign'] == 'bottom' ) {
          if ( $row->header_image_url ) {
            $form_maker_front_end .= '<div class="fm-header-img"><img src="' . $row->header_image_url . '" ' . $image_width . ' ' . $image_height . '/></div>';
          }
        }
        $form_maker_front_end .= '</div></div>';
      }
    }
    $form_maker_front_end .= '<div class="wdform_preload"></div>';
    $form_maker_front_end .= '</form>';
    $jsversion = $row->jsversion ? $row->jsversion : 1;

    WDW_FM_Library::create_js($form_id);
    wp_register_script('fm-script-' . $form_id, WDFM()->plugin_url . '/js/frontend/fm-script-' . $form_id . '.js', array(), $jsversion);
	wp_enqueue_script('fm-script-' . $form_id);

    $_GET['addon_view'] = 'frontend';
    $_GET['form_id'] = $form_id;
    do_action('WD_FM_SAVE_PROG_init');

    return $formType == 'embedded' ? WDW_FM_Library::fm_container($theme_id, $form_maker_front_end) : $form_maker_front_end;
  }

  /**
   * Autoload form.
   *
   * @return string
   */
  public function autoload_form($id, $form, $type, $form_html, $display_on_this, $message, $error, $show_for_admin) {
    $onload_js = '';
    $fm_form = '';
    switch ($type) {
      case 'topbar': {
        $top_bottom = $form->topbar_position ? 'top' : 'bottom';
        $fixed_relative = !$form->topbar_remain_top && $form->topbar_position ? 'absolute' : 'fixed';
        $closing = $form->topbar_closing;
        $hide_duration = $form->topbar_hide_duration;
        $hide_mobile = wp_is_mobile() && $form->hide_mobile ? FALSE : TRUE;
        if ($display_on_this && $hide_mobile) {
          if (isset($_SESSION['fm_hide_form_after_submit' . $id]) && $_SESSION['fm_hide_form_after_submit' . $id] == 1) {
            if ($error == 'success') {
              if ($message) {
                $onload_js .= '
								jQuery("#fm-form' . $id . '").css("display", "none");
								jQuery("#fm-pages' . $id . '").css("display", "none");
								jQuery("#fm-topbar' . $id . '").css("visibility", "");
								fm_hide_form(' . $id . ', ' . $hide_duration . ');';
              }
              else {
                $onload_js .= '
								fm_hide_form(' . $id . ', ' . $hide_duration . ');';
              }
            }
          }
          else {
            $onload_js .= '
								if (' . $hide_duration . ' == 0) {
									localStorage.removeItem("hide-"+' . $id . ');
								}
								var hide_topbar = localStorage.getItem("hide-"+' . $id . ');
								if(hide_topbar == null || fm_currentDate.getTime() >= hide_topbar || ' . $show_for_admin . '){
									jQuery("#fm-topbar' . $id . '").css("visibility", "");
									jQuery("#fm-topbar' . $id . ' .fm-header-img").addClass("fm-animated ' . ($form->header_image_animation) . '");
								}';
          }

          $fm_form .= '<div id="fm-topbar' . $id . '" class="fm-topbar" style="position: ' . $fixed_relative . '; ' . $top_bottom . ': 0px; visibility:hidden;">';
          $fm_form .= $form_html;
          $fm_form .= '<div id="fm-action-buttons' . $id . '" class="fm-action-buttons">';
          if ($closing) {
            $fm_form .= '<span id="closing-form' . $id . '" class="closing-form dashicons dashicons-no" onclick="fm_hide_form(' . $id . ', ' . $hide_duration . ', function(){
									jQuery(\'#fm-topbar' . $id . '\').css(\'display\', \'none\');
								})">
							  </span>';
          }
          $fm_form .= '</div>';
          $fm_form .= '</div>';
          /* one more closing div for closing buttons */
        }
        break;
      }
      case 'scrollbox': {
        $left_right = $form->scrollbox_position ? 'right' : 'left';
        $trigger_point = (int)$form->scrollbox_trigger_point;
        $closing = $form->scrollbox_closing;
        $minimize = $form->scrollbox_minimize;
        $minimize_text = $form->scrollbox_minimize_text;
        $hide_duration = $form->scrollbox_hide_duration;
        $hide_mobile_class = wp_is_mobile() ? 'fm_mobile_full' : '';
        $hide_mobile = wp_is_mobile() && $form->hide_mobile ? FALSE : TRUE;
        $left_right_class = $form->scrollbox_position ? 'float-right' : 'float-left';
        if ($display_on_this && $hide_mobile) {
          if (isset($_SESSION['fm_hide_form_after_submit' . $id]) && $_SESSION['fm_hide_form_after_submit' . $id] == 1) {
            if ($error == 'success') {
              if ($message) {
                $onload_js .= '
									jQuery("#fm-form' . $id . ', #fm-pages' . $id . '").addClass("fm-hide");
									fm_hide_form(' . $id . ', ' . $hide_duration . ');
									jQuery("#fm-scrollbox' . $id . '").removeClass("fm-animated fadeOutDown").addClass("fm-animated fadeInUp");
									jQuery("#fm-scrollbox' . $id . '").css("visibility", "");
									jQuery("#minimize-form' . $id . '").css("visibility", "hidden");
								';
              }
              else {
                $onload_js .= 'fm_hide_form(' . $id . ', ' . $hide_duration . ');';
              }
            }
          }
          else {
            if (isset($_SESSION['error_occurred' . $id]) && $_SESSION['error_occurred' . $id] == 1) {
              $_SESSION['error_occurred' . $id] = 0;
              if ($message) {
                $onload_js .= '
									jQuery("#fm-scrollbox' . $id . '").removeClass("fm-animated fadeOutDown").addClass("fm-animated fadeInUp");
									jQuery("#fm-scrollbox' . $id . '").removeClass("fm-animated fadeOutDown").addClass("fm-animated fadeInUp");
									jQuery("#fm-scrollbox' . $id . '").css("visibility", "");
								';
              }
            }
            else {
              $onload_js .= '
								if(' . $hide_duration . ' == 0){
									localStorage.removeItem("hide-"+' . $id . ');
								}
								var hide_scrollbox = localStorage.getItem("hide-"+' . $id . ');';
              if ($trigger_point > 0) {
                $onload_js .= '
									if(hide_scrollbox == null || fm_currentDate.getTime() >= hide_scrollbox || ' . $show_for_admin . '){
										jQuery(window).scroll(function () {
											fmscrollHandler(' . $id . ');
										  });
										}';
              }
              else {
                $onload_js .= '
								if(hide_scrollbox == null || fm_currentDate.getTime() >= hide_scrollbox || ' . $show_for_admin . '){
									fmscrollHandler(' . $id . ');
								}';
              }
            }
          }
          if ($minimize) {
            $fm_form .= '<div id="fm-minimize-text' . $id . '" class="fm-minimize-text ' . $hide_mobile_class . '" onclick="fm_show_scrollbox(' . $id . ');" style="' . $left_right . ': 0px; display:none;">
								<div>' . $minimize_text . '</div>
							</div>';
          }

          $fm_form .= '<div id="fm-scrollbox' . $id . '" class="fm-scrollbox ' . $hide_mobile_class . '" style="' . $left_right . ': 0px; visibility:hidden;">';
          $fm_form .= '<div class="fm-scrollbox-form ' . $left_right_class . '">';
          $fm_form .= $form_html;
          $fm_form .= '<div id="fm-action-buttons' . $id . '" class="fm-action-buttons">';
          if ($minimize) {
            $fm_form .= '<span id="minimize-form' . $id . '" class="minimize-form dashicons dashicons-minus" onclick="minimize_form(' . $id . ')"></span>';
          }
          if ($closing) {
            $fm_form .= '<span id="closing-form' . $id . '" class="closing-form dashicons dashicons-no" onclick="fm_hide_form(' . $id . ', ' . $hide_duration . ', function(){ jQuery(\'#fm-scrollbox' . $id . '\').removeClass(\'fm-show\').addClass(\'fm-hide\'); });"></span>';
          }
          $fm_form .= '</div>';
          $fm_form .= '</div>';
          $fm_form .= '</div>';
          /* one more closing div for cloasing buttons */
        }
        break;
      }
      case 'popover': {
        $animate_effect = $form->popover_animate_effect;
        $loading_delay = (int)$form->popover_loading_delay;
        $frequency = $form->popover_frequency;
        $hide_mobile = wp_is_mobile() && $form->hide_mobile ? FALSE : TRUE;
        $hide_mobile_class = wp_is_mobile() ? 'fm_mobile_full' : '';

        if ($display_on_this && $hide_mobile) {
          if (isset($_SESSION['fm_hide_form_after_submit' . $id]) && $_SESSION['fm_hide_form_after_submit' . $id] == 1) {
            if ($error == 'success') {
              if ($message) {
                $onload_js .= '
									jQuery("#fm-form' . $id . '").addClass("fm-hide");
									jQuery("#fm-pages' . $id . '").addClass("fm-hide");
									jQuery("#fm-popover-background' . $id . '").css("display", "block");
									jQuery("#fm-popover' . $id . '").css("visibility", "");

									fm_hide_form(' . $id . ', ' . $frequency . ');
								';
              }
              else {
                $onload_js .= '
									jQuery("#fm-form' . $id . '").addClass("fm-hide");
									jQuery("#fm-pages' . $id . '").addClass("fm-hide");
									fm_hide_form(' . $id . ', ' . $frequency . ', function(){
										jQuery("#fm-popover-background' . $id . '").css("display", "none");
										jQuery("#fm-popover' . $id . '").css("display", "none");
									});
								';
              }
            }
          }
          else {
            if (isset($_SESSION['error_occurred' . $id]) && $_SESSION['error_occurred' . $id] == 1) {
              $_SESSION['error_occurred' . $id] = 0;
              if ($message) {
                $onload_js .= '
									jQuery("#fm-popover-background' . $id . '").css("display", "block");
									jQuery("#fm-popover' . $id . '").css("visibility", "");
								';
              }
            }
            else {
              $onload_js .= '
								if(' . $frequency . ' == 0){
									localStorage.removeItem("hide-"+' . $id . ');
								}
								var hide_popover = localStorage.getItem("hide-"+' . $id . ');
								if(hide_popover == null || fm_currentDate.getTime() >= hide_popover || ' . $show_for_admin . '){
									setTimeout(function(){
										jQuery("#fm-popover-background' . $id . '").css("display", "block");
										jQuery("#fm-popover' . $id . '").css("visibility", "");
										jQuery(".fm-popover-content").addClass("fm-animated ' . ($animate_effect) . '");
										jQuery("#fm-popover' . $id . ' .fm-header-img").addClass("fm-animated ' . ($form->header_image_animation) . '");
									}, ' . ($loading_delay * 1000) . ');
								}';
            }
          }

          $onload_js .= '
							jQuery("#fm-popover-inner-background' . $id . '").on("click", function(){
								fm_hide_form(' . $id . ', ' . $frequency . ', function(){
								  jQuery("#fm-popover-background' . $id . '").css("display", "none");
								  jQuery("#fm-popover' . $id . '").css("display", "none");
								});
							});
						';

          $fm_form .= '<div class="fm-popover-background" id="fm-popover-background' . $id . '" style="display:none;"></div>
						<div id="fm-popover' . $id . '" class="fm-popover ' . $hide_mobile_class . '" style="visibility:hidden;">
							<div class="fm-popover-container" id="fm-popover-container' . $id . '">
								<div class="fm-popover-inner-background" id="fm-popover-inner-background' . $id . '"></div>
								<div class="fm-popover-content">';
          $fm_form .= $form_html;
          $fm_form .= '<div id="fm-action-buttons' . $id . '" class="fm-action-buttons">';
          $fm_form .= '<span id="closing-form' . $id . '" class="closing-form dashicons dashicons-no" onclick="fm_hide_form(' . $id . ', ' . $frequency . ', function(){
												jQuery(\'#fm-popover-background' . $id . '\').css(\'display\', \'none\');
												jQuery(\'#fm-popover' . $id . '\').css(\'display\', \'none\');
											});"></span>
								</div>
							</div>
						</div>';

          /* one more closing div for cloasing buttons */
        }
        break;
      }
    }
    wp_add_inline_script('fm-script-' . $id, $onload_js);

    return WDW_FM_Library::fm_container($form->theme, $fm_form);
  }

  private function type_file_upload($params, $label, $required_sym, $id1, $form_id, $param) {

    return '';
  }
  private function type_map($params, $label, $id1, $form_id, $param) {
    return '';
  }
  private function type_paypal_price_new($params, $label, $required_sym, $id1, $form_id, $param, $form_currency, $symbol_begin, $symbol_end) {
    return '';
  }
  private function type_paypal_select($params, $label, $required_sym, $id1, $form_id, $param) {
    return '';
  }
  private function type_paypal_radio($params, $label, $required_sym, $id1, $form_id, $param) {
    return '';
  }
  private function type_paypal_checkbox($params, $label, $required_sym, $id1, $form_id, $param) {
    return '';
  }
  private function type_paypal_shipping($params, $label, $required_sym, $id1, $form_id, $param) {
    return '';
  }
  private function type_paypal_total($params, $label, $id1, $form_id, $param) {
    return '';
  }
}
