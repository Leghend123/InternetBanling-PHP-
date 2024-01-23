<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];
//register new account

if (isset($_POST['deposit'])) {
    $tr_code = $_POST['tr_code'];
    $account_id = $_GET['account_id'];
    $acc_name = $_POST['acc_name'];
    $account_number = $_GET['account_number'];
    $acc_type = $_POST['acc_type'];
    //$acc_amount  = $_POST['acc_amount'];
    $tr_type  = $_POST['tr_type'];
    $tr_status = $_POST['tr_status'];
    $client_id  = $_GET['client_id'];
    $client_name  = $_POST['client_name'];
    $client_national_id  = $_POST['client_national_id'];
    $transaction_amt = $_POST['transaction_amt'];
    $client_phone = $_POST['client_phone'];
    $description = $_POST['tr_description'];
    $depo_type = $_POST['depo_type'];
    $check_number = $_POST['check_number'];
    $back_img_check = $_FILES['back_check']['name'];
    $front_img_check = $_FILES['front_check']['name'];   
    $balance = getAccountBalance($client_id, $account_id, $account_number, $mysqli);

    move_uploaded_file($back_img_check, "dist/img/" . $_FILES["back_check"]["name"]);
    move_uploaded_file($_FILES['front_check']['tmp_name'], "dist/img/" . $_FILES["front_check"]["name"]);



    //Notication
    $notification_details = "$client_name Has Deposited GH₵  $transaction_amt To Bank Account $account_number";


    //Insert Captured information to a database table
    $query = "INSERT INTO iB_Transactions (tr_code, account_id, acc_name, account_number, acc_type, tr_type,depo_type,description,frontcheck_image, back_checkimage,check_number, tr_status, client_id, client_name, client_national_id, transaction_amt,balance ,client_phone) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $notification = "INSERT INTO  iB_notifications ( notification_details) VALUES (?)";

    $stmt = $mysqli->prepare($query);
    $notification_stmt = $mysqli->prepare($notification);

    //bind paramaters
    $rc = $notification_stmt->bind_param('s', $notification_details);
    $rc = $stmt->bind_param('ssssssssssssssssss', $tr_code, $account_id, $acc_name, $account_number, $acc_type, $tr_type,$depo_type,$description,$front_img_check,$back_img_check ,$check_number, $tr_status, $client_id, $client_name, $client_national_id, $transaction_amt,$balance, $client_phone);
    $stmt->execute();
    $notification_stmt->execute();


    //declare a varible which will be passed to alert function
    if ($stmt && $notification_stmt) {
        $success = "Money Deposited";
    } else {
        $err = "Please Try Again Or Try Later";
    }
}


function getAccountBalance($client_id, $account_id, $account_number, $mysqli) {

        // Calculate the total deposits for the account
        $query = "SELECT COALESCE(SUM(transaction_amt), 0) AS total_deposits FROM iB_Transactions WHERE -client_id = ? AND account_number = ? AND tr_type = 'Deposit'";
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
        $stmt->bind_param('s', $account_id);
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
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<?php include("dist/_partials/head.php"); ?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include("dist/_partials/nav.php"); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include("dist/_partials/sidebar.php"); ?>

        <!-- Content Wrapper. Contains page content -->
        <?php
        $account_id = $_GET['account_id'];
        $ret = "SELECT * FROM  iB_bankAccounts WHERE account_id = ? ";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $account_id);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        $cnt = 1;
        while ($row = $res->fetch_object()) {

        ?>
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Deposit Money</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="pages_deposits">iBank Finances</a></li>
                                    <li class="breadcrumb-item"><a href="pages_deposits.php">Deposits</a></li>
                                    <li class="breadcrumb-item active"><?php echo $row->acc_name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- general form elements -->
                                <div class="card card-purple">
                                    <div class="card-header">
                                        <h3 class="card-title">Fill All Fields</h3>
                                    </div>
                                    <!-- form start -->
                                    <form method="post" enctype="multipart/form-data" role="form">
                                        <div class="card-body">

                                            <div class="row">
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Client Name</label>
                                                    <input type="text" readonly name="client_name" value="<?php echo $row->client_name; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Client National ID No.</label>
                                                    <input type="text" readonly value="<?php echo $row->client_national_id; ?>" name="client_national_id" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Client Phone Number</label>
                                                    <input type="text" readonly name="client_phone" value="<?php echo $row->client_phone; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Account Name</label>
                                                    <input type="text" readonly name="acc_name" value="<?php echo $row->acc_name; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Account Number</label>
                                                    <input type="text" readonly value="<?php echo $row->account_number; ?>" name="account_number" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Account Type | Category</label>
                                                    <input type="text" readonly name="acc_type" value="<?php echo $row->acc_type; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputEmail1">Transaction Code</label>
                                                    <?php
                                                    //PHP function to generate random account number
                                                    $length = 10;
                                                    $_transcode =  substr(str_shuffle('0123456789QWERgfdsazxcvbnTYUIOqwertyuioplkjhmPASDFGHJKLMNBVCXZ'), 1, $length);
                                                    ?>
                                                    <input type="text" name="tr_code" readonly value="<?php echo $_transcode; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputPassword1">Amount Deposited <?php echo "($row->currency_type)"; ?></label>
                                                    <input type="text" name="transaction_amt" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group" style="display:none">
                                                    <label for="exampleInputPassword1">Transaction Type</label>
                                                    <input type="text" name="tr_type" value="Deposit" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group" style="display:none">
                                                    <label for="exampleInputPassword1">Transaction Status</label>
                                                    <input type="text" name="tr_status" value="Success " required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputPassword1">Description</label>
                                                    <input type="text" name="tr_description" required class="form-control" id="exampleInputEmail1">
                                                </div>

                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputPassword1">Deposit Type</label>
                                                    <select  id="depositType" name="depo_type" required class="form-control">
                                                        <option value="">Select Deposit type </option>
                                                        <option value="Cash">Cash</option>
                                                        <option value="Check">Check</option>
                                                    </select>
                                                </div>
                                                <div id="checkDetails" style="display: none;">
                                                  <div class="col-md-12 form-group">
                                                    <label for="checkNumber">Check Number</label>
                                                    <input type="text" id="checkNumber" name="check_number" class="form-control">
                                                  </div>
                                                  <div class="col-md-12 form-group">
                                                      <label for="checkImage">Upload Front Check Image</label>
                                                      <input type="file" id="checkImage" name="front_check" class="form-control">
                                                  </div>
                                                  <div class="col-md-12 form-group">
                                                      <label for="checkImage">Upload Back Check Image</label>
                                                      <input type="file" id="checkImage" name="back_check" class="form-control">
                                                  </div>

                                              </div>

                                            </div>

                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" name="deposit" class="btn btn-success">Deposit Funds</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
                            </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
        <?php } ?>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            bsCustomFileInput.init();
        });


         $(document).ready(function() {
        $('#depositType').change(function() {
            var selectedOption = $(this).val();
            if (selectedOption === 'Check') {
                $('#checkDetails').show();
            } else {
                $('#checkDetails').hide();
            }
        });
    });
    </script>
</body>

</html>