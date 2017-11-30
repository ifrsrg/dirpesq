<?php

/**
 * Class FMControllerThemes_fm
 */
class FMControllerThemes_fm {
  /**
   * @var $model
   */
  private $model;
  /**
   * @var $view
   */
  private $view;
  /**
   * @var string $page
   */
  private $page; 
  /**
   * @var string $bulk_action_name
   */
  private $bulk_action_name;
  /**
   * @var int $items_per_page
   */
  private $items_per_page = 20;
  /**
   * @var array $actions
   */
	private $actions = array();

  public function __construct() {
    require_once WDFM()->plugin_dir . "/admin/models/Themes_fm.php";
    $this->model = new FMModelThemes_fm();
    require_once WDFM()->plugin_dir . "/admin/views/Themes_fm.php";
    $this->view = new FMViewThemes_fm();
    $this->page = WDW_FM_Library::get('page');
	  $this->bulk_action_name = 'bulk_action';
	
    $this->actions = array(
      'duplicate' => array(
        'title' => __('Duplicate', WDFM()->prefix),
        $this->bulk_action_name => __('duplicated', WDFM()->prefix),
      ),
      'delete' => array(
        'title' => __('Delete', WDFM()->prefix),
        $this->bulk_action_name => __('deleted', WDFM()->prefix),
      ),
    );
  }

  /**
   * Execute.
   */
  public function execute() {
    $task = WDW_FM_Library::get('task');
    $id = (int) WDW_FM_Library::get('current_id', 0);
    if ( method_exists($this, $task) ) {
      if ( $task != 'add' && $task != 'edit' && $task != 'display' ) {
        check_admin_referer(WDFM()->nonce, WDFM()->nonce);
      }
      $block_action = $this->bulk_action_name;
      $action = WDW_FM_Library::get($block_action, -1);
		  if ( $action != -1 ) {
			$this->$block_action($action);
		  }
      else {
        $this->$task($id);
      }
    }
    else {
      $this->display();
    }
  }

  /**
   * Display.
   */
  public function display() {
    // Set params for view.
    $params = array();
    $params['page'] = $this->page;
    $params['page_title'] = __('Themes', WDFM()->prefix);
    $params['actions'] = $this->actions;
    $params['order'] = WDW_FM_Library::get('order', 'desc');
    $params['orderby'] = WDW_FM_Library::get('orderby', 'default');
    // To prevent SQL injections.
    $params['order'] = ($params['order'] == 'desc') ? 'desc' : 'asc';
    if ( !in_array($params['orderby'], array( 'title', 'default' )) ) {
      $params['orderby'] = 'default';
    }
    $params['items_per_page'] = $this->items_per_page;
    $page = (int) WDW_FM_Library::get('paged', 1);
    $page_num = $page ? ($page - 1) * $params['items_per_page'] : 0;
    $params['page_num'] = $page_num;
    $params['search'] = WDW_FM_Library::get('s', '');;
    $params['total'] = $this->model->total();
    $params['rows_data'] = $this->model->get_rows_data($params);
    $this->view->display($params);
  }

  /**
   * Bulk actions.
   *
   * @param $task
   */
	public function bulk_action($task) {
		$message = 0;
		$successfully_updated = 0;

		$check = WDW_FM_Library::get('check', '');

		if ( $check ) {
		  foreach ( $check as $form_id => $item ) {
			if ( method_exists($this, $task) ) {
			  $message = $this->$task($form_id, TRUE);
			  if ( $message != 2 ) {
				// Increase successfully updated items count, if action doesn't failed.
				$successfully_updated++;
			  }
			}
		  }
		  if ( $successfully_updated ) {
			$block_action = $this->bulk_action_name;
			$message = sprintf(_n('%s item successfully %s.', '%s items successfully %s.', $successfully_updated, WDFM()->prefix), $successfully_updated, $this->actions[$task][$block_action]);
		  }
		}

		WDW_FM_Library::fm_redirect(add_query_arg(array(
													'page' => $this->page,
													'task' => 'display',
													($message === 2 ? 'message' : 'msg') => $message,
												  ), admin_url('admin.php')));

	}

  /**
   * Delete form by id.
   *
   * @param      $id
   * @param bool $bulk
   *
   * @return int
   */
  public function delete( $id, $bulk = FALSE ) {
    $isDefault = $this->model->get_default($id);
    if ( $isDefault ) {
      $message = 4;
    }
    else {
      $table = 'formmaker_themes';
      $delete = $this->model->delete_rows(array(
                                            'table' => $table,
                                            'where' => 'id = ' . $id,
                                          ));
      if ( $delete ) {
        $message = 3;
      }
      else {
        $message = 2;
      }
    }
    if ( $bulk ) {
      return $message;
    }
    WDW_FM_Library::fm_redirect(add_query_arg(array(
                                                'page' => $this->page,
                                                'task' => 'display',
                                                'message' => $message,
                                              ), admin_url('admin.php')));
  }

  /**
   * Duplicate by id.
   *
   * @param      $id
   * @param bool $bulk
   *
   * @return int
   */
  public function duplicate( $id, $bulk = FALSE ) {
    $message = 2;
    $table = 'formmaker_themes';
    $row = $this->model->select_rows("get_row", array(
      "selection" => "*",
      "table" => $table,
      "where" => "id=" . (int) $id,
    ));
    if ( $row ) {
      $row = (array) $row;
      unset($row['id']);
      $row['default'] = 0;
      $inserted = $this->model->insert_data_to_db($table, (array) $row);
      if ( $inserted !== FALSE ) {
        $message = 11;
      }
    }
    if ( $bulk ) {
      return $message;
    }
    else {
      WDW_FM_Library::fm_redirect(add_query_arg(array(
                                                  'page' => $this->page,
                                                  'task' => 'display',
                                                  'message' => $message,
                                                ), admin_url('admin.php')));
    }
  }

  /**
   * Edit.
   *
   * @param int $id
   */
  public function edit($id = 0 ) {
    $params = array();
    $params['id'] = (int) $id;
    $params['row'] = $this->model->get_row_data($params['id'], FALSE);
	if ( empty($params['row']->id) ) {
		WDW_FM_Library::fm_redirect( add_query_arg( array('page' => $this->page, 'task' => 'display'), admin_url('admin.php') ) );
	}
    $params['page_title'] = $params['row']->title;
    $params['param_values'] = $params['row']->css;
    $border_types = array(
      'solid' => 'Solid',
      'dotted' => 'Dotted',
      'dashed' => 'Dashed',
      'double' => 'Double',
      'groove' => 'Groove',
      'ridge' => 'Ridge',
      'inset' => 'Inset',
      'outset' => 'Outset',
      'initial' => 'Initial',
      'inherit' => 'Inherit',
      'hidden' => 'Hidden',
      'none' => 'None',
    );
    $borders = array( 'top' => 'Top', 'right' => 'Right', 'bottom' => 'Bottom', 'left' => 'Left' );
    $border_values = array(
      'top' => 'BorderTop',
      'right' => 'BorderRight',
      'bottom' => 'BorderBottom',
      'left' => 'BorderLeft',
    );
    $position_types = array(
      'static' => 'Static',
      'relative' => 'Relative',
      'fixed' => 'Fixed',
      'absolute' => 'Absolute',
    );
    $font_weights = array(
      'normal' => 'Normal',
      'bold' => 'Bold',
      'bolder' => 'Bolder',
      'lighter' => 'Lighter',
      'initial' => 'Initial',
    );
    $aligns = array( 'left' => 'Left', 'center' => 'Center', 'right' => 'Right' );
    $aligns_no_center = array( 'left' => 'Left', 'right' => 'Right' );
    $basic_fonts = array(
      'arial' => 'Arial',
      'lucida grande' => 'Lucida grande',
      'segoe ui' => 'Segoe ui',
      'tahoma' => 'Tahoma',
      'trebuchet ms' => 'Trebuchet ms',
      'verdana' => 'Verdana',
      'cursive' => 'Cursive',
      'fantasy' => 'Fantasy',
      'monospace' => 'Monospace',
      'serif' => 'Serif',
    );
    $bg_repeats = array(
      'repeat' => 'repeat',
      'repeat-x' => 'repeat-x',
      'repeat-y' => 'repeat-y',
      'no-repeat' => 'no-repeat',
      'initial' => 'initial',
      'inherit' => 'inherit',
    );
    $google_fonts = WDW_FM_Library::get_google_fonts();
    $font_families = $basic_fonts + $google_fonts;
    $params['fonts'] = implode("|", str_replace(' ', '+', $google_fonts));
    $params['tabs'] = array(
      'global' => 'Global Parameters',
      'header' => 'Header',
      'content' => 'Content',
      'input_select' => 'Inputbox',
      'choices' => 'Choices',
      'subscribe' => 'General Buttons',
      'paigination' => 'Pagination',
      'buttons' => 'Buttons',
      'close_button' => 'Close(Minimize) Button',
      'minimize' => 'Minimize Text',
      'other' => 'Other',
      'custom_css' => 'Custom CSS',
    );
    $params['all_params'] = $this->all_params($params['param_values'], $borders, $border_types, $font_weights, $position_types, $aligns_no_center, $aligns, $bg_repeats, $font_families);
    $this->view->edit($params);
  }

  // set all params in array
  public function all_params( $param_values, $borders, $border_types, $font_weights, $position_types, $aligns_no_center, $aligns, $bg_repeats, $font_families ) {
    $all_params = array(
      'global' => array(
        array(
          'label' => '',
          'type' => 'panel',
          'class' => 'col-md-12',
          'label_class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Font Family',
          'name' => 'GPFontFamily',
          'type' => 'select',
          'options' => $font_families,
          'class' => '',
          'value' => isset($param_values->GPFontFamily) ? $param_values->GPFontFamily : '',
          'after' => '',
        ),
        array(
          'label' => 'Width',
          'name' => 'AGPWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->AGPWidth) ? $param_values->AGPWidth : '',
          'after' => '%',
        ),
        array(
          'label' => 'Width (for scrollbox, popup form types)',
          'name' => 'AGPSPWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->AGPSPWidth) ? $param_values->AGPSPWidth : '',
          'after' => '%',
        ),
        array(
          'label' => 'Padding',
          'name' => 'AGPPadding',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->AGPPadding) ? $param_values->AGPPadding : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Margin',
          'name' => 'AGPMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->AGPMargin) ? $param_values->AGPMargin : '',
          'placeholder' => 'e.g. 5px 10px or 5% 10%',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Border',
          'name' => 'AGPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'AGPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->AGPBorderColor) ? $param_values->AGPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'AGPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->AGPBorderType) ? $param_values->AGPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'AGPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->AGPBorderWidth) ? $param_values->AGPBorderWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Border Radius',
          'name' => 'AGPBorderRadius',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->AGPBorderRadius) ? $param_values->AGPBorderRadius : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Box Shadow',
          'name' => 'AGPBoxShadow',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->AGPBoxShadow) ? $param_values->AGPBoxShadow : '',
          'placeholder' => 'e.g. 5px 5px 2px #888888',
          'after' => '</div>',
        ),
      ),
      'header' => array(
        array(
          'label' => 'General Parameters',
          'type' => 'panel',
          'class' => 'col-md-6',
          'label_class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Alignment',
          'name' => 'HPAlign',
          'type' => 'select',
          'options' => $borders,
          'class' => '',
          'value' => isset($param_values->HPAlign) ? $param_values->HPAlign : '',
          'after' => '',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'HPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->HPBGColor) ? $param_values->HPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Width',
          'name' => 'HPWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->HPWidth) ? $param_values->HPWidth : '',
          'after' => '%',
        ),
        array(
          'label' => 'Width (for topbar form type)',
          'name' => 'HTPWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->HTPWidth) ? $param_values->HTPWidth : '',
          'after' => '%',
        ),
        array(
          'label' => 'Padding',
          'name' => 'HPPadding',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->HPPadding) ? $param_values->HPPadding : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Margin',
          'name' => 'HPMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->HPMargin) ? $param_values->HPMargin : '',
          'placeholder' => 'e.g. 5px 10px or 5% 10%',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Text Align',
          'name' => 'HPTextAlign',
          'type' => 'select',
          'options' => $aligns,
          'class' => '',
          'value' => isset($param_values->HPTextAlign) ? $param_values->HPTextAlign : '',
          'after' => '',
        ),
        array(
          'label' => 'Border',
          'name' => 'HPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'HPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->HPBorderColor) ? $param_values->HPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'HPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->HPBorderType) ? $param_values->HPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'HPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->HPBorderWidth) ? $param_values->HPBorderWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Border Radius',
          'name' => 'HPBorderRadius',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->HPBorderRadius) ? $param_values->HPBorderRadius : '',
          'after' => 'px</div>',
        ),
        array(
          'label' => 'Title Parameters',
          'type' => 'panel',
          'class' => 'col-md-6',
          'label_class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Font Size',
          'name' => 'HTPFontSize',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->HTPFontSize) ? $param_values->HTPFontSize : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Font Weight',
          'name' => 'HTPWeight',
          'type' => 'select',
          'options' => $font_weights,
          'class' => '',
          'value' => isset($param_values->HTPWeight) ? $param_values->HTPWeight : '',
          'after' => '',
        ),
        array(
          'label' => 'Color',
          'name' => 'HTPColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->HTPColor) ? $param_values->HTPColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Description Parameters',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Font Size',
          'name' => 'HDPFontSize',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->HDPFontSize) ? $param_values->HDPFontSize : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Color',
          'name' => 'HDPColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->HDPColor) ? $param_values->HDPColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Image Parameters',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Alignment',
          'name' => 'HIPAlign',
          'type' => 'select',
          'options' => $borders,
          'class' => '',
          'value' => isset($param_values->HIPAlign) ? $param_values->HIPAlign : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Width',
          'name' => 'HIPWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->HIPWidth) ? $param_values->HIPWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Height',
          'name' => 'HIPHeight',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->HIPHeight) ? $param_values->HIPHeight : '',
          'after' => 'px</div>',
        ),
      ),
      'content' => array(
        array(
          'label' => 'General Parameters',
          'type' => 'panel',
          'class' => 'col-md-6',
          'label_class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'GPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->GPBGColor) ? $param_values->GPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Font Size',
          'name' => 'GPFontSize',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->GPFontSize) ? $param_values->GPFontSize : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Font Weight',
          'name' => 'GPFontWeight',
          'type' => 'select',
          'options' => $font_weights,
          'class' => '',
          'value' => isset($param_values->GPFontWeight) ? $param_values->GPFontWeight : '',
          'after' => '',
        ),
        array(
          'label' => 'Width',
          'name' => 'GPWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->GPWidth) ? $param_values->GPWidth : '',
          'after' => '%',
        ),
        array(
          'label' => 'Width (for topbar form type)',
          'name' => 'GTPWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->GTPWidth) ? $param_values->GTPWidth : '',
          'after' => '%',
        ),
        array(
          'label' => 'Alignment',
          'name' => 'GPAlign',
          'type' => 'select',
          'options' => $aligns,
          'class' => '',
          'value' => isset($param_values->GPAlign) ? $param_values->GPAlign : '',
          'after' => '',
        ),
        array(
          'label' => 'Background URL',
          'name' => 'GPBackground',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->GPBackground) ? $param_values->GPBackground : '',
          'after' => '',
        ),
        array(
          'label' => 'Background Repeat',
          'name' => 'GPBackgroundRepeat',
          'type' => 'select',
          'options' => $bg_repeats,
          'class' => '',
          'value' => isset($param_values->GPBackgroundRepeat) ? $param_values->GPBackgroundRepeat : '',
          'after' => '',
        ),
        array(
          'label' => 'Background Position',
          'name1' => 'GPBGPosition1',
          'name2' => 'GPBGPosition2',
          'type' => '2text',
          'class' => 'fm-2text',
          'value1' => isset($param_values->GPBGPosition1) ? $param_values->GPBGPosition1 : '',
          'value2' => isset($param_values->GPBGPosition2) ? $param_values->GPBGPosition2 : '',
          'before1' => '',
          'before2' => '',
          'after' => '%/left..',
        ),
        array(
          'label' => 'Background Size',
          'name1' => 'GPBGSize1',
          'name2' => 'GPBGSize2',
          'type' => '2text',
          'class' => 'fm-2text',
          'value1' => isset($param_values->GPBGSize1) ? $param_values->GPBGSize1 : '',
          'value2' => isset($param_values->GPBGSize2) ? $param_values->GPBGSize2 : '',
          'before1' => '',
          'before2' => '',
          'after' => '%/px',
        ),
        array(
          'label' => 'Color',
          'name' => 'GPColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->GPColor) ? $param_values->GPColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Padding',
          'name' => 'GPPadding',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->GPPadding) ? $param_values->GPPadding : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Margin',
          'name' => 'GPMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->GPMargin) ? $param_values->GPMargin : '',
          'placeholder' => 'e.g. 5px 10px or 5% 10%',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Border',
          'name' => 'GPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'GPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->GPBorderColor) ? $param_values->GPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'GPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->GPBorderType) ? $param_values->GPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'GPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->GPBorderWidth) ? $param_values->GPBorderWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Border Radius',
          'name' => 'GPBorderRadius',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->GPBorderRadius) ? $param_values->GPBorderRadius : '',
          'after' => 'px</div>',
        ),
        array(
          'label' => 'Mini labels (name, phone, address, checkbox, radio) Parameters',
          'type' => 'panel',
          'class' => 'col-md-6',
          'label_class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Font Size',
          'name' => 'GPMLFontSize',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->GPMLFontSize) ? $param_values->GPMLFontSize : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Font Weight',
          'name' => 'GPMLFontWeight',
          'type' => 'select',
          'options' => $font_weights,
          'class' => '',
          'value' => isset($param_values->GPMLFontWeight) ? $param_values->GPMLFontWeight : '',
          'after' => '',
        ),
        array(
          'label' => 'Color',
          'name' => 'GPMLColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->GPMLColor) ? $param_values->GPMLColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Padding',
          'name' => 'GPMLPadding',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->GPMLPadding) ? $param_values->GPMLPadding : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Margin',
          'name' => 'GPMLMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->GPMLMargin) ? $param_values->GPMLMargin : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Section Parameters',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'SEPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->SEPBGColor) ? $param_values->SEPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Padding',
          'name' => 'SEPPadding',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SEPPadding) ? $param_values->SEPPadding : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Margin',
          'name' => 'SEPMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SEPMargin) ? $param_values->SEPMargin : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Section Column Parameters',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Padding',
          'name' => 'COPPadding',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->COPPadding) ? $param_values->COPPadding : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Margin',
          'name' => 'COPMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->COPMargin) ? $param_values->COPMargin : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Footer Parameters',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Width',
          'name' => 'FPWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->FPWidth) ? $param_values->FPWidth : '',
          'after' => '%',
        ),
        array(
          'label' => 'Padding',
          'name' => 'FPPadding',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->FPPadding) ? $param_values->FPPadding : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Margin',
          'name' => 'FPMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->FPMargin) ? $param_values->FPMargin : '',
          'after' => 'px/%</div>',
        ),
      ),
      'input_select' => array(
        array(
          'label' => '',
          'type' => 'panel',
          'class' => 'col-md-12',
          'label_class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Height',
          'name' => 'IPHeight',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->IPHeight) ? $param_values->IPHeight : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Font Size',
          'name' => 'IPFontSize',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->IPFontSize) ? $param_values->IPFontSize : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Font Weight',
          'name' => 'IPFontWeight',
          'type' => 'select',
          'options' => $font_weights,
          'class' => '',
          'value' => isset($param_values->IPFontWeight) ? $param_values->IPFontWeight : '',
          'after' => '',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'IPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->IPBGColor) ? $param_values->IPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Color',
          'name' => 'IPColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->IPColor) ? $param_values->IPColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Padding',
          'name' => 'IPPadding',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->IPPadding) ? $param_values->IPPadding : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Margin',
          'name' => 'IPMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->IPMargin) ? $param_values->IPMargin : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Border',
          'name' => 'IPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'IPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->IPBorderColor) ? $param_values->IPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'IPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->IPBorderType) ? $param_values->IPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'IPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->IPBorderWidth) ? $param_values->IPBorderWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Border Radius',
          'name' => 'IPBorderRadius',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->IPBorderRadius) ? $param_values->IPBorderRadius : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Box Shadow',
          'name' => 'IPBoxShadow',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->IPBoxShadow) ? $param_values->IPBoxShadow : '',
          'placeholder' => 'e.g. 5px 5px 2px #888888',
          'after' => '</div>',
        ),
        array(
          'label' => 'Dropdown additional',
          'type' => 'panel',
          'class' => 'col-md-12',
          'label_class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Appearance',
          'name' => 'SBPAppearance',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SBPAppearance) ? $param_values->SBPAppearance : '',
          'after' => '',
        ),
        array(
          'label' => 'Background URL',
          'name' => 'SBPBackground',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SBPBackground) ? $param_values->SBPBackground : '',
          'after' => '',
        ),
        array(
          'label' => 'Background Repeat',
          'name' => 'SBPBGRepeat',
          'type' => 'select',
          'options' => $bg_repeats,
          'class' => '',
          'value' => isset($param_values->SBPBGRepeat) ? $param_values->SBPBGRepeat : '',
          'after' => '',
        ),
        array(
          'label' => 'Background Position',
          'name1' => 'SBPBGPos1',
          'name2' => 'SBPBGPos2',
          'type' => '2text',
          'class' => 'fm-2text',
          'value1' => isset($param_values->SBPBGPos1) ? $param_values->SBPBGPos1 : '',
          'value2' => isset($param_values->SBPBGPos2) ? $param_values->SBPBGPos2 : '',
          'before1' => '',
          'before2' => '',
          'after' => '%/left..',
        ),
        array(
          'label' => 'Background Size',
          'name1' => 'SBPBGSize1',
          'name2' => 'SBPBGSize2',
          'type' => '2text',
          'class' => 'fm-2text',
          'value1' => isset($param_values->SBPBGSize1) ? $param_values->SBPBGSize1 : '',
          'value2' => isset($param_values->SBPBGSize2) ? $param_values->SBPBGSize2 : '',
          'before1' => '',
          'before2' => '',
          'after' => '%/px',
        ),
        array(
          'label' => '',
          'type' => 'label',
          'class' => '',
          'after' => '</div>',
        ),
      ),
      'choices' => array(
        array(
          'label' => 'Single Choice',
          'type' => 'panel',
          'class' => 'col-md-6',
          'label_class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Input Parameters',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'SCPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->SCPBGColor) ? $param_values->SCPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Width',
          'name' => 'SCPWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SCPWidth) ? $param_values->SCPWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Height',
          'name' => 'SCPHeight',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SCPHeight) ? $param_values->SCPHeight : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Border',
          'name' => 'SCPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'SCPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->SCPBorderColor) ? $param_values->SCPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'SCPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->SCPBorderType) ? $param_values->SCPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'SCPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SCPBorderWidth) ? $param_values->SCPBorderWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Margin',
          'name' => 'SCPMargin',
          'type' => 'text',
          'class' => '5px',
          'value' => isset($param_values->SCPMargin) ? $param_values->SCPMargin : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Radius',
          'name' => 'SCPBorderRadius',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SCPBorderRadius) ? $param_values->SCPBorderRadius : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Box Shadow',
          'name' => 'SCPBoxShadow',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SCPBoxShadow) ? $param_values->SCPBoxShadow : '',
          'placeholder' => 'e.g. 5px 5px 2px #888888',
          'after' => '',
        ),
        array(
          'label' => 'Checked Parameters',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'SCCPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->SCCPBGColor) ? $param_values->SCCPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Width',
          'name' => 'SCCPWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SCCPWidth) ? $param_values->SCCPWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Height',
          'name' => 'SCCPHeight',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SCCPHeight) ? $param_values->SCCPHeight : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Margin',
          'name' => 'SCCPMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SCCPMargin) ? $param_values->SCCPMargin : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Radius',
          'name' => 'SCCPBorderRadius',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SCCPBorderRadius) ? $param_values->SCCPBorderRadius : '',
          'after' => 'px</div>',
        ),
        array(
          'label' => 'Multiple Choice',
          'type' => 'panel',
          'class' => 'col-md-6',
          'label_class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Input Parameters',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'MCPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->MCPBGColor) ? $param_values->MCPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Width',
          'name' => 'MCPWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->MCPWidth) ? $param_values->MCPWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Height',
          'name' => 'MCPHeight',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->MCPHeight) ? $param_values->MCPHeight : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Border',
          'name' => 'MCPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'MCPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->MCPBorderColor) ? $param_values->MCPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'MCPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->MCPBorderType) ? $param_values->MCPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'MCPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->MCPBorderWidth) ? $param_values->MCPBorderWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Margin',
          'name' => 'MCPMargin',
          'type' => 'text',
          'class' => '5px',
          'value' => isset($param_values->MCPMargin) ? $param_values->MCPMargin : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Radius',
          'name' => 'MCPBorderRadius',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->MCPBorderRadius) ? $param_values->MCPBorderRadius : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Box Shadow',
          'name' => 'MCPBoxShadow',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->MCPBoxShadow) ? $param_values->MCPBoxShadow : '',
          'placeholder' => 'e.g. 5px 5px 2px #888888',
          'after' => '',
        ),
        array(
          'label' => 'Checked Parameters',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'MCCPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->MCCPBGColor) ? $param_values->MCCPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Background URL',
          'name' => 'MCCPBackground',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->MCCPBackground) ? $param_values->MCCPBackground : '',
          'after' => '',
        ),
        array(
          'label' => 'Background Repeat',
          'name' => 'MCCPBGRepeat',
          'type' => 'select',
          'options' => $bg_repeats,
          'class' => '',
          'value' => isset($param_values->MCCPBGRepeat) ? $param_values->MCCPBGRepeat : '',
          'after' => '',
        ),
        array(
          'label' => 'Background Position',
          'name1' => 'MCCPBGPos1',
          'name2' => 'MCCPBGPos2',
          'type' => '2text',
          'class' => 'fm-2text',
          'value1' => isset($param_values->MCCPBGPos1) ? $param_values->MCCPBGPos1 : '',
          'value2' => isset($param_values->MCCPBGPos2) ? $param_values->MCCPBGPos2 : '',
          'before1' => '',
          'before2' => '',
          'after' => '%/left..',
        ),
        array(
          'label' => 'Width',
          'name' => 'MCCPWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->MCCPWidth) ? $param_values->MCCPWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Height',
          'name' => 'MCCPHeight',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->MCCPHeight) ? $param_values->MCCPHeight : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Margin',
          'name' => 'MCCPMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->MCCPMargin) ? $param_values->MCCPMargin : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Radius',
          'name' => 'MCCPBorderRadius',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->MCCPBorderRadius) ? $param_values->MCCPBorderRadius : '',
          'after' => 'px</div>',
        ),
      ),
      'subscribe' => array(
        array(
          'label' => 'Global Parameters',
          'type' => 'panel',
          'class' => 'col-md-12',
          'label_class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Alignment',
          'name' => 'SPAlign',
          'type' => 'select',
          'options' => $aligns_no_center,
          'class' => '',
          'value' => isset($param_values->SPAlign) ? $param_values->SPAlign : '',
          'after' => '</div>',
        ),
        array(
          'label' => 'Submit',
          'type' => 'panel',
          'class' => 'col-md-6',
          'label_class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'SPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->SPBGColor) ? $param_values->SPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Width',
          'name' => 'SPWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SPWidth) ? $param_values->SPWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Height',
          'name' => 'SPHeight',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SPHeight) ? $param_values->SPHeight : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Font Size',
          'name' => 'SPFontSize',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SPFontSize) ? $param_values->SPFontSize : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Font Weight',
          'name' => 'SPFontWeight',
          'type' => 'select',
          'options' => $font_weights,
          'class' => '',
          'value' => isset($param_values->SPFontWeight) ? $param_values->SPFontWeight : '',
          'after' => '',
        ),
        array(
          'label' => 'Color',
          'name' => 'SPColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->SPColor) ? $param_values->SPColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Padding',
          'name' => 'SPPadding',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SPPadding) ? $param_values->SPPadding : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Margin',
          'name' => 'SPMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SPMargin) ? $param_values->SPMargin : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Border',
          'name' => 'SPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'SPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->SPBorderColor) ? $param_values->SPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'SPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->SPBorderType) ? $param_values->SPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'SPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SPBorderWidth) ? $param_values->SPBorderWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Border Radius',
          'name' => 'SPBorderRadius',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SPBorderRadius) ? $param_values->SPBorderRadius : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Box Shadow',
          'name' => 'SPBoxShadow',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SPBoxShadow) ? $param_values->SPBoxShadow : '',
          'placeholder' => 'e.g. 5px 5px 2px #888888',
          'after' => '',
        ),
        array(
          'label' => 'Hover Parameters',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'SHPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->SHPBGColor) ? $param_values->SHPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Color',
          'name' => 'SHPColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->SHPColor) ? $param_values->SHPColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border',
          'name' => 'SHPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'SHPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->SHPBorderColor) ? $param_values->SHPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'SHPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->SHPBorderType) ? $param_values->SHPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'SHPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->SHPBorderWidth) ? $param_values->SHPBorderWidth : '',
          'after' => 'px</div>',
        ),
        array(
          'label' => 'Reset',
          'type' => 'panel',
          'class' => 'col-md-6',
          'label_class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'BPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->BPBGColor) ? $param_values->BPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Width',
          'name' => 'BPWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->BPWidth) ? $param_values->BPWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Height',
          'name' => 'BPHeight',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->BPHeight) ? $param_values->BPHeight : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Font Size',
          'name' => 'BPFontSize',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->BPFontSize) ? $param_values->BPFontSize : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Font Weight',
          'name' => 'BPFontWeight',
          'type' => 'select',
          'options' => $font_weights,
          'class' => '',
          'value' => isset($param_values->BPFontWeight) ? $param_values->BPFontWeight : '',
          'after' => '',
        ),
        array(
          'label' => 'Color',
          'name' => 'BPColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->BPColor) ? $param_values->BPColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Padding',
          'name' => 'BPPadding',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->BPPadding) ? $param_values->BPPadding : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Margin',
          'name' => 'BPMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->BPMargin) ? $param_values->BPMargin : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Border',
          'name' => 'BPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'BPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->BPBorderColor) ? $param_values->BPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'BPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->BPBorderType) ? $param_values->BPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'BPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->BPBorderWidth) ? $param_values->BPBorderWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Border Radius',
          'name' => 'BPBorderRadius',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->BPBorderRadius) ? $param_values->BPBorderRadius : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Box Shadow',
          'name' => 'BPBoxShadow',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->BPBoxShadow) ? $param_values->BPBoxShadow : '',
          'placeholder' => 'e.g. 5px 5px 2px #888888',
          'after' => '',
        ),
        array(
          'label' => 'Hover Parameters',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'BHPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->BHPBGColor) ? $param_values->BHPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Color',
          'name' => 'BHPColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->BHPColor) ? $param_values->BHPColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border',
          'name' => 'BHPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'BHPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->BHPBorderColor) ? $param_values->BHPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'BHPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->BHPBorderType) ? $param_values->BHPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'BHPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->BHPBorderWidth) ? $param_values->BHPBorderWidth : '',
          'after' => 'px</div>',
        ),
      ),
      'paigination' => array(
        array(
          'label' => 'Active',
          'type' => 'panel',
          'class' => 'col-md-6',
          'label_class' => 'fm-mini-title',
          'after' => '',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'PSAPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->PSAPBGColor) ? $param_values->PSAPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Font Size',
          'name' => 'PSAPFontSize',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PSAPFontSize) ? $param_values->PSAPFontSize : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Font Weight',
          'name' => 'PSAPFontWeight',
          'type' => 'select',
          'options' => $font_weights,
          'class' => '',
          'value' => isset($param_values->PSAPFontWeight) ? $param_values->PSAPFontWeight : '',
          'after' => '',
        ),
        array(
          'label' => 'Color',
          'name' => 'PSAPColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->PSAPColor) ? $param_values->PSAPColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Height',
          'name' => 'PSAPHeight',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PSAPHeight) ? $param_values->PSAPHeight : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Line Height',
          'name' => 'PSAPLineHeight',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PSAPLineHeight) ? $param_values->PSAPLineHeight : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Padding',
          'name' => 'PSAPPadding',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PSAPPadding) ? $param_values->PSAPPadding : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Margin',
          'name' => 'PSAPMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PSAPMargin) ? $param_values->PSAPMargin : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Border',
          'name' => 'PSAPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'PSAPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->PSAPBorderColor) ? $param_values->PSAPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'PSAPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->PSAPBorderType) ? $param_values->PSAPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'PSAPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PSAPBorderWidth) ? $param_values->PSAPBorderWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Border Radius',
          'name' => 'PSAPBorderRadius',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PSAPBorderRadius) ? $param_values->PSAPBorderRadius : '',
          'after' => 'px</div>',
        ),
        array(
          'label' => 'Deactive',
          'type' => 'panel',
          'class' => 'col-md-6',
          'label_class' => 'fm-mini-title',
          'after' => '',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'PSDPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->PSDPBGColor) ? $param_values->PSDPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Font Size',
          'name' => 'PSDPFontSize',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PSDPFontSize) ? $param_values->PSDPFontSize : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Font Weight',
          'name' => 'PSDPFontWeight',
          'type' => 'select',
          'options' => $font_weights,
          'class' => '',
          'value' => isset($param_values->PSDPFontWeight) ? $param_values->PSDPFontWeight : '',
          'after' => '',
        ),
        array(
          'label' => 'Color',
          'name' => 'PSDPColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->PSDPColor) ? $param_values->PSDPColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Height',
          'name' => 'PSDPHeight',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PSDPHeight) ? $param_values->PSDPHeight : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Line Height',
          'name' => 'PSDPLineHeight',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PSDPLineHeight) ? $param_values->PSDPLineHeight : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Padding',
          'name' => 'PSDPPadding',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PSDPPadding) ? $param_values->PSDPPadding : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Margin',
          'name' => 'PSDPMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PSDPMargin) ? $param_values->PSDPMargin : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Border',
          'name' => 'PSDPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'PSDPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->PSDPBorderColor) ? $param_values->PSDPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'PSDPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->PSDPBorderType) ? $param_values->PSDPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'PSDPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PSDPBorderWidth) ? $param_values->PSDPBorderWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Border Radius',
          'name' => 'PSDPBorderRadius',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PSDPBorderRadius) ? $param_values->PSDPBorderRadius : '',
          'after' => 'px</div>',
        ),
        array(
          'label' => 'Steps',
          'type' => 'panel',
          'class' => 'col-md-12',
          'label_class' => 'fm-mini-title',
          'after' => '',
        ),
        array(
          'label' => 'Alignment',
          'name' => 'PSAPAlign',
          'type' => 'select',
          'options' => $aligns,
          'class' => '',
          'value' => isset($param_values->PSAPAlign) ? $param_values->PSAPAlign : '',
          'after' => '',
        ),
        array(
          'label' => 'Width',
          'name' => 'PSAPWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PSAPWidth) ? $param_values->PSAPWidth : '',
          'after' => 'px</div>',
        ),
        array(
          'label' => 'Percentage',
          'type' => 'panel',
          'class' => 'col-md-12',
          'label_class' => 'fm-mini-title',
          'after' => '',
        ),
        array(
          'label' => 'Width',
          'name' => 'PPAPWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PPAPWidth) ? $param_values->PPAPWidth : '',
          'placeholder' => 'e.g. 100% or 500px',
          'after' => 'px/%</div>',
        ),
      ),
      'buttons' => array(
        array(
          'label' => 'Global Parameters',
          'type' => 'panel',
          'class' => 'col-md-12',
          'label_class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Font Size',
          'name' => 'BPFontSize',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->BPFontSize) ? $param_values->BPFontSize : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Font Weight',
          'name' => 'BPFontWeight',
          'type' => 'select',
          'options' => $font_weights,
          'class' => '',
          'value' => isset($param_values->BPFontWeight) ? $param_values->BPFontWeight : '',
          'after' => '</div>',
        ),
        array(
          'label' => 'Next Button Parameters',
          'type' => 'panel',
          'class' => 'col-md-6',
          'label_class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'NBPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->NBPBGColor) ? $param_values->NBPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Width',
          'name' => 'NBPWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->NBPWidth) ? $param_values->NBPWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Height',
          'name' => 'NBPHeight',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->NBPHeight) ? $param_values->NBPHeight : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Line Height',
          'name' => 'NBPLineHeight',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->NBPLineHeight) ? $param_values->NBPLineHeight : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Color',
          'name' => 'NBPColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->NBPColor) ? $param_values->NBPColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Padding',
          'name' => 'NBPPadding',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->NBPPadding) ? $param_values->NBPPadding : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Margin',
          'name' => 'NBPMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->NBPMargin) ? $param_values->NBPMargin : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Border',
          'name' => 'NBPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'NBPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->NBPBorderColor) ? $param_values->NBPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'NBPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->NBPBorderType) ? $param_values->NBPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'NBPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->NBPBorderWidth) ? $param_values->NBPBorderWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Border Radius',
          'name' => 'NBPBorderRadius',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->NBPBorderRadius) ? $param_values->NBPBorderRadius : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Box Shadow',
          'name' => 'NBPBoxShadow',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->NBPBoxShadow) ? $param_values->NBPBoxShadow : '',
          'placeholder' => 'e.g. 5px 5px 2px #888888',
          'after' => '',
        ),
        array(
          'label' => 'Hover Parameters',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'NBHPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->NBHPBGColor) ? $param_values->NBHPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Color',
          'name' => 'NBHPColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->NBHPColor) ? $param_values->NBHPColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border',
          'name' => 'NBHPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'NBHPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->NBHPBorderColor) ? $param_values->NBHPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'NBHPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->NBHPBorderType) ? $param_values->NBHPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'NBHPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->NBHPBorderWidth) ? $param_values->NBHPBorderWidth : '',
          'after' => 'px</div>',
        ),
        array(
          'label' => 'Previous Button Parameters',
          'type' => 'panel',
          'class' => 'col-md-6',
          'label_class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'PBPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->PBPBGColor) ? $param_values->PBPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Width',
          'name' => 'PBPWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PBPWidth) ? $param_values->PBPWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Height',
          'name' => 'PBPHeight',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PBPHeight) ? $param_values->PBPHeight : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Line Height',
          'name' => 'PBPLineHeight',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PBPLineHeight) ? $param_values->PBPLineHeight : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Color',
          'name' => 'PBPColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->PBPColor) ? $param_values->PBPColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Padding',
          'name' => 'PBPPadding',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PBPPadding) ? $param_values->PBPPadding : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Margin',
          'name' => 'PBPMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PBPMargin) ? $param_values->PBPMargin : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Border',
          'name' => 'PBPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'PBPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->PBPBorderColor) ? $param_values->PBPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'PBPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->PBPBorderType) ? $param_values->PBPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'PBPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PBPBorderWidth) ? $param_values->PBPBorderWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Border Radius',
          'name' => 'PBPBorderRadius',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PBPBorderRadius) ? $param_values->PBPBorderRadius : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Box Shadow',
          'name' => 'PBPBoxShadow',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PBPBoxShadow) ? $param_values->PBPBoxShadow : '',
          'placeholder' => 'e.g. 5px 5px 2px #888888',
          'after' => '',
        ),
        array(
          'label' => 'Hover Parameters',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'PBHPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->PBHPBGColor) ? $param_values->PBHPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Color',
          'name' => 'PBHPColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->PBHPColor) ? $param_values->PBHPColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border',
          'name' => 'PBHPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'PBHPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->PBHPBorderColor) ? $param_values->PBHPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'PBHPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->PBHPBorderType) ? $param_values->PBHPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'PBHPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->PBHPBorderWidth) ? $param_values->PBHPBorderWidth : '',
          'after' => 'px</div>',
        ),
      ),
      'close_button' => array(
        array(
          'label' => '',
          'type' => 'panel',
          'class' => 'col-md-12',
          'label_class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Position',
          'name' => 'CBPPosition',
          'type' => 'select',
          'options' => $position_types,
          'class' => '',
          'value' => isset($param_values->CBPPosition) ? $param_values->CBPPosition : '',
          'after' => '',
        ),
        array(
          'label' => 'Top',
          'name' => 'CBPTop',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->CBPTop) ? $param_values->CBPTop : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Right',
          'name' => 'CBPRight',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->CBPRight) ? $param_values->CBPRight : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Bottom',
          'name' => 'CBPBottom',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->CBPBottom) ? $param_values->CBPBottom : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Left',
          'name' => 'CBPLeft',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->CBPLeft) ? $param_values->CBPLeft : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'CBPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->CBPBGColor) ? $param_values->CBPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Font Size',
          'name' => 'CBPFontSize',
          'type' => 'text',
          'class' => '13',
          'value' => isset($param_values->CBPFontSize) ? $param_values->CBPFontSize : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Font Weight',
          'name' => 'CBPFontWeight',
          'type' => 'select',
          'options' => $font_weights,
          'class' => '',
          'value' => isset($param_values->CBPFontWeight) ? $param_values->CBPFontWeight : '',
          'after' => '',
        ),
        array(
          'label' => 'Color',
          'name' => 'CBPColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->CBPColor) ? $param_values->CBPColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Padding',
          'name' => 'CBPPadding',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->CBPPadding) ? $param_values->CBPPadding : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Margin',
          'name' => 'CBPMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->CBPMargin) ? $param_values->CBPMargin : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Border',
          'name' => 'CBPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'CBPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->CBPBorderColor) ? $param_values->CBPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'CBPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->CBPBorderType) ? $param_values->CBPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'CBPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->CBPBorderWidth) ? $param_values->CBPBorderWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Border Radius',
          'name' => 'CBPBorderRadius',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->CBPBorderRadius) ? $param_values->CBPBorderRadius : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Hover Parameters',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'CBHPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->CBHPBGColor) ? $param_values->CBHPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Color',
          'name' => 'CBHPColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->CBHPColor) ? $param_values->CBHPColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border',
          'name' => 'CBHPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'CBHPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->CBHPBorderColor) ? $param_values->CBHPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'CBHPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->CBHPBorderType) ? $param_values->CBHPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'CBHPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->CBHPBorderWidth) ? $param_values->CBHPBorderWidth : '',
          'after' => 'px</div>',
        ),
      ),
      'minimize' => array(
        array(
          'label' => '',
          'type' => 'panel',
          'class' => 'col-md-12',
          'label_class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'MBPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->MBPBGColor) ? $param_values->MBPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Font Size',
          'name' => 'MBPFontSize',
          'type' => 'text',
          'class' => '13',
          'value' => isset($param_values->MBPFontSize) ? $param_values->MBPFontSize : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Font Weight',
          'name' => 'MBPFontWeight',
          'type' => 'select',
          'options' => $font_weights,
          'class' => '',
          'value' => isset($param_values->MBPFontWeight) ? $param_values->MBPFontWeight : '',
          'after' => '',
        ),
        array(
          'label' => 'Color',
          'name' => 'MBPColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->MBPColor) ? $param_values->MBPColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Text Align',
          'name' => 'MBPTextAlign',
          'type' => 'select',
          'options' => $aligns,
          'class' => '',
          'value' => isset($param_values->MBPTextAlign) ? $param_values->MBPTextAlign : '',
          'after' => '',
        ),
        array(
          'label' => 'Padding',
          'name' => 'MBPPadding',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->MBPPadding) ? $param_values->MBPPadding : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Margin',
          'name' => 'MBPMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->MBPMargin) ? $param_values->MBPMargin : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'Border',
          'name' => 'MBPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'MBPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->MBPBorderColor) ? $param_values->MBPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'MBPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->MBPBorderType) ? $param_values->MBPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'MBPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->MBPBorderWidth) ? $param_values->MBPBorderWidth : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Border Radius',
          'name' => 'MBPBorderRadius',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->MBPBorderRadius) ? $param_values->MBPBorderRadius : '',
          'after' => 'px',
        ),
        array(
          'label' => 'Hover Parameters',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Background Color',
          'name' => 'MBHPBGColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->MBHPBGColor) ? $param_values->MBHPBGColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Color',
          'name' => 'MBHPColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->MBHPColor) ? $param_values->MBHPColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border',
          'name' => 'MBHPBorder',
          'type' => 'checkbox',
          'options' => $borders,
          'class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Border Color',
          'name' => 'MBHPBorderColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->MBHPBorderColor) ? $param_values->MBHPBorderColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Type',
          'name' => 'MBHPBorderType',
          'type' => 'select',
          'options' => $border_types,
          'class' => '',
          'value' => isset($param_values->MBHPBorderType) ? $param_values->MBHPBorderType : '',
          'after' => '',
        ),
        array(
          'label' => 'Border Width',
          'name' => 'MBHPBorderWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->MBHPBorderWidth) ? $param_values->MBHPBorderWidth : '',
          'after' => 'px</div>',
        ),
      ),
      'other' => array(
        array(
          'label' => 'Deactive Text',
          'type' => 'panel',
          'class' => 'col-md-12',
          'label_class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Color',
          'name' => 'OPDeInputColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->OPDeInputColor) ? $param_values->OPDeInputColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Font Style',
          'name' => 'OPFontStyle',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->OPFontStyle) ? $param_values->OPFontStyle : '',
          'after' => '',
        ),
        array(
          'label' => 'Required',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Color',
          'name' => 'OPRColor',
          'type' => 'text',
          'class' => 'color',
          'value' => isset($param_values->OPRColor) ? $param_values->OPRColor : '',
          'after' => '',
        ),
        array(
          'label' => 'Date Picker',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Background URL',
          'name' => 'OPDPIcon',
          'type' => 'text',
          'class' => '',
          'placeholder' => '',
          'value' => isset($param_values->OPDPIcon) ? $param_values->OPDPIcon : '',
          'after' => '',
        ),
        array(
          'label' => 'Background Repeat',
          'name' => 'OPDPRepeat',
          'type' => 'select',
          'options' => $bg_repeats,
          'class' => '',
          'value' => isset($param_values->OPDPRepeat) ? $param_values->OPDPRepeat : '',
          'after' => '',
        ),
        array(
          'label' => 'Background Position',
          'name1' => 'OPDPPos1',
          'name2' => 'OPDPPos2',
          'type' => '2text',
          'class' => 'fm-2text',
          'value1' => isset($param_values->OPDPPos1) ? $param_values->OPDPPos1 : '',
          'value2' => isset($param_values->OPDPPos2) ? $param_values->OPDPPos2 : '',
          'before1' => '',
          'before2' => '',
          'after' => '%/left..',
        ),
        array(
          'label' => 'Margin',
          'name' => 'OPDPMargin',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->OPDPMargin) ? $param_values->OPDPMargin : '',
          'after' => 'px/%',
        ),
        array(
          'label' => 'File Upload',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Background URL',
          'name' => 'OPFBgUrl',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->OPFBgUrl) ? $param_values->OPFBgUrl : '',
          'after' => '',
        ),
        array(
          'label' => 'Background Repeat',
          'name' => 'OPFBGRepeat',
          'type' => 'select',
          'options' => $bg_repeats,
          'class' => '',
          'value' => isset($param_values->OPFBGRepeat) ? $param_values->OPFBGRepeat : '',
          'after' => '',
        ),
        array(
          'label' => 'Background Position',
          'name1' => 'OPFPos1',
          'name2' => 'OPFPos2',
          'type' => '2text',
          'class' => 'fm-2text',
          'value1' => isset($param_values->OPFPos1) ? $param_values->OPFPos1 : '',
          'value2' => isset($param_values->OPFPos2) ? $param_values->OPFPos2 : '',
          'before1' => '',
          'before2' => '',
          'after' => '%/left..',
        ),
        array(
          'label' => 'Grading',
          'type' => 'label',
          'class' => 'fm-mini-title',
          'after' => '<br/>',
        ),
        array(
          'label' => 'Text Width',
          'name' => 'OPGWidth',
          'type' => 'text',
          'class' => '',
          'value' => isset($param_values->OPGWidth) ? $param_values->OPGWidth : '',
          'after' => 'px</div>',
        ),
      ),
      'custom_css' => array(
        array(
          'label' => '',
          'type' => 'panel',
          'class' => 'col-md-12',
          'label_class' => '',
          'after' => '',
        ),
        array(
          'label' => 'Custom CSS',
          'name' => 'CUPCSS',
          'type' => 'textarea',
          'class' => '',
          'value' => isset($param_values->CUPCSS) ? $param_values->CUPCSS : '',
          'after' => '</div>',
        ),
      ),
    );

    return $all_params;
  }

  public function save() {
    $message = $this->save_db();
    $page = WDW_FM_Library::get('page');
    WDW_FM_Library::fm_redirect(add_query_arg(array(
                                                'page' => $page,
                                                'task' => 'display',
                                                'message' => $message,
                                              ), admin_url('admin.php')));
  }

  public function apply() {
    $message = $this->save_db();
    $id = (int) $this->model->get_max_id();
    $current_id = (int) WDW_FM_Library::get('current_id', $id);
    $page = WDW_FM_Library::get('page');
    $active_tab = WDW_FM_Library::get('active_tab');
    $pagination = WDW_FM_Library::get('pagination-type');
    $form_type = WDW_FM_Library::get('form_type');
    WDW_FM_Library::fm_redirect(add_query_arg(array(
                                                'page' => $page,
                                                'task' => 'edit',
                                                'current_id' => $current_id,
                                                'message' => $message,
                                                'active_tab' => $active_tab,
                                                'pagination' => $pagination,
                                                'form_type' => $form_type,
                                              ), admin_url('admin.php')));
  }

  public function copy_themes() {
    global $wpdb;
    $theme_ids_col = $this->model->get_all_ids();
    foreach ( $theme_ids_col as $theme_id ) {
      if ( isset($_POST['check_' . $theme_id]) ) {
        $theme = $this->model->get_row_data($theme_id, 0);
        $title = $theme->title;
        $params = $theme->css;
        $version = $theme->version;
        $save = $this->model->insert_theme(array(
                                             'title' => $title,
                                             'css' => $params,
                                             'version' => $version,
                                             'default' => 0,
                                           ));
      }
    }
    if ( $save !== FALSE ) {
      $message = 1;
    }
    else {
      $message = 2;
    }
    $page = WDW_FM_Library::get('page');
    WDW_FM_Library::fm_redirect(add_query_arg(array(
                                                'page' => $page,
                                                'task' => 'display',
                                                'message' => $message,
                                              ), admin_url('admin.php')));
  }

  public function save_as_copy() {
    $message = $this->save_db_as_copy();
    $page = WDW_FM_Library::get('page');
    WDW_FM_Library::fm_redirect(add_query_arg(array(
                                                'page' => $page,
                                                'task' => 'display',
                                                'message' => $message,
                                              ), admin_url('admin.php')));
  }

  public function save_db() {
    global $wpdb;
    $id = (int) WDW_FM_Library::get('current_id', 0);
    $title = (isset($_POST['title']) ? esc_html(stripslashes($_POST['title'])) : '');
    $version = 2;
    $params = (isset($_POST['params']) ? stripslashes(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $_POST['params'])) : '');
    $default = (isset($_POST['default']) ? esc_html(stripslashes($_POST['default'])) : 0);
    if ( $id != 0 ) {
      $save = $this->model->update_formmaker_themes(array(
                                                      'title' => $title,
                                                      'css' => $params,
                                                      'default' => $default,
                                                    ), array( 'id' => $id ));
      $version = $this->model->get_theme_version($id);
    }
    else {
      $save = $this->model->insert_theme(array(
                                           'title' => $title,
                                           'css' => $params,
                                           'default' => $default,
                                           'version' => $version,
                                         ));
      $id = $wpdb->insert_id;
    }
    if ( $save !== FALSE ) {
      require_once WDFM()->plugin_dir . "/frontend/models/form_maker.php";
      $model_frontend = new FMModelForm_maker();
      $form_theme = json_decode(html_entity_decode($params), TRUE);
      $model_frontend->create_css($id, $form_theme, $version == 1, TRUE);

      return 1;
    }
    else {
      return 2;
    }
  }

  public function save_db_as_copy() {
    $id = (int) WDW_FM_Library::get('current_id', 0);
    $title = isset($_POST['title']) ? esc_html(stripslashes($_POST['title'])) : '';
    $params = isset($_POST['params']) ? stripslashes(preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $_POST['params'])) : '';
    $version = $this->model->get_theme_version($id);
    $save = $this->model->insert_theme(array(
                                         'title' => $title,
                                         'css' => $params,
                                         'version' => $version,
                                         'default' => 0,
                                       ));
    if ( $save !== FALSE ) {
      return 1;
    }
    else {
      return 2;
    }
  }
	/*
	* Set default.
	*
	* @param int $id
	*/
	public function setdefault( $id ) {
		global $wpdb;
		$this->model->update_formmaker_themes( array( 'default' => 0 ), array( 'default' => 1 ) );
		$save = $this->model->update_formmaker_themes( array( 'default' => 1 ), array( 'id' => $id ) );
		if ( $save !== FALSE ) {
		  $message = 7;
		}
		else {
		  $message = 2;
		}
		$page = WDW_FM_Library::get('page');
		WDW_FM_Library::fm_redirect(add_query_arg(array(
													'page' => $page,
													'task' => 'display',
													'message' => $message,
												  ), admin_url('admin.php')));
	  }
}
