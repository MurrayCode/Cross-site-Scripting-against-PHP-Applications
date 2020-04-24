<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Johan Warlander
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

$dictionary['about']='Om';
$dictionary['about_page']=' <h2>Om '.$dictionary['programname'].'</h2> <p><b>'.$dictionary['programname'].' '.$dictionary['version'].'</b> Denna applikation &auml;r skriven av '.$dictionary['authorname'].' (epost: <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>) '.$dictionary['copyright'].'</p> <p> Syftet &auml;r att tillhandah&aring;lla en open-source single login remote skrivbords- applikation (dvs. e-post, bokm&auml;rken, att g&ouml;ra-listor osv. integrerade i en milj&ouml;) </p> <p> Detta program ('.$dictionary['programname'].') &auml;r sl&auml;ppt under GNU General Public License.  Klicka <a href="gpl.html">h&auml;r</a> f&ouml;r den kompletta versionen av licensen.  Applikationens hemsida kan hittas p&aring; f&ouml;ljande adress: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a> </p> ';
$dictionary['actions']="Actions";
$dictionary['add']='L&auml;gg till';
$dictionary['addFolder'] = "L&auml;gg till en mapp";
$dictionary['addNode'] = "L&auml;gg till en post";
$dictionary['adduser']='L&auml;gg till anv&auml;ndare';
$dictionary['admin']='Admin';
$dictionary['adminConfig']='Konfiguration';
$dictionary['admin_email']='Admin e-post';
$dictionary['allow_account_creation']="Till&aring;t skapande av anv&auml;ndarkonto";
$dictionary['back']='Tillbaka';
$dictionary['bookmark']='Bokm&auml;rke';
$dictionary['bookmarks']='Bokm&auml;rken';
$dictionary['calendar']='Kalender';
$dictionary['collapse']='Komprimera';
$dictionary['confirm']='Bekr&auml;fta';
$dictionary['confirm_delete']='&Auml;r du s&auml;ker p&aring; att du vill ta bort?';
$dictionary['contact']='Kontakt';
$dictionary['contacts']='Kontakter';
$dictionary['contents']='Inneh&aring;ll';
$dictionary['deleteTxt']='Ta bort';
$dictionary['delete_not_owner']="Du f&aring;r inte ta bort en post som du inte &auml;ger.";
$dictionary['description']='Beskrivning';
$dictionary['down']='Ner';
$dictionary['email']='E-post';
$dictionary['expand']='Expandera';
$dictionary['explorerTree']='Tr&auml;dstruktur';
$dictionary['exportTxt']='Exportera';
$dictionary['exportusers']='Exportera anv&auml;ndare';
$dictionary['file']='Arkiv';
$dictionary['folder']='Mapp';
$dictionary['forward']='Fram&aring;t';
$dictionary['help']='Hj&auml;lp';
$dictionary['home']='Hem';
$dictionary['importTxt']='Importera';
$dictionary['importusers']='Importera anv&auml;ndare';
$dictionary['input_error'] = "Var god kontrollera inmatningsf&auml;lten";
$dictionary['installation_path']="Installationss&ouml;kv&auml;g";
$dictionary['item_private'] = "Privat post";
$dictionary['item_public'] = "Dela denna post";
$dictionary['javascript_popups']="Javascript popups";
$dictionary['language']='Spr&aring;k';
$dictionary['last_created']='Senast skapade';
$dictionary['last_modified']='Senast &auml;ndrade';
$dictionary['last_visited']='Senast bes&ouml;kta';
$dictionary['license_disclaimer']=' '.$dictionary['programname'].' applikationens hemsida kan hittas p&aring; f&ouml;ljande adress: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a> <br /> '.$dictionary['copyright'].' '.$dictionary['authorname'].' (<a href="'.$dictionary['authorurl'].'" >'.$dictionary['authorurl'].'</a>).  Du kan kontakta mig p&aring; <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>.  <br /> This program ('.$dictionary['programname'].') is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.  Click <a href="gpl.html">here</a> for the full version of the license.  ';
$dictionary['link']='l&auml;nk';
$dictionary['loginName']='Inloggningsnamn';
$dictionary['logout']='Logga ut';
$dictionary['mail']='Post';
$dictionary['message']="Meddelande";
$dictionary['modify']='&Auml;ndra';
$dictionary['month01']='Januari';
$dictionary['month02']='Februari';
$dictionary['month03']='Mars';
$dictionary['month04']='April';
$dictionary['month05']='Maj';
$dictionary['month06']='Juni';
$dictionary['month07']='Juli';
$dictionary['month08']='Augusti';
$dictionary['month09']='September';
$dictionary['month10']='Oktober';
$dictionary['month11']='November';
$dictionary['month12']='December';
$dictionary['most_visited']='Mest bes&ouml;kta';
$dictionary['move']='Flytta';
$dictionary['nameMissing'] = "Namn m&aring;ste definieras";
$dictionary['name']='Namn';
$dictionary['news']='Nyheter';
$dictionary['new_window_target']='Var &ouml;ppnas det nya f&ouml;nstret';
$dictionary['no']='Nej';
$dictionary['note']='Anteckning';
$dictionary['notes']='Anteckningar';
$dictionary['password']='L&ouml;senord';
$dictionary['preferences']='Inst&auml;llningar';
$dictionary['quickmark']='H&Ouml;GERKLICKA p&aring; f&ouml;ljande l&auml;nk f&ouml;r att l&auml;gga till den i Bokm&auml;rken/Favoriter i din <b>webbl&auml;sare</b>. <br />Varje g&aring;ng du anv&auml;nder detta bokm&auml;rke i din webbl&auml;sare s&aring; l&auml;ggs sidan du &auml;r p&aring; automatiskt till i '.$dictionary['programname'].'s bokm&auml;rken.<br /><br /><font size="-2">Klicka "OK" om du blir tillfr&aring;gad ifall du vill l&auml;gga till bokm&auml;rket - koden som "plockar up" adressen f&ouml;r sidan du vill markera g&ouml;r en del webbl&auml;sare nerv&ouml;sa.</font><br />';
$dictionary['refresh']='Uppdatera';
$dictionary['root']='Root';
$dictionary['search']='S&ouml;k';
$dictionary['setModePrivate'] = "Se &auml;gda";
$dictionary['setModePublic'] = "Se delade";
$dictionary['show']='Visa';
$dictionary['sort']='Sortera';
$dictionary['submit']='Skicka';
$dictionary['theme']='Tema';
$dictionary['title']='Titel';
$dictionary['task']='Att g&ouml;ra';
$dictionary['tasks']='Att g&ouml;ra';
$dictionary['up']='Upp';
$dictionary['locator']='URL';
$dictionary['user']='Anv&auml;ndare';
$dictionary['view']="Visa";
$dictionary['welcome_page']='<h1>Welcome %s </h1><h2>'.$dictionary['programname'].' - en multipryl n&aring;nting </h2>';
$dictionary['yahooTree']='Katalogstruktur';
$dictionary['yes']='Ja';
?>
