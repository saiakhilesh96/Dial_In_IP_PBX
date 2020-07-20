<?php

if (isset($_POST['plan_name']) && isset($_POST['calltype_name']) && isset($_POST['charge_paise']) && isset($_POST['duration_sec']) && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    // include Database connection file 
//        include("db_connection.php");
    include_once '../DBconnection.php';

    // get values 
    $Plan_name = $_POST['plan_name'];
    $calltype_name = $_POST['calltype_name'];
    $charge_paise = $_POST['charge_paise'];
    $duration_sec = $_POST['duration_sec'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $O_prefix = $_POST['o_prefix'];
    $I_prefix = $_POST['i_prefix'];


//    $planname = "SELECT plan_name,plan_id from PLAN_MASTER WHERE plan_name ='" . $plan_name . "'";
//    if ($result = mysqli_query($conn, $planname)) {
//        $FR = mysqli_fetch_array($result);
//        $planname = "UPDATE PLAN_MASTER SET plan_name='" . $plan_name . "' WHERE plan_id ='" . $FR['plan_id'] . "'";
//        mysqli_query($conn, $planname);
//        exit(mysqli_error($conn));
//    }


    $p = "select plan_id from PLAN_MASTER where plan_name='" . $Plan_name . "'";
    $Rp = mysqli_query($conn, $p);
    if (mysqli_num_rows($Rp) == 0) {
        $Rp = "insert into PLAN_MASTER(plan_name) VALUES ('" . $Plan_name . "');";
        $Rp = mysqli_query($conn, $Rp);
        $p = "select plan_id from PLAN_MASTER where plan_name='" . $Plan_name . "'";
        $Rp = mysqli_query($conn, $p);
    }
    $FRp = mysqli_fetch_array($Rp);

    $ct = "select calltype_id from CALL_TYPE where calltype_name='" . $calltype_name . "'";
    $Rct = mysqli_query($conn, $ct);
    if (mysqli_num_rows($Rct) == 0) {
        $ct = "INSERT INTO CALL_TYPE(calltype_name,o_prefix,i_prefix) VALUES('" . $calltype_name . "','" . $O_prefix . "','" . $I_prefix . "')";
        $Rct = mysqli_query($conn, $ct);
        $ct = "select calltype_id from CALL_TYPE where calltype_name='" . $calltype_name . "'";
        $Rct = mysqli_query($conn, $ct);
    }
    $FRct = mysqli_fetch_array($Rct);

    $query = "INSERT INTO PLAN(plan_id, calltype_id, charge_paise, duration_sec, start_date, end_date) VALUES('" . $FRp['plan_id'] . "', '" . $FRct['calltype_id'] . "', '" . $charge_paise . "', '" . $duration_sec . "', '" . $start_date . "', '" . $end_date . "')";
    if (!$result = mysqli_query($conn, $query)) {
        exit(mysqli_error($conn));
    }
}
?>