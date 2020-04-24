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
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 */
if (!isset ($dictionary))
{
	$dictionary=array();
}
$dictionary['item_title']='Overs&aelig;t';
$dictionary['pluginToTranslate']='GrundRamme/Komponent';
$dictionary['languageToTranslate']='Sprog';
$dictionary['bothLanguageAndPluginNeeded']='B&aring;de sprog og komponent er kr&aelig;vet';
$dictionary['translationKey']='Overs&aelig;ttelses n&oslash;gle';
$dictionary['baseTranslation']='Basis overs&aelig;ttelse';
$dictionary['currentTranslation']='Nuv&aelig;rende overs&aelig;ttelse';
$dictionary['percentComplete']='Procent fuldf&oslash;rt';
$dictionary['pluginTranslatorIndicator']='Komponent overs&aelig;tter (dit navn)';
$dictionary['translationFileName']='Overs&aelig;ttelses fil-navn ';
$dictionary['saveTranslationToLocation']='Gem fil som...';
$dictionary['stats']='Statistik';

$dictionary['item_help']='
<p>
	Overs&aelig;ttelses v&aelig;rkt&oslash;jet hj&aelig;lper dig med enten at overs&aelig;tte 
	applikationen, eller opgradere en eksisterende overs&aelig;ttelse.
</p>
<p>
	Der findes et script i &quot;tools &quot; mappen
	med navnet <code>dict.sh</code> (tak to	&Oslash;yvind Hagen), 
	som skaber mappestrukturen, og hj&aelig;lper dig med at kopiere
	sprog filerne ind p&aring; de rigtige pladser.
	Scriptet er selvforklarende
</p>
<p>
	Under normal brug af applikationen, unders&oslash;ges det f&oslash;rst
	om der findes en sprogfil af det valgte sprog. Hvis dette
	ikke er tilf&aelig;ldet, benytte applikationen den engelske
	sprogfil i stedet. 
	Det vil i praksis sige, at ukomplette overs&aelig;ttelser vil
	vise tekster med b&aring;de engelske og oversatte tekster.
</p>
<h2>Hvordan man opgraderer en eksisterende overs&aelig;ttelse</h2>
<p>
	Ved hj&aelig;lp af overs&aelig;ttelses v&aelig;rkt&oslash;jet, kan man v&aelig;lge
	sprog og komponent. Dette vil vise en dialogboks 
	med en Overs&aelig;ttelsesn&oslash;gle (brugt internt), 
	Basis overs&aelig;ttelsen (Engelsk), den nuv&aelig;rende overs&aelig;ttelse
	p&aring; dit valgte sprog (Eller r&oslash;dt NOT SET hvis sproget ikke 
	findes) samt et tekstomr&aring;de hvor man kan rette/oprette
	overs&aelig;ttelsen.
</p>
<p>
	N&aring;r du er f&aelig;rdig med overs&aelig;ttelsen, kan man 
	vise den, eller hente den hjem.
	Henter du den hjem, hedder filen &quot;dictionary_XX.php&quot;
	og skal placeres i mappen i18n under den p&aring;g&aelig;ldende komponent,
	eller framework hvis det er grundrammen du har manipuleret.
	Den faktiske placering af filen er vist i toppen af overs&aelig;ttelses
	sk&aelig;rmbilledet.
</p>
<h2>Hvordan man opretter en ny overs&aelig;ttelse.</h2>
<p>
	P&aring; oversigtsbilledet v&aelig;lges &quot;Ny&quot; overs&aelig;ttelse.
	Man vil herefter blive pr&aelig;senteret for et nyt sk&aelig;rmbillede
	hvor man kan begynde overs&aelig;ttelsen.
	N&aring;r overs&aelig;ttelsen er f&aelig;rdig, gem den ved at erstatte XX med
	i &quot;dictionary_XX.php&quot; med din sprogkode.
	Sprogkoden er sammensat af XX_YYY hvor XX er Sproget og 
	YYY er dialekten. F.Eks. PT_BR er brasiliansk portugisisk.
	Den faktiske placering af filen er vist i toppen af overs&aelig;ttelses
	sk&aelig;rmbilledet.
</p>
<p>
	Rediger herefter filen: \'framework/configuration/languages.php\' og
	tilf&oslash;j dit sprog. Tilf&oslash;j hvis det ikke allerede eksisterer
	et flag under \'framework/view/pics/flags\' med navnet
	\'flag-XX_YYY.png\' s&aring; vil sprog sektionen automatisk vise det p&aring; 
	de dertil indrettede steder.
</p>
';
?>