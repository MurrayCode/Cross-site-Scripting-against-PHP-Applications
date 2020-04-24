<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.translate
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
$dictionary['baseTranslation']='Basis vertaling';
$dictionary['bothLanguageAndPluginNeeded']='Zowel taal als plugin zijn nodig';
$dictionary['currentTranslation']='Huidige vertaling';
$dictionary['item_help']='<p>
De vertaal-tool helpt je in het vertalen van de applicatie of in het
verbeteren van een bestaande vertaling
</p>
<p>
In de tools submap is een script aanwezig (<code>dict.sh</code>, met dank aan �yvind Hagen)
dat je helpt om een map-structuur op te zetten en copieert de
juiste bestanden naar de juiste plaats .
</p>
<p>
Tijdens het normale gebruik van de applicatie is het hetvolgende dat gebeurt: als een vertaling al bestaat, dan zoekt de applicatie eerst naar de vertaling in de opgegeven taal en daarna naar de vertaling in het engels. Een incomplete vertaling zal daarom altijd gedeeltelijk de vertaling tonen en gedeeltelijk de originele engelse tekst.
</p>
<h2>Hoe een bestaande vertaling aan te vullen?</h2>
<p>
Gebruik de vertaal-utility en selecteer zowel een plugin als een taal. Hierna krijg je een scherm te zien waarin de vertaal sleutel (deze wordt intern in het systeem gebruikt) wordt getoont, daarnaast de basis (in het engels) en daarnaast de huidge vertaling (of NOT SET!!! in het rood als er geen vertaling bestaat). De allerlaatste kolom stelt je in staat een vertaling aan te passen of toe te voegen.
</p>
<p>
Als je klaar bent met de vertaling kan je het resultaat bekijken of downloaden. Downloaden geeft je een scherm met de bestandsnaam <code>dictionary_XX.php</code> waarna het bestand opgeslagen dient te worden in de <code>i18n</code> directory van de applicatie of plugin welke je aan het vertalen bent. De naam en locatie (dus waar en onder welke naam je het bestand moet opslaan) staan bovenaan het initiele vertaalscherm.
</p>
<h2>Hoe een nieuwe vertaling te maken?</h2>
<p>
Op het overzichtsscherm, selecteer \'nieuw\' voor een nieuwe vertaling. Je krijgt hierna het vertaalscherm te zien. Als je klaar bent, bewaar je vertaling door het vervangen van de XX in de naam <code>dictionary_XX.php</code> door je taal-code.
De taal-code ziet er als volgt uit: XX_YYY waarbij XX voor de taal staat en YYY voor het dialect. De naam en locatie (dus waar en onder welke naam je het bestand moet opslaan) staan bovenaan het initiele vertaalscherm.
</p>
<p>
<p>
Wijzig nu het volgende bestand:
<code>framework/i18n/languages.php</code>
en voeg je taal toe. Voeg ook de vlag van je taal toen
(als deze nog niet bestaat) in de volgende map
<code>framework/view/pics/flags</code> in de vorm
<code>flag-XX_YYY.png</code> en je taal is hierna gekent binnen het systeem.
</p>';
$dictionary['item_title']='Vertaal';
$dictionary['languageToTranslate']='Taal';
$dictionary['percentComplete']='Percentage compleet';
$dictionary['pluginToTranslate']='Raamwerk/Plugin';
$dictionary['pluginTranslatorIndicator']='Plugin vertaler (jouw naam)';
$dictionary['saveTranslationToLocation']='Sla je bestand op de volgende locatie op';
$dictionary['stats']='Statistieken';
$dictionary['translationFileName']='Bestandsnaam van de vertaling';
$dictionary['translationKey']='Vertaal sleutel';

?>