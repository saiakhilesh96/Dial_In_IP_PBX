<html>
    <head>
        <title>Validating</title>
    </head>
</html>
<?php
include_once 'DBconnection.php';
//include_once '../Controller.php';
//if ($_SESSION['bool'] != 1) {
//    header('Location:../ATSLogin.php');
//    exit();
//}
$userID = $_SESSION['login_user'];
$oldpass = $_POST['oldpass'];
$Encrypt_oldpass = md5($oldpass);
$newpass = $_POST['newpass'];
$Encrypt_newpass = md5($newpass);
$confirmpass = $_POST['confnewpass'];
$Encrypt_confirmpass = md5($confirmpass);
//echo "En-Old pass ".$Encrypt_oldpass."<br\n>";
//echo $Encrypt_newpass."<br\n>";
//echo $Encrypt_confirmpass."<br\n>";
if (isset($_POST['submit'])) {
    if (empty($_POST['oldpass']) || empty($_POST['newpass']) || empty($_POST['confnewpass'])) {
        print "<script>alert('Must enter all fields!!!')</script>";
    } else {
        if ($Encrypt_newpass !== $Encrypt_confirmpass) {
            print "<script>alert('New password and Confirm password are not same!!!')</script>";
        } else {
            if (preg_match("/^[admin]*$/", $userID)) {
                $check_oldpass = "select password from MANAGER_LOGIN where admin_name = '" . $userID . "';";
                $Rcheck_oldpass = mysqli_query($conn, $check_oldpass);
                $FRcheck_oldpass = mysqli_fetch_array($Rcheck_oldpass);
                //echo "checking ".$FRcheck_oldpass['password']."<br\n>";
                if ($FRcheck_oldpass['password'] === $Encrypt_oldpass) {
                    //print "<script>alert('Old password exist!!!')</script>";
                    $changepass = "update MANAGER_LOGIN set password = '" . $Encrypt_confirmpass . "' where admin_name= '" . $userID . "'";
                    if (mysqli_query($conn, $changepass)) {
                        print "<script>alert('Password is Successfully Updated')</script>";
                    } else {
                        echo "Error: " . $changepass . "<br>" . mysqli_error($conn);
                    }
                } else {
                    print "<script>alert('Old password does not exist!!!')</script>";
                }
            } else {
                $check_oldpass = "select user_pin from USERS where user_id = '" . $userID . "';";
                $Rcheck_oldpass = mysqli_query($conn, $check_oldpass);
                $FRcheck_oldpass = mysqli_fetch_array($Rcheck_oldpass);
                //echo "checking ".$FRcheck_oldpass['password']."<br\n>";
                if ($FRcheck_oldpass['user_pin'] === $Encrypt_oldpass) {
                    //print "<script>alert('Old password exist!!!')</script>";
                    $changepass = "update USERS set user_pin = '" . $Encrypt_confirmpass . "' where user_id= '" . $userID . "'";
                    if (mysqli_query($conn, $changepass)) {
                        print "<script>alert('Password is Successfully Updated')</script>";
                    } else {
                        echo "Error: " . $changepass . "<br>" . mysqli_error($conn);
                    }
                } else {
                    print "<script>alert('Old password does not exist!!!')</script>";
                }
                print "<script>alert('This is not Admin!!!')</script>";
            }
        }
    }
}