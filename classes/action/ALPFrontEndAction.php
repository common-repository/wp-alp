<?php
/**
 * ALPFrontEndAction.
 * @author Dave Ligthart <info@daveligthart.com>
 * @version 0.2
 * @package ALP
 */
class ALPFrontEndAction extends ALPWPPlugin{

	/** @var ApacheLogParser apache log parser instance */
	var $alp = null;

	/**
	 * __construct()
	 * @param String $plugin_name
	 * @param String $plugin_base
	 */
	function ALPFrontEndAction($plugin_name, $plugin_base){
		$this->plugin_name = $plugin_name;
		$this->plugin_base = $plugin_base;

		//$this->add_action('wp_head');
		$this->add_action('init');
		$this->add_action('wp_footer');
	}

	 /**
	  * init.
	  */
	 function init() {
	 	$form = new ALPAdminConfigForm();

	 	$log_path = $form->getApacheLogPath();

	 	if('' != $log_path){
	 		$this->alp = new ApacheLogParser(array('path'=>$log_path));
	 		//$this->showLog();
	 	}
	 }

	 /**
	  * showLog.
	  */
	 function showLog() {
	 	if(null != $this->alp) {
    		$this->alp->read();
			print_r($this->alp->getClients());
			print_r($this->alp->getTotals());
	 	}
	 }

	/**
	 * Render header.
	 * @access private
	 */
	function wp_head(){
		$this->render('head', array('plugin_name'=>$this->plugin_name));
	}

	/** Render footer */
	function wp_footer() {
		$this->render('footer', array('plugin_name'=>$this->plugin_name));
	}
}
?>