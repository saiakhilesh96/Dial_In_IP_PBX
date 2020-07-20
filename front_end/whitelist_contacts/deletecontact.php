<!--AUM SRI SAI RAM-->
<!--The selected contact is deleted here-->
<?Php
    $userid=$_POST['userid'];
    $number=$_POST['phonenumber'];
    


    $message=''; // 
    $status='success';              // Set the flag  
    //sleep(2); // if you want any time delay to be added

    //// Data validation starts ///
    
    //// Data Validation ends /////
    if($status<>'Failed')
    {  // Update the table now

    require "config.php"; // MySQL connection string

    

    //pls help swami
    $count=$dbo->prepare("DELETE FROM WHITELIST_CONTACTS WHERE (user_id= :id) AND (phone_number=:num)");    //thank you swami
    $count->bindParam(":id",$userid);
    $count->bindParam(":num",$number);

    if($count->execute()){
    $no=$count->rowCount();
    $message= " $no Record deleted<br>";
    }else{
    $message = print_r($dbo->errorInfo());
    $message .= 'database error...';
    $status='Failed';
    }

    }else{

    }// end of if else if status is success 
    $a1 = array('userid'=>$userid,'oldnumber'=>$number);
    $a1 = array('data'=>$a1,'value'=>array("status"=>"$status","message"=>"$message"));
    echo json_encode($a1); 
?>