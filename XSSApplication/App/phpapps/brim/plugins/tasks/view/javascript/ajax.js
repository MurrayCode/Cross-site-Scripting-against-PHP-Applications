/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2006
 * @package org.brim-project.plugins.tasks
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */


/**
 * Javascript/Ajax callback function to increase the priority
 * for an item. The result will endup in the function 'priorityStatus'
 *
 * @param itemId integer the itemId
 * @param phpSessionId string the sessionid used for backend authentication
 * @see priorityStatus
 */
function increasePriorityFor (itemId, phpSessionId)
{
	var theData = "plugin=tasks&ajax=true";
	theData += "&function=increasePriority";
	theData += "&itemId="+itemId;
	theData += "&PHPSESSID="+phpSessionId;
	//
	// Call the backend
	//
	$.ajax ({
		type:"POST",
		url:"index.php",
		data:theData,
		success: function(msg)
		{
			priorityStatus(msg,phpSessionId);
		}
	});
}

/**
 * Javascript/Ajax callback function to decrease the priority
 * for an item. The result will endup in the function 'priorityStatus'
 *
 * @param itemId integer the itemId
 * @param phpSessionId string the sessionid used for backend authentication
 * @see priorityStatus
 */
function decreasePriorityFor (itemId, phpSessionId)
{
	var theData = "plugin=tasks&ajax=true";
	theData += "&function=decreasePriority";
	theData += "&itemId="+itemId;
	theData += "&PHPSESSID="+phpSessionId;
	//
	// Call the backend
	//
	$.ajax ({
		type:"POST",
		url:"index.php",
		data:theData,
		success: function(msg)
		{
			priorityStatus(msg,phpSessionId);
		}
	});
}


/**
 * Javascript/Ajax callback function to increase the completed percentage
 * for an item. The result will endup in the function 'completedStatus'
 *
 * @param itemId integer the itemId
 * @param phpSessionId string the sessionid used for backend authentication
 * @see completedStatus 
 */
function increaseCompletedFor (itemId, phpSessionId)
{
	var theData = "plugin=tasks&ajax=true";
	theData += "&function=increaseCompleted";
	theData += "&itemId="+itemId;
	theData += "&PHPSESSID="+phpSessionId;
	//
	// Call the backend
	//
	$.ajax ({
		type:"POST",
		url:"index.php",
		data:theData,
		success: function(msg)
		{
			completedStatus(msg,phpSessionId);
		}
	});
	
}

/**
 * Javascript/Ajax callback function to decrease the completed percentage
 * for an item. The result will endup in the function 'completedStatus'
 *
 * @param itemId integer the itemId
 * @param phpSessionId string the sessionid used for backend authentication
 * @see completedStatus 
 */
function decreaseCompletedFor (itemId, phpSessionId)
{
	var theData = "plugin=tasks&ajax=true";
	theData += "&function=decreaseCompleted";
	theData += "&itemId="+itemId;
	theData += "&PHPSESSID="+phpSessionId;
	//
	// Call the backend
	//
	$.ajax ({
		type:"POST",
		url:"index.php",
		data:theData,
		success: function(msg)
		{
			completedStatus (msg,phpSessionId);
		}
	});
}


function animateCompleteItem (itemId)
{
	$("#item_"+itemId).TransferTo({to:"completedTasks",className:"itemTransfer", duration: 750});
	$("#item_"+itemId).remove ();
	zebraItems ();
}

function completeItem (itemId, phpSessionId)
{
	executeAjax ("tasks", "completeItem", "&itemId="+itemId, phpSessionId);
	animateCompleteItem (itemId);
}

function uncompleteItem (itemId, phpSessionId)
{
	executeAjax ("tasks", "uncompleteItem", "&itemId="+itemId, phpSessionId);
	animateCompleteItem (itemId);
}
