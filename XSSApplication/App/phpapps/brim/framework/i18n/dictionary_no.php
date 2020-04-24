<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author �yvind Hagen
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
$dictionary['about']='Om';
$dictionary['about_page']=' <h2>Om '.$dictionary['programname'].'</h2> <p><b>'.$dictionary['programname'].' '.$dictionary['version'].'</b> Denne applikasjonen er skrevet av '.$dictionary['authorname'].' (e-post: <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>) '.$dictionary['copyright'].' </p> <p> Meningen er &aring; tilby en &aring;pen kildekode, singel logginn, ekstern skrivebords-applikasjon (dvs. e-post, bokmerker, gj&oslash;rem&aring;l, osv. integrert i et milj&oslash;) </p> <p> Dette programmet ('.$dictionary['programname'].') er sluppet under The GNU General Public License.  Klikk <a href="gpl.html">her</a> for den komplette versjonen av lisensen.  Applikasjonens hjemmeside fins p&aring; f&oslash;lgendende adresse: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a> </p>';
$dictionary['actions']='Handlinger';
$dictionary['activate']='Aktiver';
$dictionary['add']='Legg til';
$dictionary['addFolder']='Legg til en mappe';
$dictionary['addNode']='Legg til et element';
$dictionary['adduser']='Legg til en bruker';
$dictionary['admin']='Admin';
$dictionary['adminConfig']='Adminkonfigurasjon';
$dictionary['admin_email']='Admin e-post';
$dictionary['allow_account_creation']='Tillat brukeroppretting av konto';
$dictionary['back']='Tilbake';
$dictionary['banking']='E-bank informasjon';
$dictionary['bookmark']='Bokmerke';
$dictionary['bookmarks']='Bokmerker';
$dictionary['calendar']='Kalender';
$dictionary['collapse']='Sammenfall';
$dictionary['confirm']='Bekreft';
$dictionary['confirm_delete']='Bekreft sletting';
$dictionary['contact']='Kontakt';
$dictionary['contacts']='Kontakter';
$dictionary['contents']='Innhold';
$dictionary['dashboard']='Oppslagstavle';
$dictionary['database']='Database';
$dictionary['deactivate']='Deaktiver';
$dictionary['deleteTxt']='Slett';
$dictionary['delete_not_owner']='Du kan ikke slette et element du ikke eier.';
$dictionary['description']='Beskrivelse';
$dictionary['down']='Ned';
$dictionary['email']='E-post';
$dictionary['expand']='Utvid';
$dictionary['explorerTree']='Tr&aring;dstruktur';
$dictionary['exportTxt']='Flytt';
$dictionary['exportusers']='Flytt brukere';
$dictionary['file']='Arkiv';
$dictionary['findDoubles']='Finn duplikater';
$dictionary['folder']='Mappe';
$dictionary['forward']='Fremover';
$dictionary['genealogy']='Slektshistorie';
$dictionary['help']='Hjelp';
$dictionary['home']='Hjem';
$dictionary['importTxt']='Importer';
$dictionary['importusers']='Importer brukere';
$dictionary['input']='Inndata';
$dictionary['input_error']='Vennligst sjekk inndata feltene';
$dictionary['installation_path']='Installasjonsti';
$dictionary['installer_exists']='<h2><font color="red">Installasjonfilen eksisterer fortsatt! V�r vennlig � fjern den</font></h2>';
$dictionary['inverseAll']='Inverter alle';
$dictionary['item_count']='Antall gjenstander';
$dictionary['item_help']=' ';
$dictionary['item_private']='Privat element';
$dictionary['item_public']='Del dette elementet';
$dictionary['item_title']=' ';
$dictionary['javascript_popups']='Javascript popups';
$dictionary['language']='Spr&aring;k';
$dictionary['last_created']='Sist opprettet';
$dictionary['last_modified']='Siste endrede';
$dictionary['last_visited']='Siste bes&oslash;kte';
$dictionary['license_disclaimer']=' Hjemmesiden til '.$dictionary['programname'].' fins p&aring; f&oslash;lgende adresse: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a> <br /> '.$dictionary['copyright'].' '.$dictionary['authorname'].' (<a href="'.$dictionary['authorurl'].'" >'.$dictionary['authorurl'].'</a>).  Du kan kontakte meg p&aring; <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>.  <br /> Dette programmet ('.$dictionary['programname'].') er fri programvare; du kan redistribuere det og/eller modifisere det under bestemmelsene til The GNU General Public License som den er publisert av The Free Software Foundation enten versjon 2 av lisensen, eller (etter ditt valg) alle senere versjoner.  Klikk <a href="gpl.html">her</a> for full versjon av lisensen.';
$dictionary['lineBasedTree']='Linjebasert';
$dictionary['link']='lenke';
$dictionary['locator']='URL';
$dictionary['loginName']='Brukernavn';
$dictionary['logout']='Logg ut';
$dictionary['mail']='Post';
$dictionary['message']='Melding';
$dictionary['modify']='Endre';
$dictionary['month01']='Januar';
$dictionary['month02']='Februar';
$dictionary['month03']='Mars';
$dictionary['month04']='April';
$dictionary['month05']='Mai';
$dictionary['month06']='Juni';
$dictionary['month07']='Juli';
$dictionary['month08']='August';
$dictionary['month09']='September';
$dictionary['month10']='Oktober';
$dictionary['month11']='November';
$dictionary['month12']='Desember';
$dictionary['modify_not_owner']='Du kan ikke endre et element du ikke eier.';
$dictionary['most_visited']='Mest bes&oslash;kte';
$dictionary['move']='Flytt';
$dictionary['multipleSelect']='Flervalg';
$dictionary['mysqlAdmin']='MySQL';
$dictionary['name']='Navn';
$dictionary['nameMissing']='Navn mangler';
$dictionary['new_window_target']='Hvor skal det nye vinduet &aring;pne?';
$dictionary['news']='Nyheter';
$dictionary['no']='Nei';
$dictionary['note']='Notat';
$dictionary['notes']='Notater';
$dictionary['overviewTree']='Oversiktstre';
$dictionary['password']='Passord';
$dictionary['passwords']='Passord';
$dictionary['pluginSettings']='Tillegg';
$dictionary['plugins']='Tillegg';
$dictionary['polardata']='Polardata';
$dictionary['preferences']='Instillinger';
$dictionary['priority']='Prioritet';
$dictionary['private']='Privat';
$dictionary['public']='Delt';
$dictionary['quickmark']='H&Oslash;YREKLIKK p&aring; f&oslash;lgende lenke for &aring; legge den til i Bokmerker/Favoritter i din <b>nettleser</b>. <br />Hver gang du andvender dette bokmerke i din nettleser s&aring; legges siden du er p&aring; automatiskt til i '.$dictionary['programname'].'s bokmerker.<br /><br /><font size="-2">Klikk "OK" om du blir spurt om du vil legge til bokmerke -- skript som "plukker opp" adressen p&aring; siden du vil markere gj&oslash;r en del nettlesere nerv&oslash;se.</font><br />';
$dictionary['refresh']='Oppdater';
$dictionary['root']='Toppnode';
$dictionary['search']='S&oslash;k';
$dictionary['selectAll']='Velg alle';
$dictionary['setModePrivate']='Se private';
$dictionary['setModePublic']='Se delte';
$dictionary['show']='Vis';
$dictionary['sort']='Sortere';
$dictionary['submit']='Send';
$dictionary['synchronizer']='Synkroniserer';
$dictionary['sysinfo']='SysInfo';
$dictionary['textsource']='Tekstkilde';
$dictionary['theme']='Tema';
$dictionary['title']='Tittel';
$dictionary['today']='I dag';
$dictionary['todo']='Gj&oslash;rem&aring;l';
$dictionary['todos']='Gj&oslash;rem&aring;l';
$dictionary['translate']='Oversett';
$dictionary['up']='Opp';
$dictionary['user']='Bruker';
$dictionary['view']='Visning';
$dictionary['visibility']='Synlighet';
$dictionary['webtools']='NettVerkt�y';
$dictionary['welcome_page']='<h1>Velkommen %s </h1><h2>'.$dictionary['programname'].' - en multisak ting </h2> Suler med bl&aring; f&oslash;tter, suler med r&oslash;de f&oslash;tter og maskerte suler. ';
$dictionary['yahooTree']='Mappestruktur';
$dictionary['yahoo_column_count']='Yahooostruktur';
$dictionary['yes']='Ja';
?>
