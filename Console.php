<?php

namespace Halligan;

class Console {

	public static function print($msg)
	{
		fputs(STDOUT, $msg);
	}


	//---------------------------------------------------------------------------------------------
	

	public static function printLine($msg)
	{
		fputs(STDOUT, $msg . "\r\n");
	}

}

/* End of file Console.php */
/* Location: ./Halligan/Console.php */