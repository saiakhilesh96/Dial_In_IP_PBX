<?php

include_once '../DBconnection.php';
include_once '../Controller.php';
if ($_SESSION['bool'] != 1) {
    header('Location:ATSLogin.php');
    exit();
}


$term = mysqli_real_escape_string($conn, $_REQUEST['term']);
 
if(isset($term)){
    // Attempt select query execution
    $sql = "SELECT user_id,user_name FROM USERS WHERE user_id LIKE '" . $term . "%'";
    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            //echo "Name and balance of searched user";
            while($row = mysqli_fetch_array($result)){
                echo "<p>".$row['user_name']."</p>";
            }
            // Close result set
            mysqli_free_result($result);
        } else{
            echo "<p>No matches found</p>";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }
}
$user_id = $_POST['user_id'];
$amount = $_POST['amount'];
$name = $_SESSION['login_user'];
$bal = "select balance from USERS where user_id= '" . $user_id . "'";
$R_bal = mysqli_query($conn, $bal);
$FR_bal = mysqli_fetch_array($R_bal);
$balance = $FR_bal['balance'];
$newBal = $balance + $amount;
if (is_null($balance)) {
    print '<script>alert ("User ID does not exists");</script>';
    $msg = urlencode("User ID does not exists");
    header("Location:Recharges.php?msg=" . $msg."#moneybox");
} else {
    $qry = "Insert into RECHARGES(`user_id`,`amount`,`recharger_id`,`old_balance`,`new_balance`)"
            . "values ('" . $user_id . "','" . $amount . "','" . $name . "','" . $balance . "','" . $newBal . "')";
    mysqli_autocommit($conn, false);
    mysqli_query($conn, $qry);
    if (mysqli_errno($conn)) {
        printf("transaction aborted at Recharges table: %s\n", mysqli_error($conn));
        mysqli_rollback($conn);
        $msg = urlencode("Transaction aborted at Recharges table.This User Con not Recharge.");
        header("Location:Recharges.php?msg=" . $msg."#moneybox");
    } else {
        $updateUsersbal = "update USERS set balance= '" . $newBal . "' where user_id = '" . $user_id . "'";
        mysqli_query($conn, $updateUsersbal);
        if (mysqli_error($conn)) {
            printf("transaction aborted at Users Table: %s\n", mysqli_error($conn));
            mysqli_rollback($conn);
            $msg = urlencode("Transaction aborted at Users table.It failed update in Recharges.");
            header("Location:Recharges.php?msg=" . $msg."#moneybox");
        } else {
            print '<script>alert ("Transaction Successfully Done");</script>';
            mysqli_commit($conn);
            $msg = urlencode("Transaction Successfully Done");
            header("Location:Recharges.php?msg=" . $msg."#moneybox");
        }
    }
}
mysqli_close($conn);
?>