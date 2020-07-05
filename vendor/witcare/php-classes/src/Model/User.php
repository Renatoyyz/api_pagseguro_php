<?php 

namespace Witcare\Model;

use Witcare\DB\Sql;
use Witcare\Model;

class User extends Model {

	public function save()
	{//save

		$sql = new Sql();

		$results = $sql->select("CALL save_new_user(:desnome, :desemail, :dessenha);", [
			':desnome'=>$this->getdesnome(),
			':desemail'=>$this->getdesemail(),
			':dessenha'=>$this->getdessenha()
		]);

		if (count($results) > 0) {
			$this->setData($results[0]);
		}

	}//save

}

?>