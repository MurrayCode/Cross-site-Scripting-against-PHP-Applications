var global_menu_set_for_first_time;
var menu_selected_app_name;
var global_currentapp_datatype;
var reset_selected_action;
var global_field_selected_item_title;
var global_field_selected_category_title;
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
var app_name =  "menu_editor";

// ******************************************************************************************************************************************** 
Admin.MenuEditor = function ()
{
    this.constructor.superclass.constructor.call(this, app_name ,'',  'id','title','cid','title');
	this.field_selected_item_title = 'title';
	this.field_selected_category_title = 'title';
	this.disableDragDrop = true;
};

///////////////////////////////////////////////////////////
$extend_class(Admin.MenuEditor, Admin.BasicNestedCategories);
///////////////////////////////////////////////////////////


Admin.MenuEditor.prototype.Run = function ()
{
	this.gekko_admin_current_path = 'gekko_menu_current_path';
	this.gekko_admin_search_form = 'gekko_menu_search_form';
	this.gekko_admin_main_content = 'gekko_menu_main_content';
	this.gekko_admin_sidebar_content = 'gekko_menu_sidebar_content';
	YAHOO.util.Event.onDOMReady(this.initLayout);		
	
	this.currentCategory = YAHOO.util.Cookie.get(this.app_name + "_currentCategory"); 
	if (this.currentCategory == null) this.currentCategory = 0;

	this.categoryTree = new YAHOO.widget.TreeView(this.gekko_admin_sidebar_content);
}

///////////////////////////////////////////////////////////
Admin.MenuEditor.prototype.setMenuAction = function (action)
{
	if (action == 'standard_browse')
	{ 
	
		document.getElementById('gekko_menu_layout').style.display = 'block';
		this.gekko_admin_current_path = 'gekko_menu_current_path';
		this.gekko_admin_search_form = 'gekko_menu_search_form';
		this.gekko_admin_main_content = 'gekko_menu_main_content';
		this.gekko_admin_sidebar_content = 'gekko_menu_sidebar_content';
//		if (global_menu_set_for_first_time)

		if (global_currentapp_datatype != null && global_currentapp_datatype != 'basiclineardata') 
		{
			document.getElementById('gekko_menu_sidebar_content').style.display = 'block';
			this.getTreeListing();
		}else 
		{
			document.getElementById('gekko_menu_sidebar_content').style.display = 'none';
		}
		if (global_currentapp_datatype != null) this.getItemsListing(0); else 
		{
			document.getElementById('gekko_menu_layout').style.display = 'none';			
			this.dataTable = null;		
		}
	} else
	{
		document.getElementById('gekko_menu_layout').style.display = 'none';		
	}
}

///////////////////////////////////////////////////////////
Admin.MenuEditor.prototype.receiveMenuInformation = function(o)
{
	
	this.setMenuAction('');
	var container = YAHOO.util.Dom.get('menu_application_methods_container');
	if (this.selected_app_name == 'home')
	{
		str ='Click Save';
	} else
	if (this.selected_app_name == 'external_link')
	{
		var existing_customurl = '';
		if (document.getElementById('existing_customurl'))  existing_customurl = document.getElementById('existing_customurl').value;
		str = '<LABEL>Please enter the URL: <INPUT NAME="customurl" type="text" class="gekko_editor_input required" value="'+existing_customurl+'"></LABEL><BR />';
	} else
	{
		
		var existing_menuaction = document.getElementById('existing_menuaction').value;
		var response = ajaxParse(o.responseText);
		
		var data_type = response['data_type'];
		var public_methods = response['public_methods'];	
		var the_app_name = response['app_name'];
		global_currentapp_datatype = data_type;
		if (response['field_category_title']) this.field_selected_category_title = response['field_category_title']; else this.field_selected_category_title = 'title';
		if (response['field_item_title']) this.field_selected_item_title = response['field_item_title']; else this.field_selected_item_title = 'title';
	
		global_field_selected_item_title = this.field_selected_item_title;
		global_field_selected_category_title = this.field_selected_category_title;	
			
		menu_selected_app_name = the_app_name;
		var str = '';
		for (var i =0; i < public_methods.length;i++)
		{
			var required_str = '';
			var action = public_methods[i]['action'];
			
			var desc = public_methods[i]['description'];
			if (i == 0) required_str = " class=\"validate-one-required\" ";
			if (existing_menuaction == action && global_menu_set_for_first_time) 
				var checked_str = ' checked'; else checked_str = '';
 			str = str + '<label id="labelradioaction'+ i +'" for="radioaction'+ i +'"><input name="menuaction" type="radio" id="radioaction'+ i +'" value="' + action + '" onclick="javascript:gekko_app.setMenuAction(this.value)"'+ checked_str+required_str +' />' + desc + '</label><br />' + "\n";
		}

	}
	if (this.selected_app_name != 'home') container.innerHTML = "<h3>Step 3. Please select an action</h3>" + str ; else container.innerHTML = '';
	var existing_application = document.getElementById('existing_application').value;	
//	if (!global_menu_set_for_first_time) document.getElementById('existing_application').value = '';
	if (existing_menuaction != "" && existing_application != 'external_link' && existing_application != 'home') 
	{
		if (global_menu_set_for_first_time) gekko_app.setMenuAction(existing_menuaction);
		var existing_menuitem = document.getElementById('existing_menuitem').value;	
		var existing_menu_parent_category = document.getElementById('existing_menu_parent_category');		
//		alert(existing_menu_parent_category.toSource());
		if (existing_menu_parent_category)
		{
			if (existing_menu_parent_category.value != "")
			{
				this.currentCategory = existing_menu_parent_category.value;
				// Dec 6, 2011 - 
			 	if (this.selected_app_name == 'home' && this.selected_app_name == 'external_link' )
					gekko_app.getItemsListing(existing_menu_parent_category);
				
			}
		}
		
	}
}
///////////////////////////////////////////////////////////

Admin.MenuEditor.prototype.notifyItemSelectionChanged = function()
{
	document.getElementById('user_changed_item_or_category').value = 1;
}

///////////////////////////////////////////////////////////
Admin.MenuEditor.prototype.getMenuInformation = function(selected_app_name,first_time)
{
//	container.innerHTML = 'test';	
	this.selected_app_name = selected_app_name;
	menu_selected_app_name = selected_app_name;
	global_menu_set_for_first_time = first_time;
	var callback = {success:this.receiveMenuInformation,failure:this.handleFailure,scope:this}; 
	var sUrl = site_httpbase + "/admin/index.php?app=" + selected_app_name + "&ajax=1&action=getmenuinfo"
	var request = YAHOO.util.Connect.asyncRequest('GET', sUrl, callback);
}

///////////////////////////////////////////////////////////
Admin.MenuEditor.prototype.formatRadioBox = function(elCell, oRecord, oColumn, oData) {
	var theID = '';
	var theValue = '';
 	if (oRecord.getData("cid") > 0)
	{
		theID = 'cat'  + oRecord.getData("cid");
		theValue =  'c'  + oRecord.getData("cid");
	}
	else
	{
		theID = 'item' + oRecord.getData("id");
		theValue =  'i'  + oRecord.getData("id");
	}
	var olditem = document.getElementById('existing_menuitem').value; 
	var user_changed_item_or_category = document.getElementById('user_changed_item_or_category').value;
	var existing_application = document.getElementById('existing_application').value;	
	if ((olditem == theValue) && (user_changed_item_or_category==0) && (existing_application == menu_selected_app_name)) var checked_str = ' checked'; else checked_str = '';

	elCell.innerHTML =  '<input type="radio" name="menuitem" id="' +theID + '"  value="' + theValue + '" onclick="javascript:gekko_app.notifyItemSelectionChanged();"'+ checked_str +' />';
//	YAHOO.util.Event.addListener(document.getElementById(theID), "click", this.toggleCheck); 	
};
///////////////////////////////////////////////////////////

Admin.MenuEditor.prototype.buildTree = function( )
{
	this.categoryTree.removeNode(this.categoryTree.getRoot(),false);
	this.categoryTree.removeChildren(this.categoryTree.getRoot());
	this.categoryTree.destroy();
	// Build Root Node
	var category_name = this.selected_app_name;
	var node_name = this.rootTreeNodeID;	
	var myobj = { label: category_name, id: 0  , html: "<div id='"+node_name+"' class='gekko_tree_folder'  >"+category_name+"</div>\n\n"}; 
	new_node = new YAHOO.widget.HTMLNode(myobj, this.categoryTree.getRoot(), true, false);
	this.categoryNodes[0] = new_node;		
	// Build the Rest
	for (var i = 0; i < this.category_array.length; i++) 
	{
		var category_name = this.category_array[i][global_field_selected_category_title];
		var category_id = this.category_array[i][this.field_category_id];
		var parent_id = this.category_array[i]['parent_id'];
		var node_name = this.treeNodeIDPrefix + category_id;
		var myobj = { label: category_name, id:category_id , html: "<div id='"+node_name+"' class='gekko_tree_folder'  >"+category_name+"</div>\n\n"}; 
		if (parent_id == 0)
		{
			new_node = new YAHOO.widget.HTMLNode(myobj, this.categoryTree.getRoot(), true, true);
		}
		else
		{
			new_node = new YAHOO.widget.HTMLNode(myobj, this.getNodeByDataID(this.category_array[i]['parent_id']), true, true);
			new_node.collapse();
		}
	//	this.dragArray[i] = new Admin.DragDrop(node_name);
		this.categoryNodes[i] = new_node;
		
	}

	this.categoryTree.subscribe("clickEvent",this.clickTree, this, true);  // scope correction with this, true - Feb  21, 2009
	this.categoryTree.draw();
};
///////////////////////////////////////////////////////////

Admin.MenuEditor.prototype.getItemsListingURL = function()
{
	var the_appname = this.selected_app_name;
	var the_action = '';
	var action = '';
	
	if (the_appname == '') this.selected_app_name = menu_selected_app_name; // bugfix hack
	
	switch (global_currentapp_datatype)
	{
		case 'basiclineardata': the_action = 'getallitems';break;
		case 'basicsimplecategory': 
		case 'basicnestedcategory':
		case 'basicmultiplecategory':the_action = 'getitemsbycategory';break;
	}
	if (the_action)
		action = site_httpbase + "/admin/index.php?app=" + the_appname + "&ajax=1&" + "action="+the_action +"&id=" + this.currentCategory;
	return action;
}
///////////////////////////////////////////////////////////
Admin.MenuEditor.prototype.buildDataSourceURL = function()
{

	//action = site_httpbase + "/admin/index.php?app=" + this.selected_app_name + "&ajax=1&" + "action=getitemsbycategory&id=" + this.currentCategory;
	return this.getItemsListingURL();
}

///////////////////////////////////////////////////////////
Admin.MenuEditor.prototype.formatEditLink = function(elCell, oRecord, oColumn, oData) {
	var itemTitle = oData;
	var itemLink = '';
	if (oRecord.getData("cid") > 0)
	{
		itemTitle = oRecord.getData(global_field_selected_category_title);
		
		if (itemTitle == '') itemTitle = '(Untitled)';
		var the_id = oRecord.getData("cid");
		var chdirLink = "javascript:gekko_app.changeDirectory("+ oRecord.getData("cid") + ")"; // Admin.BasicSimpleCategories.prototype.changeDirectory failed <<---- note  Jan 18, 2010
		itemLink = 'index.php?app=' + app_name + '&action=editcategory&id=' + oRecord.getData("cid");
 		
		elCell.innerHTML =  '<div style="float:left"><A HREF="'+chdirLink +'" id="'+the_id+'">'+ itemTitle + '</A></div>  <div style="float:right;margin-left:4em"><A HREF="'+ itemLink+'"></A></div>';
	}
	else
	{
		itemTitle = oRecord.getData(global_field_selected_item_title);
		elCell.innerHTML =   itemTitle ;		
	}

};
///////////////////////////////////////////////////////////
Admin.MenuEditor.prototype.buildDataColumnDefinitionAndResponseSchema = function()
{

	var status_array = [{value:0, label: "Inactive"}, {value:1, label: "Active"} ];
	this.columnDefinition = [
		{key:"check", label: 'Choose', formatter:this.formatRadioBox},
		{key:"button", label:"ID", formatter:this.formatIcon},
		{key: this.field_category_id, hidden:true, sortable:false, formatter:"number"},
		{key: this.field_id, hidden:true, sortable:false, formatter:"number"},
		{key: "title", label: 'Title', sortable:true, formatter:this.formatEditLink},
		{key:"status", label: 'Status', sortable:true, formatter:this.formatStatus } 
	];
	
	this.dataResponseSchema = {
		resultsList: "data",
		fields: [
			{key:this.field_category_id, parser:"number"}, 
			{key:this.field_id, parser:"number"}, 
			{key:this.field_selected_item_title, parser:"string"}, 
			{key:this.field_selected_category_title, parser:"string"}, 			
			{key:"status", parser:"number"}
		]
	};
}
///////////////////////////////////////////////////////////
Admin.MenuEditor.prototype.ajaxRequestGET = function( thefunction,  therequest) {
	var the_appname = (this.selected_app_name == "") ? menu_selected_app_name : this.selected_app_name;
	if (the_appname != "undefined")
	{
		var callback = {success:thefunction,failure:this.handleFailure,scope:this}; 
		var sUrl = site_httpbase + "/admin/index.php?app=" + the_appname + "&ajax=1&" + therequest;
		var request = YAHOO.util.Connect.asyncRequest('GET', sUrl, callback);
	}
};
///////////////////////////////////////////////////////////
Admin.MenuEditor.prototype.Start = function()
{
	gekko_app = new Admin.MenuEditor (); 
	gekko_app.Run();
	global_currentapp_datatype = '';
	var selected_app_radio_button = getRadioCheckedValue('application');
	var existing_menuaction = document.getElementById('existing_menuaction').value;
	if (selected_app_radio_button != "") gekko_app.getMenuInformation(selected_app_radio_button,true);
}

///////////////////////////////////////////////////////////
$onload(Admin.MenuEditor.prototype.Start);
