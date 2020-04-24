<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.translate
 * @subpackage i18n
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 */
if (!isset ($dictionary))
{
	$dictionary=array();
}
$dictionary['item_title']='Translate';
$dictionary['pluginToTranslate']='Framework/Plugin';
$dictionary['languageToTranslate']='Language';
$dictionary['bothLanguageAndPluginNeeded']='Both language and plugin required';
$dictionary['translationKey']='Translation key';
$dictionary['baseTranslation']='Base translation';
$dictionary['currentTranslation']='Current translation';
$dictionary['percentComplete']='Percent complete';
$dictionary['pluginTranslatorIndicator']='Plugin translator (your name)';
$dictionary['translationFileName']='Translation filename ';
$dictionary['saveTranslationToLocation']='Save your file to';
$dictionary['stats']='Statistics';

$dictionary['item_help']='
<p>
	The translate utility helps you to either
	translate the application in your language
	or upgrade an existing
	translation.
</p>
<p>
	There is a script in the tools subdirectory
	called <code>dict.sh</code> (thanks to
	�yvind Hagen), which sets up a directory
	structure for you and helps you copying
	the files to the right place afterwards.
	The script is self-explanatory.
</p>
<p>
	During normal usage of the application, the
	following happens when it comes to language usage.
	If a translation exist, the application first
	looks for the specific translation in your
	language and defaults to english if this
	specific translation cannot be found.
	An incomplete translation will therefor show
	both translated and english strings.
</p>
<h2>How to upgrade an existing translation</h2>
<p>
	Via the translate utility, select both
	plugin and language.  You will be presented
	with a screen containing the translation key
	(internal use by the system), the base
	translation (english), the current
	translation in your language (or in red the
	text \'NOT SET!!!\' if the specific
	translation does not exist) and a textarea
	allowing you to change/complete the
	translation for each item.
</p>
<p>
	Once you are done with your translation,
	you have the option to preview the result
	or download it.  Downloading presents a
	filename called \'dictionary_XX.php\'
	which should be saved in the i18n directory
	of the specific plugin (or base if you are
	translating the framework part).  The
	location and filename of the destination
	file are presented at the top of the
	translate screen.
</p>
<h2>How to create a new translation</h2>
<p>
	On the overview screen, select \'New\'
	for translation.  You will be presented
	with a translation screen.  When you have
	finished your translation, save it by
	replacing the XX in the file
	\'dictionary_XX.php\' by your language
	code. The language code is constructed
	in the following way: XX_YYY where XX
	stands for the language and YYY for the
	dialect (i.e. PT_BR is Portuguese,
	Brazilian dialect).  The location and
	filename of the destination file are
	presented at the top of the translate
	screen.
</p>
<p>
	Now edit the file
	\'framework/i18n/languages.php\' and
	add your language. Add (if it does not
	yet exist), the flag to the directory
	\'framework/view/pics/flags\' in the form
	\'flag-XX_YYY.png\' and the language
	selection will automatically show up at
	the welcome screen.
</p>
';
?>