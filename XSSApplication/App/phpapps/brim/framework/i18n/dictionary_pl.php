<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Dawid Makowski
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

$dictionary['about']='O programie';
$dictionary['about_page']=' <h2>O programie</h2> <p><b>'.$dictionary['programname'].' '.$dictionary['version'].'</b> Aplikacja zosta�a napisana przez Barry\'ego Nauta (email: <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>) '.$dictionary['copyright'].' </p> <p> G��wnym jej zadaniem jest dostarczy� u�yteczno�� prostej aplikacji biurowej opartej na zasadach prostego logowania i utrzymanej w duchu open-source.  (np. Twoje maile, zak�adki, zadania itp. zintegrowane w jednym �rodowisku)</p> <p> Ten program ('.$dictionary['programname'].') jest udost�pniony na zasadach GNU General Public License. Kliknij <a href="documentation/gpl.html">tutaj</a> aby zobaczy� pe�n� tre�� licencji Strona domowa projektu to:  <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a> </p> ';
$dictionary['actions']="Akcje";
$dictionary['add']='Dodaj';
$dictionary['adduser']='Dodaj u�ytkownika';
$dictionary['admin']='Administracja';
$dictionary['admin_email']='Admin email';
$dictionary['bookmarks']='Zak�adki';
$dictionary['bookmark']='Zak�adka';
$dictionary['collapse']='Zwin';
$dictionary['confirm']='Potwierd�';
$dictionary['contact']='Kontakt';
$dictionary['contacts']='Kontakty';
$dictionary['contents']='Zawarto��';
$dictionary['deleteTxt']='Skasuj';
$dictionary['description']='Opis';
$dictionary['email']='Email';
$dictionary['expand']='Rozwin';
$dictionary['explorerTree']='Drzewo Explorera';
$dictionary['exportTxt']='Eksportuj';
$dictionary['exportusers']='Eksportuj u�ytkownik�w';
$dictionary['file']='Plik';
$dictionary['folder']='Katalog';
$dictionary['help']='Pomoc';
$dictionary['home']='Home';
$dictionary['importTxt']='Importuj';
$dictionary['importusers']='Importuj u�ytkownik�w';
$dictionary['installation_path']="Installation path";
$dictionary['language']='J�zyk';
$dictionary['last_created']='Last created';
$dictionary['last_modified']='Last modified';
$dictionary['last_visited']='Last visited';
$dictionary['license_disclaimer']=' '.$dictionary['copyright'].' '.$dictionary['authorname'].' (<a href="'.$dictionary['authorurl'].'" >'.$dictionary['authorurl'].'</a>).  Mo�esz si� ze mn� skontaktowa� pisz� na adres : <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>.  <br /> Ten program ('.$dictionary['programname'].') jest wolnym oprogramowaniem; mo�esz go redystrybuowa� i/lub modyfikowa� w granicach licencji GNU General Public License opublikowanej przez Free Software Foundation; w wersji 2 Licencji, lub (wg. uznania) ka�dej p�niejszej wersji.  Kliknij <a href="documentation/gpl.html">tutaj</a> aby zobaczy� pe�n� tre�� licencji.  ';
$dictionary['link']='link';
$dictionary['loginName']='Login';
$dictionary['logout']='Wyloguj';
$dictionary['modify']='Zmie�';
$dictionary['most_visited']='Most visited';
$dictionary['move']='Przenie�';
$dictionary['name']='Imi�';
$dictionary['news']='Aktualnosci';
$dictionary['note']='Notatka';
$dictionary['notes']='Notatki';
$dictionary['password']='Has�o';
$dictionary['preferences']='Ustawienia';
$dictionary['quickmark']='Dodaj ponizszy link do swoich zakladek w <b>przegladarce</b>. Za kazdym razyem gdy odwiedzisz jakas strone i wywolasz ta wlasnie zakladke, ogladana strona automatycznie zostanie dodana do Twoich zakladek w '.$dictionary['programname'].'.<br />';
$dictionary['quickmark']='RIGHT-CLICK on the following link to add it to Bookmarks/Favorites in your <b>browser</b>. <br />Each time you use this bookmark from your browser\'s bookmarks, the page you are on will be automatically added to your '.$dictionary['programname'].' bookmarks.<br /><br /><font size="-2">Please click "OK" if asked about adding the bookmark - code that "picks up" the address of the page you want to bookmark makes some browsers nervous.</font><br />';
$dictionary['refresh']='Refresh';
$dictionary['root']='G��wny';
$dictionary['search']='Szukaj';
$dictionary['show']='Poka�';
$dictionary['sort']='Sort';
$dictionary['submit']='Wy�lij';
$dictionary['theme']='Temat';
$dictionary['title']='Tytu�';
$dictionary['tasks']='Zadania';
$dictionary['task']='Zadanie';
$dictionary['up']='Do g�ry';
$dictionary['locator']='URL';
$dictionary['user']='U�ytkownik';
$dictionary['view']="Widoki";
$dictionary['welcome_page']='<h1>Witaj %s </h1><h2>'.$dictionary['programname'].' - a multithingy something </h2>';
$dictionary['yahooTree']='Drzewo Yahoo';
?>
