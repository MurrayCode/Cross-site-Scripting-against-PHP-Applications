<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.bookmarks
 * @subpackage i18n
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


$dictionary['item_quick_help']='
	Klik p&aring; ikonet foran genvejen/mappen i den linie som skal flyttes/slettes/redigeres.<br />
	<br />
	For at flytte en genvej til en mappe, skal man klikke p&aring; <br />
	Rediger => Flyt => klik p&aring; modtager mappe.';

$dictionary['item_title']='Bogm&aelig;rker';
$dictionary['locatorMissing'] = 'Placering skal angives';
$dictionary['modifyBookmarkPreferences']='&AElig;ndre bogm&aelig;rke indstillinger';
$dictionary['quickmark']='KvikM&aelig;rke';
$dictionary['quickmarkExplanation']='
	<p>
	H&Oslash;JRE-KLIK p&aring; f&oslash;lgende genvej for at tilf&oslash;je
	det til dine favoritteri din <b>browser</b>.
	<br />
	Hver gang du klikker p&aring; bogm&aelig;rket, vil den aktuelle
	hjemmeside du befinder dig p&aring;, blive tilf&oslash;jet til dine 
	genveje i BRIM.	<br />
	<br />
	<font size="-2">Klik "OK" hvis du bliver spurgt om det er i orden
	at tilf&oslash;je favoritten. Det er fordi den kode som vi benytter
	til at tilf&oslash;je favoritten, g&oslash;r visse browsere nerv&oslash;se.</font><br />
	</p>';
$dictionary['showBookmarkDetails'] = 'Vis Genvejs detaljer';
$dictionary['sidebar']='Sidepanel';
$dictionary['yourPublicBookmarks']='Dine delte bogm&aelig;rker';
$dictionary['installationPathNotSet']='
<p>
	Installations stien er ikke angivet. Dette er 
	n&oslash;dvendigt for KvikM&aelig;rke funktionaliteten.
	Bed venligst din administrator om at angive det.
</p>';
$dictionary['item_help']='
<p>
	Bogm&aelig;rke komponenten giver dig mulighed for at kontrollere/styre
	dine foretrukne internet genveje on-line.
</p>
<p>
	Klik p&aring; ikonet foran genvejen/mappen for at Flytte, Slette eller Redigere elementet.
</p>
<p>
	For at Flytte en genvej til en mappe, klik p&aring;:<BR />
	Rediger -> Flyt -> Destinationsmappen
</p>
<p>
	F&oslash;lgende valgmuligheder findes for Bogm&aelig;rke komponenten:
</p>
<ul>
	<li><em>Navn</em>:
		Her er der mulighed for at give bogm&aelig;rket et navn som man kan genkende.
	</li>
	<li><em>Mappe/Genvej</em>:
		Indikerer om det er en mappe eller et bogm&aelig;rke som skal oprettes.
		Bem&aelig;rk - Denne indstilling kan ikke efterf&oslash;lgende rettes p&aring; emnet.
	</li>
	<li><em>Delt/Privat</em>:
		V&aelig;lg om alle eller kun du skal kunne se disse bogm&aelig;rker.<br />
		Bem&aelig;rk, hvis et bogm&aelig;rke s&aelig;ttes til delt, skal 
		mappen hvori samlingen ligger ogs&aring; v&aelig;re delt.
		(Root mappen er som standard delt mellem alle brugere)
	</li>
	<li><em>URL</em>:
		Bogm&aelig;rkets URL skal strate med protokol angivelse
		(f.eks. http:// eller ftp:// ), ellers betragtes de som
		relative referencer i forhold til denne hjemmeside.
	</li>
	<li><em>Beskrivelse</em>:
		Her er der plads til en kort beskrivelse af bogm&aelig;rket, 
		hvis der alts&aring; er noget at bem&aelig;rke
	</li>
	</ul>
	<p>
		F&oslash;lgende undermenuer er tilg&aelig;ngelige i Bogm&aelig;rker:
		Handlinger, Vis, Sorter, Indstillinger og Hj&aelig;lp.
	</p>
	<h3>Handlinger</h3>
	<ul>
	<li><em>Tilf&oslash;j</em>:
		Denne handling klarg&oslash;rer en indtastningsformular
		hvor man har mulighed for at oprette et nyt bogm&aelig;rke.
		Bem&aelig;rk at URLer skal starte med protekol angivelse
		(f.eks. http:// or ftp://) da de eller bliver betragtet 
		i forhold til denne hjemmesides arbejdskatalog.
	</li>
	<li><em>V&aelig;lg flere</em>:
		Denne handling s&aelig;tter en i stand til at v&aelig;lge
		flere nyhedskilder p&aring; en gang, for derefter 
		at flytte eller slette de valgte.
	</li>
	<li><em>Importer</em>:
		Denne handling s&aelig;tter en i stand til at hente
		bogm&aelig;rker fra andre browsere. For tiden er Opera og
		Netscape/Mozilla/Firefox underst&oslash;ttet.
		Hvis man &oslash;nsker bogm&aelig;rker fra Internet Explorer, skal 
		man f&oslash;rst eksportere disse. Derved dannes en fil i 
		Netscape som BRIM kan l&aelig;se/importere.
		<br />
		Ved import kan man angive synlighedsflaget. Dette flag
		g&oslash;r bogm&aelig;rkerne delte eller private, og vil g&aelig;lde for
		hele den portion bogm&aelig;rker som man er ved at importere.
		<br />
		&Oslash;nskes bogm&aelig;rker importeret i en specifik mappe
		skal man placere sig i mappen inden importfunktionen 
		aktiveres.
	</li>
	<li><em>Exporter</em>:
		Denne handling s&aelig;tter en i stand til at eksportere
		bogm&aelig;rker i Opera eller Netscape format.
		(Netscape formatet er kompatibelt med Mozilla/Firefox).
		Hvis bogm&aelig;rkerne skal bruges i Internet Explorer, skal man
		eksportere dem i Netscape format, for derefter at importere
		dem i Internet Explorer.
	</li>
	<li><em>S&oslash;g</em>:
		Denne handling giver mulighed for at s&oslash;ge bogm&aelig;rker frem, 
		baseret p&aring; navn, URL og beskrivelse.
	</li>
	</ul>
	<h3>Vis</h3>
	<ul>
	<li><em>Fold Ud</em>:
		Med denne handling ekspanderes alle tr&aelig;ets 
		grene, s&aring; alle elementer vises. Dvs. denne 
		handling er kun brugbar ved tr&aelig; visning.
	</li>
	<li><em>Fold Sammen</em>:
		Med denne handling, reduceres visningen til
		kun at vise elementer i den p&aring;g&aelig;ldende mappe.
	</li>
	<li><em>Mappe struktur</em>:
		Denne handling fort&aelig;ller systemet, at elementerne skal 
		vises i mappe struktur. <BR />
		Antallet af kolonner for denne visning kan s&aelig;ttes 
		under indstillinger.
	</li>
	<li><em>Tr&aelig; struktur</em>:
		Denne handling f&aring;r systemet til at skifte til
		en tr&aelig; repr&aelig;sentation i stil med Stifinders
		m&aring;de at vise mappe strukturen for filsystemet.
	</li>
	<li><em>Se delte</em>:
		Viser alle - egne, som delte kontaktpersoner - fra
		alle brugere. Her skelnes ikke mellem dine egne
		og andres delte kontaktpersoner.
	</li>
	<li><em>Se egne</em>:
		Vis kun dine egne bogm&aelig;rker, ikke andres delte bogm&aelig;rker.
	</li>
	</ul>
	<h3>Sortering</h3>
	<ul>
	<li><em>Seneste bes&oslash;gte</em>:
		Sorterer bogm&aelig;rker efter r&aelig;kkef&oslash;lgen for seneste bes&oslash;g.
	</li>
	<li><em>Oftest bes&oslash;gte</em>:
		Sorterer bogm&aelig;rker efter hvilke som er oftest bes&oslash;gt.
	</li>
	<li><em>Senest oprettet</em>:
		Sorterer bogm&aelig;rker i den r&aelig;kkef&oslash;lge som de er oprettet i.
	</li>
	<li><em>Senest rettet</em>:
		Sorterer bogm&aelig;rker i den r&aelig;kkef&oslash;lge som de senest er rettet i.
	</li>
	</ul>
	<h3>Indstillinger</h3>
	<ul>
	<li><em>Rediger</em>:
		Rediger indstillinger for Bogm&aelig;rkekomponenten.
		Man kan angive antallet af kolonner som er synlige
		i mappe struktur visning, om man skal pr&aelig;senteres 
		for popup vinduer n&aring;r musemark&oslash;ren placeres over
		et objekt og hvilken visning komponenten skal 
		starte med.
		Derudover kan man specifisere om klik p&aring; bogm&aelig;rket
		skal &aring;bne i de nuv&aelig;rende vindue eller i et nyt.
	</li>
	<li><em>Dine delte bogm&aelig;rker</em>:
		Klik p&aring; denne genvej, viser alle de delte 
		bogm&aelig;rker som er registreret.
		De bogm&aelig;rker som vises kan sendes til alle som
		man m&aring;tte finde behov for at dele med.
		Denne genvej er ogs&aring; integreret med en anden 
		BRIM side, som kan s&aelig;tte lidt krydderi p&aring;	
		dine bogm&aelig;rker. 
		<br />
		Bem&aelig;rk, at hvis et bogm&aelig;rke skal v&aelig;re delt, kr&aelig;ves
		det, at mappem hvori det ligger, ogs&aring; er delt.
		Rodmappen er som standard delt.
	</li>
	<li><em>Sidepanel</em>:
		Denne genvej f&oslash;rer brugeren til en ny side hvor
		man kan aktivere Brims integration med browseren
		(Kun Opera, Mozilla, Firefox og Netscape).
	</li>
	<li><em>Kvikm&aelig;rke</em>:
		H&oslash;jre-Klik p&aring; den f&oslash;lgende genvej 
		for at tilf&oslash;je det bogm&aelig;rket dine Favoritter 
		i din <b>browser</b>. <br />
		Hver gang du bruger dette bogm&aelig;rke fra din browser, 
		vil den aktuelle side blive lagt ind som bogm&aelig;rke i 
		din BRIM Bogm&aelig;rke samlings komponent.
		<br />
		<br />
		Klik p&aring; "OK" hvis du bliver spurgt om du vil tilf&oslash;je 
		bogm&aelig;rket - koden som henter URL adressen ind i BRIM kan ofte 
		g&oslash;re din browser nerv&oslash;s.
	</li>
</ul>
';
$dictionary['showFavicons']='Vis favoritter';
$dictionary['favicon']='Favorit';
$dictionary['loadAllFaviconsWarning']='
   <p>
	<b>Advarsel</b>
	! Hvis man fors&oslash;ger at hene favorit ikoner til alle bogm&aelig;rker 
	kan det godt tage lang tid. For at undg&aring; dette, kan man redigere
	bogm&aelig;rkerne individuelt og fors&oslash;ge at hente ikonerne en af gangen.
   </p>
   <p>
	Delvis hentning af favorit ikoner, er ogs&aring; muligt, hvis man stiller sig
	i en undermappe, og henter favorit ikonerne for den specifikke mappe. 
	Dette for&oslash;ger hastigheden markant.
   </p>
   <p>
	Hvis favorit ikonerne sl&oslash;ver systemet for meget ned, kan man enten 
	deaktivere funktionaliteten, eller skifte til (under indstillinger) 
	Stifinder Tr&aelig; visning i stedet for JavaScript Tr&aelig; visningen.
   </p>';

$dictionary['javascriptTree']='Javascript tr&aelig;';
$dictionary['fetchingFavicon']='Henter favorit ikoner!';
$dictionary['faviconFetched']='Ikon hentet. Klik rediger for at gemme resultatet.';
$dictionary['noFaviconFound']='Ingen favorit ikon fundet';
$dictionary['faviconDeleted']='Ikon slettet. Klik rediger for at gemme resultatet.';
$dictionary['deleteFavicon']='Slet Favorit';
$dictionary['autoAppendProtocol']='Automatisk tilf&oslash;j \'http://\' foran URL, hvis denne ikke indeholder en protekol';
?>
