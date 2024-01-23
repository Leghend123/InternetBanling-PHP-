<?php

include("dist/_partials/header.php");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PAY BILLS</title>
</head>
<body>
    <div class="container-fluid" style="margin-top: 20px;">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <table class="table table-responsive table-borderless">
                        <thead>
                            <tr>
                                <th style="color:#007b85; font-size:20px">Payments & transfers</th>
                            </tr>
                            <tr>
                                <th>
                                    <a href="pages_payment_transfers.php"><button style="background-color:navy; border: none; color:white; width: 90px;">Internal</button></a>
                                    <a href="pages_account_selector.php"><button style="background-color:navy; border:none; color: white; width:90px">Wire</button></a>
                                    <a href="pages_checkaccount_selector.php"><button style="background-color:navy; border: none; color:white;">Deposit Check</button></a>
                                    <a href="pages_pay_bills.php?client_id=<?php echo isset($_SESSION['client_id']) ? $_SESSION['client_id'] : ''; ?>">
                                       <button style="background-color:#007b85; border: none; color:white; width:90px">Bill pay</button>
                                    </a>
                                </th>
                            </tr>
                            <tr>
                                <td>Bills to Pay</td>
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
                    <table class="table table-responsive table-borderless">
                        <thead>
                            <tr>
                                <th>#</th>
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
                                echo '<td>' . $row['bill_id'] . '</td>';
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
                <div class="col-md-1"></div>
            </div>
        </div>
    </div>
</body>
</html>
