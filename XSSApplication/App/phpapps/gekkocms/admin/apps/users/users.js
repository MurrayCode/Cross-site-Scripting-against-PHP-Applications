//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
var app_name =  "users";
var app_description ="Users";

// ******************************************************************************************************************************************** 
Admin.Users = function ()
{
    this.constructor.superclass.constructor.call(this, app_name ,'',  'id','username','cid','groupname');
	this.field_category_title = 'groupname';	
	this.field_item_title = 'username';	
};

///////////////////////////////////////////////////////////
$extend_class(Admin.Users, Admin.BasicMultipleCategories);
///////////////////////////////////////////////////////////
Admin.Users.prototype.formatStatus = function(elCell, oRecord, oColumn, oData) {
	switch (oData)
	{
		case 1:  class_name = 'gekko_status_active';break;
		default: class_name = 'gekko_status_inactive';break;		
	}
	elCell.innerHTML = "<div class=\"" + class_name +"\">&nbsp;</div>";
};
 
///////////////////////////////////////////////////////////

Admin.Users.prototype.formatEditLink = function(elCell, oRecord, oColumn, oData) {
	//alert (oRecord.toSource());
	var itemTitle = oData;
	var itemLink = '';
	if (oRecord.getData("cid") > 0)
	{
		itemTitle = oRecord.getData("groupname");
		if (itemTitle == '') itemTitle = '(Untitled)';
		var the_id = oRecord.getData("cid");
		var chdirLink = "javascript:gekko_app.changeDirectory("+ oRecord.getData("cid") + ")"; // Admin.BasicSimpleCategories.prototype.changeDirectory failed <<---- note  Jan 18, 2010
		var editIcon = "<img src=\"" + site_httpbase + "/images/default/trans.png\" alt=\"Edit\" title=\"Edit\" border=\"0\" id=\"home\" class=\"img_buttons16 imgsprite16_document-properties\" />";
		itemLink = 'index.php?app=' + app_name + '&action=editcategory&id=' + oRecord.getData("cid");
		var iconEditLink = '<A HREF="'+ itemLink+'">' + editIcon+ '</A></div>';
		if (itemTitle == 'Administrators') iconEditLink = '';
		elCell.innerHTML =  '<div style="float:left"><A HREF="'+chdirLink +'" id="'+the_id+'">'+ itemTitle + '</A></div>  <div style="float:right;margin-left:4em">' + iconEditLink;
	}
	else
	{
		if (itemTitle == '') itemTitle = '(Untitled)';		
		itemLink = 'index.php?app=' + app_name + '&action=edititem&id=' + oRecord.getData("id");
		elCell.innerHTML =  '<A HREF="'+ itemLink+'">'+ itemTitle + '</A>';		
	}

};

///////////////////////////////////////////////////////////
Admin.Users.prototype.formatSelectionCheckBox = function(elCell, oRecord, oColumn, oData) {
	if (oRecord.getData('id')==1 || oRecord.getData("cid") == 1)
		elCell.innerHTML = "<img src=\"" + site_httpbase + "/images/default/trans.png\" alt=\"Admin\" title=\"Admin\" border=\"0\" id=\"home\" class=\"img_buttons16 imgsprite16_user\" />";
	else
		Admin.Users.superclass.formatSelectionCheckBox.call (this,elCell, oRecord, oColumn, oData);
};

///////////////////////////////////////////////////////////

Admin.Users.prototype.buildDataColumnDefinitionAndResponseSchema = function()
{

	var status_array = [{value:0, label: "Inactive"}, {value:1, label: "Active"} ];
	this.columnDefinition = [
		{key:"check", label: this.getHeaderSelectAllCheckbox(), formatter:this.formatSelectionCheckBox}, // use the built-in checkbox formatter (shortcut)
		{key:"button", label:"ID", formatter:this.formatIcon}, // use the built-in button formatter
		{key: this.field_category_id, hidden:true, sortable:false, formatter:"number"},
		{key: this.field_id, hidden:true, sortable:false, formatter:"number"},
		{key: this.field_item_title, label: 'Title', sortable:true, formatter:this.formatEditLink},
		{key:"status", label: 'Status', sortable:true, formatter:this.formatStatus, editor:  new YAHOO.widget.DropdownCellEditor({dropdownOptions:status_array,disableBtns:false}) }, 
		{key:"date_last_logged_in", label: 'Last Logged In', sortable:true, formatter:"string"}, // use the built-in date
		{key:"date_created", label: 'Date Created', sortable:true, formatter:this.formatDate}, // use the built-in date
		{key:"date_modified", label: 'Date Modified', sortable:true, formatter:this.formatDate}, // use the built-in date
		{key:"date_expiry", label: 'Date Expiry', sortable:true, formatter:this.formatDate} // use the built-in date		
	];
	
	this.dataResponseSchema = {
		resultsList: "data",
		// Use the parse methods to populate the RecordSet with the right data types
		fields: [
			{key:this.field_category_id, parser:"number"}, 
			{key:this.field_id, parser:"number"}, 
			{key:this.field_item_title, parser:"string"}, 
			{key:this.field_category_title, parser:"string"}, 
			{key:"status", parser:"number"}, 
			{key:"date_last_logged_in", parser:"string"},
			{key:"date_created", parser:"date"},
			{key:"date_modified", parser:"date"},
			{key:"date_expiry", parser:"date"}
		]
	};
}
///////////////////////////////////////////////////////////
Admin.Users.prototype.drawTreeIcon = function(id,category_name)
{
	if (this.alternative_mode)
	{
		var s = "<input type='checkbox' name='categories[]'  class='gekko_items_categoryeditor_checkbox' id='chk_cid_"+id+"' />";
 		if (id == this.rootTreeNodeID) s = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		return "<div id='"+id+"' class='gekko_tree_folder'>" +
			   "<label>"+ s + category_name+"</label></div>\n\n"
	}
	else
		return  Admin.BasicMultipleCategories.superclass.drawTreeIcon.call(this,id,category_name);	 
}

///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
Admin.Users.prototype.Start = function()
{
	gekko_app = new Admin.Users (app_name, app_description); 
	gekko_app.Run();
}

///////////////////////////////////////////////////////////
$onload(Admin.Users.prototype.Start);
 