<?php
/**
 * ALPAdminLoadLogAction.
 * @author Dave Ligthart <info@daveligthart.com>
 * @version 0.1
 * @package ALP
 */
class ALPAdminLoadLogAction extends ALPWPPlugin{
	/**
	 * @var
	 */
	var $adminConfigForm = null;

	/**
	 * __construct()
	 */
	function ALPAdminLoadLogAction($plugin_name, $plugin_base){
		$this->plugin_name = $plugin_name;
		$this->plugin_base = $plugin_base;

		$this->adminConfigForm = new ALPAdminConfigForm();
	}

	/**
	 * Render form.
	 */
	function render(){
	 	$log_path = $this->adminConfigForm->getApacheLogPath();
	}
}