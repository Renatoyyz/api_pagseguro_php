<?php

namespace Witcare\PagSeguro;

class Config {//class

    const SANDBOX = true;

    const SANDBOX_EMAIL = "desenvolvimento@witcare.com.br";
    const PRODUCTION_EMAIL = "desenvolvimento@witcare.com.br";
                           
    const SANDBOX_TOKEN = "BE25B897CD2849CABB5CDC483679BC6D";
    const PRODUCTION_TOKEN = "32cb96bb-1a46-4111-bd85-f00035ff8542cc2d57c249c4832a921c0de5e713f2c052fe-b05f-45df-9edc-5af49d9b32df";
    
    const SANDBOX_SESSIONS = "https://ws.sandbox.pagseguro.uol.com.br/v2/sessions";
    const PRODUCTION_SESSIONS = "https://ws.pagseguro.uol.com.br/v2/sessions";

    const SANDBOX_URL_JS = "https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js";
    const PRODUCTION_URL_JS = "https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js";

    const PRODUCTION_URL_TRANSACTION = "https://ws.pagseguro.uol.com.br/v2/transactions";
    const SANDBOX_URL_TRANSACTION = "https://ws.sandbox.pagseguro.uol.com.br/v2/transactions";

    const SANDBOX_URL_NOTIFICATION = "https://ws.sandbox.pagseguro.uol.com.br/v2/transactions/notifications/";
    const PRODUCTION_URL_NOTIFICATION = "https://ws.pagseguro.uol.com.br/v2/transactions/notifications/";

    const MAX_INSTALLMENT_NO_INTEREST = 6;// Máximo de parcelas que a loja assume os juros
    const MAX_INSTALLMENT = 10;//Máximo de parcelas

    const NOTIFICATION_URL = "http://www.painel.witcare.com.br/payment/notification";
    
    public static function getAuthentication()
    {//getAuthentication

        if( Config::SANDBOX === true )
        {//if 1

            return [
                "email"=>Config::SANDBOX_EMAIL,
                "token"=>Config::SANDBOX_TOKEN
            ];

        }//if 1
          else{//else 1
            return [
                "email"=>Config::PRODUCTION_EMAIL,
                "token"=>Config::PRODUCTION_TOKEN
            ];
          }//else 1

    }//getAuthentication

    public static function getUrlSessions():string
    {//getUrlSessions
        
        return ( Config::SANDBOX === true ) ? Config::SANDBOX_SESSIONS : Config::PRODUCTION_SESSIONS;

    }//getUrlSessions

    public static function getUrlJS()
    {//getUrlJS

        return ( Config::SANDBOX === true ) ? Config::SANDBOX_URL_JS : Config::PRODUCTION_URL_JS;

    }//getUrlJS

    public static function getUrlTransaction(){//getUrlTransaction

        return ( Config::SANDBOX === true ) ? Config::SANDBOX_URL_TRANSACTION : Config::PRODUCTION_URL_TRANSACTION;

    }//getUrlTransaction

    public static function getNotificationTransactionURL(){//getUrlTransaction

        return ( Config::SANDBOX === true ) ? Config::SANDBOX_URL_NOTIFICATION : Config::PRODUCTION_URL_NOTIFICATION;

    }//getUrlTransaction

}//class

?>



