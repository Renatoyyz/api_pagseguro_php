<?php 

use \Witcare\Page;
use \GuzzleHttp\Client;
use \Witcare\PagSeguro\Config;


$app->get('/', function() {

$client = new Client();

$res = $client->request('POST', Config::getUrlSessions() . "?" . http_build_query(Config::getAuthentication()),['verify'=>false] );

echo $res->getBody()->getContents();

	// $page = new Page([
	// 	"header"=> false,
	// 	"footer"=> false
	// ]);
	
	// $page->setTpl("payment");

});