<?php
/**
 * Admin log template.
 * @author dligthart <info@daveligthart.com>
 * @version 0.4
 * @package ALP
 */

include_once(dirname(__FILE__) . '/../../../../../wp-load.php'); // load wordpress context.
include_once(dirname(__FILE__) . '/../../config/config.php'); // plugin config.

$user = wp_get_current_user();

global $wp_version;

if(alpIsEnabled() && $user->caps['administrator']):

include_once(dirname(__FILE__) . '/../../classes/ApacheLogParser.php'); // log parser.

$clients = array();

if(defined('ALP_CACHE_DIR') && is_writable(ALP_CACHE_DIR)){
	$alp_graph = new ALP_Graph();
	$alp_graph->write();
	$clients = $alp_graph->getClients();
} else {
	echo "<strong>Can't write chart data to cache dir..</strong>";
}
?>

<?php if(is_array($clients) && count($clients)  > 0): ?>

<script type="text/javascript">
	jQuery("#statstable").tablesorter();
</script>

<?php if(function_exists('wp_ip2nation_getcountry')): ?>
<table class="tablesorter" id="statstable">
	<thead>
		<tr>
			<th>IP</th>
			<th>COUNTRY</th>
			<th>USAGE MB</th>
		</tr>
	</thead>
	<tbody>
	<?php
		foreach($clients as $client) {
			echo "<tr><td>{$client['ip']}</td>";

			$n = wp_ip2nation_getcountry($client['ip']);

			$country = $n->country;

			$code = $n->code;

			echo "<td>";

			if(function_exists('wp_ip2nation_flag')){
				echo wp_ip2nation_flag($client['ip']);
			}

			echo "<span>&nbsp;{$country}</span></td>";

			echo "<td>{$client['mb']}</td></tr>";
		}
	?>
	</tbody>
</table>
<?php else: ?>
<p>
If you have installed the WP-IP2Nation-Installer the stats will
automatically include an extra "country" table column.
<br/>
Download <a href="http://wordpress.org/extend/plugins/wp-ip2nation-installer/" target="_blank">WP-IP2Nation-Installer</a>.
</p>
<table class="tablesorter" id="statstable">
	<thead>
		<tr>
			<th>IP</th>
			<th>USAGE MB</th>
		</tr>
	</thead>
	<tbody>
	<?php
		foreach($clients as $client) {
			echo "<tr><td>{$client['ip']}</td><td>{$client['mb']}</td></tr>";
		}
	?>
	</tbody>
</table>
<?php endif;?>

<?php endif; ?>

<?php endif; ?>