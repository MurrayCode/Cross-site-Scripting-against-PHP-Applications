<?php

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage view
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 *
 * The renderObjects come in as array. Names as key, the value is
 * either <code>true</code> or <code>false</code>. The owner is also
 * added although this is something not to rely on.
 *
 * Example:
 * <pre>
 * array (3) {
 * 		["tasks"]=>
 *		string(4) "true"
 *		["webtools"]=>
 *		string(4) "true"
 * 		["owner"]=>
 *		string(5) "admin"
 * }
 * </pre>
 */
 ?>
<script type="text/javascript">
	function modifyPlugin (name, activate)
	{
			if (activate)
			{
				$.ajax ({
					type:"GET",
                	url: "index.php?plugin=framework&ajax=true&function=activatePlugin&pluginName="+name+"&PHPSESSID=<?php echo session_id (); ?>",
					success: function (msg)
					{
						activatedPlugins (msg);
					}
				});
			}
			else
			{
				$.ajax ({
					type:"GET",
                	url: "index.php?plugin=framework&ajax=true&function=deactivatePlugin&pluginName="+name+"&PHPSESSID=<?php echo session_id (); ?>",
					success: function (msg)
					{
						activatedPlugins (msg);
					}
				});
				deactivatePlugin (name);
			}
			return false;
	}

	function activatePlugin (name)
	{
		if (document.getElementById ('menu'+name))
		{
			document.getElementById ('menu'+name).style.display='';
		}
		else
		{
			window.location.reload();
		}
	}

	function activatedPlugins (plugins)
	{
		var combination = new Array ();
		var status = new Array ();
		status = plugins.split(',');
		for (i=0; i<status.length; i++)
		{
			combination = status[i].split ('=');
			if (combination[0] != '' && combination[1] != '' &&
				combination[1] != 'false')
			{
				activatePlugin (combination[0]);
			}
			else
			{
				deactivatePlugin (combination[0]);
			}
		}
	}

	function deactivatePlugin (pluginName)
	{
		if (document.getElementById ('menu'+pluginName))
		{
			document.getElementById ('menu'+pluginName).style.display='none';
		}
	}
</script>
<h2><?php echo $dictionary['plugins'] ?></h2>
<table border="0" width="100%">
<tr>
	<td width="400" valign="top">
		<table border="0">
			<?php
				foreach ($plugins as $thePlugin)
				{
					$plugin=$thePlugin['name'];
			?>
			<tr>
				<td>
					<b><?php echo $dictionary[$plugin] ?></b>
				</td>
				<td>
					&nbsp;
				</td>
				<td>
					<form action="#"
						onSubmit="return modifyPlugin ('<?php echo $plugin; ?>');">
					<input type="hidden"
						name="name"
						id="<?php echo $plugin ?>"
						value="<?php echo $plugin ?>" />
					<?php echo $dictionary['activate']  ?>:
					<input type="radio" class="radio"
						id="<?php echo $plugin ?>Active"
						onClick="javascript:modifyPlugin ('<?php echo $plugin ?>',true);"
					<?php
						if (isset ($renderObjects[$plugin])
							&& $renderObjects[$plugin] == 'true')
						{
					?>
						checked="checked"
					<?php } ?>
						name="value"
						value="true" />
					<?php echo $dictionary['deactivate'] ?>:
					<input type="radio" class="radio"
						id="<?php echo $plugin ?>NonActive"
						onClick="javascript:modifyPlugin ('<?php echo $plugin ?>',false);"
					<?php
						if (isset ($renderObjects[$plugin])
							&& $renderObjects[$plugin] == 'false')
						{
					?>
						checked="checked"
					<?php } ?>
						name="value"
						value="false" />
				</td>
				<td align="right">
					<input type="hidden" name="loginName"
						value="<?php echo $_SESSION['brimUsername'] ?>" />
					<input type="hidden"
						value="modifyPluginSetting" name="action" />
					<input type="submit"
						value="<?php echo $dictionary['modify'] ?>" />
				</td>
				</form>
			</tr>
			<?php } ?>
			</table>
		</td>
	</tr>
</table>
