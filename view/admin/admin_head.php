<?php
/**
 * Admin head template.
 * @author dligthart <info@daveligthart.com>
 * @version 0.1
 * @package ALP
 */
?>
<?php if($_REQUEST['page'] == 'wp-alp'): ?>
<script type="text/javascript" src="<?php bloginfo('url'); ?>/wp-content/plugins/wp-alp/resources/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery("#statstable").tablesorter({sortList: [[0,0], [1,0]]});
	jQuery("#alp_content").load("<?php bloginfo('url') ?><?= '/wp-content/plugins/wp-alp/view/admin/admin_log.php?log_path='.$log_path?>");
});
</script>
<style type="text/css">
/* tables */
table.tablesorter {
	background-color: #CDCDCD;
	margin:10px 0pt 15px;
	width: 100%;
	text-align: left;
	border:1px solid #ccc;
}
table.tablesorter thead tr th, table.tablesorter tfoot tr th {
	background-color: #e6EEEE;
	padding: 4px;
}
table.tablesorter thead tr .header {
	background-image: url(<?php bloginfo('url') ?>/wp-content/plugins/wp-alp/resources/images/bg.gif);
	background-repeat: no-repeat;
	background-position: center right;
	cursor: pointer;
}
table.tablesorter tbody td {
	color: #3D3D3D;
	padding: 4px;
	background-color: #FFF;
	vertical-align: top;
}
table.tablesorter tbody tr.odd td {
	background-color:#F0F0F6;
}
table.tablesorter thead tr .headerSortUp {
	background-image: url(<?php bloginfo('url') ?>/wp-content/plugins/wp-alp/resources/images/asc.gif);
}
table.tablesorter thead tr .headerSortDown {
	background-image: url(<?php bloginfo('url') ?>/wp-content/plugins/wp-alp/resources/images/desc.gif);
}
table.tablesorter thead tr .headerSortDown, table.tablesorter thead tr .headerSortUp {
	background-color: #8dbdd8;
}
</style>
<script language="javascript">AC_FL_RunContent = 0;</script>
<script language="javascript"> DetectFlashVer = 0; </script>
<script src="<?php bloginfo('url') ?>/wp-content/plugins/wp-alp/resources/js/AC_RunActiveContent.js" language="javascript"></script>
<script language="JavaScript" type="text/javascript">
<!--
var requiredMajorVersion = 9;
var requiredMinorVersion = 0;
var requiredRevision = 45;
-->
</script>
<?php endif; ?>