<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.passwords
 * @subpackage i18n
 *
 * @copyright Brim - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
if (!isset ($dictionary))
{
	$dictionary = array ();
}
$dictionary['credits']='<p>
Gebaseerd op:
</p>
<ul>
<li><li><a href="http://pajhome.org.uk/crypt/md5"
		>Paul Johnston</a>\'s MD5 javascript implementatie</a></li>
	<li><a href="http://angel.net/~nic/passwdlet.html"
		>Nic Wolff</a>\'s pasword generator</li>
	<li><a href="http://chris.zarate.org/passwd.txt"
		>Chris Zarate</a>\'s wijziging om subdomeinen te negeren</a>
</li>
</ul>';
$dictionary['generate']='Genereer';
$dictionary['generatePassword']='Genereer paswoord';
$dictionary['generatedPassword']='Gegenereerd paswoord';
$dictionary['insecureConnection']='Je gebruikt deze plugin over een onveilige lijn. Let erop dat de
fysieke communicatie ondeschept kan worden!';
$dictionary['item_title']='Paswoorden';
$dictionary['login']='Login naam';
$dictionary['masterPassword']='Hoofd paswoord';
$dictionary['modifyPasswordPreferences']='Wijzig de paswoord voorkeuren';
$dictionary['noServerCommunicationUsed']='Genereer paswoord berekent je paswoord via javascript 
aan de zeide van de klant (browser), er is geen communicatie
met de server. Deze tool kan je veilig gebruiken ongeacht de connectie met de server';
$dictionary['passPhrase']='Encryptiesleutel';
$dictionary['passPhraseMissing']='Passphrase mist!';
$dictionary['siteUrl']='Site URL';
$dictionary['url']='Link';

?>