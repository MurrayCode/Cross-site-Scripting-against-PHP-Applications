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
 * @subpackage barry
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
	<title><?php echo $dictionary['item_title'] ?></title>
	<!--
		The template stylesheet
	-->
	<style type="text/css" media="screen"
		>@import "templates/barry/template.css";</style>
	<style type="text/css" media="print"
		>@import "templates/barry/print.css";</style>
<!--
	The plugins DEFAULT stylesheet (plugin specific styles end up here 
	so we can keep the main stylesheet generic
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
	$style = 'templates/barry/plugins/'.$pluginName.'.css';
	if (file_exists ($style))
	{
		echo ('<style type="text/css" media="screen"
			>@import "'.$style.'";</style>');
	}
?>
	
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

	<script type="text/javascript" language="JavaScript">
	<!--
    	var ol_textcolor="#123456";
    	var ol_capcolor="#ffffff";
    	var ol_bgcolor="#123456";
    	var ol_fgcolor="#efefef";
	// -->
	</script>
</head>
<body>
<div id="loading" style="display:none">
	<?php echo $dictionary['loadingIndication'] ?>	
</div>


<!--
	Icon definition
-->
<?php include 'templates/barry/icons.inc' ?>


<!--
	The plugin menu contains links to the activated plugins
	like bookmarks, contacts, etc...
-->
<div id="pluginMenu">
<?php
	foreach ($menuItems as $link)
	{
		echo ('<div id="menu'.$link['name'].'" class="pluginMenuLink">');
		echo ('<a href="'.$link['href'].'">'.$dictionary[$link['name']].'</a>');
		echo ('</div>');
	}
?>
</div>


<!--
	The application menu. Contains items like: preferences, logout,
	plugins (the menu to enable/disable them), about etc
-->
<div id="applicationMenu">
<form method="POST" action="SearchController.php" name="searchBox">
<?php
        foreach ($menuItems as $menuItem)
        {
                echo ('<input type="hidden" name="search_'.$menuItem['name'].'">');
        }
?>
<input type="hidden" name="action" value="search">
<?php
        foreach ($menu as $link)
        {
                echo ('<span class="applicationMenuLink">');
                echo ('<a href="'.$link['href'].'">'.$dictionary[$link['name']].'</a>');
                echo ('</span>');
                echo ('&middot;');
        }
?>
<span class="applicationMenuLink">
<?php echo $dictionary['search'] ?>:</span>
<input type="text" style="width: 100px;height: 15px;" name="value" id="searchfield"/>
</form>
</div>

<!--
<div id="applicationMenu">
<?php
	$i=0;
	foreach ($menu as $link)
	{
		echo ('<span class="applicationMenuLink">');
		echo ('<a href="'.$link['href'].'">'.$dictionary[$link['name']].'</a>');
		echo ('</span>');
		
		//
		// I wonder... Can't this be done with a stylesheet param?
		//
		$i++;
		if ($i != count($menu))
		{
			echo ('&middot;');
		}
	}
?>
</div>
-->

<!--
	The actual pane. The plugin provides information that
	will be displayed on this pane
-->
<div id="actionMenu">
<!--
	Show the logo
-->
<div id="logo">
	<a href="index.php"><img src="templates/barry/pics/brim_logo.gif" 
		alt="[logo]"
		width="199" height="55" border="0"/></a>
</div>

<!--
	The title of the plugin we're currently using
-->
<p class="pluginTitle"><?php echo $dictionary['item_title']; ?></p>

<?php
	if (isset ($renderActions))
	{
		//
		// Render actions are labeled under a header 
		// like 'actions', 'view' etc
		//
		echo ('<div id="actions">');

		// 
		// Loop over each group
		//
		foreach ($renderActions as $actionGroup)
		{
			echo ('<p class="actionGroupTitle">');
			echo ($dictionary[$actionGroup['name']]);
			echo ('</p>');

			// 
			// Loop over each item in the group
			//
			foreach ($actionGroup['contents'] as $action)
			{
					echo ('<div class="action">');
					echo ('<a href="'.$action['href']);
					echo ('">'.$dictionary[$action['name']].'</a>');
					echo ('</div>');
			}
		}
		echo ('<p class="copyright">');
		echo ('<a href="http://opensource.org/licenses/gpl-license.php">GPL</a> - ');
		echo ($dictionary['copyright'].' by<br />');
		echo ('<a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authorname'].'</a>&nbsp;');
		echo ('<br /><a href="'.$dictionary['programurl'].'"
			>'.$dictionary['programname'].' Homepage</a>');
		echo ('</p>');
		echo ('</div>');
	}
?>
</div>

<!--
	Now display the content provided by the plugin
-->
<div id="content">
<?php
	if (isset ($message))
	{
		echo ($icons['message'].$dictionary[$message]);
	}
	include $renderer 
?>
</div>

<script type="text/javascript" language="javascript">
/* This code sets the focus of the cursor for typing in the following order:
  1. The search box at the top
  2. The name field of most plugins (bookmark, contact, etc)
  3. the ID of the name field on the calendar is 'nameProxy' instead of 'name'
  like other plugins
*/
document.getElementById('searchfield').focus();
if (document.getElementById ('name'))
{
	document.getElementById('name').focus();
}
if (document.getElementById ('nameProxy'))
{
	document.getElementById('nameProxy').focus();
}
</script>
</body>
</html>
