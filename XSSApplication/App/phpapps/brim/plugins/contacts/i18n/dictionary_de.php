<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.contacts
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
$dictionary['address']='Adresse';
$dictionary['alias']='Alias';
$dictionary['birthday']='Geburtstag';
$dictionary['clickHere']='Hier Klicken';
$dictionary['email']='E-Mail';
$dictionary['email1']='E-Mail 1';
$dictionary['email2']='E-Mail 2';
$dictionary['email3']='E-Mail 3';
$dictionary['email_home']='Email (Zuhause)';
$dictionary['email_other']='Email (andere)';
$dictionary['email_work']='Email (Arbeit)';
$dictionary['faximile']='Fax.';
$dictionary['item_help']='<p>
	Das Plugin "Kontakte" erlaubt Ihnen ihre
	Kontakte online zu organisieren. Folgende
	Parameter eines Kontaktes k&#246;nnen Sie einstellen:
</p>
<ul>
	<li><em>Name</em>:
		Name der Kontaktperson
	</li>
	<li><em>Ordner/Kontakt</em>:
		Indikator der festlegt ob das Element
		ein Ordner oder ein Kontakt ist.
		Hinweis: Bitte denken Sie daran wenn diese
		Option einmal gesetzt wurde kann sie
		nachtr&#228;glich nicht mehr ver&#228;ndert werden.
	</li>
	<li><em>&#214;ffentlich/Privat</em>:
		Indikator der festlegt ob das Element
		&#246;ffentlich sichtbar ist oder nur f&#252;r ihre Augen
		bestimmt ist.
		<br />
		Hinweis: Denken Sie daran wenn Sie ein
		spezielles Element &#246;ffentlich machen, dass
		das &#252;bergeordnete Element (meist Ordner)
		ebenfalls &#246;ffentlich gemacht werden muss.
		(die oberste Ebene ist per Standardeinstellungen
		&#246;ffentlich)
	</li>
	<li><em>Tel.(privat)</em>:
		Private Telefonnummer des Kontaktes
	</li>
	<li><em>Tel.(dienst.)</em>:
		Dienstliche Telefonnummer des Kontaktes.
	</li>
	<li><em>Fax.</em>:
		Faxnummer des Kontaktes.
	</li>
	<li><em>E-Mail 1</em>:
		Sie k&#246;nnen bis zu 3 E-Mail Adressen
		pro Kontakt hinzuf&#252;gen.
		Diese ist die erste E-Mail Adresse f&#252;r
		den Kontakt.
	</li>
	<li><em>E-Mail 2</em>:
		Sie k&#246;nnen bis zu 3 E-Mail Adressen
		pro Kontakt hinzuf&#252;gen.
		Diese ist die zweite E-Mail Adresse f&#252;r
		den Kontakt.
	</li>
	<li><em>E-Mail 3</em>:
		Sie k&#246;nnen bis zu 3 E-Mail Adressen
		pro Kontakt hinzuf&#252;gen.
		Diese ist die dritte E-Mail Adresse f&#252;r
		den Kontakt.
	</li>
	<li><em>Internetadresse/URL 1</em>:
		Sie k&#246;nnen bis zu 3 Internetadressen (URL)
		pro Kontakt hinzuf&#252;gen.
		Diese ist die erste Internetadressen (URL) f&#252;r
		den Kontakt.
	</li>
	<li><em>Internetadresse/URL 2</em>:
		Sie k&#246;nnen bis zu 3 Internetadressen (URL)
		pro Kontakt hinzuf&#252;gen.
		Diese ist die zweite Internetadressen (URL) f&#252;r
		den Kontakt.
	</li>
	<li><em>Internetadresse/URL 3</em>:
		Sie k&#246;nnen bis zu 3 Internetadressen (URL)
		pro Kontakt hinzuf&#252;gen.
		Diese ist die dritte Internetadressen (URL) f&#252;r
		den Kontakt.
	</li>
	<li><em>Berufsbezeichnung</em>:
		Berufsbezeichnung f&#252;r diese Person
	</li>
	<li><em>Alias/Kosename</em>:
		Der Spitz/Kosename f&#252;r die Person (kann in Kombination mit der Suche genutzt werden)
	</li>
	<li><em>Firma</em>:
		Die Firma/Unternehmen in der die Person arbeitet
	</li>
	<li><em>Adresse</em>:
		Privatadresse der Person
	</li>
	<li><em>Firmenadresse</em>:
		Adresse der Firma/Unternehmens in der
		die Person besch&#228;ftigt ist.
	</li>
	<li><em>Beschreibung</em>:
		Beschreibung oder auch weitere Notizen zu der Person.
	</li>
</ul>
<p>
	Folgende Untermen&#252;s sind f&#252;r einen Kontakt verf&#53052;gbar
	Aktionen, Ansicht, Sortieren und Einstellungen.
</p>
<h3>Aktionen</h3>
<ul>
	<li><em>Hinzuf&#252;gen</em>:
		Mit dieser Aktion gelangen Sie in eine
		Eingabemaske in der Sie einen neuen
		Kontakt anlegen k&#246;nnen.
	</li>
	<li><em>Mehrfachauswahl</em>:
		Mit dieser Aktion k&#246;nnen Sie mehrere
		Kontakte gleichzeitig ausw&#228;hlen (Ordner
		sind bei dieser Aktion nicht w&#228;hlbar)
		und sie verschieben (in einen anderen Ordner)
		 bzw. l&#246;schen.
	</li>
	<li><em>Importieren</em>:
		Mit dieser Aktion k&#246;nnen sie ihre
		Kontakte (z Bsp. aus Outlook) importieren.
		Derzeit wird nur das Opera vCards Format
		unterst&#252;tzt.
		<br />
		Beim Importieren k&#246;nnen Sie festlegen
		ob der Indikator (privat/&#246;ffentlich)
		gesetzt werden soll. Alle importierten Kontakte
		werden mit dem gew&#228;hlten Indikator importiert.
		<br />
		Das Importieren in einen speziellen Ordner
		ist m&#246;glich, dazu nur in den Ordner wechseln
		in die Importfunktion anklicken.
	</li>
	<li><em>Exportieren</em>:
		Mit dieser Aktion kann der Nutzer seine Kontakte
		mittels Opera oder vCards Format exportieren (diese
		k&#246;nnen dann in die meisten E-Mail Programme/Adressb&#252;cher
		importiert werden.
	<li><em>Suche</em>:
		Diese Aktion erlaubt es dem Nutzer seine Kontakte
		nach Name, Alias/Kosename, Adressen oder Beschreibung
		zu durchsuchen.
	</li>
</ul>
<h3>Ansicht</h3>
<ul>
	<li><em>Aufklappen</em>:
		Diese Aktion veranla&#223;t das System alle
		Ordner zu &#246;ffnen  und deren verf&#252;gbaren
		Elemente anzuzeigen. Dies ist aber nur
		f&#252;r die Baumansichten verf&#252;gbar.
	</li>
	<li><em>Zuklappen</em>:
		Diese Aktion veranla&#223;t das System nur
		die Elemente (nur Ordner oder Kontakte)
		des gegenw&#228;rtigen Ordners anzuzeigen.
	</li>
	<li><em>Verzeichnisstruktur</em>:
		Diese Aktion veranla&#223;t das System in die
		&#220;bersichtsansicht der Verzeichnisstruktur
		zu wechseln. Diese Ansicht zeigt die Kontakte
		in der Yahoo typischen Ansichtsart an.
		<br />
		Die Anzahl der angezeigten Spalten in der
		Ansicht kann in den Einstellungen des Kontakt
		Plugins angepa&#223;t werden.
	</li>
	<li><em>Baumansicht</em>:
		Diese Aktion veranla&#223;t das System in die
		&#220;bersichtsansicht der Baumstruktur zu
		wechseln. Diese Ansichtsart ist Ihnen durch
		verschiedene Explorer bzw. Dateimanagerprogramme
		bekannt und zeigen die Ansicht in einer
		Dateisystem Darstellung.
	</li>
	<li><em>Linien basierend</em>:
		Eine andere Art Kontakte anzuzeigen.
		Diese Ansicht zeigt jeden Kontakt auf
		einer eigenen Zeile/Linie an, zus&#228;tzlich
		mit Details zu dem Kontakt.
	<li><em>Freigegebene Kontakte anzeigen</em>:
		Alle freigegebenen Kontakte anderer Nutzer
		werden zusammen mit den eigenen Kontakten angezeigt.
		(jenachdem ob sie &#246;ffentlich oder privat sind)
	</li>
	<li><em>Nur Eigene Kontakte anzeigen</em>:
		Zeigt nur die eigenen Kontakte an.
		(alternativ zu Freigegebene Kontakte anzeigen)
	</li>
</ul>
<h3>Sortieren</h3>
<ul>
	<li><em>Alias</em>:
		Sortiert die Kontakte nach Alias/Spitznamen.
	</li>
	<li><em>E-Mail 1</em>:
		Sortiert die Kontakte nach der ersten E-Mail Adresse.
	</li>
	<li><em>Firma</em>:
		Sortiert nach der Firmenadresse des Kontaktes.
	</li>
</ul>
<h3>Einstellungen</h3>
<ul>
	<li><em>Bearbeiten</em>:
		Unter "Bearbeiten" k&#246;nnen Einstellungen
		bzgl. der Kontakte gemacht werden.
		Sie k&#246;nnen die Spaltenanzahl einstellen
		wenn Sie die Ansicht "Verzeichnisstruktur"
		gew&#228;hlt haben. Weiterhin k&#246;nnen Sie einstellen
		ob Javascript Popup Fenster mit weiteren
		Details zum Kontakt angezeigt werden sollen
		und Sie k&#246;nnen einstellen welche die Standard-
		ansicht f&#252;r die Kontakte sein soll.
		(nur f&#252;r Verzeichnis-, Baumstruktur oder der Linien
		basierenden Ansicht).
	</li>
</ul>
';
$dictionary['item_title']='Kontakte';
$dictionary['job']='Berufsbezeichnung';
$dictionary['mobile']='Mobil';
$dictionary['modifyContactPreferences']='Kontakteinstellungen &#228;ndern';
$dictionary['org_address']='Gesch&#228;ftsadresse';
$dictionary['organization']='Firma';
$dictionary['tel_home']='Tel.(privat)';
$dictionary['tel_work']='Tel.(dienst.)';
$dictionary['webaddress']='Internet URL';
$dictionary['webaddress_home']='Webadressen (Zuhause)';
$dictionary['webaddress_homepage']='Webadressen (Homepage)';
$dictionary['webaddress_work']='Webadressen (Arbeit)';

?>