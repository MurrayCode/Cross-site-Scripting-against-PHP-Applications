<?php

require_once ('framework/util/BrowserUtils.php');

/**
 * The template file that draws the layout to add, delete and modify reminders
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - February 2006
 * @package org.brim-project.plugins.calendar
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
include ('templates/'.$_SESSION['brimTemplate'].'/icons.inc');
$browserUtils = new BrowserUtils();
if (!$browserUtils->browserIsExplorer())
{
	$ajaxified = true;
}
else 
{
	$ajaxified = false;
}
?>

<script type="text/javascript">
	function selectTimeSpan (value)
	{
		document.getElementById('reminderMinuteSelection').style.display='none';
		document.getElementById('reminderHourSelection').style.display='none';
		document.getElementById('reminderDaySelection').style.display='none';
		var theDiv = '';
		switch (value)
		{
			case "minutes":
				theDiv = 'reminderMinuteSelection';
				break;
			case "hours":
				theDiv = 'reminderHourSelection';
				break;
			case "days":
				theDiv = 'reminderDaySelection';
				break;
		}
		document.getElementById(theDiv).style.display='';
	}
<?php if ($ajaxified) { ?>
	function deleteReminder (reminderId)
	{
		var theData = "plugin=calendar&ajax=true";
		theData += "&function=deleteReminder";
		theData += "&reminderId="+reminderId;
		theData += "&PHPSESSID=<?php echo session_id (); ?>";
		$.ajax ({
			type:"POST",
			url:"index.php",
			data:theData,
			success:function(data)
			{
				reminderStatus (data);
			}
		});
		//return;
	}
	
	function addReminder ()
	{
		var eventId = document.getElementById ("eventId").value;
		var timespanm = document.getElementById ("timespanm").checked;
		var timespanh = document.getElementById ("timespanh").checked;
		var timespand = document.getElementById ("timespand").checked;
		var reminderTime = 0;
		var timespan='x'; // undefined
		if (timespanm)
		{
				reminderTime = document.getElementById ("reminderMinutes").value;
				timespan='m';
		}
		else if (timespanh)
		{
				reminderTime = document.getElementById ("reminderHours").value;
				timespan='h';
		}
		else if (timespand)
		{
				reminderTime = document.getElementById ("reminderDays").value;
				timespan='d';
		}
		var theData = "plugin=calendar&ajax=true";
		theData += "&function=addReminder";
		theData += "&eventId="+eventId;
		theData += "&timespan="+timespan;
		theData += "&reminderTime="+reminderTime;
		theData += "&PHPSESSID=<?php echo session_id (); ?>";
		$.ajax ({
			type:"POST",
			url:"index.php",
			data:theData,
			success:function(data)
			{
				reminderStatus (data);
			}
		});
	}
	
	function reminderStatus (data)
	{
		var resultString = '';
		var status = eval ('(' + data + ')');
		if (status == null || status.length == 0)
		{
			resultString = 'none';
		}
		else
		{
			resultString += '<table>';
			for (i=0; i<status.length; i++)
			{
				(i%2==0)?class='even':class='odd';
				resultString += "<tr class=\""+class+"\">";
				//
				// Delete button
				//
				resultString += "<td>";
				resultString += "<a href=\"javascript:deleteReminder ('";
				resultString += status [i]['itemId'];
				resultString += "');\"> ";
				resultString += '<?php echo $icons['delete'];?>';
				resultString += '</td>';
				//
				// Time indication (5 minutes, 2 hours etc)
				// seperated over 2 cells
				//
				resultString += '<td>';
				switch (status[i]['timespan'])
				{
					case "m":
						resultString += (status[i]['reminderTime'] / 60);
						resultString += '</td><td>';
						resultString += '<?php echo $dictionary['minute_s'] ?>';
						break;
					case "h":
						resultString += (status[i]['reminderTime'] / (60*60));
						resultString += '</td><td>';
						resultString += '<?php echo $dictionary['hour_s'] ?>';
						break;
					case "d":
						resultString += (status[i]['reminderTime'] / (60*60*24));
						resultString += '</td><td>';
						resultString += '<?php echo $dictionary['day_s'] ?>';
						break;
				}
				resultString += '</td>';
				//
				// two nbsps (why?)
				//
				resultString += '<td>&nbsp;&nbsp;';
				resultString += '</td>';
				//
				// To send approx at
				//
				resultString += '<td>';
				resultString += '<?php echo $dictionary['whenToSend'] ?>:&nbsp;';
				resultString += status[i]['whenToSend'];
				resultString += '</td>';
				//
				// Sent at
				//
				resultString += '<td>';
				if (status[i]['whenSent'] != null)
				{
					resultString += "<?php echo $dictionary['whenSent'] ?>";
					resultString += '&nbsp;'+status[i]['whenSent'];
				}
				else
				{
					resultString += "<?php echo $dictionary['notYetSent'] ?>";
				}
				resultString += '</td>';
				resultString += "</tr>";
			}
			resultString += "</table>";
		}
		document.getElementById ('reminders').innerHTML=resultString;
	}
<?php } ?>
</script>

<div id="reminderDetail" style="display:none">
<h2><?php echo $dictionary ['reminders'] ?></h2>

<?php if ($isItemOwner)
{
?>	

<?php if (!$ajaxified) { ?>
	<form method="POST" 
	 action="index.php"
			name="reminderForm">
<?php } ?>
				<input type="hidden" name="plugin" value="calendar" />
		<input type="hidden" name="eventId" id="eventId" 
		<?php
			if (isset ($renderObjects))
			{
				echo 'value="'.$renderObjects->itemId.'" ';
			}
		?>
		/>
		<!-- Was called date before -->
		<input type="hidden" name="eventStartTime"
			value="<?php echo $renderObjects->eventStartDate ?>" />
		<input type="hidden" name="action" value="addReminder" />
		<table>
			<tr>
				<td><?php echo $dictionary['time'] ?>:
					&nbsp;&nbsp;
					&nbsp;&nbsp;
				</td>
				<td>
					<input type="radio" name="timespan" value="m"
						id="timespanm"
						onclick="javascript:selectTimeSpan ('minutes');"
						checked="checked"
						><?php echo $dictionary['minutes']; ?></input>
					<input type="radio" name="timespan" value="h"
						id="timespanh"
						onclick="javascript:selectTimeSpan ('hours');"
						><?php echo $dictionary['hours']; ?></input>
					<input type="radio" name="timespan" value="d"
						id="timespand"
						onclick="javascript:selectTimeSpan ('days');"
						><?php echo $dictionary['days']; ?></input>:
					&nbsp;&nbsp;
				</td>
				<td>
					<div id="reminderMinuteSelection"
						style="display:block"
					>
						<select name="reminderMinutes" id="reminderMinutes">
						<?php for ($i=5; $i<60; $i=$i+5)
							{
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
						?>
						</select>
					&nbsp;&nbsp;
					</div>
					<div id="reminderHourSelection"
						style="display:none"
					>
						<select name="reminderHours" id="reminderHours">
						<?php for ($i=1; $i<24; $i++)
							{
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
						?>
						</select>
					&nbsp;&nbsp;
					</div>
					<div id="reminderDaySelection"
						style="display:none"
					>
						<select name="reminderDays" id="reminderDays">
						<?php for ($i=1; $i<14; $i++)
							{
								echo '<option value="'.$i.'">'.$i.'</option>';
							}
						?>
						</select>
					</div>
					&nbsp;&nbsp;
					&nbsp;&nbsp;
				</td>
				<td>
					<input type="submit" name="submit"
						<?php if ($ajaxified) { ?> onclick = "javascript:addReminder();" <?php } ?>
						value="<?php echo $dictionary['add'] ?>"
					/>
				</td>
			</tr>
		</table>
<?php if (!$ajaxified) { ?>
	</form>
<?php } ?>
<?php
}
?>

<div id="reminders">
<?php
	if (isset ($reminders) && count ($reminders) > 0)
	{
		$i=0;
		echo '<table>';
		foreach ($reminders as $reminder)
		{
			($i++%2==0)?$class='even':$class='odd';
			echo '<tr class="'.$class.'">';
?>

<?php if ($ajaxified) { ?>
			<td>
				<a href="javascript:deleteReminder 
						('<?php echo $reminder->itemId ?>');"
				>
				<?php	echo $icons['delete'];?>
				</a>
			</td>
<?php } else { ?>
			<td>
				<a href="index.php?plugin=calendar&amp;action=deleteReminder&amp;itemId=<?php
					echo $reminder->itemId.'" ';
					echo 'onclick="javascript:return confirm (\'';
					echo $dictionary['confirm_delete'];
					echo '\');">';
					echo $icons['delete'];
					?>
				</a>
			</td>
<?php } ?>
			<td>
			<?php
				switch ($reminder->timespan)
				{
					case "m":
						echo ($reminder->reminderTime / 60);
						echo '</td><td>';
						echo $dictionary['minute_s'];
						break;
					case "h":
						echo ($reminder->reminderTime / (60*60));
						echo '</td><td>';
						echo $dictionary['hour_s'];
						break;
					case "d":
						echo ($reminder->reminderTime / (60*60*24));
						echo '</td><td>';
						echo $dictionary['day_s'];
						break;
				}
			?>
			</td>
			<td>
				&nbsp;&nbsp;
			</td>
			<td>
			<?php
				$eventTime = mktime (
					date ('H', $renderObjects->eventStartDate),
					date ('i', $renderObjects->eventStartDate),
					0,
					date ('n', $renderObjects->eventStartDate),
					date ('d', $renderObjects->eventStartDate),
					date ('y', $renderObjects->eventStartDate));
				$notificationTime = $eventTime - $reminder->reminderTime;
				echo $dictionary['whenToSend'].':&nbsp;';
				echo (date ('Y-m-d H:i:s', $notificationTime));

			?>
			</td>
			<td>
			<?php
				if (isset ($reminder->whenSent))
				{
					echo $dictionary['whenSent'].':&nbsp;'.$reminder->whenSent;
				}
				else
				{
					echo $dictionary['notYetSent'];
				}
				?>

			</td>
<?php
			echo '</tr>';
		}
		echo '</table>';
	}
	else 
	{
		echo '<p>'.$dictionary ['none'].'</p>';
	}
?>
</div>
</div>
