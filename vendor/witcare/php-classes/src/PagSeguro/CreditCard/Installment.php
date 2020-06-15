<?php

namespace Witcare\PagSeguro\CreditCard;

use Exception;
use DOMDocument;
use DOMElement;
use Hcode\PagSeguro\Config;

class Installment {//class

    private $quantity;
    private $value;

    public function __construct(int $quantity, float $value){//__construct

        if($quantity < 1 || $quantity > Config::MAX_INSTALLMENT){
            throw new Exception("Número de parcelas inválido.");
        }

        if( $value <= 0 ){
            throw new Exception("Valor total inválido.");
        }

        $this->quantity = $quantity;
        $this->value = $value;

    }//__construct

    public function getDOMElement():DOMElement{//getDOMElement

        $dom = new DOMDocument();

        $installment = $dom->createElement("installment");
        $installment = $dom->appendChild($installment);

        $value = $dom->createElement("value", number_format($this->value, 2, ".", "") );
        $value = $installment->appendChild($value);

        $quantity = $dom->createElement("quantity", $this->quantity);
        $quantity = $installment->appendChild($quantity);

        $noInterestInstallmentQuantity = $dom->createElement("noInterestInstallmentQuantity", Config::MAX_INSTALLMENT_NO_INTEREST);
        $noInterestInstallmentQuantity = $installment->appendChild($noInterestInstallmentQuantity);

        return $installment;


    }//getDOMElement

}//class