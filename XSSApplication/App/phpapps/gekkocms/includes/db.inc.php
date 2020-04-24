<?php
global $sql_log;
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
class Database {
//_________________________________________________________________________//
    protected $link;
    private $server, $username, $password, $db, $last_query,$last_query_result;
//_________________________________________________________________________//    
    public function __construct($server,  $db, $username, $password)
    {
		
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->db = $db;
		//file_put_contents(SITE_PATH.'/cache/sql/gekkosql.log',"-----------{$_SERVER['REQUEST_URI']}-----------\n",FILE_APPEND);
        $this->link = @mysql_connect($this->server, $this->username, $this->password);
		if (!$this->link) 
		{
			if ($_GET['ajax'] == 1)
			{
				ajaxReply('141',"Cannot connect to the database");
			}
			else include('errors/db_offline.php');
			exit;
		}
		else
			mysql_select_db($this->db, $this->link);
		mysql_set_charset('utf8',$this->link);
    }
//_________________________________________________________________________//    
	public function reselectDatabase()
	{
		if ($this->db && $this->link)
			mysql_select_db($this->db, $this->link);		
	}
	
//_________________________________________________________________________//    
	public function query($sql)
	{
		global $gekko_errorlog;
		
		$this->last_query_result = mysql_query($sql, $this->link);
		$this->last_query = $sql;
		//file_put_contents(SITE_PATH.'/cache/sql/gekkosql.log',$sql."\n",FILE_APPEND);
			if (!$this->last_query_result) {
				{
					
					$error_txt = 'Could not run query: '.$sql."\n Error:". mysql_error();
					if (strpos($_SERVER['SCRIPT_NAME'],'admin/index.php')!==false)
					{
						if ($_GET['ajax'] == 1)
						{
							ajaxReply('151',"Gekko DB SQL Error running {$sql}.\n{$error_txt}");
						} else
						echo '<div style="background:pink; border:1px solid orange">Gekko DB Error - '.$error_txt.'</div>';
					} else
					{
						
						echo '<div style="background:pink; border:1px solid orange">Gekko DB Error - '.$error_txt.'</div>';
					}
				}
				exit;
			}
	}
 //_________________________________________________________________________//    	
	public function getTableColumns($tablename, $from_cache = true)
	{
		if ($this->tableExists($tablename))
		{
			$sql = "SHOW COLUMNS FROM `{$tablename}`";
			$columns = $this->get_query_result($sql,$from_cache);
			if ($columns) return $columns; else return false;
		} else return false;
	}
 //_________________________________________________________________________//    	
	public function tableExists($tablename, $from_cache = true)
	{
		$tablename = sanitizeString($tablename);
 		$sql = "SHOW TABLES LIKE {$tablename}";
		$columns = $this->get_query_result($sql,$from_cache);
		if ($columns) return $columns; else return false;
	}
 
 //_________________________________________________________________________//    	
	public function get_query_result($sql,$from_cache=false) {
		
		$array = array();

		if (SQL_CACHE_ENABLED && $from_cache && $sql)
		{
			$md5filename = SITE_PATH.'/cache/sql/'.md5($sql).'.array';
			if (file_exists($md5filename) && (time()- filemtime($md5filename) < SQL_CACHE_TIME))
			{
				// check from cache
				$fcontent = file_get_contents($md5filename);
				$array = unserialize ($fcontent);
				return $array;
			} else
			{
		 		$this->query($sql);
				
				while ($row = mysql_fetch_assoc( $this->last_query_result )) $array[] = $row;
				mysql_free_result( $this->last_query_result );
				
				$md5handle = @fopen($md5filename, 'w') or die("Cannot save to cache");
	 			fwrite($md5handle, serialize($array));
	 			fclose($md5handle);
	 			return $array;
				
			}
		} else
		{
			$this->query($sql);
			while ($row = mysql_fetch_assoc( $this->last_query_result )) $array[] = $row;
			mysql_free_result( $this->last_query_result );
			return $array;
		}
 	}
	
//_________________________________________________________________________//    	
	function get_query_singleresult($sql,$from_cache=false) {
		$result = $this->get_query_result($sql, $from_cache);
		return $result[0];
	}
//_________________________________________________________________________//    	
	function last_insert_id() {
		return mysql_insert_id();
	}	
//_________________________________________________________________________//    	
	function get_result_as_array($from_cache = false) {

		
		$array = array();
		if (SQL_CACHE_ENABLED && $from_cache && $this->last_query)
		{
			//echo 'Cached: '.$this->last_query.'<BR/';die;
			$md5filename = SITE_PATH.'/cache/sql/'.md5($this->last_query).'.array';
			if (file_exists($md5filename) && (time()- filemtime($md5filename) < SQL_CACHE_TIME))
			{
				// check from cache
				$fcontent = file_get_contents($md5filename);
				$array = unserialize ($fcontent);
				return $array;
			} else
			{
				// create cache
				while ($row = mysql_fetch_assoc( $this->last_query_result )) $array[] = $row;
				mysql_free_result( $this->last_query_result );
				
				$md5handle = @fopen($md5filename, 'w') or die("Cannot save to cache");
	 			fwrite($md5handle, serialize($array));
	 			fclose($md5handle);
	 			return $array;
				
			}
		} else
		{
			while ($row = mysql_fetch_assoc( $this->last_query_result )) $array[] = $row;
			mysql_free_result( $this->last_query_result );
			return $array;
		}
	}
//_________________________________________________________________________//    	
	function get_result_as_object($from_cache = false) {
		$array = array();
		if (SQL_CACHE_ENABLED && $from_cache && $this->last_query)
		{
			
			$md5filename = SITE_PATH.'/cache/'.md5($this->last_query).'.object';
			if (file_exists($md5filename) && (time()- filemtime($md5filename) < SQL_CACHE_TIME))
			{
				// check from cache
				$fcontent = file_get_contents($md5filename);
				$array = unserialize ($fcontent);
				return $array;
			} else
			{
				// create cache
				while ($row = mysql_fetch_object( $this->last_query_result )) $array[] = $row;
				mysql_free_result( $this->last_query_result );
				
				$md5handle = @fopen($md5filename, 'w') or die("Cannot save to cache");
	 			fwrite($md5handle, serialize($array));
	 			fclose($md5handle);
	 			return $array;
				
			}
		} else
		{
			while ($row = mysql_fetch_object( $this->last_query_result )) $array[] = $row;
			mysql_free_result( $this->last_query_result );
			return $array;
		}
	}
//_________________________________________________________________________//    
    function __destruct()
	{
		if ($this->link) mysql_close($this->link);
    }
//_________________________________________________________________________//    	

}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
class ErrorLog
{
	protected $table_name;
	
    public function __construct($table_name)
    {
		$this->table_name =  $table_name;
	}
	//_________________________________________________________________________//	
    public function record($error)
    {
		global $gekko_db;
		
		// create new data							
		$datavalues['ipaddress'] = $_SERVER['REMOTE_ADDR'];
		$datavalues['referrer'] = $_SERVER['HTTP_REFERER'];	
		$datavalues['useragent'] = $_SERVER['HTTP_USER_AGENT'];			
		$datavalues['request_uri'] = $_SERVER['REQUEST_URI'];
		$datavalues['description'] = $error;
		$sql_set_cmd = InsertSQL($datavalues);
		$sql =  "INSERT INTO `{$this->table_name}` ".$sql_set_cmd;
		$gekko_db->query($sql);
    }
	//_________________________________________________________________________//
}
?>