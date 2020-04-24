<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Thibaut Cousin
 * @package org.brim-project.plugins.notes
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
$dictionary['item_help']='<p>Le module externe Notes vous permet de g&#233;rer vos prises de notes en ligne. Une note est d&#233;finie par les param&#232;tres suivants&nbsp;:
</p>
<ul>
        <li><em>Nom</em>&nbsp;: le nom de la note.
        </li>
        <li><em>Dossier ou note</em>&nbsp;: indique si l\'&#233;l&#233;ment &#224; cr&#233;er est un dossier ou une note. Ce choix, pour un &#233;l&#233;ment donn&#233;, est irr&#233;vocable.
        </li>
        <li><em>Public ou priv&#233;</em>&nbsp;: indique si l\'&#233;l&#233;ment est public ou non.
        <br />
        Si vous voulez qu\'un &#233;l&#233;ment soit public, il faut que son &#233;l&#233;ment parent soit public aussi ! La racine de l\'arborescence est publique par d&#233;faut.
        </li>
        <li><em>Description</em>&nbsp;: une &#233;ventuelle description de la note.
        </li>
</ul>
<p>Les sous-menus disponibles pour le module externe Signets sont "Action", "Vue", "Tri", "Pr&#233;f&#233;rences" et "Aide".
</p>
<h3>Actions</h3>
<ul>
        <li><em>Ajouter</em>&nbsp;: cette action donne acc&#232;s &#224; un formulaire o&#249; vous pouvez saisir les informations n&#233;cessaires &#224; la cr&#233;ation d\'une nouvelle note.
        </li>
        <li><em>S&#233;lection multiple</em>&nbsp;: cette action permet de s&#233;lectionner plusieurs notes (mais pas des dossiers) en m&#234;me temps, soit pour les supprimer toutes ensemble, soit pour les d&#233;placer toutes ensemble dans un m&#234;me dossier.
        </li>
        <li><em>Chercher</em>&nbsp;: cette action permet d\'effectuer des recherches dans les noms et descriptions des notes.
        </li>
</ul>
<h3>Vue</h3>
<ul>
        <li><em>D&#233;velopper</em>&nbsp;: cette action entra&#238;ne le d&#233;ploiement complet de l\'arborescence des notes. Elle n\'est applicable que pour la vue en arborescence.
        </li>
        <li><em>R&#233;duire</em>&nbsp;: suivant cette action, le syst&#232;me ne montre plus que les &#233;l&#233;ments (dossiers et notes) contenus dans le dossier courant.
        </li>
        <li><em>Vue en dossiers</em>&nbsp;: cette action entra&#238;ne l\'affichage des notes avec une vue en dossiers. Cette vue est inspir&#233;e de la mani&#232;re utilis&#233;e par Yahoo! pour montrer une structure.
        <br />
        Le nombre de colonnes pour cette vue peut &#234;tre d&#233;fini dans les pr&#233;f&#233;rences du module externe Notes.
        </li>
        <li><em>Vue en arborescence</em>&nbsp;: cette action entra&#238;ne l\'affichage des notes avec une vue en arborescence, sur le mod&#232;le de nombreux navigateurs et gestionnaires de fichiers.
        </li>
        <li><em>Afficher toutes les notes publiques</em>&nbsp;: affiche toutes les notes publiques de tous les utilisateurs en plus de vos propres notes.
        </li>
        <li><em>Afficher vos notes</em>&nbsp;: n\'affiche que vos notes, par opposition avec l\'action "Afficher tous les notes publiques".
        </li>
</ul>
<h3>Pr&#233;f&#233;rences</h3>
<ul>
        <li><em>Modifier</em>&nbsp;: permet d\'acc&#233;der aux pr&#233;f&#233;rences du module externe Notes. Vous pouvez y modifier le nombre de colonnes pour la vue en dossiers, l\'utilisation de popups Javascript au survol des signets et la vue par d&#233;faut (en dossiers ou en arborescence).
        </li>
</ul>';
$dictionary['item_title']='Notes';
$dictionary['modifyNotePreferences']='Modifier les pr&#233;f&#233;rences du module Notes';

?>