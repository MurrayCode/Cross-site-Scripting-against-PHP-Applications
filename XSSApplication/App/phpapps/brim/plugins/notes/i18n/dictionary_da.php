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
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
if (!isset ($dictionary))
{
	$dictionary=array();
}

$dictionary['item_title']='Notater';
$dictionary['modifyNotePreferences']='&AElig;ndre notat indstillinger';
$dictionary['item_help']='
<p>
	Notat delelementet giver dig mulighed for at h&aring;ndtere dine notater on-line.
	F&oslash;lgende oplysninger kan angives p&aring; et notat:
</p>
<ul>
	<li><em>Navn</em>:
		Navnet p&aring; notatet.
	</li>
	<li><em>Mappe/Notat</em>:
		Indikerer om det er en mappe eller et notat som skal oprettes.
		Bem&aelig;rk - Denne indstilling kan ikke efterf&oslash;lgende rettes p&aring; emnet.
	</li>
	<li><em>Delt/Egen</em>:
		Angiver om det kun er dig, eller alle som skal kunne se notatet.
		<br />
		Bem&aelig;rk - Hvis du opretter et delt notat, skal mappen hvori
		notatet er placeret ogs&aring; v&aelig;re delt.
		(root er som standard delt mellem alle brugere).
	</li>
	<li><em>Beskrivelse</em>:
		En beskrivelse af notatet.
	</li>
</ul>
<p>
	Der er f&oslash;lgende undermenuer tilg&aelig;ngelige for notater: 
	Handlinger - Visninger - Indstillinger - Hj&aelig;lp.
</p>
<h3>Handlinger</h3>
<ul>
	<li><em>Tilf&oslash;j</em>:
		Denne handling klarg&oslash;rer en indtastningsformular
		hvor man har mulighed for at oprette et nyt notat.
	</li>
	<li><em>V&aelig;lg flere</em>:
		Denne handling s&aelig;tter en i stand til at v&aelig;lge
		flere notater p&aring; en gang, for derefter 
		at flytte eller slette de valgte.
	</li>
	<li><em>S&oslash;g</em>:
		Det er muligt at s&oslash;ge p&aring; notaters navn og beskrivelse.
	</li>
</ul>
<h3>Visninger</h3>
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
		Viser alle - egne, som delte notater - fra
		alle brugere. Her skelnes ikke mellem dine egne
		og andres delte notater.
	</li>
	<li><em>Se egne</em>:
		Vis kun dine egne notater, ikke andres delte notater.
	</li>
</ul>
<h3>Indstillinger</h3>
<ul>
	<li><em>Rediger</em>:
		Her kan du s&aelig;tte dine foretrukne valg
		hvad ang&aring;r Notater. 
		Man kan indstille antallet af kolonner 
		som skal vises i mappe struktur oversigten,
		og man kan v&aelig;lge om man vil tillade javascript
		popups n&aring;r man f&oslash;rer musen hen over link.
		Man kan ogs&aring; angive standard visningen for 
		hvordan notaterne skal vises mappe- eller tr&aelig; 
		struktur.	
	</li>
</ul>
<h3>Hj&aelig;lp</h3>
<ul>
	Denne tekst.
</ul>
';
?>