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
$dictionary['installationPathNotSet']='<p>
Je installatie-pad is niet gezet, dit pad is nodig
voor de snelmarkering functionaliteit. Vraag je systeem
administrator om dit te corrigeren
</p>';
$dictionary['item_help']='<p>
	De bladwijzer plugin helpt je om je
	bladwijzers/favorieten online te beheren.
</p>
<p>
	De volgende parameters van een bladwijzer kunnen
	gezet worden:
</p>
<ul>
	<li><em>Naam</em>:
		De naam van een link, bijvoorbeeld: [nauta.be]
		voor mijn persoonlijke pagina.
	</li>
	<li><em>Map/Bladwijzer</em>:
		Indicatie of we een map of een bladwijzer
		willen toevoegen. Let op, zodra dit gezet is
		kan dit niet meer gewijzigd worden!
	</li>
	<li><em>Publiek/prive</em>:
		Indicator of deze item publiek of prive is.
		<br />
		Let op dat als je een specifiek item publiek
		wilt tonen, dat al zijn ouders (hierarchische
		mappen structuur) ook publiek moeten zijn!
	</li>
	<li><em>URL</em>:
		De URL van de bladwijzer. Deze moet starten met
		een protocal indicator zoals http:// of ftp://
	</li>
	<li><em>Omschrijving</em>:
		De omschrijving van deze bladwijzer
	</li>
	</ul>
	<p>
		De volgende submenus zijn beschikbaar voor bladewijzers:
		Acties, Zicht, Sorteer, Voorkeuren en Help.
	</p>
	<h3>Acties</h3>
	<ul>
	<li><em>Add</em>:
		Deze actie presenteert de gebruiker een
		input formulier waarin de bladwijzer zijn
		parameters kunnen worden ingevuld.
	</li>
	<li><em>Selecteer meerdere</em>:
		Deze actie staat de gebruiker toe om
		meerdere bladwijzers tegelijkertijd te
		selecteren en hier dan &#233;&#233;n specifieke
		actie (zoals verplaatsen of verwijderern)
		op toe te passen.
	</li>
	<li><em>Importeer</em>:
		Deze actie staat de gebruiker to om bladwijzers
		te importeren. Op het moment zijn de Opera browser
		en alle browsers van de Mozilla familie (Netscape,
		Mozilla, Firefox) ondersteunt.
		Als je je bladwijzers/favorieten van Internet Explorer
		wilt importeren moet je vanuit Internet Explorer
		eerst de bladwijzers exporteren als HTML bestand.
		Dit bestand kan hierna als Netscape bestand geimporteerd
		worden in Brim.
		<br />
		Vlak voor je de importeer actie begint kan je ook
		de publieke/prive optie zetten. Alle bladwijzers worden
		met deze optie geimporteerd.
		<br />
		Je kan ook al je bladwijzers in een specifieke map
		importeren. Ga eerst naar de map toe en importeer van daar uit.
	</li>
	<li><em>Exporteer</em>:
		Deze actie staat de gebruiker toe om bladwijzers te
		exporteren naar Opera of Netscape (Mozilla, Firefox)
		formaat.
		Als je je bladwijzers naar Internet Explorer wilt
		exporteren, dan moet je ze eerst naar Netscape formaat
		exporteren en dan vanuit Internet Explorer dit bestand
		importeren.
	</li>
	<li><em>Zoek</em>:
		Hiermee kan je bladwijzers zoeken op basis van
		naam, URL of beschrijving.
	</li>
	</ul>
	<h3>Zicht</h3>
	<ul>
	<li><em>Uitklappen</em>:
		Hiermee open je alle mappen en alle bladwijzers/mappen
		worden zichtbaar. Dit is alleen van toepassing
		op de \'Boomstructuur\'
	</li>
	<li><em>Collapse</em>:
		Hiermee sluit je alle mappen en alleen de mappen
		die direct onder root zijn zijn hierna
		zichtbaar. Dit is alleen van toepassing
		op de \'Boomstructuur\'
	</li>
	<li><em>Directory structuur</em>:
		Hiermee krijg je je bladwijzers te zien in een
		manier die lijkt op de manier waarop Yahoo!
		zijn bladwijzers toont.
		<br />
		Het aantal kolommen voor dit overzicht kan
		in de voorkeuren van de bladwijzers plugin
		gezet worden.
	</li>
	<li><em>Boom structuur</em>:
		Hiermee krijg je een overzicht van je bladwijzers
		die lijkt op de manier waarop de Verkenner van
		Microsoft en vele andere bestandbeheerders
		hun overzicht tonen.
	</li>
	<li><em>Publieke monde</em>:
		Toon alle publieke bladwijzers van alle gebruikers
		alsook al je eigen bladwijzers (ongeacht het feit of
		deze publiek of prive zijn).
	</li>
	<li><em>See owned</em>:
		Toon alleen je eigen bladwijzers.
	</li>
	</ul>
	<h3>Sorteer</h3>
	<ul>
	<li><em>Laatst bezocht</em>:
		Toont de bladwijzers gesorteerd op
		de laatst bezochte.
	</li>
	<li><em>Meest bezocht</em>:
		Toont de bladwijzers gesorteerd op
		de meest bezochte.
	</li>
	<li><em>Laatst aangemaakt</em>:
		Toont de bladwijzers gesorteerd op
		de laatst aangemaakte.
	</li>
	<li><em>Laatst gewijzigd</em>:
		Toont de bladwijzers gesorteerd op
		de laatst aangewijzigde.
	</li>
	</ul>
	<h3>Voorkeuren</h3>
	<ul>
	<li><em>Wijzig</em>:
		Wijzigt je bladwijzer specifieke voorkeuren.
		Je kan hier bijvoorbeeld het aantal kolonmmen
		in de directory structuur wijzigen, of het feit
		of je javascript popups wilt hebben als je met
		de muis over de links gaat.
	</li>
	<li><em>Jouw publieke bladwijzers</em>:
		Deze link toont al jouw publieke bladwijzers.
		De geopende link is publiek beschikbaar en je
		kunt deze dus naar anderen doorsturen met wie je
		je links wilt delen.
		Deze link staat je ook toe om je bladwijzers
		in een webpagina op te nemen: Brim powered!
	</li>
	<li><em>Sidebar</em>:
		Deze link neemt je mee naar een nieuwe pagina
		waarin je een sidebar kan activeren
		(werkt alleen met Opera, Mozilla, Firefox en Netscape)
	</li>
	<li><em>Quickmark</em>:
		Klik met de RECHTER muisknop op de QuickMark
		link en voeg deze toe aan de bladwijzers/favorieten
		van je <b>browser</b>.
		Elke keer als je deze link aanklikt wordt de pagina
		die je op dat moment aan het bekijken bent
		automatisch toegevoegd aan Brim.
	</li>
</ul>
';
$dictionary['item_quick_help']='Klik op het icoon voor <br /> de bladwijzer om deze te kunnen <br />veranderen/verwijderen/verplaatsen.';
$dictionary['item_title']='Bladwijzers';
$dictionary['locatorMissing']='Link moet gedefinieerd worden';
$dictionary['modifyBookmarkPreferences']='Wijzig de bladwijzer voorkeuren';
$dictionary['quickmark']='SnelMarkering';
$dictionary['quickmarkExplanation']='Voeg de volgende link toe als bladwijzer in je <b>browser</b>. Het aanroepen van deze link bij het bezoeken van een pagina zorgt ervoor dat de betreffence pagina automatisch wordt toegevoegd als bladwijzer in Brim';
$dictionary['showBookmarkDetails']='Toon bladwijzer details';
$dictionary['sidebar']='Sidebar';
$dictionary['yourPublicBookmarks']='Je publieke bladwijzers';
$dictionary['showFavicons']='Toon favicons';
$dictionary['favicon']='Favicon';
$dictionary['loadAllFaviconsWarning']='Opgelet! Het binnenhalen van alle favicons voor bladwijzers die nog favicons hebben kan zeer lang duren! Als je dit niet wilt, dan kan je individuele bladwijzers wijzigen en hierbij hun favicons opvragen. Je kunt ook alle favicons binnen een bepaalde map opvragen. Om dit te doen moet je eerst deze map binnengaan. Als het tonen van bladwijzers hierna traag lijkt, kan je proberen de favicons uit te schakelen of een andere boomstructuur dan Javascript te gebruiken.';
$dictionary['javascriptTree']='Javascript structuur';
$dictionary['fetchingFavicon']='Bezig favicon op te halen';
$dictionary['faviconFetched']='Favicon binnen gehaald. Wijzig om de resultaten op te slaan.';
$dictionary['noFaviconFound']='Geen favicon gevonden';
$dictionary['faviconDeleted']='Favicon verwijderd. Wijzig de bladwijzer om het resultaat te bewaren';
$dictionary['deleteFavicon']='Verwijder Favicon';
$dictionary['autoAppendProtocol']='Voeg automatisch \'http://\' aan de url toe als deze geen protocol bevat';
?>
