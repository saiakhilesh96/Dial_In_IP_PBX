<?php

// check request
if (isset($_POST['slot_id']) && isset($_POST['slot_id']) != "" ) {
    // include Database connection file
//    include("db_connection.php");
    include_once '../DBconnection.php';
    // get user id
    $ts_id = $_POST['slot_id'];
    // delete User
    $query = "DELETE FROM NONRECURRING_TS WHERE ts_id = '".$ts_id."'";
    if (!$result = mysqli_query($conn, $query)) {
        exit(mysqli_error($conn));
    }
}
?>