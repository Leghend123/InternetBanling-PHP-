<?php 
include("dist/_partials/header.php");
include("conf/config.php");
$client_id = $_SESSION['client_id'];
$query = "SELECT * FROM iB_notifications WHERE client_id=?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s',$client_id);
$stmt->execute();
$result=$stmt->get_result();
$stmt->close();

if(isset($_GET['delete'])){
	$notification_id= intval($_GET['delete']);
	$delete = "DELETE FROM ib_notifications WHERE notification_id =?";
	$stmt = $mysqli->prepare($delete);
	$stmt->bind_param('s',$notification_id);
	$stmt->execute();
	$stmt->close();
}
 ?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<div class="container" style="margin-top:20px">
		<div class="col-md-12">
			<div class="row">
				<h4 style="color:#007b58">Alerts</h4>
				<div class="col-md-3"></div>
				<div class="col-md-6">
				<?php while ($row=$result->fetch_assoc()) { ?>
				<div class="alert alert-primary alert-dismissible fade show" role="alert">
                   <?php echo $row['notification_details'] ;?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                   <a class="btn btn-white btn-sm" href="pages_alerts.php?delete=<?php echo $row['notification_id']; ?> ">Clear</a>

                </div>
            <?php } ?>
            </div>
            <div class="col-md-3"></div>
			</div>
		</div>
	</div>

</body>
</html>