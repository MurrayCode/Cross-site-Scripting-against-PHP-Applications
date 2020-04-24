<?php

require_once 'framework/util/StringUtils.php';
include 'framework/view/globalFunctions.php';

/**
 * The template file that draws the layout to add a note.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.notes
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

$stringUtils = new StringUtils ();

?>

<script type="text/javascript">
<!--
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
	<?php echo $pageTitle ?>
</h2>
<?php
	//
	// Show the ancestor path. Contributed by Michael
	//
	if(isset($parameters['ancestors']))
	{
		echo ancestorPath ($parameters ['ancestors'], 'notes', $dictionary);
	}
?>
<form method="POST" action="index.php">
<input type="hidden" name="plugin" value="notes" />
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
            <?php echo $dictionary['note'] ?>:&nbsp;<input
                type="radio" class="radio" name="isParent" value="0"
                onClick="javascript:itemSelected(true); "
                checked="checked"  />
        </td>
    </tr>
<?php
    }
	echo standardTableRowPublicPrivateRadios
		($dictionary, $viewAction, $renderObjects);
?>
</table>
<div id="itemParameters">
<table>
<?php
    //
    // Only render the items parameters if we either
    // - have no item (add) or
    // - we have an item (modify), but the item is no parent
    //
    if (!isset ($renderObjects) || !$renderObjects->isParent())
    {
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
		<input type="hidden" name="parentId" value="<?php echo $parentId ?>" />
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
		echo '<form method="POST" action="index.php"> ';
		echo '<input type="hidden" name="plugin" value="notes" />';
		echo moveButtonAndText ($dictionary, $renderObjects);
		echo '</form>';

		echo '<form method="POST" action="index.php" ';
		echo 'onsubmit="return confirmDelete()">';
		echo '<input type="hidden" name="plugin" value="notes" />';
		echo deleteButtonAndText ( $dictionary, $renderObjects);
		echo '</form>';
	}

    echo '<form method="POST" action="index.php">';
	echo '<input type="hidden" name="plugin" value="notes" />';
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
