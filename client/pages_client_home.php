<?php 
include("conf/config.php");
include("conf/checklogin.php");
include("dist/_partials/header.php");
$client_id = $_SESSION['client_id'];
$query = "SELECT * FROM iB_notifications WHERE client_id = ? ORDER BY created_at DESC LIMIT 3";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s',$client_id);
$stmt->execute();
$result=$stmt->get_result();

$stmt->close();

?>


<!DOCTYPE html>
<html>
<head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Home</title>
    <link rel="stylesheet" type="text/css" href="dist/stylesheet/style.css">

<body>


</head>
<div class="container-fluid">
<div class="col-md-12 p-4">
    <div class="row">
        <div class="col-md-8">
            <?php while ($row=$result->fetch_assoc()) { ?>
                <div class="alert alert- alert-dismissible fade show" role="alert" style="background-color:whitesmoke;">
                   <?php echo $row['notification_details'] ;?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> 
                </div>
            <?php } ?>
<?php

$client_id = $_SESSION['client_id'];

// Get all bank accounts for the client
$query = "SELECT acc_type, account_number ,account_id,currency_type FROM ib_bankaccounts WHERE client_id=?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $client_id);
$stmt->execute();
$result_account = $stmt->get_result();

echo '
<table class="table table-responsive">
    <thead>
        <tr>
            <th style="color:green">Accounts</th>
            <th><a href=""><i class="fa-solid fa-pen-to-square"></i><span>Edit Accounts</span></a></th>
            <th><a href=""><i class="fa-solid fa-print"></i><span>Print</span></a></th>
        </tr>
    </thead>
    <tbody>';

// Loop through each bank account
while ($row = $result_account->fetch_assoc()) {
    $account_number = $row['account_number'];

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

    // Output the account information and balance
    echo '
  
    <table class="table table-responsive ">

        <tbody>
            <tr>
                <td>
                    <span>' . $row["acc_type"] . ' ('.$row["currency_type"].')</span> <br><span>' . $row["account_number"] . '</span>
                </td>
                <td>
                    <span>Available balance </span><br><span>'.$row["currency_type"].'' . $account_balance . '</span>
                </td>
                <td>
                    <a href="javascript:void(0);" onclick="toggleContent(\'content_' . $row["account_number"] . '\')">
                        <h6>Recent <i class="fa-solid fa-angle-up fa-rotate-180"></i></h6>
                    </a>
                </td>
            </tr>
            </tbody>
            <tr>
                <td colspan="3" style="border:none">
                    <div class="col-md-12 hidden" id="content_' . $row["account_number"] . '">
                        <table class="table table-responsive">
                            <thead style="background-color: #003b5c !important; color:white;">
                                <tr >
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Credit</th>
                                    <th>Debit</th>
                                </tr>
                            </thead>
                            ';
    
    // Fetch and display recent transactions
    $query_recent = "SELECT * FROM iB_Transactions WHERE client_id=? AND account_number=? ORDER BY created_at DESC LIMIT 3";
    $stmt_recent = $mysqli->prepare($query_recent);
    $stmt_recent->bind_param('ss', $client_id, $account_number);
    $stmt_recent->execute();
    $recent_result = $stmt_recent->get_result();

    //loop all the recent transactions
    while ($row_recent = $recent_result->fetch_assoc()) {
        
        echo '
        <tr style="background-color: ' . ($row_recent["tr_type"] == "deposit" ? "white" : "whitesmoke") . '">
            <td>' . date('Y d M', strtotime($row_recent["created_at"])) . '</td>
            <td>' . $row_recent["description"] . '</td>
            <td>' . ($row_recent["tr_type"] == "Deposit" || $row_recent["tr_type"]=="Recieved Transfer" ? ' '.$row["currency_type"].' ' . $row_recent["transaction_amt"] : '') . '</td>
            <td>' . (($row_recent["tr_type"] == "Withdrawal" || $row_recent["tr_type"] == "Transfer") ? ''.$row["currency_type"].'' . $row_recent["transaction_amt"] : '') . '</td>
        </tr>';
    }

    echo '
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>';
}


?>
                  
</div>    
                
<div class="col-md-4" >
<h5 style="color:#007b85">Payment & Tranfers</h5>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <!-- <div class="col-md-1"></div> -->
            <?php 
                    $client_id =$_SESSION['client_id'];
                    $query = " SELECT acc_type, account_number,account_id,currency_type FROM ib_bankaccounts WHERE client_id=?";
                    $stmt = $mysqli->prepare($query);
                    $stmt-> bind_param("s", $client_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $output ="";
                    while ($row = $result->fetch_assoc()) {
                        $account_id = $row['account_id'];
                        $account_number = $row['account_number'];
                        $account_balance = getAccountBalance($client_id, $account_id,$account_number, $mysqli);
                        $output .= '<option value="' . $row['account_number'] . '" data-balance=" '.$row['currency_type'].'' . $account_balance . '">
                         ' . $row['acc_type'] . ' ' . $row['account_number'] . '
                </option>';
                    }

                   function getAccountBalance($client_id, $account_id, $account_number, $mysqli) {
                                           // Calculate the total deposits for the account
                                           $query = "SELECT COALESCE(SUM(transaction_amt), 0) AS total_deposits FROM iB_Transactions WHERE client_id = ? AND account_number = ? AND tr_type = 'Deposit'";
                                           $stmt = $mysqli->prepare($query);
                                           $stmt->bind_param('si', $client_id, $account_number);
                                           $stmt->execute();
                                           $stmt->bind_result($total_deposits);
                                           $stmt->fetch();
                                           $stmt->close();

                                           // Calculate the total withdrawals for the account
                                           $query = "SELECT COALESCE(SUM(transaction_amt), 0) AS total_withdrawals FROM iB_Transactions WHERE client_id = ? AND account_number = ? AND tr_type = 'Withdrawal'";
                                           $stmt = $mysqli->prepare($query);
                                           $stmt->bind_param('si', $client_id, $account_number);
                                           $stmt->execute();
                                           $stmt->bind_result($total_withdrawals);
                                           $stmt->fetch();
                                           $stmt->close();

                                           // Calculate the total transfers for the account
                                           $query = "SELECT COALESCE(SUM(transaction_amt), 0) AS total_transfers FROM iB_Transactions WHERE client_id = ? AND account_number = ? AND tr_type = 'Transfer'";
                                           $stmt = $mysqli->prepare($query);
                                           $stmt->bind_param('si', $client_id, $account_number);
                                           $stmt->execute();
                                           $stmt->bind_result($total_transfers);
                                           $stmt->fetch();
                                           $stmt->close();

                                           // Calculate the total wire transfer for the account
                                           $query = "SELECT COALESCE(SUM(transfer_amount), 0) AS total_wire_transfers FROM ib_wire_transfer WHERE account_id = ? AND transfer_status = 'Success'";
                                           $stmt = $mysqli->prepare($query);
                                           $stmt->bind_param('i', $account_id);
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
            
                    <div class="internal_wrapper">
                    <div class="col-md-10" id="internal" >
                        <p>INTERNAL</p>
                        <div class="internal_child" data-target="content-1">
                        <a href="javascript:void(0)"><span >SHOW <i class="fa-solid fa-angle-up fa-rotate-180"></i></span></a>
                        </div> 
                    </div>
                    <div class=" col-md-12 show-content" id="content-1" style="display: none; background-color: white;">
                       <table class="table table-responsive table-borderless">
                            <form method="post" id="transferForm">
                                <tr>
                                    <td></td>
                                    <td>
                                        <div id="resultContainer"></div> 
                                    </td>
                                </tr>
       
                                <tr>

                                    <td>
                                        <label>From Account <span style="color: red;">*</span></label>
                                    </td>
                                    <td>

                                        <select style=" height:30px" id="from_account" name="from_accounts" onchange="updateAvailableBalance()" >
                                            <option value="">Select Option</option>
                                            <?php echo $output; ?>
                                        </select>
                                        <p id="available_balance"></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>To Account <span style="color: red;">*</span></label>
                                    </td>
           

                                    <td>
                                        <select style=" height:30px" id="to_account" name="to_accounts" onchange="AvailableBalance()">
                                            <option value="">Select Option</option>
                                            <?php echo $output; ?>
                                        </select>
                                        <p id="balance"></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Date <span style="color: red;">*</span></label>
                                    </td>
                                    <td>
                                        <input type="date" name="tr_date" >
                                    </td>
                                </tr>
                                <tr>
                                    <!-- You had an issue here with misplaced closing </td> tag -->
                                    <td>
                                        <div style="display:none;">
                                            <label>Transaction Status <span style="color: red;">*</span></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display:none;">
                                        <input type="text" name="tr_status">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Amount <span style="color: red;">*</span></label>
                                    </td>
                                    <td>
                                        <input type="text" name="tr_amount" >
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Description</label>
                                    </td>
                                    <td>
                                      <input type="text" name="description">
                                      <p><span style="color:red;">*</span> indicates a required field</p>
                                  </td>
                              </tr>
                              <tr>
                                  <td></td>
                                  <td>
                                      <input type="button" id="transfermoney" name="tr_money" value="Transfer" style="background-color: #007b85; border:none; width:120px; color: white; border-radius: 2px;">
                                  </td>
                              </tr>
                          </form>
                      </table>
                    </div>
                    
                    </div>

                    <div class="paybill_wrapper">
                    <div class="col-md-10" id="pay_bill">
                        <p>PAY BILL</p> 
                        <div class="pay_bill_child" data-target="content-2">
                         <a href="javascript:void(0)"><span>SHOW <i class="fa-solid fa-angle-up fa-rotate-180"></i></span></a>
                        </div>
                    </div>
                    <div class="show-content" id="content-2" style="display: none; background-color:white; font-size:14px">
                         <table class="table table-responsive table-borderless">
                        <thead>
                            <tr>
                              
                                <th>Bill Description</th>
                                <th>Bill Amount</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            include("conf/config.php");

                            // Check if 'client_number' is set in the session
                            $client_number = isset($_SESSION['client_id']) ? $_SESSION['client_id'] : '';

                            $query = "SELECT * FROM ib_paybill WHERE client_id = ?";

                            $stmt = $mysqli->prepare($query);
                            $stmt->bind_param('s', $client_number);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                               
                                echo '<td>' . $row['description'] . '</td>';
                                echo '<td>GH₵' . $row['bill_amt'] . '</td>';
                                echo '<td>' . date('Y-m-d', strtotime($row['created_at'])) . '</td>';
                                echo '<td><a href="pages_checkout_btc.php">click here to Pay</a></td>';
                                echo '</tr>';
                            }

                            $stmt->close();
                            ?>
                        </tbody>
                    </table>
                    </div>
                    
                    </div>
                               

                    <div class="cash_management">
                    <h6>Cash Management</h6>
                    <hr>
                    <a href="pages_pay_bills.php" class="custom-link"><p>Go to positive pay</p></a>
                    <hr>
                  </div>
                    
                </div> 
                
            </div>

            <div class="col-md-2"></div>
        </div>
    </div>
</div>
    
</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
   <script>
           document.addEventListener("DOMContentLoaded", function() {
            var internalChildren = document.querySelectorAll(".internal_child");

            internalChildren.forEach(function(internalChild) {
                var contentId = internalChild.dataset.target;
                var content = document.getElementById(contentId);

                internalChild.addEventListener("click", function() {
                    if (content.style.display === "none" || content.style.display === "") {
                        content.style.display = "block";
                        internalChild.querySelector("i").classList.add("fa-rotate-180");
                    } else {
                        content.style.display = "none";
                        internalChild.querySelector("i").classList.remove("fa-rotate-180");
                    }
                });
            });
        });

    document.addEventListener("DOMContentLoaded", function() {
            var internalChildren = document.querySelectorAll(".pay_bill_child");

            internalChildren.forEach(function(internalChild) {
                var contentId = internalChild.dataset.target;
                var content = document.getElementById(contentId);

                internalChild.addEventListener("click", function() {
                    if (content.style.display === "none" || content.style.display === "") {
                        content.style.display = "block";
                        internalChild.querySelector("i").classList.add("fa-rotate-180");
                    } else {
                        content.style.display = "none";
                        internalChild.querySelector("i").classList.remove("fa-rotate-180");
                    }
                });
            });
        });    </script>
    

<script type="text/javascript">
      function toggleContent(contentId){
          var content = document.getElementById(contentId);
          content.classList.toggle("hidden");
      };
</script>
<script type="text/javascript">


$(document).ready(function () {
    $("#transfermoney").click(function (e) {
        e.preventDefault(); 
        var formData = $("#transferForm").serialize();

        $.ajax({
            type: 'POST',
            url: 'ajax_payment_transfer.php',
            data: formData,
            success: function (response) {
                if (response.includes('Transfer Successful!')) {
                    $('#transferForm')[0].reset();
                }   
                $('#resultContainer').html(response);
            }
        });
    });
});
</script>
<script>
    function updateAvailableBalance() {
        const selectedAccount = document.getElementById('from_account');
        const selectedOption = selectedAccount.options[selectedAccount.selectedIndex];
        const balance = selectedOption.getAttribute('data-balance');

        document.getElementById('available_balance').innerText = 'Available balance' + balance;
    }

    function AvailableBalance() {
        const selectedAccount = document.getElementById('to_account');
        const selectedOption = selectedAccount.options[selectedAccount.selectedIndex];
        const balance = selectedOption.getAttribute('data-balance');

        document.getElementById('balance').innerText = 'Available balance' + balance;
    }
</script>





  

</body>
</html>