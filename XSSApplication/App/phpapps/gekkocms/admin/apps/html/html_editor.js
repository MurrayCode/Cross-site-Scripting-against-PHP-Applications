var app_name =  "html";
var app_description = "htmleditor"
///////////////////////////////////////////////////////////
// ******************************************************************************************************************************************** 
Admin.HTMLPagesEditor = function (class_name)
{
    this.constructor.superclass.constructor.call(this,class_name);
};

///////////////////////////////////////////////////////////
$extend_class(Admin.HTMLPagesEditor, Admin.BasicNestedCategories);
/*
Admin.HTMLPagesEditor.prototype.getListOfUsers = function()
{
    var oDS = new YAHOO.util.XHRDataSource("/admin/index.php?app=" + this.app_name + "&ajax=1&action=getlistofusers");
    // Set the responseType
    oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
    // Define the schema of the delimited results
	
    oDS.responseSchema = {
        recordDelim: "\n",
        fieldDelim: "\t"
    };
    // Enable caching
    oDS.maxCacheEntries = 5;

    // Instantiate the AutoComplete
    var oAC = new YAHOO.widget.AutoComplete("username", "username_dropdown", oDS);
    oAC.generateRequest = function(sQuery) {
        return "&query=" + sQuery;
    };    
    return {
        oDS: oDS,
        oAC: oAC
    };
}*/

///////////////////////////////////////////////////////////
Admin.HTMLPagesEditor.prototype.Start = function()
{
	gekko_editor_app = new Admin.HTMLPagesEditor (app_name, 'HTML Editor'); 
	gekko_editor_app.Run();
}

///////////////////////////////////////////////////////////
$onload(Admin.HTMLPagesEditor.prototype.Start);

