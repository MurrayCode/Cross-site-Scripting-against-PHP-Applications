<?php

/**
 * User queries
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
$tableName = "brim_users";
include 'framework/configuration/databaseConfiguration.php';
$queries['modifyUser']=
		"UPDATE ".$tableName." SET ".
		"name='%s', password='%s', description='%s', email='%s', last_login='%s' ".
		"WHERE loginname='%s'";
$queries['getUser']=
		"SELECT * from ".$tableName." WHERE loginname='%s'";
$queries['getTempUser']=
		"SELECT * from brim_temp_users WHERE loginname='%s'";
$queries['getTempUserByTempPassword']=
		"SELECT * from brim_temp_users WHERE temp_password='%s'";
$queries['deleteTempUserByTempPassword']=
		"DELETE from brim_temp_users WHERE temp_password='%s'";
$queries['getAllUsers']=
		"SELECT * from ".$tableName;
$queries ['getAllLoginNames']=
		"SELECT loginname from ".$tableName;
$queries['getUserForLoginName']=
		"SELECT * from ".$tableName." WHERE loginname='%s'";
$queries['addUser']=
		"INSERT INTO ".$tableName." (loginname, password, name, email, description, when_created) VALUES (".
		"'%s', '%s', '%s', '%s', '%s', NOW())";
$queries['addTempUser']=
		"INSERT INTO brim_temp_users (loginname, password, name, email, description, when_created, temp_password) VALUES (".
		"'%s', '%s', '%s', '%s', '%s', NOW(), '%s')";
if ($engine == 'postgres')
{
	$queries['lastUserInsertId']=
		"SELECT currval('".$tableName."_user_id_seq')";
}
else
{
	$queries['lastUserInsertId']=
		"SELECT last_insert_id() FROM " . $tableName;
}
$queries['deleteItem']=
$queries['deleteUser']=
		"DELETE FROM ".$tableName." WHERE loginName='%s'";
$queries['getEmail']=
		"SELECT email FROM ".$tableName." WHERE loginname='%s'";
$queries['setPassword']=
		"UPDATE ".$tableName." SET ".
		"password='%s' WHERE loginname='%s'";
?>
