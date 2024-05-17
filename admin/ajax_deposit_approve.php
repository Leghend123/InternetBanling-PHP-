<?php 
include("conf/config.php");
$tr_id = $_POST['id'];
$query = "UPDATE ib_transactions SET tr_status='Success' ,tr_type='Deposit' WHERE tr_id =?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i',$tr_id);
$stmt->execute();
$stmt->close();
if ($stmt) {
	echo "success";
}else{
	echo " failed";
}
 ?>