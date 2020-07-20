<?php
// include Database connection file
//include("db_connection.php");
include_once '../DBconnection.php';
 
// check request
if(isset($_POST['slot_id']) && isset($_POST['slot_id']) != '' && isset($_POST['start_date']) && isset($_POST['start_date']) != '' && isset($_POST['start_time']) && isset($_POST['start_time']) != '' && isset($_POST['end_date']) && isset($_POST['end_date']) != '' && isset($_POST['end_time']) && isset($_POST['end_time']) != '')
{
    // get User ID
    $ts_id = $_POST['slot_id'];
    $start_date= $_POST['start_date'];
    $start_time= $_POST['start_time'];
    $end_date= $_POST['end_date'];
    $end_time= $_POST['end_time'];
    // Get User Details
    $query = "SELECT * FROM NONRECURRING_TS WHERE ts_id = ".$ts_id;
    if (!$result = mysqli_query($conn, $query)) {
        exit(mysqli_error($conn));
    }
    $response = array();
    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
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