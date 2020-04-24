<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2006
 * @package org.brim-project.framework
 * @subpackage i18n
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
include 'framework/i18n/common.php';
if (!isset ($dictionary))
{
	$dictionary=array();
}
$dictionary['tip01']='Gebruik de voorkeuren van de applicatie (en niet de plugin specifieke voorkeuren) om het thema van de applicatie te veranderen. Er zijn meerdere thema\'s beschikbaar, het kan geen kwaad om deze allemaal te proberen!';
$dictionary['tip02']='Gebruik de voorkeuren van de applicatie (en niet de plugin specifieke voorkeuren) om de grootte van de iconen te veranderen. Dit werkt alleen voor de mylook en de penguin thema.';
$dictionary['tip03']='Items kunnen veranderd worden door op het icoon voor het item te klikken, mappen kunnen gewijzigd worden door op de icoon van de map te klikken';
$dictionary['tip04']='Javascript popups kunnen PER PLUGIN aan of afgezet worden. Ga naar de betreffende plugin en gebruik de plugin specifieke voorkeuren om dit te wijzigen';
$dictionary['tip05']='Als je je IE favorieten wil importeren, dan moet je vanuit IE je favorieten als HTML bestand exporteren. Dit bestand kan dan als Netscape Bookmarks bestand geimporteerd worden in de bladwijzer plugin';
$dictionary['tip06']='De paswoord plugin heeft een optie om een paswoord per site te generen, gebaseerd op een globaal password dat jij alleen kent. Er wordt een combinatie van jouw paswoord en de site waarvoor je een paswoord wilt hebben gebruikt en er wordt een nieuw paswoord gegenereerd. Je hoeft nu nog maar 1 password te onthouden!';
$dictionary['tip07']='De bladwijzer plugin heeft een QuickMark. Dit is een speciale URL die je aan je favorieten/bladwijzers moet toevoegen. Als je hierna een site bezoekt welke je in '.$dictionary['programname'].' in je favorieten wilt opnemen, dan klik je deze quickmark en de site die je bezoekt wordt automatisch in '.$dictionary['programname'].' toegeveogd.';
$dictionary['tip08']='Tips kunnen aan en af gezet worden via de applicatie voorkeuren';
$dictionary['tip09']='Je kan je inschrijven om automatisch, via email, op op de hoogte gebracht te worden als er een nieuwe release van '.$dictionary['programname'].' is. Je kan dit doen op de <a href="http://sourceforge.net/projects/brim/">Sourceforge '.$dictionary['programname'].' project site</a> of via de <a href="http://freshmeat.net/projects/brim/">Freshmeat '.$dictionary['programname'].' project site</a>.';
$dictionary['tip10']='Je kan het laatste nieuws altijd vinden op de <a href="'.$dictionary['programurl'].'">'.$dictionary['programname'].' website </a>, alsook informatie over de verschillende plugins en meer!';
$dictionary['tip11']='<a href="'.$dictionary['programurl'].'">'.$dictionary['programname'].'</a> (de favorieten/bladwijzer plugin) integreert ook met <a href="http://wordpress.org/">Wordpress</a>. Je kan een demo op de volgende site: <a href="'.$dictionary['authorurl'].'">'.$dictionary['authorurl'].'</a> vinden';
$dictionary['tip12']='De taken plugin biedt de mogelijkheid om complete taken specifiek wel of niet te tonen';
$dictionary['tip13']='Je kan nu via email een herinnering krijgen gerelateerd aan gebeurtenissen (kalender plugin). Als je een gebeurtenis toevoegt, dan is er een optie om een herinnering toe te voegen. Is dit niet mogelijk bij jou? Vraag de systeem administrator om help.';
$dictionary['tip14']='Je wilt bijdragen aan '.$dictionary['programname'].' maar weet niet goed hoe? Je kan altijd helpen met vertalen! Gebruik de vertaal optie in het applicatie menu en vertaal/corrigeer het raamwerk of een van de plugins (elk gedeelte heeft een aparte vertaling)';
$dictionary['tip15']='Je kan nu iemand toevoegen aan je gebeurtenissen in de calendar/agenda (als je administrator die toe laat). Heb je dit gedaan en je hebt een reminder op deze gebeurtenis gezet, dan krijgt de andere persoon automatisch op het zelfde moment als jij een email bericht';

?>
