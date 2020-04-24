<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.notes
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

$dictionary['item_title']='Note';
$dictionary['modifyNotePreferences']='modifica le preferenze per le Note';
$dictionary['item_help']='
<p>
Il plugin Note vi permette di gestire online le vostre annotazioni.
Potete stabilire i seguenti parametri:
</p>
<ul>
	<li><em>Nome</em>:
Il nome dell\'annotazione
	</li>
	<li><em>Cartella/Nota</em>:
Indica se l\'elemento da aggiungere  una nota od una cartella
Una volta stabilito questo parametro non pu&ograve; essere variato.
	</li>
	<li><em>Pubblico/Privato</em>:
Per indicare se l\'elemento &egrave; privato o pubblico
		<br />
Si faccia attenzione: se volete che uno specifico elemento sia pubblico, lo deve essere anche la cartella che lo contiene.
Root &egrave; pubblico per default.
	</li>
	<li><em>Descrizione</em>:
La descrizione della nota (se di caso)
	</li>
</ul>
<p>
I sottomen&ugrave; dispobili per il plugin Note sono: Azioni, Visualizzazione, Preferenze, Aiuto
</p>
<h3>Azioni</h3>
<ul>
	<li><em>Aggiungi</em>:
Questa azione fa comparire il form in cui inserire i dati della nota.
	</li>
	<li><em>Selezione Multipla</em>:
Questa azione permette di selezionare pi&ugrave; note (ma NON cartelle) contemporaneamente per cancellarle o spostarle in un\'altra cartella
	</li>
	<li><em>Cerca</em>:
<br>
Questa azione vi permette di effettuare una ricerca interna al plugin Note in base ai parametri Nome e Descrizione
	</li>
</ul>
<h3>Visualizzazione</h3>
<ul>
	<li><em>Espandi</em>:
			Questa azione fa s&igrave; che il sistema apra e mostri il contenuto di tutte le cartelle. Si applica solo alla struttura ad albero

		</li>
		<li><em>Contrai</em>:
		Questa azione fa s&igrave; che il sistema mostri solo gli elementi della cartella selezionata

		</li>
		<li><em>Struttura a Directory</em>:
Con questo comando il sistema mostra la struttura a directory del contenuto, in modo simile a come Yahoo! si comporta con la sua ricerca per directory
			<br />
Il numero di colonne per questa visualizzazione pu&ograve; essere stabilito nelle preferenze specifiche del plugin
		</li>
		<li><em>Struttura ad albero</em>:
			Questa azione fa s&igrave; che il sistema fornisce una visualizzazione simile a quella dell\'Esplora Risorse in Windows e di molti sistemi di file management
			
		</li>
        <li><em>Dettagli</em>:
			Il sistema, con questa azione, mostrer&agrave; i dettagli degli elementi ordinati in colonna
		</li>
		<li><em>Mostra le Condivisioni</em>:
Per vedere tutte le collezioni pubbliche di tutti gli utenti insieme alle vostre (pubbliche e/o private).
		</li>
		<li><em>Mostra le Mie Note</em>:
		Per vedere solo le proprie collezioni (al contrario di "Mostra le condivisioni")
		</li>
</ul>
<h3>Preferenze</h3>
<ul>
	<li><em>Modify</em>:
	Questa opzione permette di modificare le preferenze per le Note.
	Potete stabilire il numero di colonne in cui visualizzare la struttura a directory, potete attivare o disattivare i pop-up
	javascript che compaiono quando passate sui link con il puntatore del mouse, e potete stabilire la visualizzazione predefinita per le note (a directory o ad albero)
	</li>
</ul>
';
?>
