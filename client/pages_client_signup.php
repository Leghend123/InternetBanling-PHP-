<?php 
include("conf/config.php");

if(isset($_POST['create_account'])){
	$name = $_POST['name'];
	$client_number = $_POST['client_number'];
	$national_id = $_POST['national_id'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$password =  sha1(md5($_POST['password']));
	$addres = $_POST['address'];


	$query = "SELECT * FROM iB_clients WHERE client_number=? ";
   $stmt = $mysqli->prepare($query);
   $rc= $stmt->bind_param('s',$client_number);
   $stmt->execute();
   $result = $stmt->get_result();
   $num_rows = $result->num_rows;

   if ($num_rows > 0) {
   	echo"<h5 class=' text-center alert alert-danger'> already exist</h5>";
   }else{
   $query = "INSERT INTO iB_clients (name,national_id, client_number, phone, email, password, address) VALUES(?,?,?,?,?,?,?)";
	$stmt = $mysqli->prepare($query);
	$rc = $stmt->bind_param('sssssss', $name, $national_id, $client_number, $phone, $email, $password,$addres);
	$stmt->execute();

	if($stmt){
	  echo"<h5 class='text-center alert alert-success'>'successfully login'</h5>";
	  header("Location: pages_client_index.php");

	}else{
		$err = "try again or later ";
	}

   }

	
}
 ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Login</title>
	<script src="dist/bootstrap-5.2.3-dist/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="dist/bootstrap-5.2.3-dist/css/bootstrap.min.css">

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
  	margin-left:80px;
  }
  .parent{
    background-color:#edf3f8; 
    margin-top: 150px;
    margin-left: 40px;
    justify-conten: center;
    height: 400px;
    box-shadow:2px 2px 3px rgba(0, 0, 0, 0.3),
                  0 -1px 3px rgba(0, 0, 0, 0.1),
                  0 1px 3px rgba(0, 0, 0, 0.1),
                 0 -1px 3px rgba(0, 0, 0, 0.1);
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
<body>
	<div class="container-fluid">
		<div class="col-md-12" >
			<div class="row" >
				<div class="col-md-4"></div>
				<div class="col-md-4 parent">
					<form method="post">
						<img src="dist/img/fide-removebg-preview.png">
					
						<div class="col-md-12">	
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<input type="text" name="name" required class="form-control" placeholder="Client Full Name">
									</div>
									<div class="form-group" style="display:none">
								   <?php
                                     //PHP function to generate random
                                        $length = 4;
                                          $_Number =  substr(str_shuffle('0123456789'), 1, $length);

                                         ?>
                              <input type="text" name="client_number" value="iBank-CLIENT-<?php echo $_Number; ?>" class="form-control" placeholder="Client Number">
									</div>

									<div class="form-group">
										<label>Phone </label>
										 <input type="text" name="phone" required class="form-control" placeholder="Client Phone Number">
									</div>
									<div class="form-group">
										<label>Email</label>
										 <input type="email" name="email" required class="form-control" placeholder="Email Address">

									</div>
							   
						        </div>
								<div class="col-md-6">
									<div class="form-group">
										<input type="text" required name="national_id" class="form-control" placeholder="National ID Number">
									</div>
									<div class="form-group">
										<label>Address</label>
										 <input type="text" name="address" required class="form-control" placeholder="Client Address">
									</div>

									<div class="form-group">
							        <label>Password:</label>
                              <input type="password" id="password" name="password" required class="form-control" placeholder="Password">
						        </div>
								</div>


							</div>

						</div>
						
						
						
                     <div >
						 <input type="checkbox" id="showPassword" > Show Password
						
						</div>
						 

						<div style="padding:20px 20px;">
							<input type="submit" name="create_account" class="col-md-6 btn btn-primary" value="Sign Up">

						</div>

						 <a href="pages_client_index.php" class="text-center">click here to Login</a>
						</form>
				</div>
				<div class="col-md-4"></div>


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