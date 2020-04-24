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
$dictionary['activate']='Activeaza';

$dictionary['about']='Despre';
$dictionary['about_page']=' <h2>Despre</h2> <p><b>'.$dictionary['programname'].' '.$dictionary['version'].'</b> Aceasta aplicatie este scrisa de '.$dictionary['authorname'].' (email: <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>) '.$dictionary['copyright'].' </p> <p> Scopul este sa ofere o aplicatie desktop remote open-source, cu un singur login (e.g. email-ul tau, bookmark-urile, de facut, etc integrate intr-o singura aplicatie) </p> <p> Acest program ('.$dictionary['programname'].') este eliberat sub licenta GNU General Public License. Click <a href="doc/gpl.html">aici</a> pentru versiunea completa a licentei.  Pagina oficiala a aplicatiei poate fi gasita la aceasta adresa: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a> </p> ';
$dictionary['actions']="Actiuni";
$dictionary['add']='Adauga';
$dictionary['addFolder'] = "Adauga un director";
$dictionary['addNode'] = "Adauga un Item";
$dictionary['adduser']='Adauga utilizator';
$dictionary['admin']='Admin';
$dictionary['adminConfig']='Configurare';
$dictionary['admin_email']='Email admin';
$dictionary['allow_account_creation']="Permite crearea conturilor de utilizator";
$dictionary['alphabet']=array ('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
$dictionary['back']='Inapoi';
$dictionary['banking']='E-Banking';
$dictionary['bookmark']='Bookmark';
$dictionary['bookmarks']='Bookmark-uri';
$dictionary['calendar']='Calendar';
$dictionary['collapse']='Pliaza';
$dictionary['confirm']='Confirma';
$dictionary['confirm_delete']='Esti sigur ca vrei sa stergi?';
$dictionary['contact']='Contact';
$dictionary['contacts']='Contacte';
$dictionary['contents']='Continut';
$dictionary['dashboard']='Panou control';
$dictionary['database']='Baza de date';
$dictionary['deactivate']='Deactiveaza';
$dictionary['deleteTxt']='Sterge';
$dictionary['delete_not_owner']="Nu ai voie sa stergi un item care nu iti apartine.";
$dictionary['description']='Descriere';
$dictionary['down']='Jos';
$dictionary['email']='Email';
$dictionary['expand']='Extinde';
$dictionary['explorerTree']='Structura arborescenta';
$dictionary['exportTxt']='Exporta';
$dictionary['exportusers']='Exporta utilizatori';
$dictionary['file']='Fisier';
$dictionary['folder']='Director';
$dictionary['forward']='Inainte';
$dictionary['genealogy']='Genealogie';
$dictionary['help']='Ajutor';
$dictionary['home']='Home';
$dictionary['importTxt']='Importa';
$dictionary['importusers']='Importa utilizatori';
$dictionary['input']='Input';
$dictionary['input_error'] = "Verificati campurile de input";
$dictionary['installation_path']="Cale instalare";
$dictionary['installer_exists']='<h2><font color="red">Fisierul de instalare exista! Va rog sa-l stergeti</font></h2>';
$dictionary['item_count']='Numar item-uri';
$dictionary['item_private'] = "Item privat";
$dictionary['item_public'] = "Distribuie acest item";
$dictionary['item_title']='';
$dictionary['inverseAll']='Toate invers';
$dictionary['javascript_popups']="Pop-uri Javascript";
$dictionary['language']='Limba';
$dictionary['last_created']='Creat ultima oara';
$dictionary['last_modified']='Modificat ultima oara';
$dictionary['last_visited']='Vizitat ultima oara';
$dictionary['license_disclaimer']=' Pagina aplicatiei '.$dictionary['programname'].' poate fi gasita la urmatoarea adresa: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a> <br /> '.$dictionary['copyright'].' '.$dictionary['authorname'].' (<a href="'.$dictionary['authorurl'].'" >'.$dictionary['authorurl'].'</a>).  Ma puteti contacta la <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>.  <br /> Acest program ('.$dictionary['programname'].') este software gratis; il puteti distribui si/sau modifica sub termenii licentei GNU General Public License asa cum este publicata de Free Software Foundation; sau versiunea 2 a Licentei, sau (la latitudinea dumneavoastra) orice versiune mai mare.  Click <a href="doc/gpl.html">aici</a> pentru versiunea completa a licentei.  ';
$dictionary['lineBasedTree']='Bazat pe linii';
$dictionary['link']='link';
$dictionary['loginName']='Nume login';
$dictionary['logout']='Logout';
$dictionary['mail']='Mail';
$dictionary['message']="Mesaj";
$dictionary['modify']='Modifica';
$dictionary['modify_not_owner']="Nu ai voie sa modifici un item care nu iti apartine.";
$dictionary['most_visited']='Cele mai vizitate';
$dictionary['move']='Muta';
$dictionary['multipleSelect']='Selectie multipla';
$dictionary['mysqlAdmin']='MySQL';
$dictionary['nameMissing'] = "Numele trebuie sa fie definit";
$dictionary['name']='Nume';
$dictionary['news']='Stiri';
$dictionary['new_window_target']='Unde se va deschide noua fereastra';
$dictionary['no']='Nu';
$dictionary['note']='Nota';
$dictionary['notes']='Note';
$dictionary['overviewTree']='Privire de ansamblu';
$dictionary['password']='Parola';
$dictionary['passwords']='Parole';
$dictionary['pluginSettings']='Plugin';
$dictionary['plugins']='Plugin-uri';
$dictionary['preferences']='Preferinte';
$dictionary['priority']='Prioritate';
$dictionary['private']='Privat';
$dictionary['public']='Public';
$dictionary['quickmark']='CLICK-DREAPTA pe urmatorul link pentru a-l adauga la Bookmark-uri/Favorite in <b>browser</b> dumneavoastra. <br />De fiecare data cand folositi acest bookmark din bookmark-urile browserului dumneavoastra, pagina pe care va aflati va fi adaugata automat in bookmark-urile dumneavoastra din '.$dictionary['programname'].'.<br /><br /><font size="-2">Va rog apasati "OK" daca vi se cere confirmarea la adaugarea bookmark-ului - codul care "preia" adresa paginii pe care vreti sa o faceti bookmark supara unele browsere.</font><br />';
$dictionary['refresh']='Reimprospateaza';
$dictionary['root']='Radacina';
$dictionary['search']='Cauta';
$dictionary['selectAll']='Selecteaza tot';
$dictionary['setModePrivate'] = "Vezi proprii";
$dictionary['setModePublic'] = "Vezi distribuite";
$dictionary['show']='Arata';
$dictionary['sort']='Sorteaza';
$dictionary['submit']='Trimite';
$dictionary['sysinfo']='Informatii sistem';
$dictionary['theme']='Tema';
$dictionary['title']='Titlu';
$dictionary['today']='Azi';
$dictionary['tasks']='Task-uri';
$dictionary['task']='Task';
$dictionary['up']='Sus';
$dictionary['locator']='URL';
$dictionary['user']='Utilizator';
$dictionary['view']="Vezi";
$dictionary['visibility']='Vizibilitate';
$dictionary['webtools']='Unelte Web';
$dictionary['yahoo_column_count']='Numar coloane Yahoo';
$dictionary['yahooTree']='Structura directoare';
$dictionary['yes']='Da';
$dictionary['item_help']='
	<h1>Ajutor '.$dictionary['programname'].'</h1>
	<p>
		'.$dictionary['programname'].' are doua bari-meniu, una este numita bara cu aplicatii si
		contine setarile comnune aplicatiilor, cealalta este numita
		bara plugin-urilor si contine link-uri catre diferite
		plugin-uri. Pentru ajutor specific fiecarui plugin, click
		<a href="#plugins">aici</a>.
	</p>
	<p>
		Link-ul preferinte din bara de aplicatii te va duce la o
		pagina in care poti seta limba, tema pa care ai
		vrea sa o folosesti si setarile personale precum parola, email-ul
		adresa etc. De notat ca limba si tema nu pot fi setate in
		acelasi timp!
	</p>
	<p>
		Link-ul informatii va arata informatii general despre aplicatie, printre care
	   	si numarul versiunii curente.
	</p>
	<p>
		Apasarea link-ului de logout va produce un logout din aplicatie.
		Acest link distruge deasemenea cookie-ul care a fost setat cand folositi
		optiunea "remember me" atunci cand va logati, asa ca dupa aceea va fi nevoie
		sa va relogati inainte de a folosi '.$dictionary['programname'].'.
	</p>
	<p>
		Sectiunea de plugin-uri va permite sa activati/dezactivati plugin-uri.
		Daca un plugin este deactivat, nu va aparea in
		bara de plugin-uri, nici in sectiunea de help.
	</p>
';
?>
