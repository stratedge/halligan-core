<?php

namespace Halligan;

use \Query;

class Model {

	public function __construct()
	{
		//Placeholder
	}


	public function query($sql = NULL)
	{
		$q = Factory::create("Query");

		return is_null($sql) ? $q : $q->query($sql);
	}

}

/* End of file Model.php */
/* Location: ./Halligan/Model.php */