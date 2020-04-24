//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
var app_name =  "html";
var app_description ="content";

// ******************************************************************************************************************************************** 
Admin.HTMLPages = function (class_name)
{
    this.constructor.superclass.constructor.call(this,class_name);
};

///////////////////////////////////////////////////////////
$extend_class(Admin.HTMLPages, Admin.BasicNestedCategories);
///////////////////////////////////////////////////////////
Admin.HTMLPages.prototype.buildDataColumnDefinitionAndResponseSchema = function()
{

	var status_array = [{value:0, label: "Inactive"}, {value:1, label: "Active"} ];
	this.columnDefinition = [
		{key:"check", label: this.getHeaderSelectAllCheckbox(), formatter:this.formatSelectionCheckBox}, // use the built-in checkbox formatter (shortcut)
		{key:"button", label:"ID", sortable:true, formatter:this.formatIcon}, // use the built-in button formatter
		{key: this.field_category_id, hidden:true, sortable:false, formatter:"number"},
		{key: this.field_id, hidden:true, sortable:false, formatter:"number"},
		{key: this.field_item_title, label: 'Title', sortable:true, formatter:this.formatEditLink}, 
		{key:"virtual_filename", label: 'Shortcut', sortable:true, formatter:this.formatShortcut}, 
		{key:"status", label: 'Status', sortable:true, formatter:this.formatStatus, editor:  new YAHOO.widget.DropdownCellEditor({dropdownOptions:status_array,disableBtns:false}) }, 
		{key:"sort_order", label: 'Sort Order', sortable:true, formatter:"number", editor: new YAHOO.widget.TextboxCellEditor({validator:YAHOO.widget.DataTable.validateNumber,disableBtns:false})},
//		{key:"date_available", label: 'Date Available', sortable:true, formatter:this.formatDate, editor: new YAHOO.widget.DateCellEditor({disableBtns:false})}, // use the built-in date
		{key:"date_created", label: 'Date Created', sortable:true, formatter:this.formatDate, editor: new YAHOO.widget.DateCellEditor({disableBtns:false})}, // use the built-in date
		{key:"date_modified", label: 'Date Modified', sortable:true, formatter:this.formatDate, editor: new YAHOO.widget.DateCellEditor({disableBtns:false})} // use the built-in date		
	];
	
	this.dataResponseSchema = {
		resultsList: "data",
		// Use the parse methods to populate the RecordSet with the right data types
		fields: [
			{key:this.field_category_id, parser:"number"}, 
			{key:this.field_id, parser:"number"}, 
			{key:this.field_item_title, parser:"string"}, 
			{key:"virtual_filename", parser:"string"},
			{key:"status", parser:"number"}, 
			{key:"sort_order", parser:"number"}, 
			{key:"date_created", parser:"date"},
			{key:"date_modified", parser:"date"}
		]
		
	};
}
///////////////////////////////////////////////////////////
Admin.HTMLPages.prototype.formatSelectionCheckBox = function(elCell, oRecord, oColumn, oData) {
	if (oRecord.getData('id')==1 || oRecord.getData('virtual_filename') == 'home') 
		elCell.innerHTML = "<img src=\"" + site_httpbase + "/images/default/trans.png\" alt=\"home\" title=\"Home\" border=\"0\" id=\"home\" class=\"img_buttons16 imgsprite16_home\" />";
	else
		Admin.HTMLPages.superclass.formatSelectionCheckBox.call (this,elCell, oRecord, oColumn, oData);
};

///////////////////////////////////////////////////////////
Admin.HTMLPages.prototype.Start = function()
{
	gekko_app = new Admin.HTMLPages (app_name, app_description); 
	gekko_app.Run();
}

///////////////////////////////////////////////////////////
$onload(Admin.HTMLPages.prototype.Start);
