<?php

if (isset($_POST['location']) && isset($_POST['deviceid']) && isset($_POST['devicenumber'])) {
    // include Database connection file 
//        include("db_connection.php");
    include_once '../DBconnection.php';

    // get values 
    $location = $_POST['location'];
    $location= strtoupper($location);
    $deviceid = $_POST['deviceid'];
    $devicenumber = $_POST['devicenumber'];
    
//    $planname = "SELECT plan_name,plan_id from PLAN_MASTER WHERE plan_name ='" . $plan_name . "'";
//    if ($result = mysqli_query($conn, $planname)) {
//        $FR = mysqli_fetch_array($result);
//        $planname = "UPDATE PLAN_MASTER SET plan_name='" . $plan_name . "' WHERE plan_id ='" . $FR['plan_id'] . "'";
//        mysqli_query($conn, $planname);
//        exit(mysqli_error($conn));
//    }


    $p = "select sdg_desc from SIP_DEVICE_GROUP where sdg_desc='" . $location . "'";
    $Rp = mysqli_query($conn, $p);
    if (mysqli_num_rows($Rp) == 0) {
        $Rp = "insert into SIP_DEVICE_GROUP(sdg_desc) VALUES ('" . $location . "');";
        $Rp = mysqli_query($conn, $Rp);
        $p = "select sdg_id from SIP_DEVICE_GROUP where sdg_desc='" . $location . "'";
        $Rp = mysqli_query($conn, $p);
    }else{
       exit(mysqli_error($conn)); 
    }
    $FRp = mysqli_fetch_array($Rp);

    $ct = "select sd_id,sd_extension from SIP_DEVICES where sd_id='" . $deviceid . "' and sd_extension= '".$devicenumber."'";
    $Rct = mysqli_query($conn, $ct);
    if (mysqli_num_rows($Rct) == 0) {
        $ct = "INSERT INTO SIP_DEVICES(sd_id,sd_extension) VALUES('" . $deviceid . "','" . $devicenumber . "')";
        $Rct = mysqli_query($conn, $ct);
        $ct = "select sd_id from SIP_DEVICES where sd_id='" . $deviceid . "' and sd_extension= '".$devicenumber."'";
        $Rct = mysqli_query($conn, $ct);
    }
    $FRct = mysqli_fetch_array($Rct);

    $query = "INSERT INTO DEVICE_GROUP(sdg_id, sd_id) VALUES('" . $FRp['sdg_id'] . "', '" . $FRct['sd_id'] . "')";
    if (!$result = mysqli_query($conn, $query)) {
        exit(mysqli_error($conn));
    }
}
?>