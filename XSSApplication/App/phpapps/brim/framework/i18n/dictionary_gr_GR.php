<?php

/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
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
$dictionary['about']='&#931;&#967;&#949;&#964;&#953;&#954;&#940;';
$dictionary['about_page']='h2>&#931;&#967;&#949;&#964;&#953;&#954;&#940;</h2>
<p>
	<b>'.$dictionary['programname'].' '.$dictionary['version'].'</b> &#913;&#965;&#964;&#942; &#951; &#949;&#966;&#945;&#961;&#956;&#959;&#947;&#942; &#948;&#951;&#956;&#953;&#959;&#965;&#961;&#947;&#942;&#952;&#951;&#954;&#949; &#945;&#960;&#972; &#964;&#959;&#957; '.$dictionary['authorname'].' (email:
	<a href="mailto:'.$dictionary['authoremail'].'"
	>'.$dictionary['authoremail'].'</a>)
	'.$dictionary['copyright'].' </p> <p> &#931;&#954;&#959;&#960;&#972;&#962; &#949;&#943;&#957;&#945;&#953; &#951; &#960;&#945;&#961;&#959;&#967;&#942; &#956;&#953;&#945;&#962; &#945;&#957;&#959;&#953;&#967;&#964;&#959;&#973; &#954;&#974;&#948;&#953;&#954;&#945; &#945;&#965;&#964;&#972;&#957;&#959;&#956;&#951;&#962; &#949;&#966;&#945;&#961;&#956;&#959;&#947;&#942;&#962; (&#960;.&#967;. &#959;&#953; &#963;&#949;&#955;&#953;&#948;&#959;&#948;&#949;&#943;&#954;&#964;&#949;&#962; &#963;&#945;&#962;, &#949;&#961;&#947;&#945;&#963;&#943;&#949;&#962;, &#954;&#955;&#960;) &#972;&#955;&#945; &#949;&#957;&#963;&#969;&#956;&#945;&#964;&#969;&#956;&#941;&#957;&#945; &#963;&#949; &#956;&#943;&#945; &#949;&#966;&#945;&#961;&#956;&#959;&#947;&#942;
</p>
<p>
	This program ('.$dictionary['programname'].') is released under
	the GNU General Public License. Click
	<a href="documentation/gpl.html">here</a>
	for the full version of the license.
	The homepage of the application can be
	found at the following address: <a
	href="'.$dictionary['programurl'].'"
	>'.$dictionary['programurl'].'</a> </p>
';
$dictionary['actions']='&#917;&#957;&#941;&#961;&#947;&#949;&#953;&#949;&#962;';
$dictionary['activate']='&#917;&#957;&#949;&#961;&#947;&#959;&#960;&#959;&#943;&#951;&#963;&#951;';
$dictionary['add']='&#928;&#961;&#959;&#963;&#952;&#942;&#954;&#951;';
$dictionary['addFolder']='&#928;&#961;&#959;&#963;&#952;&#942;&#954;&#951; &#966;&#945;&#954;&#941;&#955;&#959;&#965;';
$dictionary['addNode']='&#928;&#961;&#959;&#963;&#952;&#942;&#954;&#951; &#945;&#957;&#964;&#953;&#954;&#949;&#953;&#956;&#941;&#957;&#959;&#965;';
$dictionary['adduser']='&#928;&#961;&#959;&#963;&#952;&#942;&#954;&#951; &#967;&#961;&#942;&#963;&#964;&#951;';
$dictionary['admin']='Admin';
$dictionary['adminConfig']='&#916;&#953;&#945;&#956;&#972;&#961;&#966;&#969;&#963;&#951;';
$dictionary['admin_email']='Email &#948;&#953;&#945;&#967;&#949;&#953;&#961;&#953;&#963;&#964;&#942;';
$dictionary['allow_account_creation']='&#917;&#960;&#941;&#964;&#961;&#949;&#968;&#949; &#948;&#951;&#956;&#953;&#959;&#965;&#961;&#947;&#943;&#945; &#955;&#959;&#947;&#945;&#961;&#953;&#945;&#963;&#956;&#959;&#973; &#967;&#961;&#942;&#963;&#964;&#951;';
$dictionary['back']='&#928;&#943;&#963;&#969;';
$dictionary['banking']='E-Banking';
$dictionary['bookmark']='&#931;&#949;&#955;&#953;&#948;&#959;&#948;&#949;&#943;&#954;&#964;&#951;&#962;';
$dictionary['bookmarks']='&#931;&#949;&#955;&#953;&#948;&#959;&#948;&#949;&#943;&#954;&#964;&#949;&#962;';
$dictionary['calendar']='&#919;&#956;&#949;&#961;&#959;&#955;&#972;&#947;&#953;&#959;';
$dictionary['cancel']='&#913;&#954;&#973;&#961;&#969;&#963;&#951;';
$dictionary['charset']='iso-8859-1';
$dictionary['checkbook']='Checkbook';
$dictionary['collapse']='&#922;&#945;&#964;&#940;&#961;&#961;&#949;&#965;&#963;&#951;';
$dictionary['collections']='&#931;&#965;&#955;&#955;&#959;&#947;&#941;&#962;';
$dictionary['confirm']='&#917;&#960;&#953;&#946;&#949;&#946;&#945;&#943;&#969;&#963;&#951;';
$dictionary['confirm_delete']='&#917;&#943;&#963;&#964;&#949; &#963;&#943;&#947;&#959;&#965;&#961;&#959;&#962; &#947;&#953;&#945; &#964;&#951; &#948;&#953;&#945;&#947;&#961;&#945;&#966;&#942;;';
$dictionary['contact']='&#917;&#960;&#945;&#966;&#942;';
$dictionary['contacts']='&#917;&#960;&#945;&#966;&#941;&#962;';
$dictionary['contents']='&#928;&#949;&#961;&#953;&#949;&#967;&#972;&#956;&#949;&#957;&#945;';
$dictionary['dashboard']='&#928;&#943;&#957;&#945;&#954;&#945;&#962; &#949;&#955;&#941;&#947;&#967;&#959;&#965;';
$dictionary['database']='&#914;&#940;&#963;&#951; &#948;&#949;&#948;&#959;&#956;&#941;&#957;&#969;&#957;';
$dictionary['dateFormat']='&#924;&#959;&#961;&#966;&#942; &#951;&#956;&#949;&#961;&#959;&#956;&#951;&#957;&#943;&#945;&#962;';
$dictionary['deactivate']='&#913;&#960;&#949;&#957;&#949;&#961;&#947;&#959;&#960;&#959;&#943;&#951;&#963;&#951;';
$dictionary['defaultTxt']='&#927;&#961;&#953;&#963;&#956;&#972;&#962;';
$dictionary['deleteTxt']='&#916;&#953;&#945;&#947;&#961;&#945;&#966;&#942;';
$dictionary['delete_not_owner']='&#916;&#949;&#957; &#949;&#960;&#953;&#964;&#961;&#941;&#960;&#949;&#964;&#945;&#953; &#957;&#945; &#948;&#953;&#945;&#947;&#961;&#940;&#968;&#949;&#964;&#949; &#945;&#957;&#964;&#953;&#954;&#949;&#943;&#956;&#949;&#957;&#959; &#960;&#959;&#965; &#948;&#949; &#963;&#945;&#962; &#945;&#957;&#942;&#954;&#949;&#953;';
$dictionary['depot']='DepotTracker';
$dictionary['description']='&#928;&#949;&#961;&#953;&#947;&#961;&#945;&#966;&#942;';
$dictionary['deselectAll']='&#913;&#960;&#959;&#949;&#960;&#953;&#955;&#959;&#947;&#942; &#972;&#955;&#969;&#957;';
$dictionary['down']='&#922;&#940;&#964;&#969;';
$dictionary['email']='Email';
$dictionary['expand']='&#917;&#960;&#941;&#954;&#964;&#945;&#963;&#951;';
$dictionary['explorerTree']='&#916;&#949;&#957;&#948;&#961;&#953;&#954;&#942; &#948;&#959;&#956;&#942;';
$dictionary['exportTxt']='&#917;&#958;&#945;&#947;&#969;&#947;&#942;';
$dictionary['exportusers']='&#917;&#958;&#945;&#947;&#969;&#947;&#942; &#967;&#961;&#951;&#963;&#964;&#974;&#957;';
$dictionary['file']='&#913;&#961;&#967;&#949;&#943;&#959;';
$dictionary['findDoubles']='&#917;&#973;&#961;&#949;&#963;&#951; &#948;&#953;&#960;&#955;&#959;&#949;&#947;&#947;&#961;&#945;&#966;&#974;&#957;';
$dictionary['folder']='&#934;&#940;&#954;&#949;&#955;&#959;&#962;';
$dictionary['formError']='&#919; &#965;&#960;&#959;&#946;&#945;&#955;&#955;&#972;&#956;&#949;&#957;&#951; &#966;&#972;&#961;&#956;&#945; &#960;&#949;&#961;&#953;&#941;&#967;&#949;&#953; &#963;&#966;&#940;&#955;&#956;&#945;';
$dictionary['forward']='&#924;&#960;&#961;&#959;&#963;&#964;&#940;';
$dictionary['genealogy']='&#915;&#949;&#957;&#949;&#945;&#955;&#959;&#947;&#943;&#945;';
$dictionary['gmail']='GMail';
$dictionary['help']='&#914;&#959;&#942;&#952;&#949;&#953;&#945;';
$dictionary['home']='&#913;&#961;&#967;&#953;&#954;&#942;';
$dictionary['importTxt']='&#917;&#953;&#963;&#945;&#947;&#969;&#947;&#942;';
$dictionary['importusers']='&#917;&#953;&#963;&#945;&#947;&#969;&#947;&#942; &#967;&#961;&#951;&#963;&#964;&#974;&#957;';
$dictionary['input']='&#917;&#943;&#963;&#959;&#948;&#959;&#962;';
$dictionary['input_error']='&#928;&#945;&#961;&#945;&#954;&#945;&#955;&#959;&#973;&#956;&#949; &#949;&#955;&#941;&#947;&#958;&#964;&#949; &#964;&#945; &#960;&#949;&#948;&#943;&#945; &#949;&#953;&#963;&#945;&#947;&#969;&#947;&#942;&#962;';
$dictionary['installation_path']='Path &#949;&#947;&#954;&#945;&#964;&#940;&#963;&#964;&#945;&#963;&#951;&#962;';
$dictionary['installer_exists']='<h2><font color="red">
&#932;&#959; &#945;&#961;&#967;&#949;&#943;&#959; &#949;&#947;&#954;&#945;&#964;&#940;&#963;&#964;&#945;&#963;&#951;&#962; &#965;&#960;&#940;&#961;&#967;&#949;&#953; &#945;&#954;&#972;&#956;&#945;! &#928;&#945;&#961;&#945;&#954;&#945;&#955;&#959;&#973;&#956;&#949; &#945;&#966;&#945;&#953;&#961;&#941;&#963;&#964;&#949; &#964;&#959;</font></h2>
';
$dictionary['inverseAll']='&#913;&#957;&#945;&#963;&#964;&#961;&#959;&#966;&#942; &#972;&#955;&#969;&#957;';
$dictionary['item_count']='&#913;&#961;&#953;&#952;&#956;&#972;&#962; &#945;&#957;&#964;&#953;&#954;&#949;&#953;&#956;&#941;&#957;&#969;&#957;';
$dictionary['item_help']='<h1>'.$dictionary['programname'].' Help</h1>
<p>
	&#932;&#959; '.$dictionary['programname'].' &#941;&#967;&#949;&#953; &#948;&#973;&#959; &#956;&#960;&#940;&#961;&#949;&#962; &#956;&#949;&#957;&#959;&#973;, &#951; &#956;&#943;&#945; &#955;&#941;&#947;&#949;&#964;&#945;&#953; &#956;&#960;&#940;&#961;&#945; &#949;&#966;&#945;&#961;&#956;&#959;&#947;&#942;&#962; &#954;&#945;&#953; &#960;&#949;&#961;&#953;&#941;&#967;&#949;&#953; &#947;&#949;&#957;&#953;&#954;&#941;&#962; &#949;&#960;&#953;&#955;&#959;&#947;&#941;&#962; &#964;&#951;&#962; &#949;&#966;&#945;&#961;&#956;&#959;&#947;&#942;&#962;, &#951; &#940;&#955;&#955;&#951; &#955;&#941;&#947;&#949;&#964;&#945;&#953; &#956;&#960;&#940;&#961;&#945; plugin plugin bar &#954;&#945;&#953; &#960;&#949;&#961;&#953;&#941;&#967;&#949;&#953; &#964;&#959;&#965;&#962; &#963;&#965;&#957;&#948;&#941;&#963;&#956;&#959;&#965;&#962; &#963;&#964;&#945; &#948;&#953;&#940;&#966;&#959;&#961;&#945; plugins. &#915;&#953;&#945; &#946;&#959;&#942;&#952;&#949;&#953;&#945; &#963;&#967;&#949;&#964;&#953;&#954;&#940; &#956;&#949; &#963;&#965;&#947;&#954;&#949;&#954;&#961;&#953;&#956;&#941;&#957;&#959; plugin, &#954;&#940;&#957;&#964;&#949; &#954;&#955;&#953;&#954; <a href="#plugins">&#949;&#948;&#974;</a>.
</p>
<p>
	&#927; &#963;&#973;&#957;&#948;&#949;&#963;&#956;&#959;&#962; &#949;&#960;&#953;&#955;&#959;&#947;&#974;&#957; &#963;&#964;&#951;&#957; &#956;&#960;&#940;&#961;&#945; &#949;&#966;&#945;&#961;&#956;&#959;&#947;&#942;&#962; &#963;&#945;&#962; &#960;&#951;&#947;&#945;&#943;&#957;&#949;&#953; &#963;&#949; &#956;&#943;&#945; &#959;&#952;&#972;&#957;&#951; &#963;&#964;&#951;&#957; &#959;&#960;&#959;&#943;&#945; &#956;&#960;&#959;&#961;&#949;&#943;&#964;&#949; &#957;&#945; &#959;&#961;&#943;&#963;&#949;&#964;&#949; &#964;&#951; &#947;&#955;&#974;&#963;&#963;&#945; &#963;&#945;&#962;, &#964;&#959; &#952;&#941;&#956;&#945; &#960;&#959;&#965; &#949;&#960;&#953;&#952;&#965;&#956;&#949;&#943;&#964;&#949; &#957;&#945; &#967;&#961;&#951;&#963;&#953;&#956;&#959;&#960;&#959;&#953;&#942;&#963;&#949;&#964;&#949; &#954;&#945;&#953; &#964;&#953;&#962; &#960;&#961;&#959;&#963;&#969;&#960;&#953;&#954;&#941;&#962; &#963;&#945;&#962; &#949;&#960;&#953;&#955;&#959;&#947;&#941;&#962; &#972;&#960;&#969;&#962; &#959;&#953; &#954;&#969;&#948;&#953;&#954;&#959;&#943;, &#964;&#945; email &#954;&#955;&#960;. &#931;&#951;&#956;&#949;&#953;&#974;&#963;&#964;&#949; &#972;&#964;&#953; &#951; &#947;&#955;&#974;&#963;&#963;&#945; &#954;&#945;&#953; &#964;&#959; &#952;&#941;&#956;&#945; &#948;&#949;&#957; &#956;&#960;&#959;&#961;&#959;&#973;&#957; &#957;&#945; &#959;&#961;&#953;&#963;&#952;&#959;&#973;&#957; &#964;&#945;&#965;&#964;&#972;&#967;&#961;&#959;&#957;&#945;!
</p>
<p>
	&#927; &#963;&#973;&#957;&#948;&#949;&#963;&#956;&#959;&#962; &#960;&#955;&#951;&#961;&#959;&#966;&#959;&#961;&#953;&#974;&#957; &#948;&#949;&#943;&#967;&#957;&#949;&#953; &#947;&#949;&#957;&#953;&#954;&#941;&#962; &#960;&#955;&#951;&#961;&#959;&#966;&#959;&#961;&#943;&#949;&#962; &#964;&#951;&#962; &#949;&#966;&#945;&#961;&#956;&#959;&#947;&#942;&#962;, &#963;&#965;&#956;&#960;&#949;&#961;&#953;&#955;&#945;&#956;&#946;&#945;&#957;&#959;&#956;&#941;&#957;&#959;&#965; &#964;&#959;&#965; &#945;&#961;&#953;&#952;&#956;&#959;&#973; &#964;&#951;&#962; &#964;&#961;&#941;&#967;&#959;&#965;&#963;&#945;&#962; &#941;&#954;&#948;&#959;&#963;&#951;&#962;
</p>
<p>
	&#928;&#945;&#964;&#974;&#957;&#964;&#945;&#962; &#964;&#959; &#963;&#973;&#957;&#948;&#949;&#963;&#956;&#959; &#945;&#960;&#959;&#963;&#973;&#957;&#948;&#949;&#963;&#951;&#962; &#952;&#945; &#945;&#960;&#959;&#963;&#965;&#957;&#948;&#949;&#952;&#949;&#943;&#964;&#949; &#945;&#960;&#972; &#964;&#951;&#957; &#949;&#966;&#945;&#961;&#956;&#959;&#947;&#942;. &#913;&#965;&#964;&#972;&#962; &#959; &#963;&#973;&#957;&#948;&#949;&#963;&#956;&#959;&#962; &#949;&#960;&#943;&#963;&#951;&#962; &#952;&#945; &#948;&#953;&#945;&#947;&#961;&#940;&#968;&#949;&#953; &#964;&#959; cookie &#960;&#959;&#965; &#959;&#961;&#943;&#963;&#952;&#951;&#954;&#949; &#972;&#964;&#945;&#957; &#967;&#961;&#951;&#963;&#953;&#956;&#959;&#960;&#959;&#953;&#942;&#963;&#945;&#964;&#949; &#964;&#951;&#957; &#949;&#960;&#953;&#955;&#959;&#947;&#942; "&#933;&#960;&#949;&#957;&#952;&#973;&#956;&#953;&#963;&#951;" &#954;&#945;&#964;&#940; &#964;&#951; &#963;&#973;&#957;&#948;&#949;&#963;&#942; &#963;&#945;&#962;, &#959;&#960;&#972;&#964;&#949; &#945;&#961;&#947;&#972;&#964;&#949;&#961;&#945; &#952;&#945; &#960;&#961;&#941;&#960;&#949;&#953; &#957;&#945; &#949;&#960;&#945;&#957;&#945;&#963;&#965;&#957;&#948;&#949;&#952;&#949;&#943;&#964;&#949; &#960;&#961;&#953;&#957; &#967;&#961;&#951;&#963;&#956;&#953;&#956;&#959;&#960;&#959;&#953;&#942;&#963;&#949;&#964;&#949; &#964;&#959; '.$dictionary['programname'].'>
</p>
<p>
	&#927; &#964;&#959;&#956;&#941;&#945;&#962; &#964;&#969;&#957; plugin &#963;&#945;&#962; &#949;&#960;&#953;&#964;&#961;&#941;&#960;&#949;&#953; &#957;&#945; &#949;&#957;&#949;&#961;&#947;&#959;&#960;&#959;&#953;&#942;&#963;&#949;&#964;&#949;/&#945;&#960;&#949;&#957;&#949;&#961;&#947;&#959;&#960;&#959;&#953;&#942;&#963;&#949;&#964;&#949; &#964;&#945; plugin. &#917;&#940;&#957; &#941;&#957;&#945; plugin &#949;&#943;&#957;&#945;&#953; &#945;&#960;&#949;&#957;&#949;&#961;&#947;&#959;&#960;&#959;&#953;&#951;&#956;&#941;&#957;&#959;, &#948;&#949; &#952;&#945; &#949;&#956;&#966;&#945;&#957;&#953;&#963;&#964;&#949;&#943; &#963;&#964;&#951; &#956;&#960;&#940;&#961;&#945; plugin, &#959;&#973;&#964;&#949; &#963;&#964;&#959;&#957; &#964;&#959;&#956;&#941;&#945; &#964;&#951;&#962; &#946;&#959;&#942;&#952;&#949;&#953;&#945;&#962;.
</p>
';
$dictionary['item_private']='&#928;&#961;&#959;&#963;&#969;&#960;&#953;&#954;&#972; &#945;&#957;&#964;&#953;&#954;&#949;&#943;&#956;&#949;&#957;&#959;';
$dictionary['item_public']='&#924;&#959;&#943;&#961;&#945;&#963;&#949; &#945;&#965;&#964;&#972; &#964;&#959; &#945;&#957;&#964;&#953;&#954;&#949;&#943;&#956;&#949;&#957;&#959;';
$dictionary['javascript_popups']='Javascript popups';
$dictionary['language']='&#915;&#955;&#974;&#963;&#963;&#945;';
$dictionary['last_created']='&#932;&#949;&#955;&#949;&#965;&#964;&#945;&#943;&#945; &#948;&#951;&#956;&#953;&#959;&#965;&#961;&#947;&#951;&#956;&#941;&#957;&#959;';
$dictionary['last_modified']='&#932;&#949;&#955;&#949;&#965;&#964;&#945;&#943;&#945; &#964;&#961;&#959;&#960;&#959;&#960;&#959;&#953;&#951;&#956;&#941;&#957;&#959;';
$dictionary['last_visited']='&#932;&#949;&#955;&#949;&#965;&#964;&#945;&#943;&#945; &#949;&#960;&#943;&#963;&#954;&#949;&#968;&#951;';
$dictionary['license_disclaimer']='&#919; &#945;&#961;&#967;&#953;&#954;&#942; &#963;&#949;&#955;&#943;&#948;&#945; &#964;&#959;&#965; '.$dictionary['programname'].' project &#946;&#961;&#943;&#963;&#954;&#949;&#964;&#945;&#953; &#963;&#964;&#951;&#957; &#945;&#954;&#972;&#955;&#959;&#965;&#952;&#951; &#948;&#953;&#949;&#973;&#952;&#965;&#957;&#963;&#951;:
	<a href="'.$dictionary['programurl'].'"
	>'.$dictionary['programurl'].'</a>
	<br />
	'.$dictionary['copyright'].' '.$dictionary['authorname'].'
	(<a href="'.$dictionary['authorurl'].'"
	>'.$dictionary['authorurl'].'</a>).
&#924;&#960;&#959;&#961;&#949;&#943;&#964;&#949; &#957;&#945; &#949;&#960;&#953;&#954;&#959;&#953;&#957;&#969;&#957;&#942;&#963;&#949;&#964;&#949; &#956;&#945;&#950;&#943; &#956;&#959;&#965; &#963;&#964;&#959; <a
	href="mailto:'.$dictionary['authoremail'].'"
	>'.$dictionary['authoremail'].'</a>.  <br />
	This program ('.$dictionary['programname'].') is free software;
	you can redistribute it and/or modify
	it under the terms of the GNU General
	Public License as published by the
	Free Software Foundation; either
	version 2 of the License, or
	(at your option) any later version.
	Click <a href="documentation/gpl.html"
	>here</a> for the full version of the
	license.
';
$dictionary['lineBasedTree']='Line based';
$dictionary['link']='&#963;&#973;&#957;&#948;&#949;&#963;&#956;&#959;&#962;';
$dictionary['locator']='URL';
$dictionary['loginName']='&#908;&#957;&#959;&#956;&#945; login';
$dictionary['logout']='&#913;&#960;&#959;&#963;&#973;&#957;&#948;&#949;&#963;&#951;';
$dictionary['mail']='&#932;&#945;&#967;&#965;&#948;&#961;&#959;&#956;&#949;&#943;&#959;';
$dictionary['message']='&#924;&#942;&#957;&#965;&#956;&#945;';
$dictionary['modify']='&#932;&#961;&#959;&#960;&#959;&#960;&#959;&#943;&#951;&#963;&#951;';
$dictionary['modify_not_owner']='&#916;&#949;&#957; &#949;&#960;&#953;&#964;&#961;&#941;&#960;&#949;&#964;&#945;&#953; &#957;&#945; &#948;&#953;&#945;&#947;&#961;&#940;&#968;&#949;&#964;&#949; &#945;&#957;&#964;&#953;&#954;&#949;&#943;&#956;&#949;&#957;&#959; &#960;&#959;&#965; &#948;&#949; &#963;&#945;&#962; &#945;&#957;&#942;&#954;&#949;&#953;';
$dictionary['month01']='&#921;&#945;&#957;&#959;&#965;&#940;&#961;&#953;&#959;&#962;';
$dictionary['month02']='&#934;&#949;&#946;&#961;&#959;&#965;&#940;&#961;&#953;&#959;&#962;';
$dictionary['month03']='&#924;&#940;&#961;&#964;&#953;&#959;&#962;';
$dictionary['month04']='&#913;&#960;&#961;&#943;&#955;&#953;&#959;&#962;';
$dictionary['month05']='&#924;&#940;&#953;&#959;&#962;';
$dictionary['month06']='&#921;&#959;&#973;&#957;&#953;&#959;&#962;';
$dictionary['month07']='&#921;&#959;&#973;&#955;&#953;&#959;&#962;';
$dictionary['month08']='&#913;&#973;&#947;&#959;&#965;&#963;&#964;&#959;&#962;';
$dictionary['month09']='&#931;&#949;&#960;&#964;&#941;&#956;&#946;&#961;&#953;&#959;&#962;';
$dictionary['month10']='&#927;&#954;&#964;&#974;&#946;&#961;&#953;&#959;&#962;';
$dictionary['month11']='&#925;&#959;&#941;&#956;&#946;&#961;&#953;&#959;&#962;';
$dictionary['month12']='&#916;&#949;&#954;&#941;&#956;&#946;&#961;&#953;&#959;&#962;';
$dictionary['most_visited']='&#928;&#949;&#961;&#953;&#963;&#963;&#972;&#964;&#949;&#961;&#949;&#962; &#949;&#960;&#953;&#963;&#954;&#941;&#968;&#949;&#953;&#962;';
$dictionary['move']='&#924;&#949;&#964;&#945;&#954;&#943;&#957;&#951;&#963;&#951;';
$dictionary['multipleSelect']='&#928;&#959;&#955;&#955;&#945;&#960;&#955;&#942; &#949;&#960;&#953;&#955;&#959;&#947;&#942;';
$dictionary['mysqlAdmin']='MySQL';
$dictionary['name']='&#908;&#957;&#959;&#956;&#945;';
$dictionary['nameMissing']='&#928;&#961;&#941;&#960;&#949;&#953; &#957;&#945; &#959;&#961;&#953;&#963;&#952;&#949;&#943; &#972;&#957;&#959;&#956;&#945;';
$dictionary['new_window_target']='&#928;&#959;&#973; &#945;&#957;&#959;&#943;&#947;&#949;&#953; &#964;&#959; &#957;&#941;&#959; &#960;&#945;&#961;&#940;&#952;&#965;&#961;&#959;';
$dictionary['news']='&#925;&#941;&#945;';
$dictionary['no']='&#908;&#967;&#953;';
$dictionary['noSearchResult']='&#922;&#945;&#957;&#941;&#957;&#945; &#945;&#960;&#959;&#964;&#941;&#955;&#949;&#963;&#956;&#945; &#945;&#957;&#945;&#950;&#942;&#964;&#951;&#963;&#951;&#962;';
$dictionary['note']='&#931;&#951;&#956;&#949;&#943;&#969;&#963;&#951;';
$dictionary['notes']='&#931;&#951;&#956;&#949;&#953;&#974;&#963;&#949;&#953;&#962;';
$dictionary['overviewTree']='&#917;&#960;&#953;&#963;&#954;&#972;&#960;&#951;&#963;&#951; &#916;&#941;&#957;&#948;&#961;&#959;&#965;';
$dictionary['password']='&#922;&#969;&#948;&#953;&#954;&#972;&#962;';
$dictionary['passwords']='&#922;&#969;&#948;&#953;&#954;&#959;&#943;';
$dictionary['pluginSettings']='Plugins';
$dictionary['plugins']='Plugins';
$dictionary['polardata']='&#928;&#959;&#955;&#953;&#954;&#940; &#948;&#949;&#948;&#959;&#956;&#941;&#957;&#945;';
$dictionary['preferedIconSize']='&#928;&#961;&#959;&#964;&#953;&#956;&#972;&#956;&#949;&#957;&#959; &#956;&#941;&#947;&#949;&#952;&#959;&#962; &#949;&#953;&#954;&#959;&#957;&#953;&#948;&#943;&#959;&#965;';
$dictionary['preferences']='&#917;&#960;&#953;&#955;&#959;&#947;&#941;&#962;';
$dictionary['priority']='&#928;&#961;&#959;&#964;&#949;&#961;&#945;&#953;&#972;&#964;&#951;&#964;&#945;';
$dictionary['private']='&#928;&#961;&#959;&#963;&#969;&#960;&#953;&#954;&#972;';
$dictionary['public']='&#922;&#959;&#953;&#957;&#972;';
$dictionary['quickmark']='&#922;&#940;&#957;&#964;&#949; &#948;&#949;&#958;&#943; &#954;&#955;&#953;&#954; &#963;&#964;&#959;&#957; &#945;&#954;&#972;&#955;&#959;&#965;&#952;&#959; &#963;&#973;&#957;&#948;&#949;&#963;&#956;&#959; &#947;&#953;&#945; &#957;&#945; &#964;&#959;&#957; &#960;&#961;&#959;&#963;&#952;&#941;&#963;&#949;&#964;&#949; &#963;&#964;&#959;&#965;&#962; &#931;&#949;&#955;&#953;&#948;&#959;&#948;&#949;&#943;&#954;&#964;&#949;&#962;/&#913;&#947;&#945;&#960;&#951;&#956;&#941;&#957;&#945; &#963;&#964;&#959;&#957; <b>browser</b> &#963;&#945;&#962;. <br />&#922;&#940;&#952;&#949; &#966;&#959;&#961;&#940; &#960;&#959;&#965; &#967;&#961;&#951;&#963;&#953;&#956;&#959;&#960;&#959;&#953;&#949;&#943;&#964;&#949; &#945;&#965;&#964;&#972; &#964;&#959;&#957; &#963;&#949;&#955;&#953;&#948;&#959;&#948;&#949;&#943;&#954;&#964;&#951; &#945;&#960;&#972; &#964;&#960;&#965;&#949; &#963;&#949;&#955;&#953;&#948;&#959;&#948;&#949;&#943;&#954;&#964;&#949;&#962; &#964;&#959;&#965; browser &#963;&#945;&#962;, &#951; &#963;&#949;&#955;&#943;&#948;&#945; &#960;&#959;&#965; &#946;&#961;&#943;&#963;&#954;&#949;&#963;&#964;&#949; &#945;&#965;&#964;&#972;&#956;&#945;&#964;&#945; &#954;&#945;&#964;&#945;&#967;&#969;&#961;&#949;&#943;&#964;&#945;&#953; &#963;&#964;&#959;&#965;&#962; &#963;&#949;&#955;&#953;&#948;&#959;&#948;&#949;&#943;&#954;&#964;&#949;&#962; &#963;&#945;&#962;.';
$dictionary['recipes']='&#931;&#965;&#957;&#964;&#945;&#947;&#941;&#962;';
$dictionary['refresh']='&#913;&#957;&#945;&#957;&#941;&#969;&#963;&#951;';
$dictionary['root']='&#928;&#951;&#947;&#942;';
$dictionary['search']='&#913;&#957;&#945;&#950;&#942;&#964;&#951;&#963;&#951;';
$dictionary['select']='&#917;&#960;&#953;&#955;&#959;&#947;&#942;';
$dictionary['selectAll']='&#917;&#960;&#953;&#955;&#959;&#947;&#942; &#972;&#955;&#969;&#957;';
$dictionary['setModePrivate']='&#932;&#945; &#960;&#961;&#959;&#963;&#969;&#960;&#953;&#954;&#940; &#956;&#959;&#965;';
$dictionary['setModePublic']='&#922;&#959;&#953;&#957;&#972;&#967;&#961;&#951;&#963;&#964;&#945;';
$dictionary['show']='&#928;&#961;&#959;&#946;&#959;&#955;&#942;';
$dictionary['showTips']='&#928;&#961;&#959;&#946;&#959;&#955;&#942; tips';
$dictionary['sort']='&#932;&#945;&#958;&#953;&#957;&#972;&#956;&#951;&#963;&#951;';
$dictionary['spellcheck']='&#904;&#955;&#949;&#947;&#967;&#959;&#962; &#963;&#965;&#955;&#955;&#945;&#946;&#953;&#963;&#956;&#959;&#973;';
$dictionary['submit']='&#933;&#960;&#959;&#946;&#959;&#955;&#942;';
$dictionary['synchronizer']='&#931;&#965;&#947;&#967;&#961;&#959;&#957;&#953;&#963;&#964;&#942;&#962;';
$dictionary['sysinfo']='SysInfo';
$dictionary['task']='&#917;&#961;&#947;&#945;&#963;&#943;&#945;';
$dictionary['tasks']='&#917;&#961;&#947;&#945;&#963;&#943;&#949;&#962;';
$dictionary['textsource']='&#928;&#951;&#947;&#942; &#954;&#949;&#953;&#956;&#941;&#957;&#959;&#965;';
$dictionary['theme']='&#920;&#941;&#956;&#945;';
$dictionary['tip']='Tip';
$dictionary['title']='&#932;&#943;&#964;&#955;&#959;&#962;';
$dictionary['today']='&#931;&#942;&#956;&#949;&#961;&#945;';
$dictionary['translate']='&#924;&#949;&#964;&#940;&#966;&#961;&#945;&#963;&#951;';
$dictionary['up']='&#928;&#940;&#957;&#969;';
$dictionary['user']='&#935;&#961;&#942;&#963;&#964;&#951;&#962;';
$dictionary['view']='&#928;&#961;&#959;&#946;&#959;&#955;&#942;';
$dictionary['visibility']='&#927;&#961;&#945;&#964;&#972;&#964;&#951;&#964;&#945;';
$dictionary['webtools']='WebTools';
$dictionary['welcome_page']='<h1>&#922;&#945;&#955;&#969;&#963;&#942;&#961;&#952;&#945;&#964;&#949; %s </h1><h2>'.$dictionary['programname'].' -
a multithingy something </h2>';
$dictionary['yahooTree']='&#916;&#959;&#956;&#942; &#954;&#945;&#964;&#945;&#955;&#972;&#947;&#959;&#965;';
$dictionary['yahoo_column_count']='Yahootree &#945;&#961;&#943;&#952;&#956;&#951;&#963;&#951; &#963;&#964;&#951;&#955;&#974;&#957;';
$dictionary['yes']='&#925;&#945;&#953;';

?>
