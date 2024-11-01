<?php
/*
Plugin Name: WP-Alp
Plugin URI: http://www.daveligthart.com
Description: Apache log parser for Wordpress. Monitor daily bandwidth usage. Adds a dashboard widget displaying the daily bandwidth usage graph.
Version: 1.3.4
Author: Dave Ligthart
Author URI: http://www.daveligthart.com
*/
include_once(dirname(__FILE__) . '/config/config.php');
include_once(dirname(__FILE__) . '/classes/util/ALPUtils.php');
include_once(dirname(__FILE__) . '/classes/ApacheLogParser.php');
include_once(dirname(__FILE__) . '/classes/util/ALPWPPlugin.php');
include_once(dirname(__FILE__) . '/classes/model/ALPAdminConfigForm.php');
include_once(dirname(__FILE__) . '/classes/action/ALPAdminAction.php');
include_once(dirname(__FILE__) . '/classes/action/ALPAdminConfigAction.php');
include_once(dirname(__FILE__) . '/classes/action/ALPAdminLoadLogAction.php');
include_once(dirname(__FILE__) . '/classes/action/ALPFrontEndAction.php');
include_once(dirname(__FILE__) . '/classes/util/com.daveligthart.php');
/**
 * Is Enabled?
 * @return boolean
 * @access public
 */
function alpIsEnabled() {
	return (defined('ALP_CACHE_DIR') && is_writable(ALP_CACHE_DIR));
}

/**
 * WPAlp.
 * @author dligthart <info@daveligthart.com>
 * @version 1.3.2
 * @package ALP
 */
class WPAlp extends ALPWPPlugin {

	/**
	 * @var AdminAction admin action handler
	 */
	var $adminAction = null;

	/**
	 * @var FrontEndAction frontend action handler
	 */
	var $frontEndAction = null;

	 /**
	  * __construct()
	  */
	function WPAlp($path) {
		$this->register_plugin('wp-alp', $path);

		if (is_admin()) {
	 		wp_enqueue_script('jquery');
			$this->adminAction = new ALPAdminAction($this->plugin_name, $this->plugin_base);
		} else {
			if(alpIsEnabled()){
				$this->frontEndAction = new ALPFrontEndAction($this->plugin_name, $this->plugin_base);
			}
	 	}
	}
}

// create wpalp instance.
$wpalp = new WPAlp(__FILE__);
?>