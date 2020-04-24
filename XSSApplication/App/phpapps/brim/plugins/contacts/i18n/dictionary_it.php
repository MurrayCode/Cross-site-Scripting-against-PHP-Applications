<?php

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.contacts
 * @subpackage i18n
 * @tradotto in italiano da Luigi Garella
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * This file is part of the Booby project.
 * The GNU Public License
 */
if (!isset ($dictionary))
{
	$dictionary=array();
}

$dictionary['address']='Indirizzo (casa)';
$dictionary['alias']='Alias';
$dictionary['birthday']='Compleanno';
$dictionary['email']='Email';
$dictionary['email1']='Email (casa)';
$dictionary['email2']='Email (lavoro)';
$dictionary['email3']='Email (altro)';
$dictionary['faximile']='Fax (lavoro)';
$dictionary['item_title']='Contatti';
$dictionary['job']='Titolo Lavorativo';
$dictionary['mobile']='Tel (mobile)';
$dictionary['modifyContactPreferences']='Modifica le preferenze del contatto';
$dictionary['org_address']='Indirizzo (lavoro)';
$dictionary['organization']='Ente';
$dictionary['tel_home']='Tel (casa)';
$dictionary['tel_work']='Tel (lavoro)';
$dictionary['webaddress']='Web';
$dictionary['item_help']='
<p>
Il plugin "Contatti" permette di gestire online i vostri contatti.
Potete stabilire e modificare i seguenti parametri:
</p>
<ul>
	<li><em>Nome</em>:
Il nome della persona da inserire tra i contatti
	</li>
	<li><em>Cartella/Contatto</em>:
Per indicare se l\'elemento da aggiungere &egrave; una nuova cartella od un nuovo contatto.
Una volta che questo parametro &egrave; stabilito non potr&agrave; pi&ugrave; essere cambiato

	</li>
	<li><em>Pubblico/Privato</em>:
Questo parametro serve a stabilire la condivisione o meno dell\'elemento
		<br />
		Se volete che uno specifico elemento sia pubblico lo deve essere anche la cartella che lo contiene!!!
		(il Root della struttura &egrave; pubblico per default)
	</li>
	<li><em>Tel. casa</em>:
Il numero di telefono di casa del contatto
	</li>
	<li><em>Tel. lavoro</em>:
Il numero di telefono sul lavoro del contatto
	</li>
	<li><em>Fax.</em>:
Il numero di fax del vostro contatto

	</li>
	<li><em>Email (casa)</em>:
Per ogni contatto avete a disposizione tre indirizzi email.
Questo campo concerne l\'email di casa, o la principale
	</li>
	<li><em>Email (lavoro)</em>:

		Per ogni contatto avete a disposizione tre indirizzi email.
Questo campo concerne l\'email lavorativa, o la secondaria

	</li>
	<li><em>Email (altro)</em>:
Per ogni contatto avete a disposizione tre indirizzi email.
Questo campo concerne un ulteriore indirizzo email
	</li>
	<li><em>Indirizzo Web (homepage)</em>:
Per ogni contatto avete a disposizione tre campi per indirizzi web		
Questo riguarda la homepage del vostro contatto, o l\'indirizzo principale

	</li>
	<li><em>Indirizzo Web (lavoro)</em>:

Per ogni contatto avete a disposizione tre campi per indirizzi web		
Questo riguarda l\'indirizzo web del lavoro del vostro contatto, o l\'indirizzo secondario

		
	</li>
	<li><em>Indirizzo Web (casa)</em>:

Per ogni contatto avete a disposizione tre campi per indirizzi web		
Questo riguarda l\'indirizzo web di casa del vostro contatto, od altro
		
	</li>
	<li><em>Titolo Lavorativo</em>:
		Il titolo lavorativo del vostro contatto
	</li>
	<li><em>Alias</em>:

L\'alias o il nickname della persona (pu&ograve; essere usato nella ricerca)
	</li>
	<li><em>Ente</em>:
L\'ente od organizzazione per cui lavora il vostro contatto
	</li>
	<li><em>Indirizzo (casa)</em>:
		L\'indirizzo di casa del vostro contatto
	</li>
	<li><em>Indirizzo (lavoro)</em>:
L\'indirizzo lavorativo dell\'ente od organizzazione per cui lavora il vostro contatto
	</li>
	<li><em>Descrizione</em>:
Una descrizione per il vostro contatto
	</li>
</ul>
<p>
Il plugin "contatti" mette a disposizione i seguenti sottmen&ugrave;: Azioni, Visualizza, Organizza, Preferenza
</p>
<h3>Azioni</h3>
<ul>
	<li><em>Aggiungi</em>:
Cliccando su questo parametro verr&agrave; caricato un form in cui inserire i dati del contatto		
		<br />
Gli indirizzi Web devono iniziare con un corretto indicatore di protocollo (es.: http:// o ftp://)
	</li>
	<li><em>Selezione Multipla</em>:
Questa azione permette all\'utente di selezionare pi&ugrave; contatti (ma non pi&ugrave; cartelle) contemporaneamente 
al fine di cancellarli o spostarli in un\'altra cartella

	</li>
	<li><em>Importa</em>:
	Questa azione consente di importare i vostri contatti, al momento sono supportati il formato usato da Opera (http://www.opera.com)
	e vCards.
		
				<br />
    Nelle fasi di importazione &egrave; inclusa la possibilit&agrave; di assegnare i valori di accessibilit&agrave;: i contatti possono essere privati o pubblici
		<br />
E\' data la possibilit&agrave; di importare da una cartella specifica, &egrave; sufficiente specificarne il percorso (path) e quindi cliccare sull\'azione "importa"
	</li>
	<li><em>Esporta</em>:
	Questa azione vi consente di epsortare i vostri contatti nel formato di Opera o verso vCards (e a questo punto importabili dam olti altri sistemi)
	<li><em>Cerca</em>:
Questa azione vi consente di effettuare una ricerca interna ai vostri contatti che verr&agrave; compiuta per i campi nome, alias, descrizione od indirizzo
	</li>
</ul>
<h3>Visualizza</h3>
<ul>
	<li><em>Espandi</em>:
Tramite questa azione il sistema espande e mostra tutte le cartelle ed il loro contenuto. Si applica solo alla visualizzazione a struttura ad albero
	</li>
	<li><em>Contrai</em>:
Tramite questa azione il sistema mostra solo gli elementi (cartelle o contatti) della cartella selezionata
	</li>
	<li><em>Struttura a Directory</em>:
Questa azione dice al sistema di passare alla visualizzazione generale a directory. In modo simile a come Yahoo! mostra la struttura delle directory
		<br />
Nelle preferenze del plugin potete stabilire il numero di colonne in cui dividere il contenuto.
	</li>
	<li><em>Struttura ad Albero</em>:
Tramite questa azione il sistema passa ad una visualizzazione simile a quella di "Esplora Risorse" o di altri sistemi di gestione dei fle di sistema
	</li>
	<li><em>Dettagli</em>:
Un altro modo di visualizzare i contenuti che mostra i contatti con molti dettagli
	<li><em>Mostra i Condivisi</em>:
Mostra tutto il contenuto condiviso degli utenti unito al vostro (pubblico e privato)
	</li>
	<li><em>Vedi i tuoi</em>:
mostra solo i vostri contatti (al contrario di "mostra i condivisi")
	</li>
</ul>
<h3>Ordina</h3>
<ul>
	<li><em>Alias</em>:
Ordina in base all\'Alias dei contatti	
	</li>
	<li><em>Email 1</em>:
<br>
Ordina i contatti in base al loro indirizzo email primario
	</li>
	<li><em>Ente</em>:
Organizza i contatti in base all\'ente od organizzazione cui sono legati
	</li>
</ul>
<h3>Preferenze</h3>
<ul>
	<li><em>Modifica</em>:
Questa opzione vi consente di modificare le preferenze relative ai vostri contatti.
Potete agire sul numero di colonne della visualizzazione a directory, potete attivare o disattivare i pop-up javascript che riportano 
alcune informazioni quando scorrete col mouse sul contatto e potet anche stabilire la visualizzazione predefinita
(a directory, ad albero o dettagli)
	</li>
</ul>
';
$dictionary['email_home']='Email&nbsp;(casa)';
$dictionary['email_other']='Email&nbsp;(altro)';
$dictionary['email_work']='Email&nbsp;(lavoro)';
$dictionary['webaddress_home']='IndirizzoWeb&nbsp;(casa)';
$dictionary['webaddress_homepage']='IndirizzoWeb&nbsp;(homepage)';
$dictionary['webaddress_work']='IndirizzoWeb&nbsp;(work)';
$dictionary['clickHere']='Clicca qui';
?>
