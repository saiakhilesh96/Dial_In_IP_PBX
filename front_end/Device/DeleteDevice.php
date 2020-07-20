<?php

// check request
if (isset($_POST['sdg_id']) && (isset($_POST['sdg_id']) != "") && isset($_POST['sd_id']) && (isset($_POST['sd_id']) != "") && isset($_POST['sd_extension']) && (isset($_POST['sd_extension']) != "")) {
    // include Database connection file
    include_once '../DBconnection.php';
//    print '<script>alert("sairam");</script>';
    // get user id
    $sdg_id = $_POST['sdg_id'];
    $sd_id = $_POST['sd_id'];
    $sd_extension = $_POST['sd_extension'];
    // delete User
    $query = "DELETE FROM DEVICE_GROUP WHERE sdg_id = '".$sdg_id."' and sd_id= '".$sd_id."';";
    if(!$result = mysqli_query($conn, $query)) {
        exit(mysqli_error($conn));
    }
    $query1= "DELETE FROM SIP_DEVICES WHERE sd_id = '".$sd_id."';";
    if(!$result = mysqli_query($conn, $query1)) {
        exit(mysqli_error($conn));
    }
    $query2= "DELETE FROM SIP_DEVICE_GROUP WHERE sdg_id = '".$sdg_id."';";
    if(!$result = mysqli_query($conn, $query2)) {
        exit(mysqli_error($conn));
    }
}
?>