<?php

class Database {

	static $results = array();

	public static function connect()
	{
		return new PDO_Stub('test', 'test', 'test');
	}

	public static function query($sql)
	{
		PDOStatement_Stub::$last_sql = $sql;

		$pdo_statement = new PDOStatement_Stub();

		return new QueryResult($pdo_statement, PDOStatement_Stub::$last_insert_id);
	}

	public static function prepare($sql)
	{
		PDOStatement_Stub::$last_sql = $sql;
	}

	public function execute($data)
	{
		$pdo_statement = new PDOStatement_Stub();
		$pdo = new PDO_Stub(1, 1, 1);

		foreach($data as $value)
		{
			PDOStatement_Stub::$last_sql = preg_replace("/\?/", $pdo->quote($value), PDOStatement_Stub::$last_sql, 1);
		}

		return new QueryResult($pdo_statement, PDOStatement_Stub::$last_insert_id);
	}

}

/* End of file Database.php */
/* Location: ./Tests/Database.php */