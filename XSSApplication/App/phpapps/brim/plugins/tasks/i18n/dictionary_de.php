<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.tasks
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
$dictionary['complete']='Vollst&#228;ndig';
$dictionary['completedWillDisappearAfterUpdate']='Den Artikel den Sie gew&#228;hlt haben ist nun 100% komplett';
$dictionary['due_date']='End Datum';
$dictionary['hideCompleted']='Erledigte ausblenden';
$dictionary['item_help']='<p>
	Das Plugin "Aufgaben" erlaubt es Ihnen
	ihre Aufgaben online zu verwalten. Folgende
	Einstellungen f&#252;r eine Aufgabe k&#246;nnen
	gemacht werden:
</p>
<ul>
	<li><em>Name</em>:
		Benennung/Name der Aufgabe.
	</li>
	<li><em>Verzeichnis/Aufgabe</em>:
		Indikator der festlegt ob ein
		neues Element ein Verzeichnis oder
		eine Aufgabe ist. Hinweis: Nachtr&#228;glich
		kann diese Einstellung nicht ge&#228;ndert
		werden.
	</li>
	<li><em>&#214;ffentlich/Privat</em>:
		Indikator der festlegt ob die Aufgabe
		&#246;ffentlich oder nur f&#252;r die eigenen Augen
		bestimmt ist.
		<br />
		Hinweis: Wenn Sie eine Aufgabe &#246;ffentlich
		machen wollen muss das &#252;bergeordnete Verzeichnis
		(wenn vorhanden) ebenfalls &#246;ffentlich sein.
		(Das oberste Verzeichnis ist &#246;ffentlich per
		Standardeinstellung)
	</li>
	<li><em>Vollst&#228;ndig</em>:
		Erlaubt den Nutzer eine Einsch&#228;tzung
		abzugeben (in Prozent) in wie weit
		die Aufgabe erledigt wurde.
	</li>
	<li><em>Priorit&#228;t</em>:
		Legt die Priorit&#228;t einer Aufgabe fest.
		Diese k&#246;nnen sein Dringend (Standard), Hoch,
		Mittel, Niedrig und Unwichtig.
	</li>
	<li><em>Status</em>:
		Ein zus&#228;tzlicher Status kann gesetzt werden.
		Dieser Status kann vom Nutzer selbst bestimmt werden.
		(geplant, begonnen etc.)
	</li>
	<li><em>Start Datum</em>:
		Das Datum an dem die Aufgabe beginnt.
	</li>
	<li><em>End Datum</em>:
		Das Datum an dem die Aufgabe endet.
	</li>
	<li><em>Beschreibung</em>:
		Beschreibung f&#252;r die Aufgabe.
	</li>
</ul>
<p>
	Die Untermen&#252;s die f&#252;r das "Aufgaben" Plugin
	verf&#252;gbar sind lauten Aktionen, Ansicht,
	Sortieren, Einstellungen und Hilfe.
</p>
<h3>Aktionen</h3>
<ul>
	<li><em>Neue Aufgabe</em>:
		Diese Aktion geleitet den Nutzer
		in eine Eingabemaske wo eine neue
		Aufgabe mit ihren dazugeh&#246;rigen
		Informationen und Parametern angelegt
		werden kann.
	</li>
	<li><em>Mehrfachauswahl</em>:
		Diese Aktion gestattet dem Nutzer
		mehrere Aufgaben gleichzeitig auszuw&#228;hlen
		(Verzeichnisse sind bei dieser Option
		NICHT ausw&#228;hlbar) und zu l&#246;schen oder auch
		in einen separates Verzeichnis zu verschieben.
		(denkbar ist z.Bsp. ein Erledigte-Aufgaben-Archiv
		Verzeichnis)
	</li>
	<li><em>Suche</em>:
		Diese Aktion erlaubt dem Nutzer die Aufgaben
		nach Name, Status oder Beschreibung zu durchsuchen.
	</li>
</ul>
<h3>Ansicht</h3>
<ul>
	<li><em>Aufklappen</em>:
		Diese Aktion veranla&#223;t das System alle
		Ordner und darin enthaltene Elemente/Aufgaben
		anzuzeigen. Dieses ist nur f&#252;r die Ansichten
		wie die "Baumansicht" verf&#252;gbar.
	</li>
	<li><em>Zuklappen</em>:
		Diese Aktion veranla&#223;t das System
		nur die Elemente/Aufgaben des
		gegenw&#228;rtigen Ordners anzuzeigen.
		(Unterordner sowie Lesezeichen).
	</li>
	<li><em>Verzeichnisstruktur</em>:
		Diese Aktion veranla&#223;t das System in
		die Verzeichnis&#252;bersicht umzuschalten.
		Diese Ansicht zeigt die Aufagen in der
		von Yahoo bekannten Art und Weise in
		einer Verzeichnisstruktur an.
		<br />
		Die Anzahl der Spalten f&#252;r die Ansicht
		kann in den Einstellungen f&#252;r das
		"Aufgaben" Plugin von dem Nutzer einge-
		stellt werden.
	</li>
	<li><em>Verzeichnisbaum</em>:
		Diese Aktion veranla&#223;t das System
		eine andere Art von &#220;bersicht anzuzeigen
		die eine Kombination zwischen Linien- basierten
		Ansicht und Baumansicht darstellt.
	</li>
	<li><em>Linien basiert</em>:
		Diese Ansicht stellt die Aufgaben
		und dazugeh&#246;rige Details in einer
		Linie dar.
	</li>
	<li><em>Baumansicht</em>:
		Diese Aktion veranla&#223;t das System in
		eine &#220;bersicht zu wechseln die von vielen
		Explorern bzw. Dateimanagern bekannt ist und
		den Inhalt/Aufgaben als Dateisystem darstellt.
	</li>
	<li><em>Freigaben anzeigen</em>:
		Zeigt alle &#246;ffentlichen Aufgaben
		aller Nutzer zusammen mit den eigenen
		Aufgaben an.
	</li>
	<li><em>Privates anzeigen</em>:
		Zeigt nur die eigenen Aufgaben an
		(alternativ zu "Freigaben anzeigen")
	</li>
</ul>
<h3>Sortieren</h3>
<ul>
	<li><em>Priorit&#228;t</em>:
		Sortiert die Aufagen nach ihrer Priorit&#228;t.
	</li>
	<li><em>Vollst&#228;ndig</em>:
		Sortiert die Aufgaben anhand ihres
		Erledigungsgrades.
	</li>
	<li><em>Start Datum</em>:
		Sortiert die Aufgaben nach ihrem Start Datum.
	</li>
	<li><em>Ende Datum</em>:
		Sortiert die Aufgaben nach ihrem Ende Datum.
	</li>
</ul>
<h3>Einstellungen</h3>
<ul>
	<li><em>Bearbeiten</em>:
		Bearbeiten Sie ihre Einstellungen.
		Sie k&#246;nnen die Spaltenanzahl f&#252;r die
		Aufgaben, wenn diese in der Verzeichnis
		&#220;bersichtsstruktur angezeigt werden, einstellen.
		Weiterhin k&#246;nnen Sie Javascripts Popups aktivieren,
		und Sie k&#246;nnen die Standardansicht f&#252;r
		Ihre Aufgaben w&#228;hlen (nur f&#252;r Verzeichnis,
		&#220;bersicht, Baum- oder Linienbasierten
		Ansichten verf&#252;gbar)
	</li>
</ul>
';
$dictionary['item_title']='Aufgaben';
$dictionary['modifyTaskPreferences']='Einstellungen f&#252;r die Aufgaben bearbeiten';
$dictionary['priority']='Priorit&#228;t';
$dictionary['priority1']='Dringend';
$dictionary['priority2']='Hoch';
$dictionary['priority3']='Mittel';
$dictionary['priority4']='Niedrig';
$dictionary['priority5']='Unwichtig';
$dictionary['showCompleted']='Erledigte anzeigen';
$dictionary['start_date']='Start Datum';
$dictionary['status']='Status';
$dictionary['taskHideCompleted']='Erledigte Aufgaben ausblenden';

?>