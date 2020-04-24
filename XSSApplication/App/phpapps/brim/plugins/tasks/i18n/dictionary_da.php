<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.tasks
 * @subpackage tasks
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

$dictionary['complete']='Fuldf&oslash;rt';
$dictionary['due_date']='Forfaldsdato';
$dictionary['item_title']='Opgaver';
$dictionary['modifyTaskPreferences']='&AElig;ndre Opgave indstillinger';
$dictionary['priority1']="Haster";
$dictionary['priority2']="H&oslash;j";
$dictionary['priority3']="Middel";
$dictionary['priority4']="Lav";
$dictionary['priority5']="Ville v&aelig;re rart at have";
$dictionary['priority']='Prioritet';
$dictionary['start_date']='Start dato';
$dictionary['status']='Status';
$dictionary['item_help']='
<p>
	Opgave komponenten giver dig mulighed for at 
	holde styr p&aring; dine opgaver on-line
	F&oslash;lgende indstillinger kan s&aelig;ttes for Opgaver:
</p>
<ul>
	<li><em>Navn</em>:
		Navn eller titel p&aring; opgaven.
	</li>
	<li><em>Mappe/Opgave</em>:
		V&aelig;lg mellem mappe eller opgave.
		Med mappe kan man gruppere opgaver som har et eller andet f&aelig;lles tr&aelig;k.
		Bem&aelig;rk dette valg kan ikke omg&oslash;res efter oprettelsen.
	</li>
	<li><em>Delt/Egen</em>:
		Man skal her tage stilling til om opgaven skal kunne ses af 
		de &oslash;vrige brugere eller kun en selv.<br />
		Bem&aelig;rk at en delt opgave kun kan ses af andre, hvis den ligger 
		i en mappe som ogs&aring; er delt. (Root er som standard delt)
	</li>
	<li><em>Fuldf&oslash;rt</em>:
		Giver mulighed for at angive hvor t&aelig;t p&aring; &quot;l&oslash;st&quot; opgaven er.
	</li>
	<li><em>Prioritet</em>:
		S&aelig;t prioriteten p&aring; opgaven. V&aelig;lg mellem
		Haster (Standard), H&oslash;j,	Middel, Lav, Rart at have.
	</li>
	<li><em>Status</em>:
		Alternativt kan en status angives. Denne kan valgfrit indtastes.
	</li>
	<li><em>Start dato</em>:
		En dato for hvorn&aring;r opgaven skal p&aring;begyndes.
	</li>
	<li><em>Slut dato</em>:
		En angivelse af hvorn&aring;r opgaven forventes l&oslash;st f&aelig;rdig.
	</li>
	<li><em>Beskrivelse</em>:
		En beskrivelse af hvad opgaven g&aring;r ud p&aring;.
	</li>
</ul>
<p>
	Undermenuerne tilr&aring;dighed for opgave komponenten er - Handlinger, Vis, Sorter, Indstillinger og Hj&aelig;lp.
</p>
<h3>Handlinger</h3>
<ul>
	<li><em>Tilf&oslash;j</em>:
		Denne handling pr&aelig;senterer brugeren for en 
		indtastningsformular, s&aring; en ny opgave kan indtastes.
	</li>
	<li><em>V&aelig;lg Flere</em>:
		Denne handling giver brugeren mulighed for at 
		f&aelig;lge flere opgaver samtidigt, for enten at slette
		eller flytte dem til en modtagermappe.
	</li>
	<li><em>S&oslash;g</em>:
		S&oslash;gninger kan foretages baseret p&aring; navn, status eller beskrivelse.
	</li>
</ul>
<h3>Vis</h3>
<ul>
	<li><em>Fold ud</em>:
		Med denne handling ekspanderes alle tr&aelig;ets 
		grene, s&aring; alle elementer vises. Dvs. denne 
		handling er kun brugbar ved tr&aelig; visning.
	</li>
	<li><em>Fold sammen</em>:
		Med denne handling, reduceres visningen til
		kun at vise elementer i den p&aring;g&aelig;ldende mappe.
	</li>
	<li><em>Mappe visning</em>:
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
	<li><em>Liniebaseret visning</em>:
		Detaljeret visning, hvor hver kontaktperson
		optr&aelig;der p&aring; en linie, men en masse oplysninger.
	</li>
	<li><em>Se delte</em>:
		Viser alle - egne, som delte kontaktpersoner - fra
		alle brugere. Her skelnes ikke mellem dine egne
		og andres delte kontaktpersoner.
	</li>
	<li><em>Se egne</em>:
		Vis kun dine egne opgaver, ikke andres delte opgaver.
	</li>
</ul>
<h3>Sorter</h3>
<ul>
	<li><em>Prioritet</em>:
		Sorter efter opgavernes prioritet.
	</li>
	<li><em>Fuldf&oslash;rt</em>:
		Sorter efter opgavernes f&aelig;rdigg&oslash;relsesgrad.
	</li>
	<li><em>Start dato</em>:
		Sorter efter opgave startdato.
	</li>
	<li><em>Slut dato</em>:
		Sorter efter opgave slutdato.
	</li>
</ul>
<h3>Indstillinger</h3>
<ul>
	<li><em>Ret</em>:
		&AElig;ndre Opgave komponentens indstillinger.
		Her kan antallet af kolonner som benyttes i 
		mappevisning s&aelig;ttes. JaveScript popup vinduer
		kan sl&aring;es til/fra, og standard visning kan angives.
	</li>
</ul>
';
$dictionary['taskHideCompleted']='Skjul f&aelig;rdige opgaver';
$dictionary['hideCompleted']='Skjul fuldf&oslash;rte';
$dictionary['showCompleted']='Vis fuldf&oslash;rte';
$dictionary['completedWillDisappearAfterUpdate']='Da du har valgt at skjule 100% fuldf&oslash;rte opgaver, vil opgaven som nu er 100% fuldf&oslash;rt ikke fremg&aring; at oversigten efter n&aelig;ste opdatering.';
?>
