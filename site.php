<?php 

use \Witcare\Page;
use \GuzzleHttp\Client;
use \Witcare\PagSeguro\Config;
use \Witcare\PagSeguro\Transporter;
use \Witcare\PagSeguro\Document;
use \Witcare\PagSeguro\Phone;
use \Witcare\PagSeguro\Address;
use \Witcare\PagSeguro\Sender;
use \Witcare\PagSeguro\CreditCard\Holder;
use \Witcare\PagSeguro\CreditCard\Installment;
use \Witcare\PagSeguro\Shipping;
use \Witcare\PagSeguro\CreditCard;
use \Witcare\PagSeguro\Item;
use \Witcare\PagSeguro\Payment;
use \Witcare\Model\Order;
use \Witcare\DB\Sql;

$app->post('/payment/notification', function(){

	// "parameters": {
	// 	"notificationType": "transaction",
	// 	"notificationCode": "6313649F29122912DD244411CFBBF73C3632"
	//   }
	    

		Transporter::getNotification($_POS['notificationCode'], $_POS['notificationType']);


});

$app->get('/payment/success', function(){

	echo "Deu Tudo certo!!!!!!";

});

$app->get('/painel', function() {

	$order = new Order();

	$order->setPagSeguroTransactionResponse(
		1,
		'123456',
		123.12,
		0.0,
		0.0,
		0.0,
		10.0,
		'http://link'

	);

	echo 'Passou';
	
	});

$app->post('/processo', function(){

$cpf = new Document(Document::CPF, $_POST['cpf'] );
$phone = new Phone($_POST['ddd'],$_POST['fone']);
$address = new Address('Rua','1','#','bairro','09690100','Sao Bernardo do Campo','SP','Brasil');//Não terá endereço, passar space ou qualquer caracter só para criar a instancia
//$address = new Address('#','#','#','#','09690100','#','#','#');//Não terá endereço, passar space ou qualquer caracter só para criar a instancia

$bithDate = new DateTime($_POST['born_date']);
$sender = new Sender($_POST['nome_comprador'], $cpf, $bithDate, $phone,$_POST['email'], $_POST['hash']);
$holder = new Holder($_POST['nome_comprador'], $cpf, $bithDate, $phone);
$shipping = new Shipping($address,0,0,false);//ter que ser false para não ter endereço
$installment = new Installment(1, (float)$_POST['valor_total'] );
$creditCard = new CreditCard($_POST['token'], $installment, $holder, $address );

$payment = new Payment("0001", $sender, $shipping );
$item1 = new Item(1, "Texto1", (float)$_POST['valor_total'], 1);
$payment->addItem($item1);

$payment->setCreditCard($creditCard);

$xml = Transporter::sendTransaction($payment, 1);



echo json_encode([
	"success"=> true
]);

exit;

});

$app->get('/api_witcare', function(){//Pega todos

	header('Content-type: application/json');//Especifica a saída como formato json

	$method = $_SERVER['REQUEST_METHOD'];//verica qual o metodo que veio ( se POST ou GET )
	$sql = new Sql();

	$retorno = $sql->select('SELECT * FROM tb_user;');

	$json = json_encode($retorno);

	if($method === 'GET'){//GET
		// if( $json[$path[0]] ){
		// 	if( $param1 == "" ){
		// 		echo json_encode( $json[$path[0]] );
		// 	  }else{
		// 		$encontrado = findById($json[$path[0]], $param1);
		// 		  if( $encontrado >= 0 ){
		// 			echo json_encode( $json[$path[0]][$encontrado] ); 
		// 		  }else{
		// 			  echo 'Error.';
		// 		  }
		// 	  }
		// }else{
		// 	echo '[]';
		// }

		echo $json;
		exit;

	}//GET

});//Pega todos

$app->get('/api_witcare/:id', function($id){//Pega pelo id

	header('Content-type: application/json');//Especifica a saída como formato json

	$method = $_SERVER['REQUEST_METHOD'];//verica qual o metodo que veio ( se POST ou GET )
	$sql = new Sql();
	$retorno = $sql->select("SELECT * FROM tb_user WHERE iduser = :iduser;" , [
		":iduser"=>$id
	]);

	if( $retorno ){//if
		$json = json_encode($retorno);

		if($method === 'GET'){//GET
			// if( $json[$path[0]] ){
			// 	if( $param1 == "" ){
			// 		echo json_encode( $json[$path[0]] );
			// 	  }else{
			// 		$encontrado = findById($json[$path[0]], $param1);
			// 		  if( $encontrado >= 0 ){
			// 			echo json_encode( $json[$path[0]][$encontrado] ); 
			// 		  }else{
			// 			  echo 'Error.';
			// 		  }
			// 	  }
			// }else{
			// 	echo '[]';
			// }
			print_r($json);
			exit;

		}//GET
	}//if
	else{

		// $retorno = [
		// 	msg=>"Usuario nào existe."
		// ];
		echo 'Usuário não existe.';
		
	}

});//Pega pelo id

$app->post('/', function() {

	header('Content-type: application/json');
	$body = file_get_contents('php://input');
	$jsonBody = json_decode($body, true);


	// // //Aqui vem todos os valores da requisição - vem do $_POST , mas vou fazer simulações primeiro
	
	$order = $jsonBody;

		$page = new Page([
			"header"=> false,
			"footer"=> false
		]);
		
		$page->setTpl("payment",[
			"pagseguro"=>[
				"urlJS"=>Config::getUrlJS(),
				"id"=>Transporter::createSession()
			],
			"order"=>$order
		]);

});