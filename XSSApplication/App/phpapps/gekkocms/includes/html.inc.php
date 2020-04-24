<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
	global $defaultHTTPErrorMessages;
	$defaultHTTPErrorMessages = array(
	401 => array('header' => 'HTTP/1.0 401 Unauthorized', 'pagetitle' => '401 - Unauthorized'),
	403 => array('header' => 'HTTP/1.0 403 Forbidden', 'pagetitle' => '403 - Access denied'),
	404 => array('header' => 'HTTP/1.0 404 Not Found', 'pagetitle'=> '404 - Page not found'),
	500 => array('header' => 'HTTP/1.0 500 Internal Server Error', 'pagetitle' =>'500 - Internal Server Error')
	); 
	

	function SAFE_HTML($s)
	{
		return htmlentities($s, ENT_QUOTES, "UTF-8");//		htmlspecialchars($s, ENT_QUOTES);	
	}
	
	function META($key,$value)
	{
		$key = SAFE_HTML($key);
		$value = SAFE_HTML($value);
		return "<meta name=\"{$key}\" content=\"{$value}\" />\n";
	}
	//_________________________________________________________________________//
	function JAVASCRIPT_TEXT()
	{
		$js = '';
		$totalcount = func_num_args();
		$srcs = func_get_args();
		for ($i = 0; $i < $totalcount; $i++) $js.="<script type=\"text/javascript\">\n{$srcs[$i]}\n</script>\n";
		return $js;
	}
	
	//_________________________________________________________________________//
	function JAVASCRIPT_EXTERNAL()
	{
		$js = '';
		$totalcount = func_num_args();
		$filenames = func_get_args();
		for ($i = 0; $i < $totalcount; $i++) $js.="<script type=\"text/javascript\" src=\"{$filenames[$i]}\"></script>\n";
		return $js;
	}
	//_________________________________________________________________________//
	function JAVASCRIPT()
	{
		
		
		$js = '';
		$totalcount = func_num_args();
		$filenames = func_get_args();
		for ($i = 0; $i < $totalcount; $i++) $js.="<script type=\"text/javascript\" src=\"".SITE_HTTPBASE."{$filenames[$i]}\"></script>\n";
		return $js;
	}
	//_________________________________________________________________________//
	function JAVASCRIPT_YUI2_COMBO()
	{
		$yui = ( defined('SEF_ENABLED') && SEF_ENABLED==true) ? 'yui_combo.js' : 'js_gzip.php?js=yui_combo';
 		$js ="<script type=\"text/javascript\" src=\"".SITE_HTTPBASE."/js/{$yui}\"></script>\n";
		return $js; 
	}	
	//_________________________________________________________________________//
	function JAVASCRIPT_GEKKO()
	{
 		$js ="<script type=\"text/javascript\" src=\"".SITE_HTTPBASE."/js/gekkoz.js\"></script>\n";
		return $js; 
	}	
	//_________________________________________________________________________//
	function JAVASCRIPT_GEKKO_ADMIN()
	{
 		$js ="<script type=\"text/javascript\" src=\"".SITE_HTTPBASE."/admin/js/admin.js\"></script>\n";
		return $js; 
	}	
	
	//_________________________________________________________________________//
	function JAVASCRIPT_YUI2_MINIUTIL()
	{
		$yui_mini = ( defined('SEF_ENABLED') && SEF_ENABLED==true) ? 'yui_mini_utilities.js' : 'js_gzip.php?js=yui_mini_utilities';		
 		$js ="<script type=\"text/javascript\" src=\"".SITE_HTTPBASE."/js/{$yui_mini}\"></script>\n";
		return $js; 
	}	
	
	//_________________________________________________________________________//
	function INIT_TEXTAREA_EDITOR()
	{
		echo JAVASCRIPT('/js/tiny_mce/tiny_mce_gzip.js','/js/tinymceinit.js.php');		
	}
	//_________________________________________________________________________//
	function INIT_TEXTAREA_EDITOR_SIMPLE()
	{
		echo JAVASCRIPT('/js/tiny_mce/tiny_mce_gzip.js','/js/tinymceinit_simple.js.php');		
	}
	
	//_________________________________________________________________________//
	function INIT_REGULAR_EDITOR()
	{
		//echo JAVASCRIPT_GEKKO();		
	}
	//_________________________________________________________________________//
	function JAVASCRIPT_YUI()
	{
		$js.="<script type=\"text/javascript\" src=\"".SITE_HTTPBASE."/js/yui_combo.js\"></script>\n";
		return $js; 
	}
	//_________________________________________________________________________//
	function CSS($filename)
	{
		return "<link type=\"text/css\" href=\"".SITE_HTTPBASE."{$filename}\" rel=\"stylesheet\" />\n";
	}
	//_________________________________________________________________________//
	function CSS_EXTERNAL($filename)
	{
		return "<link type=\"text/css\" href=\"{$filename}\" rel=\"stylesheet\" />\n";
	}
	//_________________________________________________________________________//
	function TITLE($s)
	{
		return "<title>$s</title>\n";
	}
	//_________________________________________________________________________//
	function H1($s,$id='',$class='')
	{
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";
		return "<h1{$id_str}{$class_str}>$s</h1>";
	}
	//_________________________________________________________________________//
	function H2($s,$id='',$class='')
	{
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";
		return "<h2{$id_str}{$class_str}>$s</h2>";
	}
	//_________________________________________________________________________//
	function H3($s,$id='',$class='')
	{
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";
		
		return "<h3{$id_str}{$class_str}>$s</h3>";
	}
	//_________________________________________________________________________//
	function H4($s,$id='',$class='')
	{
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";
		
		return "<h4{$id_str}{$class_str}>$s</h4>";
	}
	//_________________________________________________________________________//
	function H5($s,$id='',$class='')
	{
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";
		
		return "<h5{$id_str}{$class_str}>$s</h5>";
	}
	//_________________________________________________________________________//
	function H6($s,$id='',$class='')
	{
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";		
		return "<h6{$id_str}{$class_str}>$s</h6>";
	}
	//_________________________________________________________________________//
	function BR()
	{
		return "<br />";
	}
	//_________________________________________________________________________//
	function HR($id='',$class='')
	{
		return "<hr id='{$id}' class='{$class}' />";
	}
	//_________________________________________________________________________//
	function IMG($src,$alt, $width=0, $height=0, $border=0, $id='', $class='', $style='')
	{
		if ($width)
		$txtwidth = " width=\"{$width}\" ";
		if ($height)
		$txtheight = " height=\"{$height}\" ";
		if ($style)
		$txtstyle = " style=\"{$style}\" ";
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";
		
		return "<img src=\"$src\" alt=\"{$alt}\" title=\"{$alt}\" {$txtwidth} {$txtheight} border=\"{$border}\"{$id_str}{$class_str} {$txtstyle} />";
	}
	//_________________________________________________________________________//
	function P($s)
	{
		return "<p>$s</p>";
	}
	//_________________________________________________________________________//
	function LI($s, $class='')
	{
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		return "<li{$class_str}>$s</li>\n";
	}
	//_________________________________________________________________________//
	function A($s, $link, $id='', $class='', $target='',$title='')
	{
		
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";
		$title_str = (empty($title)) ? '' :  " title=\"".SAFE_HTML($title)."\"";
		$target_str = (empty($target)) ? '' :  " target=\"{$target}\"";

		return "<a href=\"{$link}\"{$id_str}{$class_str}{$target_str}{$title_str}>{$s}</a>";
	}
	//_________________________________________________________________________//
	function SPAN($s, $id='', $class='')
	{
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";
		return "<span{$id_str}{$class_str}>$s</span>";
	}
	
	//_________________________________________________________________________//
	function DIV_start($id='', $class='')
	{
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";
		return "<div{$id_str}{$class_str}>\n";
	}
	//_________________________________________________________________________//
	function DIV_end()
	{
		return "</div>\n\n";
	}
	//_________________________________________________________________________//
	function UL_start($id='', $class='')
	{
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";
		return "<ul{$id_str}{$class_str}>\n";
	}
	
	//_________________________________________________________________________//
	function UL_end()
	{
		return "</ul>\n";
	}
	//_________________________________________________________________________//	
	function FIELDSET($legend='',$content, $id='', $class='')
	{
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";
		if (!empty($legend)) $legend_str = "<legend>{$legend}</legend>"; else $legend_str = '';
		return "<fieldset{$id_str}{$class_str}>{$legend_str}\n{$content}\n</fieldset>";
	}
	//_________________________________________________________________________//	
	function FIELDSET_start($id='', $class='')
	{
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";
		return "<fieldset{$id_str}{$class_str}>";
	}
	//_________________________________________________________________________//
	function FIELDSET_end()
	{
		return "</fieldset>";
	}
	//_________________________________________________________________________//
	function LEGEND($legend)
	{
		if (!empty($legend)) return "<legend>{$legend}</legend>"; else return '';
	}
	
	//_________________________________________________________________________//
	
	function TABLE_start($id='',$class='')
	{
		$id_and_class_str = get_id_and_class_string($id,$class);
        return "<table{$id_and_class_str}>";
	}
	//_________________________________________________________________________//

	function TR($content,$id='',$class='')
	{
		$id_and_class_str = get_id_and_class_string($id,$class);
        return "<tr{$id_and_class_str}>{$content}</tr>\n";
	}
	//_________________________________________________________________________//

	function TH($content,$id='',$class='')
	{
		$id_and_class_str = get_id_and_class_string($id,$class);
        return "<th{$id_and_class_str}>{$content}</th>\n";
		
	}
	
	//_________________________________________________________________________//

	function TD($content,$id='',$class='')
	{
		$id_and_class_str = get_id_and_class_string($id,$class);
        return "<td{$id_and_class_str}>{$content}</td>\n";
		
	}
	//_________________________________________________________________________//

	function TABLE_end()
	{
		return "</table>\n";	
	}
	//_________________________________________________________________________//
	
	function get_id_and_class_string($id,$class)
	{
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";
		return $class_str.$id_str;
	}
	
	//_________________________________________________________________________//
	function INPUT_SINGLECHECKBOX ($inputname,$value, $checked=false, $readonly=false,$id='', $class='')
	{
		$id_and_class_str = get_id_and_class_string($id,$class);		
		$check_str  = " value=\"{$value}\" ";
		if ($checked == true) $check_str.=' checked';
		return "<input name=\"{$inputname}\" type=\"checkbox\" {$id_and_class_str}{$check_str} />";
	}
	//_________________________________________________________________________//
	function INPUT_SINGLERADIOBOX ($inputname,$value, $checked=false, $readonly=false,$id='', $class='')
	{
		$id_and_class_str = get_id_and_class_string($id,$class);		
		$check_str  = " value=\"{$value}\" ";
		if ($checked == true) $check_str.=' checked';
		return "<input name=\"{$inputname}\" type=\"radio\" {$id_and_class_str}{$check_str} />";
	}
	
	//_________________________________________________________________________//	
	function INPUT_RADIOBOX($inputname,$question,$answer,$legend='',$readonly=false)
	{
		
		$total_count = count ($question);
		$item_per_column = $total_count / 2;
		$str = '';
		for ($i = 0; $i < $total_count; $i++)
		{
			$checked = ( isset($answer) && ($answer == $question[$i]['value']));
			$checkbox = INPUT_SINGLERADIOBOX ($inputname,$question[$i]['value'],$checked);
			$str.=  LABEL($question[$i]['label'],$checkbox,false);
			$str.= '<br/>';
		}
		if (!empty($legend)) $str = FIELDSET($legend,$str);
		return $str;
	}	
	//_________________________________________________________________________//	
	function INPUT_DROPDOWN($inputname,$question,$answer,$legend='',$readonly=false,$id='', $class='')
	{
		$total_count = count ($question);
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";
		
		$str = "<select name=\"{$inputname}\" {$id_str}{$class_str}>";
		for ($i = 0; $i < $total_count; $i++)
		{
			$checked = ( ($answer) && ($answer == $question[$i]['value']));
			if ($checked) $check_str = ' selected '; else $check_str = '';
			$option_value = $question[$i]['value'];
			$option_label = $question[$i]['label'];
			$str.=" <option value=\"{$option_value}\"{$check_str}>{$option_label}</option>";
		}
		$str.='</select>';
		if (!empty($legend)) $str = LABEL($legend,$str);		
		return $str; 
	}	
	
	//_________________________________________________________________________//	
	function INPUT_MULTIPLECHECKBOX($inputname,$question,$answer,$legend='',$readonly=false)
	{
		
		$total_count = count ($question);
		$item_per_column = $total_count / 2;
		$str = '';
		if ($total_count < 5)
		{
			for ($i = 0; $i < $total_count; $i++)
			{
				$checked = ($answer && array_search($question[$i]['value'],$answer)!==false);
				$checkbox = INPUT_SINGLECHECKBOX ($inputname.'[]',$question[$i]['value'],$checked);
				$str.=  LABEL($question[$i]['label'],$checkbox,false);
				$str.= '<br/>';
			}
		}
		else
		{
			
			$str.= "<table border='0'> <tr><td valign='top'>";
			for ($i = 0; $i < $total_count; $i++)
			{
				$checked = ($answer && array_search($question[$i]['value'],$answer)!==false);
				$checkbox = INPUT_SINGLECHECKBOX ($inputname.'[]',$question[$i]['value'],$checked);
				$str.=  LABEL($question[$i]['label'],$checkbox,false);
				$str.= '<br/>';
				if ($i > 1 && $i % 7 == 0) $str.= "</td><td valign='top'>";
				
			}
			
			$str.= "</td></tr></table>";
		}
		if (!empty($legend)) $str = FIELDSET($legend,$str);
		return $str;
	}
	
	//_________________________________________________________________________//
	function INPUT_TEXT ($inputname, $id='', $class='', $value='')
	{
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";
		if (!empty($value)) $value_str = " value=\"{$value}\""; else $value_str = '';
		return "<input name=\"{$inputname}\" type=\"text\" {$id_str}{$class_str}{$value_str} />";
	}
	
	//_________________________________________________________________________//
	function INPUT_TEXTAREA ($inputname, $id='', $class='', $value='')
	{
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";
		return "<textarea name=\"{$inputname}\" type=\"text\" {$id_str}{$class_str}>{$value}</textarea>";
	}
	//_________________________________________________________________________//
	function LABEL ($the_label,$the_input,$before=true,$id='', $class='')
	{
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";
		if ($before) $pos = "{$the_label} {$the_input}"; else $pos ="{$the_input} {$the_label}";
		return "<label {$id_str}{$class_str}>{$pos}</label>";
	}
	//_________________________________________________________________________//
	function LABEL_FOR ($the_label,$the_input,$id='', $class='')
	{
		$class_str = (empty($class)) ? '' :  " class=\"{$class}\"";
		$id_str = (empty($id)) ? '' : " id=\"{$id}\"";
		return "<label {$id_str}{$class_str} for=\"{$the_input}\">{$the_label}</label>";
	}

?>