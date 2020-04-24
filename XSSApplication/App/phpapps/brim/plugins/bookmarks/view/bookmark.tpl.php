<?php

include 'framework/view/globalFunctions.php';
require_once 'framework/util/BrowserUtils.php';

/**
 * The template file that draws the layout to add a bookmark.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.bookmarks
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

$browserUtils = new BrowserUtils ();
if (!$browserUtils->browserIsExplorer())
{
	//
	// Favicons don't work in Explorer
	//
?>
	<script type="text/javascript">
	<!--
		//
		// fadedout for the overlib library
		//
		var ol_fadetime=4000;

		//
		// The array that contains the form elements that should be displayed
		// when the item is not a folder
		//
		var itemFormParameters = Array ('description','locator',
						'locatorLabel','descriptionLabel',
						'ajaxFavicon');
		/**
	 	 * Retrieves a favicon by retrieving the value from the form and
	 	 * submitting it to the backend via a ajax call.
	 	 *
	 	 * When the call returns, the function 'faviconReceived' is called
	 	 */
		function getFavicon ()
		{
			//
			// Delete existing favicon
			//
			document.getElementById('favicon').value = '';
			document.getElementById('favImage').innerHTML = '&nbsp;';
			//
			// Get the ip and call the backend
			//
			var ip = document.getElementById ('locator').value;
			//
			// Show popup
			//
			document.getElementById('favImage').innerHTML = '<?php echo $icons['busy'] ?>';
			//
			// Call the backend
			//
			var theData="plugin=bookmarks&ajax=true&function=getFavicon&ip="+ip;
			$.ajax ({
				type:"POST",
				url:"index.php",
				data:theData,
				success:function(data)
				{
					faviconReceived (data);
				}
			});
			return false;
		}

		/**
		 * We have received the favicon from the backend. Show a status message and
		 * replace the html content with the retrieved icon (if found)
		 */
		function faviconReceived (result)
		{
			//
			// Get rid of popup
			//
			nd ();
			//
			// Show another popup that fades out with either the message that the item
			// needs to be modified or a message that no favicon is found
			//
			if (result == null || result == '' || result == ('null'))
			{
				document.getElementById('favImage').innerHTML = '&nbsp;';
				overlib('<?php echo $dictionary['noFaviconFound'] ?>', CAPTION, 'Message', FADEOUT,
				 	BUBBLE, BUBBLETYPE, 'roundcorners');
				nd ();
			}
			else
			{
				document.getElementById('favicon').value = result;
				document.getElementById('favImage').innerHTML =
					'<img src="data:image/x-icon;base64,'+result+'" border="0">';
				overlib('<?php echo $dictionary['faviconFetched'] ?>', CAPTION, 'Message', FADEOUT,
				 	BUBBLE, BUBBLETYPE, 'roundcorners');
				nd ();
			}
			return false;
		}

		/**
		 * Simply delete the icon. For the moment, only a status message is shown,
		 * no backend call is performed.
		 */
		function deleteFavicon ()
		{
			//
			// remove previous popup first
			//
			nd ();
			//
			// Now remove the favicon self
			//
			previousResult = document.getElementById ('favicon').value;
			document.getElementById('favicon').value = '';
			document.getElementById('favImage').innerHTML = '&nbsp;';
			if (previousResult != null && previousResult.length != 0)
			{
				overlib('<?php echo $dictionary['faviconDeleted'] ?>', CAPTION, 'Message', FADEOUT,
			 		BUBBLE, BUBBLETYPE, 'roundcorners');
				nd ();
			}
			return false;
		}

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
	//-->
	</script>
<?php
	}
?>
<h2>
	<?php echo $pageTitle ?>
</h2>
<?php
	//
	// Show the ancestor path. Contributed by Michael
	//
	if(isset($parameters['ancestors']))
	{
		echo ancestorPath ($parameters ['ancestors'], 'bookmarks', $dictionary);
	}
?>
<form method="POST" action="index.php">
<input type="hidden" name="plugin" value="bookmarks" />
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
			<?php echo $dictionary['bookmark'] ?>:&nbsp;<input
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
	if (!isset ($renderObjects) || !$renderObjects->isParent())
	{
?>
	<tr>
		<td class="inputParamName">
			<label id="locatorLabel"><?php echo $dictionary['locator'] ?>:</label>
		</td>
		<td class="inputParamValue">
			<input type="text" name="locator" id="locator" class="text"
				<?php if (isset ($renderObjects) && (isset ($renderObjects->locator)))
					{
						//
						// Render the URL, but only if we have one
						//
						echo ' value="'.str_replace ("&", "&amp;", $renderObjects->locator).'" ';
					}
				?>
			>
		</td>
	</tr>
	<tr>
		<td class="inputParamName">
			<label id="descriptionLabel"><?php echo $dictionary['description'] ?>:</label>
		</td>
		<td class="inputParamValue">
			<textarea id="description" name="description" class="text"
			><?php if (isset ($renderObjects) && (isset ($renderObjects->description)))
				{
					//
					// Render the description, but only if we have one
					//
					echo $renderObjects->description;
				}
			?></textarea>
		</td>
	</tr>
<?php
	}
?>
</table>
<input type="hidden" name="favicon" id="favicon" />
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
		echo '<form method="POST" action="index.php"> ';
		echo '<input type="hidden" name="plugin" value="bookmarks" />';
		echo moveButtonAndText ($dictionary, $renderObjects);
		echo '</form>';

		echo '<form method="POST" action="index.php" ';
		echo 'onsubmit="return confirmDelete()">';
		echo '<input type="hidden" name="plugin" value="bookmarks" />';
		echo deleteButtonAndText ( $dictionary, $renderObjects);
		echo '</form>';
	}
	echo '<form method="POST" action="index.php">';
	echo '<input type="hidden" name="plugin" value="bookmarks" />';
	echo cancelButtonAndText ($dictionary, $parentId);
	echo '</form>';
	if ($viewAction == 'add' || $viewAction == 'modify')
	{
		echo spellButtonAndText ($dictionary);
	}
?>

<?php
if (!$browserUtils->browserIsExplorer() && (!isset ($renderObjects) || !$renderObjects->isParent ()))
{
	//
	// Doesn't work in Explorer, so don't even bother
	//
	// This is the default form, no ajax calls, no javascript hooks
	// A piece of javascript code further on will disable this form, which
	// assures that when javascript is available, the javascript part is used.
	// If javascript is not available, this part will be used
	//
?>
<div id="formFavicon">
<table>
	<tr>
		<td>
			<form action="index.php" method="POST">
				<input type="submit" class="button" value="Favicon" />
				<input type="hidden" name="plugin" value="bookmarks" />
				<input type="hidden" name="action" value="getFavicon" />
				<input type="hidden" name="itemId"
					value="<?php echo $renderObjects->itemId ?>" />
			</form>
			<form action="index.php" method="POST">
				<input type="submit" class="button" value="Delete Favicon" />
				<input type="hidden" name="plugin" value="bookmarks" />
				<input type="hidden" name="action" value="deleteFavicon" />
				<input type="hidden" name="itemId"
					value="<?php echo $renderObjects->itemId ?>" />
			</form>
		</td>
		<td>
				<?php if (isset ($renderObjects->favicon)
					&& $renderObjects->favicon != '')
					{
						echo '<img src="data:image/x-icon;base64,'.
							str_replace(array("\n", "\r"), array("", ""), $renderObjects->favicon).'" alt="favicon" border="0">';
					}
					else
					{
						echo '&nbsp;';
					}
				?>
		</td>
	</tr>
</table>
</div>
<div id="ajaxFavicon">
<script type="text/javascript">
<!--
	//
	// If we have Javascript, set the display of the previous form
	// to invisible and output (also written by Javascript; we know
	// that this form will only be shown if we actually have javascript)
	// a different form that is capable of handling the cpaint callback
	//
	document.getElementById ('formFavicon').style.display = 'none';
	//
	// Now output the other form. Use javascript function calls to make sure
	// that this html is only rendered if javascript is really available
	//
	document.write ('																	\
		<table border="0"> 																\
			<tr> 																		\
				<td>																	\
					<form action="#" onSubmit="javascript:return getFavicon ();"> 		\
						<input type="submit" class="button" 							\
								value="<?php echo $dictionary['favicon'] ?>" /> 		\
					<\/form> 															\
					<form action="#" onSubmit="javascript:return deleteFavicon ();"> 	\
						<input type="submit" class="button" 							\
								value="<?php echo $dictionary['deleteFavicon'] ?>" \/> 	\
					<\/form>																\
				<\/td>																	\
				<td> 																	\
					<div id="favImage"> 												\
	');
	<?php
		if (isset ($renderObjects->favicon) && $renderObjects->favicon != '')
		{
			$escaped = str_replace(array("\n", "\r"), array("", ""), $renderObjects->favicon);
	?>
		document.write ('<img src="data:image/x-icon;base64,<?php echo $escaped ?>" border="0">');
	<?php
		} else {
	 ?>
		document.write ('&nbsp;');
	<?php } ?>
	document.write('<\/div> ');
	document.write('<\/td> ');
	document.write('<\/tr> <\/table>');
//-->
</script>
</div>
<?php
	}
	if ($viewAction == 'add' || 'viewAction' == 'modify')
	{
		echo focusOnField ('name');
	}
?>
