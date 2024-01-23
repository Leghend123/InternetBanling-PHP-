<?php
include("dist/_partials/header.php");
include("conf/config.php");

$client_id = $_SESSION['client_id'];
$query = " SELECT acc_type,account_number,account_id,client_id,currency_type FROM iB_bankAccounts WHERE client_id = ? ";
$stmt= $mysqli->prepare($query);
$stmt->bind_param('s', $client_id);
$stmt->execute();
$result = $stmt->get_result();
$output="";

// Loop through each bank account
while($row = $result->fetch_assoc()){
	$account_number = $row['account_number'];
	$account_id = $row['account_id'];

	// Calculate the total deposits for the account
    $query = "SELECT COALESCE(SUM(transaction_amt), 0) AS total_deposits FROM iB_Transactions WHERE client_id = ? AND account_number = ? AND tr_type = 'Deposit' ";
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


	$total_balance = $total_deposits - ($total_transfers + $total_withdrawals + $total_wire_transfers);
	 $formatted_balance = $row['currency_type'] . number_format($total_balance, 2, '.', ',');

   
    $output .='
            
							<tr>
								<td>
								<a href="pages_cash_management.php?account_id='.$row['account_id'].'& client_id='.$row['client_id'].' " style="text-decoration:none; color:black">'.$row["acc_type"].' <br><span>'.$row["account_number"].'</span></a>
								</td>
								<td>
								 <a href="pages_cash_management.php?account_id='.$row['account_id'].'& client_id='.$row['client_id'].' " style="text-decoration:none; color:black">Current balance <br><span style="font-size:15px; color:navy; font-weight:bold">'.$formatted_balance.'</span></a>
								 </td>
								<td>
								<a href="pages_cash_management.php?account_id='.$row['account_id'].'& client_id='.$row['client_id'].' " style="text-decoration:none; color:black">Available balance <br> <span style="font-size:15px;color:navy; font-weight:bold "> '.$formatted_balance.'</</span>
								</a>
								</td>
								<td>
								<a href="pages_cash_management.php?account_id='.$row['account_id'].'& client_id='.$row['client_id'].'  "><button class="btn btn-primary">Click to Make A Deposit</button></a>
								</td>

							</tr>
						

    ';

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
	<div class="container-fluid">
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
			    					
			    					<a href="pages_pay_bills.php"><button style="background-color:navy; border: none; color:white; width:90px">Bill pay</button></a>
			    				</th>
			    			</tr>
			    			<tr>
			    				<td>Select the account for the check deposit</td>
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
				<div class="col-md-1"></div>
				<div class="col-md-10">
					<table class="table table-responsive">
						<tr>
							<th>Accounts</th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
						<tbody><?php echo $output; ?></tbody>

					</table>
				</div>
				<div class="col-md-1"></div>

			</div>
		</div>
	</div>

</body>
</html>