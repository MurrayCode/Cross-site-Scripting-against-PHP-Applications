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
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
if (!isset ($dictionary))
{
	$dictionary=array();
}

$dictionary['item_title']='Adgangskoder';
$dictionary['modifyPasswordPreferences']='Rediger Adgangskoder indstillinger';
$dictionary['passPhrase']='Kode s&aelig;tning';
$dictionary['login']='Login navn';
$dictionary['url']='Link';
$dictionary['generate']='Generer';
$dictionary['siteUrl']='Hjemmeside URL';
$dictionary['masterPassword']='Master adgangskode';
$dictionary['generatePassword']='Generer adgangskode';
$dictionary['generatedPassword']='Genereret adgangskode';
$dictionary['credits']='
<p>
        Baseret p&aring;:
</p>
<ul>
	<li><a href="http://pajhome.org.uk/crypt/md5">Paul Johnston</a>\'s MD5 javascript implementation</li>
	<li><a href="http://angel.net/~nic/passwdlet.html">Nic Wolff</a>\'s password generator</li>
	<li><a href="http://chris.zarate.org/passwd.txt">Chris Zarate</a>\'s modification to ignore subdomains</a></li>
</ul>';
$dictionary['item_help']='
<p>
	Adgangskode komponenten giver dig mulighed for 
	at holde styr p&aring; alle de adgangskoder du bruger 
	online. 
	Faktisk hedder komponenten adgangskode fordi 
	den typisk benyttes til tekst som man &oslash;nsker 
	lagret krypteret i databasen, men komponenten  
	kan ogs&aring; benyttes til almindelig tekst. 
</p>
<p>
	<font color="red">
		Det er vigtigt at huske p&aring;, at adgangskoderne er 
		krypteret inden lagringen i databasen. (S&aring; database 
		administratoren heller ikke kan l&aelig;se dem) 
		Koderne bliver dog dekrypteret p&aring; serveren og sendt 
		i klar tekst, hvis ikke man benytter en sikret protekol 
		som f.eks https!!
	</font>
</p>
<p>
	Adgangskoder har f&oslash;lgende parametre:
</p>
<ul>
	<li><em>Navn</em>:
		The name of the password.
	</li>
	<li><em>Mappe/Adgangskode</em>:
		Indikerer om det er en mappe eller en adgangskode som skal oprettes. 
		Bem&aelig;rk - Denne indstilling kan ikke efterf&oslash;lgende rettes p&aring; emnet. 
	</li>
	<li><em>Kode s&aelig;tning</em>:
		Kan kaldes for n&oslash;glen til kodekassen. 
		N&aring;r en adgangskode skal hentes fra 
		databasen, skal denne s&aelig;tning indtastes 
		s&aring;ledes at adgangskoden kan dekrypteres.
	</li>
	<li><em>Beskrivelse</em>:
		Beskrivelsen af adgangskoden, vil blive 
		krypteret med Kode S&aelig;tningen, og lagret i 
		databasen. 
	</li>
</ul>
<p>
	F&oslash;lgende undermenuer er tilg&aelig;ngelige i Adgangskode komponenten: 
	Handlinger, Vis, Indstillinger og  Hj&aelig;lp.
</p>
<h3>Handlinger</h3>
<ul>
	<li><em>Tilf&oslash;j</em>:
		Denne handling klarg&oslash;rer en indtastningsformular 
		hvor man har mulighed for at oprette en ny adgangskode.. 
	</li>
	<li><em>V&aelig;lg flere</em>:
		Denne handling s&aelig;tter en i stand til at v&aelig;lge 
		flere adgangskoder p&aring; en gang, for derefter 
		at flytte eller slette de valgte..
	</li>
	<li><em>S&oslash;g</em>:
		Det er muligt at s&oslash;ge p&aring; adgangskodernes navn. 
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
	
</ul>
<h3>Indstillinger</h3>
<ul>
	<li><em>Rediger</em>:
		Her angives komponent specifikke indstillinger. 
		Der angiver hvor mange kolonner som skal vises ved mappe struktur visningen, 
		hvorvidt man JavaScript popup vinduer n&aring;r musens mark&oslash;r befinder sig over et objekt, 
		og endeligt hvilken visning som skal benyttes som standard. 
	</li>
</ul>
';
$dictionary['insecureConnection']='Du benytter dennne komponent over en ikke sikret forbindelse. 
				   V&aelig;r opm&aelig;rksom p&aring; at kommunikationen kan opsnappes.!';

$dictionary['noServerCommunicationUsed']='Adgangskode generatoren beregner dine kodeord p&aring; 
					  klienten (javascript), s&aring;ledes at der ikke er 
					  nogen kommunikation til og fra serveren. 
					  Denne generator er sikker at bruge, uanset hvilken 
					  forbindelse man har til serveren.';

$dictionary['passPhraseMissing']='Kode s&aelig;tning mangler!';
?>
