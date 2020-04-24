<?php

/**
 * The template file that draws the layout to add events
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.calendar
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
if ($showTabs)
{
	echo '<div id="eventDetail">';
}
if (($viewAction == 'add') || ($viewAction == 'modify'))
{ ?>
<form method="POST" action="index.php">
	<input type="hidden" name="plugin" value="calendar" />
	<input type="hidden" name="selectingDate" id="selectingDate" />
	<input type="hidden" id="name" name="name"
	<?php
		if (isset ($renderObjects) && (isset ($renderObjects->name)))
		{
			echo 'value="'.$renderObjects->name.'" ';
		}
	?>
	/>
<?php } ?>

<table>
<?php
	echo standardTableRowInput ('location',
		$dictionary, $viewAction, $renderObjects);
	echo standardTableRowTextarea ('description',
		$dictionary, $viewAction, $renderObjects);
?>
	<!--
		Start time option
	-->
	<tr>
		<td>
			<?php echo $dictionary['start_date']; ?>:
		</td>
		<td>
			<input type="checkbox" name="toggleTheStartTime" id="toggleTheStartTime"
			<?php if (!$useStartTime) { ?>
				checked="checked" 
			<?php } ?>
				onClick="javascript:toggleStartTime();" />
			&nbsp;<?php echo $dictionary['dontUseStartTime']; ?>
		</td>
	</tr>
	<!--
		The start day dropdown boxes
	-->
	<tr>
		<td>
			&nbsp;
		</td>
		<td>
			<select name="start_month" id="start_month">
				<?php echo monthOptionBox ($dictionary, $startDateMonth); ?>
			</select>
			<select name="start_day" id="start_day">
				<?php echo dayOptionBox ($dictionary, $startDateDay); ?>
			</select>
			<select name="start_year" id="start_year">
				<?php echo yearOptionBox ($startDateYear, 10, 10); ?>
			</select>
			<input type="hidden" name="startDate" id="startDate" />
			<input type="button"  value="<?php echo $dictionary['select'] ?>"
				onclick="javascript:selectDateValue ('startDate'); javascript:displayDatePicker('startDate', this);" />
			<!--
				Start time (visibility controlled by checkbox)
			-->
			<div id="start_time_div" 
				<?php if (!$useStartTime) { echo(' style="display:none" '); } ?>
			>
				<input type="text" size="2" name="start_time_hours" id="start_time_hours"
					value="<?php echo $startHours; ?>" />
				&nbsp;:&nbsp;
				<input type="text" size="2" name="start_time_minutes" id="start_time_minutes"
					value="<?php echo $startMinutes; ?>" />
				&nbsp;
				<?php echo $dictionary['start_time']; ?>
			</div>
			<div id="duration_div" 
				<?php if (!$useDuration) { echo (' style="display:none" '); } ?>
			>
				<input type="text" size="2" name="durationHours" id="durationHours"
					value="<?php echo $durationHours; ?>"
		   		/>
				&nbsp;:&nbsp;
				<input type="text" size="2" name="durationMinutes" id="durationMinutes"
					value="<?php echo $durationMinutes; ?>"
				/>
				&nbsp;<?php echo $dictionary['duration'] ?>
			</div>
		</td>
	</tr>
	<!--
		End date selection
	-->
	<tr>
		<td>
			<?php echo $dictionary['end_date']; ?>:
		</td>
		<td>
			<input type="checkBox" name="toggleTheEndDate" id="toggleTheEndDate"
				<?php if (!$useEndDate) { echo ' checked="checked" '; } ?>
				onclick="javascript:toggleEndDate();"
			>
			&nbsp;<?php echo $dictionary['dontUseEndDate'] ?>
		</td>
	</tr>
	<!--
		End indication date
	-->
	<tr>
		<td>
			&nbsp;
		</td>
		<td>
			<div id="end_time_div" style="display:
				<?php if (!$useEndDate) { echo 'none'; } ?>
			">
				<input type="hidden" name="useEndDate" id="useEndDate"
				<?php
					if ($useEndDate) { echo ' value="true" />'; }
					else { echo ' value="false" />'; }
				?>
				<select name="end_month" id="end_month">
					<?php echo monthOptionBox ($dictionary, $endDateMonth); ?>
				</select>
				<select name="end_day" id="end_day">
					<?php echo dayOptionBox ($dictionary, $endDateDay); ?>
				</select>
				<select name="end_year" id="end_year">
					<?php echo yearOptionBox ($endDateYear, 10, 10); ?>
				</select>
				<input type="hidden" name="endDate" />
				<input type="button"  value="<?php echo $dictionary['select'] ?>"
					onclick="javascript:selectDateValue ('endDate'); javascript:displayDatePicker('endDate', this);" />
				<br />
				<input type="text" size="2" name="end_time_hours" id="end_time_hours"
					value="<?php echo $endHours; ?>" />
				&nbsp;:&nbsp;
				<input type="text" size="2" name="end_time_minutes" id="end_time_minutes"
					value="<?php echo $endMinutes; ?>" />
				&nbsp;<?php echo $dictionary['end_time']; ?>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $dictionary['priority'] ?>:
		</td>
		<td>
			<select name="priority" id="priority">
			<?php
				$this->plugin ('options', array (1,2,3,4,5), $item->priority);
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $dictionary['colour'] ?>:
		</td>
		<td>
			<input type="text" name="eventColour" id="eventColour" value="<?php
				if (isset ($renderObjects->eventColour)) { echo $renderObjects->eventColour; } ?>">
			<input type="button"  value="<?php echo $dictionary['select'] ?>"
				onclick="javascript:overlib(overlibColourPicker('eventColour'), STICKY);"
				onmouseout="nd();return false;"
			/>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $dictionary['recurrence'] ?>:
		</td>
		<td>
			<input type="checkbox" name="recurrenceCheckbox" id="recurrenceCheckbox"
			<?php if ($useRecurrence) { ?>
				checked="checked"
			<?php } ?>
			onClick="javascript:toggleRecurrence();" />
			&nbsp;<?php echo $dictionary['enableRecurring'] ?>
		</td>
	</tr>
</table>

<?php if ($useRecurrence) { ?>
<div id="recurrence">
<?php } else { ?>
<div id="recurrence" style="display:none">
<?php } ?>

<table>
	<tr>
		<td>
			&nbsp;
		</td>
		<td>
			<div id="recurring_range_div">
				<?php echo $dictionary['recurrenceRange'] ?>:<br />
				<input type="radio" value="noRecurrenceEnding" class="radio"
					<?php if (!$useRecurringEndDate) { ?>
						checked="checked"
					<?php } ?>
				name="recurringEnding">
				&nbsp;<?php echo $dictionary['noEndingDate'] ?>
				<input type="radio" value="recurringEnding" class="radio"
					<?php if ($useRecurringEndDate) { ?>
						checked="checked"
					<?php } ?>
				name="recurringEnding">
				&nbsp;<?php echo $dictionary['endBy'] ?>:
				<select name="recurringEndMonth" id="recurringEndMonth">
					<?php echo monthOptionBox ($dictionary,
						$dateUtils->getMonthFromDate ($recurringEndDate));
					?>
				</select>
				<select name="recurringEndDay" id="recurringEndDay">
					<?php echo dayOptionBox ($dictionary,
						$dateUtils->getDayInMonthFromDate ($recurringEndDate));
					?>
				</select>
				<select name="recurringEndYear" id="recurringEndYear">
					<?php echo yearOptionBox ($dateUtils->getYearFromDate
							($recurringEndDate), 50, 50);
					?>
				</select>
				<input type="hidden" name="recurringEndDate" />
				<input type="button"  value="<?php echo $dictionary['select'] ?>"
					onclick="javascript:selectDateValue ('recurringEndDate'); javascript:displayDatePicker('recurringEndDate', this);" />
			</div>
		</td>
	</tr>
	<tr>
		<td>
			&nbsp;
		</td>
		<td>
			<div id="recurring_div">
			<?php
				$repeatType = array ('none', 'daily', 'weekly', 'monthly', 'yearly');
				echo $dictionary['repeat_type'].':&nbsp;';
				echo '<select name="frequency" id="frequency">';
				foreach ($repeatType as $type)
				{
					echo '<option value="repeat_type_'.$type.'" ';
					if (isset ($renderObjects) &&
						$renderObjects->frequency == 'repeat_type_'.$type)
					{
						echo 'selected="selected" ';
					}
					echo '>';
					echo $dictionary['repeat_type_'.$type];
					echo '</option>';
				}
				?>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			&nbsp;
		</td>
		<td>
			<div id="weekly_selection_div">
				<?php echo $dictionary['repeat_day_weekly']; ?>&nbsp;<br />
				<?php
					for ($i=0; $i<7; $i++)
					{
						echo ($dictionary['day'.$i.'short'].':
							<input type="checkbox"
							name="repeat_day_weekly_'.$i.'" ');
						if (isset ($renderObjects->byWhatValue))
						{
							if ($renderObjects->byWhatValue{$i} == 1)
							{
								echo ('checked="checked" ');
							}
						}
						echo ('>&nbsp;');
					}
				?>
			</div>
		</td>
	</tr>
</table>
</div> <!-- recurrence -->


<?php
    if ($viewAction == 'modify' && $isItemOwner)
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
		if ($showTabs)
		{
	?>

		<input type="submit"
			id="addAndContinue"
			name="addAndContinue"
			class="button"
			onclick="javascript:document.getElementById('action').value='addItemAndContinue';document.forms[0].submit()"
			value="<?php echo $dictionary['addAndContinue'] ?>"
		/>
	<?php } ?>
<?php
    }
    if ($viewAction == 'add' || $viewAction == 'modify')
	{
		echo '</form>';
	}

    if ($viewAction == 'modify' && $isItemOwner)
    {
        echo '<form method="POST" action="index.php" ';
        echo 'onsubmit="return confirmDelete()">';
        echo '<input type="hidden" name="plugin" value="calendar" />';
        echo deleteButtonAndText ( $dictionary, $renderObjects);
        echo '</form>';
    }
    echo '<form method="POST" action="index.php">';
    echo '<input type="hidden" name="plugin" value="calendar" />';
    echo cancelButtonAndText ($dictionary, $parentId);
    echo '</form>';
    if ($viewAction == 'add' || ($viewAction == 'modify' && $isItemOwner))
    {
        echo spellButtonAndText ($dictionary);
    }

?>
<?php if (($viewAction == 'add') || ($viewAction == 'modify')) { ?>
</form>
<?php } ?>
<?php if ($showTabs) { ?>

</div> <!-- tab -->
<?php } ?>
