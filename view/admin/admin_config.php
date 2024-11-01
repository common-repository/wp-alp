<?php
/**
 * Admin config template.
 * @author dligthart <info@daveligthart.com>
 * @version 0.1
 * @package ALP
 */
$cache_path = dirname(__FILE__) . '/../../../../cache/';
?>
<div class="wrap">
	<h2><?php _e('WP-Alp Options','alp');?></h2>
	<p>
		<?php if(!file_exists($cache_path)): ?>

		<strong><?php _e('wp-content/cache does not exist:','alp');?></strong>
		&nbsp;<?php _e('please make sure that the "wp-content/cache" directory is created','alp');?>.
		<br/>

		<?php else: ?>

		<?php if(!is_writable($cache_path)): ?>

		<strong><?php _e('wp-content/cache is not writable:','alp');?></strong>
		&nbsp;<?php _e('please make sure that the "wp-content/cache" directory is writable by webserver.','alp');?>.
		<br/>

		<?php endif; ?>

		<?php endif; ?>
	</p>
	<form name="alp_config_form" method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" accept-charset="utf-8">
		<?= $form->htmlFormId(); ?>
		<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
				<tr>
					<th scope="row">
						<label for="alp_apache_log_path"><?php _e('apache log path','alp'); ?>:</label>
					</th>
					<td>
								<?php
								echo "<input type='text' size='60' ";
								echo "name='alp_apache_log_path' ";
								echo "id='alp_apache_log_path' ";
								echo "value='".$form->getApacheLogPath()."'" .
										"/>\n";
								?><br/>
								<?php _e('Enter path to apache log','alp'); ?>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="alp_type"><?php _e('Type','alp'); ?>:</label>
					</th>
					<td>

				<?php
				  /**
					* line
				    * column (default)
				    * stacked column
				    * floating column
				    * 3d column
				    * image column
				    * stacked 3d column
				    * parallel 3d column
				    * pie
				    * 3d pie
				    * image pie
				    * donut
				    * bar
				    * stacked bar
				    * floating bar
				    * area
				    * stacked area
				    * 3d area
				    * stacked 3d area
				    * candlestick
				    * scatter
				    * polar
				    * bubble
					*/
					echo alp_html_dropdown('alp_type', array('line', 'column', 'bar', '3d column', '3d area', 'bubble'), $form->getAlpType());
					?>
					<br/>
					<?php _e('Select chart type','alp'); ?>
					</td>
				</tr>
		</table>
		<p class="submit"><input type="submit" name="Submit" value="<?php _e('Save Changes','alp'); ?>" />
		</p>
	</form>

	<?php if(alpIsEnabled()): ?>
	<div id="alp_graph" align="center">
	<script language="JavaScript" type="text/javascript">
	<!--
	if (AC_FL_RunContent == 0 || DetectFlashVer == 0) {
		alert("This page requires AC_RunActiveContent.js.");
	} else {
		var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
		if(hasRightVersion) {
			AC_FL_RunContent(
				'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,45,0',
				'width', '670',
				'height', '265',
				'scale', 'noscale',
				'salign', 'TL',
				'bgcolor', '#ffffff',
				'wmode', 'transparent',
				'movie', 'charts',
				'src', '<?php bloginfo('url');?>/wp-content/plugins/wp-alp/resources/swf/charts',
				'FlashVars', 'library_path=../wp-content/plugins/wp-alp/resources/swf/charts_library&xml_source=../wp-content/plugins/wp-alp/view/admin/graph_xml.php',
				'id', 'alp_chart',
				'name', 'alp_chart',
				'menu', 'false',
				'allowFullScreen', 'true',
				'allowScriptAccess','never',
				'quality', 'high',
				'align', 'middle',
				'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
				'play', 'true',
				'devicefont', 'false'
				);
		} else {
			var alternateContent = 'This content requires the Adobe Flash Player. '
			+ '<u><a href=http://www.macromedia.com/go/getflash/>Get Flash</a></u>.';
			document.write(alternateContent);
		}
	}
	// -->
	</script>
	</div>
	<?php endif; ?>
	<div id="alp_content"><img src="<?php bloginfo('url') ?>/wp-content/plugins/wp-alp/resources/images/spinner.gif" alt="Loading stats" style="padding:2px;border:none;" />&nbsp;<strong>loading stats..</strong></div>
</div>
<?php include_once('blocks/footer.php'); ?>
