<?php
/**
 * Item queries, these are 'abstract' queries. 
 *
 * Tablename need to be specified by the file that includes these 
 * queries
 * 
 * This file is part of the Brim project.
 * The brim-project is located at the following 
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 * 
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage sql
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php 
 * The GNU Public License
 */
include 'framework/configuration/databaseConfiguration.php';

$queries=array ();

// queries containing 'public' : some modified by Michael
// getItem modified by Michael : removed owner
$queries['getItem']=
		"SELECT * from " . $tableName . " WHERE (owner='%s' OR visibility='public') ".
		"AND (is_deleted=0 || IsNull(is_deleted)) ".
		"AND item_id=%d";
$queries['getPublicItem']=
		"SELECT * from " . $tableName . " WHERE item_id=%d ".
		"AND (is_deleted=0 || IsNull(is_deleted)) ".
		"AND (owner='%s' OR visibility='public')";
		
$queries['getItems']=
		"SELECT * from " . $tableName . " WHERE owner='%s' ".
		"AND (is_deleted=0 || IsNull(is_deleted)) ".
		"ORDER BY is_parent DESC, name ASC";
$queries['getTrashedItems']=
		"SELECT * from " . $tableName . " WHERE owner='%s' ".
		"AND is_deleted=1 ".
		"ORDER BY is_parent DESC, name ASC";
$queries['getPublicItems']=
		"SELECT * from " . $tableName . " WHERE owner='%s' OR visibility='public' ".
		"AND (is_deleted=0 || IsNull(is_deleted)) ".
		"ORDER BY is_parent DESC, name ASC";
		
$queries['getSortedItems']=
		"SELECT * FROM " . $tableName . " WHERE owner='%s' and parent_id=%d ".
		"AND (is_deleted=0 || IsNull(is_deleted)) ".
		"ORDER BY %s %s";
$queries['getAllSortedItems']=
		"SELECT * FROM " . $tableName . " WHERE owner='%s' ".
		"AND (is_deleted=0 || IsNull(is_deleted)) ".
		"ORDER BY %s %s";
		
$queries['getPublicSortedItems']=
		"SELECT * FROM " . $tableName . " WHERE parent_id=%d and (owner='%s' OR visibility='public') ".
		"AND (is_deleted=0 || IsNull(is_deleted)) ".
		"ORDER BY %s %s";
		
$queries['getAllPublicSortedItems']=
		"SELECT * FROM " . $tableName . " WHERE (owner='%s' OR visibility='public') AND parent_id=%d ".
		"AND (is_deleted=0 || IsNull(is_deleted)) ".
		"ORDER BY %s %s";

$queries['getItemChildren']=
		"SELECT * from ". $tableName .
		" WHERE parent_id=%d AND owner='%s' ".
		"AND (is_deleted=0 || IsNull(is_deleted)) ".
		"ORDER BY is_parent DESC, name ASC";
		
$queries['getPublicItemChildren']=
		"SELECT * from ". $tableName .
		" WHERE parent_id=%d AND (owner='%s' OR visibility='public') ".
		"AND (is_deleted=0 || IsNull(is_deleted)) ".
		"ORDER BY is_parent DESC, name ASC";

$queries['getPublicItemChildrenForUser']=
		"SELECT * from ". $tableName .
		" WHERE parent_id=%d AND owner='%s' AND visibility='public' ".
		"AND (is_deleted=0 || IsNull(is_deleted)) ".
		"ORDER BY is_parent DESC, name ASC";

$queries['searchItems']=
		"SELECT * from ".$tableName . 
		" WHERE %s LIKE '%%%s%%' and owner='%s' ".
		"AND (is_deleted=0 || IsNull(is_deleted)) ".
		"ORDER BY is_parent DESC, name ASC";
		
$queries['searchPublicItems']=
		"SELECT * from ".$tableName . 
		" WHERE %s LIKE '%%%s%%' AND (owner='%s' OR visibility='public') ".
		"AND (is_deleted=0 || IsNull(is_deleted)) ".
		"ORDER BY is_parent DESC, name ASC";
		
$queries['getItemChildrenThatAreParent']=
		"SELECT * from ". $tableName .
		" WHERE parent_id=%d AND owner='%s' AND is_parent=1 ".
		"AND (is_deleted=0 || IsNull(is_deleted)) ".
		"ORDER BY is_parent DESC, name ASC";
		
$queries['getPublicItemChildrenThatAreParent']=
		"SELECT * from ". $tableName .
		" WHERE parent_id=%d AND is_parent=1 AND (owner='%s' OR visibility='public') ".
		"AND (is_deleted=0 || IsNull(is_deleted)) ".
		"ORDER BY is_parent DESC, name ASC";
		
$queries['getItemOwner']=
		"SELECT owner from " . $tableName . " WHERE item_id=%d";
		
if ($engine == 'postgres')
{
	$queries['lastItemInsertId']=
		"SELECT currval('".$tableName."_item_id_seq')";
}
else
{
	$queries['lastItemInsertId']=
		"SELECT last_insert_id() FROM " . $tableName;
}
		
$queries['deleteItem']=
		"DELETE from " . $tableName . " WHERE item_id=%d";
		
$queries['updateItemVisitCount']=
		"UPDATE ".$tableName .
		" SET visit_count=visit_count+1 ".
		"WHERE item_id=%d";

$queries['handleTrash']=
		"UPDATE ".$tableName .
		" SET is_deleted=%d, ".
		"when_modified=NOW() ".
		"WHERE owner='%s' AND item_id=%d";

// added by Michael.
// We could put the fields names into a config file (this is what I do with my datamodel)
$queries['getParent']=
		"SELECT DISTINCT parent.item_id, parent.owner, parent.parent_id, ".
		" parent.name, parent.description, parent.visibility, parent.when_created, parent.when_created ".
		" from " . $tableName . " parent, " . $tableName . " self".
		" WHERE self.item_id=%d ".
		" AND self.parent_id = parent.item_id";
		
$queries['getChildrenCount']=
		"SELECT count(*) FROM ". $tableName .
		" WHERE parent_id=%d AND owner='%s' AND is_parent <> 1 ";
		
$queries['getPublicChildrenCount']=
		"SELECT count(*) FROM ". $tableName .
		" WHERE parent_id=%d AND is_parent <> 1 AND (owner='%s' OR visibility='public') ";

$queries['getItemCount']=
		"SELECT count(*) FROM ".$tableName." WHERE owner='%s'";
$queries['getDashboard']=
		"SELECT * FROM " . $tableName . " WHERE owner='%s' and is_parent=0 ".
		"AND is_deleted=0 ".
		"ORDER BY %s %s";
$queries['deleteAllForUser']=
		"DELETE FROM ".$tableName." WHERE owner='%s'";
$queries['moveItem']= 
		"UPDATE ".$tableName .
		" SET parent_id=%d ".
		"WHERE owner='%s' AND item_id=%d";
$queries['getTrashCount']=
		"SELECT count(*) FROM ".$tableName." ".
		"WHERE owner='%s' AND ".
		"is_deleted=1";		
?>
