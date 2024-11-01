<?php
/**
 * ALP config file.
 * @author Dave Ligthart <info@daveligthart.com>
 * @version 0.2
 * @package ALP
 */

/**
 * Set cache dir. Make sure it is writable by webserver. (chmod 777 or chown www:www).
 */
$cache_path = dirname(__FILE__) . '/../../../cache/';
$cache_dir = $cache_path . 'wp-alp';

define('ALP_CACHE_DIR', $cache_dir);
define('ALP_CACHE_PATH',  '/../../../cache/');

if(!file_exists($cache_dir)){
	if(is_writable($cache_path)){
		mkdir($cache_dir);
	}
}

/**
 * Start the parser after every (x) pageloads
 */
define('ALP_MAX_PAGELOAD', 1);
?>
