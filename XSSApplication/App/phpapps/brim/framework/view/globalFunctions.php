<?php

require_once ('framework/util/DateUtils.php');
require_once ('framework/util/StringUtils.php');

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage view
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

$dateUtils = new DateUtils ();

function TableRowInput($title, $fieldname, $fieldvalue, $action, $textbefore="", $textbehind="", $classtdTitle="inputParamName", $classtdValue="inputParamValue")
{
	$stringUtils = new StringUtils ();
	$result = '<tr>';

	// First field contains the name
	//$result .= '<td class="'.$classtdTitle.'">'.$stringUtils->urlEncodeQuotes($title).':</td>';
	$result .= '<td class="'.$classtdTitle.'">'.$title.':</td>';
	// Second field contains the value
	$result .= '<td class="'.$classtdValue.'">'.$textbefore;
	if ($action == 'add' || $action == 'modify')
	{
		$result .= '<input type="text" class="text" ';
		//$result .= 'name="'.$stringUtils->urlEncodeQuotes($fieldname).'" ';
		$result .= 'id="'.$fieldname.'" ';
		$result .= 'name="'.$fieldname.'" ';
		// Fill in the value if we are modifying the item.
		if ($action == 'modify' || isset($fieldvalue))
		{
			if (isset($fieldvalue) && "" != $fieldvalue)
			{
				//$result .= 'value="'.$stringUtils->urlEncodeQuotes($fieldvalue).'" ';
				$result .= 'value="'.$fieldvalue.'" ';
			}
		}
		$result .= ' />';
	}
	else
	{
	 	// Show-action: no input but plain text
		$result .= $stringUtils->urlEncodeQuotes($fieldvalue);
	}
	$result .= $textbehind.'</td>';
	$result .= '</tr>';

	return $result;
}


/**
 * Returns a row with two data fields.
 * The first field will contain the mapping of the name in the
 * dictionary, the second field will contain the value of the field
 * in an input field.
 *
 * If the action is 'show' then editable will be set to false
 * and if modify or show is action, the fieldValue will be
 * filled in, which is not the case for adding and item
 * (we do not have a fieldValue at this moment)
 *
 * @param name the name of the field, this name will be used to
 * retrieve the appropriate dictionary title and also to retrieve
 * the appropriate object member
 * @param array dictionary the dictionary
 * @param sting action either add, modify or show
 * @param fieldValue the value to be filled in
 *		(expect for the add action)
 * @param string inputText optional text to be put in the input field
 * @return string the string with the additional parameters
 */
function standardTableRowInput ($name, $dictionary, $action, $object,
	$inputText="")
{
    $value = (isset($object->$name))? $object->$name : NULL;
 	return TableRowInput($dictionary[$name], $name, $value, $action);
}

/**
 * Returns a row with two data fields.
 * The first field will contain the mapping of the name in the
 * dictionary, the second field will contain the value of the field
 * in a textarea.
 *
 * If the action is 'show' then editable will be set to false
 * and if modify or show is action, the fieldValue will be
 * filled in, which is not the case for adding and item
 * (we do not have a fieldValue at this moment)
 *
 * @param name the name of the field, this name will be used to
 * retrieve the appropriate dictionary title and also to retrieve
 * the appropriate object member
 * @param array dictionary the dictionary
 * @param sting action either add, modify or show
 * @param object item the item
 * @return string the string with the additional parameters
 */
function standardTableRowTextarea ($name, $dictionary, $action, $item)
{
	$stringUtils = new StringUtils ();
	$result = '<tr>';

	//
	// First field contains the name
	//
	$result .= '<td class="inputParamName">'.$dictionary[$name].':</td>';

	//
	// Second field contains the value
	//
	$result .= '<td>';
	if ($action == 'add' || $action == 'modify')
	{
		$result .= '<textarea ';
		$result .= 'name="'.$name.'" ';
		$result .= 'id="'.$name.'" ';
		$result .= '>';
		//
		// Fill in the value if we are modifying the item. This has no
		// use for adding...
		//
		if (($action == 'modify') || isset ($item))
		{
			$result .= $item->$name;
		}
		$result .= '</textarea>';
	}
	else if ($action == 'show')
	{
		$result .= $stringUtils->newlinesToHtml ($item->$name);
	}
	$result .= '</td>';
	$result .= '</tr>';

	return $result;
}


function TableRowRadios($fieldname, $field1title, $field1value, $field1checked, $field2title, $field2value, $action,
							$title="&nbsp;", $seperateline=1,
							$classtdTitle="inputParamName", $classtdValue="inputParamValue")
{
    $result = '';
 	if (isset($action))
    {
	 	$result = '<tr>';
		// First field is empty, names will show up in second field
		$result .= '<td class="'.$classtdTitle.'">'.$title.'</td>';
		// Second field contains the names and values
		$result .= '<td class="'.$classtdValue.'">';
		if ($action == 'add' || $action == 'modify')
		{
			$result .= $field1title.':&nbsp;';
			$result .= '<input type="radio" class="radio" ';
			$result .= 'name="'.$fieldname.'" value="'.$field1value.'" ';
			if ($field1checked)
			{
				$result .= 'checked="checked" ';
			}
			$result .= ' />';
			if ($seperateline)
			{
				$result .= '<br />';
			}
			$result .= $field2title.':&nbsp;';
			$result .= '<input type="radio" class="radio" ';
			$result .= 'name="'.$fieldname.'" value="'.$field2value.'" ';
			if (!$field1checked)
			{
				$result .= 'checked="checked" ';
			}
			$result .= ' />';
		}
		else if ('show' == $action)
		{
			if ($field1checked)
			{
				$result .= $field1title;
			}
			else
			{
				$result .= $field2title;
			}
		}
		$result .= '</td>';
		$result .= '</tr>';
	}
	return $result;
}

/**
 * Shows the radio buttons for selection between item
 * and folder. These buttons are only shown if the provided action
 * is 'add'
 *
 * @param array dictionary the dictionary
 * @param string action the action which is either add, modify or show
 * @param string itemName the name of the item, will be used for
 * 		dictionary lookup
 */
function standardTableRowFolderItemRadios
	($dictionary, $action, $itemName)
{
    return TableRowRadios("isParent",
    					$dictionary['folder'],
    					"1",
    					0,
    					$dictionary[$itemName],
    					"0",
    					('add' == $action) ? $action : NULL
    					);
}


/**
 * Shows the radio buttons for selection between item
 * and folder. These buttons are only shown if the provided action
 * is 'add'
 *
 * @param array dictionary the dictionary
 * @param string action the action which is either add, modify or show
 * @param string itemName the name of the item, will be used for
 * 		dictionary lookup
 */
function standardTableRowPublicPrivateRadios
	($dictionary, $action, $item)
{
 	return TableRowRadios("visibility",
 						  $dictionary['item_public'],
 						  "public",
 						  isset($item->visibility) && $item->visibility == 'public',
 						  $dictionary['item_private'],
 						  "private",
 						  $action
 						  );
}

/**
 * Displays error messages (if any)
 *
 * @param array dictionary the dictionary containing the translations
 * 		of the errors
 * @param array errors the list of errors
 * @param array icons must contain the warning icon
 */
function standardTableRowErrorMessages ($dictionary, $errors, $icons)
{
	$result = '';
	if (!empty ($errors))
	{
		$result .= '<tr>';
		$result .= '<td>';
		$result .= $icons['warning'];
		$result .= '</td>';
		$result .= '<td>';
		foreach ($errors as $error)
		{
			$result .= $dictionary[$error].'<br />';
		}
		$result .= '</td>';
		$result .= '</tr>';
	}
	return $result;
}

/**
 * Displays a button
 *
 * @param buttontitle = the text written on the button
 * @param itemId = ID of the item for the specified action
 * @param parentId = ID of the parent of the item
 * @param action = action value to send to the controller
 * @param parentIdName = name of the parentId parameter to send (default: 'parentId')
 * @param itemIdName = name of the itemId parameter to send (default: 'itemId')
 */
function ButtonAndText($buttontitle, $itemId, $parentId, $action, $parentIdName="parentId", $itemIdName="itemId")
{
    $result = "";
 	if((isset($itemId) && $itemId) && (isset($itemIdName) && ("" != $itemIdName)))
    {
	 	$result .= '<input type="hidden"
						name="'.$itemIdName.'"
						value="'.$itemId.'" />';
	}
    if((isset($parentId) && $parentId) && (isset($parentIdName) && ("" != $parentIdName)))
    {
		$result .= '<input type="hidden"
						name="'.$parentIdName.'"
						value="'.$parentId.'" />';
	}
	$result .= '<input type="hidden"
	    			name="action"
		    		value="'.$action.'" />';
	$result .= '<input type="submit"
					class="button"
					name="submit"
					value="'.$buttontitle.'" />';
	return $result;
}

function deleteButtonAndText ($dictionary, $item, $action="deleteItemPost")
{
	return ButtonAndText($dictionary['deleteTxt'], $item->itemId, $item->parentId, $action);
}

// WARNING!!! This putton must be placed inside the form which contains the data!!!
function modifyButtonAndText ($dictionary, $item, $action="modifyItemPost")
{
	return ButtonAndText($dictionary['modify'], $item->itemId, $item->parentId, $action);
}

// WARNING!!! This putton must be placed inside the form which contains the data!!!
function addButtonAndText ($dictionary, $parentId, $action="addItemPost")
{
    return ButtonAndText($dictionary['add'], 0, $parentId, $action);
}

function moveButtonAndText ($dictionary, $item, $action="move")
{
	return ButtonAndText($dictionary['move'], $item->itemId, $item->parentId, $action);
}

function cancelButtonAndText($dictionary, $parentId, $action="cancel")
{
    return ButtonAndText($dictionary['cancel'], 0, $parentId, $action);
}

/**
 * Displays an option box
 *
 * @param label = array of option box labels
 * @param value = array of option box values
 * @param defaultvalue = value to be selected
 * @param text = array of text displayed in the option box
 */
function OptionBox($label, $value, $defaultvalue, $text)
{
    $result = '';
	for ($i=0; $i<count($label); $i++)
	{
		$result .= '<option label="'.$label[$i];
		$result .= '" value="'.$value[$i].'" ';
		if (isset($defaultvalue))
		{
	    	if ($value[$i]==$defaultvalue)
	   		{
	   			$result .= 'selected="selected" ';
	   		}
		}
	   	$result .= '>'.$text[$i].'</option>';
	}
	return $result;
}

// Displays an option box "jan to dec"
function monthOptionBox ($dictionary, $default)
{
    $label = array();
    $value = array();
 	for ($i=1; $i<=12; $i++)
	{
        if ($i<10)
		{
			$label[] = $dictionary['month0'.$i];
		}
		else
		{
			$label[] = $dictionary['month'.$i];
		}
        $value[] = $i;
	}
	return OptionBox($label, $value, $default, $label);
}

// Displays an option box "1 to 31"
function dayOptionBox ($dictionary, $default)
{
    $value = array();
 	for ($i=1; $i<=31; $i++)
	{
        if ($i<10)
		{
			$value[] = '0'.$i;
		}
		else
		{
			$value[] = $i;
		}
	}
	return OptionBox($value, $value, $default, $value);
}

// Displays an option box with the year ((default-before) to (default+after))
function yearOptionBox ($default, $before, $after)
{
	$value = range($default-$before, $default+$after);
	return OptionBox($value, $value, $default, $value);
}

// Displays an option box in a table
function TableRowOptionBox($label, $value, $defaultvalue, $text, $action, $fieldname, $title="",
	$classtdTitle="inputParamName", $classtdValue="inputParamValue")
{
    $stringUtils = new StringUtils ();
    $result = '';

    if (isset($action))
    {
	    $result = '<tr>';
		// First field contains the name
		$result .= '<td class="'.$classtdTitle.'">'.$stringUtils->urlEncodeQuotes($title).':</td>';
		// Second field contains the value
		$result .= '<td class="'.$classtdValue.'">';
	    switch ($action)
	    {
	    	case 'add':
	    	case 'modify':
	            if (NULL == $defaultvalue)
	            {
					$defaultvalue = $value[0];
				}
	            $result .= '<select name="'.$fieldname.'">';
				$result .= OptionBox($label, $value, $defaultvalue, $text);
				$result .= '</select>';
	    		break;
			case 'show':
	         	$i = array_search($value, $defaultvalue);
	         	if ($i)
	         	{
	                  $result .= $text[$i];
				}
				break;
		}
		$result .= '</td>';
		$result .= '</tr>';
	}
	return $result;
}


// Displays option boxes for a date in a table
function TableRowDateOptionBox($date, $action, $dictionary, $fieldname="date", $dateformat="Y-m-d H:i", $title="",
		$classtdTitle="inputParamName", $classtdValue="inputParamValue")
{
    $stringUtils = new StringUtils ();
    $dateUtils = new DateUtils ();
    $result = '';

    if (isset($action))
    {
	 	$result = '<tr>';
		// First field contains the name
		$result .= '<td class="'.$classtdTitle.'">'.$stringUtils->urlEncodeQuotes($title).':</td>';
		// Second field contains the value
		$result .= '<td class="'.$classtdValue.'">';
	    switch ($action)
	    {
	    	case 'add':
	    		$date = date ('Y-m-d');
	    	case 'modify':
	            $result .= '<select name="'.$fieldname.'_Month">';
				$result .= monthOptionBox ($dictionary, $dateUtils->getMonthFromDate($date));
				$result .= '</select>';
				$result .= '<select name="'.$fieldname.'_Day">';
				$result .= dayOptionBox ($dictionary, $dateUtils->getDayInMonthFromDate($date));
				$result .= '</select>';
				$result .= '<select name="'.$fieldname.'_Year">';
				$result .= yearOptionBox($dateUtils->getYearFromDate($date), 10, 10);
				$result .= '</select>';
	    		break;
			case 'show':
				$result .= date($dateformat, strtotime($date));
		}
		$result .= '</td>';
		$result .= '</tr>';
	}

	return $result;
}

/**
 * Added by �yvind Hagen
 */
function spellButtonAndText ($dictionary)
{
	if (ini_get ('safe_mode'))
	{
		// Shell_exec doesn't work when safe mode is enabled
		return '';
	}
	include ('framework/configuration/languages.php');

	if (!isset($_SESSION['spelldict']))
	{
		$_SESSION['spelldict'] = $_SESSION['brimLanguage'];
	}
	$langCodes = array ();
	preg_match_all('([\w-]+)', shell_exec('aspell dump dicts'), $langCodes);

 	if ($langCodes[0] && isset($languages))
	{
		$result = '<script type="text/javascript" src="ext/javascript/speller/spellChecker.js">
			</script>';

		$result .= '<script type="text/javascript">
			<!--
			function openSpellChecker(form)
			{
				// * spellCheckAll() checks every text box/area
				//   in every form in the HTML document
				// * checkTextBoxes() checks only text boxes
				// * checkTextAreas() checks only text areas

				var langCode = form.lang.options
					[form.lang.selectedIndex];
				var speller = new spellChecker(langCode);
				speller.spellCheckAll();
			}
			//-->
			</script>';

		//$result .= '<h3>'.$dictionary['spellcheck'].'</h3>';

		$result .= '<form>';
		$result .= '<select name="lang">';
		$langs = array ();
		foreach ($languages as $language)
		{
			$langs[$language[0]] = $language[1];
		}
		foreach ($langCodes[0] as $langCode)
		{
			$knownLangCode = array_key_exists($langCode, $langs);
			$baseLangCode = !substr_count($langCode, '-');
			if ($baseLangCode && $knownLangCode &&
				!strcmp($langCode, $_SESSION['spelldict']))
			{
				$result .= '<option value="'.$langCode.
					'" selected>'
					.$langs[$langCode].'</option>';
			}
			else if ($baseLangCode && $knownLangCode)
			{
				$result .= '<option value="'.$langCode.'">'
					.$langs[$langCode].'</option>';
			}
			else if ($baseLangCode && !$knownLangCode)
			{
				$result .= '<option>'.$langCode.'</option>';
			}
		}
		$result .= '</select>';
		$result .= '<input type="button"
			class="button"
			onClick="openSpellChecker(this.form);"
			value="'.$dictionary['spellcheck'].'" />';
		$result .= '</form>';

	}
	else
	{
		$result = '';
	}
	return $result;
}

function spellButtonAndTextOld ($dictionary)
{
	if (shell_exec('aspell'))
	{
		$result = '<script type="text/javascript" src="ext/javascript/speller/spellChecker.js"></script>';

		$result .= '<script type="text/javascript">
		<!--
		function openSpellChecker()
		{
			// * spellCheckAll() checks every text input/area
			//   input in every form in the HTML document
			// * checkTextBoxes() checks only text inputs
			// * checkTextAreas() checks only text areas
			var speller = new spellChecker();
			speller.spellCheckAll();
		}
		// ->
		</script>';

		$result .= '<h3>'.$dictionary['spellcheck'].'</h3>';
		$result .= '<input type="button"
					class="button"
					onClick="openSpellChecker();"
					value="'.$dictionary['spellcheck'].'" />';
	}
	else
	{
		$result = '';
	}
	return $result;
}


function rowInput ($parameter, $dictionary, $defaultValue, $viewAction)
{
	$editable = (($viewAction=='show')?false:true);
    $result = '
    <tr>
        <td class="inputParamName">
            <label id="'.$parameter.'Label">'.$dictionary[$parameter].':</label>
        </td>
        <td class="inputParamValue">
            <input type="text" class="text" id="'.$parameter.'" name="'.$parameter.'" ';
            if (!$editable)
            {
                $result .= 'readonly="true" ';
            }
            if (isset ($defaultValue) && $defaultValue != null)
            {
                $result .= 'value="'.$defaultValue.'" ';
            }
            $result .= ' />
        </td>
    </tr>';
    return $result;
}

function rowTextArea ($parameter, $dictionary, $defaultValue, $viewAction)
{
	$editable = (($viewAction=='show')?false:true);
    $result = '
    <tr>
        <td class="inputParamName">
            <label id="'.$parameter.'Label">'.$dictionary[$parameter].':</label>
        </td>
        <td class="inputParamValue">
            <textarea class="text" id="'.$parameter.'" name="'.$parameter.'" ';
            if (!$editable)
            {
                $result .= 'readonly="true" ';
            }
			$result .= '>';
            if (isset ($defaultValue) && $defaultValue != null)
            {
                $result .= $defaultValue;
            }
            $result .= '</textarea>
        </td>
    </tr>';
    return $result;
}

function focusOnField ($field)
{
	$result = '<script type="text/javascript">
    <!--
        document.getElementById (\''.$field.'\').focus ();
    // -->
    </script>';
	return $result;
}

function cancelButton ($dictionary, $plugin, $parentId)
{
	$result = '
		<form method="POST" action="index.php">
			<input type="hidden" name="plugin" value="'.$plugin.'" />
			<input type="hidden" name="parentId" value="'.$parentId.'" />
			<input type="hidden" name="action" value="cancel" />
			<input type="submit" class="button" name="submit"
				value="'.$dictionary['cancel'].'" />
		</form>
	';
	return $result;
}

/**
 * Show the ancestor path. Contributed by Michael
 */
function ancestorPath ($ancestors, $plugin, $dictionary)
{
	$result = '
	<!-- Ancestors -->
	<table>
		<tr>
			<td>
				<a href="index.php?plugin='.$plugin.'&amp;parentId=0" class="ancestor"
				>'.$dictionary['root'].'</a>
			</td>';
	if (is_array ($ancestors))
	{
		//
		// all ancestors other than root
		//
		foreach($ancestors as $ancestor)
		{
			$result .= '
			<td>&nbsp;/&nbsp;
				<a href="index.php?plugin='.$plugin.'&amp;parentId='.$ancestor->itemId.'"
				class="ancestor">'.$ancestor->name.'</a>
			</td>';
		}
	}
	$result .= '
		</tr>
	</table>';
	return $result;
}
?>
