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
 
var sessionId = null;
function changeDueDateFor (objectId, itemId, phpSessionId)
{
	sessionId = phpSessionId;
	theObject = document.getElementById (objectId);
	displayDatePicker("dueDateFor"+itemId, theObject, 'ymd', '-');
}

function changeStartDateFor (objectId, itemId, phpSessionId)
{
	sessionId = phpSessionId;
	theObject = document.getElementById (objectId);
	displayDatePicker("startDateFor"+itemId, theObject, 'ymd', '-');
}

function datePickerClosed(dateField)
{
	if (dateField.name.substring (0, 'dueDateFor'.length) == 'dueDateFor')
	{
		newDueDateFor (dateField.name.substring ('dueDateFor'.length), dateField.value, sessionId);
	}
	else if (dateField.name.substring (0, 'startDateFor'.length) == 'startDateFor')
	{
		newStartDateFor (dateField.name.substring ('startDateFor'.length), dateField.value, sessionId);
	}
}

function newDueDateFor (itemId, newDate, phpSessionId)
{
	var theData = "plugin=tasks&ajax=true";
	theData += "&function=newDueDate";
	theData += "&amp;itemId="+itemId;
	theData += "&dueDate="+newDate;
	theData += "&PHPSESSID="+phpSessionId;
	//
	// Call the backend
	//
        $.ajax ({
                type:"POST",
                url:"index.php",
                data:theData
        });
	document.getElementById ('dueDateTextFor_'+itemId).innerHTML = newDate;
}

function newStartDateFor (itemId, newDate, phpSessionId)
{
	var theData = "plugin=tasks&ajax=true";
	theData += "&function=newStartDate";
	theData += "&amp;itemId="+itemId;
	theData += "&startDate="+newDate;
	theData += "&PHPSESSID="+phpSessionId;
	//
	// Call the backend
	//
        $.ajax ({
                type:"POST",
                url:"index.php",
                data:theData
        });
	document.getElementById ('startDateTextFor_'+itemId).innerHTML = newDate;
}
