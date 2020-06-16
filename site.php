<?php 

use \Witcare\Page;
use \GuzzleHttp\Client;
use \Witcare\PagSeguro\Config;
use \Witcare\PagSeguro\Transporter;

$app->get('/payment', function() {

	//Aqui vem todos os valores da requisição - vem do $_POST , mas vou fazer simulações primeiro
	$order = [
		"vltotal"=>500.00
	];

	
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

	var_dump( $_POST );
	exit;

});

$app->post('/', function() {

	header('Content-type: application/json');
	$body = file_get_contents('php://input');
	
	$jsonBody = json_decode($body, true);

	// echo json_encode($jsonBody);
	// exit;

	//Aqui vem todos os valores da requisição - vem do $_POST , mas vou fazer simulações primeiro
	$order = [
		"valor_total"=> $jsonBody['valor_total'],
		"numero_cartao"=> $jsonBody['numero_cartao'],
		"maxInstallmentNoInterest"=> $jsonBody['maxInstallmentNoInterest'],
		"brand"=>$jsonBody['brand'],
		"cvv"=>$jsonBody['cvv'],
		"expirationMonth"=> $jsonBody['expirationMonth'],
		"expirationYear"=> $jsonBody['expirationYear']
	];

	
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

	// header('Location: /payment');
	// exit;

});