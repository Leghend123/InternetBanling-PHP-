<?php
include("dist/_partials/header.php");
include("conf/config.php");

$account_id = $_GET['id'];
$output="";
$output2="";
$output3="";
$icon ="";

$query = "SELECT client_id, acc_type, account_number,account_id,currency_type FROM iB_bankAccounts WHERE account_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $account_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();


$output .='
             <tr >

  					<th  style="color:#007b85; font-size:20px">'.$row['acc_type'].' ' . $row['account_number'] . ' <a href="javascript:void(0)" style="color:#007b85; "><i class="fa-solid fa-angle-up fa-rotate-90"></i></a></th>

  					</tr>

  					<tr>
  						<th> 
                        <a ><button style="background-color:#007b85; border: none; color:white;">Details</button></a>
                        <a href="pages_statement.php?id='.$row['account_id'].'" target="_blank"><button style="background-color:navy; border: none; color:white;">Electronic Statement</button></a>
                         <a href="pages_statement.php?id='.$row['account_id'].'" target="_blank"><button style="background-color:navy; border: none; color:white;">Download</button></th>
  					</tr>
  					<tr>
  						<th style="color:#007b85; font-size:20px;">Account Infomation</th>
  					</tr>
  				
  				
  					<tr>
  					<th>Balance</th> 
  					<th></th>
  					<th>Activity</td>
  					<th></th>
';




	$client_id = $row['client_id'];
    $account_number = $row['account_number'];

    // Calculate the previous day transactions for the account
    $previous_day = date('Y-m-d', strtotime('-1 day'));
    $query_previous_day = "SELECT COALESCE(SUM(transaction_amt), 0) AS total_transactions FROM iB_Transactions WHERE client_id = ? AND account_number = ? AND created_at = ?";
    $stmt_previous_day = $mysqli->prepare($query_previous_day);
    $stmt_previous_day->bind_param('sss', $client_id, $account_number, $previous_day);
    $stmt_previous_day->execute();
    $stmt_previous_day->bind_result($total_transactions_previous_day);
    $stmt_previous_day->fetch();
    $stmt_previous_day->close();

    // Retrieve details of the last deposit
    $query_last_deposit = "SELECT created_at, transaction_amt FROM iB_Transactions WHERE client_id = ? AND account_number = ? AND tr_type = 'Deposit' ORDER BY created_at DESC LIMIT 1";
    $stmt_last_deposit = $mysqli->prepare($query_last_deposit);
    $stmt_last_deposit->bind_param('ss', $client_id, $account_number);
    $stmt_last_deposit->execute();
    $stmt_last_deposit->bind_result($last_deposit_date, $last_deposit_amount);
    $stmt_last_deposit->fetch();
    $stmt_last_deposit->close();

    // Fetch the last check details
    $query_last_check = "SELECT transaction_amt, created_at FROM iB_Transactions WHERE client_id = ? AND account_number = ? AND depo_type = 'Check' ORDER BY created_at DESC LIMIT 1";
    $stmt_last_check = $mysqli->prepare($query_last_check);
    $stmt_last_check->bind_param('ss', $client_id, $account_number);
    $stmt_last_check->execute();
    $stmt_last_check_result = $stmt_last_check->get_result();

    if ($last_check = $stmt_last_check_result->fetch_assoc()) {
        $last_check_amount = $last_check['transaction_amt'];
        $last_check_date = date('F d, Y', strtotime($last_check['created_at']));
    } else {
        $last_check_amount = 0.00;
        $last_check_date = 'N/A';
    }

    // Calculate the total pending transactions for the account
    $query_total_pending = "SELECT COALESCE(SUM(transfer_amount), 0) AS total_pending_amount FROM ib_wire_transfer WHERE account_id = ? AND transfer_status = 'Pending'";
    $stmt_total_pending = $mysqli->prepare($query_total_pending);
    $stmt_total_pending->bind_param('s', $account_id);
    $stmt_total_pending->execute();
    $stmt_total_pending->bind_result($total_pending_amount);
    $stmt_total_pending->fetch();
    $stmt_total_pending->close();
	// Calculate the total deposits for the account
    $query = "SELECT COALESCE(SUM(transaction_amt), 0) AS total_deposits FROM iB_Transactions WHERE client_id = ? AND account_number = ? AND tr_type = 'Deposit'";
	$stmt= $mysqli->prepare($query);
	$stmt->bind_param('ss', $client_id,$account_number);
	$stmt->execute();
	$stmt->bind_result($total_deposits);
	$stmt->fetch();
	$stmt->close();

	// Calculate the total withdrawal for the account
    $query = "SELECT COALESCE(SUM(transaction_amt), 0) AS total_withdrawals FROM iB_Transactions WHERE client_id = ? AND account_number = ? AND tr_type = 'Withdrawal'";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param('ss', $client_id,$account_number);
	$stmt->execute();
	$stmt->bind_result($total_withdrawals);
	$stmt->fetch();
	$stmt->close();

	// Calculate the total transfer for the account
    $query = "SELECT COALESCE(SUM(transaction_amt), 0) AS total_transfers FROM iB_Transactions WHERE client_id = ? AND account_number = ? AND tr_type = 'Transfer'";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param('ss',$client_id, $account_number);
	$stmt->execute();
	$stmt->bind_result($total_transfers);
	$stmt->fetch();
	$stmt->close();

        // Calculate the total wire transfer for the account
    $query = "SELECT COALESCE(SUM(transfer_amount), 0) AS total_wire_transfers FROM ib_wire_transfer WHERE account_id = ? AND transfer_status = 'Success'";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $row['account_id']);
    $stmt->execute();
    $stmt->bind_result($total_wire_transfers);
    $stmt->fetch();
    $stmt->close();

    // $query = "SELECT balance FROM iB_Transactions WHERE client_id = ? AND account_number =?";
    // $stmt = $mysqli->prepare($query);
    // $stmt->bind_param('ss', $client_id,$account_number);
    // $stmt->execute()

	$total_balance = $total_deposits - ($total_transfers + $total_withdrawals + $total_wire_transfers);
	$available_balance = $total_balance - $total_pending_amount ;
	$formatted_balance = $row['currency_type']  . number_format($total_balance, 2, '.', ',');
	$formatted_transactions = $row['currency_type']  . number_format($total_transactions_previous_day, 2, '.', ',');
    $formatted_last_deposit = $row['currency_type']  . (isset($last_deposit_amount) && is_numeric($last_deposit_amount) ? number_format($last_deposit_amount, 2, '.', ',') : '0.00');
	$formatted_last_check = $row['currency_type']  . number_format($last_check_amount, 2, '.', ',');
	$formatted_available_balance = $row['currency_type']  . number_format($available_balance, 2, '.', ',');

	$formatted_total_pending = $row['currency_type']  . number_format($total_pending_amount, 2, '.', ',');


	$formatted_last_deposit_date = date('F d, Y', strtotime($last_deposit_date));



// Display the balance
$output2 .='
<tr>
                   	<td>Previous day transactions</td>
                   	<td>'.$formatted_transactions.'</td>
                   	<td>Last deposit('.$formatted_last_deposit_date.')</td>
                   	<td></td>
                   	<td>'.$formatted_last_deposit.'</td>
                   </tr>
                     <tr>
                   	<td>Current balance</td>
                   	<td>' . $formatted_balance . '</td>
                   	<td>Last check('.$last_check_date.')</td>
                   	<td></td>
                   	<td>'.$formatted_last_check.'</td>
                   </tr>
                   <tr>
                    <td>Pending transactions</td>
                   	<td>'.$formatted_total_pending.'</td>
                   </tr>
                   <tr>
                    <td>Available balance</td>
                   	<td>'.$formatted_available_balance.'</td>
                   </tr>
';

// Fetch and display recent transactions
$query = "SELECT * FROM iB_Transactions WHERE client_id = ? AND account_number =? ORDER BY created_at DESC LIMIT 6";
$stmt= $mysqli->prepare($query);
$stmt->bind_param('ss' , $client_id , $account_number);
$stmt->execute();
$result = $stmt->get_result();

$query = "SELECT currency_type FROM iB_bankAccounts WHERE account_id= ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s',$account_id);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();
$row_cur = $res->fetch_assoc();

//looping through all the recent trecent transactions
while($row=$result->fetch_assoc()){

    if ($row['tr_status'] == 'Success') {
        $icon = "<i class='fa-regular fa-face-smile' style='color: #00e66f;'></i>";
    } elseif ($row['tr_status'] == 'Pending') {
        $icon = "<i class='fa-regular fa-clock' style='color: #eda507;'></i>";
    }


	$output3.='
	  					<tr>
  						<td> ' .$icon. ' '.$row['created_at'].'</td>
  						<td>'.$row['description'].'</td>
  						
  						<td>' . (($row["tr_type"] == "Withdrawal" || $row["tr_type"] == "Transfer") ? $row_cur['currency_type']  . $row["transaction_amt"] : '') . '</td>
                        <td>' . ($row["tr_type"] == "Deposit" ? $row_cur['currency_type']  . $row["transaction_amt"] : '') . '</td>


  						<td>'.$row_cur['currency_type'].' '.$row['balance'].'</td>
  					</tr>
	';
}


?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Account details </title>
</head>
<body>
  <div class="container-fluid" style="margin-top:30px">
  	<div class="col-md-12">
  		<div class="row">
            <div class="col-md-1"></div>
  			<div class="col-md-10">
  			<table class=" table table-borderless table-responsive">
  				<thead>
  					<?php echo $output; ?>
  					
  					</thead>
  					

                   </tr>
                   <tbody>
                   <?php echo $output2; ?>
  				</tbody>
  

  			</table>
  	
  			</div>
            <div class="col-md-1"></div>

  		</div>
  	</div>
  </div>
  <div class="container-fluid">
  	<div class="col-md-12">
  		<div class="row">
            <div class="col-md-1"></div>

  			<div class="col-md-8"  style="height:400px">
  						<table class="table table-responsive table-borderless">
  				<thead>
  					<tr>
  						<th style="color:#007b85; font-size:20px">Transactions</th>

  					</tr>
  					<tr>
  						<th> <i class="fa-regular fa-clock" style="color: #eda507;"></i> pending  <span> <i class="fa-regular fa-face-smile" style="color: #00e66f;"></i> posted </span> </th>

  					</tr>
  					<tr style="background-color:#003b5c; color: white;">

  						<th>Date</th>
  						<th>Description</th>

  						<th>Debit</th>
  						<th>Credit</th>
  						<th>Balance</th>
  					</tr>
  				</thead>
  				<tbody>
  					<?php echo $output3; ?>


  					
  				</tbody>
  				
  			</table>
  			</div>
  			<div class="col-md-3"></div>
  		</div>
  	</div>
  </div>

</body>
</html>