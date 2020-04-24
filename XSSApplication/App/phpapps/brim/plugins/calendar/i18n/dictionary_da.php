<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Per Thomsen
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
$dictionary['day0short']='S&oslash;n';
$dictionary['day0']='S&oslash;ndag';
$dictionary['day1']='Mandag';
$dictionary['day1short']='Man';
$dictionary['day2short']='Tir';
$dictionary['day2']='Tirsdag';
$dictionary['day3short']='Ons';
$dictionary['day3']='Onsdag';
$dictionary['day4short']='Tor';
$dictionary['day4']='Torsdag';
$dictionary['day5']='Fredag';
$dictionary['day5short']='Fre';
$dictionary['day6']='L&oslash;rdag';
$dictionary['day6short']='L&oslash;r';
$dictionary['dayView']='Dag';
$dictionary['days']='Dage';
$dictionary['day']='Dag';
$dictionary['default_view']='Standardvisning';
$dictionary['duration']='Varighed';
$dictionary['end_date']='Slutdato';
$dictionary['endDateMissing']='Slutdato mangler';
$dictionary['end_time']='Tid';
$dictionary['endTimeMissing']='Sluttid mangler';
$dictionary['event']='Event';
$dictionary['firstDayOfWeek']='F&oslash;rste ugedag';
$dictionary['frequency']='Interval';
$dictionary['hour']='Time';
$dictionary['hours']='Timer';
$dictionary['hours_minutes']='(Timer:Minuter)';
$dictionary['item_title']='Kalender';
$dictionary['location']='Sted';
$dictionary['minute']='Minut';
$dictionary['minutes']='Minutter';
$dictionary['modifyCalendarPreferences']='Modifcer kalenderstandarder';
$dictionary['month01']='Januar';
$dictionary['month02']='Februar';
$dictionary['month03']='Marts';
$dictionary['month04']='April';
$dictionary['month05']='Maj';
$dictionary['month06']='Juni';
$dictionary['month07']='Juli';
$dictionary['month08']='August';
$dictionary['month09']='September';
$dictionary['month10']='Oktober';
$dictionary['month11']='November';
$dictionary['month12']='December';
$dictionary['monthView']='M&aring;ned';
$dictionary['month']='M&aring;ned';
$dictionary['months']='M&aring;neder';
$dictionary['next']='N&aelig;ste';
$dictionary['organizer']='Organiser';
$dictionary['previous']='Forrige';
$dictionary['repeat_day_weekly']='Gentag dag for ugentlig gentagelse';
$dictionary['repeat_end_date']='Gentag slutdato';
$dictionary['repeat_type']='Gentag type';
$dictionary['repeat_type_daily']='Daglig';
$dictionary['repeat_type_weekly']='Ugentlig';
$dictionary['repeat_type_monthly']='M&aring;nedlig';
$dictionary['repeat_type_yearly']='&aring;rlig';
$dictionary['start_date']='Startdato';
$dictionary['startDateMissing']='Startdag mangler';
$dictionary['start_time']='Tid';
$dictionary['startTimeMissing']='Starttid mangler';
$dictionary['today']='I dag';
$dictionary['week']='Uge';
$dictionary['weeks']='Uger';
$dictionary['weekView']='Uge';
$dictionary['yearView']='&aring;r';
$dictionary['year']='&aring;r';
$dictionary['years']='&aring;r';

$dictionary['noDayWeeklyRepeat']='Du har sat ugentlig gentagelse af en event, uden at specificere dagen';
$dictionary['startDateAfterEndDate']='Startdato skal v&aelig;re F&Oslash;R slutdato';
$dictionary['startDateAfterRecurringEndDate']='Startdato er efter den tilbagevendende dato';
$dictionary['endDateAfterRecurringEndDate']='Slutdato er efter den tilbagevendende dato';
$dictionary['invalidRepeatType']='Forkert gentagelsestype';
$dictionary['recurrenceNoRepeatType']='Du har markeret tilbagevendende, men ikke type for tilbagevende';
$dictionary['item_help']='
<p>
	Kalenderdelen hj&aelig;lper dig med at gemme dine aftaler online.
</p>
<p>
	Klik p&aring; event-navnet for at rette indholdet som dag/tid etc.
</p>
<p>
	F&oslash;lgende parametre for en event kan s&aelig;ttes:
</p>
<ul>
	<li><em>Navn</em>:
		Navnet p&aring; event\'en. Dette navn bliver vist
		i kalenderen.
	</li>
	<li><em>Sted</em>:
		Stedet, hvor event\'en finder sted.
	</li>
	<li><em>Startdato</em>:
		Startdato og evt. tid for denne	event.
	</li>
	<li><em>Slutdato</em>:
		Slutdato og evt. tid for denne	event.
	</li>
	<li><em>Beskrivelse</em>:
		Beskrivelse af event\'en.
	</li>
	<li><em>Gentagelsestype</em>:
		Enten ingen, daglig, ugentlig,
		m&aring;nedlig eller &aring;rlig
	</li>
	<li><em>Gentagelsesdag for ugentlige events</em>:
		Hvis dette er en ugentlig event, hvilken
		dag(e) skal den gentages?
	</li>
</ul>
<p>
	Undermenuerne der er tilg&aelig;ngelig fra
	kalenderen er: Handling, Vis,
	Indstillinger og Hj&aelig;lp
</p>
<h3>Handling</h3>
<p>
	Der er to handlinger tilg&aelig;ngelig. &quot;Tilf&oslash;j&quot;, som
	tilf&oslash;jer en event. &quot;I dag&quot;, som bringer
	dig til den aktuelle dato i det valgte layout
	<h3>Vis</h3>
<p>
	Der er fire sk&aelig;rmbilledetyper tilg&aelig;ngelige:
</p>
<ul>
	<li><em>&Aring;r</em>:
		Viser valgte &aring;r som overbliksbillede.
		M&aring;nederne vises med klikbare datoer. Der
		vises ingen events.
	</li>
	<li><em>M&aring;ned</em>:
		Viser valgte m&aring;ned med sm&aring; overbliksbilleder
		af forrige og n&aelig;ste m&aring;ned.
		<br />
		N&aring;r &quot;M&aring;ned&quot; v&aelig;lges, kan man klikke p&aring;
		en/et specifik dag/uge for at vise denne.
		Eller du kan klikke p&aring; en event for at
		vise denne.
	</li>
	<li><em>Uge</em>:
		Viser valgte uge.
		Hvis der klikkes p&aring; dagen, vises den valgte
		dag. Hvis der klikkes p&aring; event\'en, vises den
		valgte event med detalier.
		<br />
		Links n&aelig;ste og forrige uge, vises i toppen
		af billedet.
	</li>
	<li><em>Dag</em>:
		Viser valgte dag. Udover dette, vises
		et lille overbliksbillede af m&aring;neden.
		<br />
		Links til n&aelig;ste og forrige dag, vises i toppen
		af billedet.
	</li>
</ul>
<h3>Indstillinger</h3>
<p>
	Du kan s&aelig;tte hvilken ugedag, du &oslash;nsker som 1.
	dag i ugen (s&oslash;ndag eller mandag). Om du &oslash;nsker
	javascript popups og om standardvisningen skal
	vise dag, uge, m&aring;ned eller &aring;r.
<h3>Hj&aelig;lp</h3>
<p>
	Denne undermenu indeholder link til
	den information du l&aelig;ser i &oslash;jeblikket :-)
</p>
';
?>