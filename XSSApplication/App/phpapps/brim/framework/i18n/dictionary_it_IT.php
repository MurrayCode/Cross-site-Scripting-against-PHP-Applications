<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Maurizio Zanetti
 * @package org.brim-project.framework
 * @subpackage i18n
 *
 * @copyright Brim - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
include 'framework/i18n/common.php';
if (!isset ($dictionary))
{
	$dictionary = array ();
}
$dictionary['about']='Info';
$dictionary['about_page']='<h2>Info</h2>

<p>

<b>'.$dictionary['programname'].' '.$dictionary['version'].'</b> Questa applicazione &#232; stata scritta da Barry Nauta email: <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>)

'.$dictionary['copyright'].' </p> <p> Lo scopo &#232; quello di fornire una applicazione open-source accessibile da qualsiasi computer tramite una semplice interfaccia web, comprendente calendari, rubriche private e condivise, ed altri comodi strumenti.



Questo programma ('.$dictionary['programname'].') &#232; rilasciato sotto la GNU General Public License. Clicca <a href="documentation/gpl.html">qu&#236;</a> per la versione completa (in inglese) della licenza. Per informazioni su questo progetto visitate il seguente indirizzo: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a> </p>';
$dictionary['actions']='Azioni';
$dictionary['activate']='Attiva';
$dictionary['add']='Aggiungi';
$dictionary['addFolder']='Aggiungi una cartella';
$dictionary['addNode']='Aggiungi un oggetto';
$dictionary['adduser']='Aggiungi un utente';
$dictionary['admin']='Amministrazione';
$dictionary['adminConfig']='Configurazione';
$dictionary['admin_email']='e-mail Amministratore';
$dictionary['allow_account_creation']='Permetti la creazione di nuovi accounts';
$dictionary['back']='Indietro';
$dictionary['banking']='Home banking';
$dictionary['bookmark']='Segnalibro';
$dictionary['bookmarks']='Segnalibri';
$dictionary['calendar']='Calendario';
$dictionary['cancel']='Cancella';
$dictionary['checkbook']='Gestione spese';
$dictionary['collapse']='Concentra';
$dictionary['collections']='Collezioni';
$dictionary['confirm']='Conferma';
$dictionary['confirm_delete']='Confermi l\'eliminazione?';
$dictionary['contact']='Contatto';
$dictionary['contacts']='Rubrica';
$dictionary['contents']='Contenuti';
$dictionary['dashboard']='Quadro strumenti';
$dictionary['database']='Database';
$dictionary['dateFormat']='Formato Data';
$dictionary['deactivate']='Disattiva';
$dictionary['defaultTxt']='Standard';
$dictionary['deleteTxt']='Elimina';
$dictionary['delete_not_owner']='Permessi insufficienti per eliminare questo oggetto.';
$dictionary['depot']='Spedizioni';
$dictionary['description']='Descrizione';
$dictionary['deselectAll']='Deseleziona tutto';
$dictionary['down']='Gi&#249;';
$dictionary['email']='Email';
$dictionary['expand']='Espandi';
$dictionary['explorerTree']='Struttura ad albero';
$dictionary['exportTxt']='Esporta';
$dictionary['exportusers']='Esporta utenti';
$dictionary['file']='File';
$dictionary['findDoubles']='Trova duplicati';
$dictionary['folder']='Cartella';
$dictionary['formError']='Controllare i dati inseriti';
$dictionary['forward']='Avanti';
$dictionary['genealogy']='Genealogico';
$dictionary['gmail']='Account GMail';
$dictionary['help']='Help';
$dictionary['home']='Home';
$dictionary['importTxt']='Importa';
$dictionary['importusers']='Importa utenti';
$dictionary['input']='Inserisci';
$dictionary['input_error']='Per favore: controlla i campi inseriti';
$dictionary['installation_path']='Percorso di installazione';
$dictionary['installer_exists']='<h2><font color="red">

Il file di installazione &#232; ancora presente! Per favore, eliminarlo al pi&#249; presto</font></h2>';
$dictionary['inverseAll']='Inverti tutto';
$dictionary['item_count']='Numero di oggetti';
$dictionary['item_help']='<h1>Help di '.$dictionary['programname'].'</h1>

<p>

'.$dictionary['programname'].' ha due barre men&#249;, una viene chiamata "barra di sistema" e contiene i settaggi generali, l\'altra viene chiamata "barra dei moduli" e contiene i collegamenti ai moduli installati. Per informazioni specifiche sui moduli, clicca <a href="#plugins">qu&#236;</a>.

</p>

<p>

Il pulsante "preferenze" nella barra di sistema, apre una schermata in cui puoi impostare la lingua preferita, il tema grafico desiderato, e le tue impostazioni personali come password, indirizzo email, ecc. Nota: la lingua ed il tema grafico non possono essere cambiati contemporaneamente!

</p>

<p>

Il pulsante "Info" mostra le informazioni generali riguardo l\'applicazione, incluso il numero di versione corrente.

</p>

<p>

Premendo il pulsante "Logout" si esce dall\'applicazione. Questa azione comporta anche l\'eliminazione del cookie che viene salvato settando l\'opzione "ricordami" all\'accesso, quindi per riaccedere avrai bisogno di autenticarti nuovamente.

</p>

<p>

Il pulsante "Moduli" permette di attivare o disattivare i vari moduli. Un modulo disattivato non sar&#224; visibile sulla barra dei moduli, e nemmeno all\'interno dell\'Help.

</p>';
$dictionary['item_private']='Oggetto privato';
$dictionary['item_public']='Condividi con gli altri utenti';
$dictionary['javascript_popups']='Popups Javascript';
$dictionary['language']='Lingua';
$dictionary['last_created']='Ultimo creato';
$dictionary['last_modified']='Ultimo modificato';
$dictionary['last_visited']='Ultimo visto';
$dictionary['license_disclaimer']='La home page del progetto '.$dictionary['programname'].' si trova a questo indirizzo: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a>

<br />

'.$dictionary['copyright'].' '.$dictionary['authorname'].' (<a href="'.$dictionary['authorurl'].'">'.$dictionary['authorurl'].'</a>). Potete contattarmi con una email all\'indirizzo  <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>.

Questo programma ('.$dictionary['programname'].') &#232; Software Libero; potete redistribuirlo e/o modificarlo secondo i termini della GNU General Public License, pubblicata dalla Free Software Foundation, versione 2, oppure qualsiasi versione successiva. Cliccate <a href="documentation/gpl.html">qu&#236;</a> per la versione completa (in inglese) della licenza.';
$dictionary['lineBasedTree']='Allineati';
$dictionary['link']='link';
$dictionary['locator']='URL';
$dictionary['loginName']='Nome utente';
$dictionary['logout']='Logout';
$dictionary['mail']='Indirizzo email';
$dictionary['message']='Messaggio';
$dictionary['modify']='Modifica';
$dictionary['modify_not_owner']='Permessi insufficienti per eliminare questo oggetto.';
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
$dictionary['most_visited']='Pi&#249; visitati';
$dictionary['move']='Sposta';
$dictionary['multipleSelect']='Seleziona molti';
$dictionary['mysqlAdmin']='MySQL';
$dictionary['name']='Nome';
$dictionary['nameMissing']='Occorre definire un nome';
$dictionary['new_window_target']='Dove si apre la finestra';
$dictionary['news']='Notizie';
$dictionary['no']='No';
$dictionary['noSearchResult']='Nessun risultato';
$dictionary['note']='Annotazione';
$dictionary['notes']='Note';
$dictionary['overviewTree']='Vista ad albero';
$dictionary['password']='Password';
$dictionary['passwords']='Password';
$dictionary['pluginSettings']='Moduli';
$dictionary['plugins']='Moduli';
$dictionary['polardata']='Dati polari';
$dictionary['preferedIconSize']='Dimensione icone';
$dictionary['preferences']='Preferenze';
$dictionary['priority']='Priorit&#224;';
$dictionary['private']='Privato';
$dictionary['public']='Pubblico';
$dictionary['quickmark']='Fai un click con il tasto destro sul seguente link, per aggiungerlo ai Segnalibri/Bookmarks del tuo <b>browser</b>. <br />Ogni volta che usi questo collegamento dal tuo browser, la pagina che stai consultando verr&#224; aggiunta ai tuoi Segnalibri su '.$dictionary['programname'].'.

<br />

<br />

<font size="-2">Per favore: cliccare su "OK" se richiesto, per aggiungere il codice che preleva l\'indirizzo della pagina da aggiungere. Questo rende qualche browser "nervoso".</font><br />';
$dictionary['recipes']='Ricette';
$dictionary['refresh']='Aggiorna vista';
$dictionary['root']='Inizio';
$dictionary['search']='Cerca';
$dictionary['select']='Seleziona';
$dictionary['selectAll']='Seleziona tutto';
$dictionary['setModePrivate']='Vedi elementi privati';
$dictionary['setModePublic']='Vedi elementi pubblici';
$dictionary['show']='Mostra';
$dictionary['showTips']='Mostra suggerimenti';
$dictionary['sort']='Disponi';
$dictionary['spellcheck']='Controllo ortografico';
$dictionary['submit']='Conferma';
$dictionary['synchronizer']='Sincronizzazione';
$dictionary['sysinfo']='Informazioni di sistema';
$dictionary['task']='Attivit&#224;';
$dictionary['tasks']='Attivit&#224;';
$dictionary['textsource']='Sorgente testo';
$dictionary['theme']='Temi';
$dictionary['tip']='Suggerimento';
$dictionary['title']='Titolo';
$dictionary['today']='Oggi';
$dictionary['translate']='Traduci';
$dictionary['up']='Su';
$dictionary['user']='Utente';
$dictionary['view']='Vedi';
$dictionary['visibility']='Visibilit&#224;';
$dictionary['webtools']='Strumenti Web';
$dictionary['welcome_page']='<h1>Benvenuto %s </h1><h2>'.$dictionary['programname'].' - un "coso" multiuso </h2>';
$dictionary['yahooTree']='Struttura ad elenco';
$dictionary['yahoo_column_count']='Conteggio colonne elenco Yahoo';
$dictionary['yes']='Si';

?>
