<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Ladislav Urbanek
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
$dictionary['charset']='utf-8';

$dictionary['day0short']='Ne';
$dictionary['day0']='Ned&#283;le';
$dictionary['day1']='Pond&#283;l&#237;';
$dictionary['day1short']='Po';
$dictionary['day2short']='&#218;t';
$dictionary['day2']='&#218;ter&#253;';
$dictionary['day3short']='St';
$dictionary['day3']='St&#345;eda';
$dictionary['day4short']='&#268;t';
$dictionary['day4']='&#268;tvrtek';
$dictionary['day5']='P&#225;tek';
$dictionary['day5short']='P&#225;';
$dictionary['day6']='Sobota';
$dictionary['day6short']='So';
$dictionary['dayView']='Den';
$dictionary['days']='Dny';
$dictionary['day']='Den';
$dictionary['default_view']='Standardn&#237; zobrazen&#237;';
$dictionary['duration']='Trv&#225;n&#237;';
$dictionary['end_date']='Ukon&#269;en&#237;';
$dictionary['endDateMissing']='Chyb&#237; datum ukon&#269;en&#237;';
$dictionary['end_time']='&#268;as';
$dictionary['endTimeMissing']='Chyb&#237; &#269;as ukon&#269;en&#237;';
$dictionary['event']='Ud&#225;lost';
$dictionary['firstDayOfWeek']='Za&#269;&#225;tek t&#253;dne';
$dictionary['frequency']='Opakov&#225;n&#237;';
$dictionary['hour']='Hodina';
$dictionary['hours']='Hodiny';
$dictionary['hours_minutes']='(Hodiny:Minuty)';
$dictionary['item_title']='Kalend&#225;&#345;';
$dictionary['location']='M&#237;sto';
$dictionary['minute']='Minuta';
$dictionary['minutes']='Minuty';
$dictionary['modifyCalendarPreferences']='Upravit p&#345;edvolby pro Kalend&#225;&#345;';
$dictionary['monthView']='M&#283;s&#237;c';
$dictionary['month']='M&#283;s&#237;c';
$dictionary['months']='M&#283;s&#237;ce';
$dictionary['next']='Dal&#353;&#237;';
$dictionary['organizer']='Organiz&#233;r';
$dictionary['previous']='P&#345;edchoz&#237;';
$dictionary['repeat_day_weekly']='P&#345;i t&#253;den&#237;m opakov&#225;n&#237;, opakovat ve dnech';
$dictionary['repeat_end_date']='Konec opakov&#225;n&#237;';
$dictionary['repeat_type']='Opakov&#225;n&#237;';
$dictionary['repeat_type_none']='';
$dictionary['repeat_type_daily']='Denn&#237;';
$dictionary['repeat_type_weekly']='T&#253;denn&#237;';
$dictionary['repeat_type_monthly']='M&#283;s&#237;&#269;n&#237;';
$dictionary['repeat_type_yearly']='Ro&#269;n&#237;';
$dictionary['start_date']='Za&#269;&#225;tek';
$dictionary['startDateMissing']='Chyb&#237; po&#269;&#225;te&#269;n&#237; datum';
$dictionary['start_time']='&#268;as';
$dictionary['startTimeMissing']='Chyb&#237; po&#269;&#225;te&#269;n&#237; &#269;as';
$dictionary['today']='Dnes';
$dictionary['week']='T&#253;den';
$dictionary['weeks']='T&#253;dny';
$dictionary['weekView']='T&#253;den';
$dictionary['yearView']='Rok';
$dictionary['year']='Rok';
$dictionary['years']='Roky';

$dictionary['noDayWeeklyRepeat']='Nastavil jste t&#253;den&#237; opakov&#225;n&#237; bez zadan&#237; dne';
$dictionary['startDateAfterEndDate']='Po&#269;&#225;te&#269;n&#237; datum mus&#237; b&#253;t P&#344;ED datem ukon&#269;en&#237;';
$dictionary['startDateAfterRecurringEndDate']='Po&#269;&#225;te&#269;n&#237; datum je po datu opakov&#225;n&#237;';
$dictionary['endDateAfterRecurringEndDate']='Datum ukon&#269;en&#237; je po datu ukon&#269;en&#237; opakov&#225;n&#237;';
$dictionary['invalidRepeatType']='Neplatn&#253; typ opakov&#225;n&#237;';
$dictionary['repeat_type_unknown']='';

$dictionary['recurrenceNoRepeatType']='Nastavil jste opakov&#225;n&#237;, ale ne jeho typ';
$dictionary['item_help']='
	<p>
		Plugin kalend&#225;&#345; V&#225;m pom&#225;h&#225; uchov&#225;vat v&#353;echny Va&#353;e sch&#367;zky
		on-line.
	</p>
	<p>
		Klikn&#283;te na n&#225;zev ud&#225;losti pro &#250;pravu data/&#269;asu atp.

	</p>
	<p>
		Je mo&#382;no nastavit n&#225;sleduj&#237;c&#237; parametry ud&#225;losti:
	</p>
	<ul>
		<li><em>N&#225;zev</em>:
			N&#225;zev ud&#225;losti. Bude se zobrazovat v kalend&#225;&#345;i.

		</li>
		<li><em>Um&#237;st&#283;n&#237;</em>:
			Um&#237;st&#283;n&#237; ud&#225;losti.
		</li>
		<li><em>Za&#269;&#225;tek</em>:
			Po&#269;&#225;te&#269;n&#237; datum a voliteln&#283; &#269;as pro ud&#225;lost.
		</li>
		<li><em>Ukon&#269;en&#237;</em>:
			Datum ukon&#269;en&#237; a voliteln&#283; &#269;as pro ud&#225;lost.
		</li>
		<li><em>Popis</em>:
			Popis pro ud&#225;lost.
		</li>
		<li><em>Opakov&#225;n&#237;</em>:
			Nikdy, denn&#283;, t&#253;dn&#283;, m&#283;s&#237;&#269;n&#283; nebo ro&#269;n&#283;.
		</li>
		<li><em>Denn&#237; opakov&#225;n&#237; pro t&#253;denn&#237; ud&#225;losti</em>:
			Je-li tohle t&#253;denn&#237; ud&#225;lost, kter&#233; dny se m&#225;
      opakovat?
		</li>
	</ul>
	<p>
		Pro plugin Kalend&#225;&#345; jsou dostupn&#233; polo&#382;ky:
		Akce, Zobrazen&#237;, P&#345;edvolby a N&#225;pov&#283;da
	</p>
	<h3>Akce</h3>
	<p>
    Zde jsou dostupn&#233; dv&#283; akce. Pro p&#345;id&#225;n&#237; ud&#225;losti a pro
    zobrazen&#237; aktu&#225;ln&#237;ho dne.

	<h3>Zobrazen&#237;</h3>
	<p>
		Jsou dostupn&#233; &#269;ty&#345;i typy.
	</p>
	<h3>P&#345;edvolby</h3>
	<p>
		P&#345;edvolby m&#367;&#382;ete pou&#382;&#237;t k nastaven&#237; za&#269;&#225;tku Va&#353;eho t&#253;dne
		(ned&#283;le nebo pond&#283;l&#237;), JavaScriptov&#253;ch pop-up oken
		a zobrazen&#237; (denn&#237;, t&#253;denn&#237;, m&#283;s&#237;&#269;n&#237; nebo ro&#269;n&#237;)

	<h3>N&#225;pov&#283;da</h3>
	<p>
		Podmenu obsahuje odkaz na informace jen&#382; pr&#225;v&#283; &#269;tete :)

	</p>
';
?>