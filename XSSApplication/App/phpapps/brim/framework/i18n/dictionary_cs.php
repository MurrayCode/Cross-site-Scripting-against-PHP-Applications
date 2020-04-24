<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Ladislav Urbanek
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
$dictionary['activate']='Aktivovat';

$dictionary['about']='O '.$dictionary['programname'].'';
$dictionary['about_page']='Definitivn&#283; zm&#283;n&#283;na znakov&#225; sada soubor&#367; s &#269;eskou lokalizac&#237; na UTF-8!<br /><br /><h2>O '.$dictionary['programname'].'</h2> <p>Aplikaci <b>'.$dictionary['programname'].' '.$dictionary['version'].'</b> napsal: '.$dictionary['authorname'].' (e-mail: <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>) '.$dictionary['copyright'].' </p> <p> &#218;&#269;elem je poskytnout v&#237;ce&#250;&#269;elovou webovou open-source aplikaci, p&#345;&#237;stupnou v&#382;dy jedn&#237;m p&#345;ihl&#225;&#353;en&#237;m (nap&#345;. do Va&#353;&#237; po&#353;ty, z&#225;lo&#382;ek, &#250;kol&#367;, atd. integrovan&#253;ch do jednoho prost&#345;ed&#237;) </p> <p> Tento program ('.$dictionary['programname'].') je vydan&#253; pod GNU General Public License. Klikn&#283;te <a href="doc/gpl.html">zde</a> pro zobrazen&#237; cel&#233; licence.  Webov&#233; str&#225;nky aplikace naleznete na adrese: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a> </p> <br /> <p> &#268;e&#353;tina: Ladislav Urb&#225;nek (<a href="mailto:mailbox@raulsoft.net">mailbox@raulsoft.net</a>)<br />P&#345;ipom&#237;nky k p&#345;ekladu jsou v&#237;tan&#233;. <br /><br /></p> ';
$dictionary['actions']="Akce";
$dictionary['add']='P&#345;idat';
$dictionary['addFolder'] = "P&#345;idat slo&#382;ku";
$dictionary['addNode'] = "P&#345;idat polo&#382;ku";
$dictionary['adduser']='P&#345;idat u&#382;ivatele';
$dictionary['admin']='Admin';
$dictionary['adminConfig']='Konfigurace';
$dictionary['admin_email']='Admin e-mail';
$dictionary['allow_account_creation']="Povolit u&#382;ivatel&#367;m vytvo&#345;en&#237; &#250;&#269;tu";
$dictionary['alphabet']=array ('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
$dictionary['back']='Zp&#283;t';
$dictionary['banking']='E-banka';
$dictionary['bookmark']='Z&#225;lo&#382;ka';
$dictionary['bookmarks']='Z&#225;lo&#382;ky';
$dictionary['calendar']='Kalend&#225;&#345;';
$dictionary['collapse']='Sbalit';
$dictionary['confirm']='Potvrzen&#237;';
$dictionary['confirm_delete']='Opravdu chcete polo&#382;ku/slo&#382;ku odstranit?';
$dictionary['contact']='Kontakt';
$dictionary['contacts']='Kontakty';
$dictionary['contents']='Obsah';
$dictionary['dashboard']='Dashboard';
$dictionary['database']='Datab&#225;ze';
$dictionary['deactivate']='Deaktivovat';
$dictionary['deleteTxt']='Odstranit';
$dictionary['delete_not_owner']="Nejste opr&#225;vn&#283;ni smazat polo&#382;ku, kterou nevlastn&#237;te.";
$dictionary['description']='Popis';
$dictionary['down']='Dol&#367;';
$dictionary['email']='E-mail';
$dictionary['expand']='Rozbalit';
$dictionary['explorerTree']='Stromov&#225; struktura';
$dictionary['exportTxt']='Exportovat';
$dictionary['exportusers']='Exportovat u&#382;ivatele';
$dictionary['file']='Soubor';
$dictionary['folder']='Slo&#382;ka';
$dictionary['forward']='Dop&#345;edu';
$dictionary['genealogy']='Genealogie';
$dictionary['help']='N&#225;pov&#283;da';
$dictionary['home']='Dom&#367;';
$dictionary['importTxt']='Importovat';
$dictionary['importusers']='Importovat u&#382;ivatele';
$dictionary['input']='Vstup';
$dictionary['input_error'] = "Pros&#237;m zkontrolujte vstupn&#237; pole";
$dictionary['installation_path']="Instala&#269;n&#237; cesta";
$dictionary['item_count']='Po&#269;et polo&#382;ek';
$dictionary['item_private'] = "Soukrom&#225; polo&#382;ka";
$dictionary['item_public'] = "Sd&#237;len&#225; polo&#382;ka";
$dictionary['item_title']='';
$dictionary['inverseAll']='Obr&#225;tit v&#353;e'; // #raul# pouzit jiny termin
$dictionary['javascript_popups']="Javascript pop-up okna";
$dictionary['language']='Jazyk';
$dictionary['last_created']='Posledn&#237; vytvo&#345;en&#233;';
$dictionary['last_modified']='Posledn&#237; upraven&#233;';
$dictionary['last_visited']='Posledn&#237; nav&#353;t&#237;ven&#233;';
$dictionary['license_disclaimer']=' Webov&#233; str&#225;nky aplikace '.$dictionary['programname'].' naleznete na adrese: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a> <br /> '.$dictionary['copyright'].' '.$dictionary['authorname'].' (<a href="'.$dictionary['authorurl'].'" >'.$dictionary['authorurl'].'</a>).  M&#367;&#382;ete m&#283; kontaktovat na e-mailov&#233; adrese <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>. <br /> Tento program ('.$dictionary['programname'].') je svobodn&#253; software; m&#367;&#382;ete jej &#353;&#237;&#345;it a modifikovat podle ustanoven&#237; GNU General Public License, vyd&#225;van&#233; Free Software Foundation; a to bu&#271; verze 2 t&#233;to licence anebo (podle Va&#353;eho uv&#225;&#382;en&#237;) kter&#233;koli pozd&#283;j&#353;&#237; verze. Klikn&#283;te <a href="doc/gpl.html">zde</a> pro zobrazen&#237; licence.';
$dictionary['lineBasedTree']='Po &#345;&#225;dc&#237;ch';
$dictionary['link']='Odkaz';
$dictionary['loginName']='P&#345;ihla&#353;ovac&#237; jm&#233;no';
$dictionary['logout']='Odhl&#225;sit';
$dictionary['mail']='E-mail';
$dictionary['message']="Zpr&#225;va";
$dictionary['modify']='Upravit';
$dictionary['modify_not_owner']="Nejste opr&#225;vn&#283;ni upravovat polo&#382;ku, kterou nevlastn&#237;te.";
$dictionary['month01']='Leden';
$dictionary['month02']='&#218;nor';
$dictionary['month03']='B&#345;ezen';
$dictionary['month04']='Duben';
$dictionary['month05']='Kv&#283;ten';
$dictionary['month06']='&#268;erven';
$dictionary['month07']='&#268;ervenec';
$dictionary['month08']='Srpen';
$dictionary['month09']='Z&#225;&#345;&#237;';
$dictionary['month10']='&#344;&#237;jen';
$dictionary['month11']='Listopad';
$dictionary['month12']='Prosinec';
$dictionary['most_visited']='Nejnav&#353;t&#283;vovan&#283;j&#353;&#237;';
$dictionary['move']='P&#345;esunout';
$dictionary['multipleSelect']='V&#237;cen&#225;sobn&#253; v&#253;b&#283;r';
$dictionary['mysqlAdmin']='MySQL';
$dictionary['nameMissing'] = "N&#225;zev musi b&#253;t definov&#225;n";
$dictionary['name']='Jm&#233;no';
$dictionary['news']='Novinky';
$dictionary['new_window_target']='Kam se otev&#345;e nov&#233; okno';
$dictionary['no']='Ne';
$dictionary['note']='Pozn&#225;mka';
$dictionary['notes']='Pozn&#225;mky';
$dictionary['overviewTree']='P&#345;ehled';
$dictionary['password']='Heslo';
$dictionary['pluginSettings']='Pluginy';
$dictionary['plugins']='Pluginy';
$dictionary['preferences']='Volby';
$dictionary['priority']='Priorita';
$dictionary['private']='Soukrom&#225;';
$dictionary['public']='Ve&#345;ejn&#225;';
$dictionary['quickmark']='Kliknut&#237;m prav&#253;m tla&#269;&#237;tkem my&#353;i na n&#225;sleduj&#237;c&#237; odkaz se p&#345;id&#225; Quickmark do Z&#225;lo&#382;ek/Obl&#237;ben&#253;ch ve Va&#353;em <b>prohl&#237;&#382;e&#269;i</b>. Poka&#382;d&#233;, kdy&#382; pou&#382;ijete Quickmark ve Va&#353;em prohl&#237;&#382;e&#269;i, str&#225;nka, na n&#237;&#382; se nach&#225;z&#237;te, bude automaticky p&#345;id&#225;na do Z&#225;lo&#382;ek v aplikaci '.$dictionary['programname'].'.<br /><br /><font size="-2">Pros&#237;m klikn&#283;te na "OK", pokud budete t&#225;z&#225;ni na p&#345;id&#225;n&#237; z&#225;lo&#382;ky - k&#243;d, kter&#253; "vyb&#237;r&#225;" adresu str&#225;nky, kterou chcete p&#345;idat do z&#225;lo&#382;ek, n&#283;kter&#233; prohl&#237;&#382;e&#269;e m&#367;&#382;e "znerv&#243;znit".</font><br />';
$dictionary['refresh']='Obnovit';
$dictionary['root']='Root';
$dictionary['search']='Vyhled&#225;v&#225;n&#237;';
$dictionary['selectAll']='Vybrat v&#353;e';
$dictionary['setModePrivate'] = "Vlastn&#237;";
$dictionary['setModePublic'] = "Ve&#345;ejn&#233;";
$dictionary['show']='Uk&#225;zat';
$dictionary['sort']='T&#345;&#237;dit';
$dictionary['submit']='Odeslat';
$dictionary['sysinfo']='SysInfo';
$dictionary['theme']='Motiv';
$dictionary['title']='N&#225;zev';
$dictionary['today']='Dnes';
$dictionary['tasks']='&#218;koly';
$dictionary['task']='&#218;kol';
$dictionary['up']='Nahoru';
$dictionary['locator']='URL';
$dictionary['user']='U&#382;ivatel';
$dictionary['view']="Zobrazen&#237;";
$dictionary['visibility']='Viditelnost:';
$dictionary['webtools']='WebTools';
$dictionary['welcome_page']='<h1>V&#237;tejte, %s </h1><h2>\''.$dictionary['programname'].' - a multithingy something\'</h2>';
$dictionary['yahoo_column_count']='Yahootree po&#269;et sloupc&#367;';
$dictionary['yahooTree']='Adres&#225;&#345;ov&#225; struktura';
$dictionary['yes']='Ano';
$dictionary['item_help']='
	<h1>'.$dictionary['programname'].' n&#225;pov&#283;da</h1>
	<p>
    '.$dictionary['programname'].' m&#225; dv&#283; li&#353;ty, jedna obsahuje nastaven&#237; aplikace
    a druh&#225; obsahuje odkazy na dal&#353;&#237; pluginy.


    N&#225;pov&#283;du pro pluginy najdete <a href="#plugins">zde</a>.
	</p>
	<p>
    Odkaz Volby V&#225;s p&#345;epne na str&#225;nku kde m&#367;&#382;ete nastavit V&#225;&#353; jazyk,
    motiv kter&#253; byste r&#225;di pou&#382;&#237;vali a Va&#353;e osobn&#237; nastaven&#237; jako heslo,
    e-mail atd.

		Jazyk a motiv nem&#367;&#382;ou b&#253;t m&#283;n&#283;ny zar&#225;z!
	</p>
	<p>
		Info odkaz ukazuje obecn&#233; informace o aplikaci, v&#269;etn&#283;
    aktu&#225;ln&#237; verze.
	</p>
	<p>
		Klinut&#237;m na Odhl&#225;sit se provede odhl&#225;&#353;en&#237; z aplikace. Tak&#233; budou zni&#269;eny
		cookie, kter&#233; byli nastaveny, pokud jste pou&#382;ili funkci "zapamatovat" p&#345;i
		Va&#353;em p&#345;ihl&#225;&#353;en&#237;, tak&#382;e pot&#233; bude pot&#345;eba se znovu p&#345;ihl&#225;sit p&#345;ed dal&#353;&#237;m
    pou&#382;it&#237;m '.$dictionary['programname'].'.
	</p>
	<p>
		Plugin sekce dovoluje aktivovat/deaktivovat jednotliv&#233; pluginy.
		Je-li plugin deaktivov&#225;n, nebude se V&#225;m zobrazovat ve Va&#353;&#237; li&#353;te,
		ani v n&#225;pov&#283;d&#283;.
	</p>
';
?>
