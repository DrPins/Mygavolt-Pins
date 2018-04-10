<?php

require_once('includes/header.php');
require_once('includes/functions_panier.php');
require_once('includes/paypal.php');

//partie pour essayer de récupérer les données sans passer par la validation paypal
	$products = '';

	$paypal = new Paypal();

	$totalTTC = montantGlobalTTC();
	$totalHT = montantGlobal();
	$TVA = $totalTTC - $totalHT;
	$fraisDePort = 20;
	$currency_code = 'EUR';
	$date_achat = date("Ymd");
	$user_id = $_SESSION['user_id'];

	for ($i=0; $i < count($_SESSION['panier']['lib_prod']); $i++) {
		//va rajouter à l'array product l'id du produit
		$products.=$_SESSION['panier']['id_prod'][$i];
		if($i<=1){
			$products.=', ';
		}
	}
	//a la sortie du for, la variables products va contenir tous les produits




	$insert = $db->prepare("INSERT INTO transactions (name, street, city, date_achat, transaction_id, amount, shipping, currency_code, user_id, products)
				VALUES('', '', '', '$date_achat', '', '$totalTTC', '$fraisDePort', '$currency_code', '$user_id', '$products') ");

	$insert->execute();

	supprimePanier();

	header('Location: success.php');




//**********************************end***********************************************

/*


$response = $paypal -> request('GestExpressCheckoutDetails',array(

	'TOKEN'=>$_GET['token']));

if($response){
	if($response['CHECKOUTSTATUS']=='PaymentActionCompleted'){

		$response2 = $paypal -> request('GestTransactionDetails',array(
			'TRANSACTIONID'=>$response['PAYEMENTREQUEST_0_TRANSACTIONID']));

	var_dump($response2);
		die('Ce paiement a déjà été validé');
	}
}
else{
	var_dump($paypal->errors);
	die();
}

$response = $paypal->request('DoExpressCheckoutPayment', array(
	'TOKEN'=> $_GET['token'],
	'PAYERID'=>$_GET['PayerID'],
	'PAYMENTACTION'=>'Sale',
	'PAYMENTREQUEST_0_AMT'=> $totalTTC + $fraisDePort,
	'PAYEMENTREQUEST_0_CURRENCYCODE'=> 'EUR'));


if($response){

	$response2 = $paypal -> request('GestTransactionDetails',array(
		'TRANSACTIONID'=>$response['PAYEMENTREQUEST_0_TRANSACTIONID']));

	$products = '';

	for ($i=0; $i < count($_SESSION['panier']['lib_prod']); $i++) {
		//va rajouter à l'array product l'id du produit
		$products.=$_SESSION['panier']['id_prod'][$i];
		if(count($_SESSION['panier']['lib_prod'])>1){
			$products.=', ';
		}
	}
	//a la sortie du for, la variables products va contenir tous les produits
	var_dump($products);
	var_dump($response2);
}
else{
	var_dump($paypal->errors);
}
*/
?>
