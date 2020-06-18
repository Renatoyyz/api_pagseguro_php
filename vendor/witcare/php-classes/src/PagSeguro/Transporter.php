<?php

namespace Witcare\PagSeguro;

use \GuzzleHttp\Client;
use Hcode\Model\Order;


class Transporter {//class

    public static function createSession(){//func 1

    $client = new Client();
    $response = $client->request
            (
                'POST', 
                Config::getUrlSessions() . "?" . http_build_query(Config::getAuthentication()), 
                [
                    'verify'=>false
                ] 
            );
    
    $xml = simplexml_load_string( $response->getBody()->getContents() );
    return ((string)$xml->id);
    }//func 1

    // Vou carregar o idorder nessa classe só para testar
    // Mudar depois
    public static function sendTransaction(Payment $payment, int $idorder){//sendTransaction

            $client = new Client();
            $response = $client->request
                    (
                        'POST', 
                        Config::getUrlTransaction() . "?" . http_build_query(Config::getAuthentication()), 
                        [
                            'verify'=>false,
                            'headers'=>[
                                'Content-Type'=>'application/xml'
                            ],
                            'body'=>$payment->getDOMDocument()->saveXML()
                        ] 
                    );
            
            $xml = simplexml_load_string( $response->getBody()->getContents() );

            //var_dump($xml);

        //     $order = new Order();

        //     $order->setPagSeguroTransactionResponse(
        //         $idorder,
        //         (string)$xml->code,
        //         (float)$xml->grossAmount,
        //         (float)$xml->discountAmount,
        //         (float)$xml->feeAmount,
        //         (float)$xml->netAmount,
        //         (float)$xml->extraAmount,
        //         (string)$xml->paymentLink

        //     );

        //    //var_dump('OK!!!!!');
           return $xml;

    }//sendTransaction

}//class