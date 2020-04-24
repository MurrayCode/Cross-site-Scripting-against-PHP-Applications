<?php

header("Content-Type: text/html; charset=utf-8");

if (
	isset ($_REQUEST['renderer']) || 
	isset ($_GLOBALS['renderer']) ||
	isset ($_REQUEST['GLOBALS']) ||
	isset ($_REQUEST['_GLOBALS']) ||
	isset ($_FILES['GLOBALS'])
)
{
	die (print_r ("Invalid access"));
}
$renderer = str_replace ("*", "", $renderer);
$renderer = str_replace ("<", "", $renderer);
$renderer = str_replace (">", "", $renderer);
$renderer = str_replace ("..", "", $renderer);
$renderer = str_replace ("//", "", $renderer);
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.templates
 * @subpackage sidebar
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
?>
<html>
<head>
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css"
		href="templates/sidebar/template.css" />
	<meta http-equiv="Content-Type"
		content="text/html; charset=<?php echo $dictionary['charset'] ?>">

<?php include "templates/sidebar/icons.inc" ?>
</head>
<body>
<?php
if (isset ($_SESSION['bookmarkOverlib']))
{
?>
<script type="text/javascript" src="ext/overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
<script type="text/javascript" src="ext/overlib/overlib_fade.js"></script>
<script type="text/javascript" src="ext/overlib/overlib_bubble.js"></script>
<?php } ?>
	<table border="0" cellspacing="0" cellpadding="0" width="99%"
		height="95%">
	<tr>
		<td>
		</td>
	</tr>
	<tr>
		<td width="100%" valign="top" bgcolor="#ffffff">
			<!-- including file: <?php echo $renderer; ?> -->
			<?php
				include $renderer;
			?>
		</td>
	</tr>
	</table>
</body>
</html>
