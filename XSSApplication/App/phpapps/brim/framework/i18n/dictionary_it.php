<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage i18n
 *
 * @traduzione in italiano di Luigi Garella
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
include 'framework/i18n/common.php';
if (!isset ($dictionary))
{
	$dictionary=array();
}
$dictionary['activate']='Attiva';

$dictionary['about']='Informazioni';
$dictionary['about_page']=' <h2>Informazioni</h2>
<p>
	<b>'.$dictionary['programname'].' '.$dictionary['version'].'</b> 
	Questo applicativo &egrave; stato scritto da '.$dictionary['authorname'].' (email:
	<a href="mailto:'.$dictionary['authoremail'].'"
	>'.$dictionary['authoremail'].'</a>)
	'.$dictionary['copyright'].' </p> <p> 
	Lo scopo &egrave; di avere una suite di applicativi simili a quelli
	comuni per desktop con un\'unica login (ad es.: preferiti, calendario, agenda, ecc.), il tutto open source
	</p>
<p>
	Il programma ('.$dictionary['programname'].') &egrave; rilasciato con licenza GNU General Public License.
	Cliccate <a href="documentation/gpl.html">qui</a> per la versione completa della suddetta licenza.
	La homepage del progetto &egrave; <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a>
</p>';
$dictionary['actions']="Azioni";
$dictionary['add']='Aggiungi';
$dictionary['addFolder'] = "Aggiungi una Cartella";
$dictionary['addNode'] = "Aggiungi un Elemento";
$dictionary['adduser']='Aggiungi un Utente';
$dictionary['admin']='Amministratore';
$dictionary['adminConfig']='Configurazione';
$dictionary['admin_email']='Email dell\'Amministratore';
$dictionary['allow_account_creation']="Permetti la creazione di account utenti";
$dictionary['back']='Indietro';
$dictionary['bookmark']='Segnalibro';
$dictionary['bookmarks']='Segnalibri';
$dictionary['cancel']='Cancella';
$dictionary['calendar']='Calendario';
$dictionary['collapse']='Contrai';
$dictionary['confirm']='Conferma';
$dictionary['confirm_delete']='Sei sicuro di voler cancellare?';
$dictionary['contact']='Contatto';
$dictionary['contacts']='Contatti';
$dictionary['contents']='Contenuti';
$dictionary['dashboard']='Pannello di Controllo';
$dictionary['database']='Database';
$dictionary['deactivate']='Disattiva';
$dictionary['deleteTxt']='Cancella';
$dictionary['delete_not_owner']="Non ti &egrave; consentito cancellare un elemento che non ti appartiene.";
$dictionary['description']='Descrizione';
$dictionary['down']='Giù';
$dictionary['email']='Email';
$dictionary['expand']='Espandi';
$dictionary['explorerTree']='Struttura ad Albero';
$dictionary['exportTxt']='Esporta';
$dictionary['exportusers']='Esporta Utenti';
$dictionary['file']='File';
$dictionary['findDoubles']='Trova doppioni';
$dictionary['folder']='Cartella';
$dictionary['forward']='Avanti';
$dictionary['genealogy']='Genealogia';
$dictionary['help']='Aiuto';
$dictionary['home']='Home';
$dictionary['importTxt']='Importa';
$dictionary['importusers']='Importa Utenti';
$dictionary['input']='Input';
$dictionary['input_error'] = "Per favore contraolla il campo di input";
$dictionary['installation_path']="Percorso (path) di installazione";
$dictionary['installer_exists']='<h2><font color="red">C\'&egrave; ancora il file di installazione! Per cortesia, cancellalo</font></h2>';
$dictionary['item_count']='Numero di elementi';
$dictionary['item_private'] = "Elemento Privato";
$dictionary['item_public'] = "Condividi questo elemento";
//$dictionary['item_title']='';
$dictionary['inverseAll']='Inverti tutto';
$dictionary['javascript_popups']="Pop-up Javascript";
$dictionary['language']='Lingua';
$dictionary['last_created']='Ultimi Creati';
$dictionary['last_modified']='Ultimi Modificati';
$dictionary['last_visited']='Ultimi Visitati';
$dictionary['license_disclaimer']='
La homepage di '.$dictionary['programname'].' si trova all\'indirizzo:
	<a href="'.$dictionary['programurl'].'"
	>'.$dictionary['programurl'].'</a>
	<br />
	'.$dictionary['copyright'].' '.$dictionary['authorname'].'
	(<a href="'.$dictionary['authorurl'].'"
	>'.$dictionary['authorurl'].'</a>).
	Potete contattare Barry all\'email <a
	href="mailto:'.$dictionary['authoremail'].'"
	>'.$dictionary['authoremail'].'</a>.  <br />
	Questo programma ('.$dictionary['programname'].') &egrave; gretuito, siete liberi di ridistribuirlo e/o modificarlo
	sotto le condizioni dettate dalla licenza GNU General Public License pubblicate
	dalla Free Software Foundation; ritenete valida dal versione 2 e le successive di tale licenza.
	Cliccate <a href="documentation/gpl.html"
	>qui</a> per il testo completo dello licenza.  ';
$dictionary['lineBasedTree']='Dettagli';
$dictionary['link']='Link';
$dictionary['loginName']='Login';
$dictionary['logout']='Logout';
$dictionary['mail']='Mail';
$dictionary['message']="Messaggio";
$dictionary['modify']='Modifica';
$dictionary['modify_not_owner']="Non ti &egrave; consentito cancellare un elemento che non ti appartiene.";
$dictionary['month01']='Gennaio';
$dictionary['month02']='Febbraio';
$dictionary['month03']='Marzo';
$dictionary['month04']='Aprile';
$dictionary['month05']='Maggio';
$dictionary['month06']='Giugno';
$dictionary['month07']='Luglio';
$dictionary['month08']='Agosto';
$dictionary['month09']='Settembre';
$dictionary['month10']='Ottobre';
$dictionary['month11']='Novembre';
$dictionary['month12']='Dicembre';
$dictionary['most_visited']='I Più Visitati';
$dictionary['move']='Sposta';
$dictionary['multipleSelect']='Selezione Multipla';
$dictionary['mysqlAdmin']='MySQL';
$dictionary['nameMissing'] = "Devi definire un Nome";
$dictionary['name']='Nome';
$dictionary['news']='notizie';
$dictionary['new_window_target']='Dove si deve aprire la nuova finestra';
$dictionary['no']='No';
$dictionary['note']='Nota';
$dictionary['notes']='Note';
$dictionary['overviewTree']='Visualizzazione ad Albero';
$dictionary['password']='Password';
$dictionary['passwords']='Password';
$dictionary['pluginSettings']='Plugin';
$dictionary['plugins']='Plugin';
$dictionary['preferences']='Preferenze';
$dictionary['priority']='Priorit&agrave;';
$dictionary['private']='Privato';
$dictionary['public']='Pubblico';
$dictionary['quickmark']='Aggiungete questo link tra i vostri Segnalibri/Preferiti cliccandolo col Tasto DESTRO.

 <br />Quando vorrete aggiungere ai Segnalibri di '.$dictionary['programname'].' una pagina web su cui vi trovate vi sar&agrave; sufficiente cliccare su questo Segnalibro
	<br />
	<br />
	<font size="-2">Per favore, cliccate su OK se vi viene chiesto se volete aggiungerlo tra i Segnalibri -
	il codice che fa funzionare il sistema sembra infastidire alcuni browser ma non preoccupatevi.</font><br />';
$dictionary['refresh']='Aggiorna';
$dictionary['root']='Root';
$dictionary['search']='Cerca';
$dictionary['selectAll']='Seleziona Tutto';
$dictionary['deselectAll']='Deseleziona Tutto';
$dictionary['setModePrivate'] = "Mostra i Miei Elementi";
$dictionary['setModePublic'] = "Mostra le Condivisioni";
$dictionary['show']='Mostra';
$dictionary['sort']='Ordina';
$dictionary['submit']='Invia';
$dictionary['sysinfo']='SysInfo';
$dictionary['theme']='Tema';
$dictionary['title']='Titolo';
$dictionary['today']='Oggi';
$dictionary['tasks']='Impegni';
$dictionary['task']='Impegno';
$dictionary['translate']='Traduci';
$dictionary['tasks']='Impegni';
$dictionary['task']='Impegno';
$dictionary['up']='Su';
$dictionary['locator']='URL';
$dictionary['user']='Utente';
$dictionary['view']="Visualizzazione";
$dictionary['visibility']='Visibilit&agrave;';
$dictionary['webtools']='StrumentiWeb';
$dictionary['welcome_page']='<h1>Benvenuti %s </h1><h2>'.$dictionary['programname'].' -
una "cosina" multifunzionale </h2>';
$dictionary['yahoo_column_count']='Conteggio colonne della visualizzazione ad albero';
$dictionary['yahooTree']='Struttura a Directory';
$dictionary['yes']='Sì';

// sterry
$dictionary['polardata'] 			= 'Polar Data';
$dictionary['textsource'] 			= 'Sorgente Testo';
$dictionary['banking'] 				= 'Home Banking';
$dictionary['synchronizer'] 		= 'Sincronizzatore';
$dictionary['spellcheck']='Controllo Ortografico';
$dictionary['item_help']='
<h1>Aiuto per '.$dictionary['programname'].'</h1>
<p>
'.$dictionary['programname'].' ha due barre di menù. Una si chiama barra del programma
e contiene i settaggi generali dell\'applicazione; l\'altra &egrave; la barra dei plugin
e contiene i link ai diversi plugin. Cliccate <a href="#plugins">qui</a> per un aiuto specifico per i plugin.
</p>
<p>
Il link "Preferenze" nella barra del programma vi porta ad una schermata dove potete stabilire la lingua, il tema della visualizzazione ed
i vostri settaggi personali come password, email, ecc.
Badate che non si può stabilire contemporaneamente un linguaggio ed un tema nuovi!
</p>
<p>
Il link "Informazioni" mostra alcune informazioni generali su '.$dictionary['programname'].' come la versione attualmente in uso
</p>
<p>
Cliccando sul bottone Logout verrete disconnessi dall\'applicativo. Attraverso quest\'operazione
si distrugge anche il cookie creato quando (e se) avete scelto l\'opzione "Ricordati di me" all\'atto del Login.
</p>
<p>
La sezione dei Plugin vi consente di attivare/disattivare dei plugin: se uno &egrave; disattivo non sar&agrave; visualizzato nella barra dei plugin né nella sezione di Aiuto
</p>
';
$dictionary['collections']='Collezioni';
$dictionary['depot']='Desposito Azionario';
$dictionary['checkbook']='Spese';
$dictionary['gmail']='GMail';
$dictionary['dateFormat']='Formato della Data';
$dictionary['select']='Seleziona';
$dictionary['formError']='Il form che avete inviato contiene degli errori';
$dictionary['defaultTxt']='Predefinito';
$dictionary['preferedIconSize']='Dimenzione preferita per le icone';
$dictionary['showTips']='Mostra gli aiuti (tip)';
$dictionary['tip']='Aiuto';
$dictionary['noSearchResult']='Non ci sono risultati per la ricerca';
$dictionary['recipes']='Ricette';
$dictionary['calendarEmailReminder']='Gli Avvisi via Email per gli eventi non sono attivi (es.: contrab)';
$dictionary['addToFolderNotOwned']='Non puoi aggiungere un elemento ad una cartella che non &egrave; tua';
$dictionary['attentionTemplate']='Il template in uso tollera solo un certo numero di elementi, oltre quel numero scompare la barra del programma. 
Clicca <a href="PreferenceController.php">qui</a> se non &egrave; possibile accedere al menù del programma (che contiene le opzioni per Preferenze, Aiuto, Ricerca, Logout, Traduzione)';
$dictionary['weather']='Previsioni Meteo';
?>
