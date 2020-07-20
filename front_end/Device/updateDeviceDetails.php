<?php
// include Database connection file
//include("db_connection.php");
include_once '../DBconnection.php';
 
// check request
if(isset($_POST['sdg_id']) && (isset($_POST['sdg_id']) != "") && isset($_POST['sd_id']) && (isset($_POST['sd_id']) != "") && isset($_POST['sd_extension']) && (isset($_POST['sd_extension']) != ""))
{
    // get values
    $sdg_id = $_POST['sdg_id'];
    $olddeviceid = $_POST['sd_id'];
    $olddevicenumber = $_POST['sd_extension'];
    $location = $_POST['location'];
    $newdeviceid= $_POST['deviceid'];
    $newdevicenumber= $_POST['devicenumber'];
 
    // Updaste User details
    $devicelocation = "UPDATE SIP_DEVICE_GROUP SET sdg_desc='".$location."' WHERE sdg_id ='".$sdg_id."'";
    if (!$result = mysqli_query($conn, $devicelocation)) {
        exit(mysqli_error($conn));
    }
    
    
    $query = "UPDATE SIP_DEVICES SET sd_id = '".$newdeviceid."', sd_extension='".$newdevicenumber."' WHERE sd_id = '".$olddeviceid."' and sd_extension= '".$olddevicenumber."'";
    if (!$result = mysqli_query($conn, $query)) {
        exit(mysqli_error($conn));
    }
    
    
    $DEVICE_GROUP = "UPDATE DEVICE_GROUP SET sd_id='".$newdeviceid."' WHERE sdg_id ='".$sdg_id."'";
    if (!$result = mysqli_query($conn, $DEVICE_GROUP)) {
        exit(mysqli_error($conn));
    }
}