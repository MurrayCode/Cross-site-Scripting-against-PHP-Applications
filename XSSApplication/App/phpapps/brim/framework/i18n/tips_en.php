<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2006
 * @package org.brim-project.framework
 * @subpackage i18n
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
include 'framework/i18n/common.php';
if (!isset ($dictionary))
{
	$dictionary=array();
}
$dictionary['tip01']='Use the application preferences (as opposed to plugin specific preferences) to change the theme. Several themes are available, it doesn\'t hurt to try them all!';
$dictionary['tip02']='Use the application preferences (as opposed to plugin specific preferences) to change the icon size if you are using the penguin or the mylook template';
$dictionary['tip03']='Items can be edited by clicking on the icon in front of the item, folders can be edited by clicking on the folder icon';
$dictionary['tip04']='You can chose to disable javascript popups PER PLUGIN. Go to the plugin and select the plugin specific preferences';
$dictionary['tip05']='If you would like to import your IE favorites, you have to export your IE favorties as HTML from within Internet Explorer. This file can be imported into '.$dictionary['programname'].' as Netscape bookmark format';
$dictionary['tip06']='The password plugin has an option to generate a password per site, based on one global password. It basically takes the combination of the password you specify and the url of the site for which you want to generate a password. Easy concept, you only have to memorize one password!';
$dictionary['tip07']='The bookmark plugin has a QuickMark. This is a special URL which you need to add to the bookmarks of your browser. If you visit a site afterwards which you would like to bookmark, all you have to do is click the special QuickMark link and the site will automatically be added to '.$dictionary['programname'].'';
$dictionary['tip08']='This message can be disabled (and optionally enabled afterwards) via the application preferences';
$dictionary['tip09']='You can subscribe to new releases of this application via the <a href="http://sourceforge.net/projects/brim/">sourceforge '.$dictionary['programname'].' project site</a> or via the <a href="http://freshmeat.net/projects/brim/">Freshmeat '.$dictionary['programname'].' project site</a>.';
$dictionary['tip10']='On the <a href="'.$dictionary['programurl'].'">'.$dictionary['programname'].'</a> website, you can find the latest available versions, information on the different plugins available and more!';
$dictionary['tip11']='<a href="'.$dictionary['programurl'].'">'.$dictionary['programname'].'</a> (the bookmarks plugin of it) also integrates with <a href="http://wordpress.org/">Wordpress</a>. You can find a demo on the following site: <a href="'.$dictionary['authorurl'].'">'.$dictionary['authorurl'].'</a>';
$dictionary['tip12']='The task plugin offers you the possibility to dis/en-able completed tasks';
$dictionary['tip13']='You can now be reminded of non-recurring events (calendar plugin) via email. When adding/modifying an event, there is an option to add reminders. If this option is not available, ask your system administrator for help';
$dictionary['tip14']='You wish to contribute to '.$dictionary['programname'].' but don\'t know how? Translate! Use the translate option in the application menu (next to logout, preferences, plugin
s etc) to show the embedded translation tool. This tool enables you to translate the framework and the plugins (each part has a different translation dictionary)';
?>
