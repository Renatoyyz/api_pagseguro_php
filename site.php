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

$app->get('/payment/success', function(){

	echo "Deu Tudo certo!!!!!!";

});

$app->get('/payment', function() {

	//Aqui vem todos os valores da requisição - vem do $_POST , mas vou fazer simulações primeiro
	$order = $_POST;

	// var_dump($order);
	// exit;

	
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

$app->post('/processo', function(){

//Para testar vou trocar o $_POST pelo $jsonBody;

//var_dump($_POST);

$cpf = new Document(Document::CPF, $_POST['cpf'] );
$phone = new Phone($_POST['ddd'],$_POST['fone']);
$address = new Address('Rua M.M.D.C','974','n-a','PAuliceia','09690100','Sao Bernardo do Campo','SP','Brasil');//Não terá endereço, passar space ou qualquer caracter só para criar a instancia

$bithDate = new DateTime($_POST['born_date']);
$sender = new Sender($_POST['nome_comprador'], $cpf, $bithDate, $phone,$_POST['email'], $_POST['hash']);
$holder = new Holder($_POST['nome_comprador'], $cpf, $bithDate, $phone);
$shipping = new Shipping($address,0,0,false);//ter que ser false para não ter endereço
$installment = new Installment(1, (float)$_POST['valor_total'] );
$creditCard = new CreditCard($_POST['token'], $installment, $holder, $address );

$payment = new Payment("Reference", $sender, $shipping );
$item1 = new Item(1, "Texto1", 50.00, 1);
$payment->addItem($item1);
$item2 = new Item(2, "Texto2", 50.00, 1);
$payment->addItem($item2);
$item3 = new Item(3, "Texto3", 50.00, 1);
$payment->addItem($item3);

$payment->setCreditCard($creditCard);

$xml = Transporter::sendTransaction($payment, 1);



echo json_encode([
	"success"=> true
]);

exit;



//$dom = $payment->getDOMDocument();

//echo $dom->saveXML();

//$dom = new DOMDocument();
//$dom = $payment->getDOMDocument();

//$test = $payment->getDOMElement();

//$testNode = $dom->importNode($test, true);

//$dom->appendChild($testNode);

//echo $dom->saveXML();
//exit;

});

$app->post('/', function() {
//$app->get('/', function() {

	header('Content-type: application/json');
	$body = file_get_contents('php://input');
	$jsonBody = json_decode($body, true);

	// echo var_dump($jsonBody);
	// echo var_dump(Transporter::createSession());
    // exit;


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