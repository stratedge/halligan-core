<?php

namespace Halligan\Controller;

class Page extends Controller {

	protected $_components = array();
	protected $_global = array();
	protected $_layout  = NULL;

	const CONTENT_TYPE_COMPONENT	=	"Component";
	const CONTENT_TYPE_STATIC		=	"Static";


	//---------------------------------------------------------------------------------------------
	

	public function __construct()
	{
		parent::__construct();
	}


	//---------------------------------------------------------------------------------------------
	

	public function addComponent($class, $section, Array $options = array())
	{
		$data = array_merge($options, array(
			"type" => self::CONTENT_TYPE_COMPONENT,
			"class" => $class,
			"section" => $section
		));

		return ($this->_components[] = (object) $data);
	}


	//---------------------------------------------------------------------------------------------
	

	public function addStatic($content, $section)
	{
		$data = array(
			"type" => self::CONTENT_TYPE_STATIC,
			"content" => $content,
			"section" => $section
		);

		return ($this->_components[] = (object) $data);
	}


	//---------------------------------------------------------------------------------------------


	public function build($layout = NULL, $globals = array())
	{
		if(!is_null($layout)) $this->setLayout($layout);

		if(!empty($globals)) $this->addGlobal($globals);
		
		//Instantiate our layout file
		$layout = new Layout($this->_layout);

		//Add global data to the layout
		$layout->addGlobal($this->_global);

		foreach($this->_components as $component)
		{
			switch($component->type)
			{
				case self::CONTENT_TYPE_STATIC:
					$layout->addContentToSection($component->content, $component->section);
					break;

				case self::CONTENT_TYPE_COMPONENT:
					if(!isset($component->class) || empty($component->class)) continue;
					if(!isset($component->section) || empty($component->section)) continue;

					$method = isset($component->method) && !empty($component->method) ? $component->method : Config::get('Component', 'default_method');

					$params = (!isset($component->params) || empty($component->params) || !is_array($component->params)) ? array() : $component->params;

					$class = new $component->class($this->_global);

					if(!method_exists($class, $method)) continue;

					$layout->addContentToSection(call_user_func_array(array($class, $method), $params), $component->section);
					break;
			}
		}

		Response::setOutput($layout->build());
	}


	//---------------------------------------------------------------------------------------------


	public function addGlobal($key, $value = NULL)
	{
		if(empty($key)) return FALSE;

		if(is_array($key) || is_object($key))
		{
			$this->_global = array_merge($this->_global, (array) $key);
		}
		else
		{
			$this->_global[$key] = $value;
		}

		return TRUE;
	}


	//---------------------------------------------------------------------------------------------
	

	public function setLayout($layout)
	{
		if(empty($layout) || !is_string($layout) || is_numeric($layout)) return FALSE;

		return $this->_layout = $layout;
	}
}

/* End of file Page.php */
/* Location: ./Halligan/Controller/Page.php */