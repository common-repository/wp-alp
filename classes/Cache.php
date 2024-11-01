<?php
/**
 * @author Dave Ligthart <dave.ligthart@framemakers.nl>
 * @package ALP
 */
include_once('ClientInfo.php');
/**
 * ALP_Cache
 * @package alp
 * @subpackage classes
 */
class ALP_Cache{
	var $cache_dir;
	var $cache_file; //md5ed usage by ip
	var $cache_offset_file;	//filepointer (byte offset)
	var $cache_totals_file; //date and total usage
	var $cache_file_separator = ';';
	var $cache_filehandle = null;
	var $cache_file_buffer = array();
	var $clients = array(); //totals
	var $clients_loaded = false;
	var $totals = array();

	function ALP_Cache($accesslogname){
		$this->setCachefile($accesslogname);
		$this->loadClients();
		$this->loadTotals();
	}
	/**
	 * Set the cache file.
	 * @param string $accesslogname
	 * @access protected
	 */
	function setCachefile($accesslogname){
		$today = getDate();
		$year = $today['year'];
		$month = $today['mon'];
		$this->cache_dir = ALP_CACHE_DIR;
		$this->cache_file = md5($accesslogname.$month); //every month a new cache log
		$this->cache_offset_file = "_".md5($accesslogname);
		$this->cache_totals_file = "__".md5($accesslogname.$year); //every year a new cache totals log
	}
	/**
	 * Save the clients to the cache.
	 * @access public
	 */
	function saveClients($clients){
		foreach($clients as $client){
			$this->saveClient($client);
		}
		$this->writeCache();
	}
	/**
	 * Save a client to the cache.
	 * @param ClientInfo $client
	 * @access protected
	 */
	function saveClient($client){
		if(isset($this->clients[$client->getIp()])){
			$client->add("","","",$this->clients[$client->getIp()]->getBytes());
		}
		$this->clients[$client->getIp()] = $client;
	}
	/**
	 * Load clients.
	 * @access protected
	 */
	function loadClients(){
		if(!$this->clients_loaded){
			$this->loadCache();
			foreach($this->cache_file_buffer as $line){
				list($ip,$sizeb,$sizemb) = explode(';',$line,3);
				if('' != $ip){
					$this->clients[$ip] = new ALP_ClientInfo($ip);
					$this->clients[$ip]->add('','','',$sizeb);
					$this->clients[$ip]->render();
				}
			}
			$this->clients_loaded = true;
		}
	}
	/**
	 * Open the cache.
	 * @return boolean opened
	 * @access protected
	 */
	function openCache(){
		if($this->cache_filehandle==null)
			$this->cache_filehandle = fopen($this->cache_dir.'/'.$this->cache_file,"a+");
		if($this->cache_filehandle)return true;
		return false;
	}
	/**
	 * Load the cache.
	 * @access protected
	 */
	function loadCache(){
		if($this->openCache()){
			while (!feof ($this->cache_filehandle)) {
				$this->cache_file_buffer[] = fgets($this->cache_filehandle, 4096);
			}
			fclose($this->cache_filehandle);
		}
	}
	/**
	 * Save totals to the cache.
	 * @param integer $size_bytes
	 * @access public
	 */
	function saveTotals($size_bytes){
		if($size_bytes > 0){
			$this->totals[date('Y-m-d')]+=$size_bytes;
			$this->writeTotals();
		}
	}
	/**
	 * Load totals from cache.
	 * Format: date;bytes;
	 * @access public
	 */
	function loadTotals(){
		$fh = fopen($this->cache_dir.'/'.$this->cache_totals_file,'a+');
		if($fh){
			$buffer = array();
			while (!feof($fh)){
				$buffer[] = fgets($fh, 4096);
			}
			foreach($buffer as $line){
				list($date,$sizeb) = explode(';',$line,2);
				$this->totals[$date] = $sizeb;
			}
			fclose($fh);
		}
	}
	/**
	 * Write totals to the cache.
	 * @access protected
	 */
	function writeTotals(){
		$cp = $this->cache_dir.'/'.$this->cache_totals_file;
		if(is_writable($this->cache_dir)){
			if(count($this->totals) > 0){
				$fh = fopen($cp,'w+');
				foreach($this->totals as $date=>$size){
					if(trim($date)!="")
						fwrite($fh,"{$date};{$size};\n");
				}
				fclose($fh);
			}
		}
	}
	/**
	 * Load the log file offset from the cache.
	 * @return integer offset bytes
	 * @access public
	 */
	function loadOffset(){
		$op = $this->cache_dir.'/'.$this->cache_offset_file;
		if (file_exists($op))
			$offset = (int)file_get_contents($op);
		else $offset = 0;
		return $offset;
	}
	/**
	 * Write byte offset to the cache.
	 * @param integer $offset bytes
	 * @access public
	 */
	function writeOffset($offset){
		$op = $this->cache_dir.'/'.$this->cache_offset_file;
		if($offset > 0 && is_writable($this->cache_dir)){
			$fh = fopen($op,'w+');
			fwrite($fh,$offset);
			fclose($fh);
		}
	}
	/**
	 * Write totals to cache.
	 * Format: ip;size_b;size_mb;
	 * @access protected
	 */
	function writeCache(){
		$cp = $this->cache_dir.'/'.$this->cache_file;
		if(is_writable($this->cache_dir) && is_writable($cp)){
			$fh = fopen($cp,'w+');
			if(count($this->clients) > 0){
				foreach($this->clients as $client){
					$ip = $client->getIp();
					$size_b = $client->getBytes();
					$size_mb = round($size_b / (1024*1024),4);
					$line = "{$ip};".$size_b.";".$size_mb.";\n";
					fwrite($fh,$line);
				}
			}
			fclose($fh);
		}
	}
	/**
	 * Get totals.
	 * @access public
	 */
	function getTotals(){
		return $this->totals;
	}
	/**
	 * Get clients.
	 * @return array clients ip=>ClientInfo
	 * @access public
	 */
	function getClients(){
		return $this->clients;
	}
}
?>