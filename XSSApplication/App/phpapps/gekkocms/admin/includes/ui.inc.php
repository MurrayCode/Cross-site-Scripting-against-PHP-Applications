<?php
/*
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
*/
function editor_button_apply($mode)
{
    $mode = ucfirst ($mode);
	echo '<button name="btn_apply" class="gekko_standard_editor_button" onclick="javascript:gekko_editor_app.ajaxSave'.$mode.'();return false;"><img class="img_buttons32   imgsprite32_medbutton_apply" src="'.TRANS_SPRITE_IMAGE.'" align="absmiddle" />Apply</button>';
}
function editor_button_save()
{
	
    
	echo '<button type="submit" name="btn_save" class="gekko_standard_editor_button" value="Submit"><img class="img_buttons32 imgsprite32_medbutton_ok" src="'.TRANS_SPRITE_IMAGE.'" align="absmiddle" />Save</button>';
}


function editor_button_ok()
{
	echo '<button type="submit" name="btn_ok" class="gekko_standard_editor_button" value="Submit"><img class="img_buttons32 imgsprite32_medbutton_ok" src="'.TRANS_SPRITE_IMAGE.'" align="absmiddle" />OK</button>';
}

function editor_button_cancel($nowarning=false)
{
	
    
	echo '<button type="reset" name="clear" class="gekko_standard_editor_button" value="Clear" onclick="javascript:gekko_editor_cancel_edit('.$nowarning.');"><img class="img_buttons32  imgsprite32_medbutton_cancel" src="'.TRANS_SPRITE_IMAGE.'"  align="absmiddle" />Cancel</button>';
}

function editor_button_revert()
{
	echo '<button type="reset" name="clear" class="gekko_standard_editor_button" value="Clear" onclick="javascript:return gekko_editor_revert_confirmation();"><img class="img_buttons48 imgsprite32_medbutton_revert" src="'.TRANS_SPRITE_IMAGE.'" align="absmiddle" />Revert</button>';
}


function big_editor_button_ok()
{
	echo '<button type="submit" name="btn_ok" class="gekko_larger_editor_button" value="Submit"><img class="img_buttons48 imgsprite48_bigbutton_ok" src="'.TRANS_SPRITE_IMAGE.'"  align="absmiddle" />OK</button>';
}

function big_editor_button_cancel($nowarning=false)
{
	echo '<button type="reset" name="clear" class="gekko_larger_editor_button" value="Clear" onclick="javascript:gekko_editor_cancel_edit('.$nowarning.');"><img class="img_buttons48 imgsprite48_bigbutton_cancel" src="'.TRANS_SPRITE_IMAGE.'" width="48" height="48" align="absmiddle" />Cancel</button>';
}

function big_editor_button_revert()
{
	echo '<button type="reset" name="clear" class="gekko_larger_editor_button" value="Clear" onclick="javascript:return gekko_editor_revert_confirmation();"><img class="img_buttons48 imgsprite48_bigbutton_revert" src="'.TRANS_SPRITE_IMAGE.'" align="absmiddle" />Revert</button>';
}



function date_editor_calendar_button($s)
{
	echo '<img src="'.SITE_HTTPBASE.'/admin/images/minicalendar.png" border="0" onclick="javascript:Gekko.DateTimePicker.showCalendar(\''.$s.'\');" />';	
}

function date_editor_with_calendar_input($s,$value)
{
	echo "<input type=\"text\" name=\"{$s}\" id=\"{$s}\"  class=\"gekko_editor_input\" value=\"{$value}\" />";
	date_editor_calendar_button($s);
}

function uninstall_button_ok()
{
	echo '<button type="submit" name="submit" class="gekko_larger_editor_button" id="ok_uninstall_button" value="Submit" disabled="disabled"><img class="img_buttons48  imgsprite48_bigbutton_ok" src="'.TRANS_SPRITE_IMAGE.'" align="absmiddle" />'.TXT_OK.'</button>';
	
}

function uninstall_button_cancel($nowarning=false)
{
	echo ' <button type="reset" name="clear" class="gekko_larger_editor_button" value="Clear" onclick="javascript:gekko_editor_cancel_return_to_main_app(1);"><img class="img_buttons48  imgsprite48_bigbutton_cancel" src="'.TRANS_SPRITE_IMAGE.'" width="48" height="48" align="absmiddle" />'.TXT_CANCEL.'</button>';
}

function toolbar_button($commandid,$buttonimg,$text,$link='#',$title='',$target='')
{
	$txt = IMG(TRANS_SPRITE_IMAGE,htmlspecialchars($text), 16, 16, 0, '',"img_buttons16 imgsprite16_{$buttonimg}");
	echo A($txt.$text, $link,$commandid,'',$target,$title)."\n";
}

function get_css_sprite_img($size,$id,$imgid='',$alt='',$style='')
{
	if ($alt == '') $alt = $id;
	if ($imgid == '') $imgid = $id;
	return IMG(TRANS_SPRITE_IMAGE,$alt, $size, $size, 0, $imgid,"img_buttons{$size} imgsprite{$size}_{$id}",$style);	
}

function toolbar_searchbox()
{
echo '<input name="searchword" id="searchbox" class="input_searchbox" alt="'.TXT_SEARCH.'" type="text" size="20" value="'.TXT_SEARCH_TEXT_IN_BOX.'"  onblur="if(this.value==\'\') this.value=\''. TXT_SEARCH_TEXT_IN_BOX.'\';" onfocus="if(this.value==\''.TXT_SEARCH_TEXT_IN_BOX.'\') this.value=\'\';" />'."\n";
}

function display_generic_admin_main_form($title,$toolbars='',$leftcontent='',$rightcontent='')
{
	echo '<div id="gekko_admin_sidebar"> 
<h3>'.$title.'</h3>
<div id="gekko_admin_sidebar_content"></div>
</div>
<!-- end sidebar -->
<div id="gekko_admin_main"> 
	<div class="gekko_admin_panel">
   <form id="gekko_admin_searchform" method="GET" action="#" onsubmit="return false" >'.$toolbars.'</form>
  <p id="gekko_admin_current_path"></p><input type="hidden" id="gekko_admin_path_info"></div>
<div id="gekko_admin_main_content"></div>
</div>';
}

?>
