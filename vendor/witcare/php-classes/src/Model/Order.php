<?php 

namespace Witcare\Model;

use Witcare\DB\Sql;
use Witcare\Model;

class Order extends Model {

	const SESSION = "OrderSession";
	const SUCCESS = "Order-Success";
	const ERROR = "Order-Error";

	public function save()
	{

		$sql = new Sql();

		$results = $sql->select("CALL sp_orders_save(:idorder, :idcart, :iduser, :idstatus, :idaddress, :vltotal)", [
			':idorder'=>$this->getidorder(),
			':idcart'=>$this->getidcart(),
			':iduser'=>$this->getiduser(),
			':idstatus'=>$this->getidstatus(),
			':idaddress'=>$this->getidaddress(),
			':vltotal'=>$this->getvltotal()
		]);

		if (count($results) > 0) {
			$this->setData($results[0]);
		}

	}

	public function get($idorder)
	{

		$sql = new Sql();

		$results = $sql->select("
			SELECT * 
			FROM tb_orders a 
			INNER JOIN tb_ordersstatus b USING(idstatus) 
			INNER JOIN tb_carts c USING(idcart)
			INNER JOIN tb_users d ON d.iduser = a.iduser
			INNER JOIN tb_addresses e USING(idaddress)
			INNER JOIN tb_persons f ON f.idperson = d.idperson
			WHERE a.idorder = :idorder
		", [
			':idorder'=>$idorder
		]);

		if (count($results) > 0) {
			$this->setData($results[0]);
		}

	}

	public static function listAll()
	{

		$sql = new Sql();

		return $sql->select("
			SELECT * 
			FROM tb_orders a 
			INNER JOIN tb_ordersstatus b USING(idstatus) 
			INNER JOIN tb_carts c USING(idcart)
			INNER JOIN tb_users d ON d.iduser = a.iduser
			INNER JOIN tb_addresses e USING(idaddress)
			INNER JOIN tb_persons f ON f.idperson = d.idperson
			ORDER BY a.dtregister DESC
		");

	}

	public function delete()
	{

		$sql = new Sql();

		$sql->query("DELETE FROM tb_orders WHERE idorder = :idorder", [
			':idorder'=>$this->getidorder()
		]);

	}

	
// Vou carregar o idorder nessa classe só para testar
// Mudar depois
	public function setPagSeguroTransactionResponse(
		int $idorder,
		string $descode,
		float $vlgrossamount,
		float $vldisccountamount,
		float $vlfeeamount,
		float $vlnetamount,
		float $extraamount,
		string $despaymentlink = ""
	)
	{//setPagSeguroTransactionResponse

		$sql = new Sql();

		$sql->query("CALL sp_orderspagseguro_save(:idorder, :descode, :vlgrossamount, :vldisccountamount, :vlfeeamount,:vlnetamount, :extraamount, :despaymentlink)",[
			':idorder'=>$idorder,//
			':descode'=>$descode,
			':vlgrossamount'=>$vlgrossamount,
			':vldisccountamount'=>$vldisccountamount,
			':vlfeeamount'=>$vlfeeamount,
			':vlnetamount'=>$vlnetamount,
			':extraamount'=>$extraamount,
			':despaymentlink'=>$despaymentlink
		]);



	}//setPagSeguroTransactionResponse

}

?>