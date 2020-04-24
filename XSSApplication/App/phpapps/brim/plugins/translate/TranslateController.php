<?php

require_once 'framework/Controller.php';
require_once 'plugins/translate/model/TranslationServices.php';
require_once 'framework/util/StringUtils.php';

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - March 2006
 * @package org.brim-project.plugins.translate
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 */
class TranslateController extends Controller
{
	var $languages;
	var $plugins;
	var $stringUtils;
	/**
	 * Default constructor
	 */
	function TranslateController ()
	{
		parent::Controller ();
		$this->title = 'Brim - Translate';
		$this->pluginName = 'translate';
		$this->itemName = 'Translate';
		$this->operations = new TranslationServices ();
		$this->stringUtils = new StringUtils ();

		$languages = array ();
		$plugins = array ();
		include 'framework/configuration/plugins.php';
		include 'framework/configuration/languages.php';
		$this->languages = $languages;
		$this->plugins = array ();
		$this->plugins [] = 'framework';
		$this->plugins [] = 'messages';
		$this->plugins [] = 'tips';
		$this->plugins [] = 'sysinfo';
		$this->plugins [] = 'translate';
		foreach ($plugins as $plugin)
		{
			$this->plugins [] = $plugin['name'];
		}
	}

	function getActions ()
	{
		$actions = array ();
		$actions[0]['name']='view';
		$actions[0]['contents'][]=
			array ('href'=>'TranslateController.php?action=',
			 	'name'=>'translate'
			);
		$actions[0]['contents'][]=
			array ('href'=>'TranslateController.php?action=stats',
			 	'name'=>'stats'
			);
		$actions[1]['name']='help';
		$actions[1]['contents'][]=
			array ('href'=>'TranslateController.php?action=help',
			 	'name'=>'help'
			);
		return $actions;
	}

	function activate ()
	{
		$this->renderEngine->assign
			('controller', 'TranslateController.php');
		$this->renderEngine->assign ('languages', $this->languages);
		$this->renderEngine->assign ('plugins', $this->plugins);
		//
		// Shortcut. Overrule the action so we have have more buttons
		// within one frame
		//
		if (isset ($_POST['translateDownload']))
		{
			unset ($_POST['translateDownload']);
			$this->action = 'download';
		}
		elseif (isset ($_POST['translatePreview']))
		{
			unset ($_POST['translatePreview']);
			$this->action = 'preview';
		}
		//
		// Evaluate the requested action
		//
		switch ($this->getAction ())
		{
			case 'help':
				$this->helpAction ();
				break;
			case 'stats':
				$this->renderer = 'stats';
				break;
			case 'preview':
				$translation = $this->createTranslationFile ();
				if (strtolower ($translation['charset']) != 'utf-8')
				{
					//$translation = utf8_to_unicode (utf8_encode ($translation));
					$translation = utf8_to_unicode (($translation));
					//$translation = utf8_encode ($translation);
				}
				$this->renderEngine->assign
					('translation', htmlspecialchars($translation));
				$this->renderer = 'preview';
				break;
			case 'download':
				header('Content-Type: text/plain');
				$language = 'XX';
				if (isset ($_POST['translationLanguage']))
				{
					$language = $_POST['translationLanguage'];
				}
				$fileName = 'dictionary_'.$language.'.php';
				if (isset ($_POST['translationPlugin']))
				{
					$fileName = $_POST['translationPlugin'].'_'.$fileName;
				}
				$translation = $this->createTranslationFile ();
				$translation = trim (utf8_to_unicode (($translation)));
				header('Content-Disposition: attachment; filename="'.$fileName.'"');
				echo $translation;
				exit;
				break;
			case 'translate':
				if (!isset ($_POST['translationLanguage']) ||
					!isset ($_POST['translationPlugin']))
				{
					//
					// Both language and plugin must be set.
					// If this is not the case, show an error
					//
					$this->renderEngine->assign
						('message', 'bothLanguageAndPluginNeeded');
					$this->renderer = 'overview';
				}
				else
				{
					$dictionary = array ();
					//
					// Ok, we have a plugin and a language
					//
					if ($_POST['translationPlugin'] == 'framework')
					{
						//
						// Framework is in a different directory
						//
						include 'framework/i18n/dictionary_en.php';
						$baseDictionary = $dictionary;
						unset ($dictionary);
						$translationFileName =
							'framework/i18n/dictionary_'.
							$_POST['translationLanguage'].'.php';
						if (file_exists ($translationFileName))
						{
							//
							// If we already have a (partial)
							// translation, load it
							//
							include $translationFileName;
							$translatedDictionary = $dictionary;
						}
						else
						{
							//
							// Otherwise, default to empty
							//
							$translatedDictionary = array ();
						}
					}
					else if ($_POST['translationPlugin'] == 'tips')
					{
						//
						// Framework is in a different directory
						//
						include 'framework/i18n/tips_en.php';
						$baseDictionary = $dictionary;
						unset ($dictionary);
						$translationFileName =
							'framework/i18n/tips_'.
							$_POST['translationLanguage'].'.php';
						if (file_exists ($translationFileName))
						{
							//
							// If we already have a (partial)
							// translation, load it
							//
							include $translationFileName;
							$translatedDictionary = $dictionary;
						}
						else
						{
							//
							// Otherwise, default to empty
							//
							$translatedDictionary = array ();
						}
					}
					else if ($_POST['translationPlugin'] == 'messages')
					{
						//
						// Framework is in a different directory
						//
						include 'framework/i18n/messages_en.php';
						$baseDictionary = $dictionary;
						unset ($dictionary);
						$translationFileName =
							'framework/i18n/messages_'.
							$_POST['translationLanguage'].'.php';
						if (file_exists ($translationFileName))
						{
							//
							// If we already have a (partial)
							// translation, load it
							//
							include $translationFileName;
							$translatedDictionary = $dictionary;
						}
						else
						{
							//
							// Otherwise, default to empty
							//
							$translatedDictionary = array ();
						}
					}
					else
					{
						//
						// Plugin translation
						//
						include 'plugins/'.$_POST['translationPlugin'].
							'/i18n/dictionary_en.php';
						$baseDictionary = $dictionary;
						unset ($dictionary);
						$translationFileName =
							'plugins/'.
							$_POST['translationPlugin'].
							'/i18n/dictionary_'.
							$_POST['translationLanguage'].'.php';
						if (file_exists ($translationFileName))
						{
							//
							// If we already have a (partial)
							// translation, load it
							//
							include $translationFileName;
							$translatedDictionary = $dictionary;
						}
						else
						{
							//
							// Otherwise, default to empty
							//
							$translatedDictionary = array ();
						}
					}
					$this->renderEngine->assign
						('shortFileName', 'dictionary_'.$_POST['translationLanguage'].'.php');
					$this->renderEngine->assign
						('translationFileName', $translationFileName);
					$this->renderEngine->assign
						('baseDictionary', $baseDictionary);
					$this->renderEngine->assign
						('translatedDictionary',$translatedDictionary);
					$this->renderEngine->assign
						('translationLanguage',$_POST['translationLanguage']);
					$this->renderEngine->assign
						('translationPlugin',
							$_POST['translationPlugin']);
					$this->renderer = 'translate';
				}
				break;
			default:
				$this->renderer = 'overview';
				break;
		}
	}

	function getDictionary ()
	{
		if (isset($_POST['translationLanguage'])
			&& file_exists ('framework/i18n/dictionary_'.$_POST['translationLanguage'].'.php')
		)
		{
			//
			// If the translation has a specific charset, overrule
			// the existing one so non-ascii characters show up
			//
			include 'framework/i18n/dictionary_'.
				$_POST['translationLanguage'].'.php';
			if (isset ($dictionary['charset']))
			{
				$charset = $dictionary['charset'];
			}
			$dictionary = parent::getDictionary ();
			$dictionary['charset']=$charset;
		}
		else
		{
			//
			// Return the default dictionary otherwise
			//
			$dictionary = parent::getDictionary ();
		}
		return $dictionary;
	}

	function createTranslationFile ()
	{
		$plugin = $_POST['translationPlugin'];
		if ($plugin == 'framework')
		{
			$package = 'framework';
		}
		else
		{
			$package = 'plugins.'.$plugin;
		}
		unset ($_POST['translationLanguage']);
		unset ($_POST['translationPlugin']);
		ksort ($_POST);
		$author = 'Barry Nauta';
		if (isset ($_POST['pluginTranslator'])
			&& ($_POST['pluginTranslator'] != '')
		)
		{
			$author = $_POST['pluginTranslator'];
		}
		unset ($_POST['pluginTranslator']);
		$translation = '<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author '.$author.'
 * @package org.brim-project.'.$package.'
 * @subpackage i18n
 *
 * @copyright Brim - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
if (!isset ($dictionary))
{
	$dictionary = array ();
}
';
		foreach ($_POST as $key => $item)
		{
			if ($item != '')
			{
			$translation .= '$dictionary[\''.$key.'\']=\'';
			$translation .= addSlashesSingleQuotes
				($this->stringUtils->gpcStripSlashes ($item)).'\';
';
			}
		}
		$translation .= '
?>';
		return $translation;
	}
}


/**
 * Works like addslashes but doesn't touch the double quote
 */
function addSlashesSingleQuotes ($input)
{
	return str_replace ("'", "\'", $input);
}

/**
 * http://www.randomchaos.com/documents/?source=php_and_unicode
 */
    function utf8_to_unicodeorig( $str ) {

        $unicode = array();
        $values = array();
        $lookingFor = 1;

        for ($i = 0; $i < strlen( $str ); $i++ ) {

            $thisValue = ord( $str[ $i ] );

            if ( $thisValue < 128 ) $unicode[] = $thisValue;
            else {

                if ( count( $values ) == 0 ) $lookingFor = ( $thisValue < 224 ) ? 2 : 3;

                $values[] = $thisValue;

                if ( count( $values ) == $lookingFor ) {

                    $number = ( $lookingFor == 3 ) ?
                        ( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ):
                    	( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64 );

                    $unicode[] = $number;
                    $values = array();
                    $lookingFor = 1;

                } // if

            } // if

        } // for

        //return $unicode;
		$entities = '';
		foreach ($unicode as $value)
		{
				$entities .= '&#' . $value . ';';
				//$entities .= ( $value > 127 ) ? '&#' . $value . ';' : chr( $value );
		}
   		return $entities;
    } // utf8_to_unicode

    function utf8_to_unicode ($str ) {

		$result = '';
        $unicode = array();
        $values = array();
        $lookingFor = 1;

        for ($i = 0; $i < strlen( $str ); $i++ ) {

            $thisValue = ord( $str[ $i ] );

            if ( $thisValue < 128 )
			{
				$result .= chr ($thisValue);
			}
            else {

                if ( count( $values ) == 0 )
				{
						$lookingFor = ( $thisValue < 224 ) ? 2 : 3;
               	}
                $values[] = $thisValue;

                if ( count( $values ) == $lookingFor ) {
                    $number = ( $lookingFor == 3 ) ?
                        ( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ):
                    	( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64 );
			if ($number > 127)
			{
				$result .= '&#'.$number.';';
			}
			else
			{
				$result .= chr ($number);
			}
                    $values = array();
                    $lookingFor = 1;
                }
            }
        }
	return $result;
    }
?>
