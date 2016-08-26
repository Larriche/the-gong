<?php
class DBSelectManager
{
	protected $table;

	protected $fields;

	protected $whereFields;

	protected $values;

    protected $hasLimit = false;

	protected $start;

    protected $offset;

    protected $orderField;

    protected $orderType;

    protected $connection;

    protected $command;

    protected $operator = "AND";

    protected $useManualStatement = false;

    public function __construct()
    {
    	global $connection;
    	$this->connection = $connection;
    }

    public function select()
    {
    	$this->fields = func_get_args();
    	return $this;
    }

    public function from($table)
    {
    	$this->table = $table;
    	return $this;
    }

    public function where()
    {
    	$this->whereFields = func_get_args();
    	return $this;
    }

    public function match()
    {
    	$this->values = func_get_args();
    	return $this;
    }

    public function limit($start,$offset)
    {
    	$this->hasLimit = true;
    	$this->start = $start;
    	$this->offset = $offset;

        return $this;
    }

    public function manual($statement)
    {
        $this->useManualStatement = true;
        $this->statement = $statement;
        return $this;
    }

    public function useOperator($operator)
    {
        $this->operator = $operator;
        return $this;
    }

    public function orderBy($field,$type)
    {
        $this->orderField = $field;
        $this->orderType = $type;
        return $this;
    }

    public function getRows()
    {
        if($this->useManualStatement)
            $query = $this->command;

        else{
            $query = "SELECT " . join(" , ",$this->fields)." FROM {$this->table}";

            if(!empty($this->whereFields)){
                $query .= " WHERE ";
                for($i = 0;$i < count($this->whereFields);$i++) {
                    $this->whereFields[$i] = $this->whereFields[$i]." = ?";
                }

                $query .= join(' '.$this->operator.' ',$this->whereFields);
            }

            if($this->orderField){
                $query .= " ORDER BY {$this->orderField} {$this->orderType}";
            }

            if(($this->hasLimit)){
                $query .= " LIMIT ".$this->start." , ".$this->offset;
            }
        }

		$stmt = $this->connection->prepare($query);
		$stmt->execute($this->values);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	    return $rows;
    }


}
?>