<?php
/**
 * ALPAdminConfigForm model object.
 * @author Dave Ligthart <info@daveligthart.com>
 * @version 0.1
 * @package ALP
 */
include_once('ALPBaseForm.php');
class ALPAdminConfigForm extends ALPBaseForm{
	var $alp_apache_log_path;
	var $alp_type; //chart type.

	function ALPAdminConfigForm(){
		parent::ALPBaseForm();
		if($this->setFormValues()){

			$this->saveOptions();
		}
		$this->loadOptions();
	}


	function getApacheLogPath(){
		return trim($this->alp_apache_log_path);
	}

	function getAlpType() {
		return $this->alp_type;
	}
}
?>