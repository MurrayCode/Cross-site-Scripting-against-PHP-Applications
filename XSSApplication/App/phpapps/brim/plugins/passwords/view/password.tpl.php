<?php

require_once 'framework/util/StringUtils.php';
include 'framework/view/globalFunctions.php';

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2004
 * @package org.brim-project.plugins.passwords
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

$stringUtils = new StringUtils ();
//
// If we are simply 'viewing' an item, add 'modify' as action to
// the action list
//
if ($viewAction == 'show')
{
	    $renderActions[0]['contents'][]= array (
		'href'=>'index.php?plugin=passwords&amp;action=modifyAskPassphrase&amp;itemId='.$itemId,
	        'name'=>'modify');
}
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
		echo ancestorPath ($parameters ['ancestors'], 'passwords', $dictionary);
	}
?>
<form method="POST" action="index.php">
<input type="hidden" name="plugin" value="passwords" />
<table>
<?php
	//if (!stristr($_SERVER["SERVER_PROTOCOL"],'https'))
	if (isset ($_SERVER['HTTPS']) && !stristr($_SERVER["HTTPS"],'on'))
	{
		$parameters['errors'][]='insecureConnection';
	}
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
            <?php echo $dictionary['password'] ?>:&nbsp;<input
                type="radio" class="radio" name="isParent" value="0"
                    onClick="javascript:itemSelected(true); "
                checked="checked"  />
        </td>
    </tr>
<?php
    }
?>
</table>
<div id="itemParameters">
<table>
<?php
    if (!$renderObjects->isParent ||
		(($renderObjects->isParent && $viewAction == 'add')))
    {
		echo rowInput ('passPhrase', $dictionary,
			(isset ($renderObjects) && isset ($renderObjects->passPhrase))?
                    $renderObjects->passPhrase:null, $viewAction);
		echo rowInput ('login', $dictionary,
			(isset ($renderObjects) && isset ($renderObjects->login))?
                    $renderObjects->login:null, $viewAction);
		echo rowInput ('password', $dictionary,
			(isset ($renderObjects) && isset ($renderObjects->password))?
                    $renderObjects->password:null, $viewAction);
		echo rowInput ('url', $dictionary,
			(isset ($renderObjects) && isset ($renderObjects->url))?
                    $renderObjects->url:null, $viewAction);
		echo rowTextArea ('description', $dictionary,
			(isset ($renderObjects) && isset ($renderObjects->description))?
                    $renderObjects->description:null, $viewAction);
    }
	else
	{
		// TODO is this needed?
		echo ('<input type="hidden" name="isParent" value="1" />');
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
		echo '<input type="hidden" name="plugin" value="passwords" />';
		echo moveButtonAndText ($dictionary, $renderObjects);
		echo '</form>';

		echo '<form method="POST" action="index.php" ';
		echo 'onsubmit="return confirmDelete()">';
		echo '<input type="hidden" name="plugin" value="passwords" />';
		echo deleteButtonAndText ( $dictionary, $renderObjects);
		echo '</form>';
	}

    echo '<form method="POST" action="index.php">';
	echo '<input type="hidden" name="plugin" value="passwords" />';
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
