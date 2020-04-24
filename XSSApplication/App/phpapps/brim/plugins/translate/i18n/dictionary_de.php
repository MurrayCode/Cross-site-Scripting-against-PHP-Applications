<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.translate
 * @subpackage i18n
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2005 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 */
/*@german full translation Brim 1.0.3 / Nov2005 by Mario Glagow */

if (!isset ($dictionary))
{
	$dictionary=array();
}
$dictionary['item_title']='&#220;bersetzen';
$dictionary['pluginToTranslate']='Framework/Plugin';
$dictionary['languageToTranslate']='Sprache';
$dictionary['bothLanguageAndPluginNeeded']='Beides, Sprache und Plugin erforderlich';
$dictionary['translationKey']='&#220;bersetzungsschl&#1852;ssel';
$dictionary['baseTranslation']='Grund&#252;bersetzung';
$dictionary['currentTranslation']='Aktuelle &#220;bersetzung';
$dictionary['percentComplete']='Prozent komplett';
$dictionary['pluginTranslatorIndicator']='Plugin &#252;bersetzt durch (ihr Name)';
$dictionary['translationFileName']='&#220;bersetzung Dateinname ';
$dictionary['saveTranslationToLocation']='Sichern ihrer Datei nach';
$dictionary['stats']='Statistik';

$dictionary['item_help']='
<p>
	Das &#220;bersetzungswerkzeug hilft Ihnen
	dabei Brim in ihrer Sprache zu &#252;bersetzen oder
	eine bestehende Installation von Brim zu
	aktualisieren.
</p>
<p>
	Im Unterordner "Tools" befindet sich ein
	Skript <code>dict.sh</code> (Dank an
	&#216;yvind Hagen) welches eine Verzeichnis-
	struktur generiert und nach der &#220;bersetzung
	die Datei an den richtigen Ort kopiert
	werden kann. Das Skript ist selbsterkl&#228;rend.
</p>
<p>
	W&#228;hrend der normalen Nutzung der Appliaktion
	wird die &#220;bersetzung wie folgt vom System
	ausgef&#252;hrt:
	Wenn eine &#220;bersetzungsdatei vorhanden ist sucht
	Brim anhand ihrer Spracheinstellungen diese Datei.
	Sofern nicht vorhanden w&#228;hlt Brim die Standarddatei
	der englischen &#220;bersetzung aus. Bei einer unvollst&#1828;ndigen
	&#220;bersetzungsdatei werden die fehlenden W&#1846;rter durch
	die englischen W&#246;rter/Zeichenketten angezeigt.
</p>
<h2>Wie aktualisiere ich eine bestehende &#220;bersetzung</h2>
<p>
	Mit dem &#220;bersetzungswerkzeug w&#1828;hlen Sie
	das zu &#252;bersetzende Plugin und Ihre Sprache aus.
	In der sich &#246;ffnenden Ansicht in der der &#220;bersetzungs-
	schl&#252;ssel angezeigt wird, (Der &#220;bersetzungsschl&#51004;ssel
	wird intern vom System ben&#246;tigt), die Standard&#252;bersetzung
	(englisch), die gegenw&#228;rtige &#220;bersetzung in ihrer Sprache
	(oder in roter Schrift \'NOT SET!!!\' wenn keine
	&#220;bersetzung f&#1852;r dieses Wort/Zeichenkette existiert)
	und ein Texteingabefeld welches Ihnen gestattet
	das Element zu bearbeiten/komplettieren.
</p>
<p>
	Wenn Sie ihre &#220;bersetzung fertiggestellt haben
	k&#246;nnen Sie sich das Ergebnis in einer Vorschau
	ansehen oder als Datei herunterladen.
	Die zum herunterladen angebotene Datei tr&#228;gt den
	Dateinamen \'dictionary_XX.php\' und muss in das
	i18n Verzeichnis des betreffenen Plugins (Ausnahme
	sind &#220;bersetzungen f&#1852;r das Framework) kopiert werden.
	Der Ort und Dateiname der betreffenen Datei wird
	oben in der &#220;bersetzungsansicht angezeigt.
</p>
<h2>Wie erstelle ich eine neue &#220;bersetzung</h2>
<p>
	In der &#220;bersichtsansicht w&#1828;hlen Sie
	\'Neu\' f&#252;r die &#220;bersetzung. Sie werden
	in die &#220;bersetzungsansicht geleitet.
	Wenn Sie ihre &#220;bersetzung abgeschlossen haben
	sichern Sie diese und ersetzen die XX im Dateinamen
	\'dictionary_XX.php\' durch ihren L&#228;ndercode (de = deutsch).
	Der L&#228;ndercode setzt sich nach folgendem Weg zusammen:
	XX_YYY wobei XX f&#252;r die Sprache und YYY f&#252;r den
	Dialekt (z.Bsp. PT_BR ist portugiesich, brasilianischen
	Dialekt). Der Ort und Dateiname der betreffenen Datei wird
	oben in der &#220;bersetzungsansicht angezeigt.
</p>
<p>
	Bearbeiten Sie nun die Datei
	\'framework/i18n/languages.php\' und
	f&#252;gen Sie Ihre Sprache ein. F&#252;gen Sie
	,sofern sie nicht existiert, ihre Landes-
	fahne in das Verzeichnis (gleiches Format/Gr&#246;&#223;e
	wie die vorhanden Flaggen benutzen)
	\'framework/view/pics/flags\' in dem
	Format \'flag-XX_YYY.png\' ein. Die
	neue Sprache wird automatisch in der
	Willkommens Ansicht angezeigt.
</p>
';
?>