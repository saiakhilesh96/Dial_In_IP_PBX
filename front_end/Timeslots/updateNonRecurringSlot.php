<?php
// include Database connection file
//include("db_connection.php");
include_once '../DBconnection.php';
 
// check request
if(isset($_POST['slot_id']) && isset($_POST['slot_id']) != '')
{
    // get values
    $slot_id = $_POST['slot_id'];
    $slot_name = $_POST['slot_name'];   //slot name
    $oldendtime = $_POST['oldendtime']; //old end time
    $oldstarttime = $_POST['oldstarttime'];//old start time
    $oldstartdate= $_POST['oldstartdate'];//old start date
    $oldenddate= $_POST['oldenddate'];//old end date
    $start_date= $_POST['start_date']; //updated start_date
    $end_date= $_POST['end_date']; //updated end_date
    $start_time = $_POST['start_time']; //updated start_time
    $end_time = $_POST['end_time']; //updated end_time
 
    // Update User details
    //$query = "UPDATE NONRECURRING_TS SET start_date = str_to_date('".$start_date."','%d-%m-%Y'), start_time = '".$start_time."', end_time = str_to_date('".$end_time."','%d-%m-%Y'),end_date= '".$end_date."' WHERE ts_id = '".$slot_id."' and start_time= '".$oldstarttime."' and end_time='".$oldendtime."' and start_date= '".$oldstartdate."' and end_date= '".$oldenddate."'";
    $query = "UPDATE NONRECURRING_TS SET start_date = str_to_date('".$start_date."','%d-%m-%Y'), start_time = '".$start_time."', end_date = str_to_date('".$end_date."','%d-%m-%Y'),end_time= '".$end_time."' WHERE ts_id = ".$slot_id; 
    if (!$result = mysqli_query($conn, $query)) {
        exit(mysqli_error($conn));
    }
}