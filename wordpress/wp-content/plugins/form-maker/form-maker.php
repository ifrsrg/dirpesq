<?php
/**
 * Plugin Name: Form Maker
 * Plugin URI: https://web-dorado.com/products/form-maker-wordpress.html
 * Description: This plugin is a modern and advanced tool for easy and fast creating of a WordPress Form. The backend interface is intuitive and user friendly which allows users far from scripting and programming to create WordPress Forms.
 * Version: 1.12.4
 * Author: WebDorado Form Builder Team
 * Author URI: https://web-dorado.com/
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('ABSPATH') || die('Access Denied');

final class WDFM {
  /**
   * The single instance of the class.
   */
  protected static $_instance = null;
  /**
   * Plugin directory path.
   */
  public $plugin_dir = '';
  /**
   * Plugin directory url.
   */
  public $plugin_url = '';
  /**
   * Plugin main file.
   */
  public $main_file = '';
  /**
   * Plugin version.
   */
  public $plugin_version = '';
  /**
   * Plugin database version.
   */
  public $db_version = '';
  /**
   * Plugin menu slug.
   */
  public $menu_slug = '';
  /**
   * Plugin menu slug.
   */
  public $prefix = '';
  public $css_prefix = '';
  public $js_prefix = '';

  public $nicename = '';
  public $nonce = 'nonce_fm';
  public $is_pro = false;

  /**
   * Main WDFM Instance.
   *
   * Ensures only one instance is loaded or can be loaded.
   *
   * @static
   * @return WDFM - Main instance.
   */
  public static function instance() {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  /**
   * WDFM Constructor.
   */
  public function __construct() {
    $this->define_constants();
    require_once($this->plugin_dir . '/framework/WDW_FM_Library.php');
    if (is_admin()) {
      require_once(wp_normalize_path($this->plugin_dir . '/admin/views/view.php'));
    }
    $this->add_actions();
    if (session_id() == '' || (function_exists('session_status') && (session_status() == PHP_SESSION_NONE))) {
      @session_start();
    }
  }

  /**
   * Define Constants.
   */
  private function define_constants() {
    $this->plugin_dir = WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__));
    $this->plugin_url = plugins_url(plugin_basename(dirname(__FILE__)));
    $this->main_file = plugin_basename(__FILE__);
    $this->plugin_version = '1.12.4';
    $this->db_version = '2.12.4';
    $this->menu_slug = 'manage_fm';
    $this->prefix = 'form_maker';
    $this->css_prefix = 'fm_';
    $this->js_prefix  = 'fm_';
    $this->nicename = __('Form Maker', $this->prefix);
    $this->menu_postfix = '';//todo:use in menu slugs. should be 'fmc' in contact form maker.
  }

  /**
   * Add actions.
   */
  private function add_actions() {
    add_action('init', array($this, 'init'), 9);
    add_action('admin_menu', array( $this, 'form_maker_options_panel' ) );

    add_action('wp_ajax_get_stats', array($this, 'form_maker')); //Show statistics
    add_action('wp_ajax_generete_csv', array($this, 'form_maker_ajax')); // Export csv.
    add_action('wp_ajax_generete_xml', array($this, 'form_maker_ajax')); // Export xml.
    add_action('wp_ajax_formmakerwdcaptcha', array($this, 'form_maker_ajax')); // Generete captcha image and save it code in session.
    add_action('wp_ajax_nopriv_formmakerwdcaptcha', array($this, 'form_maker_ajax')); // Generete captcha image and save it code in session for all users.
    add_action('wp_ajax_formmakerwdmathcaptcha', array($this, 'form_maker_ajax')); // Generete math captcha image and save it code in session.
    add_action('wp_ajax_nopriv_formmakerwdmathcaptcha', array($this, 'form_maker_ajax')); // Generete math captcha image and save it code in session for all users.
    add_action('wp_ajax_product_option', array($this, 'form_maker_ajax')); // Open product options on add paypal field.
    add_action('wp_ajax_FormMakerEditCountryinPopup', array($this, 'form_maker_ajax')); // Open country list.
    add_action('wp_ajax_FormMakerMapEditinPopup', array($this, 'form_maker_ajax')); // Open map in submissions.
    add_action('wp_ajax_FormMakerIpinfoinPopup', array($this, 'form_maker_ajax')); // Open ip in submissions.
    add_action('wp_ajax_show_matrix', array($this, 'form_maker_ajax')); // Edit matrix in submissions.
    add_action('wp_ajax_FormMakerSubmits', array($this, 'form_maker_ajax')); // Open submissions in submissions.
    add_action('wp_ajax_FormMakerSQLMapping', array($this, 'form_maker_ajax')); // Add/Edit SQLMaping from form options.

    add_action('wp_ajax_select_data_from_db', array($this, 'form_maker_ajax')); // select data from db.

    add_action('wp_ajax_manage_fm', array($this, 'form_maker_ajax')); //Show statistics

    if ( $this->is_pro ) {
      add_action('wp_ajax_paypal_info', array($this, 'form_maker_ajax')); // Paypal info in submissions page.
      add_action('wp_ajax_checkpaypal', array($this, 'form_maker_ajax')); // Notify url from Paypal Sandbox.
      add_action('wp_ajax_nopriv_checkpaypal', array($this, 'form_maker_ajax')); // Notify url from Paypal Sandbox for all users.
      add_action('wp_ajax_get_frontend_stats', array($this, 'form_maker_ajax_frontend')); //Show statistics frontend
      add_action('wp_ajax_nopriv_get_frontend_stats', array($this, 'form_maker_ajax_frontend')); //Show statistics frontend
      add_action('wp_ajax_frontend_show_map', array($this, 'form_maker_ajax_frontend')); //Show map frontend
      add_action('wp_ajax_nopriv_frontend_show_map', array($this, 'form_maker_ajax_frontend')); //Show map frontend
      add_action('wp_ajax_frontend_show_matrix', array($this, 'form_maker_ajax_frontend')); //Show matrix frontend
      add_action('wp_ajax_nopriv_frontend_show_matrix', array($this, 'form_maker_ajax_frontend')); //Show matrix frontend
      add_action('wp_ajax_frontend_paypal_info', array($this, 'form_maker_ajax_frontend')); //Show paypal info frontend
      add_action('wp_ajax_nopriv_frontend_paypal_info', array($this, 'form_maker_ajax_frontend')); //Show paypal info frontend
      add_action('wp_ajax_frontend_generate_csv', array($this, 'form_maker_ajax_frontend')); //generate csv frontend
      add_action('wp_ajax_nopriv_frontend_generate_csv', array($this, 'form_maker_ajax_frontend')); //generate csv frontend
      add_action('wp_ajax_frontend_generate_xml', array($this, 'form_maker_ajax_frontend')); //generate xml frontend
      add_action('wp_ajax_nopriv_frontend_generate_xml', array($this, 'form_maker_ajax_frontend')); //generate xml frontend
    }

    // Add media button to WP editor.
    add_action('wp_ajax_FMShortocde', array($this, 'form_maker_ajax'));
    add_filter('media_buttons_context', array($this, 'media_button'));

    add_action('admin_head', array($this, 'form_maker_admin_ajax'));//js variables for admin.

    // Form maker shortcodes.
    if ( !is_admin() ) {
      add_shortcode('FormPreview', array($this, 'fm_form_preview_shortcode'));
      add_shortcode('Form', array($this, 'fm_shortcode'));
      if ($this->is_pro) {
        add_shortcode('contact_form', array($this, 'fm_shortcode'));
        add_shortcode('wd_contact_form', array($this, 'fm_shortcode'));
      }
      add_shortcode('email_verification', array($this, 'fm_email_verification_shortcode'));
    }
    // Action to display not emedded type forms.
    if (!is_admin() && !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) {
      add_action('wp_footer', array($this, 'FM_front_end_main'));
    }

    // Form Maker Widget.
    if (class_exists('WP_Widget')) {
      require_once($this->plugin_dir . '/admin/controllers/Widget.php');
      add_action('widgets_init', create_function('', 'return register_widget("FMControllerWidget");'));
    }

    // Register fmemailverification post type
    add_action('init', array($this, 'register_fmemailverification_cpt')); 

	  // Register fmformpreview post type
    add_action('init', array($this, 'register_form_preview_cpt'));

    // Form maker activation.
    register_activation_hook(__FILE__, array($this, 'form_maker_activate'));
    if ( (!isset($_GET['action']) || $_GET['action'] != 'deactivate')
      && (!isset($_GET['page']) || $_GET['page'] != 'uninstall_fm') ) {
      add_action('admin_init', array($this, 'form_maker_activate'));
    }
    add_action('admin_notices', array($this, 'fm_topic'), 11);

    // Register scripts/styles.
    add_action('wp_enqueue_scripts', array($this, 'register_frontend_scripts'));
    add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));

    // Set per_page option for submissions.
    add_filter('set-screen-option', array($this, 'set_option_submissions'), 10, 3);

    // Check add-ons versions.
    add_action('admin_notices', array( $this, 'fm_check_addons_compatibility' ));

    add_action('plugins_loaded', array($this, 'plugins_loaded'));


  }

  /**
   * Wordpress init actions.
   */
  public function init() {
    ob_start();
    $this->fm_overview();
  }

  /**
   * Plugins loaded actions.
   */
  public function plugins_loaded() {
    // Languages localization.
    load_plugin_textdomain($this->prefix, FALSE, basename(dirname(__FILE__)) . '/languages');
    // Initialize add-ons.
    do_action('fm_init_addons');    
  }

  /**
   * Plugin menu.
   */
  public function form_maker_options_panel() {
    $parent_slug = $this->is_pro ? $this->menu_slug : null;
    if( $this->is_pro || get_option( "fm_subscribe_done" ) == 1 ) {
      add_menu_page($this->nicename, $this->nicename, 'manage_options', $this->menu_slug, array( $this, 'form_maker' ), $this->plugin_url . '/images/FormMakerLogo-16.png', 105.105);
      $parent_slug = $this->menu_slug;
    }
    add_submenu_page($parent_slug, __('Forms', $this->prefix), __('Forms', $this->prefix), 'manage_options', $this->menu_slug, array($this, 'form_maker'));
    $submissions_page = add_submenu_page($parent_slug, __('Submissions', $this->prefix), __('Submissions', $this->prefix), 'manage_options', 'submissions_fm', array($this, 'form_maker'));
    add_action('load-' . $submissions_page, array($this, 'submissions_per_page'));

    add_submenu_page(null, __('Blocked IPs', $this->prefix), __('Blocked IPs', $this->prefix), 'manage_options', 'blocked_ips_fm', array($this, 'form_maker'));
    add_submenu_page($parent_slug, __('Themes', $this->prefix), __('Themes', $this->prefix), 'manage_options', 'themes_fm', array($this, 'form_maker'));
	  add_submenu_page($parent_slug, __('Options', $this->prefix), __('Options', $this->prefix), 'manage_options', 'options_fm', array($this, 'form_maker'));
	  if (!$this->is_pro) {
      add_submenu_page($parent_slug, __('Pro Version', $this->prefix), __('Pro Version', $this->prefix), 'manage_options', 'licensing_fm', array($this, 'form_maker'));
    }
    add_submenu_page($parent_slug, __('Uninstall', $this->prefix), __('Uninstall', $this->prefix), 'manage_options', 'uninstall_fm', array($this, 'form_maker'));
	  add_submenu_page($parent_slug, __('Add-ons', $this->prefix), __('Add-ons', $this->prefix), 'manage_options', 'extensions_fm', array($this , 'fm_extensions'));
  }

  /**
   * Add per_page screen option for submissions page.
   */
  function submissions_per_page() {
    $option = 'per_page';
    $args_rates = array(
      'label' => __('Number of items per page:', $this->prefix),
      'default' => 20,
      'option' => 'fm_submissions_per_page'
    );
    add_screen_option( $option, $args_rates );
  }

  /**
   * Set per_page option for submissions page.
   *
   * @param $status
   * @param $option
   * @param $value
   * @return mixed
   */
  function set_option_submissions($status, $option, $value) {
    if ( 'fm_submissions_per_page' == $option ) return $value;
    return $status;
  }

  /**
   * Output for admin pages.
   */
  public function form_maker() {
    if (function_exists('current_user_can')) {
      if (!current_user_can('manage_options')) {
        die('Access Denied');
      }
    }
    else {
      die('Access Denied');
    }
    $page = WDW_FM_Library::get('page');
    if (($page != '') && (($page == 'manage_fm') || ($page == 'options_fm') || ($page == 'submissions_fm') || ($page == 'blocked_ips_fm') || ($page == 'themes_fm') || ($page == 'uninstall_fm') || ($page == 'FMShortocde') || ($page == 'extensions_fm') || (!$this->is_pro && $page == 'licensing_fm'))) {
      // This ugly span is here to hide admin output while css files are not loaded. Temporary.
      // todo: Remove span somehow.
      echo '<div id="fm_loading"></div>';
      echo '<span id="fm_admin_container" style="display: none;">';
      require_once ($this->plugin_dir . '/admin/controllers/' . ucfirst(strtolower($page)) . '.php');
      $controller_class = 'FMController' . ucfirst(strtolower($page));
      $controller = new $controller_class();
      $controller->execute();
      echo '</span>';
    }
  }
  
  /**
   * Output for Add-on pages.
   */
  public function fm_extensions() {
    if ( function_exists('current_user_can') ) {
      if ( !current_user_can('manage_options') ) {
        die('Access Denied');
      }
    }
    else {
      die('Access Denied');
    }
    require_once($this->plugin_dir . '/featured/featured.php');
    fm_extensions_page('form-maker');
  }

  /**
   * Register Admin styles/scripts.
   */
  public function register_admin_scripts() {
    // Admin styles.
    wp_register_style('fm-tables', $this->plugin_url . '/css/form_maker_tables.css', array(), $this->plugin_version);
    wp_register_style('fm-first', $this->plugin_url . '/css/form_maker_first.css', array(), $this->plugin_version);
    wp_register_style('fm-phone_field_css', $this->plugin_url . '/css/intlTelInput.css', array(), $this->plugin_version);
    wp_register_style('fm-jquery-ui', $this->plugin_url . '/css/jquery-ui-1.10.3.custom.css', array(), $this->plugin_version);
    wp_register_style('fm-jquery-ui-spinner', $this->plugin_url . '/css/jquery-ui-spinner.css', array(), $this->plugin_version);
    wp_register_style('fm-style', $this->plugin_url . '/css/style.css', array(), $this->plugin_version);
    wp_register_style('fm-codemirror', $this->plugin_url . '/css/codemirror.css', array(), $this->plugin_version);
    wp_register_style('fm-layout', $this->plugin_url . '/css/form_maker_layout.css', array(), $this->plugin_version);
    wp_register_style('fm-bootstrap', $this->plugin_url . '/css/fm-bootstrap.css', array(), $this->plugin_version);
    wp_register_style('fm-colorpicker', $this->plugin_url . '/css/spectrum.css', array(), $this->plugin_version);
    if ($this->is_pro) {
      wp_register_style('jquery.fancybox', $this->plugin_url . '/js/fancybox/jquery.fancybox.css', array(), '2.1.5');
    }
    // Add-ons page
    wp_register_style('fm-featured', $this->plugin_url . '/featured/style.css', array(), $this->plugin_version);
    wp_register_style('fm-featured-admin', $this->plugin_url . '/featured/admin.css', array(), $this->plugin_version);

    // Admin scripts.
    $fm_settings = get_option('fm_settings');
    $google_map_key = !empty($fm_settings['map_key']) ? '&key=' . $fm_settings['map_key'] : '';

    wp_register_script('google-maps', 'https://maps.google.com/maps/api/js?v=3.exp' . $google_map_key);
    wp_register_script('fm-gmap_form', $this->plugin_url . '/js/if_gmap_back_end.js', array(), $this->plugin_version);

    wp_register_script('fm-phone_field', $this->plugin_url . '/js/intlTelInput.js', array(), '11.0.0');

    wp_register_script('fm-admin', $this->plugin_url . '/js/form_maker_admin.js', array(), $this->plugin_version);
    wp_register_script('fm-manage', $this->plugin_url . '/js/form_maker_manage.js', array(), $this->plugin_version);
    wp_register_script('fm-manage-edit', $this->plugin_url . '/js/form_maker_manage_edit.js', array(), $this->plugin_version);
    wp_register_script('fm-formmaker_div', $this->plugin_url . '/js/formmaker_div.js', array(), $this->plugin_version);
    wp_register_script('fm-form-options', $this->plugin_url . '/js/form_maker_form_options.js', array(), $this->plugin_version);
    wp_register_script('fm-form-advanced-layout', $this->plugin_url . '/js/form_maker_form_advanced_layout.js', array(), $this->plugin_version);
    wp_register_script('fm-add-fields', $this->plugin_url . '/js/add_field.js', array('fm-formmaker_div'), $this->plugin_version);
    wp_localize_script('fm-add-fields', 'form_maker', array(
      'plugin_url' => $this->plugin_url,
      'nothing_found' => __('Nothing found.', $this->prefix),
      'captcha_created' => __('The captcha already has been created.', $this->prefix),
      'update' => __('Update', $this->prefix),
      'add' => __('Add', $this->prefix),
      'add_field' => __('Add Field', $this->prefix),
      'edit_field' => __('Edit Field', $this->prefix),
      'disabled1' => __('This field type is disabled in free version.', $this->prefix),
      'disabled2' => __('Please upgrade to Paid version.', $this->prefix),
      'stripe1' => __('Please install ', $this->prefix),
      'stripe2' => __(' add-on to use this feature.', $this->prefix),
      'stripe3' => __('To use this feature, please go to Form Options > Payment Options and select "Stripe" as the Payment Method.', $this->prefix),
      'sunday' => __('Sunday', $this->prefix),
      'monday' => __('Monday', $this->prefix),
      'tuesday' => __('Tuesday', $this->prefix),
      'wednesday' => __('Wednesday', $this->prefix),
      'thursday' => __('Thursday', $this->prefix),
      'friday' => __('Friday', $this->prefix),
      'saturday' => __('Saturday', $this->prefix),
    ));

    wp_register_script('fm-codemirror', $this->plugin_url . '/js/layout/codemirror.js', array(), '2.3');
    wp_register_script('fm-clike', $this->plugin_url . '/js/layout/clike.js', array(), '1.0.0');
    wp_register_script('fm-formatting', $this->plugin_url . '/js/layout/formatting.js', array(), '1.0.0');
    wp_register_script('fm-css', $this->plugin_url . '/js/layout/css.js', array(), '1.0.0');
    wp_register_script('fm-javascript', $this->plugin_url . '/js/layout/javascript.js', array(), '1.0.0');
    wp_register_script('fm-xml', $this->plugin_url . '/js/layout/xml.js', array(), '1.0.0');
    wp_register_script('fm-php', $this->plugin_url . '/js/layout/php.js', array(), '1.0.0');
    wp_register_script('fm-htmlmixed', $this->plugin_url . '/js/layout/htmlmixed.js', array(), '1.0.0');

    wp_register_script('fm-colorpicker', $this->plugin_url . '/js/spectrum.js', array(), $this->plugin_version);

    wp_register_script('fm-admin', $this->plugin_url . '/js/form_maker_admin.js', array(), $this->plugin_version);
    wp_register_script('fm-themes', $this->plugin_url . '/js/themes.js', array(), $this->plugin_version);

    wp_register_script('fm-submissions', $this->plugin_url . '/js/form_maker_submissions.js', array(), $this->plugin_version);
    wp_register_script('fm-ng-js', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular.min.js', array(), '1.5.0');

    wp_localize_script('fm-admin', 'form_maker', array(
      'delete_confirmation' => __('Do you want to delete selected items?', $this->prefix),
      'select_at_least_one_item' => __('You must select at least one item.', $this->prefix),
      ));
    if ($this->is_pro) {
      wp_register_script('jquery.fancybox.pack', $this->plugin_url . '/js/fancybox/jquery.fancybox.pack.js', array(), '2.1.5');
    }
    else {
      wp_register_style('fm-deactivate-css',  $this->plugin_url . '/wd/assets/css/deactivate_popup.css', array(), $this->plugin_version);
      wp_register_script('fm-deactivate-popup', $this->plugin_url . '/wd/assets/js/deactivate_popup.js', array(), $this->plugin_version, true );
      $admin_data = wp_get_current_user();
      wp_localize_script( 'fm-deactivate-popup', 'fmWDDeactivateVars', array(
        "prefix" => "fm" ,
        "deactivate_class" => 'fm_deactivate_link',
        "email" => $admin_data->data->user_email,
        "plugin_wd_url" => "https://web-dorado.com/products/wordpress-form.html",
      ));
      wp_register_style('fm-license', $this->plugin_url . '/css/license.css', array(), $this->plugin_version);
    }
  }

  /**
   * Admin ajax scripts.
   */
  public function register_admin_ajax_scripts() {
    wp_register_style('fm-tables', $this->plugin_url . '/css/form_maker_tables.css', array(), $this->plugin_version);
    wp_register_style('fm-style', $this->plugin_url . '/css/style.css', array(), $this->plugin_version);
    wp_register_style('fm-jquery-ui', $this->plugin_url . '/css/jquery-ui-1.10.3.custom.css', array(), $this->plugin_version);

    wp_register_script('fm-shortcode', $this->plugin_url . '/js/shortcode.js', array('jquery'), $this->plugin_version);

    $fm_settings = get_option('fm_settings');
    $google_map_key = !empty($fm_settings['map_key']) ? '&key=' . $fm_settings['map_key'] : '';

    wp_register_script('google-maps', 'https://maps.google.com/maps/api/js?v=3.exp' . $google_map_key);
    wp_register_script('fm-gmap_form', $this->plugin_url . '/js/if_gmap_back_end.js', array(), $this->plugin_version);

    wp_localize_script('fm-shortcode', 'form_maker', array(
      'insert_form' => __('You must select a form', $this->prefix),
      'update' => __('Update', $this->prefix),
    ));
  }

  /**
   * admin-ajax actions for admin.
   */
  public function form_maker_ajax() {
    $page = WDW_FM_Library::get('action');
    if ( $page != 'formmakerwdcaptcha' && $page != 'formmakerwdmathcaptcha' && $page != 'checkpaypal' ) {
      if ( function_exists('current_user_can') ) {
        if ( !current_user_can('manage_options') ) {
          die('Access Denied');
        }
      }
      else {
        die('Access Denied');
      }
    }
    if ( $page != '' ) {
      if ( !is_file($this->plugin_dir . '/admin/controllers/' . ucfirst($page) . '.php') ) {
        die('The file <b> ' . ucfirst($page) . ' </b> not found.');
      }
      $this->register_admin_ajax_scripts();
      require_once($this->plugin_dir . '/admin/controllers/' . ucfirst($page) . '.php');
      $controller_class = 'FMController' . ucfirst($page);
      $controller = new $controller_class();
      $controller->execute();
    }
  }

  /**
   * admin-ajax actions for site.
   */
  public function form_maker_ajax_frontend() {
    $page = WDW_FM_Library::get('page');
    if ($page != '') {
      $this->register_frontend_ajax_scripts();
      require_once ($this->plugin_dir . '/frontend/controllers/' . $page . '.php');
      $controller_class = 'FMController' . ucfirst($page);
      $controller = new $controller_class();
      $controller->execute();
    }
  }

  /**
   * Javascript variables for admin.
   * todo: change to array.
   */
  public function form_maker_admin_ajax() {
    $upload_dir = wp_upload_dir();
    ?>
    <script>
      var fm_site_url = '<?php echo site_url() .'/'; ?>';
      var admin_url = '<?php echo admin_url('admin.php'); ?>';
      var plugin_url = '<?php echo $this->plugin_url; ?>';
      var upload_url = '<?php echo $upload_dir['baseurl']; ?>';
      var nonce_fm = '<?php echo wp_create_nonce($this->nonce); ?>';
      // Set shortcode popup dimensions.
      function fm_set_shortcode_popup_dimensions(tbWidth, tbHeight) {
        var tbWindow = jQuery('#TB_window'), H = jQuery(window).height(), W = jQuery(window).width(), w, h;
        w = (tbWidth && tbWidth < W - 90) ? tbWidth : W - 40;
        h = (tbHeight && tbHeight < H - 60) ? tbHeight : H - 40;
        if (tbWindow.size()) {
          tbWindow.width(w).height(h);
          jQuery('#TB_iframeContent').width(w).height(h - 27);
          tbWindow.css({'margin-left': '-' + parseInt((w / 2), 10) + 'px'});
          if (typeof document.body.style.maxWidth != 'undefined') {
            tbWindow.css({'top': (H - h) / 2, 'margin-top': '0'});
          }
        }
      }
    </script>
    <?php
  }

  /**
   * Form maker preview shortcode output.
   *
   * @return mixed|string
   */
  public function fm_form_preview_shortcode() {
    // check is adminstrator
    if ( !current_user_can('manage_options') ) {
      echo __('Sorry, you are not allowed to access this page.', $this->prefix);
    }
    else {
      ob_start();
      $id = WDW_FM_Library::get('form_id', 0);
	    $display_options = WDW_FM_Library::display_options( $id );
      $type = $display_options->type;

      $attrs = array( 'id' => $id );
      $this->FM_front_end_main($attrs, $type); // embedded popover topbar scrollbox

      return str_replace(array( "\r\n", "\n", "\r" ), '', ob_get_clean());
    }
  }

  /**
   * Form maker shortcode output.
   *
   * @param $attrs
   * @return mixed|string
   */
  public function fm_shortcode($attrs) {
    //todo: use fm_shortcode option for js enqueue.
    ob_start();
    $this->FM_front_end_main($attrs, 'embedded');
    return str_replace(array("\r\n", "\n", "\r"), '', ob_get_clean());
  }

  /**
   * Form maker output.
   *
   * @param array $params
   * @param string $type
   */
  public function FM_front_end_main($params = array(), $type = '') {
    if ( !isset($params['type']) ) {
      $form_id = isset($params['id']) ? (int) $params['id'] : 0;
      wd_form_maker($form_id, $type);
    }
    else if ($this->is_pro) {
      $shortcode_deafults = array(
        'id' => 0,
        'startdate' => '',
        'enddate' => '',
        'submit_date' => '',
        'submitter_ip' => '',
        'username' => '',
        'useremail' => '',
        'form_fields' => '1',
        'show' => '1,1,1,1,1,1,1,1,1,1',
      );
      shortcode_atts($shortcode_deafults, $params);

      require_once($this->plugin_dir . '/frontend/controllers/form_submissions.php');
      $controller = new FMControllerForm_submissions();
		
      $submissions = $controller->execute($params);

      echo $submissions;
    }
    return;
  }

  /**
   * Email verification output.
   */
  public function fm_email_verification_shortcode() {
    require_once($this->plugin_dir . '/frontend/controllers/verify_email.php');
    $controller_class = 'FMControllerVerify_email';
    $controller = new $controller_class();
    $controller->execute();
  }

  /**
   * Register email verification custom post type.
   */
  public function register_fmemailverification_cpt() {
    $args = array(
      'public' => true,
      'show_in_menu' => false,
      'create_posts' => 'do_not_allow',
      'capabilities' => array(
        'create_posts' => FALSE,
        'edit_post' => 'edit_posts',
        'read_post' => 'edit_posts',
        'delete_posts' => FALSE,
      )
    );

    register_post_type('fmemailverification', $args);
    if (!get_option('fm_emailverification')) {
      flush_rewrite_rules();
      add_option('fm_emailverification', true);
    }
  }
  
    /**
   * Register form preview custom post type.
   */
  public function register_form_preview_cpt() {
    $args = array(
      'public' => true,
      'show_in_menu' => false,
      'create_posts' => 'do_not_allow',
      'capabilities' => array(
        'create_posts' => FALSE,
        'edit_post' => 'edit_posts',
        'read_post' => 'edit_posts',
        'delete_posts' => FALSE,
      )
    );

    register_post_type('form-maker', $args);
  }  

  /**
   * Frontend scripts/styles.
   */
  public function register_frontend_scripts() {
    wp_register_style('fm-jquery-ui', $this->plugin_url . '/css/jquery-ui-1.10.3.custom.css', array(), $this->plugin_version);
    wp_register_style('fm-jquery-ui-spinner', $this->plugin_url . '/css/jquery-ui-spinner.css', array(), $this->plugin_version);

    $fm_settings    = get_option('fm_settings');
    $google_map_key = !empty($fm_settings['map_key']) ? '&key=' . $fm_settings['map_key'] : '';
    wp_register_script('google-maps', 'https://maps.google.com/maps/api/js?v=3.exp' . $google_map_key);

    wp_register_script('fm-phone_field', $this->plugin_url . '/js/intlTelInput.js', array(), $this->plugin_version);

    wp_register_style('fm-phone_field_css', $this->plugin_url . '/css/intlTelInput.css', array(), $this->plugin_version);
    wp_register_style('fm-frontend', $this->plugin_url . '/css/form_maker_frontend.css', array(), $this->plugin_version);

    wp_register_script('fm-frontend', $this->plugin_url . '/js/main_div_front_end.js', array(), $this->plugin_version);
    wp_register_script('fm-gmap_form', $this->plugin_url . '/js/if_gmap_front_end.js', array(), $this->plugin_version);

    wp_localize_script('fm-frontend', 'fm_objectL10n', array(
      'plugin_url' => $this->plugin_url,
      'form_maker_admin_ajax' => admin_url('admin-ajax.php'),
      'fm_file_type_error' => addslashes(__('Can not upload this type of file', $this->prefix)),
      'fm_field_is_required' => addslashes(__('Field is required', $this->prefix)),
      'fm_min_max_check_1' => addslashes((__('The ', $this->prefix))),
      'fm_min_max_check_2' => addslashes((__(' value must be between ', $this->prefix))),
      'fm_spinner_check' => addslashes((__('Value must be between ', $this->prefix))),
      'fm_clear_data' => addslashes((__('Are you sure you want to clear saved data?', $this->prefix))),
      'fm_grading_text' => addslashes(__('Your score should be less than', $this->prefix)),
    ));

    $google_fonts = WDW_FM_Library::get_google_fonts();
    $fonts = implode("|", str_replace(' ', '+', $google_fonts));
    wp_register_style('fm-googlefonts', 'https://fonts.googleapis.com/css?family=' . $fonts . '&subset=greek,latin,greek-ext,vietnamese,cyrillic-ext,latin-ext,cyrillic', null, null);

    wp_register_style('fm-animate', $this->plugin_url . '/css/fm-animate.css', array(), $this->plugin_version);

    wp_register_script('fm-g-recaptcha', 'https://www.google.com/recaptcha/api.js?onload=fmRecaptchaInit&render=explicit');

    // Register admin styles to use in frontend submissions.
    wp_register_script('gmap_form_back', $this->plugin_url . '/js/if_gmap_back_end.js', array(), $this->plugin_version);

    if ($this->is_pro) {
      wp_register_script('fm-file-upload', $this->plugin_url . '/js/file-upload.js', array(), $this->plugin_version);
      wp_register_style('fm-submissions_css', $this->plugin_url . '/css/style_submissions.css', array(), $this->plugin_version);
    }
  }

  /**
   * Frontend ajax scripts.
   */
  public function register_frontend_ajax_scripts() {
    $fm_settings    = get_option('fm_settings');
    $google_map_key = !empty($fm_settings['map_key']) ? '&key=' . $fm_settings['map_key'] : '';
    wp_register_script('google-maps', 'https://maps.google.com/maps/api/js?v=3.exp' . $google_map_key);
    wp_register_script('fm-gmap_form_back', $this->plugin_url . '/js/if_gmap_back_end.js', array(), $this->plugin_version);
  }

  /**
   * Activate plugin.
   */
  public function form_maker_activate() {
    if ($this->is_pro) {
      deactivate_plugins("contact-form-maker/contact-form-maker.php");
      delete_transient('fm_update_check');
    }
    $version = get_option("wd_form_maker_version");
    $new_version = $this->db_version;
    global $wpdb;
    if (!$version) {
      add_option("wd_form_maker_version", $new_version, '', 'no');
      if ($wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "formmaker'") == $wpdb->prefix . "formmaker") {
        deactivate_plugins($this->main_file);
        wp_die(__("Oops! Seems like you installed the update over a quite old version of Form Maker. Unfortunately, this version is deprecated.<br />Please contact Web-Dorado support team at support@web-dorado.com. We will take care of this issue as soon as possible.", $this->prefix));
      }
      else {
        require_once $this->plugin_dir . "/form_maker_insert.php";
        WDFMInsert::form_maker_insert();
        $email_verification_post = array(
          'post_title' => 'Email Verification',
          'post_content' => '[email_verification]',
          'post_status' => 'publish',
          'post_author' => 1,
          'post_type' => 'fmemailverification',
        );
        $mail_verification_post_id = wp_insert_post($email_verification_post);

        add_option('fm_settings', array('public_key' => '', 'private_key' => '', 'csv_delimiter' => ',', 'map_key' => ''));
        $wpdb->update($wpdb->prefix . "formmaker", array(
          'mail_verification_post_id' => $mail_verification_post_id,
        ), array('id' => 1), array(
          '%d',
        ), array('%d'));
      }
    }
    elseif (version_compare($version, $new_version, '<')) {
      $version = substr_replace($version, '1.', 0, 2);
      require_once $this->plugin_dir . "/form_maker_update.php";
      $mail_verification_post_ids = $wpdb->get_results($wpdb->prepare('SELECT mail_verification_post_id FROM ' . $wpdb->prefix . 'formmaker WHERE mail_verification_post_id!="%d"', 0));
      if ($mail_verification_post_ids) {
        foreach ($mail_verification_post_ids as $mail_verification_post_id) {
          $update_email_ver_post_type = array(
            'ID' => (int)$mail_verification_post_id->mail_verification_post_id,
            'post_type' => 'fmemailverification',
          );
          wp_update_post($update_email_ver_post_type);
        }
      }
      WDFMUpdate::form_maker_update($version);
      update_option("wd_form_maker_version", $new_version);

      if (FALSE === $fm_settings = get_option('fm_settings')) {
        $recaptcha_keys = $wpdb->get_row('SELECT `public_key`, `private_key` FROM ' . $wpdb->prefix . 'formmaker WHERE public_key!="" and private_key!=""', ARRAY_A);
        $public_key = isset($recaptcha_keys['public_key']) ? $recaptcha_keys['public_key'] : '';
        $private_key = isset($recaptcha_keys['private_key']) ? $recaptcha_keys['private_key'] : '';
        add_option('fm_settings', array('public_key' => $public_key, 'private_key' => $private_key, 'csv_delimiter' => ',', 'map_key' => ''));
      }
    }
  }

  /**
   * Form maker topics at page top.
   */
  public function fm_topic() {
    $page = isset($_GET['page']) ? $_GET['page'] : '';
    $task = isset($_REQUEST['task']) ? $_REQUEST['task'] : '';
    $user_guide_link = 'https://web-dorado.com/wordpress-form-maker/';
    $support_forum_link = 'https://wordpress.org/support/plugin/form-maker';
    $pro_icon = $this->plugin_url . '/images/wd_logo.png';
    $pro_link = 'https://web-dorado.com/files/fromFormMaker.php';
    $support_icon = $this->plugin_url . '/images/support.png';
    $prefix = $this->prefix;
    switch ($page) {
      case 'blocked_ips_fm': {
        $help_text = 'block IPs';
        $user_guide_link .= 'blocking-ips.html';
        break;
      }
      case 'options_fm': {
        $help_text = 'edit form settings';
        $user_guide_link .= '';
        break;
      }
      case 'licensing_fm': {
        $help_text = '';
        $user_guide_link .= '';
        break;
      }
      case 'manage_fm': {
        switch ($task) {
          case 'edit':
          case 'edit_old': {
            $help_text = 'add fields to your form';
            $user_guide_link .= 'description-of-form-fields.html';
            break;
          }
          case 'form_options':
          case 'form_options_old': {
            $help_text = 'edit form options';
            $user_guide_link .= 'configuring-form-options.html';
            break;
          }
          default: {
            $help_text = 'create, edit forms';
            $user_guide_link .= 'creating-form.html';
          }
        }
        break;
      }
      case 'submissions_fm': {
        $help_text = 'view and manage form submissions';
        $user_guide_link .= 'managing-submissions.html';
        break;
      }
      case 'themes_fm': {
        $help_text = 'create, edit form themes';
        $user_guide_link .= '';
        break;
      }
      default: {
        return '';
      }
    }
    ob_start();
    ?>
    <style>
      .wd_topic {
        background-color: #ffffff;
        border: none;
        box-sizing: border-box;
        clear: both;
        color: #6e7990;
        font-size: 14px;
        font-weight: bold;
        line-height: 44px;
        padding: 0 0 0 15px;
        vertical-align: middle;
        width: 98%;
      }
      .wd_topic .wd_help_topic {
        float: left;
      }
      .wd_topic .wd_help_topic a {
        color: #0073aa;
      }
      .wd_topic .wd_help_topic a:hover {
        color: #00A0D2;
      }
      .wd_topic .wd_support {
        float: right;
        margin: 0 10px;
      }
      .wd_topic .wd_support img {
        vertical-align: middle;
      }
      .wd_topic .wd_support a {
        text-decoration: none;
        color: #6E7990;
      }
      .wd_topic .wd_pro {
        float: right;
        padding: 0;
      }
      .wd_topic .wd_pro a {
        border: none;
        box-shadow: none !important;
        text-decoration: none;
      }
      .wd_topic .wd_pro img {
        border: none;
        display: inline-block;
        vertical-align: middle;
      }
      .wd_topic .wd_pro a,
      .wd_topic .wd_pro a:active,
      .wd_topic .wd_pro a:visited,
      .wd_topic .wd_pro a:hover {
        background-color: #D8D8D8;
        color: #175c8b;
        display: inline-block;
        font-size: 11px;
        font-weight: bold;
        padding: 0 10px;
        vertical-align: middle;
      }
    </style>
    <div class="update-nag wd_topic">
      <?php
      if ($help_text) {
        ?>
      <span class="wd_help_topic">
        <?php echo sprintf(__('This section allows you to %s.', $prefix), $help_text); ?>
            <a target="_blank" href="<?php echo $user_guide_link; ?>">
          <?php _e('Read More in User Manual', $prefix); ?>
        </a>
      </span>
        <?php
      }
      if (!$this->is_pro) {
        $text = strtoupper(__('Upgrade to paid version', $prefix));
        ?>
        <div class="wd_pro">
          <a target="_blank" href="<?php echo $pro_link; ?>">
            <img alt="web-dorado.com" title="<?php echo $text; ?>" src="<?php echo $pro_icon; ?>" />
            <span><?php echo $text; ?></span>
          </a>
        </div>
        <?php
      }
      if (FALSE) {
        ?>
        <span class="wd_support">
      <a target="_blank" href="<?php echo $support_forum_link; ?>">
        <img src="<?php echo $support_icon; ?>" />
        <?php _e('Support Forum', $prefix); ?>
      </a>
    </span>
        <?php
      }
      ?>
    </div>
    <?php
    echo ob_get_clean();
  }

  /**
   * Form maker overview.
   */
  public function fm_overview() {
    if (is_admin() && !isset($_REQUEST['ajax'])) {
      if (!class_exists("DoradoWeb")) {
        require_once($this->plugin_dir . '/wd/start.php');
      }
      global $fm_options;
      $fm_options = array(
        "prefix" => "fm",
        "wd_plugin_id" => 31,
        "plugin_title" => "Form Maker",
        "plugin_wordpress_slug" => "form-maker",
        "plugin_dir" => $this->plugin_dir,
        "plugin_main_file" => __FILE__,
        "description" => __('Form Maker plugin is a modern and advanced tool for easy and fast creating of a WordPress Form. The backend interface is intuitive and user friendly which allows users far from scripting and programming to create WordPress Forms.', $this->prefix),
        // from web-dorado.com
        "plugin_features" => array(
          0 => array(
            "title" => __("Easy to Use", $this->prefix),
            "description" => __("This responsive form maker plugin is one of the most easy-to-use form builder solutions available on the market. Simple, yet powerful plugin allows you to quickly and easily build any complex forms.", $this->prefix),
          ),
          1 => array(
            "title" => __("Customizable Fields", $this->prefix),
            "description" => __("All the fields of Form Maker plugin are highly customizable, which allows you to change almost every detail in the form and make it look exactly like you want it to be.", $this->prefix),
          ),
          2 => array(
            "title" => __("Submissions", $this->prefix),
            "description" => __("You can view the submissions for each form you have. The plugin allows to view submissions statistics, filter submission data and export in csv or xml formats.", $this->prefix),
          ),
          3 => array(
            "title" => __("Multi-Page Forms", $this->prefix),
            "description" => __("With the form builder plugin you can create muilti-page forms. Simply use the page break field to separate the pages in your forms.", $this->prefix),
          ),
          4 => array(
            "title" => __("Themes", $this->prefix),
            "description" => __("The WordPress Form Maker plugin comes with a wide range of customizable themes. You can choose from a list of existing themes or simply create the one that better fits your brand and website.", $this->prefix),
          )
        ),
        // user guide from web-dorado.com
        "user_guide" => array(
          0 => array(
            "main_title" => __("Installing", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-form-maker/installing.html",
            "titles" => array()
          ),
          1 => array(
            "main_title" => __("Creating a new Form", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-form-maker/creating-form.html",
            "titles" => array()
          ),
          2 => array(
            "main_title" => __("Configuring Form Options", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-form-maker/configuring-form-options.html",
            "titles" => array()
          ),
          3 => array(
            "main_title" => __("Description of The Form Fields", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-form-maker/description-of-form-fields.html",
            "titles" => array(
              array(
                "title" => __("Selecting Options from Database", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-form-maker/description-of-form-fields/selecting-options-from-database.html",
              ),
            )
          ),
          4 => array(
            "main_title" => __("Publishing the Created Form", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-form-maker/publishing-form.html",
            "titles" => array()
          ),
          5 => array(
            "main_title" => __("Blocking IPs", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-form-maker/blocking-ips.html",
            "titles" => array()
          ),
          6 => array(
            "main_title" => __("Managing Submissions", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-form-maker/managing-submissions.html",
            "titles" => array()
          ),
          7 => array(
            "main_title" => __("Publishing Submissions", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-form-maker/publishing-submissions.html",
            "titles" => array()
          ),
        ),
        "video_youtube_id" => "tN3_c6MhqFk",  // e.g. https://www.youtube.com/watch?v=acaexefeP7o youtube id is the acaexefeP7o
        "plugin_wd_url" => "https://web-dorado.com/products/wordpress-form.html",
        "plugin_wd_demo_link" => "http://wpdemo.web-dorado.com",
        "plugin_wd_addons_link" => "https://web-dorado.com/products/wordpress-form/add-ons.html",
        "after_subscribe" => admin_url('admin.php?page=overview_fm'), // this can be plagin overview page or set up page
        "plugin_wizard_link" => '',
        "plugin_menu_title" => "Form Maker",
        "plugin_menu_icon" => $this->plugin_url . '/images/FormMakerLogo-16.png',
        "deactivate" => !$this->is_pro,
        "subscribe" => !$this->is_pro,
        "custom_post" => 'manage_fm',
        "menu_position" => null,
      );

      dorado_web_init($fm_options);
    }
  }

  /**
   * Add media button to Wp editor.
   *
   * @param $context
   *
   * @return string
   */
  function media_button($context) {
    ob_start();
    $url = add_query_arg(array('action' => 'FMShortocde', 'task' => 'form', 'TB_iframe' => '1'), admin_url('admin-ajax.php'));
    ?>
    <a onclick="tb_click.call(this); fm_set_shortcode_popup_dimensions(400, 140); return false;" href="<?php echo $url; ?>" class="button" title="<?php _e('Insert Form', $this->prefix); ?>">
      <span class="wp-media-buttons-icon" style="background: url('<?php echo $this->plugin_url; ?>/images/fm-media-form-button.png') no-repeat scroll left top rgba(0, 0, 0, 0);"></span>
      <?php _e('Add Form', $this->prefix); ?>
    </a>
    <?php
    $url = add_query_arg(array('action' => 'FMShortocde', 'task' => 'submissions', 'TB_iframe' => '1'), admin_url('admin-ajax.php'));
    ?>
    <a onclick="tb_click.call(this); fm_set_shortcode_popup_dimensions(500, 570); return false;" href="<?php echo $url; ?>" class="button" title="<?php _e('Insert submissions', $this->prefix); ?>">
      <span class="wp-media-buttons-icon" style="background: url('<?php echo $this->plugin_url; ?>/images/fm-media-submissions-button.png') no-repeat scroll left top rgba(0, 0, 0, 0);"></span>
      <?php _e('Add Submissions', $this->prefix); ?>
    </a>
    <?php
    $context .= ob_get_clean();

    return $context;
  }


  /**
   * Check add-ones version compatibility with FM.
   *
   */
  function  fm_check_addons_compatibility() {
    $add_ons = array(
      'form-maker-export-import' => array('version' => '2.0.7', 'file' => 'fm_exp_imp.php'),
      'form-maker-save-progress' => array('version' => '1.0.1', 'file' => 'fm_save.php'),
      'form-maker-conditional-emails' => array('version' => '1.0.1', 'file' => 'fm_conditional_emails.php'),
      'form-maker-pushover' => array('version' => '1.0.1', 'file' => 'fm_pushover.php'),
      'form-maker-mailchimp' => array('version' => '1.0.1', 'file' => 'fm_mailchimp.php'),
      'form-maker-reg' => array('version' => '1.1.0', 'file' => 'fm_reg.php'),
      'form-maker-post-generation' => array('version' => '1.0.2', 'file' => 'fm_post_generation.php'),
      'form-maker-dropbox-integration' => array('version' => '1.1.1', 'file' => 'fm_dropbox_integration.php'),
      'form-maker-gdrive-integration' => array('version' => '1.0.0', 'file' => 'fm_gdrive_integration.php'),
      'form-maker-pdf-integration' => array('version' => '1.0.3', 'file' => 'fm_pdf_integration.php'),
      'form-maker-stripe' => array('version' => '1.0.1', 'file' => 'fm_stripe.php'),
      'form-maker-calculator' => array('version' => '1.0.3', 'file' => 'fm_calculator.php'),
    );

    $add_ons_notice = array();
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');

    foreach ($add_ons as $add_on_key => $add_on_value) {
      $addon_path = plugin_dir_path( dirname(__FILE__) ) . $add_on_key . '/' . $add_on_value['file'];
      if (is_plugin_active($add_on_key . '/' . $add_on_value['file'])) {
        $addon = get_plugin_data($addon_path); // array
        if (version_compare($addon['Version'], $add_on_value['version'], '<=')) {   //compare versions
          deactivate_plugins($addon_path);
          array_push($add_ons_notice, $addon['Name']);
        }
      }
    }

    if (!empty($add_ons_notice)) {
      $this->fm_addons_compatibility_notice($add_ons_notice);
    }
  }

  /**
   * Incompatibility message.
   *
   * @param $add_ons_notice
   */
  function fm_addons_compatibility_notice($add_ons_notice) {
    $addon_names = implode($add_ons_notice, ', ');
    $count = count($add_ons_notice);
    $single = __('Please update the %s add-on to start using.', $this->prefix);
    $plural = __('Please update the %s add-ons to start using.', $this->prefix);
    echo '<div class="error"><p>' . sprintf( _n($single, $plural, $count, $this->prefix), $addon_names ) .'</p></div>';
  }
}

/**
 * Main instance of WDFM.
 *
 * @return WDFM The main instance to prevent the need to use globals.
 */
function WDFM() {
  return WDFM::instance();
}

WDFM();

/**
 * Form maker output.
 *
 * @param $id
 * @param string $type
 */
function wd_form_maker($id, $type = 'embedded') {
  require_once (WDFM()->plugin_dir . '/frontend/controllers/form_maker.php');
  $controller = new FMControllerForm_maker();
  $form = $controller->execute($id, $type);
  echo $form;
}

/**
 * Show notice to install backup plugin
 */
function fm_bp_install_notice() {
  // Remove old notice.
  if ( get_option('wds_bk_notice_status') !== FALSE ) {
    update_option('wds_bk_notice_status', '1', 'no');
  }

  // Show notice only on plugin pages.
  if ( !isset($_GET['page']) || strpos(esc_html($_GET['page']), '_fm') === FALSE ) {
    return '';
  }

  $meta_value = get_option('wd_bk_notice_status');
  if ( $meta_value === '' || $meta_value === FALSE ) {
    ob_start();
    $prefix = WDFM()->prefix;
    $nicename = WDFM()->nicename;
    $url = WDFM()->plugin_url;
    $dismiss_url = add_query_arg(array( 'action' => 'wd_bp_dismiss' ), admin_url('admin-ajax.php'));
    $install_url = esc_url(wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=backup-wd'), 'install-plugin_backup-wd'));
    ?>
    <div class="notice notice-info" id="wd_bp_notice_cont">
      <p>
        <img id="wd_bp_logo_notice" src="<?php echo $url . '/images/logo.png'; ?>" />
        <?php echo sprintf(__("%s advises: Install brand new FREE %s plugin to keep your forms and website safe.", $prefix), $nicename, '<a href="https://wordpress.org/plugins/backup-wd/" title="' . __("More details", $prefix) . '" target="_blank">' .  __("Backup WD", $prefix) . '</a>'); ?>
        <a class="button button-primary" href="<?php echo $install_url; ?>">
          <span onclick="jQuery.post('<?php echo $dismiss_url; ?>');"><?php _e("Install", $prefix); ?></span>
        </a>
      </p>
      <button type="button" class="wd_bp_notice_dissmiss notice-dismiss" onclick="jQuery('#wd_bp_notice_cont').hide(); jQuery.post('<?php echo $dismiss_url; ?>');"><span class="screen-reader-text"></span></button>
    </div>
    <style>
      @media only screen and (max-width: 500px) {
        body #wd_backup_logo {
          max-width: 100%;
        }
        body #wd_bp_notice_cont p {
          padding-right: 25px !important;
        }
      }
      #wd_bp_logo_notice {
        width: 40px;
        float: left;
        margin-right: 10px;
      }
      #wd_bp_notice_cont {
        position: relative;
      }
      #wd_bp_notice_cont a {
        margin: 0 5px;
      }
      #wd_bp_notice_cont .dashicons-dismiss:before {
        content: "\f153";
        background: 0 0;
        color: #72777c;
        display: block;
        font: 400 16px/20px dashicons;
        speak: none;
        height: 20px;
        text-align: center;
        width: 20px;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
      }
      .wd_bp_notice_dissmiss {
        margin-top: 5px;
      }
    </style>
    <?php
    echo ob_get_clean();
  }
}

if ( !is_dir(plugin_dir_path( dirname(__FILE__) ) . 'backup-wd') ) {
  add_action('admin_notices', 'fm_bp_install_notice');
}

if ( !function_exists('wd_bps_install_notice_status') ) {
  // Add usermeta to db.
  function wd_bps_install_notice_status() {
    update_option('wd_bk_notice_status', '1', 'no');
  }
  add_action('wp_ajax_wd_bp_dismiss', 'wd_bps_install_notice_status');
}
