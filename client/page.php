<div class="col-md-12">
        <div class="row" >
            <div class="col-md-8" id="pay_detail">
                <div class="row">
                    <div class="col-md-6" id="internal">
                        <p>INTERNAL</p>
                    </div>
                    <div class="col-md-2 show-button" id="show" data-target="content-1">
                        <a href="javascript:void(0);"><p>SHOW <i class="fa-solid fa-angle-up fa-rotate-180"></i></p></a>
                        </div>
                </div>

                <!-- <div class="row show-content" id="content-1" style="display: none; background-color: white; height: 360px;">
                    <div class="col-md-12" id="hidden-content">
                        <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-10">
                            <div id="resultContainer"></div>



                            <form method="post" id="transferForm">
                            <div>
                                <?php
                                 $client_id =$_SESSION['client_id'];
                                 $query = " SELECT acc_type, account_number, account_id FROM ib_bankaccounts WHERE client_id=?";
                                 $stmt = $mysqli->prepare($query);
                                 $stmt-> bind_param("s", $client_id);
                                 $stmt->execute();
                                 $results = $stmt->get_result();

  

                                 $output="";

                                 while ($row = $results->fetch_assoc()) {
                                    $account_id = $row['account_id'];
                                    $account_number = $row['account_number'];
                                    $account_balance = getAccountBalance($client_id, $account_id,$account_number, $mysqli);

                                    $output .= '<option value=" ' . $row['account_id'] . ' ' . $row['account_number'] . '" data-balance="' . $account_balance . '">
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
                                <label for="from_account">From account</label>
                                <select id="from_account" name="from_account" class="" onchange="updateAvailableBalance()">
                                      <option value="">Select Account</option>
                                      <?php echo $output; ?>
                                </select>
    
                                <p id="available_balance" style="color:black; font-size: 12px;">Available balance GH₵ <?php
                                if (isset($_POST['from_account'])) {
                                
                                $account_number=$_POST['from_account'];
                                 if (empty($account_number)){
                                    echo" no selected account";
                                 }else{echo $account_balance;} } ?> </p>
                            </div>

                            <div>
                                <label for="to_account">TO account</label>
                                <select id="to_account" name="to_account" class="" onchange="AvailableBalance()">
                                      <option value="">Select Account</option>
                                      <?php echo $output; ?>
                                </select>
    
                                
                                <p id="balance" style="color:black; font-size: 12px;">Available balance GH₵ <?php
                                if (isset($_POST['to_account'])) {
                                
                                $account_number=$_POST['to_account'];
                                 if (empty($account_number)){
                                    echo" no selected account";
                                 }else{echo $account_balance;} } ?> </p>

                            <div>
                                Date <input type="date" name="tr_date">
                            </div><br>
                            <div>
                                <label>Amount</label> <br>
                                <input type="text" name="tr_amt">
                            </div>
                            <div class=" col-md-4 form-group" style="display:none">
                                <label for="exampleInputPassword1">Transaction Status</label>
                                <input type="text" name="tr_status" value="Success" required class="form-control" id="exampleInputEmail1">
                            </div>

                            <button type="button" id="transferButton" name="int_transfer" style="border:none;background-color:#007b85 ; margin-top:15px; float:right; width: 100px; border-radius: 2px; color: white;">Tranfer</button>
                            </form>
                            
                                
                        
                        <div class="col-md-1"></div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>


    <div class="col-md-8">
    <div class="row">
        <div class="col-md-6">
            <p style="color: white;">PAY BILLS</p>
        </div>
        <div class="col-md-2 show-button" id="show" data-target="content-2">
            <a href="javascript:void(0);"><p style="margin-left:50px">SHOW <i class="fa-solid fa-angle-up fa-rotate-180"></i></p></a>
        </div>
        </div>

        <div class="row show-content" id="content-2" style="display: none;">
            <div class="col-md-12" id="hidden-content">
                <p>xxxxxxaccoutdfghjkl</p>
            </div>
            </div>


            <div class="col-md-6">
            <p style="color: white;">PAY BILLS</p>
        </div>
        <div class="col-md-2 show-button" id="show" data-target="content-2">
            <a href="javascript:void(0);"><p style="margin-left:50px">SHOW <i class="fa-solid fa-angle-up fa-rotate-180"></i></p></a>
        </div>
        </div>

        <div class="row show-content" id="content-2" style="display: none;">
            <div class="col-md-12" id="hidden-content">
                <p>xxxxxxaccoutdfghjkl</p>
            </div>
        </div>

           <div class="col-md-8">
           <div class="row">
        
            </div>
        </div>

    </div>
</div>
<div class="col-md-12" style="margin-top:30px">
    <div class="row">
        <div class="col-md-10" id="management">
                <h6>Cash Management</h6>
                    <hr>
                    <a href="" class="custom-link"><p>Go to positive pay</p></a>
                    <hr>
        </div>
        </div>
    </div>