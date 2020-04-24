<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

/*
interfaceApplication --> interfaceApplicationLinearData --> interfaceApplicationSimpleCategories --> interfaceApplicationNestedCategories --> interfaceApplicationMultipleCategories
*/

interface interfaceFilter
{
	public function getConfiguration();
    public function Run($text,$obj,$caller_function,$extra_info=false);
}

interface interfaceBlock 
{
	public function getConfiguration();
	public function Run();
}

interface interfaceApplication 
{
	public function redirectToOtherAction($action,$protocol=OPT_REDIRECT_DEFAULT);
	public function getApplicationDescription();
	public function processOutputWithFilter($text, $the_function,$extra_info = false);
	public function displayBreadCrumbs();
	public function displayPageTitle();
	public function displayMainPage();
	public function interpretFriendlyURL($url);
	public function createFriendlyURL($url);
	public function Run($command);
}

interface interfaceApplicationLinearData extends interfaceApplication 
{

        // get data info
	public function getTotalItemCount($criteria='',$cache=false);
	public function getItemTableName();
	public function getItemFieldNames();
	public function getFieldID();
	// other functions
	public function delete($str);
	public function getAllItems($fields='*',$extra_criteria = '',$start=0,$end=0,$sortby='', $sortdirection='ASC', $from_cache = false);
	public function getDefaultItemID();
	public function displayItemByID($id=1,$from_cache=false);
	public function getItemByID($id,$from_cache=false);
	public function getItemByVirtualFilename($input_filename, $category_id=-1);

	public function preventDuplicateItemByFieldName($fieldname,$id, $name);
	public function findDuplicateItems($data);
	public function validateSaveItem($data);
	public function saveItem($id);
	// search
	public function genericSearch($fieldname, $keyword, $fields_tobe_selected = '*',$start=0,$end=0,$sortby='', $sortdirection='ASC');
}

interface interfaceApplicationSimpleCategories extends interfaceApplicationLinearData 
{
        
	// get data info
	public function getTotalItemCountByCategoryID($category_id, $criteria='', $cache=false);	
	public function getTotalCategoryCount($criteria='', $cache=false);	
 	public function getItemOrCategoryToViewFromFullVirtualFilename($url, $enable_redirect_no_trailingslash_folder=false);
	public function getCategoryByVirtualFilename($input_filename, $parent_id=-1);
	public function getCategoryTableName();
	public function getCategoryFieldNames();
	public function getFieldCategoryID();
	public function getDefaultCategoryID();
	public function getFullPathByItemID($item_id);
	public function getFullPathByCategoryID($cat_id);
	// display html
	public function displayCategories($id=1, $pg=1, $sortby='', $sortdirection='ASC', $criteria='status > 0');
	public function displayItemsInCategoryByID($id=1, $pg=1, $sortby='', $sortdirection='ASC', $criteria='status > 0');
	// functions
	public function getAllChildItemsInMultipleCategories($multiple_category_ids);
	public function getAllCategories($fields='*',$extra_criteria = '',$start=0,$end=0,$sortby='', $sortdirection='ASC');
	public function getCategoryByID($id,$from_cache=false);
	public function getItemsByCategoryID($id,$fields='*',$extra_criteria='',$start=0,$end=0,$sortby='', $sortdirection='ASC',$from_cache=false);
	public function preventDuplicateCategoryInThisCategoryByFieldName($fieldname,$id, $name);
	public function preventDuplicateItemInThisCategoryByFieldName($fieldname,$id, $name);
	public function findDuplicateCategories($data);
	public function validateSaveCategory($data);
	public function saveCategory($id);

	
}

interface interfaceApplicationNestedCategories extends interfaceApplicationSimpleCategories 
{
	public function getTotalChildCategoryCountByCategoryID($parent_cid,$criteria='',$cache=false);
    public function TraverseCategories($catid);
 	public function getChildCategoriesByParentID($id,$fields='*',$extra_criteria='',$start=0,$end=0,$sortby='', $sortdirection='ASC',$from_cache=false);
}

interface interfaceApplicationMultipleCategories extends interfaceApplicationNestedCategories 
{
	public function getItemsToCategoryFieldNames();
	public function getItemsToCategoryTableName();
	public function setItemCategory($id,$cid,$state);
	public function getItemCategoryIDsByItemID($id,$fields='*',$start=0,$end=0,$sortby='', $sortdirection='ASC',$from_cache=false);
}



?>