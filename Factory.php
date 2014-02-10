<?php

namespace Halligan;

use ReflectionClass;

class Factory {

	protected static $_registered_objs = array();
	protected static $_registered_locs = array();

	//---------------------------------------------------------------------------------------------

	public static function create($class, $params = array())
	{
		if(isset(self::$_registered_objs[$class]) && !empty(self::$_registered_objs[$class])) return self::$_registered_objs[$class];

		if(isset(self::$_registered_locs[$class]) && !empty(self::$_registered_locs[$class]))
		{
			require_once(self::$_registered_locs[$class]);
		}

		$rc = new ReflectionClass($class);

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
	}


	//---------------------------------------------------------------------------------------------
	

	public static function registerMock($class, $mock_obj)
	{
		if(is_object($mock_obj)) self::$_registered_objs[$class] = $mock_obj;
	}


	//---------------------------------------------------------------------------------------------
	

	public static function callStatic($class, $method, $params = array())
	{
		return call_user_func_array(array(self::create($class), $method), $params);
	}

}

/* End of file Factory.php */
/* Location: ./Halligan/Factory.php */