<?php
/**
 * ALPAdminConfigAction
 * @author Dave Ligthart <info@daveligthart.com>
 * @version 0.2
 * @package ALP
 */
class ALPAdminConfigAction extends ALPWPPlugin{
	/**
	 * @var
	 */
	var $adminConfigForm = null;

	/**
	 * __construct()
	 */
	function ALPAdminConfigAction($plugin_name, $plugin_base){
		$this->plugin_name = $plugin_name;
		$this->plugin_base = $plugin_base;
		$this->adminConfigForm = new ALPAdminConfigForm();
	}

	/**
	 * Render form.
	 */
	function render(){
	 	$log_path = $this->adminConfigForm->getApacheLogPath();

		$this->render_admin('admin_config', array(
				'form'=>$this->adminConfigForm,
				'plugin_base_url'=>$this->url(),
				'plugin_name'=>$this->plugin_name
			)
		);
	}
}