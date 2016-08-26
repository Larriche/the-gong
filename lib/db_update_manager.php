<?php
class DBUpdateManager
{
	protected $table;

	protected $fields;

	protected $values;

	protected $whereFields;

	protected $matchFields;

	protected $connection;


	public function __construct()
	{
		global $connection;
		$this->connection = $connection;
	} 

	public function in($table)
	{
		$this->table = $table;
		return $this;
	}

	public function update()
	{
		$this->fields = func_get_args();
		return $this;
	}

	public function with()
	{
		$this->values = func_get_args();
		return $this;
	}

	public function where()
	{
		$this->whereFields = func_get_args();
		return $this;
	}

	public function match()
	{
		$this->matchFields = func_get_args();
		return $this;
	}

	public function execute()
	{
		$query = "UPDATE ".$this->table." SET ";

		foreach($this->fields as $field)
			$query .=  $field . " = ? ,";

		$query = rtrim($query,",");

		if(!empty($this->whereFields)){
			for($i = 0;$i < count($this->whereFields);$i++)
				$this->whereFields[$i] = $this->whereFields[$i]." = ? ";
			$query .= " WHERE ".join(' AND ',$this->whereFields);
		}

	
        
        // adding the values for the where match fields to the values of the SET statements
        // to have the right number of values for data binding
        foreach($this->matchFields as $matchField)
        	$this->values[] = $matchField;

		$stmt = $this->connection->prepare($query);
		$stmt->execute($this->values);

	}		
}
?>