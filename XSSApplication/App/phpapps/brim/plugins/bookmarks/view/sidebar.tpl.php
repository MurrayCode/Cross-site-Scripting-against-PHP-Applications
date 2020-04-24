<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.bookmarks
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

if (!isset ($installationPath) || trim ($installationPath) == '')
{
	die ('Installation path not set. Please contact your administrator and report the problem');
}
$lastChar = $installationPath{strlen($installationPath)-1};
if ($lastChar != '/')
{
	$installationPath .= '/';
}
?>
	<script type="text/javascript">
	<!--
        function addBookmarksNetscapePanel()
        {
                if ((typeof window.sidebar == "object") &&
                        (typeof window.sidebar.addPanel == "function"))
                {
                        window.sidebar.addPanel
                        ("Brim Bookmarks",
                        	"<?php echo ($installationPath); ?>BookmarkSidebarController.php", "");
                }
        }
	 // -->
	</script>
	<h1>Sidebars</h1>
	<table border="0" cellpadding="4" cellspacing="4">
	<tr>
		<td>
			<a
			href="javascript:addBookmarksNetscapePanel();"
			><img src="plugins/bookmarks/view/pics/addnetscapepanel.gif"
			border="0"></a>
		</td>
		<td>
			<h2>Netscape</h2>
		</td>
		<td>
			<a href="http://www.netscape.com/">http://www.netscape.com</a>
		</td>
	</tr>
	<tr>
		<td>
			<a
			href="javascript:addBookmarksNetscapePanel();"
			><img src="plugins/bookmarks/view/pics/addmozillapanel.gif"
			border="0"></a>
		</td>
		<td>
			<h2>Mozilla</h2>
		</td>
		<td>
			<a href="http://www.mozilla.org/">http://www.mozilla.org</a>
		</td>
	</tr>
	<tr>
		<td>
			<a href="<?php echo ($installationPath);
			?>BookmarkSidebarController.php"
			rel="sidebar" title="Brim Bookmarks"
			><img src="plugins/bookmarks/view/pics/opera.png"
			border="0"></a>
		</td>
		<td>
			<h2>Opera</h2>
		</td>
		<td>
			<a href="http://www.opera.com/">http://www.opera.com</a>
		</td>
	</tr>
	<tr>
		<td>
			<a
			href="javascript:addBookmarksNetscapePanel();"
			><img src="plugins/bookmarks/view/pics/firefox-icon.png"
			border="0"></a>
		</td>
		<td>
			<h2>Firefox</h2>
		</td>
		<td>
			<a href="http://www.mozilla.org/firefox">http://www.mozilla.org/firefox</a>
		</td>
	</tr>
	</table>