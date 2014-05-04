<?php

class PDOStatement_Stub extends \PDOStatement {

	public static $results = array();
	public static $last_sql = NULL;
	public static $last_insert_id = NULL;
	public static $throw_exception = FALSE;


	//---------------------------------------------------------------------------------------------


	public function __construct()
	{
		if(self::$throw_exception)
		{
			throw new DatabaseException(array("CODE", 123, "Database Exception"));
		}

		return $this;
	}


	//---------------------------------------------------------------------------------------------
	

	public static function addResult($result, $index = NULL)
	{
		if(is_null($index) || !is_numeric($index))
		{
			self::$results[] = $result;
		}
		else
		{
			self::$results[(int) $index] = $result;
		}
	}


	//---------------------------------------------------------------------------------------------
	

	public function fetch($how = NULL, $orientation = NULL, $offset = NULL)
	{
		if(!isset(self::$results[0])) return NULL;
		return array_shift(self::$results[0]);
	}


	//---------------------------------------------------------------------------------------------
	

	public function fetchColumn($column_number = NULL)
	{
		if(!isset(self::$results[0])) return FALSE;

		$column_number = $column_number ?: 0;

		$result = self::$results[0];

		if(count($result))
		{
			//Grab he first available row for the result
			$val = array_shift($result);

			//Set the most current result to no longer include the row we just grabbed
			self::$results[0] = $result;

			//Return the correct column from the row we just grabbed
			return $val[$column_number];
		}

		//No values left in the latest result, remove it
		array_shift(self::$results);
		return FALSE;
	}


	//---------------------------------------------------------------------------------------------
	

	public static function getLastSQL()
	{
		return self::formatQuery(self::$last_sql);
	}


	//---------------------------------------------------------------------------------------------
	

	public static function reset()
	{
		self::$results = array();
		self::$last_sql = NULL;
		self::$last_insert_id = NULL;
		self::$throw_exception = FALSE;
	}


	//---------------------------------------------------------------------------------------------
	

	public static function formatQuery($sql)
	{
		return preg_replace("/[\s]+/", " ", $sql);
	}


	//---------------------------------------------------------------------------------------------
	

	public static function throwException()
	{
		self::$throw_exception = TRUE;
	}


	//---------------------------------------------------------------------------------------------
	

	public static function setLastInsertId($id)
	{
		self::$last_insert_id = $id;
	}

}

/* End of file PDOStatement.php */
/* Location: ./Tests/PDOStatement.php */