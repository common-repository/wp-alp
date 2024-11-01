<?php
/**
 * Dashboard widget.
 * @author dligthart <info@daveligthart.com>
 * @version 0.1
 * @package ALP
 */
global $wp_version;
$user = wp_get_current_user(); // stats are only for administrators.
if($user->caps['administrator']) :
?>

<p align="left" id="wp_alp_graph_widget" style="margin:0px;padding:20px 0px 0px 0px;">
	<script language="JavaScript" type="text/javascript">
	<!--
	if (AC_FL_RunContent == 0 || DetectFlashVer == 0) {
		alert("This page requires AC_RunActiveContent.js.");
	} else {
		var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
		if(hasRightVersion) {
			AC_FL_RunContent(
				'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,45,0',
				<?php if($wp_version < 2.7): ?>
				'width', '550',
				'height', '240',
				'scale', 'noscale',
				<?php else: ?>
				'width', '100%',
				'height', '180',
				'scale', 'default',
				<?php endif; ?>
				'salign', 'TL',
				'bgcolor', '#ffffff',
				'wmode', 'transparent',
				'movie', 'charts',
				'src', '<?php bloginfo('url');?>/wp-content/plugins/wp-alp/resources/swf/charts',
				'FlashVars', 'library_path=../wp-content/plugins/wp-alp/resources/swf/charts_library&xml_source=../wp-content/plugins/wp-alp/view/admin/graph_xml.php',
				'id', 'alp_chart_dashboard',
				'name', 'alp_chart_dashboard',
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
	</script> 			            																															<?php if($wp_version < 2.7):?><p style="padding:5px 0px 5px 75px;"><small>wp-alp by:&nbsp;<a href="http://www.daveligthart.com" target="_blank" title="WP-Alp created By Dave Ligthart">daveligthart.com</a></small></p><?php else: ?><div align="right"><small>wp-alp by:&nbsp;<a href="http://www.daveligthart.com" target="_blank" title="WP-Alp created By Dave Ligthart">daveligthart.com</a></small></div><?php endif;?>
</p>

<?php endif; ?>