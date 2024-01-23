<?php 
include("conf/config.php");
$tr_id = $_POST['id'];
$query ="UPDATE ib_Transactions SET tr_status='Reject' WHERE tr_id=?";
$stmt =$mysqli->prepare($query);
$stmt->bind_param('s', $tr_id);
$stmt->execute();
$stmt->close();
if ($stmt) {
	echo "Reject";
}else{
	echo "Failed";
}
 ?>
