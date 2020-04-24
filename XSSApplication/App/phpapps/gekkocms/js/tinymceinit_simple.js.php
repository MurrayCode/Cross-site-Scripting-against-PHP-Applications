<?php 
error_reporting(E_ERROR | E_PARSE | E_COMPILE_ERROR  | E_CORE_ERROR );
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
	mode : "textareas",
	theme : "advanced",
    skin : "babygekko",
	width : "640",
	plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras",

	document_base_url : "<?php echo SITE_HTTPBASE.'/'; ?>",
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,|,pasteword,|,search,replace,|bullist,numlist,|,outdent,indent,blockquote,|,forecolor,backcolor",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
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
		title: "Baby Gekko File Manager",
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
