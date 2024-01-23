<?php 
session_start();
include("conf/config.php");
$show = "";
$client_id = $_SESSION['client_id'];

$from_account = $_POST['from_accounts'];
$to_account = $_POST['to_accounts'];
$tr_date = $_POST['tr_date'];
$tr_amount =$_POST['tr_amount'];
$tr_status =$_POST['tr_status'];
$description = $_POST['description'];
$account_number = getAccountNumberFromOption($from_account,$to_account);
$from_tr_code = generatTransactionCode();
$to_tr_code = generatTransactionCode();
$from_account_number = getAccountNumberFromOption($from_account,$client_id,$mysqli);
$to_account_number = getAccountNumberFromOption($to_account, $client_id, $mysqli);
$from_account_id = getAccountIdFromOption($from_account_number, $client_id, $mysqli);
$to_account_id = getAccountIdFromOption($to_account_number, $client_id, $mysqli);
$acc_type = getAccountType($account_number,$client_id,$mysqli);
$from_acc_type =getAccountType($from_account_number,$client_id,$mysqli);
$to_acc_type =getAccountType($to_account_number,$client_id,$mysqli);
$from_acc_name = getAccountName($from_account_number, $client_id, $mysqli);
$to_acc_name = getAccountName($to_account_number, $client_id, $mysqli);
$client_name = getClientName($account_number,$client_id,$mysqli);
$from_balance = getAccountBalance($client_id, $from_account_number, $mysqli);
$to_balance = getAccountBalance($client_id, $to_account_number, $mysqli);
$notification_details = "$client_name Has Transfered GH  $tr_amount To Bank Account $account_number";

 $error=array();
if (!empty($from_account) && !empty($to_account) && !empty($tr_date) && !empty($tr_amount)){

    if ($from_balance >= $tr_amount && $from_account_number != $to_account_number) {
    
    // Deducting from the account to be transferred from
    $query = "INSERT INTO iB_Transactions (tr_code,account_id,client_id,client_name,tr_type, acc_name, account_number, acc_type,transaction_amt, balance, description, tr_status) VALUES(?,?,?,?,'Transfer',?,?,?,?,?,?, 'Success')";
    $notification = "INSERT INTO  iB_notifications ( client_id,notification_details) VALUES (?,?)";

    $stmt = $mysqli->prepare($query); 
    $notification_stmt = $mysqli->prepare($notification);
    $rc = $notification_stmt->bind_param('ss',$client_id, $notification_details);           
    $stmt->bind_param('issssssdss', $from_tr_code, $from_account_id, $client_id, $client_name,$from_acc_name, $from_account_number, $from_acc_type, $tr_amount,$from_balance, $description);
    $stmt->execute();
    $notification_stmt->execute();
    $stmt->close();



     // Adding the amount to the "To" account
     $query = "INSERT INTO iB_Transactions (tr_code,account_id,client_id,client_name,tr_type, acc_name, account_number, acc_type,transaction_amt,balance,description, tr_status) VALUES(?,?,?,?,'Recieved Transfer',?,?,?,?,?,?,'Success')";
     $stmt = $mysqli->prepare($query);
     $stmt->bind_param('issssssdss', $to_tr_code, $to_account_id, $client_id, $client_name, $to_acc_name, $to_account_number, $to_acc_type, $tr_amount, $to_balance,$description );
     $stmt->execute();
     $stmt->close();
     
     echo "<p style='color:green'>Transfer Successful!</p>";


   }else{
    echo "<p style='color:red'>Insufficient funds in the from account! / you can't transfer between account with the same account number!</p>";

   }

}else{
    if (empty($from_account)) {
        $error['s'] = "<p style='color:red'>Kindly select from Account!</p>";
    }elseif (empty($to_account)) {
        $error['s'] = "<p style='color:red'>Kindly select to Account!</p>";

    }elseif (empty($tr_date)) {
    	$error['s'] = "<p style='color:red'>Kindly set date!</p>";
    }elseif (empty($tr_amount)) {
    	$error['s'] = "<p style='color:red'>Kindly Enter the amount!</p>";

    }

    if (isset($error['s'])) {
    	$er = $error['s'];
    	echo "<p>$er</p>";
    }
}


function getAccountBalance($client_id, $account_number, $mysqli) {

        // Calculate the total deposits for the account
        $query = "SELECT COALESCE(SUM(transaction_amt), 0) AS total_deposits FROM iB_Transactions WHERE client_id = ? AND account_number = ? AND tr_type = 'Deposit'";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ss', $client_id, $account_number);
        $stmt->execute();
        $stmt->bind_result($total_deposits);
        $stmt->fetch();
        $stmt->close();

        // Calculate the total withdrawals for the account
        $query = "SELECT COALESCE(SUM(transaction_amt), 0) AS total_withdrawals FROM iB_Transactions WHERE client_id = ? AND account_number = ? AND tr_type = 'Withdrawal'";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ss', $client_id, $account_number);
        $stmt->execute();
        $stmt->bind_result($total_withdrawals);
        $stmt->fetch();
        $stmt->close();

        // Calculate the total transfers for the account
        $query = "SELECT COALESCE(SUM(transaction_amt), 0) AS total_transfers FROM iB_Transactions WHERE client_id = ? AND account_number = ? AND tr_type = 'Transfer'";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ss', $client_id, $account_number);
        $stmt->execute();
        $stmt->bind_result($total_transfers);
        $stmt->fetch();
        $stmt->close();

        //calculate the total wire tranfer
        $query = "SELECT COALESCE(SUM(transfer_amount), 0) AS total_wire_transfers FROM ib_wire_transfer WHERE account_id = ? AND transfer_status = 'Success'";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('s', $row['account_id']);
        $stmt->execute(); 
        $stmt->bind_result($total_wire_transfers);
        $stmt->fetch();
        $stmt->close();

        $query = "SELECT COALESCE(SUM(transaction_amt), 0) AS total_transfers FROM iB_Transactions WHERE client_id = ? AND account_number = ? AND tr_type = 'Recieved Transfer'";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ss', $client_id, $account_number);
        $stmt->execute();
        $stmt->bind_result($total_recieved_transfer);
        $stmt->fetch();
        $stmt->close();

        // Calculate the account balance
        $account_balance = ($total_deposits + $total_recieved_transfer) - ($total_withdrawals + $total_transfers + $total_wire_transfers);

        return $account_balance;
}


function generatTransactionCode(){
	$randNumber = sprintf("%09d", rand(0,9999999999999));
	return $randNumber;
}

function getAccountNumberFromOption($Option)
{
    $parts = explode(" ", $Option);
    $account_number = end($parts);
    return $account_number;
}

function getAccountIdFromOption($account_number, $client_id, $mysqli) {
    $query = "SELECT account_id FROM ib_bankaccounts WHERE account_number = ? AND client_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('si', $account_number, $client_id);
    $stmt->execute();
    $stmt->bind_result($account_id);

    // Check if a record is found
    if ($stmt->fetch()) {
        $stmt->close();
        return $account_id;
    } else {
        $stmt->close();
        return false; 
    }
}
function getAccountType($account_number,$client_id,$mysqli){
	$query = "SELECT acc_type FROM ib_bankaccounts WHERE account_number = ? AND client_id = ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param('ss', $account_number, $client_id);
	$stmt->execute();
	$stmt->bind_result($acc_type);

	if($stmt->fetch()){
		$stmt->close();
		return $acc_type;
	}else{
		$stmt->close();
		return false;
	}
}
function getAccountName($account_number,$client_id,$mysqli){
	$query = "SELECT acc_name FROM iB_bankAccounts WHERE account_number = ? AND client_id = ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param('ss',$account_number,$client_id);
	$stmt->execute();
	$stmt->bind_result($acc_name);
	if($stmt->fetch()){
		$stmt->close();
		return $acc_name;
	}else{
		$stmt->close();
		return false;
	}
}
function getClientName($account_number,$client_id,$mysqli){
	$query = "SELECT client_name FROM iB_bankAccounts WHERE account_number = ? AND client_id = ?";
	$stmt = $mysqli->prepare($query);
	$stmt->bind_param('ss',$account_number,$client_id);
	$stmt->execute();
	$stmt->bind_result($client_name);

	if ($stmt->fetch()) {
		$stmt->close();
		return $client_name;
	}else{
		$stmt->close();
		return false;
	}

}

 ?>