<?php
/*
Plugin Name: GNA Google Analytics
Version: 1.1.0
Plugin URI: http://wordpress.org/plugins/gna-google-analytics/
Author: Chris Mok
Author URI: http://webgna.com/
Description: Easy to set-up the Google Analytics Script
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: gna-google-analytics
*/

if(!defined('ABSPATH'))exit; //Exit if accessed directly

include_once('gna-google-analytics-core.php');

register_activation_hook(__FILE__, array('GNA_GoogleAnalytics', 'activate_handler'));		//activation hook
register_deactivation_hook(__FILE__, array('GNA_GoogleAnalytics', 'deactivate_handler'));	//deactivation hook
