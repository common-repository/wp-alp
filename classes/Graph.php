<?php
/**
 * ALP_Graph.
 * @author Dave Ligthart <info@daveligthart.com>
 * @package ALP
 * @version 0.2
 */
class ALP_Graph {
	/** @var clients array. */
	var $clients = null;

	/** @var graph xml string. */
	var $xml = '';

	/**
	 * __construct()
	 */
	function ALP_Graph() {

	}

	/**
	 * Write graph xml.
	 * @param boolean $to_file optional
	 * @return boolean success
	 * @access public
	 */
	function write($to_file = true){
		global $wp_version;

		$success = false;

		if(defined('ALP_CACHE_DIR') && is_writable(ALP_CACHE_DIR)){

			// Read log.
			$alp = new ApacheLogParser(array('path'=>get_option('alp_apache_log_path')));

			$alp->read();

			$this->clients = $alp->getClients();

			// Set chart type.
			$type = get_option('alp_type');

			$chart_type = 'line';

			$types = array('line', 'column', 'bar', '3d column', '3d area', 'bubble');

			if($type > 0) {
				$chart_type = $types[$type];
			}

			// Get data.
			$totals = $alp->getTotals();

			// Create xml.
			$xml .= '<chart>';

			$xml .= '<chart_data>';

			$count_totals = count($totals);

			if(is_array($totals) &&  $count_totals > 0) {

				// Skip first date: messes up the graph.
				if(count($totals) > 1){
					$totals[key($totals)] = '';
				}

				// Dates.
				$xml .= '<row>';
				$xml .= '<string>Date</string>';
				foreach($totals as $date=>$bytes) {
					$xml  .= '<string>'.$date.'</string>';
				}
				$xml .='</row>';

				// Sizes.
				$xml .= '<row>';
				$xml .= '<string>Daily Bandwidth Usage - MB</string>';

				foreach($totals as $date=>$bytes_size) {
					$size = round($bytes_size / 1048576, 0);
					$xml  .= '<number>'.$size.'</number>';
				}
				$xml .='</row>';
			}
			$xml .= '</chart_data>';

			$xml .='<draw />
			<link />
			<legend_rect x="-100" y="-100" width="5" height="10" margin="0" />';

			if($wp_version < 2.7){
				$xml .= '<chart_rect x="75" y="5" height="195" width="475" />';
			} else {
				$xml .= '<chart_rect x="75" y="5" height="'.(195/1.1).'" width="'.(475/1.1).'" />';
			}

			$xml .= '<chart_grid_h alpha="10" color="0066FF" thickness="30" />
			<chart_grid_v alpha="10" color="0066FF" thickness="1" />
			<series_color>
				<value>14568a</value>
			</series_color>
			<chart_value prefix="" suffix=" views" decimals="0" separator="," position="cursor" hide_zero="1" as_percentage="" font="arial" bold="" size="12" color="ffffff" background_color="000000" alpha="80" />
			<axis_category skip="1" size="10" orientation="diagonal_up" />
			<axis_value decimals="0" size="18" />
			<chart_type>'.$chart_type.'</chart_type>
			<chart_transition type="scale" duration="0.6" />
			<link_data url="http://www.daveligthart.com" target="_self" />
			';

			$xml .= '</chart>';

			$this->xml = $xml;

			if($to_file){
				$fh = fopen(ALP_CACHE_DIR . '/chart.xml', 'w+');
				$success = fwrite($fh, $xml);
				fclose($fh);
			}
		}

		return $success;
	}

	/**
	 * Get clients.
	 * return array clients
	 * @access public
	 */
	function getClients() {
		return $this->clients;
	}

	/**
	 * Read graph xml.
	 * @param boolean $from_file optional
	 * @return string xml
	 * @access public
	 */
	function read($from_file = true) {
		$xml = '';
		if(defined('ALP_CACHE_DIR') && $from_file) {
			ob_start();
			readfile(ALP_CACHE_DIR . '/chart.xml');
			$xml = ob_get_contents();
			ob_end_clean();
		} else {
			$this->write(false);
			$xml = $this->xml;
		}
		return $xml;
	}
}
?>
