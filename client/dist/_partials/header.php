<?php 
session_start(); 
include("conf/config.php");

if(!isset($_SESSION['client_id'])){
	echo "<script>window.location.href='pages_client_index.php'</script>";
}


?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link rel="stylesheet" type="text/css" href="dist/bootstrap-5.2.3-dist/css/bootstrap.min.css">
	

	<script src="dist/bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script> -->

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

   <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.1/css/fontawesome.min.css"></script>

   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Nosifer&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

   <link rel="stylesheet" type="text/css" href="dist/stylesheet/style.css">


</head>
<body> 




<?php 
          $output="";
          
         if (isset($_SESSION['client_id'])) {
           $loginTime = date('M d, Y');
           $client_id= $_SESSION['client_id'];
           $query = "SELECT name,client_number FROM iB_clients WHERE client_id = ?";
           $stmt = $mysqli->prepare($query);
           $stmt->bind_param('s',$client_id);
           $stmt->execute();
           $result = $stmt->get_result();
           $stmt->close();	
           $row = $result->fetch_assoc();
           $client_number = $row['client_number'];


           $queryAlert = "SELECT * FROM iB_notifications WHERE client_id = ? ";
           $stmt =$mysqli->prepare($queryAlert);
           $stmt->bind_param('s',$client_id);
           $stmt->execute();
           $res=$stmt->get_result();
           $num = mysqli_num_rows($res);
           $output .="
                   <span style='color:whitesmoke'>($num)</span>
           ";


    
?>
    <div class="container-fluid">
		<div class="col-md-12">
			<div class=" row d-flex justify-content-between navbar text-white">
				<div class="col-md-2"></div>
				<div class="col-md-4 " id="navbar_child">
				
				  <h5>Welcome to Business Advantage, Last login : <?php echo $loginTime; ?> <br><span id="navbar_child-1"> <?php echo strtoupper($row['name']);?> </span></h5>
				  

				</div>
				<div class="col-md-1"></div>
				<div class="col-md-5 d-flex" id="nav-item" >
					
					<div id="help">
				  	  <a href="pages_enquiries.php"><i class="fa-solid fa-question"></i><p>HELP</p></a>
				  </div>
				  	<div id="alert">
				  	  <a href="pages_alerts.php"><i class="fa-solid fa-bell" >  <?php echo $output; ?></i><p>ALERT</p></a>
				  </div>
				  	<div id="mail">
				  	  <a href="pages_mail.php"><i class="fa-regular fa-envelope"></i><p>MAIL</p></a>
				  </div>
                  <div id="profile">
                      <a href="pages_profile.php?client_number=<?php echo $client_number; ?>"><i class="fa-solid fa-user"></i><p>PROFILE</p></a>
                  </div>

				  <div id="logout">
				  	<a href="pages_logout.php"><i class="fa-solid fa-right-from-bracket"></i><p>LOGOUT</p></a>
				  </div>
				 

				</div>

				
			</div>
		</div>
		
	</div>
        <?php } ?>


	<div class="container-fluid">
		<div class="col-md-12">
			<div class="row d-flex justify-content-between text-white" id="bg-white">
				<div class="col-md-4" id="img">
					<img src="dist/img/fide.jpg">
					
				</div>
				<div class="col-md-1"></div>
				
				<div class="col-md-7 d-flex" id="nav">
					<div id="home">
						<a href="pages_client_home.php"><p>Home</p></a>
					</div>
					<div id="account">
						<a href="pages_account.php"><p>Accounts</p></a>
					</div>
					<div id="payment">
						<a href="pages_payment_transfers.php"><p>Payments & Transfers </p></a>
					</div>
					<div id="cash">
						<a href="pages_checkaccount_selector.php"><p>Cash Management</p></a>
					</div>
					<div id="admin">
						<a href="pages_enquiries.php"><p>Enquiries</p></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

 <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<script type="text/javascript">
	$(document).ready(function(){
            
            $(".open_nav").click(function(){
                 $(".nav_content").fadeIn();
            });

              $(".close_nav").click(function(){
                 $(".nav_content").fadeOut();
            });
	})
</script>
</body>
</html>