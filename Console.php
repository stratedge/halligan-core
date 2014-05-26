<?php

namespace Halligan;

class Console {

	public static $arguments = array();


	//---------------------------------------------------------------------------------------------
	

	public static function write($msg = "")
	{
		fputs(STDOUT, $msg);
	}


	//---------------------------------------------------------------------------------------------
	

	public static function writeLine($msg = "")
	{
		fputs(STDOUT, $msg . "\r\n");
	}


	//---------------------------------------------------------------------------------------------
	

	public static function setArguments($arguments = array())
	{
		static::$arguments = $arguments;
	}


	//---------------------------------------------------------------------------------------------
	

	public static function hasArgument($argument)
	{
		return isset(static::$arguments[$argument]);
	}


	//---------------------------------------------------------------------------------------------
	

	public static function getArgument($argument)
	{
		return static::hasArgument($argument) ? static::$arguments[$argument] : FALSE;
	}


	//---------------------------------------------------------------------------------------------
	

	public static function getFirstArgument($args = array())
	{
		foreach($args as $arg)
		{
			if(static::hasArgument($arg)) return getArgument($arg);
		}

		return FALSE;
	}


	//---------------------------------------------------------------------------------------------
	

	public static function writeTable($data = array(), $headers = array(), $spacing = 4)
	{
		$col_widths = array();

		foreach($data as $items)
		{
			$i = 0;

			foreach($items as $value)
			{
				$col_widths[$i] = isset($col_widths[$i]) && $col_widths[$i] > strlen($value) ? $col_widths[$i] : strlen($value);
				$i++;
			}
		}

		if(!empty($headers))
		{
			$i = 0;

			foreach($headers as $value)
			{
				$col_widths[$i] = isset($col_widths[$i]) && $col_widths[$i] > strlen($value) ? $col_widths[$i] : strlen($value);
				$i++;
			}

			$i = 0;
			$str = "";

			foreach($headers as $value)
			{
				$str .= str_pad($value, $col_widths[$i] + $spacing);
				$i++;
			}

			self::writeLine($str);

			$str = str_pad("", array_sum($col_widths) + ((count($col_widths) - 1) * $spacing), "-");

			self::writeLine($str);
		}

		foreach($data as $items)
		{
			$i = 0;
			$str = "";

			foreach($items as $value)
			{
				$str .= str_pad($value, $col_widths[$i] + $spacing);
				$i++;
			}

			self::writeLine($str);
		}
	}


	//---------------------------------------------------------------------------------------------
	

	public static function writeHelpMenu($usage = NULL, $options = array())
	{
		if(is_string($usage))
		{
			Console::writeLine($usage);
			Console::writeLine();
		}

		$count = 0;

		foreach(array_keys($options) as $key)
		{
			if(strlen($key) > $count) $count = strlen($key);
		}

		foreach($options as $key => $value)
		{
			// $add = $count - strlen($key);
			$key = str_pad($key, $count);
			Console::writeLine("  $key $value");
		}

		Console::writeLine();
		
		exit;
	}

}

/* End of file Console.php */
/* Location: ./Halligan/Console.php */