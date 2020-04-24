//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
var app_name =  "filters";
var app_description ="filters";

// ******************************************************************************************************************************************** 
Admin.Filters = function (theapp_name, theapp_description, field_id, field_category_id, field_item_title, field_category_title)
{
	if (document.getElementById('apps_select_all')) this.alternative_mode = true; else this.alternative_mode = false;	
    this.constructor.superclass.constructor.call(this,theapp_name, theapp_description, 'id','title');
};

///////////////////////////////////////////////////////////
$extend_class(Admin.Filters, Admin.BasicLinearData);
///////////////////////////////////////////////////////////
Admin.Filters.prototype.buildDataColumnDefinitionAndResponseSchema = function()
{

	var status_array = [{value:0, label: "Inactive"}, {value:1, label: "Active"} ];
	this.columnDefinition = [
		{key:"check", label: this.getHeaderSelectAllCheckbox(), formatter:this.formatSelectionCheckBox}, // use the built-in checkbox formatter (shortcut)
		{key:"button", label:"ID", formatter:this.formatIcon}, // use the built-in button formatter
		{key: this.field_category_id, hidden:true, sortable:false, formatter:"number"},
		{key: this.field_id, hidden:true, sortable:false, formatter:"number"},
		{key: this.field_item_title, label: 'Filter Name', sortable:true, formatter:this.formatEditLink}, 
		{key:"status", label: 'Status', sortable:true, formatter:this.formatStatus, editor:  new YAHOO.widget.DropdownCellEditor({dropdownOptions:status_array,disableBtns:false}) }, 
		{key:"sort_order", label: 'Sort Order', sortable:true, formatter:"number", editor: new YAHOO.widget.TextboxCellEditor({validator:YAHOO.widget.DataTable.validateNumber,disableBtns:false})},
		{key:"date_modified", label: 'Date Modified', sortable:true, formatter:this.formatDate} // use the built-in date		
	];
	
	this.dataResponseSchema = {
		resultsList: "data",
		// Use the parse methods to populate the RecordSet with the right data types
		fields: [
			{key:this.field_id, parser:"number"}, 
			{key:this.field_item_title, parser:"string"}, 
			{key:"status", parser:"number"},
			{key:"sort_order", parser:"number"}, 
			{key:"date_modified", parser:"date"}
		]
	};
}
///////////////////////////////////////////////////////////
 Admin.Filters.prototype.setSelectionEventListener = function ()
{
   var display_in_apps = document.forms.filter_item_editor.display_in_apps_workaround.value;
   var display_in_blocks = document.forms.filter_item_editor.display_in_blocks_workaround.value;
	
   YAHOO.util.Event.addListener(document.getElementById('apps_select_all'), "click", this.hideAppSelections, this, true); 	   
   YAHOO.util.Event.addListener(document.getElementById('apps_select_some'), "click", this.showAppSelections, this, true); 			
   YAHOO.util.Event.addListener(document.getElementById('blocks_select_all'), "click", this.hideBlockSelections, this, true); 	   
   YAHOO.util.Event.addListener(document.getElementById('blocks_select_some'), "click", this.showBlockSelections, this, true); 			
   if (display_in_apps == 1) this.hideAppSelections();    
   if (display_in_apps == 1) this.hideBlockSelections(); 
}
///////////////////////////////////////////////////////////
Admin.Filters.prototype.hideAppSelections = function ()
{
	var div = document.getElementById('app_selections');
	div.style.display = 'none';
}
///////////////////////////////////////////////////////////
Admin.Filters.prototype.showAppSelections = function (str)
{
	var div = document.getElementById('app_selections');
	div.style.display = 'block';
}
///////////////////////////////////////////////////////////
Admin.Filters.prototype.hideBlockSelections = function ()
{
	var div = document.getElementById('block_selections');
	div.style.display = 'none';
}
///////////////////////////////////////////////////////////
Admin.Filters.prototype.showBlockSelections = function (str)
{
	var div = document.getElementById('block_selections');
	div.style.display = 'block';
}


///////////////////////////////////////////////////////////
Admin.Filters.prototype.Run = function ()
{
	if (this.alternative_mode)
	{
		this.setSelectionEventListener();
	} else Admin.Filters.superclass.Run.call(this);
}

///////////////////////////////////////////////////////////
Admin.Filters.prototype.Start = function()
{
	gekko_app = new Admin.Filters (app_name, app_description); 
	gekko_app.Run();
	gekko_editor_app = gekko_app; //workaround - Dec 12, 2011
}

///////////////////////////////////////////////////////////
$onload(Admin.Filters.prototype.Start);