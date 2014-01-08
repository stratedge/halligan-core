<?php

namespace Halligan;

class Console {

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
	

	public static function clear()
	{
		shell_exec("clear");
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

}

/* End of file Console.php */
/* Location: ./Halligan/Console.php */