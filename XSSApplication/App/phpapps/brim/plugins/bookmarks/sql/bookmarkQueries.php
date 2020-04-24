<?php
/**
 * Bookmark related queries
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.bookmarks
 * @subpackage sql
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
$tableName = "brim_bookmarks";
include ("framework/sql/itemQueries.php");
$queries['addItem']=
		"INSERT INTO ".$tableName.
		" (owner, parent_id, is_parent, name, description, visibility, category, when_created, locator, visit_count, favicon) ".
		" VALUES (".
		"'%s', %d, %d, '%s', '%s', '%s', '%s', '%s', '%s', 0, '%s')";
// changed by Michael to add visibility
$queries['modifyItem']=
		"UPDATE ".$tableName." SET ".
		"when_modified='%s', name='%s', parent_id=%d, description='%s',is_deleted=%d, ".
		"locator='%s', visibility='%s', favicon='%s' WHERE item_id=%d";
$queries['updateVisitedInformation']=
		"UPDATE ".$tableName." SET ".
		"when_visited='%s' WHERE item_id=%d";
?>