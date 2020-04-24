//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
var app_name =  "bbgkmediamanager";
var app_description ="bbgkmediamanager";
var fileList;
// ******************************************************************************************************************************************** 
Admin.bbgkmediamanager = function (theapp_name, theapp_description, field_id, field_category_id, field_item_title, field_category_title)
{
    this.constructor.superclass.constructor.call(this,theapp_name, theapp_description, 'id','title');
	//this.setMenuSelectionEventListener();
    if (this.currentCategory == 0) this.currentCategory = 1;
};

///////////////////////////////////////////////////////////

$extend_class(Admin.bbgkmediamanager, Admin.BasicNestedCategories);
///////////////////////////////////////////////////////////
Admin.bbgkmediamanager.prototype.assignButtons = function ()
{
	this.initDialogs();
	// YAHOO.util.Event.addListener(document.getElementById('button_new_folder'), "click", this.showNewFolderDialog, this, true); 
        // YAHOO.util.Event.addListener(document.getElementById('button_delete'), "click", this.editDelete, this, true); 
	YAHOO.util.Event.addListener(document.getElementById('button_file_upload'), "click", this.showFileUploadDialog, this, true);    
	// YAHOO.util.Event.addListener(document.getElementById(this.gekko_admin_searchform), "submit", this.searchItems, this, true); 
        Admin.bbgkmediamanager.superclass.assignButtons.call (this);
        
}
///////////////////////////////////////////////////////////
Admin.bbgkmediamanager.prototype.ajaxUploadDone = function(o)
{
//	this.ajaxRequestPOSTForm(this.reportAjaxSaveOperation, 'ajaxsaveitem', 'frm_property_item_editor');
	document.getElementById('upload_warning').style.display = 'none';	
	this.getItemsListing(this.currentCategory);
}

Admin.bbgkmediamanager.prototype.upload = function() {
	var currentDir = this.getPathByCategoryID(this.currentCategory);
	var startpath = document.getElementById('fileuploadpath');
	startpath.value = currentDir;
	document.getElementById('upload_warning').style.display = 'block';	
	this.ajaxRequestPOSTForm(this.ajaxUploadDone, 'upload', 'gekko_upload_form');
	this.fileUploadDialog.hide(); 
}
 

///////////////////////////////////////////////////////////
Admin.bbgkmediamanager.prototype.initDialogs = function ()
{
// Instantiate the Dialog
	YAHOO.util.Dom.removeClass("new_folder_dialog", "yui-pe-content");
	YAHOO.util.Dom.removeClass("file_upload_dialog", "yui-pe-content");

	this.newFolderDialog = new YAHOO.widget.Dialog("new_folder_dialog", 
			{ width : "300px", fixedcenter : true, visible : false, constraintoviewport : true,
			  buttons : [ { text:"Submit", handler: {fn:this.createNewFolder, obj: this, scope: this}, isDefault:true},
						  { text:"Cancel", handler: {fn:this.cancelNewFolderDialog,obj:this,scope:this}} ]
			 } );
	this.newFolderDialog.callback ={success:this.getTreeListing,failure:this.handleFailure,scope:this};
	this.newFolderDialog.render();
 
	this.fileUploadDialog = new YAHOO.widget.Dialog("file_upload_dialog", 
			{ width : "300px", fixedcenter : true, visible : false, constraintoviewport : true,
			  buttons : [ { text:"Submit", handler: {fn:this.upload, obj: this, scope: this}, isDefault:true},
						  { text:"Cancel", handler: {fn:this.cancelFileUploadDialog,obj:this,scope:this}} ]
			 } );
	this.fileUploadDialog.callback ={success:this.buildTree,failure:this.handleFailure,scope:this};
	this.fileUploadDialog.render();
}
///////////////////////////////////////////////////////////

Admin.bbgkmediamanager.prototype.initLayout = function ()
{
	
	if (document.getElementById('gekko_admin_sidebar') && document.getElementById('gekko_admin_main')) 
	// prana - March 14 - TODO - no hardcoding please
	{
		this.layout = new YAHOO.widget.Layout(
		{
			units: [
				//{ position: 'top', height: 50, resize: true, body: 'header', gutter: '0', scroll: null, zIndex: 2},
				{ position: 'left',  width: 250, resize: true, body: 'gekko_admin_sidebar', gutter: '5px', scroll:true },
				{ position: 'center', width: 500, body: 'gekko_admin_main' , gutter:'0', resize:true, collapse: false, close: false, scroll:true},
				{ position: 'bottom', height: 100, body: 'footer' , gutter:'5px',resize:true}
	
			]
		});
		/////////
	//		this.layout.getUnitByPosition('top').setStyle('overflow','visible'); 
		this.layout.on("resize", gekko_app.afterLayoutRender);
		this.layout.render();
		
	
	}
}

///////////////////////////////////////////////////////////
Admin.bbgkmediamanager.prototype.drawTreeIcon = function(id,category_name)
{
	var tooltip = "";
	var cid = id.replace(this.treeNodeIDPrefix,'');
	return "<div id='"+id+"' class='gekko_tree_folder'  >"+category_name+"</div>\n\n";	
}
///////////////////////////////////////////////////////////


Admin.bbgkmediamanager.prototype.showNewFolderDialog = function()
{
	if (this.currentCategory == 0) this.changeDirectory(1);
	this.newFolderDialog.show();
}
///////////////////////////////////////////////////////////

Admin.bbgkmediamanager.prototype.showFileUploadDialog = function()
{
	if (this.currentCategory == 0)   this.changeDirectory(1);
 	this.fileUploadDialog.show();	
}
///////////////////////////////////////////////////////////
Admin.bbgkmediamanager.prototype.createNewFolder = function()
{
	var currentDir = this.getPathByCategoryID(this.currentCategory);
    var startpath = document.getElementById('newfolderstartpath');
	startpath.value = currentDir;
	this.newFolderDialog.submit();
 }
///////////////////////////////////////////////////////////

Admin.bbgkmediamanager.prototype.cancelNewFolderDialog = function(o)
{
	this.newFolderDialog.hide();
}
Admin.bbgkmediamanager.prototype.cancelFileUploadDialog = function(o)
{
	this.fileUploadDialog.hide();
}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.formatSelectionCheckBox = function(elCell, oRecord, oColumn, oData) {
	var theID = '';
	var theValue = '';
	var app_name = oColumn.app_name;
        
 	if (oRecord.getData("cid") > 0)
	{
		theID = 'cat'  + oRecord.getData("cid");
		theValue =  'c'  + oRecord.getData("cid");
	}
	else
	{
		theID = 'item' + oRecord.getData("full_path");
		theValue =  'i'  + oRecord.getData("full_path");
	}
        elCell.innerHTML =  '<input type="checkbox" name="'+app_name+'chkselections[]" id="' +theID + '"  value="' + theValue + '" onclick="javascript:gekko_app.toggleCheck(\''+theID+'\');" />';        
//	YAHOO.util.Event.addListener(document.getElementById(theID), "click", gekko_app.toggleCheck);
};
///////////////////////////////////////////////////////////

Admin.bbgkmediamanager.prototype.buildDataColumnDefinitionAndResponseSchema = function()
{

	var status_array = [{value:0, label: "Inactive"}, {value:1, label: "Active"} ];
	this.columnDefinition = [
		{key:"check", label: this.getHeaderSelectAllCheckbox(), formatter:this.formatSelectionCheckBox}, // use the built-in checkbox formatter (shortcut)
		//{key:"button", label:"ID", formatter:this.formatIcon}, // use the built-in button formatter
		//{key: this.field_category_id, hidden:true, sortable:false, formatter:"number"},
		{key: "id", hidden:true, sortable:false, formatter:"string"},
		{key: this.field_item_title, label: 'File Name', sortable:true, formatter:this.formatEditLink}, 
		{key: "size", label: 'Size (Kb)', sortable:true, formatter:"string"}, 
		{key: "full_path", label: 'Full Path', sortable:true, formatter:"string"},
		//{key:"status", label: 'Status', sortable:true, formatter:this.formatStatus, editor:  new YAHOO.widget.DropdownCellEditor({dropdownOptions:status_array,disableBtns:false}) }, 
		{key:"date_modified", label: 'Date Modified', sortable:true, formatter:this.formatDate} // use the built-in date		
	];
	
	this.dataResponseSchema = {
		resultsList: "data",
		// Use the parse methods to populate the RecordSet with the right data types
		fields: [
			//{key:this.field_category_id, parser:"number"}, 
			{key:"id", parser:"number"}, 
			{key:this.field_item_title, parser:"string"}, 
			{key:"thumbnail",parser:"string"},			
			{key:"full_path",parser:"string"},
			{key:"size", parser:"string"},
			{key:"date_modified", parser:"date"}
		]
	};
}
///////////////////////////////////////////////////////////

Admin.bbgkmediamanager.prototype.getFileExtension = function (fname)
{
  var pos = fname.lastIndexOf(".");
  var strlen = fname.length;
  if (pos != -1 && strlen != pos + 1) {
    var ext = fname.split(".");
    var len = ext.length;
    var extension = ext[len - 1].toLowerCase();
  } else {
    extension = "No extension found";
  }
  return extension;
}
///////////////////////////////////////////////////////////

Admin.bbgkmediamanager.prototype.isDirectory = function (fname)
{
  var pos = fname.lastIndexOf("/");
  var strlen = fname.length;
  var is_dir = false;
  if (pos != -1 && strlen == pos + 1)
	  is_dir = true;
  return is_dir;
}

///////////////////////////////////////////////////////////
Admin.bbgkmediamanager.prototype.buildDataSourceURL = function()
{
	var searchbox = document.getElementById('searchbox');
	var searchString = '';
	var action = '';
	
	if (searchbox) searchString = document.getElementById('searchbox').value;
	if (searchString != 'search...' && searchString != '')
	{
		action = site_httpbase + "/admin/index.php?app=" + this.app_name + "&ajax=1&" + "action=search&keyword=" + searchString;
		this.displaySearchResultInPathBar(searchString); // fix this 10 please
	}
	else
	{
		if (this.currentCategory == 0) this.currentCategory = 1;
		action = site_httpbase + "/admin/index.php?app=" + this.app_name + "&ajax=1&" + "action=getitemsbycategory&id=" + this.currentCategory;
		this.displayPath (this.currentCategory);

	}
	return action;
}
///////////////////////////////////////////////////////////
Admin.bbgkmediamanager.prototype.formatEditLink = function(elCell, oRecord, oColumn, oData) {
	//alert (oRecord.toSource());
	var itemTitle = oData;
	var itemLink = '';
	var imgIcon;
	var ext = Admin.bbgkmediamanager.prototype.getFileExtension(itemTitle);

	if (!Admin.bbgkmediamanager.prototype.isDirectory(oRecord.getData("full_path")))
	{
		switch (ext)
		{
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'gif':imgIcon =  oRecord.getData('thumbnail'); break;
			case 'gz':
			case 'dmg':
			case 'zip':imgIcon =  "/images/icons/file_zip.png";break;
			case 'pdf':imgIcon =  "/images/icons/file_pdf.png";break;
			case 'exe':imgIcon = "/images/icons/file_exe.png";break;
			case 'mpg':
			case 'mpeg':
			case 'avi':
			case 'wmv':
			case 'mov':imgIcon =  "/images/icons/file_exe.png";break;
			default:imgIcon =  "/images/icons/file.png";
		}
	} else imgIcon =  "/images/icons/folder.png";
	if (itemTitle == '') itemTitle = '(Untitled)';
	itemLink = "javascript:insertURL('"+site_httpbase + oRecord.getData('full_path')+"')";
	elCell.innerHTML =  '<A HREF="'+ itemLink+'"><img src="'+site_httpbase + imgIcon+'" border="0" align="absmiddle" />'+ itemTitle + '</A>';		

};

///////////////////////////////////////////////////////////
Admin.bbgkmediamanager.prototype.getCategoryListingURL = function() {
	var action = "action=getfolders";
	return action;
};

///////////////////////////////////////////////////////////
var insertURL = function(URL)
{
				try 
				{
					var win = tinyMCEPopup.getWindowArg("window");
				} 
				catch(err) 
				{
					alert('Error, cannot insert');
					return;
				}	
					
				// insert information now
				win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = URL;
						
				// are we an image browser
				if (typeof(win.ImageDialog) != "undefined") 
				{
					// we are, so update image dimensions...
					if (win.ImageDialog.getImageData) 
						win.ImageDialog.getImageData();
							
					// ... and preview if necessary
					if (win.ImageDialog.showPreviewImage) 
						win.ImageDialog.showPreviewImage(URL);
				}
						
				// close popup window
	            tinyMCEPopup.close();
			
	
}
////////////////////////////////////////////////////////////
Admin.bbgkmediamanager.prototype.updateFileListToUpload = function()
{
  var input = document.getElementById('fileselector');
  var ul = document.getElementById('filelist');
	while (ul.hasChildNodes()) {
		ul.removeChild(ul.firstChild);
	}  
  // You've selected input.files.length files
  for (var i = 0; i < input.files.length; i++) {
    // input.files[i] is a file object
    var li = document.createElement("li");
    li.innerHTML = input.files[i].name;
    ul.appendChild(li);
  }
}
///////////////////////////////////////////////////////////

Admin.bbgkmediamanager.prototype.Start = function()
{
	gekko_app = new Admin.bbgkmediamanager (app_name, app_description); 
	gekko_app.Run();
}

///////////////////////////////////////////////////////////
$onload(Admin.bbgkmediamanager.prototype.Start);
  
