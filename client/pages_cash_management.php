<?php 
include("dist/_partials/header.php");
include("conf/config.php");
$client_id = isset($_SESSION['client_id']) ? $_SESSION['client_id'] : '';
$account_id = isset($_GET['account_id']) ? $_GET['account_id'] : '';
$clientid = isset($_GET['client_id']) ? $_GET['client_id'] : '';;
$query = "SELECT * FROM iB_bankAccounts WHERE client_id =? AND account_id =?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('ss',$client_id,$account_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$output="";
$output2="";

if (isset($_POST['deposit'])) {

$tr_code = $_POST['tr_code'];
$account_id = $_POST['account_id'];
$acc_name = $_POST['acc_name'];
$acc_type = $_POST['acc_type'];
$account_number = $_POST['account_number'];
$tr_type  = $_POST['tr_type'];
$tr_status = $_POST['tr_status'];
$client_id  = $_POST['client_id'];
$client_name  = $_POST['client_name'];
$client_national_id  = $_POST['national_id'];
$transaction_amt = $_POST['check_amt'];
$client_phone = $_POST['phone'];
$description = $_POST['description'];
$depo_type = $_POST['depo_type'];
$check_number = $_POST['check_number'];

// Check if 'front_check' and 'back_check' keys exist in the $_FILES array
if (isset($_FILES['front_check']) && isset($_FILES['back_check'])) {
    $back_img_check = $_FILES['back_check']['name'];
    $front_img_check = $_FILES['front_check']['name'];

    // Check if there were no file upload errors
    if ($_FILES['front_check']['error'] == 0 && $_FILES['back_check']['error'] == 0) {
        // Move uploaded files if they were successfully uploaded
        move_uploaded_file($_FILES['back_check']['tmp_name'], "dist/img/" . $back_img_check);
        move_uploaded_file($_FILES['front_check']['tmp_name'], "dist/img/" . $front_img_check);
        
        
        }
        if (isset($_POST['check_number'])) {
        	$query ="SELECT * FROM iB_Transactions WHERE check_number =?";
        	$stmt = $mysqli->prepare($query);
        	$stmt->bind_param('s',$check_number);
        	$stmt->execute();
        	$res =$stmt->get_result();
        	if (mysqli_num_rows($res)) {
        		$output2 .="Check number already exist";
        	}else{
        		$query = "INSERT INTO iB_Transactions (tr_code, account_id, acc_name, account_number, acc_type, tr_type, depo_type, description, frontcheck_image, back_checkimage, check_number, tr_status, client_id, client_name, client_national_id, transaction_amt, client_phone) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

                 $stmt = $mysqli->prepare($query);
                 $stmt->bind_param('sssssssssssssssss', $tr_code, $account_id, $acc_name, $account_number, $acc_type, $tr_type, $depo_type, $description, $front_img_check, $back_img_check, $check_number, $tr_status, $client_id, $client_name, $client_national_id, $transaction_amt, $client_phone);

                 $stmt->execute();
                 $stmt->close();
        

                 if ($stmt) {
                     $output .="inserted successfully";
            
                 } else {
                     echo "failed to execute database query";
                 }
        	         }
                 }
     
    } else {
        // Handle file upload errors
        echo "Error: Front check or back check file upload failed. File upload error code: " . $_FILES['front_check']['error'] . " " . $_FILES['back_check']['error'];
    }
} 

 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cash</title>
</head>
<body style="height:850px">
	<div class="container-fluid"style="margin-top: 20px;">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-1"></div>
				<div class="col-md-10">
					<table class=" table table-responsive table-borderless ">
			    		<thead>
			    			<tr>
			    				<th style="color:#007b85; font-size:20px">Cash Management</th>
			    			</tr>
			    			<tr>
			    				<th >
			    					<a href="pages_checkaccount_selector.php"><button style="background-color:#007b85; border: none; color:white;">Deposit Check</button></a>
			    					
			    					<a href="pages_pay_bills.php?client_id=<?php echo isset($_SESSION['client_id']) ? $_SESSION['client_id'] : ''; ?>">
                                       <button style="background-color:navy; border: none; color:white; width:90px">Bill pay</button>
                                    </a>
			    				</th>
			    			</tr>
			    		</thead>
			    	</table>
				</div>
				<div class="col-md-1"></div>
				
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="col-md-12">
			<div class="row">
				 
				<form method="post" enctype="multipart/form-data" id="formdata">
					<div id="result"></div>
					<div class="row">
					   <div class="col-md-4"></div>
					   <div class="col-md-2">
					   <div>
					   	<p style="color:green;"><?php echo $output; ?></p>
					   
					   </div>
						<div>
							<label style="font-weight: bold;">Client Name: </label>
							<input type="text" name="client_name" readonly value="<?php echo $row['client_name'];?>">
						</div>
						<div>
							<label  style="font-weight: bold;">Account Name: </label>
							<input type="text" name="acc_name" readonly value="<?php echo $row['acc_name'];?>">
						</div>
						<div>
							<?php
                                //PHP function to generate random account number
                                $length = 15;
                                $_transcode =  substr(str_shuffle('0123456789QWERgfdsazxcvbnTYUIOqwertyuioplkjhmPASDFGHJKLMNBVCXZ'), 1, $length);
                             ?>
							<label  style="font-weight: bold;">Transaction Code: </label>
							<input type="text" name="tr_code" readonly value="<?php echo $_transcode; ?>">
						</div>
						<div>
						
                         <label style="font-weight: bold;">Account Type: </label>
                          <input type="text" name="acc_type" readonly value="<?php echo $row['acc_type']; ?>">
                        

						</div>
						<div>
							<label  style="font-weight: bold;">Upload Front of Check: </label>
							<input type="file" name="front_check" required id="Front_Check" class="form-control">
						</div>
						<div>
							<label  style="font-weight: bold;">Upload Back of Check: </label>
							<input type="file" name="back_check" required id="Back_Check" class="form-control">
						</div>
						<div>
							<label  style="font-weight: bold;">Description: </label>
							<input type="text" name="description" required>
						</div>
						<div style="display:none;">
							<label  style="font-weight: bold;">Tr_Status: </label>
							<input type="text" name="tr_status" value="Pending">
						</div>
						<div style="display:none;">
							<label style="font-weight: bold;">Client ID</label>
							<input type="text" name="client_id" value="<?php echo $clientid;?>">

						</div>
						<div style="display:none;">
							<label style="font-weight: bold;">Account ID</label>
							<input type="text" name="account_id" value="<?php echo $account_id;?>">

						</div>


					
				    </div>
				    <div class="col-md-2">
				    	<div> <p style="color:red"><?php echo $output2; ?></p></div>
						<div>
							<label style="font-weight: bold;">National ID: </label>
							<input type="text" name="national_id" readonly value="<?php echo $row['client_national_id'];?>">
						</div>
						<div>
							<label style="font-weight: bold;">Phone Number: </label>
							<input type="text" name="phone" readonly value="<?php echo $row['client_phone'];?>">
						</div>
						<div>
							<label style="font-weight: bold;">Account Number: </label>
							<input type="text" name="account_number" readonly value="<?php echo $row['account_number'];?>">
						</div>
						<div>
							<label  style="font-weight: bold;">Check Amount: </label>
							<input type="text" name="check_amt" required >
						</div>
						
						<div style="display:none">
                            <label>Transaction Type</label>
                            <input type="text" name="tr_type" value="CheckDeposit" required class="form-control">
                        </div>
                        <div style="display:none">
                            <label>Deposit Type</label>
                            <input type="text" name="depo_type" value="Check" required class="form-control">
                        </div>
                        <div >
                            <label>Check Number</label>
                            <input type="text" name="check_number"  required>
                        </div>
						<div style="margin-top:20px; width:150px;">
							<input type="submit" name="deposit" value="Deposit" id="deposit_check" class="form-control" style="background-color:#007b85;color: white;">
						</div>
				    </div>
				</div>
				<div class="col-md-4"></div>
			   </form>
				
			</div>
		</div>
	</div>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script type="text/javascript">
	// $(document).ready(function(){
	// 	$("#deposit_check").click(function(e){
	// 		e.preventDefault();
	// 	var formData =$("#formdata").serialize();
	// 	console.log(formData);
	// 	$.ajax({
	// 		method:"POST",
	// 		url:"ajax_check_deposit.php",
	// 		data:formData,
	// 		success:function(response){
    //          $("#result").html(response);
	// 		}
	// 	})
	//   });
	// });

</script>

</body>
</html>