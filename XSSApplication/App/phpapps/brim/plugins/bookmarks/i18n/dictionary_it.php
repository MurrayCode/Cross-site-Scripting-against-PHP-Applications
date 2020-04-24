<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.bookmarks
 * @subpackage i18n
 * @tradotto in italiano da Luigi Garella
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


$dictionary['item_quick_help']='Cliccate sull\'icona Cartella/Elemento ad inizio riga
per sposatre/cancellare/modificare un link.
<br /><br />Se desiderate spostare a Root o in altre cartella:
<br />click su Modifica => Sposta => click sulla cartella di destinazione';
$dictionary['item_title']='Segnalibri';
$dictionary['locatorMissing'] = 'Specifica il Link';
$dictionary['modifyBookmarkPreferences']='Modifica le preferenze dei Segnalibri';
$dictionary['quickmark']='QuickMark';
$dictionary['quickmarkExplanation']='
<p> Cliccate col tasto DESTRO del mouse su questo link per aggiungerlo ai Preferiti/Segnalibri del vostro <strong>browser</strong>.
	<br />Ogni volta che userete questo segnalibro contenuto nella lista del broswer, la pagina su cui vi trovate verr&agrave; aggiunta qui al 
	plugin Segnalibri
	<br /><br />
	<font size="-2">
	Per favore, cliccate "OK" se vi dovesse essere richiesta conferma per l\'aggiunta
	del segnalibro. Il codice che recupera ed invia l\'indirizzo della pagina rende alcuni browser suscettibili ma non
	sussiste alcun problema.</font><br />
</p>';
$dictionary['showBookmarkDetails'] = 'Mostra i dettagli del Link';
$dictionary['sidebar']='Sidebar';
$dictionary['yourPublicBookmarks']='I Miei Segnalibri Pubblici';
$dictionary['installationPathNotSet']='
<p>
	Non avete specificato il percorso (path) di installazione,: &egrave; un elemento fondamentale 
	per il funzionamento del quickmark. Se non lo conoscete, rivolgetevi al vostro amministratore di sistema.
</p>';
$dictionary['item_help']='
<p>
	Il plugin Segnalibri vi permette di gestire i vostri Preferiti/Segnalibri direttamente online.
</p>
<p>
	Per spostare/cancellare/modificare un link &egrave; sufficiente cliccare sull\'icona ad inizio riga.
</p>
<p>
	Per spostare un elemento ad un\'altra cartella o in Root dovete cliccare su Modifica => Sposta => cliccare sulla cartella di destinazione.
</p>
<p>
	Potete definire i seguenti parametri dei Segnalibri:
</p>
<ul>
	<li><em>Nome</em>:
		Il nome del link. Ad esempio: [nauta.be] &egrave; la mia homepage personale
	</li>
	<li><em>Cartella/Segnalibro</em>:
		L\'opzione permette di stabilire se l\'elemento da aggiungere sia una cartella od un singolo segnalibro.
		Una volta effettuta la scelta non si pu&ograve; pi&ugrave; modificare.
	</li>
	<li><em>Pubblico/Privato</em>:
		E\' l\'opzione che vi permette di stabilire se il segnalibro sia accessibile solo a voi od a tutti gli utenti.
		<br />
		Se volete che un elemento sia pubblico &egrave; necessario che lo sia pure la cartella che lo contiene!!!
		(Root &egrave; pubblico per default)
	</li>
	<li><em>URL</em>:
		L\'URL del segnalibro, deve inziare con l\'indicatore di protocollo (es.: http:// o ftp://) perché sia correttamente gestito da Brim
	</li>
	<li><em>Descrizione</em>:
	Se desiderate potete qui inserire una descrizione per il segnalibro
	</li>
	</ul>
	<p>
		 Il plugin Segnalibri mette a disposizione i seguenti sottomen&ugrave;:
		 Azioni, Visualizzazione, Ordina, Preferenze, Aiuto
	</p>
	<h3>Azioni</h3>
	<ul>
	<li><em>Aggiungi</em>:
	E\' l\'azione che fa caricare il form in cui inserire i dati del nuovo elemento. Ricordate di specificare l\'URL
	con il corretto indicatore di protocollo (es.: http:// o ftp:// o altro)
	</li>
	<li><em>Selezione Multipla</em>:
		Questa azione vi consente di selezionare pi&ugrave; segnalibri (NON cartelle) contemporaneamente per procedere a spostarli o cancellarli.
	</li>
	<li><em>Importa</em>:
		Questa zione vi permette di importare segnalibri dal vostro browser. Al momento Brim supporta i tipi di file 
		utilizzati dal browser Opera e quello della famiglia Netscape/Mozilla/Firefox. Se desiderate importare i preferiti di Internet Explorer 
		&egrave; necessario che prima li esportiate dal programma in formato compatibile per Netscape e quindi importiate il file cos&igrave; ottenuto in Brim.
		<br />
		Durante l\'importazione potete anche settare il parametro di visibilit&agrave;: pubblico o privato. Tutti i segnalibri saranno di conseguenza importati
		con quel settaggio.
		<br />
		E\' possibile importare da una specifica cartella, &egrave; sufficiente indicarne il percorso e quindi cliccare su Importa
	</li>
	<li><em>Espora</em>:
		Questa azione vi consente di esportare i preferiti da Brim in un file di tipo supportato dal browser Opera o quello della famiglia Netscape/Mozilla/Firefox.
		Se volete esportare per Internet Explorer dovete scegliere l\'opzione per il formato di Netscape che quindi potrete importare nel vostro browser.
	</li>
	<li><em>Cerca</em>:
		Questa azione vi consente di effettuare una ricerca all\'interno dei Segnalibri	per nome, URL o descrizione.
	</li>
	</ul>
	<h3>Visualizzazione</h3>
	<ul>
	<li><em>Espandi</em>:
		Questa azione dice al sistema di aprire tutte le cartelle e mostrarne il contenuto. Si applica solo alla visualizzazione gerarchica ad Albero.
	</li>
	<li><em>Contrai</em>:
		Questa azione dice al sistema di mostrare solo gli elementi (cartelle o segnalibri) della cartella in cui vi trovate.
	</li>
	<li><em>Struttura a Directory</em>:
		Questa azione dice al sistema di passare alla visualizzazione a directory, un sistema simile a quello con cui Yahoo! mostra le ricerche per directory.
		<br />
		Potete stabilire il numero di colonne da utilizzare per la visualizzazione nelle preferenze del plugin.
	</li>
	<li><em>Struttura ad Albero</em>:
		Questa azione dice al sistema di passare ad un tipo di visualizzazione simile a quello usato in Windows dalla funzione Esplora Risorse e da 
		molti altri sistemi di gestione di file e cartelle.
	</li>
	<li><em>Mostra i Condivisi</em>:
		Verranno mostrati i segnalibri condivisi da tutti gli utenti assieme a tutti i vostri (pubblici e privati)
	</li>
	<li><em>Mostra i Miei</em>:
		Al contrario di "mostra condivisioni" il sistema con questo comando mostrer&agrave; solo i vostri segnalibri
	</li>
	</ul>
	<h3>Ordina</h3>
	<ul>
	<li><em>Dall\'Ultimo visitato</em>:
		I vostri segnalibri verranno ordinati in base alla data in cui sono stati visitati, a partire dal pi&ugrave; recente
	</li>
	<li><em>Dal Pi&ugrave; Visitato</em>:
		I vostri segnalibri verranno ordinati in base alla frequenza con cui li visitate, a partire dai pi&ugrave; cliccati
	</li>
	<li><em>Dall\'Utimo aggiunto</em>:
		I vostri segnalibri verranno ordinati in base alla data di creazione, a partire dall\'ultimo aggiunto
	</li>
	<li><em>Dall\'Ultimo Modificato</em>:
I vostri segnalibri verranno ordinati in base alla data di mofica, a prtire dall\'ultimo che avete editato.
	</li>
	</ul>
	<h3>Preferenze</h3>
	<ul>
	<li><em>Modifica</em>:
		Vi consente di modificare le preferenze del plugin. Potete agire sul numero di colonne da utilizzare per la visualizzazione, potete attivare
		o disattivare i pop-up javascript che compaiono quando passate col puntatore del mouse su un link, stabilire la visualizzazione
		che preferite come default (a directory o ad albero) e, infine, stabilire se volete che i link si aprano in una nuova finestra (o tab) o meno.
	</li>
	<li><em>I Miei Segnalibri Pubblici</em>:
		Questo link mostrer&agrave; tutti i segnalibri che avete lasciato pubblici. La pagina che verr&agrave; caricata ha un URL pubblico
		che quindi potete inviare a chi volete per la condivisione. Questo link si pu&ograve; integrare anche in un\'altra vostra pagina cos&igrave; da mostrare tutta la bellezza di Brim!
		<br />
		Fate attenzione visto che se volete che un elemento sia pubblico lo deve essere anche l\'elemento che lo contiene!!!
	</li>
	<li><em>Pannello</em>:
		Questo link vi porter&agrave; in una nuova pagina che vi permetter&agrave; una ancor migliore integrazione con il vostro browser
		(solo per Opera, Mozilla, Firefox and Netscape)
		
	</li>
	<li><em>Quickmark</em>:
		 Cliccate col tasto DESTRO del mouse su questo link per aggiungerlo ai Preferiti/Segnalibri del vostro <strong>browser</strong>.
	<br />Ogni volta che userete questo segnalibro contenuto nella lista del broswer, la pagina su cui vi trovate verr&agrave; aggiunta qui al 
	plugin Segnalibri
	<br />
		Per favore, cliccate "OK" se vi dovesse essere richiesta conferma per l\'aggiunta
	del segnalibro. Il codice che recupera ed invia l\'indirizzo della pagina rende alcuni browser suscettibili ma non
	sussiste alcun problema.<br />
	</li>
</ul>
';
$dictionary['showFavicons']='Mostra le Favicon';
$dictionary['favicon']='Favicon';
$dictionary['loadAllFaviconsWarning']='<p><b>Attenzione</b>! Tentare il recupero di tutte le favicon dei vostri segnalibri potrebbe essere un\'operazione
piuttosto lunga. Se volete che si visualizzino potete scegliere l\'opzione di caricarle una per volta dalle preferenze del singolo segnalibro oppure caricarle 
si sottocartella in sottocartella. Questo dovrebbe farvi risparmiare tempo ;-)
</p><p>Se per caso doveste accorgervi in seguito di un rallentamento potete o disattivare l\'inserimento delle favicon (dalle preferenze del plugin)
oppure optare per "ExplorerTree" invece di "JavascriptTree"</p>';
$dictionary['javascriptTree']='Javascript tree';
$dictionary['fetchingFavicon']='Recupero Favicon in corso!';
$dictionary['faviconFetched']='Icona trovata. Cliccate su Modifica per salvare il risultato.';
$dictionary['noFaviconFound']='Non &egrave; stata trovata alcuna Favicon';
$dictionary['faviconDeleted']='Icona cancellata. Cliccate su Modifica per salvare il risultato.';
$dictionary['deleteFavicon']='Cancella Favicon';
$dictionary['autoAppendProtocol']='Aggiungi automaticamente \'http://\' se l\'URL non contiene l\'indicazione di protocollo necessaria';
?>
