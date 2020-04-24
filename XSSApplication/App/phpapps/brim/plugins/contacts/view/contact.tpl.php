<?php

include 'framework/view/globalFunctions.php';

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.contacts
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
function params ($action, $fieldValue)
{
	$result = '';
	if ($action == 'show')
	{
		$result .= 'readonly="true" ';
	}
	if ($action == 'show' || $action == 'modify')
	{
		if (isset ($fieldValue) && $fieldValue != '')
		{
			$result .= 'value="'.$fieldValue.'" ';
		}
	}
	return $result;
}

?>
<script type="text/javascript" language="javascript">
<!--
		//
		// The array that contains the form elements that should be displayed
		// when the item is not a folder
		//
		var itemFormParameters = Array ('itemParameters');
		/**
		 * Function call when either item or folder is selected.
		 * Based on the selection, a number of form items will be displayed
		 * or hidden
		 * @see array itemFormParameters
		 */
		function itemSelected (isFolder)
		{
			var display = isFolder?'':'none';
			for (i=0; i<itemFormParameters.length; i++)
			{
				document.getElementById (itemFormParameters[i]).style.display=display;
			}
			return false;
		}

// -->
</script>
<h2>
	<?php echo $pageTitle; ?>
</h2>
<?php
	//
	// Show the ancestor path. Contributed by Michael
	//
	if(isset($parameters['ancestors']))
	{
		echo ancestorPath ($parameters ['ancestors'], 'contacts', $dictionary);
	}
?>
<form method="POST" action="index.php">
<input type="hidden" name="plugin" value="contacts" />
<table>

<?php
	if(!empty($parameters['errors']))
	{
		echo standardTableRowErrorMessages
			($dictionary, $parameters['errors'], $icons);
	}
	echo standardTableRowInput ('name',
		$dictionary, $viewAction, $renderObjects);

	if ($viewAction == 'add')
	{
?>
	<tr>
		<td class="inputParamName">&nbsp;</td>
		<td class="inputParamValue">
			<?php echo $dictionary['folder']; ?>:&nbsp;<input
				type="radio" class="radio" name="isParent" value="1"
				onClick="javascript:itemSelected(false); "
				/>
			<br />
			<?php echo $dictionary['contact'] ?>:&nbsp;<input
				type="radio" class="radio" name="isParent" value="0"
				onClick="javascript:itemSelected(true); "
				checked="checked"  />
		</td>
	</tr>
<?php
	}
	echo standardTableRowPublicPrivateRadios
		($dictionary, $viewAction, $renderObjects);
	//
	// Only render the locator and description if we either
	// - have no item (add) or
	// - we have an item (modify), but the item is no parent
	//
	echo '
</table>
<div id="itemParameters">
<table>';
	if (!isset ($renderObjects) || !$renderObjects->isParent())
	{
		echo rowInput ('tel_home', $dictionary,
			(isset ($renderObjects) && isset ($renderObjects->tel_home))
				?$renderObjects->tel_home:null, $viewAction);
		echo rowInput ('tel_work', $dictionary,
			(isset ($renderObjects) && isset ($renderObjects->tel_work))
				?$renderObjects->tel_work:null, $viewAction);
		echo rowInput ('mobile', $dictionary,
			(isset ($renderObjects) && isset ($renderObjects->mobile))
				?$renderObjects->mobile:null, $viewAction);
		echo rowInput ('faximile', $dictionary,
			(isset ($renderObjects) && isset ($renderObjects->faximile))
				?$renderObjects->faximile:null, $viewAction);
?>
<tr>
	<td class="inputParamName">
		<label id="email1Label"><?php echo $dictionary['email_home'] ?>:</label>
	</td>
	<td class="inputParamValue">
		<?php
			//
			// Show the email as hyperlink when we view
			// (as opposed to modifying or editing)
			//
			if ($viewAction == 'show')
			{
				echo ('<a href="mailto:'.$renderObjects->email1.'"');
				echo ('>'.$renderObjects->email1.'</a>');
			}
			else
			{
				echo '<input type="text" ';
				echo 'class="text" ';
				echo 'id="email1" ';
				echo 'name="email1" ';
				echo params ($viewAction, $renderObjects->email1);
				echo '/>';
			}
		?>
	</td>
</tr>
<tr>
	<td class="inputParamName">
		<label id="email2Label"><?php echo $dictionary['email_work'] ?>:</label>
	</td>
	<td class="inputParamValue">
		<?php
			//
			// Show the email as hyperlink when we view
			// (as opposed to modifying or editing)
			//
			if ($viewAction == 'show')
			{
				echo ('<a href="mailto:'.$renderObjects->email2.'"');
				echo ('>'.$renderObjects->email2.'</a>');
			}
			else
			{
				echo '<input type="text" ';
				echo 'class="text" ';
				echo 'id="email2" ';
				echo 'name="email2" ';
				echo params ($viewAction, $renderObjects->email2);
				echo '/>';
			}
		?>
	</td>
</tr>
<tr>
	<td class="inputParamName">
		<label id="email3Label"><?php echo $dictionary['email_other'] ?>:</label>
	</td>
	<td class="inputParamValue">
		<?php
			//
			// Show the email as hyperlink when we view
			// (as opposed to modifying or editing)
			//
			if ($viewAction == 'show')
			{
				echo ('<a href="mailto:'.$renderObjects->email3.'"');
				echo ('>'.$renderObjects->email3.'</a>');
			}
			else
			{
				echo '<input type="text" ';
				echo 'class="text" ';
				echo 'id="email3" ';
				echo 'name="email3" ';
				echo params ($viewAction, $renderObjects->email3);
				echo '/>';
			}
		?>
	</td>
</tr>
<tr>
	<td class="inputParamName">
		<label id="webaddress1Label"><?php echo $dictionary['webaddress_homepage'] ?>:</label>
	</td>
	<td class="inputParamValue">
		<?php
			//
			// Show the webaddress as hyperlink when we view
			// (as opposed to modifying or editing)
			//
			if ($viewAction == 'show')
			{
				echo ('<a href="'.(($renderObjects->webaddress1=='') ? '#' : $renderObjects->webaddress1).'"');
				echo ('>'.$renderObjects->webaddress1.'</a>');
			}
			else
			{
				echo '<input type="text" ';
				echo 'class="text" ';
				echo 'id="webaddress1" ';
				echo 'name="webaddress1" ';
				echo params ($viewAction, $renderObjects->webaddress1);
				echo '/>';
			}
		?>
	</td>
</tr>
<tr>
	<td class="inputParamName">
		<label id="webaddress2Label"><?php echo $dictionary['webaddress_work'] ?>:</label>
	</td>
	<td class="inputParamValue">
		<?php
			//
			// Show the webaddress as hyperlink when we view
			// (as opposed to modifying or editing)
			//
			if ($viewAction == 'show')
			{
				echo ('<a href="'.(($renderObjects->webaddress2=='') ? '#' : $renderObjects->webaddress2).'"');
				echo ('>'.$renderObjects->webaddress2.'</a>');
			}
			else
			{
				echo '<input type="text" ';
				echo 'class="text" ';
				echo 'id="webaddress2" ';
				echo 'name="webaddress2" ';
				echo params ($viewAction, $renderObjects->webaddress2);
				echo '/>';
			}
		?>
	</td>
</tr>
<tr>
	<td class="inputParamName">
		<label id="webaddress3Label"><?php echo $dictionary['webaddress_home'] ?>:</label>
	</td>
	<td class="inputParamValue">
		<?php
			//
			// Show the webaddress as hyperlink when we view
			// (as opposed to modifying or editing)
			//
			if ($viewAction == 'show')
			{
				echo ('<a href="'.(($renderObjects->webaddress3=='') ? '#' : $renderObjects->webaddress3).'"');
				echo ('>'.$renderObjects->webaddress3.'</a>');
			}
			else
			{
				echo '<input type="text" ';
				echo 'class="text" ';
				echo 'id="webaddress3" ';
				echo 'name="webaddress3" ';
				echo params ($viewAction, $renderObjects->webaddress3);
				echo '/>';
			}
		?>
	</td>
</tr>
<?php
	echo rowInput ('job', $dictionary,
		(isset ($renderObjects) && isset ($renderObjects->job))
			?$renderObjects->job:null, $viewAction);
	echo rowInput ('alias', $dictionary,
		(isset ($renderObjects) && isset ($renderObjects->alias))
			?$renderObjects->alias:null, $viewAction);
	echo rowInput ('organization', $dictionary,
		(isset ($renderObjects) && isset ($renderObjects->organization))
			?$renderObjects->organization:null, $viewAction);
	echo rowTextArea ('address', $dictionary,
		(isset ($renderObjects) && isset ($renderObjects->address))
			?$renderObjects->address:null, $viewAction);
	echo rowTextArea ('org_address', $dictionary,
		(isset ($renderObjects) && isset ($renderObjects->org_address))
			?$renderObjects->org_address:null, $viewAction);
	echo rowTextArea ('description', $dictionary,
		(isset ($renderObjects) && isset ($renderObjects->description))
			?$renderObjects->description:null, $viewAction);
	}
?>
</table>
	</div>
<?php
	if ($viewAction == 'modify')
	{
		echo modifyButtonAndText ($dictionary, $renderObjects);
	}
	else if ($viewAction == 'add')
	{
		//echo addButtonAndText ($dictionary, $parentId);
?>
                <input type="hidden"
                	name="parentId"
                	id="parentId"
                        value="<?php echo $parentId ?>"
                />
                <input type="hidden"
                	name="action"
                	id="action"
                        value="addItemPost"
                />
                <input type="submit"
                        class="button"
                        name="submit"
                        value="<?php echo $dictionary['add'] ?>"
                />
                <input type="submit"
                        id="addAndAddAnother"
                        name="addAndAddAnother"
                        class="button"
                        onclick="javascript:document.getElementById('action').value='addAndAddAnother';document.forms[0].submit()"
                        value="<?php echo $dictionary['addAndAddAnother'] ?>"
                />
<?php

	}
?>
</form>
<?php
	if ($viewAction == 'modify')
	{
		echo '<form method="POST" action="index.php">';
		echo '<input type="hidden" name="plugin" value="contacts" />';
		echo moveButtonAndText ($dictionary, $renderObjects);
		echo '</form>';

		echo '<form method="POST" action="index.php" ';
		echo 'onsubmit="return confirmDelete()">';
		echo '<input type="hidden" name="plugin" value="contacts" />';
		echo deleteButtonAndText ( $dictionary, $renderObjects);
		echo '</form>';
	}
    echo '<form method="POST" action="index.php">';
	echo '<input type="hidden" name="plugin" value="contacts" />';
    // cancel button
    echo cancelButtonAndText ($dictionary, $parentId);
    echo '</form>';
	if ($viewAction == 'add' || $viewAction == 'modify')
	{
		echo spellButtonAndText ($dictionary);
	}
	if ($viewAction == 'add' || 'viewAction' == 'modify')
	{
        echo focusOnField ('name');
    }
?>
