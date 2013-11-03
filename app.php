<?php
//
ini_set('precision', 16);  //这只浮点型精度
define('ROOT_PATH', dirname(__FILE__));
define('LIB_PATH', ROOT_PATH.'/lib');
define('CLASS_PATH', ROOT_PATH.'class');
define('CONF_PATH', ROOT_PATH.'/conf');
define('LANG_PATH', ROOT_PATH.'LANG_PATH');
define('COM_PATH', ROOT_PATH.'/common');


function __autoload($strClassName)
{
	$strClassName = str_replace('_', '/', $strClassName);
	$libClassPath = LIB_PATH.'/'.$strClassName.'.class.php';
	$localClassPath = CLASS_PATH.'/'.$strClassName.'.class.php';
	if(file_exists($libClassPath)){
		require_once $libClassPath;
	} else if(file_exists($localClassPath)){
		require_once $localClassPath;
	}
}


require_once COM_PATH.'/function.php';

Config::Load();

$dbConfig = Config::Get('db');
