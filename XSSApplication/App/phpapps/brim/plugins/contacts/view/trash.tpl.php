<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2006
 * @package org.brim-project.plugins.contacts
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
?>


<script type="text/javascript" src="ext/jQuery/jquery-checkboxes.js"></script>
<script type="text/javascript" src="ext/jQuery/jquery.tablesorter.js"></script>
<!--
<script type="text/javascript" src="framework/view/javascript/brim.js"></script>
-->
<script type="text/javascript" type="text/javascript">
<!--
function deleteForever ()
{
	if (confirmDelete ())
	{
	}
}

function toggleAllCheckboxes ()
{
    $("#theForm").toggleCheckboxes();
}

function deleteForeverConfirmation()
{
	//if (window.confirm(confirm_delete))
	//{
	$("#theForm > #action").val ("deleteForever");
	$("#theForm").submit();
	//}
}
function undeleteAction ()
{
	// Make sure to find the right form. There is also 
	// a search form on the page
	$("#theForm > #action").val("undelete");
	$("#theForm").submit();
}
$(document).ready (
function ()
{
	zebraItems ();
	setupSortableTables ([0,1]);
});

// -->
</script>

<h2><?php echo $dictionary['trash'] ?></h2>
<?php
	include ('templates/'.$_SESSION['brimTemplate'].'/icons.inc');
	$configuration = array ();

	$callback = 'index.php?plugin=contacts';
	if (count ($renderObjects > 0))
	{
		echo '
		<form id="theForm" method="POST" action="index.php">
		<input type="hidden" name="plugin" value="contacts" />
		<input type="hidden" name="action" id="action" value="undefined" />
		<table class="zebraStripe sortableTable">
			<thead>
			<tr id="header">
				<th></th>
				<th></th>
				<th>Name</th>
				<th>When deleted</th>
			</tr>
			</thead>
	
			<tbody>';
			foreach ($renderObjects as $trashed)
			{
			echo '
			<tr>
				<td>
					<input type="checkbox" 
						name="itemid_'.$trashed->itemId.'">
				</td>
				<td>';
					if ($trashed->isParent)
					{
						echo $icons['folder_closed'];
					}
					else
					{
						echo $icons['node'];
					}
					echo '
				</td>
				<td>'.$trashed->name.'</td>
				<td>'.$trashed->when_modified.'</td>
			</tr>';
			}
			echo '
			</tbody>
		</table>
        [<a href="javascript:toggleAllCheckboxes();">'.$dictionary['toggleSelection'].'</a>]
        <br />
		<input type="submit" 
			id="deleteForever" 
			name="deleteForever"
			class="button"
			onclick="deleteForeverConfirmation()"
			value="'.$dictionary['deleteForever'].'" />
		<input type="submit" 
			id="undelete" 
			name="undelete"
			class="button"
			onclick="undeleteAction ();"
			value="'.$dictionary['undelete'].'" />
		</form>';
	}
	else
	{
		echo $dictionary['emptyTrash'];
	}

?>
