<?php

class HalliganTestCase extends PHPUnit_Framework_TestCase {

	protected $inputs = array();
	protected $input_modifiers = array();

	const INPUT_CLASS_BOOL			=	"InputClassBool";
	const INPUT_CLASS_INT			=	"InputClassInt";
	const INPUT_CLASS_INT_VAL		=	"InputClassIntVal";
	const INPUT_CLASS_INT_GTZ		=	"InputClassIntGtz";
	const INPUT_CLASS_INT_VAL_GTZ	=	"InputClassIntValGtz";
	const INPUT_CLASS_ARRAY			=	"InputClassArray";
	const INPUT_CLASS_NUMERIC		=	"InputClassNumeric";


	//---------------------------------------------------------------------------------------------
	

	protected function setUp()
	{
		PDOStatement_Stub::reset();
		Factory::reset();
		$this->input_modifiers = array();
		$this->inputs = array(
			TRUE,
			FALSE,
			NULL,
			-1,
			"-1",
			-1.1,
			"-1.1",
			"-1,000",
			"-1,000.1",
			0,
			"0",
			0.0,
			"0.0",
			1,
			"1",
			1.1,
			"1.1",
			"1,000",
			"1,000.1",
			"A",
			"AA",
			"",
			" ",
			"\r",
			"\n",
			array(),
			array(1),
			array("1"),
			array("test" => "value"),
			array(array()),
			array((object) array()),
			(object) array(),
			(object) array("test" => "value")
		);
	}


	//---------------------------------------------------------------------------------------------
	

	public function getInputs()
	{
		return $this->inputs;
	}


	//---------------------------------------------------------------------------------------------
	

	public function addInput(/* polymorphic */)
	{
		foreach(func_get_args() as $input)
		{
			$this->input_modifiers[] = (object) array(
				"type" => "add",
				"value" => $input
			);
		}
	}


	//---------------------------------------------------------------------------------------------
	

	public function subInput(/* polymorphic */)
	{
		foreach(func_get_args() as $input)
		{
			$this->input_modifiers[] = (object) array(
				"type" => "sub",
				"value" => $input
			);
		}
	}


	//---------------------------------------------------------------------------------------------
	

	public function subInputClass($class)
	{
		switch($class)
		{
			case self::INPUT_CLASS_BOOL:
				$this->subInput(TRUE);
				$this->subInput(FALSE);
				break;

			case self::INPUT_CLASS_INT:
				$this->subInput(-1, 0, 1);
				break;

			case self::INPUT_CLASS_INT_GTZ:
				$this->subInput(1);
				break;

			case self::INPUT_CLASS_INT_VAL:
				$this->subInput(-1, "-1", 0, "0", 1, "1");
				break;

			case self::INPUT_CLASS_INT_VAL_GTZ:
				$this->subInput(1, "1");
				break;

			case self::INPUT_CLASS_ARRAY:
				foreach($this->inputs as $input) { if(is_array($input)) $this->subInput($input); }
				break;

			case self::INPUT_CLASS_NUMERIC:
				foreach($this->inputs as $input) { if(is_numeric($input)) $this->subInput($input); }
				break;
		}
	}


	//---------------------------------------------------------------------------------------------
	

	public function subInputBool()
	{
		$this->subInputClass(self::INPUT_CLASS_BOOL);
	}


	//---------------------------------------------------------------------------------------------
	

	public function subInputInt($greater_than_zero = FALSE)
	{
		if($greater_than_zero)
		{
			$this->subInputClass(self::INPUT_CLASS_INT_GTZ);
		}
		else
		{
			$this->subInputClass(self::INPUT_CLASS_INT);		
		}
	}


	//---------------------------------------------------------------------------------------------
	

	public function subInputIntVal($greater_than_zero = FALSE)
	{
		if($greater_than_zero)
		{
			$this->subInputClass(self::INPUT_CLASS_INT_VAL_GTZ);
		}
		else
		{
			$this->subInputClass(self::INPUT_CLASS_INT_VAL);		
		}
	}


	//---------------------------------------------------------------------------------------------
	

	public function subInputArray()
	{
		$this->subInputClass(self::INPUT_CLASS_ARRAY);
	}


	//---------------------------------------------------------------------------------------------
	

	public function subInputNumeric()
	{
		$this->subInputClass(self::INPUT_CLASS_NUMERIC);
	}


	//---------------------------------------------------------------------------------------------
	


	public function buildInputs()
	{
		$tmp = $this->getInputs();

		foreach($this->input_modifiers as $mod)
		{
			switch($mod->type)
			{
				case "sub":
					$this->removeInputValue($tmp, $mod->value);
					break;

				case "add":
					$this->addInputValue($tmp, $mod->value);
					break;
			}
		}

		return $tmp;
	}


	//---------------------------------------------------------------------------------------------
	

	public function removeInputValue(&$tmp, $value)
	{
		$tmp = array_filter($tmp, function($val) use ($value) { return $val !== $value; });
	}


	//---------------------------------------------------------------------------------------------
	

	public function addInputValue(&$tmp, $value)
	{
		$tmp[] = $value;
	}

}