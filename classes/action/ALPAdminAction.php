<?php
/**
 * ALPAdminAction.
 * @author Dave Ligthart <info@daveligthart.com>
 * @version 0.2
 * @package ALP
 */
class ALPAdminAction extends ALPWPPlugin{

	/**
	 * FPAdminAction.
	 */
	function ALPAdminAction($plugin_name, $plugin_base){
		$this->plugin_name = $plugin_name;
		$this->plugin_base = $plugin_base;

		/**
		 * Handle wordpress actions.
		 */
		$this->add_action('activate_'.trim($_GET['plugin']) ,'activate'); //plugin activation.
		$this->add_action('admin_head'); // header rendering.
		$this->add_action('admin_menu'); // menu rendering.
		$this->add_action('activity_box_end','admin_dashboard');
	}

	/**
	 * Render admin views.
	 * Called by admin_menu.
	 * @access private
	 */
	function renderView() {
		$sub = $this->getAction();
		$url = $this->getActionUrl();

		// Display submenu
		$this->render_admin('admin_submenu', array ('url' => $url, 'sub' => $sub));

		/**
		 * Show view.
		 */
		switch($sub){
			default:
			case 'main':
				$this->admin_start();
			break;
			case 'log':
			break;
			case 'help':
				$this->admin_help();
			break;
		}
	}

	/**
	 * Activate plugin.
	 * @access private
	 */
	function activate() {

	}

	/**
	 * Render header.
	 * @access private
	 */
	function admin_head(){
		$this->adminConfigForm = new ALPAdminConfigForm();

		$log_path = $this->adminConfigForm->getApacheLogPath();

		$this->render_admin('admin_head', array("plugin_name"=>$this->plugin_name,'log_path'=>$log_path));
	}

	/**
	 * Add sidebar widgets.
	 */
	function dbx_post_sidebar(){
		$this->render_admin('admin_post_sidebar', array("plugin_name"=>$this->plugin_name));
	}


	/**
	 * Create menu entry for admin.
	 * @return	void
	 * @access private
	 */
	function admin_menu(){
		if (function_exists('add_options_page')) {
			add_options_page(__('WP-Alp', 'alp'),
			 	__('WP-Alp', 'alp'),
				 10,
			 	basename ($this->dir()),
			 	array (&$this, 'renderView')
			 );
		}
	}

	/**
	 * Display the configuration settings.
	 * @access protected
	 */
	function admin_start(){
		$adminConfigAction = new ALPAdminConfigAction($this->plugin_name, $this->plugin_base);
		$adminConfigAction->render();
	}

	/**
	 * Display the help page.
	 * @return void
	 * @access private
	 */
	function admin_help(){
		$this->render_admin('admin_help', array("plugin_name"=>$this->plugin_name));
	}

	/**
	 * Display chart in dashboard.
	 * @return void
	 * @access private
	 */
	function admin_dashboard() {
		$this->render_admin('admin_dashboard', array("plugin_name"=>$this->plugin_name));
	}
}
?>