//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
var app_name =  "menus";
var app_description ="menu";

// ******************************************************************************************************************************************** 
Admin.MenuDragDrop = function (a,b)
{
   this.constructor.superclass.constructor.call(this,a,b);
};

$extend_class(Admin.MenuDragDrop, Admin.DragDrop);

Admin.MenuDragDrop.prototype.startDrag = function(x, y)
{   
    var clickEl = this.getEl();
	if (clickEl.className == 'menu')
	{
		var dragEl = this.getDragEl();
		dragEl.innerHTML = "<p class='dragmenu'>"+ clickEl.innerHTML+ "</p>";
		dragEl.className = "dragdrop"; //clickEl.className;
		dragEl.style.color = clickEl.style.color;
		dragEl.style.border = "1px solid yellow";
	}
};


// ******************************************************************************************************************************************** 
Admin.Menus = function (theapp_name, theapp_description, field_id, field_item_title, field_category_id, field_category_title)
{
   this.constructor.superclass.constructor.call(this,theapp_name, theapp_description, field_id, field_item_title, field_category_id, field_category_title);
   this.selectionCheckBoxes = 'chkselections[]';
   this.dragArray = new Array();
   
};
///////////////////////////////////////////////////////////
$extend_class(Admin.Menus, Admin.BasicSimpleCategories);

///////////////////////////////////////////////////////////
Admin.Menus.prototype.assignButtons = function ()
{
   YAHOO.util.Event.addListener(document.getElementById('button_copy'), "click", this.editCopy, this, true); 	   
   YAHOO.util.Event.addListener(document.getElementById('button_cut'), "click", this.editCut, this, true); 			
   YAHOO.util.Event.addListener(document.getElementById('button_paste'), "click", this.editPaste, this, true); 	
   YAHOO.util.Event.addListener(document.getElementById('button_delete'), "click", this.editDelete, this, true); 
}

Admin.Menus.prototype.setMenuStatus = function(id, status)
{
	var postData = 'id='+ id + '&field=status&value=' + status;
	this.ajaxRequestPOST(this.reportPOSTOperation, 'updatefield', postData);	
}

///////////////////////////////////////////////////////////
Admin.Menus.prototype.reportPOSTOperation = function(o)
{
	var response = ajaxParse (o.responseText);
	this.getTreeListing();
	this.getItemsListing(this.currentCategory);
}
///////////////////////////////////////////////////////////
Admin.Menus.prototype.receiveItemsArray = function(o)
{
	this.content_array = ajaxParse (o.responseText);
	this.buildApplication();
}

///////////////////////////////////////////////////////////
Admin.Menus.prototype.buildApplication = function( )
{
	if (this.currentCategory == 0)
	{
		Admin.Menus.superclass.buildApplication.call (this);
	}
	else
	{
		if (this.dragArray.length != 0)
		{ // prevent memory leak?
			var existing_drag_count = this.dragArray[0].length;
			if (existing_drag_count != 0)
			{
				for (var x=0;x < 3;x++)
				for (var y=0;i < existing_drag_count;y++)
				{
					var obj_self = this.dragArray[x][y];
					if (obj_self) obj_self.destroy();
					obj_self = null;
				}
				this.dragArray = null;
			}
		}
		this.dragArray = new Array();
		for (var i = 0; i < 4; i++) this.dragArray[i] = new Array();
		// end memory leak prevention because each time a tree is clicked the dragdrop is created over and over again... does this work? I don't know my Firefox is acting crazy.
		var admin_main_content = document.getElementById('gekko_admin_main_content');
		admin_main_content.innerHTML = this.content_array;
		var menutops = YAHOO.util.Dom.getElementsByClassName('menutop', 'div');
		var menubottoms = YAHOO.util.Dom.getElementsByClassName('menubottom', 'div');
		var menunexts = YAHOO.util.Dom.getElementsByClassName('menunext', 'div');
		var menus = YAHOO.util.Dom.getElementsByClassName('menu', 'div');
		for (var i = 0;i < menutops.length; i++)
		{
			this.dragArray[0][i] = new Admin.MenuDragDrop (menus[i]);
			this.dragArray[1][i] = new Admin.MenuDragDrop (menutops[i]);		
			this.dragArray[2][i] = new Admin.MenuDragDrop (menubottoms[i]);
			this.dragArray[3][i] = new Admin.MenuDragDrop (menunexts[i]);	
		}
	}
}
///////////////////////////////////////////////////////////
Admin.Menus.prototype.getItemsListingURL = function()
{
	var action= "action=getitemsbycategory&id=" +this.currentCategory;
	return action;
}
///////////////////////////////////////////////////////////
Admin.Menus.prototype.getItemsListing = function(directory_id) {
	this.ajaxRequestGET( this.receiveItemsArray,this.getItemsListingURL());		
}
///////////////////////////////////////////////////////////
Admin.Menus.prototype.buildDataColumnDefinitionAndResponseSchema = function()
{

	var status_array = [{value:0, label: "Inactive"}, {value:1, label: "Active"} ];
	this.columnDefinition = [
		{key:"check", label: this.getHeaderSelectAllCheckbox(), formatter:this.formatSelectionCheckBox}, // use the built-in checkbox formatter (shortcut)
		{key:"button", label:"ID", formatter:this.formatIcon}, // use the built-in button formatter
		{key: this.field_category_id, hidden:true, sortable:false, formatter:"number"},
		{key: this.field_id, hidden:true, sortable:false, formatter:"number"},
		{key: this.field_item_title, label: 'Title', sortable:true, formatter:this.formatEditLink} 
	];
	
	this.dataResponseSchema = {
		resultsList: "data",
		// Use the parse methods to populate the RecordSet with the right data types
		fields: [
			{key:this.field_category_id, parser:"number"}, 
			{key:this.field_id, parser:"number"}, 
			{key:this.field_item_title, parser:"string"}, 
			{key:"status", parser:"number"}, 
			{key:"sort_order", parser:"number"}
		]
	};
}
///////////////////////////////////////////////////////////
Admin.Menus.prototype.Start = function()
{
	gekko_app = new Admin.Menus (app_name, app_description); 
	gekko_app.Run();
}

///////////////////////////////////////////////////////////
$onload(Admin.Menus.prototype.Start);
