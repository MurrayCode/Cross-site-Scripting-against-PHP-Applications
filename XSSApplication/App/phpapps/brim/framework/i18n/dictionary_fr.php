<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Thibaut Cousin
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
$dictionary['about']='&#192; propos de '.$dictionary['programname'].'';
$dictionary['about_page']='<h2>&#192; propos de '.$dictionary['programname'].'</h2>
<p><b>'.$dictionary['programname'].' '.$dictionary['version'].'</b> a &#233;t&#233; &#233;crit par '.$dictionary['authorname'].' (email: <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>) '.$dictionary['copyright'].' </p> <p> Cette application vous offre un ensemble de services bureautiques, accessible sur un serveur web avec un identifiant personnel et un mot de passe&#160;: vous pourrez ainsi g&#233;rer en ligne vos signets, notes, contacts, etc.</p>
<p> Cette application ('.$dictionary['programname'].') est publi&#233;e sous la license \'GNU General Public License\'. Une version compl&#232;te (en Anglais) de ce contrat peut &#234;tre trouv&#233;e <a href="documentation/gpl.html">ici</a>. Le site web de '.$dictionary['programname'].' se trouve &#224; l\'adresse suivante:<a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a>. </p> ';
$dictionary['actions']='Actions';
$dictionary['activate']='Activer';
$dictionary['add']='Ajouter';
$dictionary['addAndAddAnother']='Ajouter celui-ci puis un autre';
$dictionary['addFolder']='Ajouter un dossier';
$dictionary['addNode']='Ajouter un &#233;l&#233;ment';
$dictionary['addToFolderNotOwned']='Vous n\'&#234;tes pas autoris&#233;(e) &#224; ajouter un &#233;l&#233;ment &#224; un dossier dont vous n\'&#234;tes pas propri&#233;taire';
$dictionary['adduser']='Ajouter un utilisateur';
$dictionary['admin']='Administration';
$dictionary['adminConfig']='Configuration';
$dictionary['admin_email']='Adresse &#233;lectronique administrateur';
$dictionary['allow_account_creation']='Autoriser la cr&#233;ation de comptes utilisateur';
$dictionary['attentionTemplate']='Votre mod&#232;le ne peut g&#233;rer qu\'un nombre limit&#233; d\'&#233;l&#233;ments, au-del&#224; duquel la barre principale va dispara&#238;tre. Cliquez <a href="PreferenceController.php">ici</a> si vous ne pouvez plus acc&#233;der &#224; la barre principale (proposant les pr&#233;f&#233;rences, l\'aide, la recherche, la d&#233;connexion, les traductions, etc)';
$dictionary['back']='Retour';
$dictionary['banking']='Banque en ligne';
$dictionary['bookmark']='Signet';
$dictionary['bookmarks']='Signets';
$dictionary['calendar']='Agenda';
$dictionary['calendarEmailReminder']='Les courriers &#233;lectroniques de rappel d\'&#233;v&#233;nement sont activ&#233;s (via Cron)';
$dictionary['calendarParticipation']='Activer le partage de calendrier avec d\'autres utilisateurs';
$dictionary['cancel']='Annuler';
$dictionary['checkbook']='Comptabilit&#233;';
$dictionary['collapse']='R&#233;duire';
$dictionary['collections']='Collections';
$dictionary['confirm']='Confirmer';
$dictionary['confirm_delete']='Voulez-vous vraiment supprimer cet &#233;l&#233;ment&#160;?';
$dictionary['contact']='Contact';
$dictionary['contacts']='Contacts';
$dictionary['contents']='Contenu';
$dictionary['creationDateTime']='Cr&#233;ation';
$dictionary['dashboard']='Bureau';
$dictionary['database']='Base de donn&#233;es';
$dictionary['dateFormat']='Format de date';
$dictionary['deactivate']='D&#233;sactiver';
$dictionary['defaultExpandMenu']='D&#233;velopper les &#233;l&#233;ments de menu par d&#233;faut (th&#232;me barrel)';
$dictionary['defaultShowShared']='Afficher les &#233;l&#233;ments partag&#233;s par d&#233;faut (n&#233;cessite de se reconnecter)';
$dictionary['defaultTxt']='Par d&#233;faut';
$dictionary['deleteForever']='Supprimer d&#233;finitivement';
$dictionary['deleteTxt']='Supprimer';
$dictionary['delete_not_owner']='Vous n\'&#234;tes pas autoris&#233;(e) &#224; supprimer un &#233;l&#233;ment dont vous n\'&#234;tes pas propri&#233;taire.';
$dictionary['depot']='Cours de bourse';
$dictionary['description']='Description';
$dictionary['deselectAll']='Tout d&#233;s&#233;lectionner';
$dictionary['down']='Vers le bas';
$dictionary['email']='Adresse &#233;lectronique';
$dictionary['emailRequired']='Veuillez saisir votre adresse &#233;lectronique';
$dictionary['enableAjax']='Interface utilisateur interactive';
$dictionary['expand']='D&#233;velopper';
$dictionary['explorerTree']='Vue en arborescence';
$dictionary['exportTxt']='Exporter';
$dictionary['exportusers']='Exporter des utilisateurs';
$dictionary['file']='Fichier';
$dictionary['findDoubles']='Chercher les doublons';
$dictionary['folder']='Dossier';
$dictionary['formError']='Ce formulaire comporte une erreur';
$dictionary['forward']='Faire suivre';
$dictionary['genealogy']='G&#233;n&#233;alogie';
$dictionary['gmail']='GMail';
$dictionary['help']='Aide';
$dictionary['home']='Dossier personnel';
$dictionary['importTxt']='Importer';
$dictionary['importusers']='Importer des utilisateurs';
$dictionary['input']='Saisie';
$dictionary['input_error']='Veuillez v&#233;rifier les champs saisis';
$dictionary['installation_path']='Chemin d\'installation';
$dictionary['installer_exists']='<h2><font color="red">Le fichier d\'installation existe encore&#160;! Veuillez le supprimer.</font></h2>';
$dictionary['invalidEmail']='Votre adresse &#233;lectronique ne semble pas correcte';
$dictionary['inverseAll']='Tout inverser';
$dictionary['item_count']='Nombre d\'&#233;lements';
$dictionary['item_help']='<h1>Aide de '.$dictionary['programname'].'</h1>
<p>'.$dictionary['programname'].' a deux barres de menu. L\'une est appel&#233;e la barre principale et propose des fonctionnalit&#233;s globales. L\'autre est appel&#233;e la barre de modules et contient les liens vers les diff&#233;rents modules externes. Pour obtenir de l\'aide sur un module pr&#233;cis, cliquez <a href="#plugins">ici</a>.</p>

<p>Le lien "Pr&#233;f&#233;rences" dans la barre principale vous m&#232;ne &#224; un formulaire o&#249; vous pourrez indiquer votre langue, le th&#232;me que vous voulez utilisez et divers param&#232;tres personnels comme votre mot de passe, votre adresse &#233;lectronique, etc. Veuillez noter que les r&#233;glages de la langue et du th&#232;me doivent &#234;tre effectu&#233;s l\'un apr&#232;s l\'autre, pas en m&#234;me temps.</p>
<p>The lien "&#192; propos de '.$dictionary['programname'].'" vous donne diverses informations sur '.$dictionary['programname'].', comme son num&#233;ro de version.</p>
<p>Le lien Se d&#233;connecter" vous permet de quitter l\'application. En outre, il entra&#238;ne la suppression du cookie cr&#233;&#233; si vous aviez coch&#233; la case "Se rappeler de moi" lors de la connexion. Dans ce cas, vous devrez vous identifier &#224; nouveau la prochaine fois que vous vous connecterez.</p>
<p>Le lien "Modules externes" vous permet d\'activer ou de d&#233;sactiver individuellement les modules externes. Un module d&#233;sactiv&#233; n\'appara&#238;t plus ni dans la barre de modules, ni dans l\'aide.</p>';
$dictionary['item_private']='&#201;l&#233;ment priv&#233;';
$dictionary['item_public']='Rendre cet &#233;l&#233;ment public';
$dictionary['javascript_popups']='Popups Javascript';
$dictionary['language']='Langue';
$dictionary['lastLogin']='Derni&#232;re connexion';
$dictionary['last_created']='Derniers cr&#233;&#233;s';
$dictionary['last_modified']='Derniers modifi&#233;s';
$dictionary['last_visited']='Derniers visit&#233;s';
$dictionary['license_disclaimer']='Le site web de '.$dictionary['programname'].' se trouve &#224; l\'adresse suivante&#160;: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a>.
<br />
'.$dictionary['copyright'].' '.$dictionary['authorname'].' (<a href="'.$dictionary['authorurl'].'">'.$dictionary['authorurl'].'</a>).
Vous pouvez me contacter &#224; <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>.
<br />
Cette application ('.$dictionary['programname'].') est un logiciel libre&#160;; vous pouvez la redistribuer et/ou la modifier conform&#233;ment aux termes de la license "GNU General Public License" publi&#233;e par la Free Software Foundation, en version&#160;2 ou ult&#233;rieure (selon votre choix). Cliquez <a href="documentation/gpl.html">ici</a> pour obtenir la version compl&#232;te de la licence.';
$dictionary['lineBasedTree']='Vue en lignes';
$dictionary['link']='lien';
$dictionary['loadingIndication']='Chargement en cours...';
$dictionary['locator']='URL';
$dictionary['loginName']='Nom d\'utilisateur';
$dictionary['logout']='Se d&#233;connecter';
$dictionary['mail']='Adresse &#233;lectronique';
$dictionary['message']='Message';
$dictionary['modify']='Modifier';
$dictionary['modify_not_owner']='Vous n\'&#234;tes pas autoris&#233;(e) &#224; modifier un &#233;l&#233;ment dont vous n\'&#234;tes pas propri&#233;taire.';
$dictionary['month01']='Janvier';
$dictionary['month02']='F&#233;vrier';
$dictionary['month03']='Mars';
$dictionary['month04']='Avril';
$dictionary['month05']='Mai';
$dictionary['month06']='Juin';
$dictionary['month07']='Juillet';
$dictionary['month08']='Ao&#251;t';
$dictionary['month09']='Septembre';
$dictionary['month10']='Octobre';
$dictionary['month11']='Novembre';
$dictionary['month12']='D&#233;cembre';
$dictionary['most_visited']='Les plus visit&#233;s';
$dictionary['move']='D&#233;placer';
$dictionary['multipleSelect']='S&#233;lection multiple';
$dictionary['mysqlAdmin']='MySQL';
$dictionary['name']='Nom';
$dictionary['nameMissing']='Vous devez fournir un nom';
$dictionary['nameRequired']='Veuillez saisir votre nom';
$dictionary['new_window_target']='O&#249; la nouvelle fen&#234;tre s\'ouvre-t-elle?';
$dictionary['news']='Nouvelles';
$dictionary['no']='Non';
$dictionary['noSearchResult']='La recherche n\'a donn&#233; aucun r&#233;sultat';
$dictionary['nonParticipatingUsers']='Autres utilisateurs';
$dictionary['none']='Aucun';
$dictionary['note']='Note';
$dictionary['notes']='Notes';
$dictionary['overviewTree']='Aper&#231;u en arborescence';
$dictionary['participatingUsers']='Participants';
$dictionary['password']='Mot de passe';
$dictionary['password2Required']='Veuillez confirmer votre mot de passe';
$dictionary['passwordRequired']='Veuillez saisir votre mot de passe';
$dictionary['passwords']='Mots de passe';
$dictionary['pluginSettings']='Modules externes';
$dictionary['plugins']='Modules externes';
$dictionary['polardata']='Donn&#233;es polaires';
$dictionary['preferedIconSize']='Taille d\'ic&#244;ne pr&#233;f&#233;r&#233;e';
$dictionary['preferences']='Pr&#233;f&#233;rences';
$dictionary['priority']='Priorit&#233;';
$dictionary['private']='Priv&#233;';
$dictionary['public']='Public';
$dictionary['quickmark']='Effectuez un clic <em>droit</em> sur le lien suivant pour l\'ajouter aux signets ou marque-pages de votre <b>navigateur</b>.
<br>
Chaque fois que vous utiliserez ce nouveau signet, la page web que vous &#234;tes en train de visiter sera automatiquement ajout&#233;e aux signets de '.$dictionary['programname'].'.
<br>
<br>
<font size="-2">Certains navigateurs sont nerveux avec ce type de fonctionnalit&#233;s. Si le v&#244;tre vous demande confirmation avant d\'ajouter le signet, cliquez sur "OK".</font>
<br>';
$dictionary['recipes']='Recettes';
$dictionary['refresh']='Rafra&#238;chir';
$dictionary['root']='Racine';
$dictionary['search']='Chercher';
$dictionary['select']='S&#233;lectionner';
$dictionary['selectAll']='Tout s&#233;lectionner';
$dictionary['setModePrivate']='Voir les &#233;l&#233;ments priv&#233;s';
$dictionary['setModePublic']='Voir les &#233;l&#233;ments partag&#233;s';
$dictionary['share']='Partage';
$dictionary['show']='Montrer';
$dictionary['showTips']='Afficher les bulles d\'aide';
$dictionary['sort']='Tri';
$dictionary['spellcheck']='V&#233;rification orthographique';
$dictionary['submit']='Envoyer';
$dictionary['synchronizer']='Synchronisateur';
$dictionary['sysinfo']='Informations syst&#232;me';
$dictionary['task']='T&#226;che';
$dictionary['tasks']='T&#226;ches';
$dictionary['textsource']='Source du texte';
$dictionary['theme']='Th&#232;me';
$dictionary['tip']='Astuce';
$dictionary['title']='Titre';
$dictionary['today']='Aujourd\'hui';
$dictionary['toggleSelection']='Inverser la s&#233;lection';
$dictionary['translate']='Traduire';
$dictionary['trash']='Corbeille';
$dictionary['undelete']='Restaurer';
$dictionary['up']='Vers le haut';
$dictionary['user']='Utilisateur';
$dictionary['view']='Vue';
$dictionary['visibility']='Visibilit&#233;';
$dictionary['weather']='M&#233;t&#233;o';
$dictionary['webtools']='Outils web';
$dictionary['welcome_page']='<h1>Bienvenue %s</h1><h2>'.$dictionary['programname'].' - un engin polyvalent.</h2>';
$dictionary['yahooTree']='Vue en dossiers';
$dictionary['yahoo_column_count']='Nombre de colonnes pour la vue en dossiers';
$dictionary['yes']='Oui';
$dictionary['youAreNotOwnerButParticipator']='Cet &#233;l&#233;ment ne vous appartient pas, vous ne pouvez pas le modifier';

?>
