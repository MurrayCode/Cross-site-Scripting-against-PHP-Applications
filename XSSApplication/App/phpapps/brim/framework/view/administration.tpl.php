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

require_once ('framework/realm/Realm.php');

/**
 * Provide the link for the sort icons based in the field
 *
 * @param string sortField the field on which we would like to sort
 * @return string the complete url for the sort button
 */
function sortIcons ($sortField)
{
	$resultString = '&nbsp;';
	//
	// ASCending mode
	//
	$resultString .= '<a href="AdminController.php';
	$resultString .= '?action=sort&amp;order==ASC&amp;field=='.$sortField;
	//
	// If we are sorting on this specific field, draw the icon in
	// shaded mode
	//
	if (isset ($_GET['order']) &&
		$_GET['order'] == 'ASC' &&
		$_GET['field'] == $sortField)
	{
		$resultString .= '">'.$icons['up_arrow_shaded'].'</a>';
	}
	else
	{
		$resultString .= '">'.$icons['up_arrow'].'</a>';
	}
	//
	// DESCending mode
	//
	$resultString .= '<a href="AdminController.php';
	$resultString .= '?action=sort&amp;order==DESC&amp;field=='.$sortField;
	//
	// If we are sorting on this specific field,
	// draw the icon in shaded mode
	//
	if (isset ($_GET['order']) &&
		$_GET['order'] == 'DESC'
		&& $_GET['field'] == $sortField)
	{
		$resultString .= '">'.$icons['down_arrow_shaded'].'</a>';
	}
	else
	{
		$resultString .= '">'.$icons['down_arrow'].'</a>';
	}
	return $resultString;
}
?>

<table width="100%" border="0">
	<?php
		$count = 0;
		foreach ($renderObjects as $user)
		{
			$count++;
			if ($count % 2 == 0)
			{
	?>
			<tr class="even">
	<?php
			}
			else
			{
	?>
			<tr class="odd">
	<?php
			}
	?>
			<!--
				The edit icon. Only show if the current user is not
				'admin' in which case we show a small 'x' indicating
				that this user cannot be deleted/edited
			-->
			<td>
				<?php
                                        $realm = Realm::getInstance();
                                        $isAdmin = $realm->isMemberOf($user->loginName,'admin');
					if (!$isAdmin)
					{
				?>
				<a
					href="AdminController.php?action=modify&amp;loginName=<?php echo $user->loginName ?>"
					><img src="framework/view/pics/edit.gif"
					alt="Edit" border="0"></a>
				<?php
					}
					else
					{
						echo ('x');
					}
				?>
			</td>
			<!--
				The edit icon. Only show if the current user is not
				'admin' in which case we show a small 'x' indicating
				that this user cannot be deleted/edited
			-->
			<td>
				<?php
					if (!$isAdmin)
					{
				?>
					<a
						href="AdminController.php?action=deleteUser&amp;loginName=<?php echo $user->loginName ?>"
						><img src="framework/view/pics/delete.gif"
						alt="Delete" border="0"></a>
				<?php
					}
					else
					{
						echo ('x');
					}
				?>
			</td>
			<!--
				Show the loginname
			-->
			<td>
				<?php echo $user->loginName ?>
			</td>
			<!--
				SHow the creation date/time
			-->
			<td>
				<?php echo ('Creation: '.$user->when_created); ?>
			</td>
			<!--
				Show the last login
			-->
			<td>
				<?php echo ('Last login: '.$user->lastLogin); ?>
			</td>
		</tr>
	<?php
		}
	?>
</table>
