<?php

include 'framework/view/globalFunctions.php';

/**
 * The template file that draws the layout to add a tasks.
 *
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

<script type="text/javascript">
<!--
	/**
	 * The hidden input field (selectingDate) is used to keep track of which field
	 * we are actually selecting with the datePicker tool. A selection
	 * on the value of this field is done in the datePickerClosed
	 * function which updates the appropriate date fields
	 */
	function selectDateValue (value)
	{
		document.forms[0].selectingDate.value = value;
	}

	/**
	 * The datePicker has been closed (and a date has been selected)
	 * so update the appropriate date fields based on the value in the
	 * hidden input field (selectingDate)
	 */
	function datePickerClosed (date)
	{
		var theDate = getFieldDate (date.value);
		var year = theDate.getFullYear ();
		var month = theDate.getMonth ()+1;
		var day = theDate.getDate ();
		if (day < 10)
		{
			day = '0' + day;
		}
		switch (document.forms[0].selectingDate.value)
		{
			case "startDate":
				document.forms[0].StartDate_Year.value = year;
				document.forms[0].StartDate_Month.value = month;
				document.forms[0].StartDate_Day.value = day;
				break;
			case "endDate":
				document.forms[0].DueDate_Year.value = year;
				document.forms[0].DueDate_Month.value = month;
				document.forms[0].DueDate_Day.value = day;
				break;
		}
	}

	var itemFormParameters = Array ('itemParameters');
	/**
	 * Function call when either item or folder is selected.
	 * Based on the selection, a number of form items will be displayed
	 * or hidden
	 * @see array itemFormParameters
	 */
	function itemSelected (isFolder)
	{
		var display = isFolder?'':'none';
		for (i=0; i<itemFormParameters.length; i++)
		{
		document.getElementById (itemFormParameters[i]).style.display=display;
		}
		return false;
	}
// -->
</script>
<h2>
	<?php echo $pageTitle ?>
</h2>

<?php
	//
	// Show the ancestor path. Contributed by Michael
	//
	if(isset($parameters['ancestors']))
	{
		echo ancestorPath ($parameters ['ancestors'], 'tasks', $dictionary);
	}
	if ($viewAction == 'add' || $viewAction == 'modify')
	{
		echo '<form method="POST" action="index.php">';
		echo '<input type="hidden" name="plugin" value="tasks" />';
		if (isset ($parentId))
		{
			echo '<input type="hidden" name="parentId" value="'.$parentId.'" />';
		}
	}
?>
<!--
	This hidden field is used to indicate with which date fields
	we are actually working and is triggered by a javascript call
-->
<input type="hidden" name="selectingDate" />
<table>
<?php
	if(!empty($parameters['errors']))
	{
		echo standardTableRowErrorMessages
			($dictionary, $parameters['errors'], $icons);
	}
	echo standardTableRowInput ('name',
		$dictionary, $viewAction, $renderObjects);
	//echo standardTableRowFolderItemRadios
	//	($dictionary, $viewAction, 'tasks');
	if ($viewAction == 'add')
	{
?>
	<tr>
		<td class="inputParamName">&nbsp;</td>
		<td class="inputParamValue">
		<?php echo $dictionary['folder']; ?>:&nbsp;<input
			type="radio" class="radio" name="isParent" value="1"
			onClick="javascript:itemSelected(false); "
			/>
		<br />
		<?php echo $dictionary['task'] ?>:&nbsp;<input
			type="radio" class="radio" name="isParent" value="0"
			onClick="javascript:itemSelected(true); "
			checked="checked"  />
		</td>
	</tr>
<?php
	}
	if ($viewAction == 'add' || $viewAction == 'modify')
	{
		echo standardTableRowPublicPrivateRadios
			($dictionary, $viewAction, $renderObjects);	
	}
?>
</table>
<div id="itemParameters">
<table>
<?php
	//
	// Ok, here we have a small problem.
	// The parent is given as renderObject. Howeverm when an item
	// is not correctly submitted (i.e. name is missing), the item
	// itself is passed on as renderObject
	//
	// TDB TODO BARRY FIXME
	//
	if (!($renderObjects->isParent) || $viewAction=='add')
	{
		//
		// Perc. complete
		//
		echo '<tr>';
		echo '<td>'.$dictionary['complete'].':</td>';
		switch ($viewAction)
		{
			case 'add':
				echo '<td><input type="text" name="percentComplete" size="3">';
				echo ' %</td>';
				break;
			case 'modify':
				echo '<td><input type="text" name="percentComplete" size="3" ';
				if (isset ($renderObjects->percentComplete))
				{
					echo 'value="'.$renderObjects->percentComplete.'" ';
				}
				echo '> %</td>';
				break;
			case 'show':
				echo '<td>'.$renderObjects->percentComplete.' %</td>';
				break;
		}
		echo '</tr>';
		//
		// Priority
		// TODO Perhaps make this a global function as well?
		//
		echo '<tr>';
		echo '<td>';
		echo $dictionary['priority'].':';
		echo '</td><td>';
		$options = array (
			1=>$dictionary['priority1'],
			2=>$dictionary['priority2'],
			3=>$dictionary['priority3'],
			4=>$dictionary['priority4'],
			5=>$dictionary['priority5']);
		if ($viewAction == 'add' || $viewAction == 'modify')
		{
			echo '<select name="priority">';
			echo ($this->plugin ('options', $options, $renderObjects->priority));
			echo '</select>';
		}
		else
		{
			echo $options[$renderObjects->priority];
		}
		echo '</td>';
		echo '</tr>';

		echo standardTableRowInput ('status',
			$dictionary, $viewAction, $renderObjects);
		//
		// Start indication
		//
		echo '<tr>';
		echo '<td>';
		echo $dictionary['start_date'].':';
		echo '</td>';
		echo '<td>';
		if ($viewAction == 'modify')
		{
			$startDate = $renderObjects->startDate;
			$endDate = $renderObjects->endDate;
		}
		else
		{
			$startDate = date ('Y-m-d');
			$endDate = date ('Y-m-d');
		}
		if ($viewAction == 'add' || $viewAction == 'modify')
		{
			echo '<select name="StartDate_Month">';
			echo monthOptionBox ($dictionary,
			$dateUtils->getMonthFromDate ($startDate));
			echo '</select>';
			echo '<select name="StartDate_Day">';
			echo dayOptionBox ($dictionary,
				$dateUtils->getDayInMonthFromDate ($startDate));
			echo '</select>';
			echo '<select name="StartDate_Year">';
			echo yearOptionBox ($dateUtils->getYearFromDate ($startDate), 10, 10);
			echo '</select>';
			echo '<input type="hidden" name="startDate" />';
			echo '<input type="button"  value="'.$dictionary['select'].'"  ';
			echo 'onclick="selectDateValue (\'startDate\'); displayDatePicker(\'startDate\', this);" />';
		}
		else
		{
			echo (date ('Y-m-d H:i', strtotime ($startDate)));
		}
		echo '</td>';
		echo '</tr>';
		//
		// End indication
		//
		echo '<tr>';
		echo '<td>';
		echo $dictionary['due_date'].':';
		echo '</td>';
		echo '<td>';
		if ($viewAction == 'add' || $viewAction == 'modify')
		{
			echo '<select name="DueDate_Month">';
			echo monthOptionBox ($dictionary,
			$dateUtils->getMonthFromDate ($endDate));
			echo '</select>';
			echo '<select name="DueDate_Day">';
			echo dayOptionBox ($dictionary,
			$dateUtils->getDayInMonthFromDate ($endDate));
			echo '</select>';
			echo '<select name="DueDate_Year">';
			echo yearOptionBox ($dateUtils->getYearFromDate ($endDate), 10, 10);
			echo '</select>';
			echo '<input type="hidden" name="endDate" />';
			echo '<input type="button"  value="'.$dictionary['select'].'"  ';
			echo 'onclick="selectDateValue (\'endDate\'); displayDatePicker(\'endDate\', this);" />';
		}
		else
		{
			echo (date ('Y-m-d H:i', strtotime ($endDate)));
		}
		echo '</td>';
		echo '</tr>';

		echo standardTableRowTextarea ('description',
			$dictionary, $viewAction, $renderObjects);
	}
?>
</table>
</div>
<?php
if ($viewAction == 'modify')
{
	echo modifyButtonAndText ($dictionary, $renderObjects);
}
else if ($viewAction == 'add')
{
	//echo addButtonAndText ($dictionary, $parentId);
?>
                <input type="hidden"
                name="action"
                id="action"
                        value="addItemPost"
                />
                <input type="submit"
                        class="button"
                        name="submit"
                        value="<?php echo $dictionary['add'] ?>"
                />
                <input type="submit"
                        id="addAndAddAnother"
                        name="addAndAddAnother"
                        class="button"
                        onclick="javascript:document.getElementById('action').value='addAndAddAnother';document.forms[0].submit()"
                        value="<?php echo $dictionary['addAndAddAnother'] ?>"
                />
<?php

}

if ($viewAction == 'add' || $viewAction == 'modify')
{
	echo '</form>';
}
if ($viewAction == 'modify')
{
	echo '<form method="POST" action="index.php"> ';
	echo '<input type="hidden" name="plugin" value="tasks" />';
	echo moveButtonAndText ($dictionary, $renderObjects);
	echo '</form>';

	echo '<form method="POST" action="index.php" ';
	echo 'onsubmit="return confirmDelete()">';
	echo '<input type="hidden" name="plugin" value="tasks" />';
	echo deleteButtonAndText ($dictionary, $renderObjects);
	echo '</form>';
}
	echo cancelButton ($dictionary, 'tasks', $parentId);
	if ($viewAction == 'add' || $viewAction == 'modify')
	{
		echo spellButtonAndText ($dictionary);
	}
	if ($viewAction == 'add' || 'viewAction' == 'modify')
	{
		echo focusOnField ('name');
	}
?>
