///////////////////////////////////////////////////////////
// ******************************************************************************************************************************************** 
Admin.GenericEditor = function (class_name)
{
    this.constructor.superclass.constructor.call(this,class_name);
};

///////////////////////////////////////////////////////////
$extend_class(Admin.GenericEditor, Admin.BasicSimpleCategories);
///////////////////////////////////////////////////////////
Admin.GenericEditor.prototype.Start() = function()
{
    gekko_editor_app = new Admin.GenericEditor (app_name,''); 
}

///////////////////////////////////////////////////////////

$onload(Admin.GenericEditor.prototype.Start);
