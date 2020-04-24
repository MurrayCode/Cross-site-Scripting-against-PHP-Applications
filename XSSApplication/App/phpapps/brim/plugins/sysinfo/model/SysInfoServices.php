<?php

require_once ('framework/util/ArrayUtils.php');
require_once ('framework/util/StringUtils.php');

/**
 * SysInfo Services
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - June 2004
 * @package org.brim-project.plugins.sysinfo
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class SysInfoServices
{
	var $stringUtils;
	var $arrayUtils;
	/**
	 * Default constructor
	 */
 	function SysInfoServices ()
 	{
		$this->arrayUtils = new ArrayUtils ();
		$this->stringUtils = new StringUtils ();
 	}


	function getSysInfo ()
	{
		$result = array ();
		$result[0]['name']='Settings';
		$result[0]['contents'][] = array (
					'name'=>'xml_parser_create',
					'value'=>function_exists ('xml_parser_create')
				);
		$result[0]['contents'][] = array (
					'name'=>'xml_extension_loaded',
					'value'=>extension_loaded('xml')
				);
		$result[0]['contents'][] = array (
					'name'=>'get_magic_quotes_gpc',
					'value'=>get_magic_quotes_gpc()
				);
		$result[0]['contents'][] = array (
					'name'=>'mcrypt_encrypt',
					'value'=>function_exists ('mcrypt_encrypt')
				);
		$result[0]['contents'][] = array (
					'name'=>'mcrypt_decrypt',
					'value'=>function_exists ('mcrypt_decrypt')
				);
		$result[1]['name']='Globals';
		$result[1]['contents'][] = array (
					'name'=>'$_SESSION',
					'value'=>$this->arrayUtils->
						implode_with_keys (', ',$_SESSION)
				);
		$result[1]['contents'][] = array (
					'name'=>'$_COOKIE',
					'value'=>$this->arrayUtils->
						implode_with_keys (', ',$_COOKIE)
				);
		$result[1]['contents'][] = array (
					'name'=>'$_ENV',
					'value'=>$this->arrayUtils->
						implode_with_keys (', ',$_ENV)
				);
		$result[1]['contents'][] = array (
					'name'=>'$_SERVER',
					'value'=>$this->arrayUtils->
						implode_with_keys (', ',$_SERVER)
				);
		if (function_exists ('apache_get_version')
			&& function_exists ('apache_get_modules'))
		{
			$result[2]['name']='Apache';
			$result[2]['contents'][] = array (
					'name'=>'apache_get_version',
					'value'=>apache_get_version()
				);
			$result[2]['contents'][] = array (
					'name'=>'apache_get_modules',
					'value'=>$this->arrayUtils->
						implode_with_keys(', ', apache_get_modules())
				);
		}
		return $result;
	}

	function databaseDump ()
	{

		$host = "";
		$user = "";
		$password = "";
		$database = "";
		include 'framework/configuration/databaseConfiguration.php';
		$connection = mysql_connect($host, $user, $password);
		if (mysql_select_db ($database, $connection))
		{
				$zDate = date ('Y-m-d');
			$result = '
#########################
#                       #
#  Brim database dump   #
#  date: '.$zDate.'     #
#                       #
#  Author: Barry Nauta  #
#                       #
#########################

';
			$rs = mysql_list_tables ($database, $connection);
			while ($row = mysql_fetch_row ($rs))
			{
				$table = $row[0];
				if ($this->stringUtils->startsWith ($table, 'brim'))
				{
					$result .= $this->getTableDefinition
						($connection, $database, $table);
					$result .= $this->getTableContent
						($connection, $database, $table);
					$result .= '

';
				}
			}
		}
		mysql_close ($connection);
		return $result;
	}

	function getTableDefinition ($connection, $database, $table)
	{
		$newline = '
';
		$result  = 'DROP TABLE IF EXISTS '.$table.';'.$newline;
		$result .= 'CREATE TABLE '.$table.$newline.'('.$newline;
		//
		// Column definitions
		//
		$query = 'SHOW COLUMNS FROM '.$table;
		$rs = mysql_db_query ($database, $query, $connection)
			or die ('Error executing query '.$query.' '.mysql_error ());
		//
		// Loop over the resultset
		//
		while ($row = mysql_fetch_array ($rs))
		{
			$result .= '    '.$row['Field'].' '.$row['Type'];
			//
			// Check for default
			//
			if ($row['Default'] != '')
			{
				$result .= " DEFAULT '".$row['Default']."'";
			}
			//
			// Check for null
			//
			if ($row['Null'] != 'YES')
			{
				$result .= ' NOT NULL';
			}
			//
			// Check for extra
			//
			if ($row['Extra'] != '')
			{
				$result .= ' '.$row['Extra'];
			}
			//
			// Close the line
			//
			$result .= ','.$newline;
		}
		//
		// Key definitions
		//
		$query = 'SHOW COLUMNS FROM '.$table;
		$rs = mysql_db_query ($database, $query, $connection)
			or die ('Error executing query '.$query.' '.mysql_error ());
		//
		// Loop over the resultset
		//
		while ($row = mysql_fetch_array ($rs))
		{
			$key = $row['Key'];
			//
			// This only takes one key into account.
			// TODO FIXME BARRY TBD
			//
			if ($key == 'PRI')
			{
				$result .= '    PRIMARY KEY ('.$row['Field'].'),'.$newline;
			}
			if ($key == 'MUL')
			{
				$result .= '    KEY '.$row['Field'].' ('.$row['Field'].')'.$newline;
			}
		}
		//
		// Remove the very last comma
		//
		$result = ereg_replace (','.$newline.'$', $newline, $result);
		$result .= ');'.$newline;
		return $result;
	}

	function getTableContent ($connection, $database, $table)
	{
		$newline = '
';
		$result = '';
		//
		// Make sure that noone is writing while we are dumping
		//
		$query = 'LOCK TABLES '.$table.' WRITE';
		mysql_query ($query);
		//
		// Read all values
		//
		$query = 'SELECT * FROM '.$table;
		$rs = mysql_db_query ($database, $query, $connection)
			or die ('Error executing query '.$query);
		//
		// Loop over the resultset
		//
		while ($row = mysql_fetch_array ($rs))
		{
			$result .= 'INSERT INTO '.$table.' VALUES (';
			for ($i=0; $i<mysql_num_fields ($rs); $i++)
			{
				if (!isset ($row[$i]))
				{
					$result .= 'NULL';
				}
				else if ($row[$i]=='')
				{
					$result .= "''";
				}
				else
				{
					if (mysql_field_type ($rs, $i) == 'int')
					{
						$result .= addslashes($row[$i]);
					}
					else
					{
						$result .= "'".addslashes($row[$i])."'";
					}
				}
				$result .= ', ';
			}
			//
			// Remove the very last comma
			//
			$result = ereg_replace (", $", '', $result);
			$result .= ');'.$newline;
		}
		//
		// And unlock the table
		//
		$query = 'UNLOCK TABLES';
		mysql_query ($query);
		return $result;
	}
}
?>