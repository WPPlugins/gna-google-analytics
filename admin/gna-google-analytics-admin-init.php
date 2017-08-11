<?php
/* 
 * Inits the admin dashboard side of things.
 * Main admin file which loads all settings panels and sets up admin menus. 
 */
if (!class_exists('GNA_GoogleAnalytics_Admin_Init')) {
	class GNA_GoogleAnalytics_Admin_Init {
		var $main_menu_page;
		//var $dashboard_menu;
		var $settings_menu;

		public function __construct() {
			//This class is only initialized if is_admin() is true
			$this->admin_includes();
			add_action('admin_menu', array(&$this, 'create_admin_menus'));

			if ( isset($_GET['page']) && (strpos($_GET['page'], GNA_GOOGLE_ANALYTICS_MENU_SLUG_PREFIX ) !== false) ) {
				add_action('admin_print_scripts', array(&$this, 'admin_menu_page_scripts'));
				add_action('admin_print_styles', array(&$this, 'admin_menu_page_styles'));
			}
		}

		public function admin_menu_page_scripts() {
			wp_enqueue_script('jquery');
			wp_enqueue_script('postbox');
			wp_enqueue_script('dashboard');
			wp_enqueue_script('thickbox');
			wp_enqueue_script('gna-ga-script', GNA_GOOGLE_ANALYTICS_URL. '/assets/js/gna-google-analytics.js', array(), GNA_GOOGLE_ANALYTICS_VERSION);
		}

		function admin_menu_page_styles() {
			wp_enqueue_style('dashboard');
			wp_enqueue_style('thickbox');
			wp_enqueue_style('global');
			wp_enqueue_style('wp-admin');
			wp_enqueue_style('gna-google-analytics-admin-css', GNA_GOOGLE_ANALYTICS_URL. '/assets/css/gna-google-analytics.css');
		}

		public function admin_includes() {
			include_once('gna-google-analytics-admin-menu.php');
		}

		public function create_admin_menus() {
			$this->main_menu_page = add_menu_page( __('GNA Google Analytics', 'gna-google-analytics'), __('GNA Google Analytics', 'gna-google-analytics'), 'manage_options', 'gna-ga-settings-menu', array(&$this, 'handle_settings_menu_rendering'), GNA_GOOGLE_ANALYTICS_URL . '/assets/images/gna_20x20.png' );

			add_submenu_page('gna-ga-settings-menu', __('Settings', 'gna-google-analytics'),  __('Settings', 'gna-google-analytics'), 'manage_options', 'gna-ga-settings-menu', array(&$this, 'handle_settings_menu_rendering'));

			add_action( 'admin_init', array(&$this, 'register_gna_google_analytics_settings') );
		}

		public function register_gna_google_analytics_settings() {
			register_setting( 'gna-google-analytics-setting-group', 'g_analytics_configs' );
		}

		public function handle_settings_menu_rendering() {
			include_once('gna-google-analytics-admin-settings-menu.php');
			$this->settings_menu = new GNA_GoogleAnalytics_Settings_Menu();
		}
	}
}
