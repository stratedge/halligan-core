<?php

namespace Halligan;

class Layout {

	protected $_layout = NULL;
	protected $_data = array();
	protected $_global = array();


	//---------------------------------------------------------------------------------------------
	

	public function __construct($layout = NULL)
	{
		$this->setLayout($layout);
	}


	//---------------------------------------------------------------------------------------------


	public function setLayout($layout)
	{
		if(empty($layout) || !is_string($layout) || is_numeric($layout))
		{
			return ($this->_layout = Config::get('Layout', 'default_layout'));
		}

		return ($this->_layout = $layout);
	}


	//---------------------------------------------------------------------------------------------


	public function addContentToSection($content, $section)
	{
		if(empty($section) || !is_string($section) || is_numeric($section)) return FALSE;

		if(isset($this->_data[$section])) return $this->_data[$section] .= (string) $content;
		
		return $this->_data[$section] = (string) $content;
	}


	//---------------------------------------------------------------------------------------------


	public function addGlobal($map, $value = NULL)
	{
		//If map is an associative array, merge it into the global data array
		if(is_array($map) && is_assoc($map))
		{
			return $this->_global = array_merge($this->_global, $map);
		}

		//Ensure that map is a valid PHP variable name
		if(!is_string($map) || is_numeric($map)) return FALSE;

		//Add the specified value into the appropriate key
		return $this->_global[$map] = $value;
	}


	//---------------------------------------------------------------------------------------------


	public function build()
	{
		if(!$this->_hasLayout()) return FALSE;

		//Try to load the layout file
		$path = $this->_getLayoutFilePath();

		//No file found? Exit gracefully
		if($path === FALSE) return FALSE;

		switch(Config::get("Layout", "compile_layouts", TRUE))
		{
			case TRUE:
			default:
				return $this->buildFromCompilation($path);

			case FALSE:
				return $this->buildFromParsing($path);
		}		
	}


	//---------------------------------------------------------------------------------------------
	

	public function buildFromCompilation($path)
	{
		$compiled_layout_path = $this->_compileLayout($path);

		//Begin output buffering
		ob_start();
		
		extract($this->_global);

		include($compiled_layout_path);

		$layout = ob_get_clean();

		$layout = str_replace('{execution-time}', round(((microtime(TRUE) - HALLIGAN_START)), 3), $layout);

		$layout = str_replace('{memory-usage}', round(memory_get_peak_usage() / 1024 / 1024, 2), $layout);

		return $layout;
	}


	//---------------------------------------------------------------------------------------------
	

	public function buildFromParsing($path)
	{
		//Get parsed layout
		$contents = file_get_contents($path);
		$layout = $this->parseTags($contents);

		//Start output buffermg
		ob_start();

		//Before we include the compiled layout, make the data availabe as PHP variables
		extract($this->_global);

		//Include the layout file
		eval($layout);
		
		//Return the buffered output
		return ob_get_clean();
	}


	//---------------------------------------------------------------------------------------------


	protected function _hasLayout()
	{
		return isset($this->_layout) && !empty($this->_layout) && is_string($this->_layout) && !is_numeric($this->_layout);
	}


	//---------------------------------------------------------------------------------------------


	protected function _getLayoutFilePath()
	{
		foreach(get_all_paths_ordered() as $path)
		{
			$path = realpath($path . 'Layout' . DS . $this->_layout . EXT);
			if($path !== FALSE) break;
		}

		return $path;
	}


	//---------------------------------------------------------------------------------------------


	protected function _compileLayout($path)
	{
		//Do we have a cache folder?
		if(!$this->haveCacheFolder())
		{
			//Create the cache folder
			mkdir($this->getCacheFolderPath(), 0777, TRUE);
		}

		$compiled_path = $this->_getCompiledLayoutPath($path);

		//Is the layout not yet compiled or out of date?
		if(file_exists($compiled_path) === FALSE || filemtime($path) >= filemtime($compiled_path))
		{
			$contents = file_get_contents($path);

			//Parse template tags
			$t = Factory::create("Template");
			$contents = $t->parseTags($contents);
			unset($t);

			$layout = preg_replace_callback('/\{(section:|template:)([^\}]+)*\}/', array($this, '_parseTag'), $contents);
			file_put_contents($compiled_path, $layout);
			chmod($compiled_path, 0777);
		}

		return $compiled_path;
	}


	//---------------------------------------------------------------------------------------------
	

	public function haveCacheFolder()
	{
		return realpath($this->getCacheFolderPath()) !== FALSE;
	}


	//---------------------------------------------------------------------------------------------
	

	public function getCacheFolderPath()
	{
		return path("app") . "_cache" . DS . "layout";
	}


	//---------------------------------------------------------------------------------------------
	

	protected function _getCompiledLayoutPath($path)
	{
		return $this->getCacheFolderPath() . DS . md5($path) . EXT;
	}


	//---------------------------------------------------------------------------------------------


	protected function _parseTag(Array $matches)
	{
		switch($matches[1])
		{
			case 'section:':
				return sprintf('<?php $this->_parseSection("%s"); ?>', $matches[2]);
				break;

			case 'template:':
				return sprintf('<?php $this->_parseTemplate("%s"); ?>', $matches[2]);
				break;
		}
	}


	//---------------------------------------------------------------------------------------------


	protected function _parseTemplate($template)
	{
		$tpl = Factory::create("Template");
		
		$tpl->setTemplate($template);
		
		$tpl->addData($this->_global);

		echo $tpl->build();
	}


	//---------------------------------------------------------------------------------------------


	protected function _parseSection($section)
	{
		echo isset($this->_data[$section]) ? $this->_data[$section] : NULL;
	}
}

/* End of file Layout.php */
/* Location: ./Halligan/Layout.php */