<?php
 $filename = SITE_PATH."/admin/blocks/{$item['original_block']}/config.template.php";
 global $gekko_db;
 
 include_block_class ('menus');
 $menu = new menus();
 $menu_categories = $menu->getAllCategories();
 $i=0;
 foreach ($menu_categories as $menu)
 {
	$menu_choices[$i]['label'] = $menu['title'];
	$menu_choices[$i]['value'] = $menu['cid'];	
	$i++;
 }
  echo $block_config->displayConfigAsRadiobox($item['title'],'int_menu_id', 'Select the menu to display in this block', $menu_choices);
  echo BR().BR();
  
  echo $block_config->displayConfigAsTextBoxWithLabel($item['title'],'str_custom_class','Custom CSS Stylesheet Class Prefix',true); 
  
?>
