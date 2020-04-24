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
$dictionary['tip01']='Per cambiare il tema usa le preferenze generali di '.$dictionary['programname'].', non le opzioni di alcuni plugin specifici. Ci sono alcuni temi gi&agrave; a disposizione, provarli non costa nulla!';
$dictionary['tip02']='Se stai usando il template "penguin" o "mylook" usa le preferenze generali di '.$dictionary['programname'].' per cambiare la dimensione delle icone, non le opzioni dei singoli plugin';
$dictionary['tip03']='Per modificare gli elementi &egrave; sufficiente cliccare le icone di fianco ad essi, per modificare una cartella, clicca sulla sua icona';
$dictionary['tip04']='Puoi disattivare il popup javascript in ognuno dei plugin, basta andare nelle opzioni specifiche di quel plugin';
$dictionary['tip05']='Per importare i tuoi Preferiti da IE devi esportarli, scegliendo tra le opzioni del programma  come HTML. Il file che avrai salvato potr&agrave; poi essere importato in di '.$dictionary['programname'].' trattandolo come fosse in formato di Netscape';
$dictionary['tip06']='Il plugin "password" può genereare una parola chiave per ogni sito, basandosi su una generale. Di fatto combina la password da te specificata e la URI del sito per cui vuoi generare una password. Così covrai memorizzare una sola password!';
$dictionary['tip07']='Il plugin "bookmark" utilizza QuickMark, un URL spciale che puoi aggiungere tra i preferiti/segnalibri del tuo browser: quando visiterai un sito che vuoi aggiungere ai tuoi favoriti bastera che clicchi sul link QuickMark e quella URL sar&agrave; aggiunta dentro '.$dictionary['programname'].'';
$dictionary['tip08']='Questo messaggio può essere disabilitato (e riattivato, in caso) attraverso le preferenze della applicazione';
$dictionary['tip09']='Puoi essere informato degli aggiornamenti di '.$dictionary['programname'].' attraverso <a href="http://sourceforge.net/projects/brim/">la pagina progetto su Sourceforge</a> oppure <a href="http://freshmeat.net/projects/brim/">quella su Freshmeat</a>.';
$dictionary['tip10']='Sul <a href="'.$dictionary['programurl'].'">sito principale di '.$dictionary['programname'].'</a> puoi trovare le ultime versioni dispoibili,informazioni sugli ultimi plugin e molto altro ancora!';
$dictionary['tip11']='<a href="'.$dictionary['programurl'].'">'.$dictionary['programname'].'</a>, o meglio, il suo plugin dei bookmarks, può essere integrato nel tuo blog basato su <a href="http://wordpress.org/">Wordpress</a>. Come ad esempio puoi vedere in: <a href="'.$dictionary['authorurl'].'">'.$dictionary['authorurl'].'</a>';
$dictionary['tip12']='Il plugin Agenda ti consente di attivare e disattivare attivit&agrave; gi&agrave; completate';
$dictionary['tip13']='Il plugin Calendario da ora può anche avvisarti di eventi _non_ ricorrenti via posta! Vedrai questa opzione quadno aggiungerai un nuovo evento. Nel caso questa opzione non fosse visibile o disponibile ti consigliamo di rivolgerti al tuo amministratore di sistema';
$dictionary['tip14']='Vuoi contribuire a '.$dictionary['programname'].' ma non sai come? Puoi tradurlo! Nel menù (vicino a Esci, Preferenze, Plugin, ecc.) troverai le opzioni per la traduzione, &egrave; un sistema incluso in '.$dictionary['programname'].'!. Questo strumento ti permette di tradurre sia il framework che i plugin (ogni parte ha un dizionario specifico)';
?>
