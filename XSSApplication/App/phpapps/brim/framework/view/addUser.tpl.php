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
 */
 ?>
<h2><?php echo $dictionary['preferences'] ?></h2>
<form method="POST" action="AdminController.php">
<table border="0" width="100%">
<tr>
<td width="400">
	<table>
		<tr>
			<td><?php echo $dictionary['language'] ?>:</td>
			<td>
				<select name="language">
				<?php
					foreach ($languages as $language)
					{
						if ($language[2])
						{
							echo ('<option value="'.$language[0].'"');
							if ($preferences->language == $language)
							{
								echo (' selected="selected"');
							}
							echo ('>'.$language[1].'</option>');
						}
					}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td><?php echo $dictionary['theme'] ?>:</td>
			<td>
				<select name="template">
				<?php
					sort ($templates);
					foreach ($templates as $template)
					{
						echo ('<option value="'.$template.'"');
						if ($preferences->template == $template)
						{
							echo (' selected="selected"');
						}
						echo ('>'.$template.'</option>');
					}
				?>
				</select>
			</td>
		</tr>
	</table>
</td>
</tr>
</table>
<h2><?php echo $dictionary['user'] ?></h2>
<table cellspacing="2" cellpadding="2">
<tr>
	<td><?php echo $dictionary['loginName'] ?>:
	</td>
	<td><input type="text" name="loginName" />
	</td>
</tr>
<?php

include "framework/configuration/realmConfiguration.php";
if ($realm == "database") 
{

?>
<tr>
	<td><?php echo $dictionary['password'] ?>:
	</td>
	<td><input type="password" name="password" />
	</td>
</tr>
<tr>
	<td><?php echo $dictionary['confirm'] ?>:
	</td>
	<td><input type="password" name="password2" />
	</td>
</tr>
<?php

}

?>
<tr>
	<td><?php echo $dictionary['name'] ?>:
	</td>
	<td><input type="text" name="name"
		value="<?php echo $userSettings->name ?>" />
	</td>
</tr>
<tr>
	<td><?php echo $dictionary['email'] ?>:
	</td>
	<td><input type="text" name="email"
		value="<?php echo $userSettings->email ?>" />
	</td>
</tr>
<tr>
	<td valign="top"><?php echo $dictionary['description'] ?>:
	</td>
	<td><textarea row="5" cols="60" name="description"
		><?php echo $userSettings->description ?></textarea>
	</td>
</tr>
</table>
<br />
<input type="hidden" value="addUser" name="action">
<input type="submit" value="<?php echo $dictionary['add'] ?>">
</form>
