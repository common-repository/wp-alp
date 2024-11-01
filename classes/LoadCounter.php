<?php
/**
 * @author Dave Ligthart <dave.ligthart@framemakers.nl>
 * @package ALP
 */
/**
 * ALP_LoadCounter
 * @package alp
 * @subpackage classes
 */
class ALP_LoadCounter{
	var $cache_dir;
	var $cache_loadcount_file;
	var $load_count;
	var $load_max;

	function ALP_LoadCounter($args = array()){
		if(defined('ALP_CACHE_DIR')){
			$this->cache_dir = ALP_CACHE_DIR;
			$this->cache_loadcount_file = md5('loadcount');
		}
		if(defined('ALP_MAX_PAGELOAD')){
			$this->load_max = ALP_MAX_PAGELOAD;
		}
	}
	/**
	 * Invoke parser. When load_count hits 0.
	 * @access public
	 */
	function invokeParser(){
		$this->readLoadCount();
		if($this->load_max > $this->load_count)
			$this->load_count++;
		else $this->load_count=0;

		$this->writeLoadCount();

		if($this->load_count==0){
			return true;
		}
		return false;
	}
	/**
	 * Read load count.
	 * @access public
	 */
	function readLoadCount(){
		$op = $this->cache_dir.'/'.$this->cache_loadcount_file;
		if (file_exists($op))
			$loadcount = (int)file_get_contents($op);
		else $loadcount = 0;
		$this->load_count = $loadcount;
		return $loadcount;
	}
	/**
	 * Write load count.
	 * @access public
	 */
	function writeLoadCount(){
		$op = $this->cache_dir.'/'.$this->cache_loadcount_file;
		if(is_writable($this->cache_dir)){
			$fh = fopen($op,'w+');
			fwrite($fh,$this->load_count);
			fclose($fh);
		}
	}
}
?>