<?php
/**
 * Contact queries
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.contacts
 * @subpackage sql
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
$tableName="brim_contacts";
include ("framework/sql/itemQueries.php");

/*
 * Contact specific
 */
$queries['addItem']=
		"INSERT INTO ".$tableName.
		" (owner, parent_id, is_parent, name, description, visibility, category, when_created, alias, address, ".
		" mobile, faximile, tel_home, tel_work, organization, org_address, job, ".
		" email1, email2, email3, webaddress1, webaddress2, webaddress3)".
		" VALUES (".
		"'%s', %d, %d, '%s', '%s', '%s', '%s', ".
		"'%s', '%s', '%s', '%s', '%s', '%s', '%s', ".
		"'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')";
$queries['modifyItem']=
		"UPDATE ".$tableName." SET ".
		"when_modified='%s', ".
		"name='%s', ".
		"parent_id=%d, ".
		"visibility='%s', ".
		"is_deleted=%d, ".
		"job='%s', ".
		"alias='%s', ".
		"organization='%s', ".
		"org_address='%s', ".
		"tel_home='%s', ".
		"tel_work='%s', ".
		"faximile='%s', ".
		"mobile='%s', ".
		"address='%s', ".
		"description='%s', ".
		"email1='%s', ".
		"email2='%s', ".
		"email3='%s', ".
		"webaddress1='%s', ".
		"webaddress2='%s', ".
		"webaddress3='%s' ".
		"WHERE item_id=%d";
?>