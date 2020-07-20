<?Php

//This is used for modifying a given whitelist contact
$userid = $_POST['userid'];
$oldnumber = $_POST['oldnumber'];
$relation = $_POST['relation'];
$person = $_POST['pname'];
$number = $_POST['phonenumber'];
$speeddial = $_POST['speeddial'];
$start = $_POST['startdate'];
$end = $_POST['enddate'];
$calltype = $_POST['calltype'];

$relation = strtoupper($relation);
$person = strtoupper($person);
$message = ''; // 
$status = 'success';              // Set the flag  
//sleep(2); // if you want any time delay to be added
//// Data validation starts ///

if (!is_numeric($speeddial)) { // checking data
    $message = "Data Error";
    $status = 'Failed';
}

if (!is_numeric($userid)) {  // checking data
    $message = "Data Error";
    $status = 'Failed';
}


//// Data Validation ends /////
if ($status <> 'Failed') {  // Update the table now
    require "config.php"; // MySQL connection string
    //get the calltype id
//    $calltype_qry = "SELECT calltype_id FROM CALL_TYPE WHERE calltype_name= :ct";
//    $idq = $dbo->prepare($calltype_qry);
//    $idq->bindParam(":ct", $calltype);
//    if ($idq->execute()) {
//        $calltypeid = $idq->fetchColumn();
//    } else {
//        $message = print_r($dbo->errorInfo());
//        $message = $dbo->errorInfo() . 'database error...';
//        $status = 'Failed';
//    }

    //pls help swami
    $count = $dbo->prepare("update WHITELIST_CONTACTS set calltype_id=:ctid,relation=:relation,p_name=:pname,phone_number=:phonenumber,speed_dial=:speeddial,start_date= str_to_date(:start,'%d-%m-%Y'),end_date= str_to_date(:end,'%d-%m-%Y') WHERE (user_id= :id) AND (phone_number=:old)");    //thank you swami
    $count->bindParam(":ctid", $calltype);
    $count->bindParam(":relation", $relation);
    $count->bindParam(":pname", $person);
    $count->bindParam(":phonenumber", $number);
    $count->bindParam(":speeddial", $speeddial);
    $count->bindParam(":start", $start);
    $count->bindParam(":end", $end);
    $count->bindParam(":id", $userid);
    $count->bindParam(":old", $oldnumber);

    if ($count->execute()) {
        $no = $count->rowCount();
        $message = " $no Record updated<br>";
    } else {
        $message = print_r($dbo->errorInfo());
        $message .= 'database error...';
        $status = 'Failed';
    }
} else {
    
}// end of if else if status is success 
$a1 = array('userid' => $userid, 'oldnumber' => $oldnumber, 'relation' => $relation, 'pname' => $person, 'phonenumber' => $number, 'speeddial' => $speeddial, 'startdate' => $start, 'enddate' => $end, 'calltype' => $calltype);
$a1 = array('data' => $a1, 'value' => array("status" => "$status", "message" => "$message"));
echo json_encode($a1);
