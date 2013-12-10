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
	

	public static function registerObjectForClass($class, $obj)
	{
		if(is_object($obj)) self::$_registered_objs[$class] = $obj;
	}


	//---------------------------------------------------------------------------------------------
	

	public static function registerLocationForClass($class, $loc)
	{
		self::$_registered_locs[$class] = $loc;
	}

}

/* End of file Factory.php */
/* Location: ./Halligan/Factory.php */