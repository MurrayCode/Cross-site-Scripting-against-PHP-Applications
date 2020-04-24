<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @authors Stefano Rosanelli, Luigi Garella
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

$dictionary['complete']='Completato';
$dictionary['due_date']='Scadenza';
$dictionary['item_title']='Agenda';
$dictionary['modifyTaskPreferences']='Modifica le opzioni dell\'agenda';
$dictionary['priority1']="Urgente";
$dictionary['priority2']="Alta";
$dictionary['priority3']="Media";
$dictionary['priority4']="Bassa";
$dictionary['priority5']="Bello da avere";
$dictionary['priority']='Priorit&agrave;';
$dictionary['start_date']='Data di Inizio';
$dictionary['status']='Stato';
$dictionary['item_help']='
<p>
	il plugin Agenda consente di gestire online i vostri impegni. Potete impostare i seguenti parametri:
</p>
<ul>
	<li><em>Nome</em>:
		Il nome del compito da svolgere
	</li>
	<li><em>Cartella/Compito</em>:
Per indicare se l\'elemento che volete aggiungere &egrave; una Cartella od un nuovo Compito da svolgere
Una volta stabilito non pu&ograve; essere cambiato.
	</li>
	<li><em>Pubblico/Privato</em>:
Per indicare se l\'elemento sia pubblico o riservato a voi solamente
		<br />
Fate attenzione: se volete che uno specifico elemento sia pubblico anche l\'elemento che lo contiene deve essere pubblico!!!
(Root &egrave; pubblico per default)		
	</li>
	<li><em>Completato</em>:
Per specificare lo stato di avanzamento del compito
	</li>
	<li><em>Priorit&agrave;</em>:
Per stabilire la priorit&agrave; del compito. Pu&ograve; essere Urgente (per default), Alta, Normale, Bassa, Bello da avere
	</li>
	<li><em>Stato</em>:
Tramite Stato potete fornire un ulteriore parametro di vostro gradimento
	</li>
	<li><em>Data di Inizio</em>:
Per definire la data in cui inizia l\'evento dell\'Agenda

	</li>
	<li><em>Data di Chiusura</em>:
Per definire la data in l\'evento dell\'Agenda deve avere termine
	</li>
	<li><em>Descrizione</em>:
La descizione del Compito

	</li>
</ul>
<p>
I sottomen&ugrave; disponibili per il plugin sono: Azioni, Visualizza, Ordina, Preferenze e Aiuto

</p>
<h3>Azioni</h3>
<ul>
	<li><em>Aggiungi</em>:
Questa azione permette la visualizzazione del form per l\'inserimento dei dettagli del Compito

	</li>
	<li><em>Selezione Multipla</em>:
Tramite questa azione potrete selezionare pi&ugrave; Compiti (NON le cartelle) contemporaneamente per cancellarli o spostarli in un\'altra cartella

	</li>
	<li><em>Cerca</em>:
	Con questa azione potete effettuare una ricerca nei compiti in base a Nome, Stato, Descrizione
	</li>
</ul>
<h3>Visualizza</h3>
<ul>
	<li><em>Espandi</em>:
Questa azione rende visibili tutte le cartelle ed i file a disposizione, SOLO nella visualizzazione ad albero
	</li>
	<li><em>Contrai</em>:
Questa azione fa s&igrave; che vengano visulizzati solo gli elementi (cartelle o segnalibri) della cartella selezionata
	</li>
	<li><em>Struttura a Directory</em>:
Con questo comando il sistema mostra la struttura a directory del contenuto, in modo simile a come Yahoo! si comporta con la sua ricerca per directory		<br />
Il numero di colonne per questa visualizzazione pu&ograve; essere stabilito nelle preferenze specifiche del plugin	</li>
	
	<li><em>Visione Generale ad Albero</em>:
Fonde aspetti della sola visualizzazionad albero semplice con la visione dei dettaglli
	</li>
	
	<li><em>Dettagli</em>:
			Il sistema, con questa azione, mostrer&agrave; i dettagli degli elementi della collezione ordinati in colonna	
	</li>
			
	<li><em>Struttura ad Albero</em>:
		Questa azione fa s&igrave; che il sistema fornisce una visualizzazione simile a quella dell\'Esplora Risorse in Windows e di molti sistemi di file management
	</li>
	<li><em>Mostra le Condivisioni</em>:
Permette di visualizzare tutti i Compiti pubblici di tutti gli utenti, uniti ai propri (sia pubblici sia riservati)

	</li>
	<li><em>Mostra solo i Miei Impegni</em>:
Al contrario del precedente, serve per vedere solo i propri compiti
	</li>
</ul>
<h3>Ordina</h3>
<ul>
	<li><em>Priorit&agrave;</em>:
Organizza gli impegni in base alla priorit&agrave; loro assegnata
	</li>
	<li><em>Completato</em>:
Organizza gli impegni in base all\'avanzamento
	</li>
	<li><em>Data di Inizo</em>:
Organizza gli impegni in base alla loro data di inzio
	</li>
	<li><em>Data di chiusura</em>:
Organizza gli impegni in base alla loro data di chiusura
	</li>
</ul>
<h3>Preferenze</h3>
<ul>
	<li><em>Modifica</em>:
			Modifica le preferenze specifiche del plugin "Collezioni". Si possono variare il numero di colonne
			della visualizzazione in directory, attivare o disattivare i popup javascript quando il mouse passa su un link e definire
			quale sia la visualizzazione predefinita per la Collezione (a directory o ad albero)<br />
			L\'opzione per la lunghezza del testo visualizzato permette di settare quanto testo sia visibile per l\'elemento.<br />
			Potete infine decidere se volete che il file veng aperto in un\'altra finesta o meno.
				</li>
</ul>
';
$dictionary['taskHideCompleted']='Nasconti gli Impegni Completati';
$dictionary['hideCompleted']='Nascondi quelli Completati';
$dictionary['showCompleted']='Mostra quelli Completati';
$dictionary['completedWillDisappearAfterUpdate']='L\'elemento che avete selezionato &egrave; completo al cento per cento. Col prossimo aggiornamento della pagina non comparir&agrave; pi&ugrave; dato che nelle preferenze avete scelto di Nascondere gli Impgeni Completati';
?>
