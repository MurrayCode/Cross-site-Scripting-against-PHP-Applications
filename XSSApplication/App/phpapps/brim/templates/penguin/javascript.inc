<?php

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
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2005 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
?>
<script type="text/javascript" language="JavaScript">
<!--
	var ol_textcolor="#000000";
	var ol_capcolor="#ffffff";
	var ol_bgcolor="#bbbbbb";
	var ol_fgcolor="#cccccc";

	function addBookmarksNetscapePanel($location)
	{
		if ((typeof window.sidebar == "object") &&
			(typeof window.sidebar.addPanel == "function"))
		{
			window.sidebar.addPanel
				("Brim Bookmarks", "$location", "");
		}
	}

	function confirmDelete ()
	{
		return confirm ("<?php echo $dictionary['confirm_delete']?>");
	}
//-->
</script>