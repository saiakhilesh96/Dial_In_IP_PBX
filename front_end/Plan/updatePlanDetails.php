<?php
// include Database connection file
//include("db_connection.php");
include_once '../DBconnection.php';
 
// check request
if(isset($_POST['plan_id']) && (isset($_POST['plan_id']) != "") && isset($_POST['calltype_id']) && (isset($_POST['calltype_id']) != "") && isset($_POST['start_date']) && (isset($_POST['start_date']) != "") && isset($_POST['end_date']) && (isset($_POST['end_date']) != ""))
{
    // get values
    $plan_id = $_POST['plan_id'];
    $calltype_id = $_POST['calltype_id'];
    $plan_name = $_POST['plan_name'];
    $calltype_name = $_POST['calltype_name'];
    $O_prefix= $_POST['o_prefix'];
    $I_prefix= $_POST['i_prefix'];
    $charge_paise = $_POST['charge_paise'];
    $duration_sec = $_POST['duration_sec'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $newstart_date= $_POST['newstart_date'];
    $newend_date = $_POST['newend_date'];
 
    // Updaste User details
    $planname = "UPDATE PLAN_MASTER SET plan_name='".$plan_name."' WHERE plan_id ='".$plan_id."'";
    if (!$result = mysqli_query($conn, $planname)) {
        exit(mysqli_error($conn));
    }
    
    $calltypename = "UPDATE CALL_TYPE SET calltype_name='".$calltype_name."',o_prefix= '".$O_prefix."',i_prefix= '".$I_prefix."' WHERE calltype_id ='".$calltype_id."'";
    if (!$result = mysqli_query($conn, $calltypename)) {
        exit(mysqli_error($conn));
    }
    
    $query = "UPDATE PLAN SET charge_paise = '".$charge_paise."', duration_sec='".$duration_sec."', start_date='".$newstart_date."', end_date='".$newend_date."' WHERE plan_id = '".$plan_id."' and calltype_id= '".$calltype_id."' and start_date= '".$start_date."' and end_date= '".$end_date."'";
    if (!$result = mysqli_query($conn, $query)) {
        exit(mysqli_error($conn));
    }
}