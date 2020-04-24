<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.calendar
 * @subpackage i18n
 *
 * @copyright Brim - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
if (!isset ($dictionary))
{
	$dictionary = array ();
}
$dictionary['addAndContinue']='Hinzuf&#252;gen und weiter';
$dictionary['clickEventLink']='Klicken Sie den folgenden Link um den Vorfall in Ihrem  Browser zu &#246;ffnen:';
$dictionary['colour']='Farbe';
$dictionary['day']='Tag';
$dictionary['day0']='Sonntag';
$dictionary['day0short']='So.';
$dictionary['day1']='Montag';
$dictionary['day1short']='Mo.';
$dictionary['day2']='Dienstag';
$dictionary['day2short']='Die.';
$dictionary['day3']='Mittwoch';
$dictionary['day3short']='Mi.';
$dictionary['day4']='Donnerstag';
$dictionary['day4short']='Do.';
$dictionary['day5']='Freitag';
$dictionary['day5short']='Fr.';
$dictionary['day6']='Samstag';
$dictionary['day6short']='Sa.';
$dictionary['dayView']='Tagesansicht';
$dictionary['day_s']='Tag(e)';
$dictionary['days']='Tage';
$dictionary['default_view']='Standardansicht';
$dictionary['dontUseEndDate']='Kein Enddatum festlegen';
$dictionary['dontUseStartTime']='Keine Startzeit festlegen';
$dictionary['duration']='Dauer (Std:Min)';
$dictionary['enableRecurring']='Wiederholung einschalten';
$dictionary['endBy']='Endet am';
$dictionary['endDateAfterRecurringEndDate']='Enddatum liegt nach dem Ende der Wiederholung';
$dictionary['endDateMissing']='Enddatum fehlt';
$dictionary['endTimeMissing']='Endzeit fehlt';
$dictionary['end_date']='Enddatum';
$dictionary['end_time']='Endzeit';
$dictionary['event']='Termin';
$dictionary['firstDayOfWeek']='Erster Wochentag';
$dictionary['frequency']='Frequency';
$dictionary['hour']='Stunde';
$dictionary['hour_s']='Stunde(n)';
$dictionary['hours']='Stunden';
$dictionary['hours_minutes']='(Stunden:Minuten)';
$dictionary['invalidRepeatType']='Ung&#252;ltige Wiederholungsart';
$dictionary['item_help']='<p>
	Das Kalender Plugin hilft Ihnen ihre
	Termine online zu speichern.
</p>
<p>
	Durch klicken Sie auf den Namen eines Termines
	k&#246;nnen Sie dessen Inhalt wie Datum und Zeit &#228;ndern.
</p>
<p>
	Die folgenden Parameter eines Termines k&#246;nnen gesetzt werden:
</p>
<ul>
	<li><em>Name</em>:
		Name des Termines. Dieser Name wird im Kalender angezeigt.
	</li>
	<li><em>Ort</em>:
		Der Ort auf den sich Ihr Termin bezieht bzw. stattfindet.
	</li>
	<li><em>Startdatum</em>:
		Das Startdatum und optional die Startzeit f&#252;r den Termin.
	</li>
	<li><em>Enddatum</em>:
		Enddatum und optional die Endzeit f&#252;r der Termin.
	</li>
	<li><em>Beschreibung</em>:
		Beschreibung bzw. erweiterte Information zu dem Termin.
	</li>
	<li><em>Weiderholungsart</em>:
		Keine, t&#228;glich, w&#246;chentlich, monatlich oder j&#19876;hrlich
	</li>
	<li><em>Tag f&#252;r w&#246;chentliche Wiederholungen</em>:
		Wenn dies ein w&#246;chentlich, wiederkehrender Termin ist an welchen Tag(en) soll er wiederholt werden?
	</li>
</ul>
<p>
	Die Untermen&#252;s f&#252;r das Kalender Plugin
	sind verf&#252;gbar f&#252;r: Aktionen, Ansicht,
	Einstellungen und Hilfe
</p>
<h3>Aktionen</h3>
<p>
	Hier sind zwei Aktionen verf&#252;gbar.
	Die "Neu" Aktion erlaubt Ihnen einen
	neuen Termin anzulegen. Die "Heute" Aktion
	zeigt Ihnen die heutigen Termine in Ihrer
	favorisierten Ansicht (Schema).
<h3>Ansicht</h3>
<p>
	Vier Ansichten sind verf&#252;gbar:
</p>
<ul>
	<li><em>Jahresansicht</em>:
		Diese &#220;bersicht zeigt einen kleinen
		klickbare &#220;bersicht zum gew&#1828;hlten Jahr.
		Dort werden keine Termine angezeigt.
	</li>
	<li><em>Monatsansicht</em>:
		Diese &#220;bersicht zeigt den gew&#1828;hlten Monat
		und gleichzeitig eine kleine &#220;bersicht vom
		Vor- und Folgemonat.
		<br />
		Wenn Sie dies &#220;bersicht nutzen k&#1846;nnen Sie
		auf eine Wochennummer klicken und die
		Wochenansicht erscheint. Wenn Sie auf einen
		speziellen Tag klicken gelangen Sie in die
		Tagesansicht. Nat&#252;rlich K&#246;nnen Sie auch auf einen
		Termin klicken und gelangen direkt in diesen Termin
	</li>
	<li><em>Wochenansicht</em>:
		Diese &#220;bersicht zeigt die gew&#1828;hlte Woche.
		Mit Klick auf den Wochentag gelangen Sie
		in die Tagesansicht des gew&#228;hltes Tages.
		Klick auf einen angezeigten Termin zeigt
		die Termin Detials.
		<br />
		Links zur n&#228;chsten und vorherigen Woche
		werden ebenfalls oben in dieser Ansicht angezeigt.
	</li>
	<li><em>Tagesansicht</em>:
		Diese Ansicht zeigt einen gew&#228;hlten Tag an.
		Au&#223;erdem wird eine kleine Monatsansicht dargestellt.
		<br />
		Links zum n&#228;chsten und vorherigen Tag werden oben
		in der &#220;bersicht angezeigt.
	</li>
</ul>
<h3>Einstellungen</h3>
<p>
	In den Einstellungen k&#246;nnen Sie den Beginn
	ihrer Woche einstellen( Sonntag oder Montag),
	ob Sie Javascript Popups sehen m&#246;chten oder
	welches Ihre bevorzugte Ansicht (t&#228;glich. w&#246;chentlich,
	monatlich oder j&#228;hrlich) ist.
<h3>Hilfe</h3>
<p>
	Dieses Untermen&#252; enth&#228;lt einen Link zu den Informationen
	die Sie gerade lesen ;)
/p>
';
$dictionary['item_title']='Kalendar';
$dictionary['location']='Position';
$dictionary['minute']='Minute';
$dictionary['minute_s']='Minute(n)';
$dictionary['minutes']='Minuten';
$dictionary['modifyCalendarPreferences']='Kalendereinstellungen &#228;ndern';
$dictionary['month']='Monat';
$dictionary['monthView']='Monatsansicht';
$dictionary['months']='Monate';
$dictionary['next']='N&#228;chster';
$dictionary['noDayWeeklyRepeat']='Sie haben eine w&#246;chentliche Wiederholung ohne spezielle Tagesangabe gesetzt';
$dictionary['noEndingDate']='kein Enddatum';
$dictionary['noReminderTime']='Keine Erinnerung wurde aktiviert';
$dictionary['notYetSent']='Noch nicht gesendet';
$dictionary['organizer']='Planer';
$dictionary['previous']='Vorheriger';
$dictionary['recurrence']='Wiederholung';
$dictionary['recurrenceNoRepeatType']='Sie haben eine Wiederholung ohne Wiederholungsart gesetzt';
$dictionary['recurrenceRange']='Wiederholungszeitraum';
$dictionary['reminder']='Erinnerung';
$dictionary['reminders']='Erinnerungen';
$dictionary['repeat_day_weekly']='Tag f&#252;r w&#246;chentliche Wiederholungen';
$dictionary['repeat_end_date']='Wiederholung Enddatum';
$dictionary['repeat_type']='Wiederholungstyp';
$dictionary['repeat_type_daily']='T&#228;glich';
$dictionary['repeat_type_monthly']='Monatlich';
$dictionary['repeat_type_none']='keine Wiederholungsart';
$dictionary['repeat_type_weekly']='W&#246;chentlich';
$dictionary['repeat_type_yearly']='J&#228;hrlich';
$dictionary['startDateAfterEndDate']='Startdatum muss VOR dem Enddatum liegen';
$dictionary['startDateAfterRecurringEndDate']='Startdatum liegt nach dem Ende der Wiederholung';
$dictionary['startDateMissing']='Startdatum fehlt';
$dictionary['startTimeMissing']='Startzeit fehlt';
$dictionary['start_date']='Startdatum';
$dictionary['start_time']='Zeit';
$dictionary['time']='Zeit';
$dictionary['today']='Heute';
$dictionary['week']='Woche';
$dictionary['weekView']='Wochenansicht';
$dictionary['weeks']='Wochen';
$dictionary['whenSent']='Gesendet am/um';
$dictionary['whenToSend']='Ungef&#228;hr senden am/um';
$dictionary['year']='Jahr';
$dictionary['yearView']='Jahresansicht';
$dictionary['years']='Jahre';

?>