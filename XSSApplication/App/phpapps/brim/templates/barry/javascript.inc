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
 * @subpackage barry
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2005 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
?>
<script type="text/javascript">
<!--
	var ol_textcolor="#123456";
	var ol_capcolor="#ffffff";
	var ol_bgcolor="#123456";
	var ol_fgcolor="#efefef";

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