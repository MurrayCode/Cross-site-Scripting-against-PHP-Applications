<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @package org.brim-project.plugins.calendar
 * @subpackage i18n
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
if (!isset ($dictionary))
{
	$dictionary=array();
}


$dictionary['day0short']='Dom';
$dictionary['day0']='Domingo';
$dictionary['day1']='Lunes';
$dictionary['day1short']='Lun';
$dictionary['day2short']='Mar';
$dictionary['day2']='Martes';
$dictionary['day3short']='Mie';
$dictionary['day3']='Mi�rcoles';
$dictionary['day4short']='Jue';
$dictionary['day4']='Jueves';
$dictionary['day5']='Viernes';
$dictionary['day5short']='Vie';
$dictionary['day6']='S�bado';
$dictionary['day6short']='Sab';
$dictionary['dayView']='Por D�a';
$dictionary['days']='D�as';
$dictionary['day']='D�a';
$dictionary['default_view']='Vista Normal';
$dictionary['duration']='Duraci�n';
$dictionary['end_date']='Fecha de Finalizaci�n';
$dictionary['endDateMissing']='Falta: Fecha de Finalizaci�n ';
$dictionary['end_time']='Tiempo de Finalizaci�n';
$dictionary['endTimeMissing']='Falta: Tiempo de Finalizaci�n';
$dictionary['event']='Evento';
$dictionary['firstDayOfWeek']='Primer D�a de la Semana';
$dictionary['frequency']='Frecuencia';
$dictionary['hour']='Hora';
$dictionary['hours']='Horas';
$dictionary['hours_minutes']='(Horas:Minutos)';
$dictionary['item_help']='Ayuda';
$dictionary['item_title']='Calendario';
$dictionary['location']='Ubicaci�n';
$dictionary['minute']='Minuto';
$dictionary['minutes']='Minutos';
$dictionary['modifyCalendarPreferences']='Preferencias';
$dictionary['monthView']='Por Mes';
$dictionary['month']='Mes';
$dictionary['months']='Meses';
$dictionary['next']='Siguiente';
$dictionary['organizer']='Organizador';
$dictionary['previous']='Anterior';
$dictionary['repeat_day_weekly']='D�a de repetici�n para repetici�n por semanas';
$dictionary['repeat_end_date']='Fecha de finalizaci�n de repeticiones';
$dictionary['repeat_type']='Tipo de repetici�n';
$dictionary['repeat_type_none']='';
$dictionary['repeat_type_daily']='Diaria';
$dictionary['repeat_type_weekly']='Semanal';
$dictionary['repeat_type_monthly']='Mensual';
$dictionary['repeat_type_yearly']='Anual';
$dictionary['start_date']='Fecha de Inicio';
$dictionary['startDateMissing']='Falta: Fecha de Inicio';
$dictionary['start_time']='Tiempo de Inicio';
$dictionary['startTimeMissing']='Falta: Tiempo de Inicio';
$dictionary['today']='Hoy';
$dictionary['week']='Semana';
$dictionary['weeks']='Semanas';
$dictionary['weekView']='Por Semanas';
$dictionary['yearView']='Por A�o';
$dictionary['year']='A�o';
$dictionary['years']='A�os';

$dictionary['noDayWeeklyRepeat']='Especific� repetici�n por semana pero no el/los d�a(s) de repetici�n.';
$dictionary['startDateAfterEndDate']='La fecha de inicio debe estar ANTES que la fecha de finalizaci�n.';
$dictionary['startDateAfterRecurringEndDate']='La fecha de inicio est� DESPUES que la fecha de finalizaci�n de la repetici�n.';
$dictionary['endDateAfterRecurringEndDate']='La fecha de finalizaci�n est� DESPUES que la fecha de finalizaci�n de la repetici�n.';
$dictionary['invalidRepeatType']='Tipo de repetici�n inv�lido.';
$dictionary['repeat_type_unknown']='';

$dictionary['recurrenceNoRepeatType']='Seleccion� repetici�n pero no tipo de repetici�n';
$dictionary['item_help']='
	<p>
	El m�dulo Calendario le permite organizar sus citas y tareas en l�nea.

	</p>
	<p>
	Para modificar un evento/tarea debe hacer click sobre el nombre de este.
	</p>
	<p>
	    Los par�metros que se pueden utilizar para un evento son:
	</p>
	<ul>
		<li><em>Nombre</em>:

		      El nombre del evento, este campo se ver� en el calendario.
		</li>
		<li><em>Ubici�n</em>:
		     El lugar donde se llevar� a cabo el evento.
		</li>
		<li><em>Fecha de Inicio</em>:
			La fecha de inicio para este evento.
		</li>
		<li><em>Fecha de Terminaci�n</em>:
		 	La fecha de terminaci�n del evento.
		</li>
		<li><em>Descripci�n</em>:
			Una descripci�n del evento.
		</li>
		<li><em>Tipo de repetici�n</em>:
			Sea ning�no, diario, semanal, mensual, anual.
		</li>
		<li><em>D�a de repetici�n para eventos semanales</em>:
			Si es una repetici�n semanal, en qu� d�a o dias se repite?
		</li>
	</ul>
	<p>
		The submenus that are available for the calendar plugin
		are: Actions, View, Preferences and Help
	</p>
	<h3>Actions</h3>
	<p>
		There are two actions available. The add action allows
		you to add an event. The today action takes you to the
		current day and display that day in your prefered layout.
	<h3>View</h3>
	<p>
		Four types are available:
	</p>
	<ul>
		<li><em>Year</em>:
			This overview shows a small clickable overview of the
			requested year. No events are shown.
		</li>
		<li><em>Month</em>:
			This overview shows the requested month as well as
			smaller overviews of the previous and the next month.
			Using this overview, you can click on the weeknumber
			to go to a specific week, you can click on a daynumber
			to go to a specific day overview or you can click on
			an event to go to that specific event
		</li>
		<li><em>Week</em>:
			This overview shows the requested week. Clikcing on the
			dayheaders will get you to that specific day, clicking on
			an event will show you that events\' details.
			<br />
			Links to the next and previous week are shown at the top
			of the overview.
		</li>
		<li><em>Day</em>:
			This shows one specific day. Besides that, a small
			overview of this month is shown.
			<br />
			Links to the next and previous day are shown at the top
			of the overview.
		</li>
	</ul>
	<h3>Preferences</h3>
	<p>
		You can use the preferences to set your start of the week
		(either sunday or monday), whether you would like to have
		javascript popups and what your default view is
		(day, week, month or year)
	<h3>Help</h3>
	<p>
		This submenu contains a link to the information that you
		are currently reading :-)
	</p>
';
?>