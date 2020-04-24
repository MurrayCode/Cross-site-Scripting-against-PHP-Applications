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
 * @tradotto in italiano da Luigi Garella
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 */
if (!isset ($dictionary))
{
	$dictionary=array();
}
$dictionary['item_title']='Traduzione';
$dictionary['pluginToTranslate']='Framework/Plugin';
$dictionary['languageToTranslate']='Lingua';
$dictionary['bothLanguageAndPluginNeeded']='Sono richiesti sia la Lingua che i Plugin';
$dictionary['translationKey']='Chiave di Traduzione';
$dictionary['baseTranslation']='Traduzione Base';
$dictionary['currentTranslation']='Traduzione Attuale';
$dictionary['percentComplete']='Percentuale Completata';
$dictionary['pluginTranslatorIndicator']='Traduttore del Plugin (il vostro nome)';
$dictionary['translationFileName']='Nome del file di traduzione ';
$dictionary['saveTranslationToLocation']='Salva il file in';
$dictionary['stats']='Statistiche';

$dictionary['item_help']='
<p>
Il sistema di traduzione vi aiuter&agrave; per tradurre l\'applicazione od aggiornare una traduzione pre-esistente
</p>
<p>
Nella sottodirectory degli strumenti c\'&egrave; lo script <code>dict.sh</code>
(un ringraziamento va a Dyivind Hagen) che permette la fomrazione di una struttura a directory
e vi aiuta a copiare i file nelle corrette posizioni. Lo script &egrave; di chiara comprensione
</p>
<p>
Quando si tratta di usare le impostazioni di lingua l\'applicazione si comporta nel seguente modo:
se esiste una traduzione nella vostra lingua questa viene cercata da Brim, se questa non esiste il sistema passa alla versione inglese
Una traduzione incompleta risulter&agrave; quindi in un\'interfaccia mista.
</p>
<h2>Come aggiornare una traduzione gi&agrave; esistente</h2>
<p>

Attraverso il sistema di traduzione selezionate i plugin e il linguaggio. A questo punto vi verr&agrave;
presentata una schermata con la chiave di traduzione (ad uso interno del sistema), la traduzione di partenza (inglese), l\'attuale traduzione nella vostra lingua
(o in rosso il testo \'Non settato!!\' se non ne esiste una) e un\'area di testo per modificare o completare la traduzione di ogni singolo elemento.
</p>
<p>
Una volta terminata la traduzione, potete vederne l\'anteprima oppure scaricarla sul vostro computer.
Il download vi fornisce un file chiamato \'dictionary_XX.php\' che va salvato nella cartella i18n del plugin di cui &egrave; traduzione.
La posizione e la destinazione del file sono specificate in cima llo schermo di traduzione.
</p>
<h2>Come fare una nuova traduzione</h2>
<p>
Dalla schermata principale scegliete \'Nuova\', verr&agrave; caricata l\'interfaccia di traduzione. Una volta
terminato di tradurre salvate il tutto sostituendo XX nel nome del file \'dictionary_XX.php\' con il codice della vostra lingua.
Il codice di lingua &egrave; cos&igrave; composto: XX_YYY dove XX &egrave; la linga e YYY l\'eventuale dialetto (ad es.: PT_BR &egrave; il portoghese parlato in Brasile)
La posizione e la destinazione del file sono visibili in cima alla schermata

</p>
<p>
Adesso modificate il file \'framework/i18n/languages.php\' aggiungendovi la vostra lingua. Mettete un flag (se gi&agrave; non c\'&egrave;) alla cartella
\'framework/view/pics/flags\ nella forma \'flag-XX_YYY.png\' e vi verr&agrave; mostrata automaticamente la schermata di benvenuto
</p>
';
?>
