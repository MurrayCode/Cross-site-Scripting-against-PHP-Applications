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
 * @subpackage sidebar
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
?>
<!-- FILE: showBookmarks.tpl.php -->
<?php
	include ('templates/sidebar/javascript.inc');
?>

<table width="100%">
<tr>
	<td>
		<img src="templates/sidebar/pics/brim_logo.gif" />
	</td>
</tr>
<tr>
	<td>
		<?php
			// an actionBlock is 'view' or 'action'
			foreach ($renderActions as $actionBlock)
			{
				foreach ($actionBlock as $actionBlockContents)
				{
					if (isset ($actionBlockContents) &&
						is_array ($actionBlockContents))
					{
						foreach ($actionBlockContents as $renderAction)
						{
							$actionRef = $renderAction['href'];
							$actionName = $renderAction ['name'];
							$actionImg = $renderAction ['img'];
							if ($actionName == $dictionary['add'])
							{
		?>
					<a target="_self"
					href="<?php echo $actionRef; ?>"
					onmouseover="return overlib('<?php echo $actionName; ?>', CAPTION, '');"
						onmouseout="return nd();"><?php echo $actionImg; ?></a>
		<?php
						}
						else if ($actionName == $dictionary['home'])
						{
		?>
					<a target="_content"
					href="<?php echo $actionRef; ?>"
					onmouseover="return overlib('<?php echo $actionName; ?>', CAPTION, '');"
						onmouseout="return nd();"><?php echo $actionImg; ?></a>
		<?php
						}
						else if ($actionName == $dictionary['search'])
						{
							//
							// We might add a small search
							// box in the sidebar in the futre
							//
		?>
					<a target="_content"
					href="<?php echo $actionRef; ?>"
					onmouseover="return overlib('<?php echo $actionName; ?>', CAPTION, '');"
					onmouseout="return nd();"><?php echo $actionImg; ?></a>
		<?php
						}
						else
						{
			?>
					<a  target="_self"
					href="<?php echo $actionRef; ?>"
					onmouseover="return overlib('<?php echo $actionName; ?>', CAPTION, '');"
					onmouseout="return nd();"><?php echo $actionImg; ?></a>

		<?php
						}
					}
					}
				}
			}
		?>
	</td>
</tr>

<tr>
		<td>
<?php
				include ('templates/sidebar/icons.inc');
				$configuration = array ();
				$configuration['icons']=$icons;
				$configuration['callback']='BookmarkSidebarController.php';
				if (isset ($_SESSION['bookmarkOverlib']))
				{
					$configuration ['overlib'] =$_SESSION['bookmarkOverlib'];
				}
				else
				{
					$configuration['overlib'] = true;
				}
				include_once "framework/view/Tree.php";
				include_once "templates/sidebar/BookmarkExplorerTreeDelegate.php";
				$delegate = new BookmarkExplorerTreeDelegate
					($configuration);
					//($icons, "BookmarkSidebarController.php", $dictionary);
				$tree = new Tree ($delegate, $configuration);

				if (isset ($_SESSION['bookmarkExpand']))
				{
					$tree -> setExpanded ($_SESSION['bookmarkExpand']);
				}
				echo ($tree -> toHtml ($parent, $renderObjects));
?>
		</td>
</tr>
</table>