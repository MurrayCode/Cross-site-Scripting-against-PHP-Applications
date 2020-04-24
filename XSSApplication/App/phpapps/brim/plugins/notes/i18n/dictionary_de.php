<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.notes
 * @subpackage i18n
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2005 Barry Nauta
 * @license http://opensource.org/licenses/gpl-license.php
 * This file is part of the Booby project.
 * The GNU Public License
 */
/*@german full translation Brim 1.0.3 / Nov2005 by Mario Glagow */

if (!isset ($dictionary))
{
	$dictionary=array();
}

$dictionary['item_title']='Notizen';
$dictionary['modifyNotePreferences']='Einstellungen der Notizen bearbeiten';
$dictionary['item_help']='
<p>
	Das "Notizen" Plugin erlaubt es Ihnen
	Ihre Notizen online zu verwalten. Schluss mit
	Zettelchen am Monitor :)
	Folgende Einstellungen f&#252;r eine Notiz k&#246;nnen
	gemacht werden:
</p>
<ul>
	<li><em>Name</em>:
		Name der Notiz.
	</li>
	<li><em>Ordner/Notiz</em>:
		Indikator welcher das Element als Ordner
		oder Notiz kennzeichnet. Hinweis: Nach
		dem Setzen des Indikators ist eine
		nachtr&#228;gliches &#228;ndern nicht mehr m&#18742;glich.
	</li>
	<li><em>&#214;ffentlich/Privat</em>:
		Indikator der festlegt ob eine Notiz
		&#246;ffentlich sein soll oder nur f&#252;r die
		eigenen Augen bestimmt ist.
		<br />
		Hinweis: Wenn eine Notiz &#246;ffentlich sein
		soll vergewissern Sie sich das die
		&#252;bergeordneten Elemente (Ordner) ebenfalls
		&#246;ffentlich sein m&#252;ssen. Die oberste Ebene
		ist per Standard immer &#246;ffentlich.
	</li>
	<li><em>Beschreibung</em>:
		Eine Beschreibung f&#252;r die Notiz wenn es von
		Ihnen gew&#252;nscht wird.
	</li>
</ul>
<p>
	Die Untermen&#252;s die f&#252;r das Plugin "Notizen"
	verf&#252;gbar sind lauten Aktionen, Ansicht,
	Einstellungen und Hilfe.
</p>
<h3>Aktionen</h3>
<ul>
	<li><em>Hinzuf&#252;gen</em>:
		Mit dieser Aktion gelangt der Nutzer
		in eine Eingabemaske in der die
		Notiz angelegt und zugeh&#246;rige
		Informationen eingegeben werden kann.
	</li>
	<li><em>Mehrfachauswahl</em>:
		Diese Aktion erlaubt dem Nutzer mehrere
		Notizen gleichzeitig auszuw&#228;hlen (Ordner/
		Verzeichnisse sind in dieser Option NICHT
		w&#228;hlbar) und diese zu l&#246;schen oder in ein
		anderes Verzeichnis zu verschieben.
	</li>
	<li><em>Suche</em>:
		Diese Aktion erlaubt den Nutzer seine
		Notizen nach Name oder Beschreibung zu
		durchsuchen.
	</li>
</ul>
<h3>Ansicht</h3>
<ul>
	<li><em>Aufklappen</em>:
		Diese Aktion veranla&#223;t das System
		alle Ordner/Verzeichnisse zu &#246;ffnen und
		alle vef&#252;gbaren Elemente/Notizen anzuzeigen.
		Dies ist nur in den Baum&#228;hnlichen Ansichten
		verf&#252;gbar.
	</li>
	<li><em>Zuklappen</em>:
		Diese Aktion veranla&#223;te das System
		nur die Elemente/Notizen des gegen-
		w&#228;rtigen Ordners anzuzeigen.
	</li>
	<li><em>Verzeichnisstruktur</em>:
		Diese Aktion veranla&#223;t das System in
		die Ansicht der Verzeichnis&#252;bersicht zu
		wechseln. Dies Ansicht zeigt die Notizen
		in der Yahoo typischen Art und Weise als
		Verzeichnis&#252;bersicht an.
		<br />
		Die Anzahl der angezeigten Spalten
		f&#252;r diese Ansicht kann vom Nutzer in
		den Einstellungen des "Notizen" Plugins
		vorgenommen werden.
	</li>
	<li><em>Baumansicht</em>:
		Diese Aktion veranla&#223;t das System
		in eine &#220;bersicht umzuschalten die
		einem Explorer bzw. anderen bekannten
		Dateimanager Programmen &#228;hnlich ist und
		zeigt die Notizen im Design eines
		Dateisystems an.
	</li>
	<li><em>Freigaben anzeigen</em>:
		Zeigt alle &#246;ffentlichen Notizen
		aller Benutzer zusammen mit ihren
		Notizen an.
	</li>
	<li><em>Private anzeigen</em>:
		Zeigt nur Ihre Notizen an (alternativ
		zu der Option "Freigaben anzeigen")
	</li>
</ul>
<h3>Einstellungen</h3>
<ul>
	<li><em>Bearbeiten</em>:
		Bearbeiten Sie ihre Notizen betreffenen
		Einstellungen. Sie k&#246;nnen die Anzahl
		der Spalten in der Verzeichnis &#220;bersichts-
		ansicht selbst bestimmen. Weiterhin k&#246;nnen
		Sie einstellen ob Sie Javascript Popups
		angezeigt bekommen m&#246;chten wenn Sie &#252;ber
		einen Link mit der Maus fahren. Und Sie k&#246;nnen
		einstellen welche Ansicht f&#252;r Notizen Sie
		bevorzugen. (nur f&#252;r Verzeichnis- oder
		Baumansichten)
	</li>
</ul>
';
?>