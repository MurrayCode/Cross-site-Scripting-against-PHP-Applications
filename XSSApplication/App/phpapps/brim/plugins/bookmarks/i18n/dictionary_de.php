<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.bookmarks
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
$dictionary['autoAppendProtocol']='Automatisch vorgesetzt \'http://\' wenn die URL kein Protokol enth&#228;lt.';
$dictionary['deleteFavicon']='L&#246;sche Favicon';
$dictionary['favicon']='Favicon';
$dictionary['faviconDeleted']='Icon del&#246;scht. Dr&#252;cken Sie Modifizieren um das Ergebnis zu speichern.';
$dictionary['faviconFetched']='Icon erhalten. Dr&#252;cken Sie Modifizieren um das Ergebnis zu speichern.';
$dictionary['fetchingFavicon']='Hole Favicon';
$dictionary['installationPathNotSet']='<p>
	Ihr Installationspfad wurde nicht gesetzt,
	dieser Pfad muss f&#252;r diese Funktion angegeben sein.
	Bitte informieren Sie den Administrator der dieses beheben kann.
</p>';
$dictionary['item_help']='<p>
	Das Lesezeichen Plugin erlaubt Ihnen ihre
	Lesezeichen / Favoriten online zu verwalten.
</p>
<p>
	Klick auf das Verzeichnis / Element Icon vor
	der Linie um einen Link zu bewegen/editieren/l&#246;schen.
</p>
<p>
	Um einen Link in ein anderes Verzeichnis bzw. Ebene h&#246;her zu bewegen,
	klicken Sie auf "Bearbeiten"  => Verschieben => und dann auf das Verzeichnis
	wo Sie den Link gern haben m&#246;chten.
</p>
<p>
	Folgende Parameter eines Lesezeichen k&#246;nnen eingestellt werden:
</p>
<ul>
	<li><em>Name</em>:
		Name des Links. zum Beispiel: [nauta.be]
		f&#252;r meine pers&#246;nliche Homepage.
	</li>
	<li><em>Verzeichnis/Lesezeichen</em>:
		Indikator der bestimmt ob das Element als
		Ordner oder Lesezeichen. Dies sollten Sie
		sich merken da es nachtr&#228;glich nicht ver&#228;ndert werden kann.
	</li>
	<li><em>&#214;ffentlich/Privat</em>:
		Indikator der bestimmt ob das Element &#246;ffentlich
		oder nur f&#252;r Ihre Augen angezeiogt wird.
		<br />
		Hinweis: Wenn Sie ein spezielles Element &#246;ffentlich
		machen wollen muss das &#252;bergeordnete Element (Ordner)
		ebenfalls &#246;ffentlich sein.
		(die oberste Ebene/Root ist standardm&#228;&#223;ig &#18422;ffentlich)
	</li>
	<li><em>URL</em>:
		Die URL des Lesezeichens. Diese URL muss mit dem Indikator
		des verwendeten Protokolls beginnen (http:// or
		ftp:// )um durch Brim richtig dargestellt zu werden.
	</li>
	<li><em>Beschreibung</em>:
		Die Beschreibung f&#252;r das Lesezeichen (wenn gew&#252;nscht)
	</li>
	</ul>
	<p>
		Die Untermen&#252;s die f&#252;r die Lesezeichen verf&#53052;gbar sind
		wie Aktionen, Ansicht, Sortieren, Einstellungen und Hilfe.
	</p>
	<h3>Aktionen</h3>
	<ul>
	<li><em>Hinzuf&#252;gen</em>:
		Diese Aktion bef&#228;higt den Nutzer mittels einer Eingabemaske
		die Parameter seines Lesezeichens anzulegen.
		Hinweis: Diese URL muss mit dem Indikator
		des verwendeten Protokolls beginnen (http:// or
		ftp:// )um durch Brim richtig dargestellt zu werden.

	</li>
	<li><em>Mehrfachauswahl</em>:
		Diese Aktion erlaubt dem Nutzer mehrere Lesezeichen
		gleichzeitig auszuw&#228;hlen (Ordner sind bei dieser Option
		nicht ausw&#228;hlbar)und zu l&#246;schen bzw. alles in einem
		spezifischen Ordner zu bewegen.
	</li>
	<li><em>Importieren</em>:
		Diese Aktion erlaubt den Nutzer Lesezeichen zu importieren.
		Zur Zeit werden der Opera Browser und Browser der
		Netscape/Mozilla/Firefox Familie unterst&#252;tzt.
		Wenn Sie ihre Lesezeichen/Favoriten vom IE importieren
		m&#246;chten m&#252;ssen Sie diese vorerst vom IE exportieren.
		Dieses Vorgehen erstellt eine Netscape Bookmarkdatei
		die sie dann in Brim importieren k&#246;nnen.
		<br />
		Beim Import kann der Nutzer auch festzulegen
		ob das Sichtbarkeitsflag: Privat oder &#214;ffentlich
		gesetzt wird. Alle Lesezeichen werden mit dem gesetzten
		Sichtbarkeitsflag importiert.
		<br />
		Der Import in ein spezielles Verzeichnis ist m&#246;glich,
		dazu in dieses Verzeichnis wechseln und klicken Sie
		auf den import Link.
	</li>
	<li><em>Exportieren</em>:
		Diese Aktion erlaubt dem Nutzer die Lesezeichen im
		Opera- bzw. Netscape Format zu exportieren
		(dieses ist mit Mozilla/Firefox kompatibel).
		Wenn Sie die Lesezeichen f&#252;r den IE exportieren m&#246;chten,
		m&#252;ssen Sie diese im Netscape Format exportieren um sie
		im IE anschlie&#223;end importieren zu k&#2038;nnen.
	</li>
	<li><em>Suche</em>:
		Diese Aktion erlaubt den Nutzer Lesezeichen
		nach Namen, URL oder Beschreibung zu durchsuchen.
	</li>
	</ul>
	<h3>Ansicht</h3>
	<ul>
	<li><em>Aufklappen</em>:
		Diese Funktion weist das System an alle
		Ordner auzuklappen und darunter liegende Eintr&#228;ge anzuzeigen.
		Dies ist nur f&#252;r Ansicht "Baumstruktur" verf&#252;gbar.
	</li>
	<li><em>Zuklappen</em>:
		Diese Funktion weist das System an nur
		die Elemente (entweder Ordner oder Lesezeichen)
		des gegenw&#228;rtigen gew&#228;hlten Ordners anzuzeigen.
	</li>
	<li><em>Verzeichnisstruktur</em>:
		Diese Funktion weist das System an auf die
		Ansicht der Verzeichnisstruktur zu wechseln.
		Die Ansicht zeigt die Lesezeichen in der von Yahoo
		bekannten Art und Weise in Spaltenansicht an.
		<br />
		Die Nummer der Spalten f&#252;r die Ansicht kann in
		den speziellen Einstellungen des Lesezeichen
		Plugins eingestellt werden.
	</li>
	<li><em>Baumstruktur</em>:
		Diese Funktion weist das System an in eine
		Explorertypische Ansicht zu wechseln. Diese Ansicht
		stellt &#228;hnlich wie eine Menge andere Dateimanager
		den Inhalt als eine Art Dateisystem dar.
	</li>
	<li><em>Zeige &#214;ffentliche</em>:
		Hier werden die freigegebenen Lesezeichen anderer Nutzer
		,gemixt mit ihren Eigenen angezeigt(jenachdem ob sie
		&#246;ffentlich oder privat sind).
	</li>
	<li><em>Zeige Eigene</em>:
		Zeigt nur die eigenen Lesezeichen (als Gegenteil
		zu "Zeige &#214;ffentliche")
	</li>
	</ul>
	<h3>Sortieren</h3>
	<ul>
	<li><em>Zuletzt besucht</em>:
		Zeigt die Lesezeichen anhand der Reihenfolge
		wie sie zuletzt besucht wurden.
	</li>
	<li><em>Meist besucht</em>:
		Zeigt die Lesezeichen anhand der Reihenfolge
		wie sie am meisten besucht wurden
	</li>
	<li><em>Zuletzt erstellt</em>:
		Zeigt die Lesezeichen anhand der Reihenfolge
		wie sie zuletzt erstellt wurden. Das "Neuste"
		Lesezeichen steht dabei an erster Stelle.
	</li>
	<li><em>Zuletzt ge&#228;ndert</em>:
		Zeigt die Lesezeichen anhand der Reihenfolge
		wie sie zuletzt ge&#228;ndert wurden.
	</li>
	</ul>
	<h3>Einstellungen</h3>
	<ul>
	<li><em>Bearbeiten</em>:
		Bearbeiten ihrer Lesezeichen spezifischen Einstellungen.
		Sie k&#246;nnen die Spaltenanzahl f&#252;r Lesezeichen festlegen
		wenn diese in der Verzeichnis&#252;bersicht Struktur angezeigt
		werden. Weiterhin k&#246;nne Sie einstellen ob Sie Java Skript Popups,
		welche angezeigt werden wenn Sie mit der Maus &#252;ber die Links gehen,
		angezeigt bekommen m&#246;chten. Zus&#228;tzlich k&#26934;nnen Sie noch die Ansicht
		ihrer Lesezeichen festlegen (sollte eine Ordner- oder Baumansicht sein)
		und ober bei Klicken auf ein Lesezeichen der Inhalt im gegenw&#228;rtigen oder
		in einem neuen Fenster angezeigt werden soll.
	</li>
	<li><em>Ihre &#246;ffentlichen Lesezeichen</em>:
		Das Klicken auf diesen Link zeigt alle ihre &#246;ffentlichen
		Lesezeichen. Der Link der sich dann &#246;ffnet ist &#246;ffentlich
		verf&#252;gbar, Sie k&#246;nnen diesen versenden oder auf diesem
		Weg freigeben. Dieser Link kann auch in einer anderen
		Webseite integriert werden und w&#252;rde ihre Homepage
		aufpeppen.
		<br />
		Hinweis: Wenn sie ein spezielles Element/Lesezeichen
		(unter einem Ordner) ver&#246;ffentlichen wollen muss der Ordner auch
		&#246;ffentlich freigegeben werden.
	</li>
	<li><em>Sidebar</em>:
		Dieser Link &#246;ffent eine neue Seite in der Sie
		Brim in ihrem Browser integrieren k&#246;nnen.
		Unterst&#252;tzt werden:
		(nur Opera, Mozilla, Firefox and Netscape).
	</li>
	<li><em>Quickmark</em>:
		Rechtsklick auf den folgenden Link um
		diese unter Bookmarks/Favoriten in Ihrem
		<b>Browser</b> hinzuzuf&#252;gen. Sie k&#246;nnen jederzeit
		die Lesezeichen nutzen die Seite wird sie automatisch
		in die Brim Lesezeichen einf&#252;gen.(in der obersten Ebene/Wurzel).
		<br />
		Bitte klicken Sie "OK" wenn
		nachgefragt wird ob das Lesezeichen hinzugef&#252;gt werden soll
		- Code der Bookmarks aufnimmt wird von manchen Browsern nicht korrekt ausgef&#252;hrt
	</li>
</ul>
';
$dictionary['item_quick_help']='Klicken Sie auf das Icon <br />vor dem Lesezeichen, um das <br />Element zu editieren/l&#246;schen/verschieben
<br /><br />Um einen Link in ein anderes Verzeichnis bzw. Ebene h&#246;her zu bewegen
<br />klicken Sie auf "Bearbeiten"  => Verschieben => und dann auf das Verzeichnis wo Sie den Link gern haben m&#246;chten.';
$dictionary['item_title']='Lesezeichen / Bookmarks';
$dictionary['javascriptTree']='Javascript Baum';
$dictionary['locatorMissing']='Verzeichnis f&#252;r den Link muss angegeben werden';
$dictionary['modifyBookmarkPreferences']='&#196;ndern der Lesezeichen Einstellungen';
$dictionary['noFaviconFound']='Kein Favicon gefunden';
$dictionary['quickmark']='Schnellmarkierung';
$dictionary['quickmarkExplanation']='<p>
	Rechtsklick auf den folgenden Link um
	diese unter Bookmarks/Favoriten in Ihrem
	<b>Browser</b> hinzuzuf&#252;gen. Sie k&#246;nnen jederzeit
	die Lesezeichen nutzen die Seite wird sie automatisch
	in die Brim Lesezeichen einf&#252;gen.
	<br />
	<br />
	<font size="-2">Bitte klicken Sie "OK" wenn
	nachgefragt wird ob das Lesezeichen hinzugef&#252;gt werden soll
	- Code der Bookmarks aufnimmt wird von manchen Browsern nicht korrekt ausgef&#252;hrt.</font><br />
</p>';
$dictionary['showBookmarkDetails']='Link Details anzeigen';
$dictionary['showFavicons']='Zeige Favicons';
$dictionary['sidebar']='Sidebar';
$dictionary['yourPublicBookmarks']='Ihre ver&#246;ffentlichen Lesezeichen';

?>