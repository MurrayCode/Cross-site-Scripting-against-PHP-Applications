<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage i18n
 *
 * @german full translation Brim 1.0.3 / Nov2005 by Mario Glagow
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
include 'framework/i18n/common.php';
if (!isset ($dictionary))
{
	$dictionary=array();
}
$dictionary['activate']='Aktivierung';

$dictionary['about']='Info';
$dictionary['about_page']=' <h2>&#220;ber</h2>
<p>
	<b>'.$dictionary['programname'].' '.$dictionary['version'].'</b> Diese Applikation wurde geschrieben
	von '.$dictionary['authorname'].' (email:
	<a href="mailto:'.$dictionary['authoremail'].'"
	>'.$dictionary['authoremail'].'</a>)
	'.$dictionary['copyright'].' </p> <p> '.$dictionary['programname'].'
	stellt eine Open-Source Multiuser-Anwendung dar
	in der die Anwender ihre Kontakte, Termine, Lesezeichen etc. in einer
	eigenen, integrierten Desktopumgebung verwalten und pflegen k&#246;nnen.
</p>
<p>
	Diese Applikaton ('.$dictionary['programname'].') wurde gem&#228;&#223; der GNU General Public License
	ver&#246;ffentlicht. Klicken Sie <a href="documentation/gpl.html">hier</a>
	um die volle Version der GNU General Public License zu sehen.
	Die Homepage zum '.$dictionary['programname'].'-Projekt kann unter folgender Adresse erreicht
	werden: <a
	href="'.$dictionary['programurl'].'"
	>'.$dictionary['programurl'].'</a>
</p>';
$dictionary['actions']="Aktionen";
$dictionary['add']='Hinzuf&#252;gen';
$dictionary['addFolder'] = "Verzeichnis hinzuf&#252;gen";
$dictionary['addNode'] = "Eintrag hinzuf&#252;gen";
$dictionary['adduser']='Benutzer hinzuf&#252;gen';
$dictionary['admin']='Administration';
$dictionary['adminConfig']='Konfiguration';
$dictionary['admin_email']='Admin EMail';
$dictionary['allow_account_creation']="Benutzerregistrierung erlauben";
$dictionary['back']='Zur&#252;ck';
$dictionary['bookmark']='Lesezeichen';
$dictionary['bookmarks']='Lesezeichen';
$dictionary['cancel']='Abbrechen';
$dictionary['calendar']='Kalender';
$dictionary['collapse']='Zuklappen';
$dictionary['confirm']='Best&#228;tigen';
$dictionary['confirm_delete']='Sicher das Sie l&#246;schen wollen?';
$dictionary['contact']='Kontakt';
$dictionary['contacts']='Kontakte';
$dictionary['contents']='Inhalte';
$dictionary['dashboard']='Dashboard';
$dictionary['database']='Datenbank';
$dictionary['deactivate']='Deaktivieren';
$dictionary['deleteTxt']='L&#246;schen';
$dictionary['delete_not_owner']="Sie sind nicht berechtigt einen Eintrag zu l&#246;schen dessen Eigent&#252;mer Sie nicht sind.";
$dictionary['description']='Beschreibung';
$dictionary['down']='Runter';
$dictionary['email']='Email';
$dictionary['expand']='Aufklappen';
$dictionary['explorerTree']='Baumstruktur';
$dictionary['exportTxt']='Exportieren';
$dictionary['exportusers']='Nutzer exportieren';
$dictionary['file']='Datei';
$dictionary['findDoubles']='Finde Doubletten';
$dictionary['folder']='Ordner';
$dictionary['forward']='Vorw&#228;rts';
$dictionary['genealogy']='Abstammung';
$dictionary['help']='Hilfe';
$dictionary['home']='Home';
$dictionary['importTxt']='Import';
$dictionary['importusers']='Nutzer importieren';
$dictionary['input']='Eingabe';
$dictionary['input_error'] = "Bitte &#252;berpr&#252;fen Sie die Eingabefelder";
$dictionary['installation_path']="Installationspfad";
$dictionary['installer_exists']='<h2><font color="red">
Installationsdatei vorhanden! Bitte l&#246;schen</font></h2>';
$dictionary['item_count']='Anzahl der Elemente';
$dictionary['item_private'] = "Privates Element";
$dictionary['item_public'] = "&#214;ffentliches Element";
//$dictionary['item_title']='';
$dictionary['inverseAll']='Alle umkehren';
$dictionary['javascript_popups']="Javascript PopUps";
$dictionary['language']='Sprache';
$dictionary['last_created']='Zuletzt erstellt';
$dictionary['last_modified']='Zuletzt ver&#228;ndert';
$dictionary['last_visited']='Zuletzt besucht';
$dictionary['license_disclaimer']='
	Die Homepage vom '.$dictionary['programname'].'-Projekt kann
	unter folgender URL erreicht werden:
	<a href="'.$dictionary['programurl'].'"
	>'.$dictionary['programurl'].'</a>
	<br />
	'.$dictionary['copyright'].' '.$dictionary['authorname'].'
	(<a href="'.$dictionary['authorurl'].'"
	>'.$dictionary['authorurl'].'</a>).
	Sie k&#246;nnen mich kontaktieren unter<a
	href="mailto:'.$dictionary['authoremail'].'"
	>'.$dictionary['authoremail'].'</a>.  <br />
	Diese Programm ('.$dictionary['programname'].') ist freie Software
	Sie k&#246;nnen diese weiterverteilen und / oder
	ver&#228;ndern wenn dieses unter den Bestimmungen
	der GNU General Public License (Version 2 oder h&#246;her),
	ver&#246;ffentlicht von der Free Software Foundation geschieht.
	Klicken Sie <a href="documentation/gpl.html">hier</a>
 	f&#252;r die volle Version der GNU Lizenz.';

$dictionary['lineBasedTree']='Linienansicht';
$dictionary['link']='Link';
$dictionary['loginName']='Benutzername';
$dictionary['logout']='Abmelden';
$dictionary['mail']='EMail';
$dictionary['message']="Nachricht";
$dictionary['modify']='Bearbeiten';
$dictionary['modify_not_owner']="Sie sind nicht berechtigt den Eintrag zu bearbeiten. Sie k&#246;nnen nur eigene Eintr&#228;ge bearbeiten.";
$dictionary['month01']='Januar';
$dictionary['month02']='Februar';
$dictionary['month03']='M&#228;rz';
$dictionary['month04']='April';
$dictionary['month05']='Mai';
$dictionary['month06']='Juni';
$dictionary['month07']='Juli';
$dictionary['month08']='August';
$dictionary['month09']='September';
$dictionary['month10']='Oktober';
$dictionary['month11']='November';
$dictionary['month12']='Dezember';
$dictionary['most_visited']='Am meisten besucht';
$dictionary['move']='Verschieben';
$dictionary['multipleSelect']='Mehrfachauswahl';
$dictionary['mysqlAdmin']='MySQL';
$dictionary['nameMissing'] = "Name muss definiert sein";
$dictionary['name']='Name';
$dictionary['news']='News';
$dictionary['new_window_target']='Wo soll die neue Ansicht g&#246;ffnet werden';
$dictionary['no']='Nein';
$dictionary['note']='Notiz';
$dictionary['notes']='Notizen';
$dictionary['overviewTree']='Baumansicht';
$dictionary['password']='Passwort';
$dictionary['passwords']='Passw&#246;rter';
$dictionary['pluginSettings']='Plugins';
$dictionary['plugins']='Plugins';
$dictionary['preferences']='Einstellungen';
$dictionary['priority']='Priorit&#228;t';
$dictionary['private']='Privat';
$dictionary['public']='&#214;ffentlich';
$dictionary['quickmark']='
	Rechtsklick auf den folgenden Link um
	diese unter Bookmarks/Favoriten in Ihrem
	<b>Browser</b> hinzuzuf&#252;gen. Sie k&#246;nnen jederzeit
	die Lesezeichen nutzen die Seite wird sie automatisch
	in die '.$dictionary['programname'].' Lesezeichen einf&#252;gen.
	<br />
	<br />
	<font size="-2">Bitte klicken Sie "OK" wenn
	nachgefragt wird ob das Lesezeichen hinzugef&#252;gt werden soll
	- Code der Bookmarks aufnimmt wird von manchen Browsern nicht korrekt ausgef&#252;hrt.</font><br />';
$dictionary['refresh']='Aktualisieren';
$dictionary['root']='oberste Ebene';
$dictionary['search']='Suche';
$dictionary['selectAll']='alle ausw&#228;hlen';
$dictionary['deselectAll']='Auswahl r&#252;ckg&#228;ngig';
$dictionary['setModePrivate'] = "Eigene ansehen";
$dictionary['setModePublic'] = "&#214;ffentliche Ansehen";
$dictionary['show']='Anzeigen';
$dictionary['sort']='Sortieren';
$dictionary['submit']='Senden';
$dictionary['sysinfo']='SysInfo';
$dictionary['theme']='Schema';
$dictionary['title']='Titel';
$dictionary['today']='Heute';
$dictionary['tasks']='Aufgaben';
$dictionary['task']='Aufgabe';
$dictionary['translate']='&#220;bersetzen';
$dictionary['tasks']='Aufgaben';
$dictionary['task']='Aufgabe';
$dictionary['up']='oben';
$dictionary['locator']='URL';
$dictionary['user']='Nutzer';
$dictionary['view']="Ansicht";
$dictionary['visibility']='Sichtbarkeit';
$dictionary['webtools']='WebTools';
$dictionary['welcome_page']='<h1>Willkommen %s </h1><h2>'.$dictionary['programname'].' -
das Multifunktionale Dind </h2>';
$dictionary['yahoo_column_count']='Yahoo Baumansicht Spaltenanzahl';
$dictionary['yahooTree']='Verzeichnisstruktur';
$dictionary['yes']='Ja';
// sterry
$dictionary['polardata'] 			= 'Polare Daten';
$dictionary['textsource'] 			= 'Textquelle';
$dictionary['banking'] 				= 'E-Banking';
$dictionary['synchronizer'] 		= 'Synchronizer';
$dictionary['spellcheck']='Check spelling';
$dictionary['item_help']='
<h1>'.$dictionary['programname'].' Hilfe</h1>
<p>
	'.$dictionary['programname'].' hat zwei Men&#252;leisten, ein wird Applikationsleiste
	genannt und enth&#228;lt die applikationsweiten Einstellungsm&#246;glichkeiten,
	die andere wird Pluginleiste genannt und enth&#228;lt die Links zu den
	unterschiedlichen Plugins. F&#252;r spezifische Hilfe zu den Plugins
	klicke <a href="#plugins">hier</a>.
</p>
<p>
	Der Link "Einstellungen" in der Applikationsleiste f&#252;hrt Sie in eine
	Ansicht in der Sie Ihre Sprache, Schema welches Sie nutzen m&#246;chten sowie
	pers&#246;nliche Einstellungen wie Passwort, EMail Adresse etc. vornehmen k&#246;nnen.
	Hinweis: Sprache und Schema k&#246;nnen nicht gleichzeitig ge&#228;ndert werden.
	Jede Einstellung muss durch klicken auf den "Bearbeiten" Schalter best&#228;tigt werden.
</p>
<p>
	Der Link "Info" zeigt die generelles Applikationsinformationen inklusive der
	aktuellen Versionsnummer.
</p>
<p>
	Klicken auf den Link "Abmelden" f&#252;hrt zur Abmeldung/Logout am System.
	Dieser Link l&#246;scht auch das Cookie, welches gesetzt wurde wenn man beim Anmelden
	die Option "Autologin" aktiviert hat. Um '.$dictionary['programname'].' wieder zu nutzen muss man sich erneut
	am System anmelden.
</p>
<p>
	Der Link "Plugins" f&#252;hrt Sie in die Ansicht in der Sie die verschiedenen Plugins
	von '.$dictionary['programname'].', je nach Ihren W&#252;nschen/Bedarf, aus-/einschalten k&#246;nnen. Deaktivierte
	Plugins werden weder in der Pluginleiste noch in der Hilfe angezeigt.
</p>
';
$dictionary['collections']='Sammlungen';
$dictionary['depot']='Depot &#220;berwachung';
$dictionary['checkbook']='Scheckbuch';
$dictionary['gmail']='GMail';
$dictionary['dateFormat']='Datumsformat';
$dictionary['select']='Auswahl';
$dictionary['formError']='Die gesendeten Daten enthalten Fehler';
$dictionary['defaultTxt']='Standard';
$dictionary['preferedIconSize']='Bevorzugte Icongr&#246;&#223;e';
$dictionary['showTips']='Tipps anzeigen';
$dictionary['tip']='Tipp';
$dictionary['noSearchResult']='Keine Resulate';
$dictionary['recipes']='Rezepte';
?>
