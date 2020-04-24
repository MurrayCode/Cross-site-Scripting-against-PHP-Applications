<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.calendar
 * @subpackage i18n
 * @tradotto in italiano da Luigi Garella
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
$dictionary['day0']='Domenica';
$dictionary['day1']='Luned&igrave;';
$dictionary['day1short']='Lun';
$dictionary['day2short']='Mar';
$dictionary['day2']='Marted&igrave;';
$dictionary['day3short']='Mer';
$dictionary['day3']='Mercoled&igrave;';
$dictionary['day4short']='Giov';
$dictionary['day4']='Gioved&igrave;';
$dictionary['day5']='Venerd&igrave;';
$dictionary['day5short']='Ven';
$dictionary['day6']='Sabato';
$dictionary['day6short']='Sab';
$dictionary['dayView']='Giorno';
$dictionary['days']='Giorni';
$dictionary['day']='Giorno';
$dictionary['default_view']='Visualizzazione di default';
$dictionary['duration']='Durata';
$dictionary['end_date']='Ultimo Giorno';
$dictionary['endDateMissing']='Manca data di Fine evento';
$dictionary['end_time']='Ora';
$dictionary['endTimeMissing']='Manca Ora di Fine evento';
$dictionary['event']='Evento';
$dictionary['firstDayOfWeek']='Primo giorno della settimana';
$dictionary['frequency']='Frequenza';
$dictionary['hour']='Ora';
$dictionary['hours']='Ore';
$dictionary['hours_minutes']='(Ore:Minuti)';
$dictionary['item_title']='Calendario';
$dictionary['location']='Luogo';
$dictionary['minute']='Minuto';
$dictionary['minutes']='Minuti';
$dictionary['modifyCalendarPreferences']='Modifica le preferenze del Calendario';
$dictionary['monthView']='Mese';
$dictionary['month']='Mese';
$dictionary['months']='Mesi';
$dictionary['next']='Succesivo';
$dictionary['organizer']='Organizer';
$dictionary['previous']='Precedente';
$dictionary['repeat_day_weekly']='Ripeti il giorno su base settimanale';
$dictionary['repeat_end_date']='Ripeti il giorno di fine evento';
$dictionary['repeat_type']='Tipo di Ripetizione';
$dictionary['repeat_type_daily']='Giornaliero';
$dictionary['repeat_type_weekly']='Settimanale';
$dictionary['repeat_type_monthly']='Mensile';
$dictionary['repeat_type_yearly']='Annuale';
$dictionary['start_date']='Data di Inizio';
$dictionary['startDateMissing']='Manca la Data di Inizio';
$dictionary['start_time']='Ora';
$dictionary['startTimeMissing']='Manca Ora di Inizio';
$dictionary['today']='Oggi';
$dictionary['week']='Settimana';
$dictionary['weeks']='Settimane';
$dictionary['weekView']='Settimana';
$dictionary['yearView']='Anno';
$dictionary['year']='Anno';
$dictionary['years']='Anni';

$dictionary['noDayWeeklyRepeat']='
Hai creato un evento a ripetizione settimanale ma non ne hai specificato il giorno';
$dictionary['startDateAfterEndDate']='La data di inizio deve essere i PRIMA della data di termine';
$dictionary['startDateAfterRecurringEndDate']='La data di inzio &egrave; posteriore alla data di termine della ricorrenza';
$dictionary['endDateAfterRecurringEndDate']='La data di termine &egrave; posteriore alla data di fine della sua ripetizione';
$dictionary['invalidRepeatType']='Tipo di ripetizione non valido';
$dictionary['recurrenceNoRepeatType']='Hai stabilito la frequenza ma non la tipologia';
$dictionary['item_help']='
<p>
Il plugin del Calendario ti permette di conservare online tutti i tuoi impegni ed appuntamenti
</p>
<p>
	Per modificare un evento &egrave; sufficiente cliccarlo</p>
<p>
	Si possono stabilire i seguenti parametri per un evento:
</p>
<ul>
	<li><em>Nome</em>:
		Il nome per questo evento, sar&agrave; visibile nel calendario.
	</li>
	<li><em>Luogo</em>:
		Dove si svolger&agrave; questo evento.
	</li>
	<li><em>Data inizio</em>:
		La data ed eventualmente ora di inizio 
		
	</li>
	<li><em>Data di termine</em>:
		La data in cui questo evento terminer&agrave;
	</li>
	<li><em>Descrizione</em>:
		La descrizione per il vostro evento
	</li>
	<li><em>Tipo di Ripetizione</em>:
		Nessuna, giornaliera, settimanale, mensile, annuale
	</li>
	<li><em>Ripeti il giorno su base settimanale</em>:
		
		Se questo &egrave; un evento settimanale, in quale giorno si ripete?
		
	</li>
</ul>
<p>
	I sottomen&ugrave; disponibili per il plugin Calendario sono:
	Azioni, Visualizza, Preferenze e Aiuto
	
</p>
<h3>Azioni</h3>
<p>
Sono possibili due azioni. "Aggiugi" permette di aggiungere un evento; "Oggi" carica la pagina delle attivit&agrave; odierne secondo le modalit&agrave; di display predefinite
<h3>Visualizza</h3>
<p>
	In quattro modi:
</p>
<ul>
	<li><em>Anno</em>:
		Questa modalit&agrave; non mostra gli eventi ma lo schema cliccabile dell\' anno selezionato
	</li>
	<li><em>Mese</em>:
		Questa modalit&agrave; permette di vedere il mese selezionato e contemporaneamente quello precedente ed il successivo, pi&ugrave; in piccolo
		<br />
		Potete cliccare sul numero della settimana o sul numero del giorno o sul nome di un evento per essersi condotti
	</li>
	<li><em>Settimana</em>:
		Questa modalit&agrave; vi fa vedere la settimana richiesta; cliccando sull\'intestazione di una giorno vi permette di visualizzarlo, cliccare su un evento di vederne i dettagli
		<br />
In cima alla tabella trovate i link alla settimana precedente ed a quella successiva
	</li>
	<li><em>Giorno</em>:
		Questo vi fa vedere le attivit&agrave; del giorno ed un piccolo schema del mese
		<br />
Ci sono anche i link al giorno precedente ed al successivo in cima allo schema
		
	</li>
</ul>
<h3>Preferenze</h3>
<p>

Potete usare le preferenze per stabilire quando inzia la settimana (domenica o luned&igrave;), se volete i popup in javascript e quale volete ch sia la vosta visualizzazione di default (giorno, settimana, mese, anno)

<h3>Aiuto</h3>
<p>
Questo sottomen&ugrave; contiene le informazioni che state leggendo :o)
</p>
';
$dictionary['dontUseStartTime']='Non usare Ora di inzio';
$dictionary['dontUseEndDate']='Non usare Data di termine';
$dictionary['duration']='Durata';
$dictionary['recurrence']='Ripetizione';
$dictionary['enableRecurring']='Attiva Ripetizione';
$dictionary['recurrenceRange']='Ampiezza di Ripetizione';
$dictionary['noEndingDate']='Nessuna data di Termine';
$dictionary['endBy']='Fine entro';
$dictionary['repeat_type_none']='Nessun tipo di ripetizione';
$dictionary['colour']='Colore';
$dictionary['noReminderTime']='Non &egrave; stato stabilito la sveglia per l\'evento';
$dictionary['reminder']='Reminder';
$dictionary['reminders']='Reminder';
$dictionary['time']='Ora';
$dictionary['addAndContinue']='Aggiungi e continua';
$dictionary['minute_s']='Minuti';
$dictionary['hour_s']='Ora/e';
$dictionary['day_s']='Giorno/i';
$dictionary['whenToSend']='Da mandare approssimativamente alle&nbsp;';
$dictionary['whenSent']='Manda alle&nbsp;';
$dictionary['notYetSent']='Non ancora inviato';
$dictionary['clickEventLink']='Clicca il link per aprire l\'evento nel tuo browser: ';
?>
