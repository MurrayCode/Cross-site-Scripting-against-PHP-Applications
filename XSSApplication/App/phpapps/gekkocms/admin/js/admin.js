//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

/* Prana, Baby Gekko */
var gekko_app;
var gekko_admin_current_path = 'gekko_admin_current_path';
var gekko_admin_search_form = 'gekko_admin_search_form';
var gekko_admin_main_content = 'gekko_admin_main_content';
var gekko_admin_sidebar_content = 'gekko_admin_sidebar_content';
var _gekko_application_url = '';
var $gid = YAHOO.util.Dom.get;
var $gclass = YAHOO.util.Dom.getElementsByClassName;
var $cookie = YAHOO.util.Cookie;
var $extend_class = YAHOO.lang.extend;

var gekkoDatePicker = Gekko.DateTimePicker; // For backward compatibility .. should be removed in the future
YAHOO.util.Event.onDOMReady(gekkoDatePicker.init);

//----------------------------------------------------------------------------------------------------------//
var json_decode = function(jsonstr) {
//if (jsonstr.length > 0)
if(jsonstr.replace(/\s/g,"") != "")
{
//  var data = eval('('+jsonstr+')'); // no more
  var data = JSON.parse(jsonstr); 
  return data;
}else 
{
	alert ('Gekko AJAX Communication Error.\nServer returns an empty string. Please take a screenshot and e-mail technical support');
	return false;
}
}

var ajaxParse = function(reply_from_server)
{
	var the_reply = json_decode (reply_from_server);
	if (the_reply != false)
	{
		if (the_reply['status'] != '200')
		{
			if (the_reply['data'])
				alert ('Error: ' + the_reply['data']); 
			else
				alert ('Error: AJAX Communication error with the server.\nPlease log out and try again.');
		} else return the_reply['data'];
	}
}
///////////////////////////////////////////////////////////
/*var displayGekkoInternalMessage = function()
{
	var gekko_modal_dialog;
	var handleYes = function() {this.hide();gekko_modal_dialog = null;};
	var gekko_modal_dialog = new YAHOO.widget.SimpleDialog("gekko_internal_message", 
										 { width: "300px",
										   fixedcenter: true,
										   visible: false,
										   draggable: false,
										   close: true,
										   modal:true, 
										   text: "Error",
										   icon: YAHOO.widget.SimpleDialog.ICON_WARN,
										   constraintoviewport: true,
										   buttons: [ { text:"OK", handler:handleYes, isDefault:true }  ]
										 } );
	gekko_modal_dialog.setHeader("Error");
	gekko_modal_dialog.render("container");
	gekko_modal_dialog.show();
}*/
///////////////////////////////////////////////////////////

//----------------------------------------------------------------------------------------------------------//
// override default
YAHOO.util.DataSource.Parser.date = function (oData) {
if (oData != null)
{
	if (oData != '0000-00-00 00:00:00')
	{
		var parts = oData.split(' ');
		var datePart = parts[0].split('-');
		if (parts.length > 1) 
		{
			var timePart = parts[1].split(':');
			var x = new Date(datePart[0],datePart[1]-1,datePart[2],timePart[0],timePart[1],timePart[2]);
			return x;
		} else 
		{
			var x=new Date(datePart[0],datePart[1]-1,datePart[2]);
			return x;
		}
	} else return new Date (1900,0,1);
 } else return new Date (1900,0,1);
}; 
//----------------------------------------------------------------------------------------------------------//
YAHOO.namespace('admin');
var Admin = YAHOO.admin;


/*
	DACANADACANADACANADACANADACANADACANADACANADACANADACANADACANADACANAD
	ACANADACANADACAN                                   NADACANADACANADA
	CANADACANADACANA                 A                 ADACANADACANADAC
	ANADACANADACANAD                ADA                DACANADACANADACA
	NADACANADACANADA           AC  ADACA  DA           ACANADACANADACAN
	ADACANADACANADAC            ANADACANADA            CANADACANADACANA
	DACANADACANADACA        DA   ADACANADA  NA         ANADACANADACANAD
	ACANADACANADACAN    ANADACAN  ACANADA  NADACANA    NADACANADACANADA
	CANADACANADACANA     ADACANADACANADACANADACANA     ADACANADACANADAC
	ANADACANADACANAD   NADACANADACANADACANADACANADAC   DACANADACANADACA
	NADACANADACANADA      CANADACANADACANADACANAD      ACANADACANADACAN
	ADACANADACANADAC         DACANADACANADACAN         CANADACANADACANA
	DACANADACANADACA           ANADACANADACA           ANADACANADACANAD
	ACANADACANADACAN         CANADACANADACANAD         NADACANADACANADA
	CANADACANADACANA                 A                 ADACANADACANADAC
	ANADACANADACANAD                 D                 DACANADACANADACA
	NADACANADACANADA                 A                 ACANADACANADACAN
	ADACANADACANADAC                                   CANADACANADACANA
	DACANADACANADACANADACANADACANADACANADACANADACANADACANADACANADACANAD
*/

Admin.DragDrop = function(id, sGroup, config) {
	
    if (id) {
        // bind this drag drop object to the
        // drag source object
		//alert (id);
        this.init(id, sGroup, config);
        this.initFrame();
    }
  /*  var s = this.getDragEl().style;
    s.borderColor = "transparent";
    s.backgroundColor = "#";
    s.opacity = 0.76;
    s.filter = "alpha(opacity=76)";
	var cname = this.getDragEl().className;
	cname = "dragdrop";*/
};

// extend proxy so we don't move the whole object around
Admin.DragDrop.prototype = new YAHOO.util.DDProxy();

Admin.DragDrop.prototype.onDragDrop = function(e, id) {
	var dragEl = YAHOO.util.DDM.getElement(id);
	dragEl.style.border = "none";
	var total_selections = gekko_app.getSelectedItems();
	if (total_selections.length <=1)
		var str = this.id;
	else
		var str = total_selections.join(",");
	gekko_app.moveSelectedItems(str,id);
}

Admin.DragDrop.prototype.startDrag = function(x, y) {
    // called when source object first selected for dragging
    // draw a red border around the drag object we create
    var dragEl = this.getDragEl();
    var clickEl = this.getEl();

/* January 9, 2008  Prana */
	//var total_selections = gekkoGetCheckedItems();
	var total_selections = gekko_app.getSelectedItems();
	if (total_selections.length <= 1)
	{ // single-item selection
		dragEl.innerHTML = "<div class='gekko_dragndrop'>1 item</div>";			
	}
	else
	{ // multiple-item selections
		dragEl.innerHTML = "<div class='gekko_dragndrop'>" + total_selections.length + " items</div>";	
		dragEl.style.height= '50px';
	}
	dragEl.className = "dragdrop"; //clickEl.className;
    dragEl.style.color = clickEl.style.color;
    dragEl.style.border = "1px solid red";

};

Admin.DragDrop.prototype.onDragEnter = function(e, id) {
    var el;
    // this is called anytime we drag over
    // a potential valid target
    // highlight the target in red
    if ("string" == typeof id) {
        el = YAHOO.util.DDM.getElement(id);
    } else {
        el = YAHOO.util.DDM.getBestMatch(id).getEl();
    }
	YAHOO.util.Dom.addClass(el,'dragenter');
};

Admin.DragDrop.prototype.onDragOut = function(e, id) {
    var el;

    // this is called anytime we drag out of
    // a potential valid target
    // remove the highlight
    if ("string" == typeof id) {
        el = YAHOO.util.DDM.getElement(id);
    } else {
        el = YAHOO.util.DDM.getBestMatch(id).getEl();
    }
	YAHOO.util.Dom.removeClass(el,'dragenter');
}

Admin.DragDrop.prototype.endDrag = function(e) {
   // override so source object doesn't move when we are done
}
/*
	DACANADACANADACANADACANADACANADACANADACANADACANADACANADACANADACANAD
	ACANADACANADACAN                                   NADACANADACANADA
	CANADACANADACANA                 A                 ADACANADACANADAC
	ANADACANADACANAD                ADA                DACANADACANADACA
	NADACANADACANADA           AC  ADACA  DA           ACANADACANADACAN
	ADACANADACANADAC            ANADACANADA            CANADACANADACANA
	DACANADACANADACA        DA   ADACANADA  NA         ANADACANADACANAD
	ACANADACANADACAN    ANADACAN  ACANADA  NADACANA    NADACANADACANADA
	CANADACANADACANA     ADACANADACANADACANADACANA     ADACANADACANADAC
	ANADACANADACANAD   NADACANADACANADACANADACANADAC   DACANADACANADACA
	NADACANADACANADA      CANADACANADACANADACANAD      ACANADACANADACAN
	ADACANADACANADAC         DACANADACANADACAN         CANADACANADACANA
	DACANADACANADACA           ANADACANADACA           ANADACANADACANAD
	ACANADACANADACAN         CANADACANADACANAD         NADACANADACANADA
	CANADACANADACANA                 A                 ADACANADACANADAC
	ANADACANADACANAD                 D                 DACANADACANADACA
	NADACANADACANADA                 A                 ACANADACANADACAN
	ADACANADACANADAC                                   CANADACANADACANA
	DACANADACANADACANADACANADACANADACANADACANADACANADACANADACANADACANAD
*/

// --------------------------------- CONSTRUCTOR ------------------------- //
Admin.Basic = function (theapp_name, theapp_description)
{
    this.app_name = theapp_name;
    this.app_description = theapp_description;
    $register_gekko_app(this.app_name, this)
    this.gekko_admin_current_path = 'gekko_admin_current_path';
    this.gekko_admin_search_form = 'gekko_admin_search_form';
    this.gekko_admin_main_content = 'gekko_admin_main_content';
    this.gekko_admin_sidebar_content = 'gekko_admin_sidebar_content';
    this.gekko_admin_searchform = 'gekko_admin_searchform';    
    this.detectApplicationURL();
};
// --------------------------------- VARIABLES ------------------------------- //
Admin.Basic.prototype.app_description   = '';
Admin.Basic.prototype.app_name   = '';
Admin.Basic.prototype._gekko_application_url = '';
// --------------------------------- PROCEDURES -------------------------- //
///////////////////////////////////////////////////////////
Admin.Basic.prototype.Run = function ()
{

	YAHOO.util.Event.onDOMReady(this.initLayout);

}
///////////////////////////////////////////////////////////

Admin.Basic.prototype.initLayout = function ()
{
	if (document.getElementById('gekko_admin_sidebar') && document.getElementById('gekko_admin_main')) 
	// prana - March 14 - TODO - no hardcoding please
	{
		this.layout = new YAHOO.widget.Layout(
		{
			units: [
				{ position: 'top', height: 83, body: 'header', gutter: '0', scroll: null, zIndex: 2},
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

Admin.Basic.prototype.afterLayoutRender = function()
{// very strange IE7,8 bug I have to create this function
	var elem = document.getElementById('gekko_admin_main'); 
	elem.style.display = 'block';
}
///////////////////////////////////////////////////////////
Admin.Basic.prototype.handleFailure = function()
{
	if(o.responseText !== undefined)
	{
		alert ('Error '+ o.tId + ': ' + o.status + ', ' + o.statusText);
	} else alert('Data Error');
}
///////////////////////////////////////////////////////////
Admin.Basic.prototype.reportGETOperation = function(o)
{
	var response =  ajaxParse (o.responseText);
}
///////////////////////////////////////////////////////////
Admin.Basic.prototype.reportPOSTOperation = function(o)
{
	var response = ajaxParse (o.responseText);
	
}
///////////////////////////////////////////////////////////
Admin.Basic.prototype.setParentApplicationName = function(name)
{
	this.parent_app_name = name;
	this.detectApplicationURL();
}
///////////////////////////////////////////////////////////
Admin.Basic.prototype.detectApplicationURL = function()
{
	var the_app_name = (this.app_name == "") ? app_name : this.app_name;
	var the_parent_app_name;
	
	if (typeof(this.parent_app_name) != "undefined"  ) 
	{
		if (this.parent_app_name.length > 0 )
			_gekko_application_url = "/admin/index.php?app=" + this.parent_app_name + "&appmodule=" + the_app_name;
	}
	else 
		_gekko_application_url= "/admin/index.php?app=" + the_app_name;
	this._gekko_application_url = _gekko_application_url;
}

///////////////////////////////////////////////////////////
Admin.Basic.prototype.ajaxRequestGET = function( thefunction,  therequest) {
	var the_appname = (typeof this.app_name == "undefined") ? app_name : this.app_name;
	if ((typeof app_name == "undefined")) {alert('Gekko Application Name is undefined!'); return false;}
	if ((typeof this._gekko_application_url == "undefined")) {alert('Gekko Application URL is undefined!'); return false;}	
	var callback = {success:thefunction,failure:this.handleFailure,scope:this}; 
	var sUrl = site_httpbase + this._gekko_application_url + "&ajax=1&" + therequest;
	var request = YAHOO.util.Connect.asyncRequest('GET', sUrl, callback);
};
///////////////////////////////////////////////////////////
Admin.Basic.prototype.ajaxRequestPOST = function( thefunction,  therequest, postData) {

	var the_appname =  (typeof this.app_name == "undefined") ? app_name : this.app_name;
	if ((typeof app_name == "undefined")) {alert('Gekko Application Name is undefined!'); return false;}	
	if ((typeof this._gekko_application_url == "undefined")) {alert('Gekko Application URL is undefined!'); return false;}	
	
	var callback = {success:thefunction,failure:this.handleFailure, scope:this}; 
	var sUrl = site_httpbase + this._gekko_application_url + "&ajax=1&action=" + therequest;
	var request = YAHOO.util.Connect.asyncRequest('POST', sUrl, callback,postData);
};

///////////////////////////////////////////////////////////
Admin.Basic.prototype.ajaxRequestPOSTForm = function(thefunction,  therequest, the_form) {

	var formObject = document.getElementById(the_form);  
	if (formObject)
	{
		var the_appname =  (typeof this.app_name == "undefined") ? app_name : this.app_name;
		if ((typeof app_name == "undefined")) {alert('Gekko Application Name is undefined!'); return false;}	
		if ((typeof _gekko_application_url == "undefined")) {alert('Gekko Application URL is undefined!'); return false;}	
		
		var callback = {upload:thefunction,failure:this.handleFailure, scope:this}; 
		var sUrl = site_httpbase + _gekko_application_url + "&ajax=1&action=" + therequest;
		YAHOO.util.Connect.setForm(formObject, true); //,true
		var request = YAHOO.util.Connect.asyncRequest('POST', sUrl, callback);

	}
};

/*

                ____,                        "||||.W.||
               //  "|                         ||||^T^||
              ||     /^^\\  \\/^\\  /^^\\ //^^|| /^^\\
              ||     ,-'||  ||   || ,-'||||   || ,-'||
               \\___/\\_/\;_||_ _||_\\_/\;\\__/|,\\_/\;

*/

// --------------------------------- CONSTRUCTOR ------------------------- //
Admin.BasicLinearData = function (theapp_name, theapp_description, field_id, field_item_title)
{
//	alert('Admin.BasicLinearData constructor');	
//	Admin.Basic.call (theapp_name, theapp_description);
	Admin.BasicLinearData.superclass.constructor.call(this,theapp_name, theapp_description);
//	alert(theapp_name);
	this.app_description = theapp_description;
	this.field_id  = (field_id == null) ? 'id' : field_id;
	this.field_item_title = (field_item_title == null) ? 'title' : field_item_title;
	
	this.checkAllID = theapp_name + 'check_all';
	this.selectionCheckBoxes = theapp_name +  'chkselections[]';
	
};

///////////////////////////////////////////////////////////
$extend_class(Admin.BasicLinearData, Admin.Basic);
///////////////////////////////////////////////////////////


// --------------------------------- VARIABLES ------------------------------- //
Admin.BasicLinearData.prototype.field_id   = '';
Admin.BasicLinearData.prototype.field_item_title = '';
Admin.BasicLinearData.prototype.form_item_editor = 'frm_item_editor';
Admin.BasicLinearData.prototype.app_description   = '';
Admin.BasicLinearData.prototype.content_array = [];
Admin.BasicLinearData.prototype.dataSource = null;
Admin.BasicLinearData.prototype.dataResponseSchema = null;
Admin.BasicLinearData.prototype.dataTable = null;
Admin.BasicLinearData.prototype.columnDefinition = null;
Admin.BasicLinearData.prototype.selectedRow = null;
Admin.BasicLinearData.prototype.checkAllID = 'check_all';
Admin.BasicLinearData.prototype.selectionCheckBoxes = 'chkselections[]';

// --------------------------------- PROCEDURES -------------------------- //
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.Run = function ()
{
	Admin.BasicLinearData.superclass.Run.call(this);
    this.getItemsListing();
	this.assignButtons();
}

Admin.BasicLinearData.prototype.assignButtons = function ()
{
   YAHOO.util.Event.addListener(document.getElementById('button_delete'), "click", this.editDelete, this, true); 
   YAHOO.util.Event.addListener(document.getElementById('button_copy'), "click", this.editCopy, this, true); 	   
   YAHOO.util.Event.addListener(document.getElementById('button_cut'), "click", this.editCut, this, true); 			
   YAHOO.util.Event.addListener(document.getElementById('button_paste'), "click", this.editPaste, this, true); 	
   YAHOO.util.Event.addListener(document.getElementById(this.gekko_admin_searchform), "submit", this.searchItems, this, true); 
   
}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.getItemsListingURL = function()
{
	var the_appname = this.app_name;
	if (the_appname == '') this.app_name = app_name; // bugfix hack
	
	var action = site_httpbase + _gekko_application_url + "&ajax=1&" + "action=getallitems&id=" + this.currentCategory;	
	return action;
}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.getItemsListing = function(directory_id)
{
	this.dataSource = null // Dec 12, 2011
	this.dataSource = new YAHOO.util.DataSource(this.getItemsListingURL());
	//this.dataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; // added Dec 13, 2011
	//this.dataSource.responseSchema = this.dataResponseSchema; //  added Dec 13, 2011
	
	this.buildApplication();
}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.displaySearchResultInPathBar = function (keyword)
{
	var str_path = "&raquo; " + "Search result(s) for keyword '" + keyword + "'...";
	var div = document.getElementById(this.gekko_admin_current_path);
	div.innerHTML = str_path;	
}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.getSelectedItems = function() 
{
	var chkselections = document.getElementsByName(this.selectionCheckBoxes);
	var total_selections = [];
	var total_draggable = 0;
		for (var i= 0; i < chkselections.length; i++)
		{
			if (chkselections[i].checked)
			{
				total_selections[total_draggable] = chkselections[i].value;
				total_draggable++;
			}
		}
//	if (total_selections == 0)	
//		alert('You have not made any selections yet. Please tick the checkbox to make your selections');
	return total_selections;
}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.reportPOSTOperation = function(o)
{
	Admin.BasicLinearData.superclass.reportPOSTOperation.call (this,o);
	this.dataTable.doBeforeLoadData();

	// Dec 12, 2011 - replaced this.dataTable._request to this.buildDynamicRequestURL()
	// Feb 17, 2012 - Commented out - why is it still here ??
//    this.dataTable.getDataSource().sendRequest(this.buildDynamicRequestURL(),this.dataTable.onDataReturnInitializeTable,this.dataTable);	//Jan 16 - commented out - not returning paginator - don't delete this comment
    this.dataTable.getDataSource().sendRequest(this.buildDynamicRequestURL(), { success: this.dataTable.onDataReturnSetRows, scope: this.dataTable, argument: this.dataTable.getState()});
	var checkall = document.getElementById(this.checkAllID);
	checkall.checked = false;
}
/////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.copySelectedItems = function (items_to_move,destination)
{
		var postData = "items=" +items_to_move + "&destination=" + destination;
		this.ajaxRequestPOST(this.reportPOSTOperation, 'copy', postData);
}

/////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.editCopy = function ()
{
	
	this.bufferContent = this.getSelectedItems();
	this.bufferAction = 'copy';
}
/////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.editPaste = function ()
{
	this.copySelectedItems (this.bufferContent,'c' + this.currentCategory);
	this.bufferContent = ''; // empty the buffer
	this.bufferAction = '';
}
/////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.editDelete = function ()
{
	var items = this.getSelectedItems();
 	if ( items.length > 0)
	{
		if ( confirm('Delete these items?'))
		{
			var postData = "items=" +items;
			this.ajaxRequestPOST(this.reportPOSTOperation, 'delete', postData);
		}
	} else alert('There is nothing to delete');
}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.onRowSelect = function(ev)
{
 		var Dom = YAHOO.util.Dom,
        Event = YAHOO.util.Event,
        ddRow = null,
        overLi = null,
        selectedRow = null;	var thisStatus = Dom.get('status');
		var myDataTable = this.dataTable;
        var par = this.dataTable.getTrEl(Event.getTarget(ev)); //The tr element
        this.selectedRow = this.dataTable.getSelectedRows();
 }
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.formatIcon = function(elCell, oRecord, oColumn, oData) {
	var odata_id = oRecord.getData("id");
	var odata_cid = oRecord.getData("cid");	
	var the_id = "i" + odata_id;
	var the_number = odata_id;
	var class_name = "gekko_datatable_icon_item";
	if (odata_cid > 0)
	{
		 class_name = "gekko_datatable_icon_category"
		 the_id = "c" + odata_cid;
		 the_number = odata_cid;
	} 
	elCell.innerHTML = '<div class="' + class_name +'" id="'+ the_id +'" >' + the_number + '</div>';

	if (!this.disableDragDrop) new Admin.DragDrop(the_id);			
};
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.formatSelectionCheckBox = function(elCell, oRecord, oColumn, oData) {
	var theID = '';
	var theValue = '';

	var app_name = oColumn.app_name;
 	if (oRecord.getData("cid") > 0)
	{
		theID = app_name + 'cat'  + oRecord.getData("cid");
		theValue =  'c'  + oRecord.getData("cid");
	}
	else
	{
		theID =  app_name + 'item' + oRecord.getData("id");
		theValue =  'i'  + oRecord.getData("id");
	}
	
	elCell.innerHTML =  '<input type="checkbox" name="'+app_name+'chkselections[]" id="' +theID + '"  value="' + theValue + '" onclick="javascript:gekko_app.toggleCheck(\''+theID+'\');" />';
//	YAHOO.util.Event.addListener(document.getElementById(theID), "click", gekko_app.toggleCheck);
};
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.formatStatus = function(elCell, oRecord, oColumn, oData) {
	switch (oData)
	{
		case 1:  button_name = "status_active";break;
		default:  button_name = "status_inactive";break;
	} 
	elCell.innerHTML = "<img src=\"" + site_httpbase + "/images/default/trans.png\" alt=\""+button_name+"\" title=\""+button_name+"\" border=\"0\" id=\""+button_name+"\" class=\"img_buttons16 imgsprite16_"+button_name+"\" />";
	/*
	switch (oData)
	{
		case 1:  class_name = 'gekko_status_active';break;
		default: class_name = 'gekko_status_inactive';break;		
	}	
	elCell.innerHTML = "<div class=\"" + class_name +"\">&nbsp;</div>";*/
};
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.formatDate = function(elCell, oRecord, oColumn, oData) {
    var oDate = oData;
    var sMonth;
	if (oDate != null)
	{
		if (oDate.getFullYear() == 1900)
		{
		 elCell.innerHTML = 'N/A';	
		} else
		{
			switch(oDate.getMonth())
			{
				case 0:sMonth = "Jan";break;
				case 1:sMonth = "Feb";break;
				case 2:sMonth = "Mar";break;
				case 3:sMonth = "Apr";break;
				case 4:sMonth = "May";break;
				case 5:sMonth = "Jun";break;
				case 6:sMonth = "Jul";break;
				case 7:sMonth = "Aug";break;
				case 8:sMonth = "Sep";break;
				case 9:sMonth = "Oct";break;
				case 10:sMonth = "Nov";break;
				case 11:sMonth = "Dec";break;
			}
			elCell.innerHTML =  oDate.getFullYear() + "-" + sMonth + "-" + oDate.getDate();
		}
	} else elCell.innerHTML = 'N/A';
};
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.reportAjaxSaveItemOperation = function(o)
{
	var saveResult = ajaxParse (o.responseText);
 	if (saveResult.status == "Save_OK")
	{
		var item_field_id = document.getElementById(this.field_id);
		if (item_field_id != null && saveResult.newid != null)
		{
			item_field_id.value = saveResult.newid;
			alert('Item has been saved');
			return true;
		}
	}
	return false;
}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.ajaxSaveItem = function()
{
	this.ajaxRequestPOSTForm(this.reportAjaxSaveItemOperation, 'ajaxsaveitem', this.form_item_editor);
}

///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.formatEditLink = function(elCell, oRecord, oColumn, oData) {
	//alert (oRecord.toSource());
	var itemTitle = oData;
	var itemLink = '';
	itemLink = 'index.php?app=' + app_name + '&action=edititem&id=' + oRecord.getData("id");
	elCell.innerHTML =  '<A HREF="'+ itemLink+'">'+ itemTitle + '</A>';		
};
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.toggleCheck = function(itemid)
{
	
	var checkall = document.getElementById(this.checkAllID);
	var chkselections = document.getElementsByName(this.selectionCheckBoxes);
	var allchecked = true;
	var checked = itemid.checked;
	if (checkall.checked && !itemid.checked) checkall.checked = false;
	else 
	{
		for (i= 0; i  < chkselections.length; i++)
		{
			if (!chkselections[i].checked) 
			{
				allchecked = false;break;
			} // end if
		} // end for
		checkall.checked = allchecked;
	}
}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.selectAllItemsAndCategories = function( ) {
	var checkall = document.getElementById(this.checkAllID);
	var toggle = checkall.checked;
	var chkselections = document.getElementsByName(this.selectionCheckBoxes);
	for (i= 0; i	 < chkselections.length; i++) chkselections[i].checked = toggle;
}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.getUpdateFieldURL = function()
{
	return 'updatefield';
}

///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.saveEventHandler = function(oArgs) {
	
	var oEditor = oArgs.editor;
	var newData = oArgs.newData;
	var oldData = oArgs.oldData;
	var oRecord_id = oArgs.editor.getRecord().getData("id");
	var oRecord_cid = oArgs.editor.getRecord().getData("cid");
	var oField = oArgs.editor.getColumn();
	var oRecord = '';
	if (oRecord_id > 0)
		oRecord = 'i' + oRecord_id;
	else 
		oRecord = 'c' + oRecord_cid;
	var postData = 'id='+ oRecord + '&field=' + oField.field + '&value=' + newData;

	this.ajaxRequestPOST(this.reportPOSTOperation, this.getUpdateFieldURL(), postData);
}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.searchItems = function(oArgs) {

	this.buildApplication();
	return false;
}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.buildDataPaginator = function()
{
	if (this.paginator) 
	{
		this.paginator.destroy();
		this.paginator = null;
	} /*rowsPerPageOptions : [10,25,50,100],*/
	this.paginator = null;
	this.paginator = new YAHOO.widget.Paginator({ rowsPerPage: datatable_max_row_perpage/*, template : "{FirstPageLink} {PreviousPageLink} {PageLinks} {NextPageLink} {LastPageLink} <strong>{CurrentPageReport}</strong>"*/});

}
///////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.buildDataSourceURL = function()
{
	if (document.getElementById('searchbox'))
	{
		var searchString = document.getElementById('searchbox').value;
		var action = '';
		if (searchString != 'search...' && searchString != '')
		{
			action = site_httpbase + this._gekko_application_url + "&ajax=1&" + "action=search&keyword=" + searchString;
			this.displaySearchResultInPathBar(searchString); // fix this 10 please
		}
		else
		{
			action = site_httpbase + this._gekko_application_url + "&ajax=1&" + "action=getallitems";
		}
		
	}
	else
		action = site_httpbase + this._gekko_application_url + "&ajax=1&" + "action=getallitems";
	return action;
}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.dataSourceReplyError = function (oRequest, oFullResponse, oCallback)
{
	if(oFullResponse.status != 200)
	{
		alert('Gekko Data Error :' + oFullResponse.data);
	}
	return oFullResponse;
}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.getHeaderSelectAllCheckbox = function()
{
	return '<input type="checkbox" id="'+this.checkAllID+'" />';
}

///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.buildDataColumnDefinitionAndResponseSchema = function()
{
	var status_array = [{value:0, label: "Inactive"}, {value:1, label: "Active"} ];
	
	// Step 1 - JSON Data Response Schema
	this.dataResponseSchema = {
		resultsList: "data",
		// Use the parse methods to populate the RecordSet with the right data types
		fields: [
			{key:this.field_id, parser:"number"}, 
			{key:this.field_item_title, parser:"string"}, 
			{key:"status", parser:"number"}, 
			{key:"sort_order", parser:"number"}, 
		],
		
	  metaFields: {
				totalRecords: "totalRecords",
				paginationRecordOffset : "start",
				paginationRowsPerPage : "itemsperpage",
				sortKey: "sortby",
				sortDir: "sortdirection"
			}				
	};
	
	// Step 2 - Column Definition / Headers
	this.columnDefinition = [
		{key:"check", label: this.getHeaderSelectAllCheckbox(), formatter:this.formatSelectionCheckBox}, // use the built-in checkbox formatter (shortcut)
		{key:"button", label:"ID", formatter:this.formatIcon}, // use the built-in button formatter
		{key: this.field_id, hidden:true, sortable:false, formatter:"number"},
		{key: this.field_item_title, label: 'Title', sortable:true, formatter:this.formatEditLink}, 
		{key:"status", label: 'Status', sortable:true, formatter:this.formatStatus, editor:  new YAHOO.widget.DropdownCellEditor({dropdownOptions:status_array,disableBtns:false}) }, 
		{key:"sort_order", label: 'Sort Order', sortable:true, formatter:"number", editor: new YAHOO.widget.TextboxCellEditor({validator:YAHOO.widget.DataTable.validateNumber,disableBtns:false})},
	];
	
	
}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.buildDataTableEvent = function( )
{
//	this.dataTable.subscribe("rowMouseoverEvent", this.dataTable.onEventHighlightRow);
//	this.dataTable.subscribe("rowMouseoutEvent", this.dataTable.onEventUnhighlightRow);
	this.dataTable.subscribe("cellClickEvent", this.dataTable.onEventShowCellEditor);
	this.dataTable.subscribe("editorSaveEvent", this.saveEventHandler, this, true);	
	YAHOO.util.Event.addListener(document.getElementById(this.checkAllID), "click", this.selectAllItemsAndCategories, this, true); 
}
///////////////////////////////////////////////////////////
// Dec 6, 2011 - for large dataset
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.buildDynamicRequestURL = function(oState, oSelf)
{
	oState = oState || { pagination: null, sortedBy: null };
	var sortby = (oState.sortedBy) ? oState.sortedBy.key : "id";
	var dir = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_DESC) ? "desc" : "asc";
	var startIndex = (oState.pagination) ? oState.pagination.recordOffset : 0;
	var perPage = (oState.pagination) ? oState.pagination.rowsPerPage : datatable_max_row_perpage;
	YAHOO.util.Cookie.set(app_name + "_current_start", startIndex);
	
	return  "&dynamic=1&sortby=" + sortby + "&sortdirection=" + dir + "&start=" + startIndex + "&end=" + (startIndex + perPage);
};
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.buildInitialRequestURL = function(oState, oSelf)
{
	var cookie_start_index = YAHOO.util.Cookie.get(app_name + "_current_start");
	
	oState = oState || { pagination: null, sortedBy: null };
	var sortby = (oState.sortedBy) ? oState.sortedBy.key : "id";
	var dir = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_DESC) ? "desc" : "asc";
	var startIndex =  (cookie_start_index > 0) ? cookie_start_index : 0;
	var perPage = (oState.pagination) ? oState.pagination.rowsPerPage : datatable_max_row_perpage;
//startIndex=20;
	return  "&dynamic=1&sortby=" + sortby + "&sortdirection=" + dir + "&start=" + startIndex + "&end=" + (startIndex + perPage);
};

///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.buildDataSource = function()
{
	//if (this.dataSource) this.dataSource.destroy();	<--- commented out, somehow causing an error
	this.dataSource = null;
	this.dataSource = new YAHOO.util.DataSource(this.buildDataSourceURL());
	this.dataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	this.dataSource.responseSchema = this.dataResponseSchema; //  
//	this.dataSource.subscribe('requestEvent',this.handleFailure);
    this.dataSource.doBeforeParseData = this.dataSourceReplyError;

}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.buildDataTable = function( )
{
	if (this.dataTable) this.dataTable = null;
	this.addDataResponseSchemaMetaFields();
    var dataTableConfigLargetDataset = {paginator : this.paginator, dynamicData : true, initialLoad : true, rowSingleSelect: true, generateRequest: this.buildDynamicRequestURL, initialRequest: this.buildInitialRequestURL()};	
   // var dataTableConfigSmallDataSet = {paginator : this.paginator,rowSingleSelect: true};	
	// build Data Table
	this.dataTable = new YAHOO.widget.DataTable(this.gekko_admin_main_content, this.columnDefinition, this.dataSource,dataTableConfigLargetDataset);
	
    this.dataTable.doBeforeLoadData = this.DataTableBeforeLoadData;	
	this.dataTable.handleDataReturnPayload = this.DataTablehandleDataReturnPayload;
}
///////////////////////////////////////////////////////////

Admin.BasicLinearData.prototype.addDataResponseSchemaMetaFields = function()
{// Dec 6, 2011
	if (this.dataResponseSchema.metaFields == null)
	{
        this.dataResponseSchema.metaFields = {
            totalRecords: "totalRecords",
            start: "start",
			end: "end",
            itemsperpage: "itemsperpage",			
            sortKey: "sortby",
            sortDir: "sortdirection"
        }
	}
	// Workaround for no object name in formatting (Feb 4, 2012)
	if (this.columnDefinition != null)
	{
		for (var i = 0; i < this.columnDefinition.length;i++)
		{
			this.columnDefinition[i].app_name = this.app_name;
			// Backward compatibility with v1.1.4 and below
			if (this.columnDefinition[i].key == 'check' && this.columnDefinition[i].label == '<input type="checkbox" id="check_all" />')
			{
				this.columnDefinition[i].label = this.getHeaderSelectAllCheckbox();
			}
		}
	}
}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.DataTableBeforeLoadData = function (oRequest, oResponse, oPayload)
{
	if (oPayload != undefined)
	{
		oPayload.totalRecords = oResponse.meta.totalRecords;
		oPayload.pagination.rowsPerPage  = oResponse.meta.itemsperpage;
		oPayload.pagination.recordOffset = oResponse.meta.start;
	}
	return oPayload;
}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.DataTablehandleDataReturnPayload = function (oRequest, oResponse, oPayload)
{
	oPayload.totalRecords = oResponse.meta.totalRecords;
	oPayload.pagination.rowsPerPage  = oResponse.meta.items_per_page;
	oPayload.pagination.recordOffset = oResponse.meta.start;
	return oPayload;
}

///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.buildApplication = function( )
{
	this.buildDataColumnDefinitionAndResponseSchema();
	this.buildDataPaginator();
	this.buildDataSource();
	this.buildDataTable();
	this.buildDataTableEvent();
}
///////////////////////////////////////////////////////////
/*
          __________     __________
         /          \   /          \
        /            \ /            \
        |        @@  | |   /\       |
       /\        @@  / \            /\   Kerokerokeroppi 
      /  \ _________/   \__________/  \         
     /                                 \
    (    O                         O    )
     \    \_                     _/    /
       \_   ---------------------   _/
         ----___________________---- __-->
        /   / =  =  |\ /| =  =  =\       >      
      /    /  =  =  |/ \| =  =  = \ __-- >
    /     /=  =  =  =  =  =  =  =  \     

*/

// --------------------------------- CONSTRUCTOR ------------------------- //
Admin.BasicSimpleCategories = function (theapp_name, theapp_description, field_id, field_item_title, field_category_id, field_category_title)
{
	Admin.BasicSimpleCategories.superclass.constructor.call(this,theapp_name, theapp_description, field_id, field_item_title);
	this.field_category_id = (field_category_id == null) ? 'cid' : field_category_id;;
	this.field_category_title = (field_category_title == null) ? 'title' : field_category_title;
	this.treeNodeIDPrefix = theapp_name + 'gwp_leftfolder_';
	this.rootTreeNodeID = theapp_name + 'gwp_leftfolder_0';
	this.checkboxNodeIDPrefix = 'chk_cid_' + theapp_name + 'gwp_leftfolder_';
	this.checkboxRightNodeIDPrefix = theapp_name + 'chk_cid_';
	
//	this.field_parent_id = (field_parent_id == null) ? 'parent_id' : field_parent_id;;
};
///////////////////////////////////////////////////////////
$extend_class(Admin.BasicSimpleCategories, Admin.BasicLinearData);
///////////////////////////////////////////////////////////

// --------------------------------- VARIABLES ------------------------------- //
Admin.BasicSimpleCategories.prototype.field_category_id   = '';
//Admin.BasicSimpleCategories.prototype.field_parent_id  = '';
Admin.BasicSimpleCategories.prototype.form_category_editor = 'frm_category_editor';
Admin.BasicSimpleCategories.prototype.field_category_title = '';
Admin.BasicSimpleCategories.prototype.categoryTree = null;
Admin.BasicSimpleCategories.prototype.category_array = [];
Admin.BasicSimpleCategories.prototype.categoryNodes = [];
Admin.BasicSimpleCategories.prototype.categoryNodeIndex = 0;
Admin.BasicSimpleCategories.prototype.currentCategory = 1;
Admin.BasicSimpleCategories.prototype.bufferCategory = null;
Admin.BasicSimpleCategories.prototype.bufferContent = null;
Admin.BasicSimpleCategories.prototype.bufferAction = null;
Admin.BasicSimpleCategories.prototype.disableDragDrop = false;
Admin.BasicSimpleCategories.prototype.treeNodeIDPrefix = 'gwp_leftfolder_';
Admin.BasicSimpleCategories.prototype.rootTreeNodeID = 'gwp_leftfolder_0';
Admin.BasicSimpleCategories.prototype.checkboxNodeIDPrefix = 'chk_cid_gwp_leftfolder_';
Admin.BasicSimpleCategories.prototype.checkboxRightNodeIDPrefix = 'chk_cid_';

// --------------------------------- PROCEDURES -------------------------- //
///////////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.Run = function ()
{
	//Admin.BasicSimpleCategories.superclass.Run.call(this);
	YAHOO.util.Event.onDOMReady(this.initLayout);		
	this.currentCategory = YAHOO.util.Cookie.get(this.app_name + "_currentCategory"); 
	if (this.currentCategory == null) this.currentCategory = 0;

	if (document.getElementById(this.gekko_admin_sidebar_content) != null)
	{
		this.categoryTree = new YAHOO.widget.TreeView(this.gekko_admin_sidebar_content);
		this.getTreeListing();
	
		this.getItemsListing(this.currentCategory);
		this.assignButtons();
	}
}

///////////////////////////////////////////////////////////
// Dec 6, 2011 - for large dataset
Admin.BasicSimpleCategories.prototype.buildDynamicRequestURL = function(oState, oSelf)
{

	oState = oState || { pagination: null, sortedBy: null };
	var sortby = (oState.sortedBy) ? oState.sortedBy.key : "id";
	var dir = (oState.sortedBy && oState.sortedBy.dir === YAHOO.widget.DataTable.CLASS_DESC) ? "desc" : "asc";
	var startIndex = (oState.pagination) ? oState.pagination.recordOffset : 0;
	var perPage = (oState.pagination) ? oState.pagination.rowsPerPage : datatable_max_row_perpage;
	YAHOO.util.Cookie.set(app_name + "_current_start_" + YAHOO.util.Cookie.get(app_name + "_currentCategory"), startIndex);
	YAHOO.util.Cookie.set(app_name + "_sortby_" + YAHOO.util.Cookie.get(app_name + "_currentCategory"), sortby);
	YAHOO.util.Cookie.set(app_name + "_sortdir_" + YAHOO.util.Cookie.get(app_name + "_currentCategory"), dir);	

	return  "&dynamic=1&sortby=" + sortby + "&sortdirection=" + dir + "&start=" + startIndex + "&end=" + (startIndex + perPage);
};
///////////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.buildInitialRequestURL = function(oState, oSelf)
{
	var cookie_start_index = YAHOO.util.Cookie.get(app_name + "_current_start_" + YAHOO.util.Cookie.get(app_name + "_currentCategory"));
	var cookie_sortby = YAHOO.util.Cookie.get(app_name + "_sortby_" + YAHOO.util.Cookie.get(app_name + "_currentCategory"));
	var cookie_dir = YAHOO.util.Cookie.get(app_name + "_sortdir_" + YAHOO.util.Cookie.get(app_name + "_currentCategory"));
	oState = oState || { pagination: null, sortedBy: null };
	var sortby = (cookie_sortby) ? cookie_sortby : "id";
	var dir = (sortby && /*oState.sortedBy.dir*/cookie_dir === "desc") ? "desc" : "asc";
	var startIndex =  (cookie_start_index > 0) ? cookie_start_index : 0;
	var perPage = (oState.pagination) ? oState.pagination.rowsPerPage : datatable_max_row_perpage;
	return  "&dynamic=1&sortby=" + sortby + "&sortdirection=" + dir + "&start=" + startIndex + "&end=" + (startIndex + perPage);
};
///////////////////////////////////////////////////////////

Admin.BasicSimpleCategories.prototype.assignButtons = function()
{
   YAHOO.util.Event.addListener(document.getElementById('button_delete'), "click", this.editDelete, this, true); 
   YAHOO.util.Event.addListener(document.getElementById('button_copy'), "click", this.editCopy, this, true); 	   
   YAHOO.util.Event.addListener(document.getElementById('button_cut'), "click", this.editCut, this, true); 			
   YAHOO.util.Event.addListener(document.getElementById('button_paste'), "click", this.editPaste, this, true); 	
   YAHOO.util.Event.addListener(document.getElementById('gekko_admin_searchform'), "submit", this.searchItems, this, true); 	
}
///////////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.getItemsListingURL = function()
{
	var the_appname = this.app_name;
	if (the_appname == '') this.app_name = app_name; // bugfix hack
	
	var action = site_httpbase + _gekko_application_url + "&ajax=1&" + "action=getitemsbycategory&id=" + this.currentCategory;
	
	return action;
}
///////////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.getCategoryListingURL = function() {
	var action= "action=getallcategories";
	return action;
};

///////////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.reportAjaxSaveCategoryOperation = function(o)
{
	var saveResult = ajaxParse (o.responseText);
 	if (saveResult.status == "Save_OK")
	{
		var cat_field_id = document.getElementById(this.field_category_id);
		if (cat_field_id != null && saveResult.newcid != null)
		{
			cat_field_id.value = saveResult.newcid;
			alert('Category has been saved');
		}
			
	}
	return false;
}
///////////////////////////////////////////////////////////
Admin.BasicLinearData.prototype.ajaxSaveCategory = function()
{
	this.ajaxRequestPOSTForm(this.reportAjaxSaveCategoryOperation, 'ajaxsavecategory', this.form_category_editor);
}
///////////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.receiveCategoryArray = function(o)
{
	this.category_array = ajaxParse (o.responseText);
	this.buildTree();
	this.displayPath (this.currentCategory);
}
///////////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.getTreeListing = function()
{
	this.ajaxRequestGET( this.receiveCategoryArray,this.getCategoryListingURL());	
};
///////////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.getNodeByDataID = function (id) {
for (var i = 0; i < this.categoryNodes.length; i++)
	{
		if	(this.categoryNodes[i].data.id == id)
		{
			return this.categoryNodes[i];
		}
	}
	return null;
}
///////////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.formatShortcut = function(elCell, oRecord, oColumn, oData) {
	//alert (oRecord.toSource());
	var itemTitle = oData;
	var itemLink = '';
	var path_info = document.getElementById('gekko_admin_path_info').value;
	var filename = oRecord.getData('virtual_filename') ;
		if (path_info== '') path_info = '/';	
	if (oRecord.getData("cid") > 0)
	{
		itemLink =site_httpbase + '/' +app_name + path_info +  filename + '/';
	}
	else
	{

		itemLink =site_httpbase+ '/' + app_name + path_info + filename + '.html';	
		itemTitle = itemTitle + '.html';
	}
	/*	var previewIcon = "<IMG SRC=\"" + site_httpbase + "/admin/images/toolbars/firefox.png\" border=\"0\" align=\"absmiddle\" alt=\"Preview\" title=\"Preview\">";*/
		
		elCell.innerHTML =   '<div style="float:left">' + itemTitle +'</div>';
		elCell.innerHTML += '<div style="float:right;margin-left:1em">';// <A HREF="'+ itemLink+"\" onclick=\"javascript:return (confirm('Preview this item?'));\" target=_blank>"+ previewIcon + '</A></div>' ;
};

///////////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.formatEditLink = function(elCell, oRecord, oColumn, oData) {
	//alert (oRecord.toSource());
	var itemTitle = oData;
	var itemLink = '';
	if (oRecord.getData("cid") > 0)
	{
		if (itemTitle == '') itemTitle = '(Untitled)';
		var the_id = oRecord.getData("cid");
		var chdirLink = "javascript:gekko_app.changeDirectory("+ oRecord.getData("cid") + ")"; // Admin.BasicSimpleCategories.prototype.changeDirectory failed <<---- note  Jan 18, 2010
		var editIcon = "<img src=\"" + site_httpbase + "/images/default/trans.png\" alt=\"Edit this folder\" title=\"Edit this folder\" border=\"0\" id=\"home\" class=\"img_buttons16 imgsprite16_edit_folder\" />";
		itemLink = 'index.php?app=' + oColumn.app_name + '&action=editcategory&id=' + oRecord.getData("cid");
		elCell.innerHTML =  '<div style="float:left"><A  title="Click here to go into this category" HREF="'+chdirLink +'" id="'+the_id+'">'+ itemTitle + '</A></div>  <div style="float:right;margin-left:4em"><a title="Click here to edit this category" href="'+ itemLink+'">' + editIcon+ '</a></div>';
	}
	else
	{
		if (itemTitle == '') itemTitle = '(Untitled)';
		itemLink = 'index.php?app=' + oColumn.app_name + '&action=edititem&id=' + oRecord.getData("id");
		elCell.innerHTML =  '<a title="Click here to edit this item" href="'+ itemLink+'">'+ itemTitle + '</a>';		
	}

};
///////////////////////////////////////////////////////////

Admin.BasicSimpleCategories.prototype.drawTreeIcon = function(id,category_name)
{
	var tooltip = "";
	var cid = id.replace(this.treeNodeIDPrefix,'');
	if (cid != 0)
	{
		var editFolderLink = 'index.php?app=' + this.app_name + '&action=editcategory&id=' + cid;
		var txt_edit = "<img src=\"" + site_httpbase + "/images/default/trans.png\" alt=\"Edit this folder\" title=\"Edit this folder\" border=\"0\" id=\"home\" class=\"img_buttons16 imgsprite16_edit_folder\" /> Edit";
		var editFolder = '<a class="gekko_editfolder_link" href="' + editFolderLink + '">'+txt_edit+'</a>';
		var tooltip = "<span class=\"gekko_editfolder_tooltip\">"+editFolder+"</span>";
	}
	return "<div id='"+id+"' class='gekko_tree_folder'  >"+category_name+tooltip+"</div>\n\n";	
}

///////////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.buildTree = function( )
{
	this.categoryTree.removeNode(this.categoryTree.getRoot(),false);
	this.categoryTree.removeChildren(this.categoryTree.getRoot());
	this.categoryTree.destroy();
	// Build Root Node
	var category_name = this.app_name;
	var node_name = this.rootTreeNodeID;	
	var myobj = { label: category_name, id: 0  , html: this.drawTreeIcon(node_name,category_name)}; 
	var new_node = new YAHOO.widget.HTMLNode(myobj, this.categoryTree.getRoot(), true, false);
	this.categoryNodes[0] = new_node;		
	// Build the Rest
	for (var i = 0; i < this.category_array.length; i++) 
	{
		var category_name = this.category_array[i][this.field_category_title];
		var the_virtual_filename = '';
		if (this.category_array[i]['virtual_filename']) the_virtual_filename = this.category_array[i]['virtual_filename'];
		var category_id = this.category_array[i][this.field_category_id];
		var parent_id = 0;
		var node_name = this.treeNodeIDPrefix + category_id;
		if (category_name == "") category_name = "(Untitled)";
		var myobj = { virtual_filename: the_virtual_filename, label: category_name, id:category_id ,html: this.drawTreeIcon(node_name,category_name)};  
		new_node = new YAHOO.widget.HTMLNode(myobj, this.getNodeByDataID(0), true, true);

		if (!this.disableDragDrop) new Admin.DragDrop(node_name);
		
		this.categoryNodes[i+1] = new_node;
	}
	this.categoryTree.subscribe("clickEvent",this.clickTree, this, true);  // scope correction with this, true - Feb  21, 2009
	this.categoryTree.draw();
};
///////////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.displayPath = function (category_id)
{
	var node = this.getNodeByDataID(category_id);
	var str_path = "/";
	if (node != null) str_path = node.data.label;
	var div = document.getElementById(this.gekko_admin_current_path);
	if (div) div.innerHTML =  "&raquo; /" +	str_path;
	
}

///////////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.clickTree = function (htmlobject)
{
	
	var category_id = htmlobject.node.data.id;
	var searchString = document.getElementById('searchbox');
	if (searchString) searchString.value = 'search...';
	
	this.changeDirectory(category_id);
	return false;
}
///////////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.changeDirectory = function (category_id)
{
//	alert(app_name);
  	this.displayPath(category_id);
 	this.currentCategory = category_id;
	YAHOO.util.Cookie.set(app_name + "_currentCategory", this.currentCategory); // <----------- Fix app_name to this.app_name no global Jan 18, 2010
	this.getItemsListing(category_id);
}
///////////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.reportPOSTOperation = function(o)
{
	Admin.BasicSimpleCategories.superclass.reportPOSTOperation.call (this,o);
	this.getTreeListing();
}
/////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.moveSelectedItems = function (items_to_move,destination)
{
	if (destination.search("i") > 0) alert("You cannot set an item as a target for a move operation!"); else
	{
		var postData = "items=" +items_to_move + "&destination=" + destination;
		this.ajaxRequestPOST(this.reportPOSTOperation, 'move', postData);
	}
}
/////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.copySelectedItems = function (items_to_move,destination)
{
	if (destination.search("i") > 0) alert("You cannot set an item as a target for a copy operation!"); else
	{
		var postData = "items=" +items_to_move + "&destination=" + destination;
		this.ajaxRequestPOST(this.reportPOSTOperation, 'copy', postData);
	}
}
/////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.editCut = function ()
{
	this.bufferContent = this.getSelectedItems();
	this.bufferAction = 'cut';
}
/////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.editPaste = function ()
{
	if (this.bufferAction == 'cut' && this. bufferContent != '')
	{
		this.moveSelectedItems (this.bufferContent,'c' + this.currentCategory);
		this.bufferContent = ''; // empty the buffer
		this.bufferAction = '';
	} else if (this.bufferAction == 'copy' && this. bufferContent != '')
	{
		this.copySelectedItems (this.bufferContent,'c' + this.currentCategory);
		this.bufferContent = ''; // empty the buffer
		this.bufferAction = '';
	}
}
///////////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.formatIcon = function(elCell, oRecord, oColumn, oData) {
			
	var odata_id = oRecord.getData("id");
	var odata_cid = oRecord.getData("cid");	
	var the_id = "i" + odata_id;
	var the_number = odata_id;
	var class_name = "gekko_datatable_icon_item";
        var hint;
    if (!this.disableDragDrop) hint = '" title="Drag this icon to move around the item"';
	
	if (odata_cid > 0)
	{
		 class_name = "gekko_datatable_icon_category";
		 if (!this.disableDragDrop) hint = '" title="Drag this icon to move around the category"';
		 
		 the_id = "c" + odata_cid;
		 the_number = odata_cid;
	} 
	
	elCell.innerHTML = '<div class="' + class_name +'" id="'+ the_id +'"'+hint+'>' + the_number + '</div>';
	if (!this.disableDragDrop) new Admin.DragDrop(the_id);			
};

///////////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.buildDataSourceURL = function() // Dec 13
{
	var searchbox = document.getElementById('searchbox');
	var searchString = '';
	var action = '';
	
	if (searchbox) searchString = document.getElementById('searchbox').value;
	
	if (searchString != 'search...' && searchString != '')
	{
		action = site_httpbase + this._gekko_application_url + "&ajax=1&" + "action=search&keyword=" + searchString;
		this.displaySearchResultInPathBar(searchString); // fix this 10 please
	}
	else
	{
		action = site_httpbase + this._gekko_application_url + "&ajax=1&" + "action=getitemsbycategory&id=" + this.currentCategory;
		this.displayPath (this.currentCategory);

	}
	return action;
}
///////////////////////////////////////////////////////////
Admin.BasicSimpleCategories.prototype.buildDataColumnDefinitionAndResponseSchema = function()
{
	alert('You must write your own buildDataColumnDefinitionAndResponseSchema!');
}
/*

        .)/     )/,
         /`-._,-'`._,@`-,
  ,  _,-=\,-.__,-.-.__@/
 (_,'    )\`    '(`


  
*/
///////////////////////////////////////////////////////////
// --------------------------------- CONSTRUCTOR ------------------------- //
Admin.BasicNestedCategories = function (theapp_name, theapp_description, field_id, field_item_title, field_category_id, field_category_title, field_parent_id)
{
	Admin.BasicNestedCategories.superclass.constructor.call(this,theapp_name, theapp_description, field_id, field_item_title);
	this.field_parent_id = (field_parent_id == null) ? 'parent_id' : field_parent_id;;
	this.dragArray = [];
};
///////////////////////////////////////////////////////////
$extend_class(Admin.BasicNestedCategories, Admin.BasicSimpleCategories);
///////////////////////////////////////////////////////////

// --------------------------------- VARIABLES ------------------------------- //
Admin.BasicNestedCategories.prototype.field_parent_id  = '';
///////////////////////////////////////////////////////////
Admin.BasicNestedCategories.prototype.Run = function ()
{
	Admin.BasicNestedCategories.superclass.Run.call(this);
}

// --------------------------------- PROCEDURES -------------------------- //
///////////////////////////////////////////////////////////
Admin.BasicNestedCategories.prototype.buildTree = function( )
{
	this.categoryTree.removeNode(this.categoryTree.getRoot(),false);
	this.categoryTree.removeChildren(this.categoryTree.getRoot());
	this.categoryTree.destroy();
	if (this.dragArray.length != 0)
	{// prevent memory leak?
		var existing_drag_count = this.dragArray.length;
		if (existing_drag_count != 0)
		{
			for (var x=0;i < existing_drag_count;y++)
			{
				var obj_self = this.dragArray[x];
				if (obj_self) obj_self.destroy();
				obj_self = null;
			}
			this.dragArray = null;
		}
	}// end memory leak prevention */
	this.dragArray = new Array();
	// Build Root Node
	var category_name = this.app_name;
	var node_name = this.rootTreeNodeID;
	var myobj = { label: category_name, id: 0  , html: this.drawTreeIcon(node_name,category_name)}; 
	new_node = new YAHOO.widget.HTMLNode(myobj, this.categoryTree.getRoot(), true, false);
	this.categoryNodes[0] = new_node;		
	if (!this.disableDragDrop) new Admin.DragDrop(node_name);
	 
	 
	for (var i = 0; i < this.category_array.length; i++) 
	{
		var category_name = this.category_array[i][this.field_category_title];
		var category_id = this.category_array[i][this.field_category_id];	
		var the_virtual_filename = '';
		if (this.category_array[i]['virtual_filename']) the_virtual_filename = this.category_array[i]['virtual_filename'];
		if (category_name == "") category_name = "(Untitled)";		
		var parent_id = this.category_array[i]['parent_id'];
		if (!parent_id) parent_id = 0;
		var node_name =  this.treeNodeIDPrefix + category_id;
		var myobj = { virtual_filename: the_virtual_filename, label: category_name, id:category_id , html: this.drawTreeIcon(node_name,category_name)}; 
		if (parent_id == 0)
		{
			new_node = new YAHOO.widget.HTMLNode(myobj, this.categoryTree.getRoot(), true, true);
		}
		else
		{
			new_node = new YAHOO.widget.HTMLNode(myobj, this.getNodeByDataID(this.category_array[i]['parent_id']), true, true);
			new_node.collapse();
		}
		if (!this.disableDragDrop) this.dragArray[i] = new Admin.DragDrop(node_name);
		this.categoryNodes[i] = new_node;
	}
	this.categoryTree.subscribe("clickEvent",this.clickTree, this, true);  // scope correction with this, true - Feb  21, 2009
	this.categoryTree.draw();
};
///////////////////////////////////////////////////////////
Admin.BasicNestedCategories.prototype.getPathByCategoryID = function (category_id)
{
	var node = this.getNodeByDataID(category_id);
	var str_path = "";
	if (node == null)
	{
		str_path = "";	
	} else	
	{
		if (node != undefined) while (node.depth >= 0) // fixed Jan 21, 2010
		{	
			if (node.data.virtual_filename)
			{
				str_path = node.data.virtual_filename + "/" + str_path;
			} else
			{
				str_path = node.data.label + "/" + str_path;
			}
			node = node.parent;
		}
	}
	return str_path;
}
/////////////////////////////////////////////////////////////
Admin.BasicNestedCategories.prototype.displayPath = function (category_id)
{
	var str_path = this.getPathByCategoryID(category_id);
	var div = document.getElementById(this.gekko_admin_current_path);	
	if (div !=null) div.innerHTML = "&raquo; /" +str_path;	
	var path_info = document.getElementById('gekko_admin_path_info');
	if (div != null) path_info.value = '/'+ str_path;
}


///////////////////////////////////////////////////////////
//--------------------------------- CONSTRUCTOR ------------------------- //
Admin.BasicMultipleCategories = function (theapp_name, theapp_description, field_id, field_item_title, field_category_id, field_category_title, field_parent_id)
{
	this.disableDragDrop = true;
	if (document.getElementById('gekko_multiple_categories_checkboxes')) this.alternative_mode = true; else this.alternative_mode = false;	
	Admin.BasicMultipleCategories.superclass.constructor.call(this,theapp_name, theapp_description, field_id, field_item_title, field_category_id, field_category_title, field_parent_id);
};
///////////////////////////////////////////////////////////
$extend_class(Admin.BasicMultipleCategories, Admin.BasicNestedCategories);
///////////////////////////////////////////////////////////

Admin.BasicMultipleCategories.prototype.Run = function ()
{
	
	if (this.alternative_mode)
	{
		
		this.categoryTree = new YAHOO.widget.TreeView('gekko_multiple_categories_checkboxes');
		this.getTreeListing();
		this.getItemCategoriesListing();
	} else Admin.BasicMultipleCategories.superclass.Run.call(this);
}

///////////////////////////////////////////////////////////
Admin.BasicMultipleCategories.prototype.receiveItemCategoriesArray = function(o)
{
	this.itemcategoriesarray = ajaxParse (o.responseText);
	for (var i = 0; i < this.itemcategoriesarray.length;i++)
	{
		var chk_id = this.checkboxNodeIDPrefix +this.itemcategoriesarray[i].cid;
		var checkbox = document.getElementById(chk_id);
		if (checkbox) checkbox.checked = true; // null or not?
	}
}

///////////////////////////////////////////////////////////
Admin.BasicMultipleCategories.prototype.getItemCategoriesListing = function()
{
	var id = document.getElementById('id').value;
	this.ajaxRequestGET( this.receiveItemCategoriesArray,"action=getitemcategories&id=" + id);	
};

///////////////////////////////////////////////////////////
Admin.BasicMultipleCategories.prototype.reportCategoryAssociation = function(o)
{
	var response = ajaxParse (o.responseText);
	if (response != true) alert('Cannot set the associated category')
}
///////////////////////////////////////////////////////////

Admin.BasicMultipleCategories.prototype.clickTree = function (htmlobject)
{
	if (this.alternative_mode) 
	{		
		var category_id = htmlobject.node.data.id;
		var leaf_id = this.checkboxNodeIDPrefix + category_id;
		var state = document.getElementById(leaf_id).checked;
		var id = document.getElementById('id').value; 

		if(id > 0 && category_id >=0)
		{
	 		var postData = "cid=" +category_id + "&id=" + id + "&state=" + state;
			this.ajaxRequestPOST(this.reportCategoryAssociation, 'setcategory', postData);
		}
		return false;
	}
	else return  Admin.BasicMultipleCategories.superclass.clickTree.call(this,htmlobject);
}
///////////////////////////////////////////////////////////
Admin.BasicMultipleCategories.prototype.drawTreeIcon = function(id,category_name)
{
	if (this.alternative_mode)
		return "<div id='"+id+"' class='gekko_tree_folder'>" +
			   "<label><input type='checkbox' name='categories[]' class='gekko_items_categoryeditor_checkbox' id='chk_cid_"+id+"' />"+
			   category_name+"</label></div>\n\n"
	else
		return  Admin.BasicMultipleCategories.superclass.drawTreeIcon.call(this,id,category_name);	 
}

