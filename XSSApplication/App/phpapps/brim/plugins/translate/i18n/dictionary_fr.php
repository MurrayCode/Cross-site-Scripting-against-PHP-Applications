<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Thibaut Cousin
 * @package org.brim-project.plugins.translate
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
$dictionary['baseTranslation']='Traduction de base';
$dictionary['bothLanguageAndPluginNeeded']='Vous devez indiquer la langue et le module';
$dictionary['currentTranslation']='Traduction courante';
$dictionary['item_help']='<p>L\'outil de traduction vous guide dans la traduction de l\'application dans votre langue.</p>
<p>Vous trouverez dans le dossier "tools" un script appel&#233; <code>dict.sh</code> (merci &#224; &#216;yvind Hagen), qui met en place une arborescence pour vous et vous aide  &#224; copier les fichiers de traduction au bon endroit quand vous avez fini. Utilisez ce script pour comprendre comment il fonctionne.</p>
<p>Lors d\'une utilisation normale de l\'application, voici ce qui se passe du point de vue de la traduction. Si une traduction existe, l\'application en cherche une dans votre langue, et se rabat sur l\'Anglais si elle ne la trouve pas. Une traduction existante mais incompl&#232;te donne lieu &#224; des m&#233;langes des deux langues dans l\'interface.</p>
<h2>Comment mettre &#224; jour une traduction existante</h2>
<p>Utilisez l\'outil de traduction. S&#233;lectionnez-y la langue et le module. Vous obtiendrez alors une page pr&#233;sentant la clef de traduction (&#224; usage interne de l\'application), la traduction de base (Anglais), la traduction courante dans votre langue (ou en rouge le texte "NON D&#201;FINI&#160;!" si un texte donn&#233; n\'est pas traduit) et une zone de saisie de texte dans laquelle vous pourrez modifier ou ajouter une traduction.</p>
<p>Une fois la traduction termin&#233;e, vous pouvez obtenir un aper&#231;u du r&#233;sultat ou bien le t&#233;l&#233;charger. Si vous choisissez de le t&#233;l&#233;charger, vous obtiendrez un fichier appel&#233; "dictionary_XX.php" que vous devrez enregistrer dans le dossier "i18n" du module consid&#233;r&#233; (ou de "framework" si vous traduisez l\'environnement lui-m&#234;me). L\'emplacement et le nom pr&#233;cis du fichier sont indiqu&#233;s en haut de la page de traduction.</p>
<h2>Comment cr&#233;er une nouvelle traduction</h2>
<p>Sur la page principale de traduction, cliquez sur "Nouveau". Cela vous m&#232;ne &#224; une page avec la m&#234;me interface de traduction que ci-dessus. Quand vous avez termin&#233; votre traduction, enregistrez-la en rempla&#231;ant "XX" dans le nom "dictionary_XX.php" du fichier par le code de votre langue. Le code d\'une langue est construit comme suit&#160;: XX_YYY o&#249; XX correspond &#224; la langue et YYY au dialect (par exemple, PT_BR correspond au Portugais, dialecte br&#233;silien). L\'emplacement et le nom du fichier de traduction sont indiqu&#233;s en haut de la page de traduction.</p>
<p>Enfin, &#233;ditez le fichier "framework/i18n/languages.php" et ajoutez-y votre langue. Ajoutez &#233;galement (s\'il n\'y est pas d&#233;j&#224;) le drapeau correspondant dans le dossier "framework/view/pics/flags" en nommant le fichier "flag-XX_YYY.png". Ceci fait, votre langue appara&#238;tra automatiquement parmi les langages possibles sur la page d\'accueil.</p>';
$dictionary['item_title']='Traduction';
$dictionary['languageToTranslate']='Langue';
$dictionary['percentComplete']='Avancement (en pourcentage)';
$dictionary['pluginToTranslate']='Environnement / Module';
$dictionary['pluginTranslatorIndicator']='Nom du traducteur (votre nom)';
$dictionary['saveTranslationToLocation']='Emplacement du fichier';
$dictionary['stats']='Statistiques';
$dictionary['translationFileName']='Fichier de traduction';
$dictionary['translationKey']='Clef de traduction';

?>