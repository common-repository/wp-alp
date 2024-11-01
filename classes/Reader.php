<?php
/**
 * @author Dave Ligthart <dave.ligthart@framemakers.nl>
 * @package ALP
 */
include_once('Cache.php');
/**
 * ALP_Reader.
 * Read information from the cache.
 * @package alp
 * @subpackage classes
 */
class ALP_Reader{
	var $cache = null;
	var $path_to_log;
	var $time_start,$time_end,$time_spend;
	var $ret_clients = array();
	/**
	 * Constructor.
	 * @param string $access_log_path path to logfile.
	 * @access public
	 */
	function ALP_Reader($access_log_path){
		$this->path_to_log = trim($access_log_path);
		if('' != $this->path_to_log){
			$this->start();
			$this->cache = new ALP_Cache($this->getAccessLogName());
			$this->renderClients();
		}
	}
	/**
	 * Start the reader. Time the read.
	 * @access public
	 */
	function start(){
		$this->time_start = $this->microtime_float();
	}
	/**
	 * Stop the reader. Time end read.
	 * @access public
	 */
	function stop(){
		if($this->time_end==0){
			 $this->time_end = $this->microtime_float();
			 $this->time_spend = $this->time_end - $this->time_start;
		}
	}
	/**
	 * Get clients from cache.
	 * @access public
	 */
	function renderClients(){
		$clients = $this->cache->getClients();
		foreach($clients as $c){
			$size = $c->getSize();
			$this->ret_clients[] = array('ip'=>$c->getIp(),'mb'=>$size['mb']);
		}
	}
	/**
	 * Debugging output.
	 * @access public
	 */
	function output(){
		$clients = $this->cache->getClients();
		echo '<br/>';
		foreach($clients as $client){
			$size = $client->getSize();
			echo $client->getIp().' - '.$size['mb'].'<br/>';
		}
		echo '<br/>';
		$totals = $this->cache->getTotals();
		foreach($totals as $date=>$size){
			$size = round($size/(1024*1024),4);
			if('' != $date)
			echo "<strong>{$date}</strong> - {$size} <br/>";
		}
		$this->stop();
		echo $this->time_spend.'<br/>';
	}

	/**
	 * Get totals from cache.
	 * @return array totals key=>value
	 * @access public
	 */
	function getTotals(){
		return $this->cache->getTotals();
	}
	/**
	 * Get clients.
	 * @return array clients
	 * @access public
	 */
	function getClients(){
		return $this->ret_clients;
	}
	/**
	 * Get accesslog name.
	 * @return string name
	 * @access public
	 */
	function getAccessLogName(){
			$x = explode('/',$this->path_to_log);
			return $x[count($x)-1];
	}
	/**
	 * Get time.
	 * @return float time
	 * @access private
	 */
	function microtime_float(){
            list($usec, $sec) = explode(" ",microtime());
            return ((float)$usec + (float)$sec);
    }
}
?>