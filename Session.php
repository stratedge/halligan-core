<?php

namespace Halligan;

class Session {

	public static function start()
	{
		session_start();
	}


	//---------------------------------------------------------------------------------------------
	

	public static function get($key, $default = NULL)
	{
		return array_get($_SESSION, $key, $default);
	}


	//---------------------------------------------------------------------------------------------
	

	public static function set($key, $value)
	{
		return array_set($_SESSION, $key, $value);
	}


	//---------------------------------------------------------------------------------------------
	

	public static function reset()
	{
		$_SESSION = array();
	}


	//---------------------------------------------------------------------------------------------
	

	public static function destroy()
	{
		session_destroy();
	}


	//---------------------------------------------------------------------------------------------
	

	public static function kill()
	{
		self::reset();

		$params = session_get_cookie_params();
		setcookie(
			session_name(), 
			'',
			0,
			$params["path"],
			$params["domain"],
			$params["secure"],
			$params["httponly"]
		);

		self::destroy();
	}

}

/* End of file Session.php */
/* Location: ./Halligan/Session.php */