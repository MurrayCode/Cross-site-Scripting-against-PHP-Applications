<?php


require_once 'framework/util/BrowserUtils.php';
$browserUtils = new BrowserUtils ();

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.tasks
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
?>

<?php
//
// Implementation of a 'special' folder: completed tasks.
// If this folder is requested, some other settings are ignored
//
$showCompletedTasksOnly 
		= (isset ($_GET['action']) && $_GET['action'] == "showCompletedOnly");
//
// Preference setting where we wan't to see completed tasks. This setting
// is ignored when showing only completed tasks
//
$hideCompletedTasks 
		= (isset ($_SESSION['taskHideCompleted']) && ($_SESSION['taskHideCompleted']==1));
?>


<script src="ext/javascript/datePicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="ext/javascript/datePicker.css" />
<?php
	$other = 'templates/'.$_SESSION['brimTemplate'].'/datePicker.css';
	if (file_exists ($other))
	{
?>
	<link rel="stylesheet" type="text/css" href="<?php echo $other ?>" />
<?php
	}
?>

<?php
//
// First loop over all objects and remove the completed IF this is requested
//
function filterCompleted (&$anObject)
{
	return ($anObject->percentComplete != 100);
}
if ($hideCompletedTasks && isset ($renderObjects) && !$showCompletedTasksOnly)
{
	$renderObjects =
		array_values (array_filter ($renderObjects, 'filterCompleted'));
}
?>

<script src="plugins/tasks/view/javascript/datePicker.js" type="text/javascript"></script>
<script type="text/javascript">
//
// fadedout for the overlib library
//
var ol_fadetime=5000;
/**
 * The result of the ajax-call to in/decrease the percentage completed
 * ends up here. This function takes the result and draws a new status 
 * bar and replaces the old on.
 *
 * @param data string JSON-encoded data
 * @see increaseCompletedFor
 * @see decreaseCompletedFor
 */
function priorityStatus (data, phpSessionId)
{
	var result = '';
	var status = eval ('('+data+')');
	var completedPercentage = status ['completed'];
	if (status == null || status.length == 0)
	{
		result = 'error';
	}
	else
	{
		if (status['priority'] == 1)
		{
			result += '<?php echo $dictionary['priority1'] ?>';
		}
		else if (status['priority'] == 2)
		{
			result += '<?php echo $dictionary['priority2'] ?>';
		}
		else if (status['priority'] == 3)
		{
			result += '<?php echo $dictionary['priority3'] ?>';
		}
		else if (status['priority'] == 4)
		{
			result += '<?php echo $dictionary['priority4'] ?>';
		}
		else if (status['priority'] == 5)
		{
			result += '<?php echo $dictionary['priority5'] ?>';
		}
		else
		{
			result += 'Error';
		}
		result += '&nbsp;';
		if (status['priority'] < 5)
		{
			result += '<a href="javascript:decreasePriorityFor (\''+status['itemId']+'\',\''+phpSessionId+'\')">';
			result += '<img border="0" src="framework/view/pics/tree/shaded_minus_2.gif"><\/a>';
		}
		else
		{
			result += '<img border="0" src="framework/view/pics/tree/shaded_dot_2.gif">';
		}
		result += '&nbsp;';
		if (status['priority'] > 1)
		{
			result += '<a href="javascript:increasePriorityFor (\''+status['itemId']+'\',\''+phpSessionId+'\')">';
			result += '<img border="0" src="framework/view/pics/tree/shaded_plus_2.gif"><\/a>';
		}
		else
		{
			result += '<img border="0" src="framework/view/pics/tree/shaded_dot_2.gif">';
		}
		result += '&nbsp;';
	}
	document.getElementById ("priorityFor"+status['itemId']).innerHTML = result;
}

/**
 * The result of the ajax-call to in/decrease the percentage completed
 * ends up here. This function takes the result and draws a new status 
 * bar and replaces the old on.
 *
 * @param data string JSON-encoded data
 * @see increaseCompletedFor
 * @see decreaseCompletedFor
 */
function completedStatus (data, phpSessionId)
{
	var result = '';
	var status = eval ('('+data+')');
	var completedPercentage = status ['completed'];
	if (status == null || status.length == 0)
	{
		result = 'error';
	}
	else if (status['error'] != null)
	{
		alert (status['error']);
	}
	else
	{
		result  = '<img src="framework/view/pics/completed.gif" alt="Completed" ';
		result += 'height="16" ';
		result += 'width="'+status['percentComplete']+'">';
		result += '<img src="framework/view/pics/uncompleted.gif" alt="Uncompleted" ';
		result += 'height="16" ';
		result += 'width="'+(100-status['percentComplete'])+'">&nbsp;';
		if (status['percentComplete'] == 0)
		{
			result += '&nbsp;';
		}
		result += status['percentComplete']+'%';
		result += '&nbsp;';
		if (status['percentComplete'] > 0)
		{
			result += '<a href="javascript:decreaseCompletedFor (\''+status['itemId']+'\',\''+phpSessionId+'\')">';
			result += '<img border="0" src="framework/view/pics/tree/shaded_minus_2.gif"><\/a>';
		}
		else
		{
			result += '<img border="0" src="framework/view/pics/tree/shaded_dot_2.gif">'			
		}
		result += '&nbsp;';
		if (status['percentComplete'] < 100)
		{
			result += '<a href="javascript:increaseCompletedFor (\''+status['itemId']+'\',\''+phpSessionId+'\')">';
			result += '<img border="0" src="framework/view/pics/tree/shaded_plus_2.gif"><\/a>';
		}
		else
		{
			result += '<img border="0" src="framework/view/pics/tree/shaded_dot_2.gif">'			
		}
	}
	document.getElementById ("percentCompletedFor"+status['itemId']).innerHTML = result;
	<?php if ($_SESSION['taskHideCompleted'] == 1) { ?>
		if (status['percentComplete'] == 100)
		{
			if (typeof animateCompleteItem != "undefined")
			{
				animateCompleteItem (status['itemId']);
			}
		}
	<?php } 
		if ($showCompletedTasksOnly)
		{
	?>
			if (status['percentComplete'] < 100)
			{
				if (typeof animateCompleteItem != "undefined")
				{
					animateCompleteItem (status['itemId']);
				}
			}
	<?php
		}
	?>
}


</script>
<script src="plugins/tasks/view/javascript/ajax.js" type="text/javascript"></script>
<?php
//die (print_r ($parameters['ancestors']));

	// Show the ancestor path. Contributed by Michael
	if(isset($parameters['ancestors']) && $parentId != 0)
	{
		echo ('<!-- Ancestors -->');
		echo ('<table><tr>');

		// The root link
		echo ('<td>
			<div id="item_0" class="dndFolder">
			<a href="?plugin=tasks&amp;parentId=0" class="ancestor">'.
			$dictionary['root'].'</a>
			</div>
			</td>');

		// all ancestors other than root
		foreach($parameters['ancestors'] as $ancestor)
		{
			echo ('<td>');
			$lastItem = ($ancestor->itemId != $parentId);
			if ($lastItem == 1)
			{
				echo ('<div id="item_'.$ancestor->itemId.'" class="dndFolder">');
			}
			echo ('&nbsp;/&nbsp;<a href="?plugin=tasks&amp;parentId='.$ancestor->itemId.
				'" class="ancestor">');
			echo ($ancestor->name);
			echo ('</a>');
			if (!$lastItem)
			{
				// echo ('</div>');
			}
			echo ('</td>');
		}
		echo ('</tr></table>');
	}
	include ('templates/'.$_SESSION['brimTemplate'].'/icons.inc');
	$configuration = array ();

	// Build up a proper configuration for the tree display.
	$configuration['icons']=$icons;
	$configuration['dictionary']=$dictionary;
	$configuration['callback']='index.php?plugin=tasks';
	$configuration['trashCount']=$trashCount;

	// Check for optional overlib
	if (isset ($_SESSION['taskOverlib']))
	{
		$configuration ['overlib'] =$_SESSION['taskOverlib'];
	}
	else
	{
		$configuration ['overlib'] = true;
	}

	if (!($browserUtils->browserIsPDA ()) && isset ($_SESSION['brimEnableAjax']) && ($_SESSION['brimEnableAjax'] == 1))
	{
		include_once "framework/view/AjaxLineBasedTree.php";
		include_once "plugins/tasks/view/AjaxLineBasedTreeDelegate.php";
		$delegate = new AjaxLineBasedTreeDelegate ($configuration);
		$tree = new AjaxLineBasedTree ($delegate, $configuration);
	}
	else
	{
		include_once "framework/view/LineBasedTree.php";
		include_once "plugins/tasks/view/TaskLineBasedTreeDelegate.php";

		$delegate = new TaskLineBasedTreeDelegate ($configuration);
		$tree = new LineBasedTree ($delegate, $configuration);
	}
	// Now actually show the layout
	echo ($tree -> toHtml ($parent, $renderObjects));
?>
