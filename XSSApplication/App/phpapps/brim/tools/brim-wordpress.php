<?php

/*
Plugin Name: Brim bookmarks
Plugin URI: http://www.brim-project.org/
Description: Brim bookmarks provides a hook to your public bookmarks within Brim
Author: Barry Nauta
Version: 1.0
Author URI: http://barry.nauta.be/
*/


/**
 * This function adds children to the current item, If the current 
 * item is expanded and applies this fucntion recursively to the 
 * children of the current item
 *
 * @param object item the item for which we would like to add children
 * 	if this item is expanded
 * @param services the services (model) used to retrieve the public
 *  	items for the given user
 * @param string username the name of the user 
 */
function addExpandedChildren (&$item, $services, $username)
{
	//
	// Only look for this items children if we have an item that 
	// is a parent and expanded
	//
	if ($item->isParent () && isExpanded ($item->itemId))
	{
		$items = $services->getPublicChildrenForUser ($username ,$item->itemId);
		//
		// Apply the same function recursively on this item's children
		//
		for ($i=0; $i<count($items); $i++)
		{
			$child =& $items[$i];
			addExpandedChildren ($child, $services, $username);
			$item->addChild ($child);
		}
	}
}


/**
 * Returns the icons used for the application 
 * (i.e. folder open, folder closed, node etc)
 * @return array icons an array with icon definitions
 */
function getIcons ()
{
	$brimURL = get_option ('brim_url').'/';
	$icons = array ();
	$icons['root']='<h2>RooT</h2>';
	$icons['up']="[...up...]";
	$icons['bar']='<img src="'.$brimURL.'/framework/view/pics/tree/empty_bar.gif" border="0">';
	$icons['minus']='<img src="'.$brimURL.'/framework/view/pics/tree/shaded_minus.gif" border="0">';
	$icons['corner']='<img src="'.$brimURL.'/framework/view/pics/tree/empty_corner.gif" border="0">';
	$icons['plus']='<img src="'.$brimURL.'/framework/view/pics/tree/shaded_plus.gif" border="0">';
	$icons['tee']='<img src="'.$brimURL.'/framework/view/pics/tree/empty_tee.gif" border="0">';
	$icons['folder_open']='<img src="'.$brimURL.'/framework/view/pics/tree/gnome_folder_open.gif" border="0">';
	$icons['folder_closed']='<img src="'.$brimURL.'framework/view/pics/tree/gnome_folder_closed.gif" border="0">';
	$icons['node']='<img src="'.$brimURL.'/framework/view/pics/tree/oerdec_item.gif" border="0">';
	$icons['open_new_window']='&nbsp<img src="'.$brimURL.'/framework/view/pics/tree/arrow.gif" border="0">';
	return $icons;
}

/**
 * Is this itemId in the expanded list?
 *
 * @param integer itemId the id of the item
 * @return boolean <code>true</code> if this item is expanded, 
 * 		<code>false</code> otherwise
 */
function isExpanded ($itemId)
{
	if (!isset ($_GET['expand']))
	{
		return false;
	}
	$expanded = explode (",", $_GET['expand']);
	while (list ($key, $val) = each($expanded))
	{
		if ($val == $itemId)
		{
			//
			// yep, in the expanded list
			//
			return true;
		}
	}
	return false;
}

/**
 * Callback function that fetches the appropriate parameters and 
 * draws a tree with the bookmarks
 */
function brim_bookmarks ()
{
	$resultString = '';
	//
	// Configuration used by the tree renderer. This configuration
	// is used by the treedelegate and since eachplugin uses a
	// different (hardcoded) delegate (like ExplorerTreeDelegate,
	// YahooTreeDelegate etc), this configuration needs to be set
	// before instantiating the delegate
	//
	$configuration = array ();
	//
	// Callback is the executing script, this allows a user
	// to include this script in an embedding page
	//
	$username = get_option ('brim_username');
	$configuration['callback'] = get_settings('site_url');
	set_include_path (get_option('brim_absolute_path'));
	$configuration['icons'] = getIcons ();
	$configuration['plugin'] = 'bookmarks';
	$parentId = 0;
	// 
	// Now instantiate the appropriate classes
	//
	require_once ('plugins/bookmarks/model/BookmarkServices.php');
	include ('plugins/bookmarks/i18n/dictionary_en.php');
	$services = new BookmarkServices ();
	require_once ('framework/view/PublicExplorerTreeDelegate.php');
	$delegate = new PublicExplorerTreeDelegate ($configuration);
	// 
	// Check if we clicked on a folders link. Open this folder if this 
	// is  the case
	//
	if (isset ($_GET['parentId']))
	{
		$parentId = $_GET['parentId'];
	}
	// 
	// Get the public items
	//
	$rootItems = $services->getPublicChildrenForUser ($username, $parentId);
	$root = $services->getItem ($username, $parentId);
	for ($i=0; $i<count($rootItems); $i++)
	{
		$item =& $rootItems[$i];
		addExpandedChildren ($item, $services, $username);
	}
	//
	// Create the tree and show the items
	//
	require_once ('framework/view/Tree.php');
	$tree = new Tree ($delegate, $configuration);
	$tree->setExpanded ($_GET['expand']);
	$resultString .= $tree->toHtml ($root, $rootItems);
	if (function_exists ('mysql_close'))
	{
		mysql_close ();
	}
	return $resultString;
}


/**
 * The administration men, hookup ito wordpress
 */
function brim_admin_menu () 
{
	if (function_exists('add_options_page')) 
	{
		add_options_page('Brim - bookmarks', 
			'Brim - bookmarks', 0, 
			basename(__FILE__), 'brim_options_panel');
	}
}
add_action ('admin_menu', 'brim_admin_menu');

/**
 * Create the panel for brim within wordpress.
 * This panel allows the user to change the following parameters:
 * <ul>
 * <li><b>Brim absolute path</b>, the path (filesystem) to the brim 
 * installation i.e. /home/test/public_html/brim/</li>
 * <li><b>Brim URL</b>, the URL to the brim installation, i.e. 
 * http://localhost/~test/brim</li>
 * <li><b>Brim username</b> the (Brim) username for the bookmarks we 
 * wish to embed
 */
function brim_options_panel () 
{
	if (isset($_POST['info_update'])) 
	{
		echo '<div class="updated"><p><strong>';
		_e('Options saved.');
		echo '</strong></p></div>';
		update_option ('brim_absolute_path',$_POST['brim_absolute_path']);
		update_option ('brim_username',$_POST['brim_username']);
		update_option ('brim_url',$_POST['brim_url']);
	} 
	echo '
		<div class=wrap>
			<form method="post" name="brim_option" action="'.$_SERVER['PHP_SELF'].'">
				<h2>Brim - bookmarks</h2>
				<fieldset class="options">
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" 
					value="\'brim_absolute_path\',\'brim_user\'" />
				<table width="100%" cellspacing="2" cellpadding="5" 
						class="editform">
					<tr>
						<th width="33%" valign="top" 
							scope="row">'._('Absolute path to your Brim installation:').'</th>
						<td>
							<input name="brim_absolute_path" type="text" id="brim_absolute_path" 
								value="'.get_option('brim_absolute_path').'" size="50" 
							/>
							<br />'._('Something like /home/username/public_html/brim/').'</td>
					<tr>
						<th width="33%" valign="top" 
							scope="row">'._('URL for your Brim installation:').'</th>
						<td>
							<input name="brim_url" type="text" id="brim_url" 
								value="'.get_option('brim_url').'" size="50" 
							/><br />'._('Something like http://your.host/brim/').'
							
						</td>
					</tr>
					<tr>
						<th width="33%" valign="top" 
							scope="row">'._('Brim username for your public bookmarks:').'</th>
						<td>
							<input name="brim_username" type="text" id="brim_username" 
								value="'.get_option('brim_username').'" size="50" />
						</td>
					</tr>
				</table>
				</fieldset>
				<div class="submit">
					<input type="submit" name="info_update" value=" »" />
				</div>
			</form>
		</div> ';
}
?>
