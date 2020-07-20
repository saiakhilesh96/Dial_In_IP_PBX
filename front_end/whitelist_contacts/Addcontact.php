<!DOCTYPE html>
<!--
***************************************************AUM SRI SAI RAM*****************************
In this page the details we entered for adding a new contact will be inserted into the database
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Add Contact</title>
    </head>
    <body>
        <?php
        // put your code here
        include_once './config.php';

        $userid = $_POST['user_id'];
        $phone = $_POST['phone'];
        $relation = $_POST['relation'];
        $name = $_POST['name'];
        $digit = $_POST['speeddial'];
        $calltypename = $_POST['cType'];
        $begin = $_POST['start'];
        $finish = $_POST['end'];

        $relation = strtoupper($relation);
        $name = strtoupper($name);
//        echo "<script>alert('" . $userid . $phone . $relation . $name . $digit . $calltypename . $begin . $finish . "')</script>";
        //echo "<script>alert('".$calltypename."')</script>";
        $status = 'success';              // Set the flag  
        if ($calltypename === "Call Type") {
//            echo "calltype empty";
            $status = 'Failed';
        }
        $ct_qry = "SELECT calltype_id FROM CALL_TYPE WHERE calltype_name= '" . $calltypename . "'";
        $pre_ct_qry = $dbo->prepare($ct_qry);
        if ($pre_ct_qry->execute()) {
            $calltypeid = $pre_ct_qry->fetchColumn();
        } else {
            $message = print_r($dbo->errorInfo());
            //$message = $dbo->errorInfo() . 'database error...';
//            echo "<script>alert('" . $message . "')</script>";
            $status = 'Failed';
        }

        $userqry = "SELECT user_id FROM USERS WHERE user_id= " . $userid;
//        echo $userqry;
        $u = $dbo->prepare($userqry);
        if ($u->execute()) {
            $usr = $u->fetchColumn();
            //echo "the userid :" . $usr . "---";
            if ($usr == '') {
                //userid is not existing in the database
//                echo "there is no user";
                $status = 'Failed';
                $msg = urlencode("Invalid userid");
                header("Location:Contacts.php?msg=" . $msg . "#newcontact");
            } else { //user is existing lets check for the duplicate entry of the contact
                $dupqry = "SELECT phone_number from WHITELIST_CONTACTS WHERE user_id= " . $userid . " AND phone_number= '" . $phone . "'";
                $p = $dbo->prepare($dupqry);
                if ($p->execute()) {
                    $phn = $p->fetchColumn();
                    if ($phn == '') { //new entrry
                        //now we have to check the order of the dates (startdate should be before end date)
                        $sd = new DateTime($begin);
                        $s = $sd->format('d/m/Y');
                        $ed = new DateTime($finish);
                        $e = $ed->format('d/m/Y');
                        $diff = $ed->diff($sd);
                        if (($diff->invert == 1)) {
//                            echo "<script>alert('end date is after startdate')</script>";
                        } else {
//                            echo "<script>alert('end date is before startdate')</script>";
                            $status = 'Failed';
                            $msg = urlencode("End Date can not be before Start Date");
                            header("Location:Contacts.php?msg=" . $msg . "#newcontact");
                        }
                    } else {
                        $status = 'Failed';
                        $msg = urlencode("Duplicate Contact");
                        header("Location:Contacts.php?msg=" . $msg . "#newcontact");
                    }
                }
            }
        }
        if ($status <> 'Failed') {
            $insert_contact = "INSERT INTO WHITELIST_CONTACTS VALUES(" . $userid . ",'" . $relation . "','" . $name . "','" . $phone . "'," . $calltypeid . "," . $digit . ",str_to_date('" . $begin . "','%d-%m-%Y'),str_to_date('" . $finish . "','%d-%m-%Y'))";
            //echo $insert_contact;
            $insert = $dbo->prepare($insert_contact);
            if ($insert->execute()) {
//                echo "<script>alert('RECORD UPDATED SUCCESSFULLY')</script>";
                $msg = urlencode("Contact Successfully Added");
                header("Location:Contacts.php?msg=" . $msg . "#newcontact");
            } else {
                //echo "data base error ".$dbo->errorInfo();
                echo "<script>alert('sairam data base error in inserting record')</script>";
                print_r($dbo->errorInfo());
            }
        } else {
            
        }
        ?>
    </body>
</html>
