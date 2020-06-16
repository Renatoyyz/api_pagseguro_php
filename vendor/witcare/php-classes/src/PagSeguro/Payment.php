<?php

namespace Witcare\PagSeguro;

use Exception;
use DOMDocument;
use DOMElement;
use Hcode\PagSeguro\Payment\Method;

class Payment {//class

    private $mode = "default";
    private $currency = "BRL";
    private $extraAmount = 0;
    private $reference = "";
    private $items = [];
    private $sender;
    private $shipping;
    private $method;
    private $creditCard;
    private $bank;

    public function __construct(string $reference,Sender $sender, Shipping $shipping, float $extraAmount = 0 ){//__construct

        $this->sender = $sender;
        $this->shipping = $shipping;
        $this->reference = $reference;//Referencia é o número da sua loja
        $this->extraAmount = number_format($extraAmount, 2 , ".", "");

    }//__construct

    public function addItem(Item $item){//addItem

        array_push($this->items, $item);

    }//addItem

    public function setCreditCard(CreditCard $creditcard){//setCreditCard

        $this->creditCard = $creditcard;
        $this->method = Method::CREDIT_CARD;

    }//setCreditCard

    public function setBank(Bank $bank){//setBank

        $this->bank = $bank;
        $this->method = Method::DEBIT;

    }//setBank

    public function SetBoleto(){//SetBoleto

        $this->method = Method::BOLETO;

    }//SetBoleto

    public function getDOMDocument():DOMDocument{//getDOMDocument

        $dom = new DOMDocument("1.0", "ISO-8859-1");// Aqui já atribui os valores de prólogo
        
        $payment = $dom->createElement("payment");
        $payment = $dom->appendChild($payment);

        $mode = $dom->createElement("mode", $this->mode);
        $mode = $payment->appendChild($mode);

        $currency = $dom->createElement("currency", $this->currency);
        $currency = $payment->appendChild($currency);

        $notificationURL = $dom->createElement("notificationURL", Config::NOTIFICATION_URL);
        $notificationURL = $payment->appendChild($notificationURL);

        //conferir
        $receiverEmail = $dom->createElement("receiverEmail", Config::PRODUCTION_EMAIL);
        $receiverEmail = $payment->appendChild($receiverEmail);

        $sender = $this->sender->getDOMElement();
        $sender = $dom->importNode($sender, true);
        $sender = $payment->appendChild($sender);

        $items = $dom->createElement("items");
        $items = $payment->appendChild($items);

        foreach($this->items as $_item) { 
            $item = $_item->getDOMElement();
            $item = $dom->importNode($item, true);
            $item = $items->appendChild($item);
        }

        $reference = $dom->createElement("reference", $this->reference);
        $reference = $payment->appendChild($reference);

        $shipping = $this->shipping->getDOMElement();
        $shipping = $dom->importNode($shipping, true);
        $shipping = $payment->appendChild($shipping);

        $extraAmount = $dom->createElement("extraAmount", $this->extraAmount);
        $extraAmount = $payment->appendChild($extraAmount);

        $method = $dom->createElement("method", $this->method);
        $method = $payment->appendChild($method);

        switch($this->method)
        {//switch

            case Method::CREDIT_CARD:
                $creditCard = $this->creditCard->getDOMElement();
                $creditCard = $dom->importNode($creditCard, true);
                $creditCard = $payment->appendChild($creditCard);
            break;
            case Method::DEBIT:
                $bank = $this->bank->getDOMElement();
                $bank = $dom->importNode($bank, true);
                $bank = $payment->appendChild($bank);
            break;

        }//switch

        return $dom;

    }//getDOMDocument
 
}//class