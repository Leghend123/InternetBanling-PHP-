<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];
//register new account

if (isset($_POST['deposit'])) {
    $tr_code = $_POST['tr_code'];    
    $client_number = $_POST['client_number'];
    $client_id = $_GET['client_id'];
    //$acc_amount  = $_POST['acc_amount'];
    $name  = $_POST['name'];
    $bill_amt = $_POST['bill_amt'];
    $phone = $_POST['client_phone'];
    $description = $_POST['tr_description'];
    


     $notification_details = "You Have Created GH₵ $bill_amt $ To Bank Account $client_number";
    //Insert Captured information to a database table
    $query = "INSERT INTO iB_paybill ( client_id,tr_code, name, client_number, description, bill_amt,phone) VALUES (?,?,?,?,?,?,?)";
    $notification = "INSERT INTO  iB_notifications (notification_details) VALUES (?)";

    $stmt = $mysqli->prepare($query);
    $notification_stmt = $mysqli->prepare($notification);

    //bind paramaters
    $rc = $notification_stmt->bind_param('s', $notification_details);
    $rc = $stmt->bind_param('sssssss',$client_id, $tr_code, $name, $client_number,$description, $bill_amt, $phone);
    $stmt->execute();
    $notification_stmt->execute();


    //declare a varible which will be passed to alert function
    if ($stmt && $notification_stmt) {
        $success = "Bill Created";
    } else {
        $err = "Please Try Again Or Try Later";
    }
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
        $client_id = $_GET['client_id'];
        $ret = "SELECT * FROM  iB_clients  WHERE client_id = ? ";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $client_id);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        $cnt = 1;
        if($row = $res->fetch_object()) {

        ?>
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Pay Bills</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="pages_pay_bills.php">iBank pay bills</a></li>
                                    <li class="breadcrumb-item"><a href="pages_pay_bills.php">Pay Bills</a></li>
                                    <li class="breadcrumb-item active"><?php echo $row->name; ?></li>
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
                                                    <input type="text" readonly name="name" value="<?php echo $row->name; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Client Phone Number</label>
                                                    <input type="text" readonly name="client_phone" value="<?php echo $row->phone; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Client Number</label>
                                                    <input type="text" readonly name="client_number" value="<?php echo $row->client_number; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="exampleInputPassword1">Description</label>
                                                    <input type="text" name="tr_description" required class="form-control" id="exampleInputEmail1">
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
                                                    <label for="exampleInputPassword1">Bills Amount(GH₵ )</label>
                                                    <input type="text" name="bill_amt" required class="form-control" id="exampleInputEmail1">
                                                </div>

                                                
                                              </div>

                                            </div>

                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" name="deposit" class="btn btn-success">Create a Bill</button>
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