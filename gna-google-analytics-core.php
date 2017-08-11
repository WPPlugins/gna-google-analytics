<?php
if (!class_exists('GNA_GoogleAnalytics')) {
	//add_action( 'plugins_loaded', array( 'GNA_GoogleAnalytics', 'init' ));
	class GNA_GoogleAnalytics {
		var $plugin_url;
		var $admin_init;
		var $configs;

		public function init() {
			$class = __CLASS__;
			new $class;
		}

		public function __construct() {
			$this->load_configs();
			$this->define_constants();
			$this->define_variables();
			$this->includes();
			$this->loads();

			add_action('init', array(&$this, 'plugin_init'), 0);
			add_filter('plugin_row_meta', array(&$this, 'filter_plugin_meta'), 10, 2);
		}

		public function load_configs() {
			include_once('inc/gna-google-analytics-config.php');
			$this->configs = GNA_GoogleAnalytics_Config::get_instance();
		}

		public function define_constants() {
			define('GNA_GOOGLE_ANALYTICS_VERSION', '1.1.0');

			define('GNA_GOOGLE_ANALYTICS_BASENAME', plugin_basename(__FILE__));
			define('GNA_GOOGLE_ANALYTICS_URL', $this->plugin_url());

			define('GNA_GOOGLE_ANALYTICS_MENU_SLUG_PREFIX', 'gna-ga-settings-menu');
		}

		public function define_variables() {
		}

		public function includes() {
			if(is_admin()) {
				include_once('admin/gna-google-analytics-admin-init.php');
			}

			add_action('wp_head', array(&$this, 'add_script_front'));
		}

		public function loads() {
			if(is_admin()){
				$this->admin_init = new GNA_GoogleAnalytics_Admin_Init();
			}
		}

		public function plugin_init() {
			load_plugin_textdomain('gna-google-analytics', false, dirname(plugin_basename(__FILE__ )) . '/languages/');
		}

		public function plugin_url() {
			if ($this->plugin_url) return $this->plugin_url;
			return $this->plugin_url = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
		}

		public function filter_plugin_meta($links, $file) {
			if( strpos( GNA_GOOGLE_ANALYTICS_BASENAME, str_replace('.php', '', $file) ) !== false ) { /* After other links */
				$links[] = '<a target="_blank" href="https://profiles.wordpress.org/chris_dev/" rel="external">' . __('Developer\'s Profile', 'gna-google-analytics') . '</a>';
			}

			return $links;
		}

		public function install() {
		}

		public function uninstall() {
		}

		public function activate_handler() {
		}

		public function deactivate_handler() {
		}

		public function add_script_front() {
			global $g_googleanalytics;
			
			$ua_ids = is_array($g_googleanalytics->configs->get_value('g_analytics_ua_id')) ? $g_googleanalytics->configs->get_value('g_analytics_ua_id') : array($g_googleanalytics->configs->get_value('g_analytics_ua_id'));

			$home_url = get_home_url();
			$find = array( 'http://', 'https://', 'www.');
			$replace = '';
			$output = str_replace( $find, $replace, $home_url );

			foreach ( $ua_ids as $ua_id ) {
				if($ua_id !== '') {
					echo "
<!-- GNA Google Analytics v".GNA_GOOGLE_ANALYTICS_VERSION." -->
<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', '".$ua_id."', '".$output."');
	ga('send', 'pageview');
</script>
<!-- END GNA Google Analytics -->
";
				}
			}
		}
	}
}
$GLOBALS['g_googleanalytics'] = new GNA_GoogleAnalytics();
