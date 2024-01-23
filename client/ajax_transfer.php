<?php
session_start();
include("conf/config.php");
$client_id = $_SESSION['client_id'];
$from_account = $_POST['from_account'];
$to_account = $_POST['to_account'];
$tr_status = $_POST['tr_status'];

// Check if both accounts are selected
if (!empty($from_account) && !empty($to_account)) {
    // Check if the "From" account has a sufficient balance
    $from_account_number = getAccountNumberFromOption($from_account); 
    $from_balance = getAccountBalance($client_id, $from_account_number, $mysqli);
    $account_number = getAccountNumberFromOption($from_account,$to_account);

    $tr_code =generatTransactionCode();
    $account_id = getAccountIdFromOption($account_number, $client_id, $mysqli);

    $amount = $_POST['tr_amt'];

    if ($from_balance >= $amount) {
        // Deduct the amount from the "From" account
        $query = "INSERT INTO iB_Transactions (tr_code,account_id,client_id, account_number, tr_type, transaction_amt, description,tr_status) VALUES (?, ?, ?,?, 'Transfer', ?, 'Internal Transfer','Success')";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('iissd',$tr_code,$account_id,$client_id,$from_account_number, $amount, );
        $stmt->execute();
        $stmt->close();

        // Add the amount to the "To" account
        $to_account_number = getAccountNumberFromOption($to_account); 
        $query = "INSERT INTO iB_Transactions (tr_code,account_id,client_id, account_number, tr_type, transaction_amt, description,tr_status) VALUES (?,?, ?,?, 'Recieved Transfer', ?, 'Internal Transfer','Success')";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('iissd', $tr_code,$account_id, $client_id,$to_account_number, $amount,);
        $stmt->execute();
        $stmt->close();
        echo "<p style='color:green'> Transfer successful! </p>";
    } else {
        echo "<p style='color:red'>Insufficient funds in the from account!</p>";
    }
}else{
	echo" <p style='color:red'>Kindly select the from and to account! </p>";
}

function getAccountNumberFromOption($Option)
{
    $parts = explode(" ", $Option);
    $account_number = end($parts);
    return $account_number;
}

function getAccountBalance($client_id, $account_number, $mysqli)
{
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

    $query = "SELECT COALESCE(SUM(transaction_amt), 0) AS total_transfers FROM iB_Transactions WHERE client_id = ? AND account_number = ? AND tr_type = 'Recieved Transfer'";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ss', $client_id, $account_number);
        $stmt->execute();
        $stmt->bind_result($total_recieved_transfer);
        $stmt->fetch();
        $stmt->close();

    // Calculate the account balance
    $account_balance = ($total_deposits + $total_recieved_transfer) - ($total_withdrawals + $total_transfers);

    return $account_balance;
}

function generatTransactionCode(){
	$randNumber = sprintf("%09d", rand(0,9999999999999));
	return $randNumber;
}
function getAccountIdFromOption($account_number, $client_id, $mysqli) {

    // Fetch the account_id from ib_bankaccounts based on the account number and client ID
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

?>

