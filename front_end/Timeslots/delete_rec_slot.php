<?php

// check request
if (isset($_POST['slot_id']) && isset($_POST['slot_id']) != "" && isset($_POST['day']) && isset($_POST['day']) != "" && isset($_POST['start_time']) && isset($_POST['start_time']) != "" && isset($_POST['end_time']) && isset($_POST['end_time']) != "") {
    // include Database connection file
//    include("db_connection.php");
    include_once '../DBconnection.php';
    // get user id
    $ts_id = $_POST['slot_id'];
    $day = $_POST['day'];
    $start_time= $_POST['start_time'];
    $end_time= $_POST['end_time'];
    // delete User
    $query = "DELETE FROM RECURRING_TS WHERE ts_id = '".$ts_id."' and weekday= '".$day."' and start_time= '".$start_time."' and end_time= '".$end_time."'";
    if (!$result = mysqli_query($conn, $query)) {
        exit(mysqli_error($conn));
    }
}
?>