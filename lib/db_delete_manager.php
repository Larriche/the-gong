<?php
class DBDeleteManager
{
	protected $connection;

	protected $table;

	protected $fields;

	protected $values;

	protected $operator = "AND";

	public function __construct()
    {
    	global $connection;
    	$this->connection = $connection;
    }

    public function from($table)
    {
    	$this->table = $table;
    	return $this;
    }

    public function where()
    {
    	$this->fields = func_get_args();
    	return $this;
    }

    public function match()
    {
    	$this->values = func_get_args();
    	return $this;
    }

    public function operator($operator)
    {
    	$this->operator = $operator;
    	return $this;
    }

    public function delete()
    {
    	$query = "DELETE from " . $this->table . " WHERE ";

        for($i = 0;$i < count($this->fields); $i++){
            $this->fields[$i] .= " = ? ";
        }

    	$query .= join($this->operator." " , $this->fields);
        print $query;

    	$stmt = $this->connection->prepare($query);
		$stmt->execute($this->values);
    }
}
?>