<?php 
include("conf/config.php");
// $client_id = $_SESSION['client_id'];
$query =" SELECT currency_type FROM ib_bankaccounts WHERE account_id =?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s',$account_id);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();
$row = $res->fetch_assoc();
$currency_type = $row['currency_type'];

$account_name = $_POST['account_name'];
$account_number = $_POST['account_number'];
$bank_address = $_POST['bank_address'];
$state = $_POST['state'];
$zip_code = $_POST['zip_code'];
$bank_name = $_POST['bank_name'];
$routing_number = $_POST['routing_number'];
$receiving_country = $_POST['receiving_country'];
$city = $_POST['city'];
$tr_amount = $_POST['tr_amount'];
$tr_code = generatTransactionCode();
$account_id = $_POST['account_id'];
$notification_details = "You Did WIRE Transfer Of $currency_type  $tr_amount To Bank Account $account_number";

if (!empty($account_name ) && !empty($account_number) && !empty($bank_name) && !empty($bank_address ) && !empty($zip_code ) && !empty($tr_amount )  && !empty($zip_code ) && !empty($state ) && !empty($routing_number ) && !empty($receiving_country ) && !empty($city )) {

//send the details to that ib-wire-transfer db
   $query ="INSERT INTO ib_wire_transfer (account_id,tr_code, account_name, account_number, bank_address, state, zip_code, bank_name, routing_number,receiving_country ,city, transfer_amount, transfer_status) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,'Incomplete')";
   $notification = "INSERT INTO  iB_notifications (notification_details) VALUES (?)";

   $stmt = $mysqli->prepare($query);
   $notification_stmt = $mysqli->prepare($notification);

   $notification_stmt->bind_param('s',$notification_details);
   $stmt->bind_param('sissssdssssd',$account_id,$tr_code, $account_name, $account_number, $bank_address, $state, $zip_code, $bank_name, $routing_number, $receiving_country, $city, $tr_amount);
   $stmt->execute();
   $notification_stmt->execute(); 
   $stmt->close();
    echo "Detailsuccess";


}else {
    echo "DetailError: Some fields are empty.";
}


function generatTransactionCode(){
	$randNumber = sprintf("%09d", rand(0,9999999999999));
	return $randNumber;
}
 ?>