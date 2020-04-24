<?php
/**
 * The template file that draws the layout to modify calendar
 * preferences
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
?>
<h2><?php echo $dictionary['modifyCalendarPreferences'] ?></h2>

<table>
<form method="POST" action="index.php">
<input type="hidden" name="plugin" value="calendar" />
<tr>
	<td>
		<?php echo $dictionary['firstDayOfWeek'] ?>
	</td>
	<td>
		<?php
			$options = array (0=>$dictionary['day0'],
				1=>$dictionary['day1']);
			$this->plugin ('radios', 'value', $options,
				$renderObjects['calendarStartOfWeek'],
				null, '&nbsp;', 'class="radio"');
		?>
	</td>
	<td>
		<input type="hidden" name="name" value="calendarStartOfWeek" />
		<input type="hidden" name="action" value="modifyPreferencesPost" />
		<input type="submit" value="<?php echo $dictionary['modify'] ?>"  />
	</td>
</tr>
</form>


<form method="POST" action="index.php">
<input type="hidden" name="plugin" value="calendar" />
<tr>
	<td>
		<?php echo $dictionary['javascript_popups']; ?>
	</td>
	<td>
		<?php
			$options = array ($dictionary['no'],
				$dictionary['yes']);
			$this->plugin ('radios', 'value', $options,
				$renderObjects['calendarOverlib'],
				null, '&nbsp;', 'class="radio"');
		?>
	</td>
	<td>
		<input type="hidden" name="name" value="calendarOverlib" />
		<input type="hidden" name="action" value="modifyPreferencesPost" />
		<input type="submit" value="<?php echo $dictionary['modify'] ?>"  />
	</td>
</tr>
</form>


<form method="POST" action="index.php">
<input type="hidden" name="plugin" value="calendar" />
<tr>
	<td>
		<?php echo $dictionary['default_view'] ?>
	</td>
	<td>
		<?php
			$viewOptions = array (
					'day'=>$dictionary['dayView'],
					'week'=>$dictionary['weekView'],
					'month'=>$dictionary['monthView'],
					'year'=>$dictionary['yearView']
				);
			$this->plugin ('radios', 'value', $viewOptions,
				$renderObjects['calendarDefaultView'],
				null, '&nbsp;', 'class="radio"');
		?>
	</td>
	<td>
		<input type="hidden" name="name" value="calendarDefaultView" />
		<input type="hidden" name="action" value="modifyPreferencesPost" />
		<input type="submit" value="<?php echo $dictionary['modify'] ?>"  />
	</td>

</form>
</table>