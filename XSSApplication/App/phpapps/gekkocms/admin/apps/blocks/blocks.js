//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
var app_name =  "blocks";
var app_description ="blocks";

// ******************************************************************************************************************************************** 
Admin.Blocks = function (theapp_name, theapp_description, field_id, field_category_id, field_item_title, field_category_title)
{
	if (document.getElementById('gekko_multiple_categories_checkboxes')) this.alternative_mode = true; else this.alternative_mode = false;
    this.constructor.superclass.constructor.call(this,theapp_name, theapp_description, 'id','title');
	//this.setMenuSelectionEventListener();
	
};

///////////////////////////////////////////////////////////
$extend_class(Admin.Blocks, Admin.BasicSimpleCategories);
///////////////////////////////////////////////////////////
Admin.Blocks.prototype.buildDataColumnDefinitionAndResponseSchema = function()
{

	var status_array = [{value:0, label: "Inactive"}, {value:1, label: "Active"} ];
	this.columnDefinition = [
		{key:"check", label: this.getHeaderSelectAllCheckbox(), formatter:this.formatSelectionCheckBox}, // use the built-in checkbox formatter (shortcut)
		{key:"button", label:"ID", sortable:true,formatter:this.formatIcon}, // use the built-in button formatter
		{key: this.field_category_id, hidden:true, sortable:false, formatter:"number"},
		{key: this.field_id, hidden:true, sortable:false, formatter:"number"},
		{key: this.field_item_title, label: 'Block Name', sortable:true, formatter:this.formatEditLink}, 
		{key: "original_block", label: 'Instance Of', sortable:true, formatter:"string"}, 
		{key:"status", label: 'Status', sortable:true, formatter:this.formatStatus, editor:  new YAHOO.widget.DropdownCellEditor({dropdownOptions:status_array,disableBtns:false}) }, 
		{key:"sort_order", label: 'Sort Order', sortable:true, formatter:"number", editor: new YAHOO.widget.TextboxCellEditor({validator:YAHOO.widget.DataTable.validateNumber,disableBtns:false})},
		{key:"date_modified", label: 'Date Modified', sortable:true, formatter:this.formatDate} // use the built-in date		
	];
	
	this.dataResponseSchema = {
		resultsList: "data",
		// Use the parse methods to populate the RecordSet with the right data types
		fields: [
			{key:this.field_category_id, parser:"number"}, 
			{key:this.field_id, parser:"number"}, 
			{key:this.field_item_title, parser:"string"}, 
			{key:"original_block", parser:"string"},
			{key:"status", parser:"number"},
			{key:"sort_order", parser:"number"}, 
			{key:"virtual_filename", parser:"string"}, 					
			{key:"date_modified", parser:"date"}
		]
	};
}
///////////////////////////////////////////////////////////

Admin.Blocks.prototype.setMenuSelectionEventListener = function ()
{
   var display_in_menu = document.forms.block_item_editor.display_in_menu_workaround.value;
   if (display_in_menu == 1) this.hideMenuSelection(); 
   YAHOO.util.Event.addListener(document.getElementById('menu_select_everywhere'), "click", this.hideMenuSelection, this, true); 	   
   YAHOO.util.Event.addListener(document.getElementById('menu_select_some'), "click", this.showMenuSelection, this, true); 			
}
///////////////////////////////////////////////////////////
Admin.Blocks.prototype.hideMenuSelection = function ()
{
	var menu = document.getElementById('gekko_multiple_categories_checkboxes');
	menu.style.display = 'none';
}
///////////////////////////////////////////////////////////
Admin.Blocks.prototype.showMenuSelection = function ()
{
	var menu = document.getElementById('gekko_multiple_categories_checkboxes');
	menu.style.display = 'block';
}
///////////////////////////////////////////////////////////
Admin.Blocks.prototype.getCategoryListingURL = function() {
	var action = "";
	
	if (this.alternative_mode)
		action= "action=getmenus";
	else
		action= "action=getallcategories";
	return action;
};

///////////////////////////////////////////////////////////
Admin.Blocks.prototype.Run = function ()
{
	if (this.alternative_mode)
	{
		this.categoryTree = new YAHOO.widget.TreeView('gekko_multiple_categories_checkboxes');
		this.getTreeListing();
		this.getBlockMenuAssociation();
		this.setMenuSelectionEventListener();
	} else Admin.Blocks.superclass.Run.call(this);
}
///////////////////////////////////////////////////////////
Admin.Blocks.prototype.receiveItemCategoriesArray = function(o)
{
	this.itemcategoriesarray = ajaxParse (o.responseText);
	for (var i = 0; i < this.itemcategoriesarray.length;i++)
	{
		var chk_id = 'chk_cid_block_leftfolder_' +this.itemcategoriesarray[i].menu_id;
		var checkbox = document.getElementById(chk_id);
		if (checkbox) checkbox.checked = true; // null or not?
	}
}

///////////////////////////////////////////////////////////

Admin.Blocks.prototype.getBlockMenuAssociation = function()
{
	var id = document.getElementById('id').value;
	this.ajaxRequestGET( this.receiveItemCategoriesArray,"action=getvisibility&id=" + id);	

	return false;
}
///////////////////////////////////////////////////////////
Admin.Blocks.prototype.formatStatus = function(elCell, oRecord, oColumn, oData) {
	if (oRecord.getData("id") > 0)
	{
		switch (oData)
		{
			case 1:  class_name = 'gekko_status_active';break;
			default: class_name = 'gekko_status_inactive';break;		
		}	
		elCell.innerHTML = "<div class=\"" + class_name +"\">&nbsp;</div>";
	} else elCell.innerHTML = "<div></div>";
};

///////////////////////////////////////////////////////////
Admin.Blocks.prototype.drawTreeIcon = function(id,category_name)
{
	if (this.alternative_mode)
	{
		if (id != this.rootTreeNodeID)
		return "<div id='"+id+"' class='gekko_tree_folder'>" +
			   "<label><input type='checkbox' name='categories[]' class='gekko_items_categoryeditor_checkbox' id='chk_cid_"+id+"' />"+
			   category_name+"</label></div>\n\n";
			   else
				   return  "<div id='"+id+"' class='gekko_tree_folder_green' style='padding-left:1.5em'>" + category_name+"</div>\n\n"
	}
	else
		return  Admin.Blocks.superclass.drawTreeIcon.call(this,id,category_name);	 
}
///////////////////////////////////////////////////////////
Admin.Blocks.prototype.reportVisibilityAssociation = function(o) //FIX THIS 
{
	var response = ajaxParse (o.responseText);
	if (response != true) alert('Cannot set the associated category')
}

///////////////////////////////////////////////////////////
Admin.Blocks.prototype.clickTree = function (htmlobject)
{
	if (this.alternative_mode) 
	{		
		var menu_id = htmlobject.node.data.id;
		var leaf_id = this.checkboxNodeIDPrefix + menu_id;
		var state = document.getElementById(leaf_id).checked;
		var id = document.getElementById('id').value;
		if (menu_id >=0)
		{
	 		var postData = "menu_id=" +menu_id + "&id=" + id + "&state=" + state;
			this.ajaxRequestPOST(this.reportVisibilityAssociation, 'setvisibility', postData);
		}
		return false;
	} else return Admin.Blocks.superclass.clickTree.call(this, htmlobject);
}
///////////////////////////////////////////////////////////
Admin.Blocks.prototype.getNodeByCID = function (id) {
for (var i = 0; i < this.categoryNodes.length; i++)
	{
		if	(this.categoryNodes[i].data.cid == id)
		{
			return this.categoryNodes[i];
		}
	}
	return null;
}

///////////////////////////////////////////////////////////

Admin.Blocks.prototype.buildTree = function( )
{
	if (!this.alternative_mode)
		return  Admin.Blocks.superclass.buildTree.call(this);

	/** Copy & Paste **/
	this.categoryTree.removeNode(this.categoryTree.getRoot(),false);
	this.categoryTree.removeChildren(this.categoryTree.getRoot());
	this.categoryTree.destroy();
	// Build Root Node
	var category_name = 'Menus';
	var node_name = this.rootTreeNodeID;	
	var myobj = { label: category_name, id: 0  , html: this.drawTreeIcon(node_name,category_name)}; 
	new_node = new YAHOO.widget.HTMLNode(myobj, this.categoryTree.getRoot(), true, false);
	this.categoryNodes[0] = new_node;		
	// Build the Rest
	for (var i = 0; i < this.category_array.length; i++) 
	{
		var category_name = this.category_array[i]['title'];
		var category_id = this.category_array[i]['id'];	
		var the_virtual_filename = '';
		if (this.category_array[i]['virtual_filename']) the_virtual_filename = this.category_array[i]['virtual_filename'];
		if (category_name == "") category_name = "(Untitled)";		
		var parent_id = this.category_array[i]['parent_id'];
		var cid = this.category_array[i]['cid'];
		var menu_id = 0;
		//var menu_id = this.category_array[i]['id'];
		if (cid == 0) menu_id = this.category_array[i]['id'];
		if (!parent_id) parent_id = 0;
		var node_name = this.treeNodeIDPrefix + category_id;
		/********** Fix this one - April 29, 2010 **********/
		var myobj = { virtual_filename: the_virtual_filename, label: category_name, cid: cid, id:category_id , html: this.drawTreeIcon(node_name,category_name)};

		
		if (cid != null)
		//if (parent_id == 0)
		{
		//		alert(cid);
			new_node = new YAHOO.widget.HTMLNode(myobj, this.categoryTree.getRoot(), true, true);
		}
		else
		{
		//	alert(this.category_array[i]['parent_id']);
			if (parent_id == 0)
				new_node = new YAHOO.widget.HTMLNode(myobj, this.getNodeByCID(this.category_array[i]['category_id']), true, true);	
			else
				new_node = new YAHOO.widget.HTMLNode(myobj, this.getNodeByDataID(this.category_array[i]['parent_id']), true, true);	
		}
		this.categoryNodes[i] = new_node;
		
	}
	this.categoryTree.subscribe("clickEvent",this.clickTree, this, true);  // scope correction with this, true - Feb  21, 2009
	this.categoryTree.draw();
	
	
}
///////////////////////////////////////////////////////////

Admin.Blocks.prototype.Start = function()
{
	gekko_app = new Admin.Blocks (app_name,'blocks'); 
	gekko_app.Run();
	gekko_editor_app = gekko_app; //workaround - Dec 12, 2011
}

///////////////////////////////////////////////////////////
$onload(Admin.Blocks.prototype.Start);
  
