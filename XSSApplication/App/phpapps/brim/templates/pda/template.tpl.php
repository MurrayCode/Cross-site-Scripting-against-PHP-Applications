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
 * @subpackage pda
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

$allowedPDAActions = array ();
$allowedPDAActions [] = 'add';
$allowedPDAActions [] = 'multipleSelect';
$allowedPDAActions [] = 'expand';
$allowedPDAActions [] = 'collapse';
$allowedPDAActions [] = 'help';

if ($pluginName == 'webtools')
{
	$allowedPDAActions [] = 'rot13';
	$allowedPDAActions [] = 'calculator';
	$allowedPDAActions [] = 'subnetCalculator';
}
$_SESSION['contactColumnCount']=1;
$_SESSION['noteYahooTreeColumnCount']=1;
$_SESSION['taskYahooTreeColumnCount']=1;
?>
<?php include 'templates/pda/icons.inc' ?>
<html>
<head>
	<title>BRIM - <?php echo $dictionary['item_title'] ?></title>
	<meta http-equiv="Content-Type"
		content="text/html; charset=<?php echo $dictionary['charset'] ?>">
	<!--
	<style type="text/css" media="screen"
		>@import "templates/pda/template.css";</style>
	-->
	<link rel="stylesheet" type="text/css"
		href="templates/pda/template.css" />
</head>
<body>
<table>
	<!--
		Row with image and title
	-->
	<tr>
		<td background="templates/pda/pics/bg.jpg" >
			<table>
				<tr>
					<td>
						<img src="templates/pda/pics/b.jpg" />
					</td>
					<td valign="bottom">
						<h1>Brim -
							<?php echo $dictionary['item_title'] ?>
						</h1>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<!--
		Row with list of plugins
	-->
	<tr>
		<td>
			[
			<?php
				$i=0;
				foreach ($menuItems as $plugin)
				{
					echo '<a href="'.$plugin['href'].'">';
					echo $dictionary[$plugin['name']].'</a>';
					$i++;
					if ($i != count ($menuItems))
					{
						echo ' | ';
					}
				}
			?>
			]
		</td>
	</tr>
	<!--
		Row with application menu
	-->
	<tr>
		<td class="applicationMenu">
			[
			<a href="PreferenceController.php">Prefs</a> |
			<a href="AboutController.php">About</a> |
			<a href="PluginController.php">Plugins</a> |
			<a href="HelpController.php">Help</a>
			]
			[<a href="logout.php">X</a>]
		</td>
	</tr>
	<!--
		Row with plugin specific actions
	-->
	<tr>
		<td>
			<?php
				if (isset ($renderActions))
				{
					foreach ($renderActions as $actionGroup)
					{
						foreach ($actionGroup['contents'] as $action)
						{
							if (in_array ($action['name'], $allowedPDAActions))
							{
								echo ('<a href="'.$action['href']);
								echo ('">'.$dictionary[$action['name']].'</a> ');
							}
						}
					}
				}
			?>
		</td>
	</tr>
	<!--
		Row with content pane
	-->
	<tr>
		<td>
			<?php
				if (isset ($message))
				{
					echo ($dictionary[$message]);
				}
				include $renderer;
			?>
		</td>
	</tr>
	<!--
		Copyright notice
	-->
	<tr>
		<td class="copyright">
			<?php
    		echo ('<a href="http://opensource.org/licenses/gpl-license.php">GPL</a> - ');
    		echo ($dictionary['copyright'].' by<br />');
    		echo ('<a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authorname'].'</a>&nbsp;');
    		echo ('<br /><a href="'.$dictionary['programurl'].'"
    			>'.$dictionary['programname'].' Homepage</a>');
			?>
		</td>
	</tr>
</table>
</body>
</html>
