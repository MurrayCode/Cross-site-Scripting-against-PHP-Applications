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
 * @subpackage penguin
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

/**
 * Creates the pluginbar, the bar containing the icons and textual description
 * for the enabled plugins
 */
function createPluginBar ($dictionary)
{
	global $menuItems;
	//
	// Fetch the icon size from the session (placed by the global preferences)
	// and default to 48x48 if not found
	//
	$iconSize = $_SESSION['brimPreferedIconSize'];
	if ($iconSize == '') { $iconSize = '48x48'; }
	//
	// I.E does not know how to handle transparent PNGs so use gif for IE
	//
	$browserUtils = new BrowserUtils ();
	if ($browserUtils->browserIsExplorer())
	{
		$ext = '.gif';
	}
	else
	{
		$ext = '.png';
	}
	//
	// The bar is a table with one row per installed plugin
	//
	$result = '<table witdh="195" valign="top" border="0">';
	//
	// Display the icons for the different plugins
	//
	foreach ($menuItems as $menuItem)
	{
		$result .= ' <tr> <td valign="top" ';
		if ($iconSize != '16x16' && $iconSize != '24x24')
		{
			$result .= 'align="center" ';
		}
		else
		{
			$result .= 'align="left" ';
		}
		$result .= '>';
		$result .= '<div id="menu'.$menuItem['name'].'">';
		$result .= '<span style="white-space: nowrap;">';

		$result .= '<a href="'.$menuItem['href'].'" ';
		$result .= 'name="'.$menuItem['name'].'" ';
		$result .= 'id="'.$menuItem['name'].'" ';
		$result .= '>';
		//
		// First check the template subdirectory for this specific
		// plugin and if it exists, fetch the icon from there,
		// otherwise take the plugin default
		//
		if (isset ($menuItem['icon']))
		{
			$icon = 'templates/penguin/plugins/'.
				$menuItem['name'].'_'.$iconSize.$ext;
			if (!file_exists ($icon))
			{
				$icon = 'plugins/'.$menuItem['name'].
					'/view/pics/item_'.$iconSize.$ext;
				if (!file_exists ($icon))
				{
					$icon = 'plugins/'.$menuItem['name'].
						'/view/pics/item'.$ext;
				}
			}
			$result .= '<img src="'.$icon.'" border="0" alt="'.$menuItem['name'].'"/>';
			if ($iconSize != '16x16' && $iconSize != '24x24')
			{
				$result .= '<br />';
			}
		}
		//
		// Print the name of the plugin under the icon. Don't let the
		// space be part of the anchor since this leads to ugly lines
		// (IMHO); print the link again
		//
		$result .= '</a>&nbsp;';
		$result .= '<a href="'.$menuItem['href'].'" ';
		//$result .= 'name="'.$menuItem['name'].'" ';
		//$result .= 'id="'.$menuItem['name'].'" ';
		$result .= 'class="pluginLink" ';
		$result .= '>';
		$result .= $dictionary[$menuItem['name']];
		$result .= '</span>';

		$result .= '</div>';
		$result .= '</td> </tr>';
	}
	//
	// Display the application image under all menuitems
	//
	$result .= '<tr> <td valign="bottom"> <a href="index.php"><img ';
	$result .= 'src="templates/penguin/pics/linux.jpg" border="0" alt=""/></a>';
	$result .= '</table>';
	return $result;
}
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
	<link rel="stylesheet" type="text/css"
		href="templates/penguin/template.css" />
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
	$style = 'templates/penguin/plugins/'.$pluginName.'.css';
	if (file_exists ($style))
	{
		echo ('<style type="text/css" media="screen"
		>@import "'.$style.'";</style>');
	}
?>

	<?php
		$defaultFavIcon = 'plugins/'.$pluginName.'/templates/'.
		 	'default/pics/favicon.ico';
		$templateFavIcon = 'plugins/'.$pluginName.'/templates/'.
			$_SESSION['brimTemplate'].'/pics/favicon.ico';

		if (file_exists ($templateFavIcon))
		{
			echo ('<link rel="Shortcut Icon" type="image/x-ico" ');
			echo ('href="'.$templateFavIcon.'" />');
		}
		else if (file_exists ('$defaultFavIcon'))
		{
			echo ('<link rel="Shortcut Icon" type="image/x-ico" ');
			echo ('href="'.$defaultFavIcon.'" />');
		}
	?>

	<?php include "templates/penguin/icons.inc" ?>
	<?php include ('templates/penguin/javascript.inc') ?>

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

</head>
<body>
<div id="loading" style="display:none">
	<?php echo $dictionary['loadingIndication'] ?>
</div>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<table>
	<tr>
		<!--
			 Menu Bar. Contains links to preferences, about, logout etc.
		-->
		<td colspan="2" class="menu2">
			<!--
				The menubar is a table itself, containing only one row
				(tr) and a datacell (td) for each item
			-->
			<table>
			<tr>
				<td>
					<table>
					<tr>
						<?php
							foreach ($menu as $crumbs)
							{
						?>
							<td align="center">
								<a href="<?php echo ($crumbs['href']) ?>"
										class="menu"
										>[<?php echo ($dictionary[$crumbs['name']]) ?>]</a>
							</td>
						<?php
							}
						?>
					</tr>
					</table>
				</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<!--
			Display the actual content pane.
			This content pane should be of no real concern for the
			template.
		-->
		<td width="100%" valign="top">
			<table cellpadding="5" cellspacing="5" bgcolor="#f3f3f3"
				width="100%" height="100%" border="0">
			<tr>
				<td valign="top" width="100%">
						<?php 
							if (isset ($message))
							{
								echo $icons['warning'].'<br />'.$dictionary[$message];
							}
							include $renderer; 
						?>
				</td>
				<td align="right" valign="top">
					<h1><?php echo $dictionary['item_title'] ?></h1>
					<!--
						Now display the actions that can be performed
						on this item
					-->
					<?php
						if (isset ($renderActions))
						{
					?>
                        <select name="action" onChange="location = this.options[this.selectedIndex].value;">
                            <option value="">Options</option>
                            <option value=""></option>
					<?php
							foreach ($renderActions as $renderAction)
							{
								if (isset ($dictionary[$renderAction['name']]))
								{
									echo '<option value="">['.$dictionary[$renderAction['name']].']</option>';
								}
								else
								{
									echo '<option value="">['.$renderAction['name'].']</option>';
								}
								foreach ($renderAction['contents'] as $current)
								{
									echo '<option value="'.$current['href'].'">';
									echo '&nbsp;&nbsp;&nbsp;';
									echo $dictionary[$current['name']];
									echo '</option>';
								}
							}
						}
						if (isset ($renderActions))
						{
					?>
						</select>
					<?php
						}
					?>
						<br />
						<?php
							if (isset ($dictionary['item_quick_help'])
								&& $dictionary['item_quick_help']!='')
							{
								echo ($dictionary['item_quick_help']);
							}
							if (isset ($message) && isset ($message))
							{
								if (get_class ($message) == 'string')
								{
									echo ('<h2>');
									echo ($icons['message'].'&nbsp;');
									echo ($dictionary['message']);
									echo ('</h2>');
									echo ($dictionary[$message]);
								}
							}
						 ?>
					</td>
				</tr>
			</table>
		</td>
		<td align="center">
<form method="POST" action="SearchController.php" name="searchBox">
<a href="#" class="menu">[<?php echo $dictionary['search'] ?></a>
<?php
	foreach ($menuItems as $menuItem)
	{
		echo ('<input type="hidden" name="search_'.$menuItem['name'].'">');
	}
?>
<input type="hidden" name="action" value="search">
<input type="text" class="" style="width: 100px;height: 15px;" name="value" id="searchfield"/><a href="" class="menu">]</a>
</form>
<br />
			<?php echo createPluginBar ($dictionary); ?>
		</td>
	</tr>
	<!--
		Display the license disclaimer
	-->
	<tr>
		<td colspan="2">
    		<p>
        		<font size="-2">
					<?php echo $dictionary['license_disclaimer'] ?>
        		</font>
    		</p>
		</td>
	</tr>
	</table>

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
