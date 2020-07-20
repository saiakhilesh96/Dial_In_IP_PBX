<?php
// include Database connection file
//include("db_connection.php");
// check request
if(isset($_POST['sdg_id']) && (isset($_POST['sdg_id']) != "") && isset($_POST['sd_id']) && (isset($_POST['sd_id']) != "") && isset($_POST['sd_extension']) && (isset($_POST['sd_extension']) != ""))
{
    include_once '../DBconnection.php';
    $sdg_id = $_POST['sdg_id'];
    $sd_id = $_POST['sd_id'];
    $sd_extension = $_POST['sd_extension'];
    // Get User Details
    $query = "select sip.sdg_id,sdg_desc,dg.sd_id,sd.sd_extension from SIP_DEVICE_GROUP as sip inner join DEVICE_GROUP as dg on (dg.sdg_id = '".$sdg_id."' and sip.sdg_id= '".$sdg_id."') join SIP_DEVICES as sd on (dg.sd_id = '".$sd_id."' and sd.sd_id = '".$sd_id."');
";
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