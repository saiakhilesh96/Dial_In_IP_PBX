<?php

//the following code is used to add a new Recurring time slot entry
if (isset($_POST['slot_name']) && isset($_POST['weekday']) && isset($_POST['start_time']) && isset($_POST['end_time'])) {
    // include Database connection file 
//        include("db_connection.php");
    include_once '../DBconnection.php';

    // get values 
    $slot_name = $_POST['slot_name'];
    $week_day = $_POST['weekday'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $p = "SELECT ts_id FROM TIME_SLOT WHERE ts_name='" . $slot_name . "'";
    $Rp = mysqli_query($conn, $p);

    if (mysqli_num_rows($Rp) == 0) {
        $Rp = "INSERT INTO TIME_SLOT(ts_name,ts_flag) VALUES ('" . $slot_name . "','1')";
        mysqli_query($conn, $Rp);
        $p = "SELECT ts_id FROM TIME_SLOT WHERE ts_name= '" . $slot_name . "'";
        $Rp = mysqli_query($conn, $p);
    }
    $FRp = mysqli_fetch_array($Rp);
    $ts_id = $FRp['ts_id'];
    $query = "INSERT INTO RECURRING_TS(ts_id,weekday,start_time,end_time) VALUES(" . $ts_id . ",'" . $week_day . "','" . $start_time . "','" . $end_time . "')";
//        $query = "INSERT INTO PLAN(plan_id, calltype_id, charge_paise, duration_sec, start_date, end_date) VALUES('".$FRp['plan_id']."', '".$FRct['calltype_id']."', '".$charge_paise."', '".$duration_sec."', '".$start_date."', '".$end_date."')";
    echo "<script>alert(" . $query . ")</script>";
    if (!$result = mysqli_query($conn, $query)) {
        exit(mysqli_error($conn));
    }
    echo "1 Record Added!";
}
?>