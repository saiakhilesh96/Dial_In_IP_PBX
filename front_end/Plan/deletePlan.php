<?php

// check request
if (isset($_POST['plan_id']) && (isset($_POST['plan_id']) != "") && isset($_POST['calltype_id']) && (isset($_POST['calltype_id']) != "") && isset($_POST['start_date']) && (isset($_POST['start_date']) != "") && isset($_POST['end_date']) && (isset($_POST['end_date']) != "")) {
    // include Database connection file
    include_once '../DBconnection.php';
//    print '<script>alert("sairam");</script>';
    // get user id
    $plan_id = $_POST['plan_id'];
    $calltype_id = $_POST['calltype_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    // delete User
    $query = "DELETE FROM PLAN WHERE plan_id = '".$plan_id."' and calltype_id= '".$calltype_id."' and start_date='".$start_date."' and end_date= '".$end_date."'";
    if(!$result = mysqli_query($conn, $query)) {
        exit(mysqli_error($conn));
    }
}
?>