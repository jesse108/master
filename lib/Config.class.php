<?php
class Config{
	public static $config;
	
	public static function load($name = null){
		$name = $name ? $name : 'system';
		$name = str_replace('_', '/', $name);
		$configPath = CONF_PATH . '/'.$name.'.conf.php';
		if(file_exists($configPath)){
			require $configPath;
			$config = $config ? $config : array();
			foreach ($config as $index => $one){
				self::$config[$index] = $one;
			}
		}
	}
	
	public static function get($name = null){
		if(!$name){
			return self::$config;
		}
		if(!self::$config[$name]){
			self::load($name);
		}
		return self::$config[$name];
	}
	
}