<?php

namespace Witcare\PagSeguro;
use Exception;
use DOMDocument;
use DOMElement;

class Phone {//class

    private $areaCode;
    private $number;

    public function __construct(int $areaCode,int $number ){//__construct

        if( !$areaCode || $areaCode < 11 || $areaCode > 99 ){//if 1
            throw new Exception("Informe o DDD do telefone.");
        }//if 1

        if( !$number || strlen($number) < 8 || strlen($number) > 9 ){//if 2
            throw new Exception("Informe o número do telefone.");
        }//if 2

        $this->areaCode = $areaCode;
        $this->number = $number;

    }//__construct

    public function getDOMElement():DOMElement{//getDOMElement

        $dom = new DOMDocument();

        $phone = $dom->createElement("phone");
        $phone = $dom->appendChild($phone);

        $areaCode = $dom->createElement("areaCode", $this->areaCode);
        $areaCode = $phone->appendChild($areaCode);

        $number = $dom->createElement("number", $this->number);
        $number = $phone->appendChild($number);

        return $phone;


    }//getDOMElement
 
}//class