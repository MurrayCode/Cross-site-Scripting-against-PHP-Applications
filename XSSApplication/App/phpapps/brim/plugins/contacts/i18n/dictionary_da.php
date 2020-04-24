<?php

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.contacts
 * @subpackage i18n
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * This file is part of the Booby project.
 * The GNU Public License
 */
if (!isset ($dictionary))
{
	$dictionary=array();
}

$dictionary['address']='Hjemme adresse';
$dictionary['alias']='Kaldenavn';
$dictionary['birthday']='F&oslash;dselsdag';
$dictionary['email']='E-mail';
$dictionary['email1']='E-mail 1';
$dictionary['email2']='E-mail 2';
$dictionary['email3']='E-mail 3';
$dictionary['faximile']='Fax';
$dictionary['item_title']='Kontaktperson';
$dictionary['job']='Job titel';
$dictionary['mobile']='Mobiltelefon';
$dictionary['modifyContactPreferences']='&AElig;ndre kontaktpersoner indstillinger';
$dictionary['org_address']='Arbejds adresse';
$dictionary['organization']='Organisation';
$dictionary['tel_home']='Privat Telefon';
$dictionary['tel_work']='Arbejds Telefon';
$dictionary['webaddress']='Hjemmeside';
$dictionary['item_help']='
<p>
	Kontaktperson komponenten giver dig mulighed for at holde styr p&aring; dine
	kontaktpersoner online. 
	F&oslash;lgende indstillinger kan s&aelig;ttes for kontaktpersoner:
</p>
<ul>
	<li><em>Navn</em>:
		Navnet p&aring; kontaktpersonen.
	</li>
	<li><em>Mappe/person</em>:
		Her tages stilling til om det er en mappe eller en kontaktperson
		som oprettes. Kan ikke rettes efterf&oslash;lgende.
		Dette &aring;bner mulighed for at gruppere dine kontakter
	</li>
	<li><em>Delt/Egen</em>:
		V&aelig;lg om alle eller kun du skal kunne se denne kontaktperson.
		<br />
		Bem&aelig;rk, hvis en kontaktperson s&aelig;ttes til delt, skal 
		mappen hvori personen ligger ogs&aring; v&aelig;re delt.
		(Root mappen er som standard delt mellem alle brugere)
	</li>
	<li><em>Privat telefon</em>:
		Kontaktpersonens private telefonnummer.
	</li>
	<li><em>Arbejds telefon</em>:
		Kontaktpersonens telefonnummer p&aring; arbejdet.
	</li>
	<li><em>Fax.</em>:
		Kontaktpersonens telefax nummer.
	</li>
	<li><em>E-mail</em>:
		Den prim&aelig;re e-mail adresse for kontaktpersonen. 
		Der er mulighed for at tilf&oslash;je flere per kontakt.
		De efterf&oslash;lgende E-mail adresser navngives fra 1 og opefter
	</li>
	<li><em>Hjemmeside</em>:
		Her kan en eventuel hjemmeside reference til kontaktpersonen angives.
		Man kan tilf&oslash;je flere hjemmesider p&aring; en kontakt.
	</li>
	<li><em>Job titel</em>:
		Kontaktpersonens arbejde.
	</li>
	<li><em>Kaldenavn</em>:
		Alias eller k&aelig;lenavn for kontaktpersonen (kan v&aelig;re nyttigt i forbindelse med s&oslash;gninger)
	</li>
	<li><em>Organisation</em>:
		Organisation eller firma som kontaktpersonen tegner.
	</li>
	<li><em>Privat adresse</em>:
		Kontaktpersonens privat adresse.
	</li>
	<li><em>Arbejdsadresse</em>:
		Adressen hvor kontaktpersonen arbejder.
	</li>
	<li><em>Bem&aelig;rkninger</em>:
		Beskrivelser, kommentare og andet relevant vedr&oslash;rende kontaktpersonen.
	</li>
</ul>
<p>
	Undermenuerne som er tilg&aelig;ngelige i kontaktperson komponenten er:
	Handlinger, Vis, Sorter og Indstillinger.
</p>
<h3>Handlinger</h3>
<ul>
	<li><em>Tilf&oslash;j</em>:
		Denne handling klarg&oslash;rer en indtastningsformular
		hvor man har mulighed for at oprette en ny 
		kontaktperson.<br />
		Bem&aelig;rk at URLer skal starte med protekol angivelse
		(f.eks. http:// or ftp://) da de eller bliver betragtet 
		i forhold til denne hjemmesides arbejdskatalog.
		
	</li>
	<li><em>V&aelig;lg flere</em>:
		Denne handling s&aelig;tter en i stand til at v&aelig;lge
		flere kontaktpersoner p&aring; en gang, for derefter 
		at flytte eller slette de valgte.
	</li>
	<li><em>Importer</em>:
		Man har mulighed for at importere kontaktpersoner.
		For tiden underst&oslash;ttes dog kun Opera formatet og
		vCards formatet.
		<br />
		I forbindelse med import, kan man specifisere om
		kontakterne er af delt eller egen karakter. 
		Angives batch-vis.
		<br />
		Import til en specifik mappe er muligt, ved at 
		man placerer sig i den p&aring;g&aelig;ldende mappe og starter
		importen derfra.
	</li>
	<li><em>Eksporter</em>:
		Som ved import, har man mulighed for at eksportere
		sine kontaktpersoner. Ligeledes i Opera og vCards 
		format, hvilke er underst&oslash;ttet i adskellige post- 
		og adresse applikationer.

	<li><em>S&oslash;g</em>:
		Det er muligt at s&oslash;ge p&aring; kontaktpersoners 
		navn, kaldenavn, adresse eller beskrivelse.
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
	<li><em>Linie baseret</em>:
		Detaljeret visning, hvor hver kontaktperson
		optr&aelig;der p&aring; en linie, men en masse oplysninger.

	<li><em>Se delte</em>:
		Viser alle - egne, som delte kontaktpersoner - fra
		alle brugere. Her skelnes ikke mellem dine egne
		og andres delte kontaktpersoner.
	</li>
	<li><em>Se egne</em>:
		Viser kun egne kontaktpersoner, ikke andres delte personer.
	</li>
</ul>
<h3>Sorter</h3>
<ul>
	<li><em>Kaldenavn</em>:
		Sorter kontaktpersoner efter kaldenavn.
	</li>
	<li><em>E-mail</em>:
		Sorter kontaktpersoner efter prim&aelig;r e-mail adresse.
	</li>
	<li><em>Organisation</em>:
		Sorter kontaktpersoner efter organisation.
	</li>
</ul>
<h3>Indstillinger</h3>
<ul>
	<li><em>Ret</em>:
		Rediger brugerens indstillinger vedr&oslash;rende
		kontaktpersoner komponenten.
		Man kan indstille antallet af kolonner som 
		benyttes i mappe visning, Javascript popup
		men yderligere information og standard visning
		ved opstart.
	</li>
</ul>
';
$dictionary['email_home']='Privat E-mail';
$dictionary['email_other']='Anden E-mail';
$dictionary['email_work']='Arbejds E-mail';
$dictionary['webaddress_home']='Hjemmeside Privat';
$dictionary['webaddress_homepage']='Hjemmeside Privat';
$dictionary['webaddress_work']='Hjemmeside Arbejde';
$dictionary['clickHere']='Klik her';
?>
