<?php
class User
{
	protected $username;

	protected $password;

	protected $id;

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setUsername($username)
	{
		$this->username = $username;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function save()
	{
		if($this->loadedFromTable){
			$dbman = new DBUpdateManager();

			$dbman->in('users')->update('username','password')->with($this->username,
				$this->password)->where('id')->match($this->id)->execute();
		}
		else{
			$dbman = new DBInsertManager();

			$dbman->into('users')->insert($this->username,$this->password)->fields('username','password')
			   ->execute();
		}
	}

	public static function createFromRow($row)
	{
		$user = new User();

		$user->loadedFromTable = true;
		$user->setId($row['id']);
		$user->setUsername($row['username']);
		$user->setPassword($row['password']);

		return $user;
	}

	public static function isValidLogin($username,$password)
	{
		$dbman = new DBSelectManager();
		$rows = $dbman->select('*')->from('users')->where('username')
		   ->match($username)->getRows();

		if(count($rows) == 0){
			return false;
		}
		else{
			$hashedPasswrd = $rows[0]['password'];

			if(password_verify($password,$hashedPasswrd)){
				return true;
			}
			else{
				return false;
			}
		}

		return false;
	}

	public static function find($id)
	{
		$dbman = new DBSelectManager();

		$rows = $dbman->select('*')->from('users')->where('id')
		  ->match($id)->getRows();

		if(count($rows)){
			return User::createFromRow($rows[0]);
		}

		return null;
	}

	public static function findByUsername($username)
	{
		$dbman = new DBSelectManager();

		$rows = $dbman->select('*')->from('users')->where('username')->match($username)->getRows();

		if(count($rows) > 0){
			$user = User::createFromRow($rows[0]);
			return $user;
		}

		return null;
	}

}
?>