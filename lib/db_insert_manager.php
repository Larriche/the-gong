<?php
class DBInsertManager
{
	protected $table;

	protected $fields;

	protected $values;

	protected $lastInsertId;

	public function __construct()
	{
		global $connection;
		$this->connection = $connection;
	}

	public function into($table)
	{
		$this->table = $table;
		return $this;
	}

	public function insert()
	{
		$this->values = func_get_args();
		return $this;
	}

	public function fields()
	{
		$this->fields = func_get_args();
		return $this;
	}

	public function getLastInsertId()
	{
		return $this->lastInsertId;
	}

	public function execute()
	{
		$query = "INSERT INTO ".$this->table."(".join(',',$this->fields).")";
		$query .= " VALUES (";

        $placeholders = [];
		for($i = 0;$i < count($this->values);$i++)
			$placeholders[$i] = "?";

		$query .= join(",",$placeholders).")";
		
		$stmt = $this->connection->prepare($query);
		$result = $stmt->execute($this->values);

		$this->lastInsertId = $this->connection->lastInsertId();
		return $result;
	}

}
?>