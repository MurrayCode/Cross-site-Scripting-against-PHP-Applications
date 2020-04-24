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
 * @author Kai Schaper
 * @package org.brim-project.templates
 * @subpackage oerdec
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
        "http://www.w3.org/TR/html4/strict.dtd">
<html>
 <head>
    <!--[if lt IE 7.]>
        <script defer type="text/javascript" src="ext/pngfix.js"></script>
    <![endif]-->

	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title><?php echo $title ?></title>
	<!--
		The template stylesheet
	-->
<!--
		content="text/html; charset=<?php echo $dictionary['charset'] ?>">
-->
<!--
	The plugins DEFAULT stylesheet (plugin specific styles end up
	here so we can keep the main stylesheet generic
-->
<?php
	$style = 'plugins/'.$pluginName.'/view/template.css';
	if (file_exists ($style))
	{
		echo ('<style type="text/css" media="screen"
		>@import "'.$style.'";</style>');
	}
?>
<!--
	A plugin can also provide a specific stylesheet for a template.
	If this stylesheets exists, load it as well
-->
<?php
	$style = 'templates/oerdec/plugins/'.$pluginName.'.css';
	if (file_exists ($style))
	{
		echo ('<style type="text/css" media="screen"
		>@import "'.$style.'";</style>');
	}
?>

	<link rel="stylesheet" type="text/css" href="templates/oerdec/basic.css">
	<style type="text/css" media="screen">@import "templates/oerdec/template.css";</style>
	<style type="text/css" media="print"
		>@import "templates/oerdec/print.css";</style>

	<script type="text/javascript" 
			language="JavaScript" src="ext/overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
	<script type="text/javascript" 
			language="JavaScript" src="ext/overlib/overlib_fade.js"></script>
	<script type="text/javascript" 
			language="JavaScript" src="ext/overlib/overlib_bubble.js"></script>
	<script type="text/javascript" src="ext/jQuery/jquery.js"></script>
	<script type="text/javascript" src="ext/jQuery/jquery.jeditable.js"></script>
	<script type="text/javascript" src="ext/jQuery/iutil.js"></script>
	<script type="text/javascript" src="ext/jQuery/idrag.js"></script>
	<script type="text/javascript" src="ext/jQuery/idrop.js"></script>
	<script type="text/javascript" src="ext/jQuery/ifx.js"></script>
	<script type="text/javascript" src="ext/jQuery/ifxtransfer.js"></script>
    <!--
        Generic Javascript functions
    -->
    <script type="text/javascript"
            language="JavaScript" src="framework/view/javascript/brim.js"></script>

	<script type="text/javascript" language="javascript">
	<!--
    var ol_textcolor="#000000";
    var ol_capcolor="#000000";
    var ol_bgcolor="#d4d0c8";
    var ol_fgcolor="#f9f9f9";
	// -->
	</script>

 </head>
<body>
<div id="loading" style="display:none">
	<?php echo $dictionary['loadingIndication'] ?>
</div>
<!--
    Overlib, JavaScript popups
-->
<div id="overDiv"
    style="position:absolute; visibility:hidden; z-index:1000;"></div>



<!--
    Icon definition
-->
<?php include 'templates/oerdec/icons.inc' ?>


<!-- first menue -->
<div id="first-menue">
<div class="first-menue-item"><a href="index.php">Dashboard&nbsp;</a></div>
<?php
foreach ($menuItems as $menuItem)
{
?>
<div id="menu<?php echo $menuItem['name']; ?>" class="first-menue-item"><a href="<?php echo $menuItem['href'] ?>"><?php echo $dictionary[$menuItem['name']] ?>&nbsp;</a></div>
<?php
}
?>
<form method="POST" action="SearchController.php" name="searchBox">
<?php
	foreach ($menuItems as $menuItem)
	{
		echo ('<input type="hidden" name="search_'.$menuItem['name'].'">');
	}
?>
<input type="hidden" name="action" value="search">
<span class="first-menue-item-search"><?php echo $dictionary['search'] ?><br />
<input type="text" style="width: 100px;height: 15px;" name="value" id="searchfield"/>
</span>
</form>
</div>
<!-- first menue end -->

<!-- second menue -->
<div id="second-menue">
<div id="headline">
<span id="headline-brim"><?php echo $title ?></span>
</div>
<div id="second-menue-items">
<?php
        foreach ($menu as $link)
        {
                echo ('<span class="second-menue-item">');
                echo ('<a href="'.$link['href'].'">'.$dictionary[$link['name']].'</a>');
                echo ('</span>');
                echo ('&middot;');
        }
?>
</div>

<!--
<div id="second-menue-items">
<?php
foreach ($menu as $crumb)
{
?>
<span class="second-menue-item"><a href="<?php echo $crumb['href'] ?>"><?php echo $dictionary[$crumb['name']] ?></a> &middot; </span>
<?php
}
?>
</div>
-->
</div>
<!-- second menue end -->


<div id="main">

<!-- options -->
<div id="options">

		<?php
		if (isset ($renderActions)) {
						foreach ($renderActions as $renderAction) {
		?>
		<p class="options-hl"><?php echo $dictionary[$renderAction['name']] ?></p>
			<div class="options-item">
		<?php
										foreach ($renderAction['contents'] as $current)
										{
										?>

										<a href="<?php echo $current['href'] ?>">[<?php echo $dictionary[$current['name']] ?>]</a>

										<?php
										}
										?>
		</div>

		<?php
						} // foreach end
		} // if end
		?>

<!-- help -->
								<?php
								if (isset ($dictionary['item_quick_help'])
									&& $dictionary['item_quick_help']!='') {
												echo ("<p class=\"options-hl\">");
												echo ($dictionary['help']);
												echo ('</p>');
												echo ("<div class=\"options-item\">");
												echo ($dictionary['item_quick_help']);
												echo ('</div>');
								}
								?>
<!-- help end -->

</div>
<!-- options end -->

<!-- content -->
<div id="content">
<?php 
	if (isset ($message))
	{
		echo '<h2>'.$dictionary[$message].'</h2>';
	}
	include $renderer 
?>
</div>
<!-- content end -->

<!-- footer -->
<div id="footer"><?php echo $dictionary['license_disclaimer'] ?></div>
<!-- footer end -->

</div>

<script type="text/javascript" language="javascript">
/* This code sets the focus of the cursor for typing in the following order:
  1. The search box at the top
  2. The name field of most plugins (bookmark, contact, etc)
  3. the ID of the name field on the calendar is 'nameProxy' instead of 'name'
  like other plugins
*/
document.getElementById('searchfield').focus();
if (document.getElementById('name'))
{
	document.getElementById('name').focus();
}
if (document.getElementById('nameProxy'))
{
	document.getElementById('nameProxy').focus();
}
</script>
</body>
</html>
