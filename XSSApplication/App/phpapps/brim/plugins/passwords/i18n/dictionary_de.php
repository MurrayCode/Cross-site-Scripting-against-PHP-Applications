<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.passwords
 * @subpackage i18n
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2005 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
/*@german full translation Brim 1.0.3 / Nov2005 by Mario Glagow */

if (!isset ($dictionary))
{
	$dictionary=array();
}

$dictionary['item_title']='Passw&#246;rter';
$dictionary['modifyPasswordPreferences']='Passwort Einstellungen bearbeiten';
$dictionary['passPhrase']='Passwort/Kontrolle';
$dictionary['login']='Login Name';
$dictionary['url']='Link';
$dictionary['generate']='erstellen/generieren';
$dictionary['siteUrl']='Seite/Adresse URL';
$dictionary['masterPassword']='Master Passwort';
$dictionary['generatePassword']='Passwort generieren';
$dictionary['generatedPassword']='Generiertes Passwort';
$dictionary['credits']='
<p>
        basiert auf:
</p>
<ul>
	<li><a href="http://pajhome.org.uk/crypt/md5"
		>Paul Johnston</a>\'s MD5 javascript implementation</a></li>
	<li><a href="http://angel.net/~nic/passwdlet.html"
		>Nic Wolff</a>\'s password generator</li>
	<li><a href="http://chris.zarate.org/passwd.txt"
		>Chris Zarate</a>\'s modification to ignore subdomains</a></li>
</ul>';
$dictionary['item_help']='
<p>
	Das Passwort Plugin erm&#246;glicht es Ihnen
	ihre Passw&#246;rter online zu organisieren.
	Das Plugin nennt sich zwar Passwort Manager
	aber jede Art von Text kann sicher abgespeichert
	werden mit Hilfe des Plugins.
	Passw&#246;rter sind typischerweise die Art
	von Daten die man <em>verschl&#252;sselt</em>
	in der Datenbank abspeichert.
</p>
<p>
	<font color="red">
		Es ist wichtig zu wissen das Ihre
		Passw&#246;rter verschl&#252;sselt in der
		Datenhbank gespeichert werden (durch
		Administrator der Datenbank NICHT lesbar)
		Die Passw&#246;rter werden auf dem Server
		ver-/entschl&#252;sselt und im Klartext
		zu Ihnen/ihren Browser gesendet, sofern
		der Server nur das einfache http als
		Protokoll nutzt!!
	</font>
</p>
<p>
	Folgende Einstellungen f&#252;r ein Passwort
	kann gesetzt werden:
</p>
<ul>
	<li><em>Name</em>:
		Name f&#252;r das Passwort. Typischerweise
		kann hier die Verwendung (FTP/URL/Forum/PC etc.)
		des Passwortes genommen werden.
	</li>
	<li><em>Verzeichnis/Passwort</em>:
		Indikator der kennzeichnet ob
		das Element ein Passwort oder ein Ordner
		ist. Hinweis: ein nachtr&#228;gliches &#196;ndern
		ist nicht mehr m&#246;glich.
	</li>
	<li><em>Passwort/Kontrolle</em>:
		Die Passwort/Kontrolle wird benutzt
		um die Daten zu verschl&#252;sseln. Sobald
		Sie ein Passwort ansehen wollen werden Sie
		aufgefordert das Kennwort der Passwort/Kontrolle
		einzugeben, dieses Kennwort wird wieder zur
		Verschl&#252;sselung der Daten genutzt.
	</li>
	<li><em>Beschreibung</em>:
		Die Beschreibung f&#252;r dieses Passwort.
		Dieses Feld wird mit der Passwort/Kontrolle
		verschl&#252;sselt welches Sie eingeben um
		ein neues Passwort in der Datenbank
		verschl&#252;sselt zu speichern.
	</li>
</ul>
<p>
	Folgende Untermen&#252;s sind f&#252;r das Plugin
	"Passw&#246;rter" verf&#252;gbar:
	Aktionen, Ansicht, Einstellungen und
	die Hilfe.
</p>
<h3>Aktionen</h3>
<ul>
	<li><em>Hinzuf&#252;gen</em>:
		Diese Aktion f&#252;hrt den Nutzer
		in eine Eingabemaske in der er
		die Passwort und dessen Parameter
		eingeben kann.
	</li>
	<li><em>Mehrfachauswahl</em>:
		Diese Aktion gestattet dem Nutzer
		mehrere Passw&#246;rter gleichzeitig
		auszuw&#228;hlen (Ordner/Verzeichnisse
		sind bei dieser Option NICHT ausw&#228;hlbar)
		und diese zu l&#246;schen oder in einen
		separaten Ordner zu verschieben.
	</li>
	<li><em>Suche</em>:
		Diese Aktion erlaubt es dem Nutzer
		die Passw&#246;rter basierend auf ihren
		Namen zu durchsuchen.
	</li>
</ul>
<h3>Ansicht</h3>
<ul>
	<li><em>Aufklappen</em>:
		Diese Aktion veranla&#223;t das System
		alle Ordner zu &#246;ffnen und die darin
		verf&#252;gbaren Elemente (Passw&#246;rter)
		anzuzeigen. Dies ist nur f&#252;r die
		Baumstrukturierten Ansichten m&#246;glich.
	</li>
	<li><em>Zuklappen</em>:
		Diese Aktion veranla&#223;t das System
		nur die Elemente (entweder Ordner
		oder Passw&#246;rter) des gegenw&#228;rtig
		gew&#228;hlten Ordners anzuzeigen.
	</li>
	<li><em>Verzeichnisstruktur</em>:
		Diese Aktion veranla&#223;t das System in
		die Verzeichnis &#220;bersichtsansicht zu
		wechseln. Diese Ansicht zeigt die
		Passw&#246;rter in der von Yahoo bekannten
		Art und Weise einer Verzeichnisstruktur
		an.
		<br />
		Die Anzahl der dargestellten Spalten
		kann von dem Nutzer in den Einstellungen
		des "Passw&#246;rter" Plugins vorgenommen
		werden.
	</li>
	<li><em>Baumstruktur</em>:
		Diese Aktion veranla&#223;t das System
		in eine Ansicht zu wechseln die einem
		Explorer &#228;hnelt bzw. aus verschiedenen
		Dateimanager Programmen bekannt ist und
		den Inhalt im Design eines Dateisystem
		darstellen.
	</li>
</ul>
<h3>Einstellungen</h3>
<ul>
	<li><em>Bearbeiten</em>:
		Bearbeiten ihrer Passwort spezifischen
		Einstellungen. Sie k&#246;nnen die Spaltenanzahl
		einstellen wenn Sie eine Verzeichnis &#220;bersichtsansicht
		eingestellt haben, weiterhin  k&#246;nnen Sie
		einstellen ob Sie Javascript Popus zulassen
		die angezeigt werden sofern Sie &#252;ber einen Link
		fahren und 	Sie k&#246;nnen einstellen welche
		die Standardansicht Sie bevorzugen.
		(nur Verzeichnis oder Baumansichten)
	</li>
</ul>
';
$dictionary['insecureConnection']='Sie nutzen dieses Plugin &#252;ber eine ungesicherte Verbindung.
Denken Sie daran das der Datenverkehr abgeh&#246;rt werden k&#246;nnte!';
$dictionary['noServerCommunicationUsed']='Erzeugen des Passwortes geschieht Clientseitig (javascript),
ohne Kommunikation mit dem Server. Daher ist dieses Tool bedenkenlos in der Kommunikation mit
dem Server einsetzbar';
$dictionary['passPhraseMissing']='Passwort/Kontrolle fehlt!';
?>