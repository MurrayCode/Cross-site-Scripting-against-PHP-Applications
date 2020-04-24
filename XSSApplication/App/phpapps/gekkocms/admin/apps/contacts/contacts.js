//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
var app_name =  "contacts";
var app_description ="contacts";

// ******************************************************************************************************************************************** 
Admin.Contacts = function (theapp_name, theapp_description, field_id, field_category_id, field_item_title, field_category_title)
{
    this.constructor.superclass.constructor.call(this,theapp_name, theapp_description, 'id','title');
};

///////////////////////////////////////////////////////////
$extend_class(Admin.Contacts, Admin.BasicSimpleCategories);
///////////////////////////////////////////////////////////
Admin.Contacts.prototype.buildDataColumnDefinitionAndResponseSchema = function()
{
	var status_array = [{value:0, label: "Inactive"}, {value:1, label: "Active"} ];
	this.columnDefinition = [
		{key:"check", label: this.getHeaderSelectAllCheckbox(), formatter:this.formatSelectionCheckBox}, // use the built-in checkbox formatter (shortcut)
		{key:"button", label:"ID", formatter:this.formatIcon}, // use the built-in button formatter
		{key: this.field_category_id, hidden:true, sortable:false, formatter:"number"},
		{key: this.field_id, hidden:true, sortable:false, formatter:"number"},
		{key: this.field_item_title, label: 'Name', sortable:true, formatter:this.formatEditLink}, 
		{key:"status", label: 'Status', sortable:true, formatter:this.formatStatus, editor:  new YAHOO.widget.DropdownCellEditor({dropdownOptions:status_array,disableBtns:false}) } 
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
			{key:"virtual_filename", parser:"string"}				
		]
	};
}
///////////////////////////////////////////////////////////
Admin.Contacts.prototype.Start = function()
{
	gekko_app = new Admin.Contacts (app_name, app_description); 
	gekko_app.Run();
}

///////////////////////////////////////////////////////////
$onload(Admin.Contacts.prototype.Start);
