<?php
class DB{
	const DB_TYPE_RW = 'rw';
	const DB_TYPE_RO = 'ro';
	
	private static $_dbObjectList = array();
	public static $error;
	
	
	
	public static function init($dbType = null){
		$dbType = $dbType ? $dbType : self::DB_TYPE_RW;
		if(!self::$_dbObjectList[$dbType]){
			$dbConfigList = Config::get('db');
			$dbConfig = $dbConfigList[$dbType];
			if($dbConfig){
				$dbObject = new DBObject($dbConfig);
				self::$_dbObjectList[$dbType] = $dbObject;
			}
		}
	}
	
	public static function query($sql,$dbType = null){
		$dbObject = self::_getDBObject($dbType);
		$result = $dbObject->query($sql);
		if($result){
			return $result;
		} else {
			self::$error = $dbObject->$error;
		}
	}
	
	public static function getQueryResult($sql,$one = false,$dbType = null){
		$ret = array();
		$result = self::query($sql,$dbType);
		if(!$result){
			return false;
		}
		
		while ($row=mysql_fetch_assoc($result)){
			if($one){
				return $row;
				break;
			}
			$ret[] = $row;
		}
		@mysql_free_result($result);
		return $ret;
		
	}
	
	
	
	private static function _getDBObject($dbType = null){
		$dbType = $dbType ? $dbType : self::DB_TYPE_RW;
		self::init($dbType);
		return self::$_dbObjectList[$dbType];
	}
	
	public static function close($dbType = null){
		if($dbType){
			if(self::$_dbObjectList[$dbType]){
				self::$_dbObjectList[$dbType]->close();
				unset(self::$_dbObjectList[$dbType]);
			}
			return;
		} else {
			foreach (self::$_dbObjectList as $index => $dbObject){
				$dbObject->close();
				unset(self::$_dbObjectList[$index]);
			}
			self::$_dbObjectList = array();
		}
	}
	
}





class DBObject{
	private $_connection = null;
	
	public $debug = false;
	public $error = null;
	public $count = 0;
	
	
	function __construct($dbConfig){
		if(!$dbConfig || !is_array($dbConfig)){
			throw new Exception("No DB Config");
			return;
		}
		$host = $dbConfig['host'];
		$password = $dbConfig['password'];
		$user = $dbConfig['user'];
		$name = $dbConfig['name'];
		
		$this->_connection = mysql_connect($host,$user,$password);
		
		if(mysql_errno()){
			throw new Exception("Connect failed: ".mysql_error());
		}
		@mysql_select_db($name,$this->_connection);
		@mysql_query('SET NAMES UTF8;',$this->_connection);
	}
	
	
	function query($sql){
		$this->count++;
		if($this->debug){
			Util_Time::timerStart($sql);
		}
		$result = @mysql_query($sql,$this->_connection);
		
		if($this->debug){
			$duration = Util_Time::timerStop($sql);
			$rowNum = mysql_affected_rows($this->_connection);
			$count = $this->count;
			echo "
			[{$count}][ROW:{$rowNum}][time:{$duration}]{$sql} <br>\n		
			";
		}
		if($result){
			return  $result;
		} else {
			$this->error = mysql_error($this->_connection);
			return false;
		}
		
	}
	
	
	
	
	
	
	
	////////////////////close
	public function close(){
		if(is_resource($this->_connection)){
			@mysql_close($this->_connection);
		}
		$this->_connection = null;
	}
	
	public function __destruct(){
		$this->close();
	}
	
	
}