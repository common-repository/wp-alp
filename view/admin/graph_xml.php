<?php
/**
 * Load graph xml.
 * @author dligthart <info@daveligthart.com>
 * @version 0.2
 * @package ALP
 */
include_once(dirname(__FILE__) . '/../../../../../wp-load.php'); // load wordpress context.
include_once(dirname(__FILE__) . '/../../config/config.php'); // plugin config.
include_once(dirname(__FILE__) . '/../../classes/Graph.php');
$user = wp_get_current_user();
if($user->caps['administrator']){
	$graph = new ALP_Graph();
	echo $graph->read(false);
}
exit;
?>