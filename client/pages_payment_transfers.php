<?php 
include("dist/_partials/header.php");

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

                                           // Calculate the account balance
                                           $account_balance = $total_deposits - ($total_withdrawals + $total_transfers + $total_wire_transfers);

                                           return $account_balance;
                                 }
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Payment</title>
</head>
<body>
	<div class="container-fluid" style="margin-top:30px">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-1"></div>
			    <div class="col-md-10" style="height:900px">
			    
			    	<table class=" table table-responsive table-borderless ">
			    		<thead>
			    			<tr>
			    				<th style="color:#007b85; font-size:20px">Payments & transfers</th>
			    			</tr>
			    			<tr>
			    				<th >
			    					<a href="pages_payment_transfers.php" target="_blank"><button style="background-color:#007b85; border: none; color:white; width: 90px;">Internal</button></a>
			    					<a href="pages_account_selector.php"><button style="background-color:navy; border:none; color: white; width:90px">Wire</button></a>
			    					<a href="pages_pay_bills.php"><button style="background-color:navy; border: none; color:white; width:90px">Bill pay</button></a>
			    				</th>
			    			</tr>
			    			<tr>
			    				<th style="color:#007b85; font-size:20px" colspan="8"> Create a transfer</th>
                            </tr>
			    			
			    		</thead>
			    	</table>
			    	<hr>
			    	<table class="table table-responsive">
			    		<tbody>
			    			<tr>
			    				<td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>
			    			    <td></td>

			    				<td>As a reminder, transfer processed before 9:00 p.m. EST will be submitted the same business day. Transfer after this time will be processed next business day. </td>
			    		
			    		</tbody>
			    	</table>
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

                <select style="width:240px; height:30px" id="from_account" name="from_accounts" onchange="updateAvailableBalance()" >
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
                <select style="width:240px; height:30px" id="to_account" name="to_accounts" onchange="AvailableBalance()">
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
			    <div class="col-md-1"></div>


			</div>
		</div>
	</div>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

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
        if (selectedOption) {
        	const balance = selectedOption.getAttribute('data-balance');
        	document.getElementById('available_balance').innerText = 'Available balance ' + balance;

        }else {
        	document.getElementById('available_balance').innerText = 'Available balance 0';

        }
        

    };

    function AvailableBalance() {
        const selectedAccount = document.getElementById('to_account');
        const selectedOption = selectedAccount.options[selectedAccount.selectedIndex];
        if (selectedOption) {
        	const balance = selectedOption.getAttribute('data-balance');
        	document.getElementById('balance').innerText = 'Available balance' + balance;


        } else{
        	document.getElementById('balance').innerText = 'Available balance 0';

        }

    };
</script>

</body>
</html>