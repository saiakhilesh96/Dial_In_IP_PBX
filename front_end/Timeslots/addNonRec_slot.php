<?php

//the following code is used to add a new Recurring time slot entry
if (isset($_POST['slot_name']) && isset($_POST['start_date']) && isset($_POST['start_time']) && isset($_POST['end_date']) && isset($_POST['end_time'])) {
    // include Database connection file 
//        include("db_connection.php");
    include_once '../DBconnection.php';

    // get values 
    $slot = $_POST['slot_name'];
    $slot_name = strtoupper($slot);
    $start_date = $_POST['start_date'];
    $start_time = $_POST['start_time'];
    $end_date = $_POST['end_date'];
    $end_time = $_POST['end_time'];

    $p = "SELECT ts_id FROM TIME_SLOT WHERE ts_name='" . $slot_name . "'";
    $Rp = mysqli_query($conn, $p);
    //if the timeslot exists in the master table
    if (mysqli_num_rows($Rp) == 0) {
        $Rp = "INSERT INTO TIME_SLOT(ts_name,ts_flag) VALUES ('" . $slot_name . "','0')";
        mysqli_query($conn, $Rp);
        $p = "SELECT ts_id FROM TIME_SLOT WHERE ts_name= '" . $slot_name . "'";
        $Rp = mysqli_query($conn, $p);
    }
    $FRp = mysqli_fetch_array($Rp);
    $ts_id = $FRp['ts_id'];
    
    $dup_qry = "SELECT * FROM NONRECURRING_TS WHERE ts_id= " . $ts_id;
    $dq = mysqli_query($conn, $dup_qry);
    //if the slot already exists in the non recurring time slots
    if (mysqli_num_rows($dq) == 0) {
        $query = "INSERT INTO NONRECURRING_TS(ts_id,start_date,start_time,end_date,end_time) VALUES(" . $ts_id . ",str_to_date('" . $start_date . "','%d-%m-%Y'),'" . $start_time . "',str_to_date('" . $end_date . "','%d-%m-%Y'),'" . $end_time . "')";
        if (!$result = mysqli_query($conn, $query)) {
            exit(mysqli_error($conn));
        }
        echo "1 Record Added!";
    }
}
?>