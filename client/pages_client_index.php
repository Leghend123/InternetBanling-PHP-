<?php 
session_start();
include('conf/config.php'); 
$err="";
if(isset($_POST['login'])){
	$client_number= $_POST['client_number'];
	$password = sha1(md5($_POST['password']));

	$query = " SELECT client_number , password,client_id FROM iB_clients WHERE client_number=? AND password=?";
	$stmt = $mysqli->prepare($query);
	$ret= $stmt->bind_param('ss',$client_number, $password);
	$stmt->execute();
	$stmt->bind_result($client_number,$password,$client_id);
	$res=$stmt->fetch();
	$_SESSION['client_id'] = $client_id;
	if($res){
		header("Location: pages_client_home.php");
	}else{
		$err="access denied";
	}
}

 ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="dist/bootstrap-5.2.3-dist/css/bootstrap.min.css">
	<script src="dist/bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>


   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

   <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.1/css/fontawesome.min.css"></script>

   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Nosifer&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

   <link rel="stylesheet" type="text/css" href="stylesheet/style.css">

 <style type="text/css">
  img{
  	width: 200px;
  	height: 80px;
  	margin-left:50px;
  }
  .parent{
    background-color:#edf3f8; 
    margin-top: 150px;
    margin-left: 60px;
    justify-conten: center;
    height: 350px;
    box-shadow:2px 2px 3px rgba(0, 0, 0, 0.3),
                  -1px -1px 3px rgba(0, 0, 0, 0.1),
                  -1px 1px 3px rgba(0, 0, 0, 0.1),
                  1px -1px 3px rgba(0, 0, 0, 0.1);
  }
  .showPassword{
  	display: flex;
  	justify-content: space-between;
}
  .show{
   margin-right: 60px;
  }
 
@media screen and (max-width: 1200px){
	img{
  	width: 150px;
  	height: 60px;
  	margin-left:50px;

  }
  .parent{
    background-color:#edf3f8; 
    margin-top: 100px;
    justify-conten: center;
    box-shadow:2px 2px 3px rgba(0, 0, 0, 0.3),
                  -2px -1px 3px rgba(0, 0, 0, 0.1),
                  -1px 1px 3px rgba(0, 0, 0, 0.1),
                  1px -1px 3px rgba(0, 0, 0, 0.1);

  }
  .showPassword{
  	display: flex;
  	justify-content: space-between;
  	font-size: 14px;

  }
   .show{
   margin-right:35px;
  }
}
   	
 </style>

</head>
<body style="background-color:">
	<div class="container-fluid">
		<div class="col-md-12" >
			<div class="row" >
				<div class="col-md-4"></div>
				<div class="col-md-3 parent" >
					<form method="post">
						<div><?php echo $err; ?></div>
						<img src="dist/img/fide-removebg-preview.png">
						<!-- <h5 class="text-center p-4">Customer Login</h5> -->
					<!-- 	<div class="form-group">
							<label >Account Type:</label>
							<select name="AccountType" class="form-control">
								<option value="">Select account type</option>
								<option value="Personal">Personal</option>
								<option value="Small Business">Small Business</option>
								<option value="Savings">Savings</option>
							</select>
				
						</div> -->
						<div class="form-group">
							<label>Client Number:</label>
							<input type="text" name="client_number" class="form-control" autocomplete="off">

						</div>
						<div class="form-group">
							<label>Password:</label>
							<input type="password" id="password" name="password" class="form-control" autocomplete="off">
						</div>
                         <div class="showPassword">
						 <input type="checkbox" id="showPassword" > <span class="show">Show Password</span>
						 <a href="#"><span>forgot password?</span></a>
						</div>
						 

						<div style="padding:20px 20px;">
							<input type="submit" name="login" class="col-md-12 btn btn-primary" value="Log In">

						</div>
						<a href="pages_client_signup.php" class="text-center">click here to Sign Up</a>

						</form>
				</div>
				<div class="col-md-5"></div>


			</div>
		</div>
	</div>

	<script type="text/javascript">
		const passwordInput = document.getElementById('password');
		const showPasswordCheckbox = document.getElementById('showPassword');

		showPasswordCheckbox.addEventListener('change', function(){
			if(showPasswordCheckbox.checked){
				passwordInput.type='text';
			} else{
				passwordInput.type='password';
			}
		});
	</script>

</body>
</html>