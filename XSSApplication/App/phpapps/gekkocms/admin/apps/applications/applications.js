//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
var app_name =  "applications";
var app_description ="applications";

// ******************************************************************************************************************************************** 
Admin.Applications = function (theapp_name, theapp_description, field_id, field_category_id, field_item_title, field_category_title)
{
    this.constructor.superclass.constructor.call(this,theapp_name, theapp_description, 'id','title');
};

///////////////////////////////////////////////////////////
$extend_class(Admin.Applications, Admin.BasicLinearData);
///////////////////////////////////////////////////////////
Admin.Applications.prototype.buildDataColumnDefinitionAndResponseSchema = function()
{
	var status_array = [{value:0, label: "Inactive"}, {value:1, label: "Active"} ];
	this.columnDefinition = [
		{key:"check", label: this.getHeaderSelectAllCheckbox(), formatter:this.formatSelectionCheckBox}, // use the built-in checkbox formatter (shortcut)
		{key:"button", label:"ID", formatter:this.formatIcon}, // use the built-in button formatter
		{key: this.field_category_id, hidden:true, sortable:false, formatter:"number"},
		{key: this.field_id, hidden:true, sortable:false, formatter:"number"},
		{key: this.field_item_title, label: 'Block Name', sortable:true, formatter:this.formatGotoApplication}, 
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
Admin.Applications.prototype.formatGotoApplication = function(elCell, oRecord, oColumn, oData) {
	//alert (oRecord.toSource());
	var itemTitle = oData;
	var itemLink = '';
	itemLink = 'index.php?app=' + oRecord.getData("title");
	elCell.innerHTML =  '<A HREF="'+ itemLink+'">'+ itemTitle + '</A>';		
};

///////////////////////////////////////////////////////////
Admin.Applications.prototype.enableUninstallButton = function() {
	document.getElementById('button_uninstall').style.display = 'inline';
};
///////////////////////////////////////////////////////////
Admin.Applications.prototype.uninstallSelectedItem = function() {
	if (confirm('Are you sure you want to uninstall the selected item?'))
	{
		var selectedValue = getRadioCheckedValue('item_tobe_uninstalled');
	}
};
///////////////////////////////////////////////////////////
Admin.Applications.prototype.Start = function()
{
	gekko_app = new Admin.Applications (app_name,'Applications'); 
	gekko_app.Run();
}
///////////////////////////////////////////////////////////
$onload(Admin.Applications.prototype.Start);
  
