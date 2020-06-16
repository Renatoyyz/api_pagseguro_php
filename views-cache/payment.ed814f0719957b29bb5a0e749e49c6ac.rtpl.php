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

        <?php $counter1=-1;  if( isset($order) && ( is_array($order) || $order instanceof Traversable ) && sizeof($order) ) foreach( $order as $key1 => $value1 ){ $counter1++; ?>
           <h3><?php echo htmlspecialchars( $value1, ENT_COMPAT, 'UTF-8', FALSE ); ?></h3> 
        <?php } ?>

        <!-- Biblioteca jQuery -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <!-- Blibliotca pagseguro -->
        <script src="<?php echo htmlspecialchars( $pagseguro["urlJS"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"></script>
        <script type="text/javascript">
            PagSeguroDirectPayment.setSessionId('<?php echo htmlspecialchars( $pagseguro["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>');
        </script>

        <script>
            PagSeguroDirectPayment.getPaymentMethods({//getPaymentMethods
                    amount: parseFloat("<?php echo htmlspecialchars( $order["valor_total"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"),
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
                    cardBin: ("<?php echo htmlspecialchars( $order["numero_cartao"], ENT_COMPAT, 'UTF-8', FALSE ); ?>".substring(0, 6)),//manda os seis primeiro digito do cartao 
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
                    amount: parseFloat("<?php echo htmlspecialchars( $order["valor_total"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"),
                    maxInstallmentNoInterest: parseInt('<?php echo htmlspecialchars( $order["maxInstallmentNoInterest"], ENT_COMPAT, 'UTF-8', FALSE ); ?>'),//quanto de parcelas que o lojista assume os juros, no mínimo 2
                    brand: '<?php echo htmlspecialchars( $order["brand"], ENT_COMPAT, 'UTF-8', FALSE ); ?>',
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

                var params = {};

            PagSeguroDirectPayment.createCardToken({//createCardToken
                    cardNumber: '<?php echo htmlspecialchars( $order["numero_cartao"], ENT_COMPAT, 'UTF-8', FALSE ); ?>', // Número do cartão de crédito
                    brand: '<?php echo htmlspecialchars( $order["brand"], ENT_COMPAT, 'UTF-8', FALSE ); ?>', // Bandeira do cartão
                    cvv: '<?php echo htmlspecialchars( $order["cvv"], ENT_COMPAT, 'UTF-8', FALSE ); ?>', // CVV do cartão
                    expirationMonth: '<?php echo htmlspecialchars( $order["expirationMonth"], ENT_COMPAT, 'UTF-8', FALSE ); ?>', // Mês da expiração do cartão
                    expirationYear: '<?php echo htmlspecialchars( $order["expirationYear"], ENT_COMPAT, 'UTF-8', FALSE ); ?>', // Ano da expiração do cartão, é necessário os 4 dígitos.
                    success: function (response) {
                        // Retorna o cartão tokenizado.
                        // console.log("Sucesso: ");
                        // console.log("TOKEN: ", response.card.token);
                        // console.log("HASH: " ,PagSeguroDirectPayment.getSenderHash());

                        params.token = response.card.token;
                        params.hash = PagSeguroDirectPayment.getSenderHash();
                        params.valor_total = '<?php echo htmlspecialchars( $order["valor_total"], ENT_COMPAT, 'UTF-8', FALSE ); ?>';
                        params.numero_cartao = '<?php echo htmlspecialchars( $order["numero_cartao"], ENT_COMPAT, 'UTF-8', FALSE ); ?>';
                        params.maxInstallmentNoInterest = parseInt('<?php echo htmlspecialchars( $order["maxInstallmentNoInterest"], ENT_COMPAT, 'UTF-8', FALSE ); ?>');
                        params.brand = '<?php echo htmlspecialchars( $order["brand"], ENT_COMPAT, 'UTF-8', FALSE ); ?>';
                        params.cvv = '<?php echo htmlspecialchars( $order["cvv"], ENT_COMPAT, 'UTF-8', FALSE ); ?>';
                        params.expirationMonth = '<?php echo htmlspecialchars( $order["expirationMonth"], ENT_COMPAT, 'UTF-8', FALSE ); ?>';
                        params.expirationYear = '<?php echo htmlspecialchars( $order["expirationYear"], ENT_COMPAT, 'UTF-8', FALSE ); ?>';

                        $.post(
                            "/processo",
                            $.param(params),
                            function(r){
                                console.log(r);
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

        </script>

  </body>


</html>
