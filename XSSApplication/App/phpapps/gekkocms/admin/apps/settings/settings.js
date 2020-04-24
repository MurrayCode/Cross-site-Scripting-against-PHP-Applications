//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
var app_name =  "settings";
var app_description ="settings";

// ******************************************************************************************************************************************** 
Admin.Settings = function (theapp_name, theapp_description, field_id, field_category_id, field_item_title, field_category_title)
{
    this.constructor.superclass.constructor.call(this,theapp_name, theapp_description, 'id','title');
};

///////////////////////////////////////////////////////////
$extend_class(Admin.Settings, Admin.Basic);
///////////////////////////////////////////////////////////
Admin.Settings.prototype.buildDataColumnDefinitionAndResponseSchema = function()
{

	var status_array = [{value:0, label: "Inactive"}, {value:1, label: "Active"} ];
	this.columnDefinition = [
		{key:"title", label: 'Title', sortable:true, formatter:this.formatEditLink},
		{key:"status", label: 'Status', sortable:true, formatter:this.formatStatus } 
	];
	
	this.dataResponseSchema = {
		resultsList: "data",
		fields: [
			{key:'title', parser:"string"}, 
			{key:"status", parser:"number"}
		]
	};
}
///////////////////////////////////////////////////////////

Admin.Settings.prototype.deleteSQLCache = function()
{
	if (confirm('Are you sure you want to delete the SQL cache?'))
		this.ajaxRequestPOST(this.reportPOSTOperation, 'deletesqlcache',  "confirm=1");
	
}

///////////////////////////////////////////////////////////
Admin.Settings.prototype.Start = function()
{
	gekko_app = new Admin.Settings (app_name, app_description); 
	gekko_app.Run();
}

///////////////////////////////////////////////////////////
$onload(Admin.Settings.prototype.Start);
 