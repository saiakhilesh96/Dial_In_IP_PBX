<?php
// include Database connection file
//include("db_connection.php");
include_once '../DBconnection.php';
 
// check request
if(isset($_POST['plan_id']) && (isset($_POST['plan_id']) != "") && isset($_POST['calltype_id']) && (isset($_POST['calltype_id']) != "") && isset($_POST['start_date']) && (isset($_POST['start_date']) != "") && isset($_POST['end_date']) && (isset($_POST['end_date']) != ""))
{
    // get User ID
    $plan_id = $_POST['plan_id'];
    $calltype_id= $_POST['calltype_id'];
    $start_date= $_POST['start_date'];
    $end_date= $_POST['end_date'];
    // Get User Details
    $query = "SELECT * FROM PLAN WHERE plan_id = '".$plan_id."' and calltype_id= '".$calltype_id."' and start_date= '".$start_date."' and end_date='".$end_date."'";
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