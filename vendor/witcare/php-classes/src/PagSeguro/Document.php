<?php

namespace Witcare\PagSeguro;

use Exception;
use DOMDocument;
use DOMElement;

class Document {//class

    private $type;
    private $value;

    const CPF = "CPF";

    public function __construct(string $type, string $value){//construct

        if( !$value )
        {
            throw new Exception("Informe o valor do documento.");
        }

        switch($type)
        {//switch
            case Document::CPF:
                if( !Document::isValidCPF($value) ){
                    throw new Exception("CPF Inválido");
                }
            break;
        }//switch

        $this->type = $type;
        $this->value = $value;

    }//construct

    public static function isValidCPF($number):bool{//isValidCPF

        $number = preg_replace('/[^0-9]/', '', (string) $number);

        if (strlen($number) != 11)
            return false;
    
        for ($i = 0, $j = 10, $sum = 0; $i < 9; $i++, $j--)
            $sum += $number{$i} * $j;
        $rest = $sum % 11;
        if ($number{9} != ($rest < 2 ? 0 : 11 - $rest))
            return false;
    
        for ($i = 0, $j = 11, $sum = 0; $i < 10; $i++, $j--)
            $sum += $number{$i} * $j;
        $rest = $sum % 11;
    
        return ($number{10} == ($rest < 2 ? 0 : 11 - $rest));

    }//isValidCPF

    public function getDOMElement():DOMElement{//getDOMElement

        $dom = new DOMDocument();

        $document = $dom->createElement("document");
        $document = $dom->appendChild($document);

        $type = $dom->createElement("type", $this->type);
        $type = $document->appendChild($type);

        $value = $dom->createElement("value", $this->value);
        $value = $document->appendChild($value);

        return $document;


    }//getDOMElement

 
}//class