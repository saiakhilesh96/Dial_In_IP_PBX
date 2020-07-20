<?php
// include Database connection file
//include("db_connection.php");
include_once '../DBconnection.php';
 
// check request
if(isset($_POST['slot_id']) && isset($_POST['slot_id']) != '' && isset($_POST['day']) && isset($_POST['day']) != '' && isset($_POST['start_time']) && isset($_POST['start_time']) != '' && isset($_POST['end_time']) && isset($_POST['end_time']) != '')
{
    // get User ID
    $ts_id = $_POST['slot_id'];
    $day= $_POST['day'];
    $start_time= $_POST['start_time'];
    $end_time= $_POST['end_time'];
    // Get User Details
    $query = "SELECT * FROM RECURRING_TS WHERE ts_id = '".$ts_id."' and weekday= '".$day."' and start_time= '".$start_time."' and end_time= '".$end_time."'";
    if (!$result = mysqli_query($conn, $query)) {
        exit(mysqli_error($conn));
    }
    $response = array();
    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
//            $q= "select plan_name from PLAN_MASTER where plan_id= '".$row['plan_id']."'";
//            $Rq= mysqli_query($conn, $q);
//            $FRq= mysqli_fetch_array($Rq);
//            $row= $row.$FRq;
            $response = $row;
        }
    }
    else
    {
        $response['status'] = 200;
        $response['message'] = "Data not found!";
    }
    // display JSON data
    echo json_encode($response);
}
else
{
    $response['status'] = 200;
    $response['message'] = "Invalid Request!";
}