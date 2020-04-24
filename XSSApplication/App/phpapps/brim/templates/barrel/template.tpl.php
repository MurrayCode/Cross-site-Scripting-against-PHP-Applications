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

include 'templates/barrel/iconDefinitions.inc';
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.templates
 * @subpackage barrel
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
		>@import "templates/barrel/template.css";</style>
	<style type="text/css" media="print"
		>@import "templates/barrel/print.css";</style>
	<?php
		if (isset ($pluginName))
		{
			$file = "plugins/".$pluginName."/view/template.css";
			if (file_exists ($file))
			{
	?>
			<!--
				The plugins DEFAULT stylesheet
				(plugin specific styles end up here
				so we can keep the main stylesheet generic
			-->
			<style type="text/css" media="screen"
				>@import "plugins/<?php echo ($pluginName); ?>/view/template.css";</style>
	<?php
			}
		}
	?>
	<!--
		A plugin can also provide a specific stylesheet for a template.
		If this stylesheets exists, load it as well
	-->
	<?php
		$style = 'templates/barrel/plugins/'.$pluginName.'.css';
		if (file_exists ($style))
		{
			echo ('<style type="text/css" media="screen"
				>@import "'.$style.'";</style>');
		}
	?>

	<!--
		The stylesheet for the menu handling
	-->
	<style type="text/css">
		.active a:visited,
		.active a:link,
		.active a:hover
		{
			font:bold 12px arial,helvetica,sans-serif;
			margin:12px 0px 0px 5px;
			text-decoration:none;
			color:#666666;
		}
		.inactive a:visited,
		.inactive a:link,
		.inactive a:hover
		{
			font:bold 12px arial,helvetica,sans-serif;
			margin:12px 0px 0px 5px;
			text-decoration:none;
			color:#666666;
		}
		.inactive a:hover,
		.active a:hover
		{
			font:bold 12px arial,helvetica,sans-serif;
			margin:12px 0px 0px 5px;
			text-decoration:none;
			color: white;
		}
	</style>

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
		function expand (actionGroupName)
		{
			group = document.getElementById(actionGroupName+'_groupName');
			if (group)
			{
					if (group.className == 'active')
					{
						group.className='inactive';
					}
					else
					{
						group.className = 'active';
					}
					items = document.getElementById(actionGroupName+'_actions').style;
					if (items.display=='none')
					{
						items.display = '';
					}
					else
					{
						items.display = 'none';
					}
			}
		}
		/**
		 * Overlib colors
		 */
		var ol_textcolor="#123456";
		var ol_capcolor="#ffffff";
		var ol_bgcolor="#123456";
		var ol_fgcolor="#efefef";
	</script>

</head>
<body>
<div id="loading" style="display:none"></div>
<!--
	Overlib, JavaScript popups
-->
<div id="overDiv"
	style="position:absolute; visibility:hidden; z-index:1000;"></div>


<!--
	Icon definition
-->
<?php include 'templates/barrel/icons.inc' ?>


<!--
	Show the logo
-->
<div id="logo">
	<a href="index.php"><img src="templates/barrel/pics/brim_logo.gif"
		alt="[brim-project]"
		width="175" height="35" border="0"/></a>
</div>
<!--
	The plugin menu contains links to the activated plugins
	like bookmarks, contacts, etc...
-->
<?php
	//
	// First split the menu in two parts: one part up until the
	// plugin we are using, the second part after the plugin
	// we are using. The plugins actions will be shown in between
	//
	$index=-1;
	for ($i=0; $i<count ($menuItems); $i++)
	{
		$current = $menuItems[$i];
		if ($dictionary[$current['name']] == $dictionary['item_title'])
		{
			$index=$i;
			break;
		}
	}
	if ($index==-1)
	{
		$menuItemTillCurrent = null;
		$menuItemsCurrent['name']=$dictionary['item_title'];
		if ($dictionary['item_title'] == '')
		{
				//
				// There is a bug here somewhere
				//
				//die (print_r ($dictionary['item_title']));
		}
		$menuItemsAfterCurrent = $menuItems;
	}
	else
	{
		$menuItemsTillCurrent = array_slice ($menuItems, 0, $index);
		$menuItemsCurrent = $menuItems[$index];
		$menuItemsAfterCurrent = array_slice ($menuItems, $index+1);
	}
?>
<div id="pluginMenu">
<?php
	//
	// Now show the first menu
	//
	if (isset ($menuItemsTillCurrent) && count ($menuItemsTillCurrent) > 0)
	{
		echo '<div id="pluginMenuBeforeCurrent">';
		echo '<ul>';
		foreach ($menuItemsTillCurrent as $link)
		{
			echo ('<li><a ');
			echo ('href="'.$link['href'].'" ');
			echo ('title="'.$dictionary[$link['name']].'" ');
			echo ('>'.$dictionary[$link['name']].'</a>');
		}
		echo '</ul>';
		echo '</div>';
	}
?>
<div id="pluginMenuCurrent">
<ul>
<?php
	echo ('<li><a ');
	if (isset ($menuItemsCurrent['href']))
	{
		echo ('href="'.$menuItemsCurrent['href'].'" ');
	}
	else
	{
		echo ('href="#" ');
	}
	echo ('title="'.$menuItemsCurrent['name'].'" ');
	if (isset ($dictionary[$menuItemsCurrent['name']]))
	{
		echo ('>'.$dictionary[$menuItemsCurrent['name']].'</a>');
	}
	else
	{
		echo ('>'.$menuItemsCurrent['name'].'</a>');
	}
?>
</ul>
</div>

<!--
	The actual pane. The plugin provides information that
	will be displayed on this pane
-->
<div id="actionMenu">


<?php
	//
	// Render actions are labeled under a header like 'actions' etc.
	//
	if (isset ($renderActions))
	{
		//
		// Loop over each group
		//
		foreach ($renderActions as $actionGroup)
		{
			echo '<div id="'.$actionGroup['name'].'_groupName" class="inactive">';
			echo '<p class="actionGroupTitle">';
			echo "<a href=\"javascript:expand('".$actionGroup['name']."');\">";
			echo $dictionary[$actionGroup['name']];
			echo '</a>';
			echo '</p>';
			echo '</div>';
			//
			// Loop over each item in the group
			//
			echo '<div id="'.$actionGroup['name'].'_actions" style="display:none">';
			foreach ($actionGroup['contents'] as $action)
			{
					echo '<div class="action">';
					echo '<a href="'.$action['href'];
					echo '">'.$dictionary[$action['name']].'</a>';
					echo '</div>';
			}
			echo '</div>';
		}
	}
?>
</div>

<?php
	if (isset ($menuItemsAfterCurrent) && count ($menuItemsAfterCurrent) > 0)
	{
		echo '<div id="pluginMenuAfterCurrent">';
		echo '<ul>';
		//
		// and show the second menu
		//
		foreach ($menuItemsAfterCurrent as $link)
		{
			echo ('<li>');
			echo ('<div id="menu'.$link['name'].'">');
			echo ('<a ');
			echo ('href="'.$link['href'].'" ');
			echo ('title="'.$dictionary[$link['name']].'" ');
			echo ('>'.$dictionary[$link['name']].'</a>');
			echo ('</div>');
			echo ('</li>');
		}
		echo '</ul>';
		echo '</div>';
	}
?>
<?php
	echo ('<p class="copyright">');
	echo ('<a href="http://opensource.org/licenses/gpl-license.php">GPL</a> - ');
	echo ($dictionary['copyright'].'<br />');
  echo ('by <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authorname'].'</a>&nbsp;');
	echo ('<br /><a href="'.$dictionary['programurl'].'"
		>'.$dictionary['programname'].' Homepage</a>');
	echo ('</p>');
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
	echo ('<input type="hidden" name="action" value="search">');
	foreach ($menu as $link)
	{
		echo ('<span class="applicationMenuLink">');
		echo ('<a href="'.$link['href'].'">'.$dictionary[$link['name']].'</a>');
		echo ('</span>');
		echo ('&middot;');
	}
	echo ('<span class="applicationMenuLink">'.$dictionary['search'].'&nbsp;');
	echo ('</span>');
	echo ('<input type="text" style="width: 100px;height: 15px;" name="value" id="searchfield"/>');
?>
</form>
</div>
<!--
	Now display the content provided by the plugin
-->
<div id="content">
<?php
	if (isset ($message))
	{
		echo ('<div id="pluginWarning">');
		echo '<h2 class="pluginWarning">';
		echo $dictionary[$message];
		echo '</h2>';
		echo ('</div>');
	}
?>
	<div id="pluginTitlex">
		<h2 class="pluginTitle"><?php echo $dictionary['item_title']; ?></h2>
	</div>
	<br />
	<?php include $renderer; ?>
</div>

<?php if (isset ($_SESSION['brimDefaultExpandMenu']) && ($_SESSION['brimDefaultExpandMenu'] == 1)) { ?>
   <script type="text/javascript" language="javascript">
   // let's expand some of the menus....
   expand('actions');
   expand('view');
   expand('sort');
   //expand('preferences');
   //expand('help');
   
   </script>
<?php } ?>
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
