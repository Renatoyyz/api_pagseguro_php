<?php if(!class_exists('Rain\Tpl')){exit;}?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1"> -->
    <title>Checkout example · Bootstrap</title>

    <!-- <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/checkout/"> -->

    <!-- Bootstrap core CSS -->
    <!-- <link href="/assets/dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="/res/site/css/bootstrap.min.css"> -->

    <!-- Custom styles for this template -->
    <!-- <link href="/assets/form-validation.css" rel="stylesheet"> -->
  </head>

  <body>

        <h1>Renato oliveira</h1>

        
<div id="campos" >
    <?php $counter1=-1;  if( isset($order) && ( is_array($order) || $order instanceof Traversable ) && sizeof($order) ) foreach( $order as $key1 => $value1 ){ $counter1++; ?>
      <input type="hidden" name="<?php echo htmlspecialchars( $key1, ENT_COMPAT, 'UTF-8', FALSE ); ?>" value="<?php echo htmlspecialchars( $value1, ENT_COMPAT, 'UTF-8', FALSE ); ?>" >
    <?php } ?>
</div>
        <!-- Biblioteca jQuery -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <!-- Blibliotca pagseguro -->
        <script src="<?php echo htmlspecialchars( $pagseguro["urlJS"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"></script>
        <script type="text/javascript">
            PagSeguroDirectPayment.setSessionId('<?php echo htmlspecialchars( $pagseguro["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>');
        </script>

        <script>

function showValues(){
    var params = {};
            var formData = $(":input").serializeArray();//serializa os inputs dessa página
            $.each(formData, function (index, field) {
                params[field.name] = field.value;//carrega na variável que vai ser enviado
            });
            


            

            PagSeguroDirectPayment.getPaymentMethods({//getPaymentMethods
                    amount: parseFloat(params.valor_total),
                    success: function (response) {
                        // Retorna os meios de pagamento disponíveis.
                        //console.log(response.paymentMethods.CREDIT_CARD.options.MASTERCARD);
                    },
                    error: function (response) {
                        // Callback para chamadas que falharam.
                        //console.log(response);
                    },
                    complete: function (response) {
                        // Callback para todas chamadas.
                        //console.log(response);
                    }
                });//getPaymentMethods

            PagSeguroDirectPayment.getBrand({//getBrand
                    cardBin: parseInt(params.numero_cartao),//manda os seis primeiro digito do cartao 
                    success: function (response) {
                        //bandeira encontrada
                        // console.log("Sucesso: ");
                        // console.log(response);
                    },
                    error: function (response) {
                        //tratamento do erro
                        // console.log("Erro: ");
                        // console.log(response);
                    },
                    complete: function (response) {
                        //tratamento comum para todas chamadas
                        // console.log("Ambos: ");
                        // console.log(response);
                    }
                });//getBrand

            PagSeguroDirectPayment.getInstallments({//getInstallments
                    amount: parseFloat(params.valor_total),
                    maxInstallmentNoInterest: parseInt(params.maxInstallmentNoInterest),//quanto de parcelas que o lojista assume os juros, no mínimo 2
                    brand: params.brand,
                    success: function (response) {
                        // Retorna as opções de parcelamento disponíveis
                        // console.log("Sucesso: ");
                        // console.log(response);
                    },
                    error: function (response) {
                        // callback para chamadas que falharam.
                        // console.log("Erro: ");
                        // console.log(response);
                    },
                    complete: function (response) {
                        // Callback para todas chamadas.
                    }
                });//getInstallments
                

            PagSeguroDirectPayment.createCardToken({//createCardToken
                    cardNumber: params.numero_cartao, // Número do cartão de crédito
                    brand: params.brand, // Bandeira do cartão
                    cvv: params.cvv, // CVV do cartão
                    expirationMonth: params.expirationMonth, // Mês da expiração do cartão
                    expirationYear: params.expirationYear, // Ano da expiração do cartão, é necessário os 4 dígitos.
                    success: function (response) {
                        // Retorna o cartão tokenizado.

                        //Adiciona novos valores carregado do pagseguro
                        params.token = response.card.token;
                        params.hash = PagSeguroDirectPayment.getSenderHash();

                        console.log('Valor hash');
                        console.log(params.hash);

                        $.post(
                            "/processo",
                            $.param(params),
                            function(r){

                            var response_loc = JSON.parse(r);
                            if(response_loc.success === true){
                              window.location.href = "/payment/success";
                            }
                            else{
                              
                            }
                            }
                        );


                    },
                    error: function (response) {
                        // Callback para chamadas que falharam.
                        console.log("Erro: ");
                        console.log(response);
                    },
                    complete: function (response) {
                        // Callback para todas chamadas.
                    }
                });//createCardToken
            };
            $("#campos").show(showValues());
        </script>

  </body>


</html>
