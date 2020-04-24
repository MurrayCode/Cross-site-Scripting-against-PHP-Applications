<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Thibaut Cousin
 * @package org.brim-project.plugins.bookmarks
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
$dictionary['autoAppendProtocol']='Ajouter le pr&#233;fixe http:// automatiquement si l\'URL ne comporte aucun indicateur de protocole';
$dictionary['deleteFavicon']='Supprimer le favicon';
$dictionary['favicon']='Favicon';
$dictionary['faviconDeleted']='Ic&#244;ne supprim&#233;e. Cliquez sur "Modifier" pour enregistrer le r&#233;sultat.';
$dictionary['faviconFetched']='Ic&#244;ne r&#233;cup&#233;r&#233;e. Cliquez sur "Modifier" pour enregistrer le r&#233;sultat.';
$dictionary['fetchingFavicon']='R&#233;cup&#233;ration du favicon&#160;!';
$dictionary['installationPathNotSet']='<p>Le chemin d\'installation n\'est pas d&#233;fini. Il est pourtant n&#233;cessaire pour que QuickMark fonctionne. Veuillez contacter votre administrateur &#224; ce sujet.</p>';
$dictionary['item_help']='<p>Le module externe "Signets" vous permet de g&#233;rer vos signets en ligne.
</p>
<p>Cliquez sur l\'ic&#244;ne de dossier ou d\'&#233;l&#233;ment se trouvant en d&#233;but de ligne pour d&#233;placer, supprimer ou modifier un signet.
</p>
<p>Pour d&#233;placer un signet, cliquez sur "Modifier", puis "D&#233;placer", puis cliquez sur le dossier o&#249; vous voulez placer le signet.
</p>
<p>Vous pouvez jouer sur les param&#232;tres suivants&#160;:
</p>
<ul>
        <li><em>Nom</em>&#160;: le nom du signet. Par exemple, [nauta.be] pour mon site personnel.
        </li>
        <li><em>Dossier ou signet</em>&#160;: indique si l\'&#233;l&#233;ment &#224; cr&#233;er est un dossier ou un signet. Ce choix, pour un &#233;l&#233;ment donn&#233;, est irr&#233;vocable.
        </li>
        <li><em>Public ou priv&#233;</em>&#160;: indique si l\'&#233;l&#233;ment est public ou non.
                <br />
                Si vous voulez qu\'un &#233;l&#233;ment soit public, il faut que son &#233;l&#233;ment parent soit public aussi&#160;! La racine de l\'arborescence est publique par d&#233;faut.
        </li>
        <li><em>URL</em>&#160;: l\'URL du signet. Elle doit commencer par un indicateur de protocole (http:// ou ftp://) pour que Brim puisse la g&#233;rer correctement.
        </li>
        <li><em>Description</em>&#160;: une &#233;ventuelle description du signet.
        </li>
        </ul>
        <p>Les sous-menus disponibles pour le module externe Signets sont "Action", "Vue", "Tri", "Pr&#233;f&#233;rences" et "Aide".
        </p>
        <h3>Actions</h3>
        <ul>
        <li><em>Ajouter</em>&#160;: cette action donne acc&#232;s &#224; un formulaire o&#249; vous pouvez saisir les informations n&#233;cessaires &#224; la cr&#233;ation d\'un nouvel &#233;l&#233;ment. Rappelez-vous que l\'URL d\'un signet doit commencer par un indicateur de protocole (http:// ou ftp://) pour que Brim puisse la g&#233;rer correctement.
        </li>
        <li><em>S&#233;lection multiple</em>&#160;: cette action permet de s&#233;lectionner plusieurs signets (mais pas des dossiers) en m&#234;me temps, soit pour les supprimer tous ensemble, soit pour les d&#233;placer tous ensemble dans un m&#234;me dossier.
        </li>
        <li><em>Importer</em>&#160;: cette action permet d\'importer des signets. Actuellement, vous pouvez importer des signets d\'Opera ou d\'un navigateur de la famille Netscape/Mozilla/Firefox. Si vous voulez importer des signets d\'Internet Explorer, vous devez les exporter d\'abord. Vous obtiendrez alors un fichier de signets Netscape que vous pourrez importer dans Brim.
        <br />
        Lors de l\'importation, vous pouvez d&#233;cider si les signets import&#233;s seront publics ou priv&#233;s.
        <br />
        Importer depuis un dossier particulier est possible. Il suffit de se placer dans le dossier en question puis de choisir l\'action "Importer".
        </li>
        <li><em>Exporter</em>&#160;: cette action permet d\'exporter des signets au format Opera ou Netscape (compatible avec Mozilla et Firefox). Si vous voulez exporter au format Internet Explorer, vous devez d\'abord exporter au format Netscape puis importer le r&#233;sultat dans Internet Explorer.
        </li>
        <li><em>Chercher</em>&#160;: cette action permet d\'effectuer des recherches dans les noms, URL et descriptions des signets.
        </li>
        </ul>
        <h3>Vue</h3>
        <ul>
        <li><em>D&#233;velopper</em>&#160;: cette action entra&#238;ne le d&#233;ploiement complet de l\'arborescence des signets. Elle n\'est applicable que pour la vue en arborescence.
        </li>
        <li><em>R&#233;duire</em>&#160;: suivant cette action, le syst&#232;me ne montre plus que les &#233;l&#233;ments (dossiers et signets) contenus dans le dossier courant.
        </li>
        <li><em>Vue en dossiers</em>: cette action entra&#238;ne l\'affichage des signets avec une vue en dossiers. Cette vue est inspir&#233;e de la mani&#232;re utilis&#233;e par Yahoo! pour montrer une structure.
        <br />
        Le nombre de colonnes pour cette vue peut &#234;tre d&#233;fini dans les pr&#233;f&#233;rences du module externe Signets.
        </li>
        <li><em>Vue en arborescence</em>&#160;: cette action entra&#238;ne l\'affichage des signets avec une vue en arborescence, sur le mod&#232;le de nombreux navigateurs et gestionnaires de fichiers.
        </li>
        <li><em>Afficher tous les signets publics</em>&#160;: affiche tous les signets publics de tous les utilisateurs en plus de vos propres signets.
        </li>
        <li><em>Afficher vos signets</em>&#160;: n\'affiche que vos signets, par opposition avec l\'action "Afficher tous les signets publics".
        </li>
        </ul>
        <h3>Tri</h3>
        <ul>
        <li><em>Derniers visit&#233;s</em>&#160;: affiche les signets tri&#233;s selon leur date de derni&#232;re visite.
        </li>
        <li><em>Les plus visit&#233;s</em>&#160;: affiche les signets tri&#233;s selon leur fr&#233;quence de visite.
        </li>
        <li><em>Derniers cr&#233;&#233;s</em>&#160;: affiche les signets tri&#233;s selon leur date de cr&#233;ation.
        </li>
        <li><em>Derniers modifi&#233;s</em>&#160;: affiche les signets tri&#233;s selon leur date de derni&#232;re modification.
        </li>
        </ul>
        <h3>Pr&#233;f&#233;rences</h3>
        <ul>
        <li><em>Modifier</em>&#160;: permet d\'acc&#233;der aux pr&#233;f&#233;rences du module externe Signets. Vous pouvez y modifier le nombre de colonnes pour la vue en dossiers, l\'utilisation de popups Javascript au survol des signets, la vue par d&#233;faut, et choisir l\'ouverture des signets dans une autre fen&#234;tre ou non.
        </li>
        <li><em>Vos signets publics</em>&#160;: affiche tous vos signets publics. Le lien correspondant &#224; cet affichage est en acc&#232;s public, vous pouvez donc le transmettre &#224; n\'importe qui, afin de partager vos signets. Le lien peut aussi &#234;tre int&#233;gr&#233; &#224; une page web, pour agr&#233;menter votre site&#160;!
        <br />
        Si vous voulez qu\'un &#233;l&#233;ment soit public, il faut que son &#233;l&#233;ment parent soit        </li>
        <li><em>Barre lat&#233;rale</em>&#160;: ce lien m&#232;ne &#224; une page o&#249; vous pourrez activer l\'int&#233;gration de Brim &#224; vos navigateur (Opera, Mozilla, Firefox et Netscape seulement).
        </li>
        <li><em>QuickMark</em>&#160;: Effectuez un clic <em>droit</em> sur le lien suivant pour l\'ajouter aux signets ou marque-pages de votre <b>navigateur</b>. Chaque fois que vous utiliserez ce nouveau signet, la page web que vous &#234;tes en train de visiter sera automatiquement ajout&#233;e aux signets de Brim (&#224; la racine).
        <br />
        Certains navigateurs sont nerveux avec ce type de fonctionnalit&#233;s. Si le v&#244;tre vous demande confirmation avant d\'ajouter le signet, cliquez sur "OK".
        </li>
</ul>';
$dictionary['item_quick_help']='Cliquez sur l\'ic&#244;ne de dossier ou d\'&#233;l&#233;ment se trouvant en d&#233;but de ligne pour d&#233;placer, supprimer ou modifier un signet.
<br /><br />
Pour d&#233;placer un signet,
<br />
cliquez sur "Modifier", puis "D&#233;placer", puis cliquez sur le dossier o&#249; vous voulez placer le signet.';
$dictionary['item_title']='Signets';
$dictionary['javascriptTree']='Arbre Javascript';
$dictionary['loadAllFaviconsWarning']='<p><b>Avertissement&#160;!</b>La r&#233;cup&#233;ration des favicons pour vos signets (du moins ceux qui n\'en ont pas encore) peut prendre tr&#232;s longtemps&#160;! Si cela pose probl&#232;me, vous pouvez modifier un signet individuellement pour lui ajouter son favicon.</p>
<p>Une r&#233;cup&#233;ration partielle des favicons est aussi possible. Placez-vous dans un sous-dossier puis lancez la r&#233;cup&#233;ration des favicons. Cela ira nettement plus vite. ;-)</p>
<p>Si vous jugez que les signets s\'affichent trop lentement apr&#232;s cela, essayez soit de d&#233;sactiver l\'affichage des favicons (via les pr&#233;f&#233;rences), soit d\'utiliser la vue en arborescence &#224; la place de l\'arbre Javascript.</p>';
$dictionary['locatorMissing']='Vous devez indiquer un emplacement';
$dictionary['modifyBookmarkPreferences']='Modifier les pr&#233;f&#233;rences du module Signets';
$dictionary['noFaviconFound']='Aucun favion trouv&#233;';
$dictionary['quickmark']='QuickMark';
$dictionary['quickmarkExplanation']='<p>
Effectuez un clic <em>droit</em> sur le lien suivant pour l\'ajouter aux signets ou marque-pages de votre <b>navigateur</b>.
<br />
Chaque fois que vous utiliserez ce nouveau signet, la page web que vous &#234;tes en train de visiter sera automatiquement ajout&#233;e aux signets de Brim.
<br /><br />
<font size="-2">Certains navigateurs sont nerveux avec ce type de fonctionnalit&#233;s. Si le v&#244;tre vous demande confirmation avant d\'ajouter le signet, cliquez sur "OK".</font>
<br />
</p>';
$dictionary['showBookmarkDetails']='Montrer les d&#233;tails du signet';
$dictionary['showFavicons']='Afficher les favicons';
$dictionary['sidebar']='Barre lat&#233;rale';
$dictionary['yourPublicBookmarks']='Vos signets publics';

?>