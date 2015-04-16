<?php

namespace Halligan;

use ReflectionClass;
use \Halligan\Config;

class Factory {

	protected static $_registered_objs = array();
	protected static $_registered_locs = array();
	protected static $_registered_alts = array();

	//---------------------------------------------------------------------------------------------

	public static function create($class, $params = array())
	{
		if(isset(self::$_registered_objs[$class]) && !empty(self::$_registered_objs[$class])) return self::$_registered_objs[$class];

		if(isset(self::$_registered_locs[$class]) && !empty(self::$_registered_locs[$class]))
		{
			require_once(self::$_registered_locs[$class]);
		}

		if(!empty(self::$_registered_alts[$class]))
		{
			$class = self::$_registered_alts[$class];
		}

		$rc = new ReflectionClass($class);

		$config = Config::get("Factory", $class);

		if(isset($config["params"])) $params = $params + (array) $config["params"];

		return $rc->newInstanceArgs((array) $params);
	}


	//---------------------------------------------------------------------------------------------
	

	public static function registerLocationForClass($class, $loc)
	{
		self::$_registered_locs[$class] = $loc;
	}


	//---------------------------------------------------------------------------------------------
	

	public static function reset()
	{
		self::$_registered_locs = array();
		self::$_registered_objs = array();
		self::$_registered_alts = array();
	}


	//---------------------------------------------------------------------------------------------
	

	public static function registerMock($class, $mock_obj)
	{
		if(is_object($mock_obj)) self::$_registered_objs[$class] = $mock_obj;
	}


	//---------------------------------------------------------------------------------------------
	

	public static function registerAlternative($class, $alt_class)
	{
		self::$_registered_alts[$class] = $alt_class;
	}


	//---------------------------------------------------------------------------------------------
	

	public static function callStatic($class, $method, $params = array())
	{
		return call_user_func_array(array(self::create($class), $method), $params);
	}

}

/* End of file Factory.php */
/* Location: ./Halligan/Factory.php */