<?php

// objet pour permettre de se connecter 

class Paypal{


	private $user = 'phuveteau-facilitator_api1.gmail.com';
	private $pwd = '3KCUKH82ULL8A5H8';
	private $signature = 'AOFM0UioguvD4FiDVYN6oqhgd-bFAPaiRP2df2-F6WsDbRkoPW3-bd0j';
	public $endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
	public $errors = array();

	public function __construct($user = false, $pwd = false, $signature = false, $prod=false){

		if ($user){
			$user = $this->user;
		}

		if ($pwd){
			$pwd = $this->pwd;
		}

		if ($signature){
			$signature = $this->signature;
		}

		if ($prod){
			$this->endpoint =str_replace('sandbox', '', $this->endpoint);
		}
	}

		public function request($method, $params){

			$params = array_merge($params, array(
				'METHOD'   =>$method, 
				'VERSION'  =>'204', 
				'USER'     => $this->user,
				'PWD'      => $this->pwd, 
				'SIGNATURE'=> $this->signature));

			// converti le tableau en un string accepté par pyapal
			$params = http_build_query($params);

			//sert à envoyer une requete vers l'api
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => $this->endpoint, 
				CURLOPT_POST => 1, 
				CURLOPT_POSTFIELDS=>$params,
				CURLOPT_RETURNTRANSFER=>1,
				CURLOPT_SSL_VERIFYPEER=>0, // a zéro vu qu'on est en sandbox
				CURLOPT_SSL_VERIFYHOST=>0, // a zéro vu qu'on est en sandbox
				CURLOPT_VERBOSE => 1
			));

			$response = curl_exec($curl);
			parse_str($response, $responseArray);


			// double vérification pour être sur qu'il n'y a pas d'erreur
			if(curl_errno($curl)){
				// si il y a eu une erreur
				$this->errors = curl_error($curl);
				curl_close($curl);
				return false;
			}
			else{
			
				if($responseArray['ACK'] == 'Success'){
					curl_close($curl);
					return $responseArray;
				}
				else{
					var_dump($responseArray);
					$this->errors = curl_error($curl);
					curl_close($curl);
					return false;
				}
				
			}

		}


	

}



?>