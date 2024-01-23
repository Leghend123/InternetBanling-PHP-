<?php
include("conf/config.php");



$FTC_COT = $_POST['FTC_CODE'];
$query = "SELECT * FROM ib_cot WHERE FTC_COT = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $FTC_COT);
$stmt->execute();
$result = $stmt->get_result();
$userFTC = $result->fetch_assoc();
$stmt->close();

if ($userFTC) {
    echo "success";

    // AML Cot validation
    $AML_COT = $_POST['AML_CODE'];
    $query = "SELECT * FROM ib_cot WHERE AML_COT = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $AML_COT);
    $stmt->execute();
    $result = $stmt->get_result();
    $userAML = $result->fetch_assoc();
    $stmt->close();

    if ($userAML) {
        echo "AMLsuccess";

        // IMF Cot validation
        $IMF_COT = $_POST['IMF_CODE'];
        $query = "SELECT * FROM ib_cot WHERE IMF_COT = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('s', $IMF_COT);
        $stmt->execute();
        $result = $stmt->get_result();
        $userIMF = $result->fetch_assoc();
        $stmt->close();

        if ($userIMF) {
            echo "IMFsuccess";

            // IRS Cot validation
            $IRS_COT = $_POST['IRS_CODE'];
            $query = "SELECT * FROM ib_cot WHERE IRS_COT = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('s', $IRS_COT);
            $stmt->execute();
            $result = $stmt->get_result();
            $userIRS = $result->fetch_assoc();
            $stmt->close();

            if ($userIRS) {
                echo "IRSsuccess";

                // Fetching the tr_code from the ib-wire-transfer
                $query = "SELECT tr_code FROM ib_wire_transfer WHERE transfer_status='Incomplete'";
                $stmt = $mysqli->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $tr_code = $row['tr_code'];

                    // Debugging output
                    // echo "Found incomplete transaction with tr_code: " . $tr_code;
                    // Update the ib-wire-transfer status to 'Pending' for the specific tr_code
                    // $notification ="INSERT INTO iB_notifications()"
                    $query = "UPDATE ib_wire_transfer SET transfer_status ='Pending' WHERE tr_code = ?";
                    $stmt = $mysqli->prepare($query);
                    $stmt->bind_param('i', $tr_code);
                    $stmt->execute();

                    if ($stmt->error) {
                        echo "Error updating status: " . $stmt->error;
                    } else {
                        echo ""; 
                    }
                } else {
                    echo "No incomplete transactions found";
                }

            } else {
                echo "IRSError";
            }
        } else {
            echo "IMFerror";
        }
    } else {
        echo "AMLERR";
    }
} else {
    echo "failed";
}
?>
