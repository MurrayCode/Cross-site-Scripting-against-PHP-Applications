<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Thibaut Cousin
 * @package org.brim-project.plugins.tasks
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
$dictionary['complete']='Avancement';
$dictionary['completedTasks']='T&#226;ches termin&#233;es';
$dictionary['completedWillDisappearAfterUpdate']='L\'&#233;l&#233;ment que vous venez de s&#233;lectionner est termin&#233; &#224; 100%. En outre, vous avez choisi dans vos pr&#233;f&#233;rences de masquer les t&#226;ches termin&#233;es. Donc cette t&#226;che sera masqu&#233;e au prochain rafra&#238;chissement.';
$dictionary['due_date']='Date de fin';
$dictionary['hideCompleted']='Masquer les t&#226;ches termin&#233;es';
$dictionary['item_help']='<p>Le module externe "T&#226;ches" vous permet de g&#233;rer vos t&#226;ches en ligne. Une t&#226;che est d&#233;finie par les param&#232;tres suivants&#160;:
</p>
<ul>
        <li><em>Nom</em>&#160;: le nom de la t&#226;che.
        </li>
        <li><em>Dossier ou t&#226;che</em>&#160;: indique si l\'&#233;l&#233;ment &#224; cr&#233;er est un dossier ou une t&#226;che. Ce choix, pour un &#233;l&#233;ment donn&#233;, est irr&#233;vocable.
        </li>
        <li><em>Public ou priv&#233;</em>&#160;: indique si l\'&#233;l&#233;ment est public ou non.
        <br />
        Si vous voulez qu\'un &#233;l&#233;ment soit public, il faut que son &#233;l&#233;ment parent soit public aussi ! La racine de l\'arborescence est publique par d&#233;faut.
        </li>
        <li><em>Avancement</em>&#160;: estimation de la progression dans la t&#226;che, en pourcentage.
        </li>
        <li><em>Priorit&#233;</em>&#160;: d&#233;finit la priorit&#233; de la t&#226;che, Urgente (par d&#233;faut), &#201;lev&#233;e, Moyenne, Basse, Annexe.
        </li>
        <li><em>Statut</em>&#160;: un champ libre o&#249; vous pouvez d&#233;crire la t&#226;che comme bon vous semble.
        </li>
        <li><em>Date de d&#233;but</em>&#160;: la date &#224; laquelle la t&#226;che doit commencer.
        </li>
        <li><em>Date de fin</em>&#160;: la date &#224; laquelle la t&#226;che doit s\'achever.
        </li>
        <li><em>Description</em>&#160;: une &#233;ventuelle description pour la t&#226;che.
        </li>
</ul>
<p>Les sous-menus disponibles pour le module externe Signets sont "Action", "Vue", "Tri", "Pr&#233;f&#233;rences" et "Aide".
</p>
<h3>Actions</h3>
<ul>
        <li><em>Ajouter</em>&#160;: cette action donne acc&#232;s &#224; un formulaire o&#249; vous pouvez saisir les informations n&#233;cessaires &#224; la cr&#233;ation d\'une nouvelle t&#226;che.
        </li>
        <li><em>S&#233;lection multiple</em>&#160;: cette action permet de s&#233;lectionner plusieurs t&#226;ches (mais pas des dossiers) en m&#234;me temps, soit pour les effacer toutes ensemble, soit pour les d&#233;placer toutes ensemble dans un m&#234;me dossier.
        </li>
        <li><em>Chercher</em>&#160;: cette action permet d\'effectuer des recherches dans les noms, statuts et descriptions des t&#226;ches.
        </li>
</ul>
<h3>Vue</h3>
<ul>
        <li><em>D&#233;velopper</em>&#160;: cette action entra&#238;ne le d&#233;ploiement complet de l\'arborescence des t&#226;ches. Elle n\'est applicable que pour la vue en arborescence
        </li>
        <li><em>R&#233;duire</em>&#160;: suivant cette action, le syst&#232;me ne montre plus que les &#233;l&#233;ments (dossiers et t&#226;ches) contenus dans le dossier courant.
        </li>
        <li><em>Vue en dossiers</em>&#160;: cette action entra&#238;ne l\'affichage des t&#226;ches avec une vue en dossiers. Cette vue est inspir&#233;e de la mani&#232;re utilis&#233;e par Yahoo! pour montrer une structure.
        <br />
        Le nombre de colonnes pour cette vue peut &#234;tre d&#233;fini dans les pr&#233;f&#233;rences du module externe T&#226;ches.
        </li>
        <li><em>Aper&#231;u en arborescence</em>&#160;: cette action entra&#238;ne un affichage &#224; la crois&#233;e des chemins entre une vue en lignes et une vue en arborescence.
        </li>
        <li><em>Vue en lignes</em>&#160;: cette action entra&#238;ne l\'affichage des t&#226;ches en ligne, incluant des d&#233;tails sur chaque t&#226;ches.
        </li>
        <li><em>Vue en arborescence</em>&#160;: cette action entra&#238;ne l\'affichage des t&#226;ches avec une vue en arborescence, sur le mod&#232;le de nombreux navigateurs et gestionnaires de fichiers.
        </li>
        <li><em>Afficher toutes les t&#226;ches publiques</em>&#160;: affiche toutes les t&#226;ches publiques de tous les utilisateurs en plus de vos propres t&#226;ches.
        </li>
        <li><em>Afficher vos t&#226;ches</em>&#160;: n\'affiche que vos t&#226;ches, par opposition avec l\'action "Afficher toutes les t&#226;ches publiques".
        </li>
</ul>
<h3>Tri</h3>
<ul>
        <li><em>Priorit&#233;</em>&#160;: affiche les t&#226;ches tri&#233;es selon leur priorit&#233;.
        </li>
        <li><em>Avancement</em>&#160;: affiche les t&#226;ches tri&#233;es selon leur avancement (en pourcentage).
        </li>
        <li><em>Date de d&#233;but</em>&#160;: affiche les t&#226;ches tri&#233;es selon leur date de d&#233;but.
        </li>
        <li><em>Date de fin</em>&#160;: affiche les t&#226;ches tri&#233;es selon leur date de fin.
        </li>
</ul>
<h3>Pr&#233;f&#233;rences</h3>
<ul>
        <li><em>Modifier</em>&#160;: permet d\'acc&#233;der aux pr&#233;f&#233;rences du module externe T&#226;ches. Vous pouvez y modifier le nombre de colonnes pour l\'aper&#231;u en dossiers, l\'utilisation de popups Javascript au survol des t&#226;ches et d&#233;finir la vue par d&#233;faut (vue en dossiers, aper&#231;u en arborescence, vue en lignes ou vue en arborescence).
        </li>
</ul>';
$dictionary['item_title']='T&#226;ches';
$dictionary['modifyTaskPreferences']='Modifier les pr&#233;f&#233;rences du module T&#226;ches';
$dictionary['priority']='Priorit&#233;';
$dictionary['priority1']='Urgente';
$dictionary['priority2']='&#201;lev&#233;e';
$dictionary['priority3']='Moyenne';
$dictionary['priority4']='Basse';
$dictionary['priority5']='Annexe';
$dictionary['showCompleted']='Afficher les t&#226;ches termin&#233;es';
$dictionary['start_date']='Date de d&#233;but';
$dictionary['status']='Statut';
$dictionary['taskHideCompleted']='Masquer les t&#226;ches termin&#233;es';
$dictionary['uncompletedTasks']='T&#226;ches inachev&#233;es';

?>