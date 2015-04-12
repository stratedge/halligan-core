<?php

if(function_exists("dump") === FALSE)
{
	function dump($data)
	{
		@ob_end_clean();
	    ob_start();
	    
	    if(is_bool($data)) {
	        if($data === TRUE) {
	            exit('<pre>Boolean: TRUE</pre>');
	        } else exit('<pre>Boolean: FALSE</pre>');
	    }
	    
	    if(is_string($data)) exit("<pre>String: {$data}</pre>");
	    
	    if(is_float($data)) exit("<pre>Float: {$data}</pre>");
	    
	    if(is_int($data)) exit("<pre>Integer: {$data}</pre>");
	    
	    if(is_numeric($data)) exit("<pre>Number: {$data}</pre>");
	    
	    if(is_null($data)) exit("<pre>NULL</pre>");
	    
		print_r($data);
		
		$output = ob_get_clean();
		
		exit("<pre>".$output."</pre>");
	}
}


//-------------------------------------------------------------------------------------------------


if(function_exists("us2cc") === FALSE)
{
	/**
	 * @deprecated
	 */
	function us2cc($str, $capitalize_first = TRUE)
	{
		return sc2cc($str, $capitalize_first);
	}
}


//---------------------------------------------------------------------------------------------


if(function_exists("sc2cc") === FALSE)
{
	function sc2cc($str, $capitalize_first = TRUE)
	{
		$parts = explode("_", $str);
		foreach($parts as $key => &$part)
		{
			$part = strtolower($part);
			if($capitalize_first == TRUE || $key > 0) $part = ucfirst($part);
		}

		return implode(NULL, $parts);
	}
}


//---------------------------------------------------------------------------------------------


if(function_exists("cc2sc") === FALSE)
{
	function cc2sc($str)
	{
		preg_match_all('/[A-Z][^A-Z]+/', ucfirst($str), $matches);

		$matches = array_map(function($val) { return strtolower($val); }, $matches[0]);

		return implode("_", $matches);
	}
}


//-------------------------------------------------------------------------------------------------


if(function_exists("is_assoc") === FALSE)
{
	function is_assoc($array)
	{
		return (bool) is_array($array) && count(array_filter(array_keys($array), 'is_string'));
	}
}


//-------------------------------------------------------------------------------------------------


if(function_exists("is_simple") === FALSE)
{
	function is_simple($array)
	{
		if(!is_array($array)) return FALSE;
		
		foreach($array as $key => $item)
		{
			if(!is_int($key) || is_array($item) || is_object($item)) return FALSE;
		}
		
		return TRUE;
	}
}



//-------------------------------------------------------------------------------------------------


if(function_exists("resolve_namespace") === FALSE)
{
	function resolve_namespace($path)
	{
		//Remove the working directory from the path
		$path = str_replace(path('base'), NULL, $path);
		
		//Break it into its pieces
		$path = explode(DS, $path);
		
		$path = array_filter($path, function($val) { return strpos($val, ".php") === FALSE; });
		
		array_walk($path, function(&$val) { $val = ucwords($val); });
		
		return implode('\\', $path);
		
	}
}


//-------------------------------------------------------------------------------------------------


if(function_exists("resolve_namespace_class") === FALSE)
{
	function resolve_namespace_class($path, $class)
	{
		$path = resolve_namespace($path);
		return $path . "\\" . $class;
	}
}


//-------------------------------------------------------------------------------------------------


if(function_exists("array_get") === FALSE)
{
	function array_get($array, $key, $default = NULL)
	{
		foreach(explode('.', $key) as $k)
		{
			if(!isset($array[$k])) return $default;

			$array = $array[$k];
		}

		return $array;
	}
}


//---------------------------------------------------------------------------------------------


if(function_exists("array_set") === FALSE)
{
	function array_set(&$array, $key, $value)
	{
		$parts = explode(".", $key);

		$ref = &$array;

		while($part = array_shift($parts))
		{
			if(!isset($ref[$part]) || !is_array($ref[$part]))
			{
				$ref[$part] = array();
			}

			$ref = &$ref[$part];
		}

		$ref = $value;

		return $array;
	}
}


//---------------------------------------------------------------------------------------------


if(function_exists("array_unset") === FALSE)
{
	function array_unset(&$array, $key)
	{
		$ref = &$array;

		$parts = explode(".", $key);
		$key = end($parts);

		foreach($parts as $k)
		{
			if(!isset($ref[$k])) return;

			if($k == $key)
			{
				//Found the final key, remove it
				unset($ref[$key]);
				return;
			}
			else
			{
				//Not the end yet, keep going
				$ref = &$ref[$k];
			}
		}
	}
}


//---------------------------------------------------------------------------------------------


if(function_exists("redirect") === FALSE)
{
	function redirect($uri, $code = 302)
	{
		if(strpos($uri, 'http://') !== 0 && strpos($uri, 'https://') !== 0)
		{
			$url = siteURL($uri);
		}

		header('Location: ' . $uri, TRUE, $code);
		exit();
	}
}


//---------------------------------------------------------------------------------------------


if(function_exists("is_int_val") === FALSE)
{
	function is_int_val($val, $greater_than_zero = FALSE)
	{
		if(is_numeric($val) && (is_int($val) || ctype_digit($val)))
		{
			if($greater_than_zero)
			{
				return intval($val) > 0;
			}

			return TRUE;
		}

		return FALSE;
	}
}


//---------------------------------------------------------------------------------------------


if(function_exists("siteURL") === FALSE)
{
	function siteURL($uri = NULL)
	{
		$protocol = stripos($_SERVER["SERVER_PROTOCOL"], "https") === FALSE ? "http://" : "https://";
		$domain = $_SERVER["HTTP_HOST"];

		if(!is_null($uri))
		{
			if(strpos($uri, '/') !== 0) $uri = '/' . $uri;
		}

		return $protocol . $domain . $uri;
	}
}

/* End of file Utilities.php */
/* Location: ./Halligan/Utilities.php */