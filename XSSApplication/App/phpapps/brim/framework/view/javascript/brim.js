/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.templates
 * @subpackage barrel
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2005 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
	var ol_textcolor="#123456";
	var ol_capcolor="#ffffff";
	var ol_bgcolor="#123456";
	var ol_fgcolor="#efefef";

	function addBookmarksNetscapePanel($location)
	{
		if ((typeof window.sidebar == "object") &&
			(typeof window.sidebar.addPanel == "function"))
		{
			window.sidebar.addPanel
				("Brim Bookmarks", "$location", "");
		}
	}

	function confirmDelete ()
	{
		return confirm (confirm_delete);
	}

	function zebraItems ()
	{
		//
		// Recalculate the zebra for the table
		//
		$("table.zebraStripe tr:nth-child(odd)").removeClass ().addClass("odd");
		$("table.zebraStripe tr:nth-child(even)").removeClass ().addClass("even");
		//
		// Header does not need a specific class
		//
		$("table.zebraStripe table.sortableTable #header").removeClass().addClass ("sortableTableHeader");
	}

	function executeAjax (thePlugin, theFunction, theParams, thePhpSessionId)
	{
		$.ajax ({
			type:"POST",
			url:"index.php",
			data:"ajax=true&PHPSESSID="+thePhpSessionId+"&plugin="+thePlugin+"&function="+theFunction+theParams,
			success: function(msg)
			{
				if (msg == "")
				{
					alert ("Undefined error (empty message)");
				}
				else
				{
//alert (msg);
					var json = eval ("("+msg+")");
					if (json["error"])
					{
						alert ("Error: " +json["error"]);
					}
				}
			}
		});
	}

	function animateDeleteItem (itemId)
	{
		var itemId = $("#item_"+itemId);
		itemId.TransferTo ({
				to:"trash",
				className:"itemTransfer", 
				duration: 750
		});
		itemId.remove ();
		itemId.empty ();
		//alert ($(".sortableTable").id());
		zebraItems();
	}

/*
	function animateDeleteFolder (itemId)
	{
		$("#folder_"+itemId).removeClass ().hide ("slow");
	}
*/

	function animateTrash ()
	{
		trashCount++;
		if (trashCount == 1)
		{
			// was zero, one item deleted. Change bin image
			$("#trashImage").src(trashFullImage);
		}
	}


	function deleteItem (plugin, type, itemId, phpSessionId)
	{
		executeAjax (plugin, "trash", "&itemId="+itemId, phpSessionId);
		if (type == "item")
		{
			animateDeleteItem (itemId);
		}
		else // Folder
		{
			animateDeleteFolder (itemId);
		}
		animateTrash ();
	}

/*
	function moveItem (plugin, parent, item, phpSessionId)
	{
		animateMoveItem (parent, item);
		var parentId = parent.id.split ("_")[1];
//this.id.split ("_")[1], drag.id.split ("_")[1]
		var itemId = item.id.split("_")[1];
		executeAjax (plugin, "moveItem", "&amp;itemId="+itemId+"&amp;parentId="+parentId, phpSessionId);
	}

	function animateMoveItem (parent, item)
	{
		$(item).TransferTo({to:parent,className:"itemTransfer", duration: 1500});
		$(item).remove ();
		zebraItems ();
	}	
*/
	function setupSortableTables (disableHeaders)
	{
		$(".sortableTable").tableSorter ({
			sortClassAsc: 'sortableTableHeaderUp',
			sortClassDesc: 'sortableTableHeaderDown',
			headerClass: 'sortableTableHeader',
			/*highlightClass: 'highlightedColumn',*/
			stripingRowClass: ['even','odd'],
			bind: 'resort',
			useCache: false,
			debug: false,
			disableHeader: disableHeaders
		});
	}

	$(document).ready(function()
	{
		$("#loading").ajaxStart(function(){ $(this).show(); });
		$("#loading").ajaxStop(function(){ $(this).hide(); });
		$.ajaxTimeout (5000);
		$("#loading").ajaxError (function (){
			$("#loading").css ("background-color", "red");
			$("#loading").text ("Error");
		});
	});

