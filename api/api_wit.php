<?php
//require_once('../vendor/witcare/php-classes/src/DB/Sql.php');
namespace Witcare\api;
use Witcare\DB\Sql;
//formato da api:
//http://www.painel.witcare.com.br/api/index.php/?path=promocoes , onde promocoes é o nome do objeto em db.json e $path é a variavel criada 
  //para atribuir os valores

    //$sql = new Sql();

    //$ret = $sql->select('SELECT * FROM witcare.tb_endereco;');

    //var_dump($ret);
  

if( !array_key_exists('path', $_GET) ){
    echo 'Error. Path missing.';
    exit;
}

$path = explode('/', $_GET['path']);//Se vier pela url (metodo GET), quebra valores

if( count($path)==0 || $path[0] == '' ){
    echo 'Error. Path missing.';
    exit; 
}

$param1 = "";

if( count($path)>1 ){
    $param1 = $path[1];
}



$contents = file_get_contents('db.json');// carrega o banco aqui

$json = json_decode($contents, true);//codifica o json

$method = $_SERVER['REQUEST_METHOD'];//verica qual o metodo que veio ( se POST ou GET )

function findById($vector, $param1){//findById

    $encontrado = -1;
              foreach( $vector as $key => $obj ){
                
                if( $obj['id'] == $param1 ){
                    $encontrado = $key;
                break;
                }
              }
              return $encontrado;
}//findById

header('Content-type: application/json');//Especifica a saída como formato json
$body = file_get_contents('php://input');//pega a entrada padrão do body ( se caso o método for POST e tiver conteúdo )

if($method === 'GET'){//GET
    if( $json[$path[0]] ){
        if( $param1 == "" ){
            echo json_encode( $json[$path[0]] );
          }else{
            $encontrado = findById($json[$path[0]], $param1);
              if( $encontrado >= 0 ){
                echo json_encode( $json[$path[0]][$encontrado] ); 
              }else{
                  echo 'Error.';
              }
          }
    }else{
        echo '[]';
    }
}//GET

if($method === 'POST'){//POST
    $jsonBody = json_decode($body, true);
    //$jsonBody['id'] = $jsonBody['id'];//  ou a funçào time() para gerar um numero em timestamp
    $jsonBody['id'] = time();

    if( !$json[$path[0]] ){
       $json[$path[0]] = [];
    }

    $json[$path[0]][] = $jsonBody;
    echo json_encode($jsonBody);
    file_put_contents('db.json', json_encode($json));

}//POST

if($method === 'DELETE'){//DELETE

    if( $json[$path[0]] ){
        if( $param1 == "" ){
            echo 'Error.';
          }else{
            $encontrado = findById($json[$path[0]], $param1);
              if( $encontrado >= 0 ){
                echo json_encode( $json[$path[0]][$encontrado] ); 
                unset($json[$path[0]][$encontrado]);
                file_put_contents('db.json', json_encode($json));
              }else{
                  echo 'Error.';
              }
          }
    }else{
        echo 'Error.';
    }

}//DELETE

if($method === 'PUT'){//PUT

    if( $json[$path[0]] ){
        if( $param1 == "" ){
            echo 'Error.';
          }else{
            $encontrado = findById($json[$path[0]], $param1);
              if( $encontrado >= 0 ){ 
                $jsonBody = json_decode($body, true);
                $jsonBody['id'] = $param1;
                $json[$path[0]][$encontrado] = $jsonBody;
                echo json_encode( $json[$path[0]][$encontrado] );
                file_put_contents('db.json', json_encode($json));
              }else{
                  echo 'Error.';
              }
          }
    }else{
        echo 'Error.';
    }

}//PUT