<?php
/**
 * @author Dave Ligthart <dave.ligthart@framemakers.nl>
 * @version 1.0
 * @package ALP
 */
/**
 * Includes
 */
include_once('Parser.php');
include_once('LoadCounter.php');
include_once('Reader.php');
include_once('Graph.php');
/**
 * Main Class for parsing apache log files.
 * @package ALP
 */
class ApacheLogParser{
		/**
		 * @var Parser the parser
		 */
		var $parser = null;
		/**
		 * @var string	path to logfile
		 */
		var $path;
		/**
		 * @var Reader the reader
		 */
		var $reader = null;
		/**
		 * @var boolean check if logfile is read
		 */
		var $is_read = false;

		/**
		 * __construct()
		 * @access public
		 */
		function ApacheLogParser($args=array()){
			$load_counter = new ALP_LoadCounter();
			$this->path = $args['path'];
			if('' != $this->path && $load_counter->invokeParser()){
				if(file_exists($this->path)){
					$this->parser = new ALP_Parser(array('path'=>$this->path));
				}
			}
		}
		/**
		 * @access public
		 */
		function read(){
			if(file_exists($this->path)){
				$this->reader = new ALP_Reader($this->path);
				$this->is_read = true;
			}
		}
		/**
		 * @access public
		 */
		function getTotals(){
			if($this->reader == null) return false;
			if(!$this->is_read)$this->read();
			return $this->reader->getTotals();
		}
		/**
		 * @access public
		 */
		function getClients(){
			if($this->reader == null) return false;
			if(!$this->is_read)$this->read();
			return $this->reader->getClients();
		}
}
?>