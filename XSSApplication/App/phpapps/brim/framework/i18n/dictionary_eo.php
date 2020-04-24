<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Axel Rousseau (axel.rousseau@esperanto-jeunes.org) & Emmanuelle Richard (emmanuelle.richard@esperanto-jeunes.org)
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

$dictionary['about']='Pri';
$dictionary['about_page']=' <h2>Informoj</h2> <p><b>'.$dictionary['programname'].' '.$dictionary['version'].'</b> Tiu programo ('.$dictionary['programname'].') estis verkita far '.$dictionary['authorname'].' (email: <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>) kopirajtoj (c) 2003 - 2007 </p> <p> La celo de tiu programo estas proponi liberan softvaron kiu, per unika enirnomo/pasvorto, ebligas mastrumi rete notojn, kontaktslipojn ktp. </p> <p> Tiu programo estas la&#365; la &#284;enerala Publika Permesilo \'GNU General Public License\'. Angla kaj kompleta versio troveblas <a href="documentation/gpl.html">&#265;i tie</a>.  La ttt-ejo de tiu programo estas:<a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a> </p> ';
$dictionary['actions']="Agado";
$dictionary['activate']='&#348;alti';
$dictionary['add']='Aldoni';
$dictionary['addFolder'] = "Aldoni dosierujon";
$dictionary['addNode'] = "Aldoni eron";
$dictionary['adduser']='Aldoni uzanton';
$dictionary['admin']='Administrado';
$dictionary['adminConfig']='Konfiguro';
$dictionary['admin_email']='Administranto retadreso';
$dictionary['allow_account_creation']="Ebligi kreadon de uzanto";
$dictionary['alphabet']=array ('A','B','C','&#264;','D','E','F','G','&#284;','H','&#292;','I','J','&#308;','K','L','M','N','O','P','Q','R','S','&#348;','T','U','&#364;','V','W','X','Y','Z');
$dictionary['back']='Malanta&#365;en';
$dictionary['bookmark']='Legosigno';
$dictionary['bookmarks']='Legosignoj';
$dictionary['calendar']='Kalendaro';
$dictionary['collapse']='Malgrandigi';
$dictionary['confirm']='Konfirmi';
$dictionary['confirm_delete']='Chu vi certas forvishi tion ?'; // javascript
$dictionary['contact']='Kontakto';
$dictionary['contacts']='Kontaktoj';
$dictionary['contents']='Enhavo';
$dictionary['deactivate']='Mal&#349;alti';
$dictionary['deleteTxt']='Forvi&#349;i';
$dictionary['delete_not_owner']="Vi ne rajtas forvi&#349;i ion, kion vi ne proprietas.";
$dictionary['description']='Priskribo';
$dictionary['down']='Malsupren';
$dictionary['email']='Retadreso';
$dictionary['expand']='Grandigi';
$dictionary['explorerTree']='arbe';
$dictionary['exportTxt']='Eksporti';
$dictionary['exportusers']='Eksporti uzantojn';
$dictionary['file']='Dosiero';
$dictionary['folder']='Dosierujo';
$dictionary['forward']='Plusendi';
$dictionary['genealogy']='Genealogio';
$dictionary['help']='Helpo';
$dictionary['home']='Hejmo';
$dictionary['importTxt']='Importi';
$dictionary['importusers']='Importi uzantojn';
$dictionary['input']='Eniga&#309;o';
$dictionary['input_error'] = "Kontrolu eniga&#309;on";
$dictionary['installation_path']="Instaldosierujo";
$dictionary['item_private'] = "Privata&#309;o";
$dictionary['item_public'] = "Dispartigi tion";
$dictionary['inverseAll']='Inversigi &#265;ion';
$dictionary['javascript_popups']="&#308;avaskriptaj &#349;prucfenestroj";
$dictionary['language']='Lingvo';
$dictionary['last_created']='Laste kreita';
$dictionary['last_modified']='Laste &#349;an&#285;ita';
$dictionary['last_visited']='Laste vizitita';
$dictionary['license_disclaimer']=' Kopirajtoj (c) 2003 - 2007 '.$dictionary['authorname'].' (<a href="'.$dictionary['authorurl'].'" >'.$dictionary['authorurl'].'</a>). Vi povas rete skribi al mia adreso: <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>.  <br /> Tiu programo estas la&#365; la &#284;enerala Publika Permesilo \'GNU General Public License\'. Angla kaj kompleta versio troveblas <a href="documentation/gpl.html">&#265;i tie</a>.  La ttt-ejo de tiu programo estas:<a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a>.  ';
$dictionary['lineBasedTree']='linie';
$dictionary['link']='Ligo';
$dictionary['loginName']='Enirnomo';
$dictionary['logout']='Foriri';
$dictionary['mail']='Retmesa&#285;o';
$dictionary['message']="Mesa&#285;o";
$dictionary['modify']='&#348;an&#285;i';
$dictionary['modify_not_owner']="Vi ne rajtas &#349;an&#285;i ion, kion vi ne proprietas.";
$dictionary['month01']='Januaro';
$dictionary['month02']='Februaro';
$dictionary['month03']='Marto';
$dictionary['month04']='Aprilo';
$dictionary['month05']='Majo';
$dictionary['month06']='Junio';
$dictionary['month07']='Julio';
$dictionary['month08']='A&#365;gusto';
$dictionary['month09']='Septembro';
$dictionary['month10']='Oktobro';
$dictionary['month11']='Novembro';
$dictionary['month12']='Decembro';
$dictionary['most_visited']='Plej vizitita';
$dictionary['move']='Movigi';
$dictionary['multipleSelect']='Plurelektado';
$dictionary['mysqlAdmin']='MySQL';
$dictionary['nameMissing'] = "Nomo mankas";
$dictionary['name']='Nomo';
$dictionary['news']='Nova&#309;oj';
$dictionary['new_window_target']="Kie nova fenestro sin malfermu ?";
$dictionary['no']='Ne';
$dictionary['note']='Noto';
$dictionary['notes']='Notoj';
$dictionary['overviewTree']='Superrigarde';
$dictionary['password']='Pasvorto';
$dictionary['pluginSettings']='Kromprogramaj agorda&#309;oj';
$dictionary['plugins']='Kromprogramoj';
$dictionary['preferences']='Preferinda&#309;oj';
$dictionary['priority']='Ur&#285;eco';
$dictionary['private']='Privata';
$dictionary['public']='Publika';
$dictionary['quickmark']='Aldoni tiun ligilon en viaj legosignoj de via <b>retumilo</b>. Vizitante retpa&#285;on, kiam vi musklakas tiun legosignon, la vizitita retpa&#285;o estos aldonita al via '.$dictionary['programname'].'-a legosigno.';
$dictionary['refresh']='Reekranigi';
$dictionary['root']='Radiko ???';
$dictionary['search']='Ser&#265;i';
$dictionary['selectAll']='Elekti &#265;ion';
$dictionary['setModePrivate'] = "Vidi privata&#309;ojn";
$dictionary['setModePublic'] = "Vidi publika&#309;ojn";
$dictionary['show']='Montri';
$dictionary['sort']='Ordigi';
$dictionary['submit']='Sendi';
$dictionary['sysinfo']='Sistemaj informoj';
$dictionary['theme']='Temo';
$dictionary['title']='Titolo';
$dictionary['tasks']='Taskoj';
$dictionary['task']='Tasko';
$dictionary['up']='Supren';
$dictionary['locator']='ttt-ejo';
$dictionary['user']='Uzanto';
$dictionary['view']="Vido";
$dictionary['visibility']='Videbligeco';
$dictionary['webtools']='Interretaj Iloj';
$dictionary['welcome_page']='<h1>Bonvenon %s</h1><h2>'.$dictionary['programname'].' - Io por &#265;ion fari.</h2>';
$dictionary['yahoo_column_count']="Compte de colonnes de l'arborescence Yahoo";
$dictionary['yahooTree']='fenestre';
$dictionary['yes']='Jes';
?>
