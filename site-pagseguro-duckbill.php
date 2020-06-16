<?php

use Hcode\Page;
use \GuzzleHttp\Client;
use Hcode\PagSeguro\Config;
use Hcode\PagSeguro\Transporter;
use Hcode\PagSeguro\Document;
use Hcode\PagSeguro\Phone;
use Hcode\PagSeguro\Address;
use Hcode\PagSeguro\Sender;
use Hcode\PagSeguro\Item;
use Hcode\PagSeguro\Payment;
use Hcode\PagSeguro\CreditCard;
use Hcode\PagSeguro\CreditCard\Holder;
use Hcode\PagSeguro\Shipping;
use Hcode\PagSeguro\Bank;
use Hcode\PagSeguro\CreditCard\Installment;


$app->get("/payment_duckbill/success/debit", function(){

    
    $page = new Page([
        'header'=>false,
        'footer'=>false
    ]);

    $page->setTpl("payment-success-debit", [
        'order'=>[
            'idorder'=>1,
            'despaymentlink'=>""
        ]
    ]);

});

$app->get("/payment_duckbill/success", function(){

    
    $page = new Page([
        'header'=>false,
        'footer'=>false
    ]);

    $page->setTpl("payment-success", [
        'order'=>[
            'idorder'=>1
        ]
    ]);

});

$app->post("/payment_duckbill/debit", function(){

    // echo "Renato Oliveira";
    // echo "</br>";
    // var_dump($_POST);
    // // echo json_encode($_POST);
    //exit;

    $cpf = new Document(Document::CPF,$_POST['cpf']);
    $phone = new Phone($_POST['ddd'], $_POST['phone']);
    $shippingAddress = new Address(
        "M.M.D.C",
        974,
        "",
        "Pauliceia",
        "09690100",
        "Sao Bernardo do Campo",
        "SP",
        "Brasil"
    );
    $birthDate = new DateTime('01-01-1990');

    $sender = new Sender("Renato Oliveira",$cpf,$birthDate,$phone,"admin@sandbox.pagseguro.com.br", $_POST['hash']);

    $shipping = new Shipping($shippingAddress,"0.00",Shipping::PAC);

    $payment = new Payment("0001",$sender,$shipping);

    $item1 = new Item(1,"O produto 1", 100, 1.0);
    $item2 = new Item(2,"O produto 2", 100, 1.0);

    $payment->addItem($item1);
    $payment->addItem($item2);

    $bank = new Bank("BRADESCO");

    $payment->setBank($bank);

    //$dom = $payment->getDOMDocument();
    

    Transporter::sendTransaction($payment,1 );
    echo json_encode([
        'success'=>true
    ]);

    //$dom = new DOMDocument();
    //$dom = $payment->getDOMDocument();

    // // $test = $creditCard->getDOMElement();

    // // $testNode = $dom->importNode($test, true);
    
    // // $dom->appendChild($testNode);

    //echo $dom->saveXML();
    // echo "4";
    //exit;

});

$app->post("/payment_duckbill/credit", function(){

    // echo "Renato Oliveira";
    // echo "</br>";
    // var_dump($_POST);
    // // echo json_encode($_POST);
    //exit;

    $cpf = new Document(Document::CPF,$_POST['cpf']);
    $phone = new Phone($_POST['ddd'], $_POST['phone']);
    $shippingAddress = new Address(
        "M.M.D.C",
        974,
        "",
        "Pauliceia",
        "09690100",
        "Sao Bernardo do Campo",
        "SP",
        "Brasil"
    );
    $birthDate = new DateTime('01-01-1990');
    $sender = new Sender("Renato Oliveira",$cpf,$birthDate,$phone,"admin@sandbox.pagseguro.com.br", $_POST['hash']);
    $holder = new Holder("Renato Oliveira",$cpf,$birthDate,$phone);
    $shipping = new Shipping($shippingAddress,"0.00",Shipping::PAC);
    $installment = new Installment(1, "200.00");
    $billingAddress = new Address(
        "M.M.D.C",
        974,
        "",
        "Pauliceia",
        "09690100",
        "Sao Bernardo do Campo",
        "SP",
        "Brasil"
    );
    $creditCard = new CreditCard($_POST['token'],$installment,$holder,$billingAddress);

    $payment = new Payment("0001",$sender,$shipping);
    $item1 = new Item(1,"O produto 1", 100, 1.0);
    $item2 = new Item(2,"O produto 2", 100, 1.0);

    $payment->addItem($item1);
    $payment->addItem($item2);

    $payment->setCreditCard($creditCard);

    $dom = $payment->getDOMDocument();
    

    Transporter::sendTransaction($payment,1 );
    echo json_encode([
        'success'=>true
    ]);

    //$dom = new DOMDocument();
    //$dom = $payment->getDOMDocument();

    // // $test = $creditCard->getDOMElement();

    // // $testNode = $dom->importNode($test, true);
    
    // // $dom->appendChild($testNode);

    //echo $dom->saveXML();
    // echo "4";
    //exit;

});

//order.vltotal
$app->get('/payment_duckbill', function(){

    $order = [
        "vltotal"=>192.45,
        "cardBin"=>4111111111111111,
        "yearCard"=>2023,
        "mouthCard"=>3,
        "cpf"=>"44606396024",
        "ddd"=>11,
        "phone"=>988991100
    ];

    $page = new Page([
        "header"=>false,
        "footer"=>false
    ]);

	$page->setTpl("payment_duckbill", [
        "order"=>$order,
        "msgError"=>"Um erro qualquer",
        "pagseguro"=>[
            "urlJS"=>Config::getUrlJS(),
            "id"=>Transporter::createSession(),
            "maxInstallmentNoInterest"=>Config::MAX_INSTALLMENT_NO_INTEREST,
            "maxInstallment"=>Config::MAX_INSTALLMENT
        ]
    ]);
  
});

//order.vltotal
$app->get('/payment_duckbill/teste', function(){

    $client = new Client();

    $res = $client->request('POST', Config::getUrlSessions() . "?" . http_build_query(Config::getAuthentication()), [
        'verify'=>false
    ] );

   // echo $res->getStatusCode();
    
    echo $res->getBody()->getContents();
    // $page = new Page([
    //     "header"=>false,
    //     "footer"=>false
    // ]);

	// $page->setTpl("payment_duckbill");
    exit;
});