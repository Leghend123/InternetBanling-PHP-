<?php
session_start();
include("conf/config.php");
require('./fpdf186/fpdf.php');
$client_id = $_SESSION['client_id'];
$account_id = $_GET['id'];


// fetch all details from the ib-transaction table
$query = "SELECT * FROM ib_Transactions WHERE account_id=?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $account_id);
$stmt->execute();
$result = $stmt->get_result();

// fetch all details from ib-client table
$query ="SELECT * FROM ib_clients WHERE client_id=?";
$stmt=$mysqli->prepare($query);
$stmt->bind_param('s',$client_id);
$stmt->execute();
$result_client=$stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $pdf = new FPDF('P', 'mm', "A4");

    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 11);

    $pdf->Cell(71, 10, '', 0, 0);
    $pdf->Image('dist/img/fidelity.png', 100, -15, 300);
    $pdf->Cell(59, 10, '', 0, 0);

    $pdf->SetLineWidth(0.05);  
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->Line(10, $pdf->GetY() + 32, 200, $pdf->GetY() + 32,);
    $pdf->Line(10, $pdf->GetY() + 33, 200, $pdf->GetY() + 33,);

    // Add text content using MultiCell
    $content = 'Account Type: ' .$row['acc_type'];
    $pdf->SetXY(10, $pdf->GetY() + 40);
    $pdf->SetX(150); 
    $pdf->Cell(80, 2, $content,0,0);

    $content = 'Account Number: ' .$row['account_number'];
    $pdf->SetXY(10, $pdf->GetY() + 5);
    $pdf->SetX(150); 
    $pdf->Cell(80, 2, $content,0,0);
        
    $content = 'Name: ' .$row['acc_name'];
    $pdf->SetXY(10, $pdf->GetY() -5);
    $pdf->MultiCell(0, 2, $content);

    $content ='Phone: ' .$row['client_phone'];
    $pdf->SetXY(10, $pdf->GetY()+ 5);
    $pdf->MultiCell(0, 2, $content);

    $row_client =$result_client->fetch_assoc();
    if (isset($row_client['address'])) {
        $content = 'Address: ' . $row_client['address'];
        $pdf->SetXY(10, $pdf->GetY() + 5);
        $pdf->MultiCell(0, 2, $content);
    }
    if(isset($row_client['email'])){
        $content = 'Email: ' . $row_client['email'];
        $pdf->SetXY(10, $pdf->GetY() + 5);
        $pdf->MultiCell(0, 2, $content);
    }

    $account_number = $row['account_number'];
    $current_balance = getAccountBalance($client_id, $account_number, $mysqli);


    $pdf->Cell(50, 10, 'Date', 2);
    $pdf->Cell(60, 10, 'Description', 2);
    $pdf->Cell(40, 10, 'Debit', 2);
    $pdf->Cell(30, 10, 'Credit', 2);
    $pdf->Ln();
    

    

    
// Output data from the database
    while ($row = $result->fetch_assoc()) {
       $dated= $row['created_at'];
       $formatted_date = date('F d, Y', strtotime($dated));
       $account_balance = getAccountBalance($client_id, $account_number,$mysqli);
        //fetch all detail from the ib-bankaccount table
        $query = "SELECT * FROM ib_bankAccounts WHERE account_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('s',$account_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row_cur = $result->fetch_assoc();


       $pdf->Cell(50, 5, $formatted_date, 2);
       $pdf->Cell(60, 5, $row['description'], 2);
       $pdf->Cell(40, 5, ($row["tr_type"] == "Withdrawal" || $row["tr_type"] == "Transfer") ? 'GH'. $row["transaction_amt"] : '', 0, 0);
       $pdf->Cell(30, 5, ($row["tr_type"] == "Deposit" ? $row_cur['currency_type'] . $row["transaction_amt"] : ''), 0);
       
       $pdf->Ln();


    

    
    }
    //fetch all detail from the ib-bankaccount table
    $query = "SELECT * FROM ib_bankAccounts WHERE account_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s',$account_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row_cur= $result->fetch_assoc();
    $account_balance = getAccountBalance($client_id, $account_number,$mysqli);




    
    $pdf->Cell(190, 20, 'Balance: ' . $row_cur['currency_type'] . $account_balance , 2);
    
    $pdf->Output();
}



function getAccountBalance($client_id, $account_number, $mysqli){

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

    // Calculate the total wire transfer for the account
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

?>
