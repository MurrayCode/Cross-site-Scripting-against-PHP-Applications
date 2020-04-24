<?php
/**
 * The template file to search for strings in all plugins
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - August 2006
 * @package org.brim-project.plugins.search
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
 ?>
<script type="text/javascript">
	<!-- // Hide from old browsers
		function selectAll ()
		{
			for (var i=0; i<document.forms[0].length; i++)
			{
				currentElement = document.forms[0].elements[i];
				if (currentElement.type == 'checkbox')
				{
					currentElement.checked = true;
				}
			}
		}

		function deselectAll ()
		{
			for (var i=0; i<document.forms[0].length; i++)
			{
				currentElement = document.forms[0].elements[i];
				if (currentElement.type == 'checkbox')
				{
					currentElement.checked = false;
				}
			}
		}

		function inverseAll ()
		{
			for (var i=0; i<document.forms[0].length; i++)
			{
				currentElement = document.forms[0].elements[i];
				if (currentElement.type == 'checkbox')
				{
					currentElement.checked =
						(currentElement.checked) ? false : true;
				}
			}
		}
	//-->
</script>

<h2><?php echo $dictionary['search'] ?></h2>

<form method="POST" action="SearchController.php">
<table>
<tr>
	<td>
		<?php echo $dictionary['search'] ?>:
	</td>
	<td>
		<input type="text" name="value" />
		<input type="hidden" name="action" value="search" />
	</td>
</tr>
<tr>
	<td>
		<?php echo $dictionary['plugins'] ?>:
	</td>
	<td>
		<input type="button" onClick="javascript:selectAll();"
			value="<?php echo $dictionary['selectAll'] ?>" />
		<input type="button" onClick="javascript:deselectAll();"
			value="<?php echo $dictionary['deselectAll'] ?>" />
	</td>
</tr>
<tr>
	<td valign="top">
		&nbsp;
	</td>
	<td>
		<?php
			foreach ($plugins as $plugin)
			{
				if (isset ($plugin['searchFields']))
				{
					echo '<input type="checkbox"
						name="search_'.$plugin['name'].'" ';
					echo '/>'.$dictionary[$plugin['name']].'<br />';
				}
			}
		?>
	</td>
</tr>
</table>
<input type="submit" value="<?php echo $dictionary['search'] ?>" />
</form>
