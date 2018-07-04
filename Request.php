<?php
 
    /*Dados da requisição, no caso dos campos DiscountValue, 
      IncreasePercent, IncreaseValue, só é permitido uma modalidade, isso vale para acréscimo,
      lembre-se que ambos não podem coexistir na mesma instrução*/
	  
	$transmitObject = array(
	    'FraudId' => "eb7109065ea593ecda6ce9ff9065579d",
		'Reference' => 017,
		'Currency' => 'BRL',
		'Price' => 200.00,
		'PaymentType' => 21,
		'DiscountPercent' => 0,						
		'DiscountValue' => 0,
		'IncreasePercent' => 0,
		'IncreaseValue' => 0,
		//'RequestDate' => '2018-01-10 17:45:27',
		
		/* Definindo dados do pagador, os mesmos são obrigatórios,
		Qualquer dúvida veja na aba "Dados de Envio"
		http://developers.koin.com.br/ptbr */
		
		'Buyer' => array(	
			'Name' => 'Digite o nome',
			'Email' => 'Digite o e-mail',
			'Ip' => '',						
			'BuyerType' => 1,
			'Documents' => array(
				array(
					'Key' => 'CPF',
					'Value' => '56249611207' 
				)
			),
			'AdditionalInfo' => array(
				array(
					'Key' => 'Birthday',
					'Value' => '1900-01-01'
				)
			),  
			
			// Definindo telefones do pagador, dados obrigatórios, os dados são compostos em uma lista.
			
			'Phones' =>	array(
			array(
				'AreaCode' => 11,
				'Number' => "976728857",
				'PhoneType' => 2,
				)
			),
			// Definindo dados do endereço.
			
			'Address' => array(
				'City' => 'São Paulo',
				'State' => 'SP',
				'Country' => 'Brasil',
				'District' => 'Jardim Paulista',
				'Street' => 'Av. Brigadeiro Luiz Antonio',
				'Number' => 3751,
				'Complement' => '',
				'ZipCode' => '01401-001',
				'AddressType' => 1
			)
	),
		
		// Definindo dados de entrega/frete.
		
		'Shipping' => array(
			'Address' => array(
				'City' => 'São Paulo',
				'State' => 'SP',
				'Country' => 'Brasil',
				'District' => 'Jardim Paulista',
				'Street' => 'Av. Brigadeiro Luiz Antonio',
				'Number' => 3751,
				'Complement' => '',
				'ZipCode' => '01401-001',
				'AddressType' => 1
			),
			'Price' => "40.50",
			'DeliveryDate' => '2018-01-11 00:00:00',
			'ShippingType' => 1,
		),
		
		/* Definindo dados do produto, com exceção dos campos Category e Atributes, 
		o restante é obrigatório */
		
		'Items' => array(
			array(
				'Reference' => '12',							
				'Description' => 'Deo Colonia 12 60ML',
				'Category' => "Produtos",
				'Quantity' => 1,
				'Price' => 159.50,
				'Attributes' => array ()
				),			
			)
	);
	
	//Criando um JSON
	$jsonObject = json_encode($transmitObject);
	
	
	//Definindo a URL de requisição
	$url = "http://api.qa.koin.in:8000/V1/TransactionService.svc/Request";
	//$url = "https://api.koin.com.br/V1/TransactionService.svc/Request";
	
	//Chaves de autenticação
	$consumerKey = "1BFCF567A63E4B6FB38F6A22FFA21FFE"; 		
	$secretKey = "50856FDA556747A7860C3295C25FEA26";						
	
	//convertendo o formato do timezone para UTC 
	date_default_timezone_set("UTC");
	

	//Obtendo a hora do servidor
	$time = time();
    
	//Criando o hash de autenticação
	$binaryHash = hash_hmac('sha512', $url.$time, $secretKey, true); 
	
	//Convertendo para Base64
	$hash = base64_encode($binaryHash);
	
	//Enviando a requisição
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonObject);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json; charset=utf-8", 
											   "Content-Length:".strlen($jsonObject), 
											   "Authorization: {$consumerKey},{$hash},{$time}"));
			
	try {
		
		$response = curl_exec($ch);
		curl_close ($ch);
		
		echo $response;
		
	} catch (Exception $e) {
	  echo "Ocorreu um erro : ",  $e->getMessage(), "\n";
	}

	
	?>