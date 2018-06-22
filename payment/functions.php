<?php

function check_txnid($tnxid){
	$valid_txnid = true;
	//get result set
	$sql = mysql_query("SELECT * FROM `payments` WHERE txnid = '$tnxid'");
	if ($row = mysql_fetch_array($sql)) {
		$valid_txnid = false;
	}
	return $valid_txnid;
}

function check_price($price, $id){
	$valid_price = false;
	//you could use the below to check whether the correct price has been paid for the product

	/*
	$sql = mysql_query("SELECT amount FROM `products` WHERE id = '$id'");
	if (mysql_num_rows($sql) != 0) {
		while ($row = mysql_fetch_array($sql)) {
			$num = (float)$row['amount'];
			if($num == $price){
				$valid_price = true;
			}
		}
	}
	return $valid_price;
	*/
	return true;
}


function updatePayments($data){
	require	'../config.php';
	require	'../connect.php';

	if (is_array($data)) {
		$sql = mysql_query("INSERT INTO `payments` (txnid, payment_amount, payment_status, itemid, iduser, indirizzo, createdtime) VALUES (
				'".$data['txn_id']."' ,
				'".$data['payment_amount']."' ,
				'".$data['payment_status']."' ,
				'".$data['item_number']."' ,
				'".$data['iduser']."' ,
				'".urlencode($data['indirizzo'])."'	,
				'".date("Y-m-d H:i:s")."'
				)");

		return mysql_insert_id();
	}

}
?>
