<?php

//the following code is used to add a new user entry
if (isset($_POST['user_name']) && isset($_POST['user_id']) && isset($_POST['batch']) && isset($_POST['room']) && isset($_POST['user_group']) && isset($_POST['type']) && isset($_POST['course'])) {
    // include Database connection file 
    include_once '../DBconnection.php';

    // get values
    $user_id= $_POST['user_id'];
    $user = $_POST['user_name'];
    $user_name= strtoupper($user);
    $batch = $_POST['batch'];
    $room_no = $_POST['room'];
    $room= strtoupper($room_no);
    $user_group = $_POST['user_group'];
    $type= $_POST['type'];
    $course= $_POST['course'];

    $query= "INSERT INTO USERS(user_id,user_name,batch,course_id,ug_id,room_no,access_level,pin) VALUES(".$user_id.",'".$user_name."',".$batch.",".$course.",".$user_group.",'".$room."','".$type."',MD5(123456))";
    if (!$result = mysqli_query($conn, $query)) {
        exit(mysqli_error($conn));
    }
    echo "1 Record Added!";
}
?>