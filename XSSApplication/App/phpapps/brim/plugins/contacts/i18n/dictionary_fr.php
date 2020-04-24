<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Thibaut Cousin
 * @package org.brim-project.plugins.contacts
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
$dictionary['address']='Adresse';
$dictionary['alias']='Surnom';
$dictionary['birthday']='Date de naissance';
$dictionary['clickHere']='Cliquez ici';
$dictionary['email']='Adresse &#233;lectronique';
$dictionary['email1']='Adresse &#233;lectronique (personnelle)';
$dictionary['email2']='Adresse &#233;lectronique (professionnelle)';
$dictionary['email3']='Adresse &#233;lectronique (autre)';
$dictionary['email_home']='Adresse &#233;lectronique&#160;(personnelle)';
$dictionary['email_other']='Adresse &#233;lectronique&#160;(autre)';
$dictionary['email_work']='Adresse &#233;lectronique&#160;(professionnelle)';
$dictionary['faximile']='Fax (professionnel)';
$dictionary['item_help']='<p>Le module externe "Contacts" vous permet de g&#233;rer vos contacts en ligne. Un contact est d&#233;fini par les param&#232;tres suivants&#160;:
</p>
<ul>
        <li><em>Nom</em>&#160;: le nom du contact.
        </li>
        <li><em>Dossier ou contact</em>&#160;: indique si l\'&#233;l&#233;ment &#224; cr&#233;er est un dossier ou un contact. Ce choix, pour un &#233;l&#233;ment donn&#233;, est irr&#233;vocable.
        </li>
        <li><em>Public ou priv&#233;</em>&#160;: indique si l\'&#233;l&#233;ment est public ou non.
        <br />
        Si vous voulez qu\'un &#233;l&#233;ment soit public, il faut que son &#233;l&#233;ment parent soit public aussi ! La racine de l\'arborescence est publique par d&#233;faut.
        </li>
        <li><em>T&#233;l&#233;phone (personnel)</em>&#160;: le num&#233;ro de t&#233;l&#233;phone personnel du contact.
        </li>
        <li><em>T&#233;l&#233;phone (professionnel)</em>&#160;: le num&#233;ro de t&#233;l&#233;phone professionnel du contact.
        </li>
        <li><em>Fax</em>&#160;: le num&#233;ro de fax du contact.
        </li>
        <li><em>Adresse &#233;lectronique (personnelle)</em>&#160;: vous pouvez saisir jusqu\'&#224; trois adresses &#233;lectroniques par contact. Celle-ci est l\'adresse personnelle du contact.
        </li>
        <li><em>Adresse &#233;lectronique (professionnelle)</em>&#160;:  vous pouvez saisir jusqu\'&#224; trois adresses &#233;lectroniques par contact. Celle-ci est l\'adresse professionnelle du contact.
        </li>
        <li><em>Adresse &#233;lectronique (autre)</em>&#160;: vous pouvez saisir jusqu\'&#224; trois adresses &#233;lectroniques par contact. Celle-ci est une autre adresse &#233;lectronique du contact.
        </li>
        <li><em>Site web (page d\'accueil)</em>&#160;: vous pouvez saisir jusqu\'&#224; trois adresses de sites web par contact. Celle-ci est l\'adresse de la page d\'accueil du contact.
        </li>
        <li><em>Site web (professionnel)</em>&#160;: vous pouvez saisir jusqu\'&#224; trois adresses de sites web par contact. Celle-ci est l\'adresse du site web professionnel du contact.
        </li>
        <li><em>Site web (personnel)</em>&#160;: vous pouvez saisir jusqu\'&#224; trois adresses de sites web par contact. Celle-ci est l\'adresse du site web personnel du contact.
        </li>
        <li><em>Fonction</em>&#160;: la fonction du contact dans son entreprise.
        </li>
        <li><em>Surnom</em>&#160;: le surnom du contact (peut-&#234;tre utilis&#233; lors d\'une recherche).
        </li>
        <li><em>Organisation</em>&#160;: le nom de l\'entreprise ou de l\'organisme dans lequel le contact travaille.
        </li>
        <li><em>Adresse (personnelle)</em>&#160;: l\'adresse personnelle du contact.
        </li>
        <li><em>Adressse (professionnelle)</em>&#160;: l\'adresse de l\'organisation dans laquelle travaille le contact.
        </li>
        <li><em>Description</em>&#160;: une description du contact.
        </li>
</ul>
<p>Les sous-menus disponibles pour le module externe Signets sont "Action", "Vue", "Tri", "Pr&#233;f&#233;rences" et "Aide".
</p>
<h3>Actions</h3>
<ul>
        <li><em>Ajouter</em>&#160;: cette action donne acc&#232;s &#224; un formulaire o&#249; vous pouvez saisir les informations n&#233;cessaires &#224; la cr&#233;ation d\'un nouvel &#233;l&#233;ment.
        </li>
        <li><em>S&#233;lection multiple</em>&#160;: cette action permet de s&#233;lectionner plusieurs contacts (mais pas des dossiers) en m&#234;me temps, soit pour les effacer tous ensemble, soit pour les d&#233;placer tous ensemble dans un m&#234;me dossier.
        </li>
        <li><em>Importer</em>&#160;: cette action permet d\'importer des contacts. Actuellement, vous pouvez importer des contacts au format Opera ou vCard.
        <br />
        Lors de l\'importation, vous pouvez d&#233;cider si les signets import&#233;s seront publics ou priv&#233;s.
        <br />
        Importer depuis un dossier particulier est possible. Il suffit de se placer dans le dossier en question puis de choisir l\'action "Importer".
        </li>
        <li><em>Exporter</em>&#160;: cette action permet d\'exporter des signets au format Opera ou vCard (compatible avec de nombreux logiciels de mail et de carnet d\'adresses).
        </li>
        <li><em>Chercher</em>&#160;: cette action permet d\'effectuer des recherches dans les noms, surnoms, descriptions et adresses des contacts.
        </li>
</ul>
<h3>Vue</h3>
<ul>
        <li><em>D&#233;velopper</em>&#160;: cette action entra&#238;ne le d&#233;ploiement complet de l\'arborescence des contacts. Elle n\'est applicable que pour la vue en arborescence.
        </li>
        <li><em>R&#233;duire</em>&#160;: suivant cette action, le syst&#232;me ne montre plus que les &#233;l&#233;ments (dossiers et contacts) contenus dans le dossier courant.
        </li>
        <li><em>Vue en dossiers</em>&#160;: cette action entra&#238;ne l\'affichage des contacts avec une vue en dossiers. Cette vue est inspir&#233;e de la mani&#232;re utilis&#233;e par Yahoo! pour montrer une structure.
        <br />
        Le nombre de colonnes pour cette vue peut &#234;tre d&#233;fini dans les pr&#233;f&#233;rences du module externe Contacts.
        </li>
        <li><em>Vue en arborescence</em>&#160;:  cette action entra&#238;ne l\'affichage des contacts avec une vue en arborescence, sur le mod&#232;le de nombreux navigateurs et gestionnaires de fichiers.
        </li>
        <li><em>Vue en lignes</em>&#160;: un autre affichage possible des contacts, en lignes avec de nombreux d&#233;tails.
        <li><em>Afficher tous les contacts publics</em>&#160;: affiche tous les contacts publics de tous les utilisateurs en plus de vos propres contacts.
        </li>
        <li><em>Afficher vos contacts</em>&#160;: n\'affiche que vos contacts, par opposition avec l\'action "Afficher tous les contacts publics".
        </li>
</ul>
<h3>Tri</h3>
<ul>
        <li><em>Surnom</em>&#160;: affiche les contacts tri&#233;s selon leur surnom.
        </li>
        <li><em>Adresse &#233;lectronique (personnelle)</em>&#160;: affiche les contacts tri&#233;s selon leur adresse &#233;lectronique personnelle.
        </li>
        <li><em>Organisation</em>&#160;: affiche les contacts tri&#233;s selon l\'organisation o&#249; ils travaillent.
        </li>
</ul>
<h3>Pr&#233;f&#233;rences</h3>
<ul>
        <li><em>Modifier</em>&#160;: permet d\'acc&#233;der aux pr&#233;f&#233;rences du module externe Contacts. Vous pouvez y modifier le nombre de colonnes pour la vue en dossiers, l\'utilisation de popups Javascript au survol des contacts et d&#233;finir la vue par d&#233;faut (en dossiers, en arborescence ou en lignes).
        </li>
</ul>';
$dictionary['item_title']='Contacts';
$dictionary['job']='Fonction';
$dictionary['mobile']='Portable';
$dictionary['modifyContactPreferences']='Modifier les pr&#233;f&#233;rences du module Contacts';
$dictionary['org_address']='Adresse (professionnelle)';
$dictionary['organization']='Organisation';
$dictionary['tel_home']='T&#233;l&#233;phone (personnel)';
$dictionary['tel_work']='T&#233;l&#233;phone (professionnel)';
$dictionary['webaddress']='Site web';
$dictionary['webaddress_home']='Site web (personnel)';
$dictionary['webaddress_homepage']='Site web (page d\'accueil)';
$dictionary['webaddress_work']='Site web (professionnel)';

?>