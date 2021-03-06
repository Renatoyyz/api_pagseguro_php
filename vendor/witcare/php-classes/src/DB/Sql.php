<?php 

namespace Witcare\DB;

class Sql {

	const HOSTNAME = "localhost:8889";
	const USERNAME = "root";
	const PASSWORD = "root";
	const DBNAME = "witcare";
	
	// const HOSTNAME = "mysql.maeda-st.com.br";
	// const USERNAME = "maedast01";
	// const PASSWORD = "renato123";
	// const DBNAME = "maedast01";

	// const HOSTNAME = "mysql.witcare.com.br";
	// const USERNAME = "witcare";
	// const PASSWORD = "renato123";
	// const DBNAME = "witcare";

	private $conn;

	public function __construct()
	{

		$this->conn = new \PDO(
			"mysql:dbname=".Sql::DBNAME.";host=".Sql::HOSTNAME, 
			Sql::USERNAME,
			Sql::PASSWORD
		);

	}

	private function setParams($statement, $parameters = array())
	{

		foreach ($parameters as $key => $value) {
			
			$this->bindParam($statement, $key, $value);

		}

	}

	private function bindParam($statement, $key, $value)
	{

		$statement->bindParam($key, $value);

	}

	public function query($rawQuery, $params = array())
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

	}

	public function select($rawQuery, $params = array()):array
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);

	}

}

 ?>