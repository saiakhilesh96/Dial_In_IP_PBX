<?php
// include Database connection file
//include("db_connection.php");
include_once '../DBconnection.php';
 
// check request
if(isset($_POST['slot_id']) && isset($_POST['slot_id']) != '')
{
    // get values
    $slot_id = $_POST['slot_id'];
    $day = $_POST['day'];   //updated day
    $weekday = $_POST['weekday'];   //old day
    $slot_name = $_POST['slot_name'];   //slot name
    $oldendtime = $_POST['oldendtime']; //old end time
    $oldstarttime = $_POST['oldstarttime'];//old start time
    $start_time = $_POST['start_time']; //updated start_time
    $end_time = $_POST['end_time']; //updated end_time
 
    // Update User details
    $query = "UPDATE RECURRING_TS SET weekday = '".$day."', start_time = '".$start_time."', end_time = '".$end_time."' WHERE ts_id = '".$slot_id."' and weekday= '".$weekday."' and start_time= '".$oldstarttime."' and end_time='".$oldendtime."'";
    if (!$result = mysqli_query($conn, $query)) {
        exit(mysqli_error($conn));
    }
}