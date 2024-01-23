<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];

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
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Check Deposit</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="pages_checkdeposit.php">iBank Finances</a></li>
                <li class="breadcrumb-item active">Check Deposit</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Approve or Reject these check deposits</h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-hover table-bordered table-striped">
                  <thead>
                    <div id="result"></div>
                    <tr>
                      <th>#</th>
                      <th>Tr Code</th>
                      <th>Acc Name</th>
                      <th>Account Number</th>
                      <th>Acc Type</th>
                      <th>Tr Type</th>
                      <th>Deposit Type</th>
                      <th>Check Image</th>
                      
                      <th>Check Number</th>
                      <th>Check Amt</th>

                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $output="";
                    //fetch all iB_Accs
                    $ret = "SELECT * FROM iB_Transactions WHERE tr_status = 'Pending'";
                    $stmt = $mysqli->prepare($ret);
                    $stmt->execute(); //ok
                    $res = $stmt->get_result();
                    $cnt = 1;
                  
                    while ($row = $res->fetch_object()) {
                      //Trim Timestamp to DD-MM-YYYY : H-M-S
                      $dateOpened = $row->created_at;
                      $cnt = $cnt + 1;
                      $output .='
                        <tr>
                        <td> '.$cnt.'</td>
                        <td> '.$row->tr_code.'</td>
                        <td>'.$row->acc_name.'</td>
                        <td>'.$row->account_number.'</td>
                        <td>'. $row->acc_type.'</td>
                        <td>'. $row->tr_type.'</td>
                        <td>'.$row->depo_type.'</td>
                        <td class="image-cell" data-front-image="dist/img/'.$row->frontcheck_image.'" data-back-image="dist/img/'. $row->back_checkimage.'">
                            '.$row->frontcheck_image.' '.$row->back_checkimage.'
                        </td>

                        
                        <td>'. $row->check_number.'</td>
                        <td>GH₵ '.$row->transaction_amt.'</td>

                        <td>
                         <button id="'.$row->tr_id.'" class="btn btn-primary approve" style="height:20px;font-size: 10px; padding-bottom: 20px;">Approve</button>

                         <button id="'.$row->tr_id.'" class="btn btn-danger reject" style="height:20px;font-size: 10px; padding-bottom: 20px;">Reject</button>

                        </td>
                        </tr>

                      ';

                    }

                    
                     echo $output;
                     $cnt = $cnt + 1;
                    ?>

                      
                    
                    </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </section>
      <!-- /.content -->
    </div>
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
  <!-- DataTables -->
  <script src="plugins/datatables/jquery.dataTables.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <!-- page script -->
  <script>
    $(function() {
      $("#example1").DataTable();
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
      });
    });

$(document).on('click', '.approve', function (e) {
  e.preventDefault();


  var id = $(this).attr("id");

  $.ajax({
    method: "POST",
    url: "ajax_checkapprove.php",
    data: { id:id },
    success: function (data) {
      if (data === 'success') {
        alert('Transfer approved successfully!');
       location.reload();
        $("#result").html(data);
      } else {
        alert('Failed to approve transfer.');
         $("#result").html(data);
      }
    }
  });
});

$(document).on('click', '.reject', function(e){
  e.preventDefault();
  var id = $(this).attr("id");
  $.ajax({
    method:"POST",
    url:"ajax_checkreject.php",
    data:{id:id},
    success:function(data){
            if (data === 'Reject') {
        alert('Transfer reject successfully!');
        location.reload();
        $("#result").html(data);
      } else {
        alert('Failed to reject transfer.');
         $("#result").html(data);
      }
    }
  })
});

  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
        var imageCell = document.querySelector(".image-cell");

        imageCell.addEventListener("click", function () {
            var frontImage = imageCell.getAttribute("data-front-image");
            var backImage = imageCell.getAttribute("data-back-image");

            if (frontImage) {
                window.open(frontImage, "_blank");
            }

            if (backImage) {
                window.open(backImage, "_blank");
            }
        });
    });
</script>

</body>

</html>