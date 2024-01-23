<?php
include('conf/config.php');
$wire_id = $_POST['id'];

$query = "UPDATE ib_wire_transfer SET transfer_status='Success' WHERE wire_id =?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i',$wire_id);
$stmt->execute();
$stmt->close();
if ($stmt) {
	echo "success";
}else{
	echo " failed";
}
?>
