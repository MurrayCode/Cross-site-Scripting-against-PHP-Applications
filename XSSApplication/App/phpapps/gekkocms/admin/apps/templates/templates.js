//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
var app_name =  "templates";
var app_description ="templates";

// ******************************************************************************************************************************************** 
Admin.Templates = function (theapp_name, theapp_description, field_id, field_category_id, field_item_title, field_category_title)
{
    this.constructor.superclass.constructor.call(this,theapp_name, theapp_description, 'id','title');
};

///////////////////////////////////////////////////////////
$extend_class(Admin.Templates, Admin.BasicLinearData);
///////////////////////////////////////////////////////////
Admin.Templates.prototype.buildDataColumnDefinitionAndResponseSchema = function()
{

	var status_array = [{value:0, label: "Inactive"}, {value:1, label: "Active"} ];
	this.columnDefinition = [
		{key: this.field_id, hidden:true, sortable:false, formatter:"number"},
		{key: this.field_item_title, label: 'Template Name', sortable:true, formatter:this.formatEditLink},
		{key:"default_site", label: 'Default', sortable:true, formatter:this.formatOptionBoxDefaultTemplate},
		{key:"default_mobile_site", label: 'Mobile/PDA', sortable:true, formatter:this.formatOptionBoxMobileTemplate}
		//{key:"default_iphone_site", label: 'IPhone', sortable:true, formatter:this.formatOptionBoxIPhoneTemplate},
	];
	
	this.dataResponseSchema = {
		resultsList: "data",
		// Use the parse methods to populate the RecordSet with the right data types
		fields: [
			{key:this.field_id, parser:"number"}, 
			{key:this.field_item_title, parser:"string"}
		]
	};
}
///////////////////////////////////////////////////////////
Admin.Templates.prototype.formatOptionBoxDefaultTemplate = function(elCell, oRecord, oColumn, oData) {
	elCell.innerHTML =  '<div align="center"><input type="radio" id="default' + oRecord.getData("id") + '" name="default_template" ' +
						 ' value="' + oRecord.getData("id") + '" onclick="javascript:gekko_app.setTemplate(\'default\',this.value);" /></div>';
};
///////////////////////////////////////////////////////////
Admin.Templates.prototype.formatOptionBoxMobileTemplate = function(elCell, oRecord, oColumn, oData) {
	elCell.innerHTML =  '<div align="center"><input type="radio" id="mobile' + oRecord.getData("id") + '" name="default_mobile_template" ' +
						 ' value="' + oRecord.getData("id") + '" onclick="javascript:gekko_app.setTemplate(\'mobile\',this.value);" /></div>';
};
///////////////////////////////////////////////////////////
Admin.Templates.prototype.reportSetTemplate = function(o)
{
		// nothing
}
///////////////////////////////////////////////////////////
Admin.Templates.prototype.setTemplate = function(mode,value)
{
	var postData = 'mode='+mode+'&id='+value;
	this.ajaxRequestPOST(this.reportSetTemplate, 'settemplate', postData);
}
///////////////////////////////////////////////////////////
Admin.Templates.prototype.receiveDefaultTemplates= function(o)
{
	var tmpl = ajaxParse (o.responseText);
	var template_default = document.getElementById('default'+tmpl['default']);
	var template_mobile = document.getElementById('mobile'+tmpl['mobile']);
	template_default.checked = true;
	template_mobile.checked = true;
}
///////////////////////////////////////////////////////////
Admin.Templates.prototype.Run = function()
{
	Admin.Templates.superclass.Run.call(this);
	this.ajaxRequestGET( this.receiveDefaultTemplates,'action=getdefaulttemplates');
}
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
Admin.Templates.prototype.Start = function()
{
	gekko_app = new Admin.Templates (app_name, app_description); 
	gekko_app.Run();
}

///////////////////////////////////////////////////////////
$onload(Admin.Templates.prototype.Start);
 