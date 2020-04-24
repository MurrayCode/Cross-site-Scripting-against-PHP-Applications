<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Thibaut Cousin
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
$dictionary['addAndContinue']='Ajouter et continuer';
$dictionary['clickEventLink']='Cliquez sur le lien suivant pour ouvrir l\'&#233;v&#233;nement dans votre navigateur&#160;';
$dictionary['colour']='Couleur';
$dictionary['day']='Jour';
$dictionary['day0']='Dimanche';
$dictionary['day0short']='Dim';
$dictionary['day1']='Lundi';
$dictionary['day1short']='Lun';
$dictionary['day2']='Mardi';
$dictionary['day2short']='Mar';
$dictionary['day3']='Mercredi';
$dictionary['day3short']='Mer';
$dictionary['day4']='Jeudi';
$dictionary['day4short']='Jeu';
$dictionary['day5']='Vendredi';
$dictionary['day5short']='Ven';
$dictionary['day6']='Samedi';
$dictionary['day6short']='Sam';
$dictionary['dayView']='Jour';
$dictionary['day_s']='Jour(s)';
$dictionary['days']='Jours';
$dictionary['default_view']='Vue par d&#233;faut';
$dictionary['dontUseEndDate']='Pas de date de fin';
$dictionary['dontUseStartTime']='Pas d\'heure de d&#233;but';
$dictionary['duration']='Dur&#233;e';
$dictionary['enableRecurring']='Activer la r&#233;p&#233;tition';
$dictionary['endBy']='Se termine le';
$dictionary['endDateAfterRecurringEndDate']='La date de fin est post&#233;rieure la date de fin de r&#233;p&#233;tition';
$dictionary['endDateMissing']='Date de fin manquante';
$dictionary['endTimeMissing']='Heure de fin manquante';
$dictionary['end_date']='Date de fin';
$dictionary['end_time']='Heure';
$dictionary['event']='&#201;v&#233;nement';
$dictionary['firstDayOfWeek']='Premier jour de la semaine';
$dictionary['frequency']='Fr&#233;quence';
$dictionary['hour']='Heure';
$dictionary['hour_s']='Heure(s)';
$dictionary['hours']='Heures';
$dictionary['hours_minutes']='(heures:minutes)';
$dictionary['invalidRepeatType']='Type de r&#233;p&#233;tition incorrect';
$dictionary['item_help']='<p>Le module externe "Agenda" vous permet de m&#233;moriser vos rendez-vous en ligne.
</p>
<p>Cliquez sur le nom d\'un &#233;v&#233;nement pour modifier ses param&#232;tres : date, heure, etc.
</p>
<p>Vous pouvez jouer sur les param&#232;tres suivants&#160;:
</p>
<ul>
        <li><em>Nom</em>&#160;: le nom de l\'&#233;v&#233;nement. Ce nom appara&#238;t sur l\'agenda.
        </li>
        <li><em>Lieu</em>&#160;: le lieu o&#249; l\'&#233;v&#233;nement a lieu.
        </li>
        <li><em>Date de d&#233;but</em>&#160;: la date de d&#233;but, et &#233;ventuellement l\'heure, de l\'&#233;v&#233;nement.
        </li>
        <li><em>Date de fin</em>&#160;: la date de fin, et &#233;ventuellement l\'heure, de l\'&#233;v&#233;nement.
        </li>
        <li><em>Description</em>&#160;: description de l\'&#233;v&#233;nement.
        </li>
        <li><em>Type de r&#233;p&#233;tition</em>&#160;: aucune, quotidienne, hebdomadaire, menseulle ou annuelle.
        </li>
        <li><em>Jour(s) pour une r&#233;p&#233;tition hebdomadaire</em>&#160;: si l\'&#233;v&#233;nement est hebdomadaire, quel(s) jour(s) se r&#233;p&#232;te-il&#160;?
        </li>
</ul>
<p>Les sous-menus disponibles pour le module externe Agenda sont "Action", "Vue", "Pr&#233;f&#233;rences" et "Aide".
</p>
<h3>Actions</h3>
<p>Il y a deux actions possibles : "Ajouter" vous permet d\'ajouter un &#233;v&#233;nement quelconque. "Aujourd\'hui" ram&#232;ne l\'affichage &#224; votre vue pr&#233;f&#233;r&#233;e, avec le jour courant s&#233;lectionn&#233;.
<h3>Vue</h3>
<p>Quatre types de vue sont disponibles&#160;:
</p>
<ul>
        <li><em>Ann&#233;e</em>&#160;: affiche une vue cliquable de l\'ann&#233;e demand&#233;e. Aucun &#233;v&#233;n&#233;ment n\'est affich&#233; dans cette vue.
        </li>
        <li><em>Mois</em>&#160;: affiche une vue du mois demand&#233;, ainsi que des vues r&#233;duites du mois pr&#233;c&#233;dent et du mois suivant.
        <br />
        Dans cette vue, vous pouvez cliquer sur un num&#233;ro de semaine pour afficher cette semaine. Vous pouvez aussi afficher un jour ou un &#233;v&#233;nement.
        </li>
        <li><em>Semaine</em>&#160;: affiche une vue de la semaine demand&#233;e. Cliquer sur l\'en-t&#234;te portant le nom d\'un jour vous permet d\'afficher ce jour. Cliquer sur un &#233;v&#233;nement affiche les d&#233;tails de cet &#233;v&#233;nement.
        <br />
        Des liens vers les semaines pr&#233;c&#233;dente et suivante sont disponibles en haut de la page.
        </li>
        <li><em>Jour</em>&#160;: affiche une vue du jour demand&#233;. Un aper&#231;u du mois correspondant est inclu.
        <br />
        Des liens vers les jours pr&#233;c&#233;dent et suivant sont disponibles en haut de la page.
        </li>
</ul>
<h3>Pr&#233;f&#233;rences</h3>
<p>Vous pouvez utiliser les pr&#233;f&#233;rences pour fixer le jour de d&#233;but de semaine (dimanche ou lundi), d&#233;finir l\'utilisation de popups Javascript et choisir la vue par d&#233;faut (jour, semaine, mois ou ann&#233;e).
<h3>Aide</h3>
<p>Ce sous-menu contient un lien vers le texte que vous &#234;tes en train de lire. :-)
</p>';
$dictionary['item_title']='Agenda';
$dictionary['location']='Lieu';
$dictionary['minute']='Minute';
$dictionary['minute_s']='Minute(s)';
$dictionary['minutes']='Minutes';
$dictionary['modifyCalendarPreferences']='Modifier les pr&#233;f&#233;rences de l\'agenda';
$dictionary['month']='Mois';
$dictionary['monthView']='Mois';
$dictionary['months']='Mois';
$dictionary['next']='Suivant';
$dictionary['noDayWeeklyRepeat']='Vous avez d&#233;fini un &#233;v&#233;nement hebdomadaire sans indiquer de jour';
$dictionary['noEndingDate']='Pas de date de fin';
$dictionary['noReminderTime']='Aucune heure de rappel n\'a &#233;t&#233; d&#233;finie';
$dictionary['notYetSent']='Pas encore envoy&#233;';
$dictionary['organizer']='Agenda';
$dictionary['previous']='Pr&#233;c&#233;dent';
$dictionary['recurrence']='R&#233;p&#233;tition';
$dictionary['recurrenceNoRepeatType']='Vous avez indiqu&#233; une r&#233;p&#233;tition, mais pas pr&#233;cis&#233; le type de r&#233;p&#233;tition';
$dictionary['recurrenceRange']='Intervalle de date pour la r&#233;p&#233;tition';
$dictionary['reminder']='Rappel';
$dictionary['reminders']='Rappels';
$dictionary['repeat_day_weekly']='Jour(s) pour une r&#233;p&#233;tition hebdomadaire';
$dictionary['repeat_end_date']='Date de fin de r&#233;p&#233;tition';
$dictionary['repeat_type']='Type de r&#233;p&#233;tition';
$dictionary['repeat_type_daily']='Quotidienne';
$dictionary['repeat_type_monthly']='Mensuelle';
$dictionary['repeat_type_none']='Pas de type de r&#233;p&#233;tition';
$dictionary['repeat_type_weekly']='Hebdomadaire';
$dictionary['repeat_type_yearly']='Annuelle';
$dictionary['startDateAfterEndDate']='La date de d&#233;but doit &#234;tre AVANT la date de fin';
$dictionary['startDateAfterRecurringEndDate']='La date de d&#233;but est post&#233;rieure &#224; la date de fin de r&#233;p&#233;tition';
$dictionary['startDateMissing']='Date de d&#233;but manquante';
$dictionary['startTimeMissing']='Heure de d&#233;but manquante';
$dictionary['start_date']='Date de d&#233;but';
$dictionary['start_time']='Heure';
$dictionary['time']='Heure';
$dictionary['today']='Aujourdh\'ui';
$dictionary['week']='Semaine';
$dictionary['weekView']='Semaine';
$dictionary['weeks']='Semaines';
$dictionary['whenSent']='Envoyer &#224;&nbsp;';
$dictionary['whenToSend']='&#192; envoyer approximativement &#224;&#160;';
$dictionary['year']='Ann&#233;e';
$dictionary['yearView']='Ann&#233;e';
$dictionary['years']='Ann&#233;es';

?>