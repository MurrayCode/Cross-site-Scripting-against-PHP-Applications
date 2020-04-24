<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Thibaut Cousin
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
        Bas&#233; sur&nbsp;:
</p>
<ul>
        <li><a href="http://pajhome.org.uk/crypt/md5">l\'impl&#233;mentation de MD5 en Javascript par Paul Johnston</a></li>
        <li><a href="http://angel.net/~nic/passwdlet.html">le g&#233;n&#233;rateur de mots de passe de Nic Wolff</a></li>
        <li><a href="http://chris.zarate.org/passwd.txt">les modifications de Chris Zarate</a> pour ignorer les sous-domaines</a></li>
</ul>';
$dictionary['generate']='G&#233;n&#233;rer';
$dictionary['generatePassword']='G&#233;n&#233;rer un mot de passe';
$dictionary['generatedPassword']='Mot de passe g&#233;n&#233;r&#233;';
$dictionary['insecureConnection']='Vous &#234;tes en train d\'utiliser le module Mots de passe sur une connexion non s&#233;curis&#233;e. Toute communication sur cette ligne peut &#234;tre intercept&#233;e&nbsp;!';
$dictionary['item_help']='<p>Le module externe "Mots de passe" vous permet de g&#233;rer vos mots de passe en ligne. Vos mots de passe sont des donn&#233;es pr&#233;cieuses, que vous voudrez stocker <em>crypt&#233;s</em> dans une base de donn&#233;es. En fait, ce module peut stocker n\'importe quel texte sous forme crypt&#233;e.
</p>
<p>
        <font color="red">Vous devez garder en m&#233;moire que les mots de passe sont m&#233;moris&#233;s crypt&#233;s dans la base de donn&#233;es (donc m&#234;me l\'administrateur ne peut pas les lire). Ils sont crypt&#233;s et d&#233;crypt&#233;s au niveau du serveur. Donc si la connexion entre votre serveur et vous n\'est pas s&#233;curis&#233;e (donc si vous utilisez un simple protocole HTTP), alors vos mots de passe transiteront en clair&nbsp;!
        </font>
</p>
<p>Un mot de passe est d&#233;fini par les param&#232;tres suivants&nbsp;:
</p>
<ul>
        <li><em>Nom</em>&nbsp;: le nom du mot de passe.
        </li>
        <li><em>Dossier ou mot de passe</em>&nbsp;: indique si l\'&#233;l&#233;ment &#224; cr&#233;er est un dossier ou un mot de passe. Ce choix, pour un &#233;l&#233;ment donn&#233;, est irr&#233;vocable.
        </li>
        <li><em>Phrase de passe</em>&nbsp;: le mot de passe ou la phrase de passe utilis&#233;e pour crypter les donn&#233;es. Lorsque vous demandez &#224; acc&#233;der &#224; un mot de passe, vous devrez d\'abord taper cette phrase de passe, n&#233;cessaire au d&#233;cryptage.
        </li>
        <li><em>Description</em>&nbsp;: une &#233;ventuelle description du mot de passe. Elle sera m&#233;moris&#233;e crypt&#233;e avec le mot de passe.
        </li>
</ul>
<p>Les sous-menus disponibles pour le module externe Signets sont "Action", "Vue", "Tri", "Pr&#233;f&#233;rences" et "Aide".
</p>
<h3>Actions</h3>
<ul>
        <li><em>Ajouter</em>&nbsp;: cette action donne acc&#232;s &#224; un formulaire o&#249; vous pouvez saisir les informations n&#233;cessaires &#224; la cr&#233;ation d\'un nouveau mot de passe.
        </li>
        <li><em>S&#233;lection multiple</em>&nbsp;: cette action permet de s&#233;lectionner plusieurs mots de passe (mais pas des dossiers) en m&#234;me temps, soit pour les effacer tous ensemble, soit pour les d&#233;placer tous ensemble dans un m&#234;me dossier.
        </li>
        <li><em>Chercher</em>&nbsp;: cette action permet d\'effectuer des recherches dans les noms des mots de passe.
        </li>
</ul>
<h3>Vue</h3>
<ul>
        <li><em>D&#233;velopper</em>&nbsp;: cette action entra&#238;ne le d&#233;ploiement complet de l\'arborescence des mots de passe. Elle n\'est applicable que pour la vue en arborescence.
        </li>
        <li><em>R&#233;duire</em>&nbsp;: suivant cette action, le syst&#232;me ne montre plus que les &#233;l&#233;ments (dossiers et mots de passe) contenus dans le dossier courant.
        </li>
        <li><em>Vue en dossiers</em>&nbsp;: cette action entra&#238;ne l\'affichage des mots de passe avec une vue en dossiers. Cette vue est inspir&#233;e de la mani&#232;re utilis&#233;e par Yahoo! pour montrer une structure.
        <br />
        Le nombre de colonnes pour cette vue peut &#234;tre d&#233;fini dans les pr&#233;f&#233;rences du module externe Mots de passe.
        </li>
        <li><em>Vue en arborescence</em>&nbsp;: cette action entra&#238;ne l\'affichage des mots de passe avec une vue en arborescence, sur le mod&#232;le de nombreux navigateurs et gestionnaires de fichiers.
        </li>
</ul>
<h3>Pr&#233;f&#233;rences</h3>
<ul>
        <li><em>Modifier</em>&nbsp;:  permet d\'acc&#233;der aux pr&#233;f&#233;rences du module externe Mots de passe. Vous pouvez y modifier le nombre de colonnes pour la vue en dossiers, l\'utilisation de popups Javascript au survol des mots de passe et d&#233;finir la vue par d&#233;faut (en dossiers ou en arborescence).
        </li>
</ul>';
$dictionary['item_title']='Mots de passe';
$dictionary['login']='Identifiant de connexion';
$dictionary['masterPassword']='Mot de passe ma&#238;tre';
$dictionary['modifyPasswordPreferences']='Modifier les pr&#233;f&#233;rences du module Mots de passe';
$dictionary['noServerCommunicationUsed']='La g&#233;n&#233;ration de mot de passe se fait c&#244;t&#233; client (en Javascript), sans communication avec le serveur. Cet outil est donc parfaitement s&#251;r m&#234;me si la connexion au serveur ne l\'est pas.';
$dictionary['passPhrase']='Phrase de passe';
$dictionary['passPhraseMissing']='Vous devez indiquer une phrase de passe&nbsp;!';
$dictionary['siteUrl']='URL du site';
$dictionary['url']='Lien';

?>