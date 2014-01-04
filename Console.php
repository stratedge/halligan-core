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

}

/* End of file Console.php */
/* Location: ./Halligan/Console.php */