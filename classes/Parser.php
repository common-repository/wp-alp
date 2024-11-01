<?php
/**
 * @author Dave Ligthart <dave.ligthart@framemakers.nl>
 * @package ALP
 */
include_once('ClientInfo.php');
include_once('Cache.php');
/**
 * ALP_Parser
 * @package alp
 * @subpackage classes
 */
class ALP_Parser{
		/**
		 * @var integer number of rows parsed
		 */
		var $rowsParsed;
		/**
		 * @var integer  number of bytes transfered
		 */
		var $bytesTransfered;
		/**
		 * @var array
		 */
		var $clients = array();
		var $time_start = 0;
		var $time_end = 0;
		var $time_spend = 0;
		var $path_to_log = "";
		var $error_occurred = false;
		var $logfile_handle;
		var $cache = null;

		/**
		 * Constructor.
		 * @param array $args arguments expects: "path"
		 * @access public
		 */
		function ALP_Parser($args=array()){
			 if(isset($args['path'])){
				$this->path_to_log = trim($args['path']);
			 }
			 $this->init();
		}
		/**
		 * Init start parser.
		 * @access private
		 */
		function init(){
			set_time_limit(30);
			$this->cache = new ALP_Cache($this->getAccessLogName());
			$this->start();
		}
		/**
		 * Start.
		 * @access protected
		 */
		function start(){
			set_time_limit(60 * 10);
			$this->openLogFile();
			$this->time_start = $this->microtime_float();
			fseek($this->logfile_handle, $this->startReadAtBytes());
			while ($data = fgets($this->logfile_handle, 4096)){
				$logInfo = $this->parse($data);
				// perform the needed actions with $logInfo array
			}
			$this->stop();
		}
		/**
		 * Stop.
		 * @access protected
		 */
		function stop(){
			$this->saveReadBytes();
			fclose($this->logfile_handle);
			$this->saveLogInfo();
			//$this->outputInfo();
		}
		/**
		 * Open log file.
		 * @access public
		 */
		function openLogFile(){
			$fp = null;
			if('' != $this->path_to_log){
				 $fp = fopen($this->path_to_log, 'r');
			}
			else $this->err('log file not specified!');
			if($fp != null){
				$this->logfile_handle = $fp;
			}
		}
		/**
		 * Incremental log parse.
		 * @return integer byteoffset
		 * @access protected
		 */
		function startReadAtBytes(){
			return $this->cache->loadOffset();
		}
		/**
		 * Get bytes read.
		 * @return integer bytes
		 * @access protected
		 */
		function getReadBytes(){
			return ftell($this->logfile_handle);
		}
		/**
		 * Write bytes read.
		 * @access protected
		 */
		function saveReadBytes(){
			$this->cache->writeOffset($this->getReadBytes());
			 if($this->time_end==0){
				 $this->time_end = $this->microtime_float();
				 $this->time_spend = $this->time_end - $this->time_start;
			 }
		}
		/**
		 * Save log info.
		 * @access protected
		 */
		function saveLogInfo(){
			$this->cache->saveClients($this->clients);
			$this->cache->saveTotals($this->bytesTransfered);
		}
		/**
		 * Output info for debugging.
		 * @access public
		 */
		function outputInfo(){
			echo "<p>Parsed {$this->rowsParsed} rows in {$this->time_spend} seconds.</p>";
			//$kbytesTransfered = $this->bytesTransfered / 1024;
			echo "<p>Total  {$this->bytesTransfered} bytes have been transfered!</p>";
			$mbytesTransfered = round($this->bytesTransfered / (1024*1024),4);
			echo "<p>MBytes {$mbytesTransfered}</p>";
			foreach($this->clients as $c){
				$c->output();
			}
		}
		/**
		 * Parse data.
		 * @param string $data
		 * @access protected
		 */
		function parse($data){
			if('' != trim($data) && !$this->error_occurred){
				$request['ip'] = $this->parseData('ip',"/[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+/i",$data);
				$datetime = $this->parseData('date',"#\[([0-9]+\/[a-zA-Z]+\/[0-9]+:[0-9]+:[0-9]+:[0-9]+\ [\+\-0-9]+)\]#",$data);
				$request['time'] = $this->parseTime($datetime);
				$request['date'] = $this->parseDate($datetime);
				list( , , , , , ,$ref,$http,$code,$size) = explode(" ", $data, 10);
				$request['size'] = $size;
				$request['referer'] = $ref;
				$request['code'] = $code;
				$request['http'] = str_replace("\"","",$http);

				if(!isset($this->clients[$request['ip']]))
					$this->clients[$request['ip']] = new ALP_ClientInfo($request['ip']);
				else $this->clients[$request['ip']]->add($request['date'],$request['time'],$request['referer'],$request['size']);

				$this->rowsParsed++;
				$this->bytesTransfered += $request['size'];
			}
			return $request;
		}
		/**
		 * Parse data.
		 * @param string $type
		 * @param string $regex
		 * @param string $data
		 * @access protected
		 */
		function parseData($type,$regex,$data){
			$pattern = $regex;
			$matches = array();
			$match = "";
			if(preg_match($pattern,$data,$matches)){
				$match = $matches[0];
			}
			return $match;
		}

		/**
		 * Parse date.
		 * Parse date into Y-m-d format, you can also edit this to parse the date into unix timestamp.
		 * @param string $date
		 * @return string date Y-m-d
		 * @access protected
		 */
		function parseDate($date){
			if (empty($date)){
				$this->err('Date empty!');
			}
			list($d, $M, $y, $h, $m, $s, $z) = sscanf($date, "[%2d/%3s/%4d:%2d:%2d:%2d %5s]");
			return date('Y-m-d', strtotime("$d $M $y $h:$m:$s $z"));
		}
		/**
		 * Parse time in H:i:s format
		 * @param string $date
		 * @return string time H:i:s
		 * @access protected
		 */
		function parseTime($date){
			list($d, $M, $y, $h, $m, $s, $z) = sscanf($date, "[%2d/%3s/%4d:%2d:%2d:%2d %5s]");
			return date('H:i:s', strtotime("$d $M $y $h:$m:$s $z"));
		}
		/**
		 * Get microtime_float
		 * @return float time
		 * @access protected
		 */
		function microtime_float(){
            list($usec, $sec) = explode(" ",microtime());
            return ((float)$usec + (float)$sec);
        }
        /**
         * Error occurred.
         * @param string $mesg error message
         * @access protected
         */
		function err($mesg){
			$this->error_occurred = true;
			trigger_error($mesg, E_USER_WARNING);
		}
		/**
		 * Get accesslog name.
		 * @return string accesslogname
		 * @access public
		 */
		function  getAccessLogName(){
			$x = explode('/',$this->path_to_log);
			return $x[count($x)-1];
		}
}