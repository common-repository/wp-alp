<?php
/**
 * @author Dave Ligthart <dave.ligthart@framemakers.nl>
 * @package ALP
 */
/**
 * ALP_ClientInfo.
 * Client information is stored here.
 * @package alp
 * @subpackage classes
 */
class ALP_ClientInfo{
	var $ip;
	var $info = array();
	var $bytes_used = 0;
	var $is_rendered = false;
	/**
	 * Constructor.
	 * @param string $ip ip address
	 * @access public
	 */
	function ALP_ClientInfo($ip){
		$this->ip = $ip;
	}
	/**
	 * Add client information to client.
	 * @param string $date
	 * @param string $time
	 * @param string $referer
	 * @param integer $bytes transferred bytes
	 * @access public
	 */
	function add($date,$time,$referer,$bytes){
		$this->info[] = array("date"=>trim($date),"time"=>trim($time),"page"=>trim($referer),"bytes"=>trim($bytes));
	}
	/**
	 * @access public
	 */
	function get(){
		return $this->info;
	}
	/**
	 * @access public
	 */
	function set($info){
		$this->info = $info;
	}
	/**
	 * @access public
	 */
	function getIp(){
		return $this->ip;
	}
	/**
	 * @access public
	 */
	function getSize(){
		$mbytes = round($this->bytes_used / (1024*1024),4);
		$kbytes = round($this->bytes_used / 1024,4);
		return array("mb"=>$mbytes,"kb"=>$kbytes,"b"=>$this->bytes_used);
	}
	/**
	 * @access public
	 */
	function getBytes(){
		if(!$this->is_rendered)$this->render();
		return $this->bytes_used;
	}
	/**
	 * @access public
	 */
	function render(){
		$this->bytes_used = 0;
		foreach($this->get() as $request){
			$this->bytes_used += $request["bytes"];
		}
		$this->is_rendered = true;
	}
	/**
	 * @access public
	 */
	function output(){
		$this->render();
		$size = $this->getSize();
		echo "client {$this->ip} used ".$size["kb"]." KB or ".$size["mb"]." MB of data <br />";
	}
}
?>