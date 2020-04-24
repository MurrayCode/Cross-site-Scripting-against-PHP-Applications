<?php

require_once ('framework/model/AdminServices.php');

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.plugins.bookmarks
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

echo $dictionary['quickmark'];
$adminServices = new AdminServices ();
$installationPath = $adminServices->getAdminConfig ('installation_path');
$lastChar = $installationPath{strlen($installationPath)-1};
if ($lastChar != '/')
{
	$installationPath .= '/';
}
?>
<html>
<head>
	<title>
		Brim Mozilla/Netscape SideBar
	</title>
	<script type="text/javascript">
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
	</script>
 	<link rel="stylesheet" href="documentation/css/brim.css"
		type="text/css" />

</head>

<body>
	<h1>Bookmarks</h1>
	<h2>sidebars</h2>
	<table border="0">
	<tr>
		<td>
			<a
			href="javascript:addBookmarksNetscapePanel();"
			><img src="framework/view/pics/addnetscapepanel.gif"
			border="0"></a>
		</td>
		<td>
			<a
			href="javascript:addBookmarksNetscapePanel();"
			><img src="framework/view/pics/addmozillapanel.gif"
			border="0"></a>
		</td>
		<td>
			<a href="<?php echo ($installationPath);
			?>BookmarkSidebarController.php"
			rel="sidebar" title="Brim
			Bookmarks"><img src="framework/view/pics/opera.png"
			border="0"></a>
		</td>
		<td>
			<a
			href="javascript:addBookmarksNetscapePanel();"
			><img src="framework/view/pics/firefox-icon.png"
			border="0"></a>
		</td>
	</tr>
</body>
</html>