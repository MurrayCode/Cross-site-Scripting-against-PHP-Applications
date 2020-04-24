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
$dictionary['activate']='Activate';

$dictionary['about']='Om';
$dictionary['about_page']=' <h2>About</h2> <p><b>'.$dictionary['programname'].' '.$dictionary['version'].'</b> Denne applikation er skrevet af '.$dictionary['authorname'].' (email: <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>) '.$dictionary['copyright'].' </p> <p>Form�let er, at tilbyde et program, skrevet i open-source og som med et enkelt login, giver et fjernskrivebord. (f.eks. din mail, bogm�rker/foretrukne, opgaver osv i samme brugerflade) </p> <p> Dette program ('.$dictionary['programname'].') er frigivet inder GNU General Public License. Klik <a href="documentation/gpl.html">her</a> for at se den fulde version af licensen. Programmets hjemmeside kan findes p� f�lgende adresse: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a> </p> ';
$dictionary['actions']="Handling";
$dictionary['add']='Tilf�j';
$dictionary['addFolder'] = "Tilf�j folder";
$dictionary['addNode'] = "Tilf�j et element";
$dictionary['adduser']='Tilf�j bruger';
$dictionary['admin']='Admin';
$dictionary['adminConfig']='Konfiguration';
$dictionary['admin_email']='Admin email';
$dictionary['allow_account_creation']="Tillad brugere at oprette konto";
$dictionary['alphabet']=array ('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
$dictionary['back']='Tilbage';
$dictionary['banking']='E-Banking';
$dictionary['bookmark']='Bogm�rke';
$dictionary['bookmarks']='Bogm�rker';
$dictionary['calendar']='Kalender';
$dictionary['collapse']='Fold sammen';
$dictionary['confirm']='Bekr�ft';
$dictionary['confirm_delete']='Er du sikker p�, at du vil slette?';
$dictionary['contact']='Kontakt';
$dictionary['contacts']='Kontakts';
$dictionary['contents']='Indhold';
$dictionary['dashboard']='Dashboard';
$dictionary['database']='Database';
$dictionary['deactivate']='Deaktiver';
$dictionary['deleteTxt']='Slet';
$dictionary['delete_not_owner']="Du kan ikke slette et elemt du ikke selv har oprettet.";
$dictionary['description']='Beskrivelse';
$dictionary['down']='Ned';
$dictionary['email']='Email';
$dictionary['expand']='Fold ud';
$dictionary['explorerTree']='Tr�struktur';
$dictionary['exportTxt']='Eksporter';
$dictionary['exportusers']='Eksporter brugere';
$dictionary['file']='Fil';
$dictionary['folder']='Folder';
$dictionary['forward']='Fremad';
$dictionary['genealogy']='Historie';
$dictionary['help']='Hj�lp';
$dictionary['home']='Hjem';
$dictionary['importTxt']='Import';
$dictionary['importusers']='Importer brugere';
$dictionary['input']='Input';
$dictionary['input_error'] = "Kontroller inputfelterne";
$dictionary['installation_path']="Installation sti";
$dictionary['installer_exists']='<h2><font color="red">Installationsfilen findes stadig! Venligst slet filen</font></h2>';
$dictionary['item_count']='Antal elementer';
$dictionary['item_help']='';
$dictionary['item_private'] = "Private elementer";
$dictionary['item_public'] = "Del dette element";
$dictionary['item_title']='';
$dictionary['inverseAll']='Fremh�v alle';
$dictionary['javascript_popups']="Javascript popups";
$dictionary['language']='Sprog';
$dictionary['last_created']='Sidst oprettet';
$dictionary['last_modified']='Sidst �ndret';
$dictionary['last_visited']='Sidst bes�gt';
$dictionary['license_disclaimer']=' Programmet '.$dictionary['programname'].' hjemmeside kan findes p� f�lgende adresse <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a> <br /> '.$dictionary['copyright'].' '.$dictionary['authorname'].' (<a href="'.$dictionary['authorurl'].'" >'.$dictionary['authorurl'].'</a>).  You can contact me at <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>.  <br /> Dette program ('.$dictionary['programname'].') er gratis software. Du kan redistribuere det og/eller modificere det under de betingelser der g�lder for GNU General Public License as published by the Free Software Foundation; entern version 2 af licensen, eller (efter dit valg) hvilken som helst senere version.  Klik <a href="documentation/gpl.html">her</a> for en fuld version af licensen.  ';
$dictionary['lineBasedTree']='Liniebaseret';
$dictionary['link']='link';
$dictionary['loginName']='Login navn';
$dictionary['logout']='Logout';
$dictionary['mail']='Mail';
$dictionary['message']="Meddelelse";
$dictionary['modify']='Ret';
$dictionary['modify_not_owner']="Det er ikke tilladt at rette et emne, som ikke er ens eget.";
$dictionary['most_visited']='Mest bes�gte';
$dictionary['move']='Flyt';
$dictionary['multipleSelect']='V�lg flere';
$dictionary['mysqlAdmin']='MySQL';
$dictionary['nameMissing'] = "Navnet skal defineres";
$dictionary['name']='Navn';
$dictionary['news']='Nyheder';
$dictionary['new_window_target']='Hvor �bner det nye vindue';
$dictionary['no']='Nej';
$dictionary['note']='Note';
$dictionary['notes']='Noter';
$dictionary['overviewTree']='Oversigts tr�';
$dictionary['password']='Password';
$dictionary['passwords']='Passwords';
$dictionary['pluginSettings']='Plugins';
$dictionary['plugins']='Plugins';
$dictionary['preferences']='Indstillinger';
$dictionary['priority']='Prioritet';
$dictionary['private']='Privat';
$dictionary['public']='Offentlig';
$dictionary['quickmark']='H�JRE-KLIK p� f�lgende link for at tilf�je det til bogm�rke/foretrukne i din <b>browser</b>. <br />Hver gang du bruger dette bogm�rke fra din browsers foretrukne, vil den side du er p� automatisk blive tilf�jet til Bogm�rke/foretrukne n�ste gang du er p� din '.$dictionary['programname'].'-side<br /><br /><font size="-2">Klik p� "OK" hvis du bliver spurgt om du vil tilf�je bogm�rket til foretrukne. Koden der g�r dette kan g�re nogle browsere "nerv�se"</font><br />';
$dictionary['refresh']='Opdater';
$dictionary['root']='Root';
$dictionary['search']='S�g';
$dictionary['selectAll']='V�lg alle';
$dictionary['setModePrivate'] = "Se egne";
$dictionary['setModePublic'] = "Se delte";
$dictionary['show']='Vis';
$dictionary['sort']='Sorter';
$dictionary['submit']='Send';
$dictionary['sysinfo']='SysInfo';
$dictionary['theme']='Tema';
$dictionary['title']='Tittel';
$dictionary['today']='Idag';
$dictionary['tasks']='Opgaver';
$dictionary['task']='Opgave';
$dictionary['up']='Op';
$dictionary['locator']='URL';
$dictionary['user']='Bruger';
$dictionary['view']="Vis";
$dictionary['visibility']='Gennemsigtig';
$dictionary['webtools']='WebTools';
$dictionary['welcome_page']='<h1>Velkommen %s </h1><h2>'.$dictionary['programname'].' - en multiting et eller andet </h2>';
$dictionary['yahoo_column_count']='Yahoo tr� kolonne t�ller';
$dictionary['yahooTree']='Biblioteks struktur';
$dictionary['yes']='Ja';
?>
