//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
var app_name =  "main";

// ******************************************************************************************************************************************** 
Admin.Main = function ()
{
    this.constructor.superclass.constructor.call(this, app_name);
};

///////////////////////////////////////////////////////////
$extend_class(Admin.Main, Admin.Basic);
///////////////////////////////////////////////////////////
Admin.Main.prototype.createIFrame = function(inside_element,src)
{
//doesn't block the load event
  var i = document.createElement("iframe");
  i.src = src;
  i.scrolling = "auto";
  i.frameborder = "0";
  i.width = "100%";
  i.height = "350px";
  i.style.border = "none";
  document.getElementById(inside_element).appendChild(i);
}
///////////////////////////////////////////////////////////
Admin.Main.prototype.Start = function()
{
	gekko_app = new Admin.Main (); 
	gekko_app.Run();
	gekko_app.createIFrame('tab2','index.php?app=main&action=externalrss&source=bbgknews');
	gekko_app.createIFrame('tab3','index.php?app=main&action=externalrss&source=bbgkext');	
}

$onload(Admin.Main.prototype.Start);

