<?php 
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_COMPILE_ERROR  | E_CORE_ERROR );
header('Content-type: text/javascript; charset=UTF-8'); 
include ('../config.inc.php'); 

?>
if (typeof tinyMCE_GZ != "undefined") 
{
tinyMCE_GZ.init({
      themes : "advanced",
      plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,safari,inlinepopups",
      languages : "en",
      disk_cache : true
   });
}
tinyMCE.init({
	// General options
	button_tile_map : true,    
	mode : "textareas",
	theme : "advanced",
    skin : "babygekko",
//    skin_variant : "black",
	width : "640",
    height: "400",
	plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras",

	document_base_url : "<?php echo SITE_HTTPBASE.'/'; ?>",
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,|,pasteword,|,search,replace",
	theme_advanced_buttons2 : "bullist,numlist,|,outdent,indent,blockquote,|,forecolor,backcolor,|undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,media,advhr,|fullscreen",
	//theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
	relative_urls : false,

   forced_root_block : false,
   force_p_newlines : false,
	// Example content CSS (should be your site CSS)
	content_css : site_template + "/editor.css",

	external_link_list_url : site_httpbase + "/js/tinymce_file_list.php",
	external_image_list_url : site_httpbase + "/js/tinymce_image_list.php",
	external_media_list_url : site_httpbase + "/js/tinymce_image_list.php",    
	file_browser_callback : "filebrowser"    
	// Replace values for the template plugin
});

function filebrowser(field_name, url, type, win) {
		
	fileBrowserURL = site_httpbase + "/admin/index.php?app=bbgkmediamanager";
			
	tinyMCE.activeEditor.windowManager.open({
		title: "Gekko File Manager",
		url: fileBrowserURL,
		width: 950,
		height: 650,
		inline: 1,
		maximizable: 1,
		close_previous: 0
	},{
		window : win,
		input : field_name
	});		
}
