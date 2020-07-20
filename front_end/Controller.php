<?php
include_once './DBconnection.php';
session_start();
$errormsg = "";
if (isset($_POST['submit'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $errormsg = "Must enter all fields";
    } else {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $password = md5($password);
        echo "pass " . $password;
        $bool = 1;
        //print '<script>alert("succussfully connected!!!")</script>';
        $retrieve = "select admin_name,password,access_level from MANAGER_LOGIN where admin_name= '" . $username . "' AND password= '" . $password . "'";
        $Query = mysqli_query($conn, $retrieve);
        $resultQ = mysqli_fetch_array($Query);
        $UsersQ = "select user_id,pin,access_level from USERS where user_id= '" . $username . "' AND pin= '" . $password . "'";
        $RuserQ = mysqli_query($conn, $UsersQ);
        $FRuserQ = mysqli_fetch_array($RuserQ);
        //echo "username from db ".$FRuserQ['username']."<br\n>";
        echo "password " . $resultQ['password'] . "depass " . $password . "<br>";
        //$rows = mysqli_num_rows($Query);
        //$_SESSION['dbpass']= $resultQ['password'];
        if (($resultQ['admin_name'] === $username || $FRuserQ['user_id'] === $username) && ($resultQ['password'] === $password || $FRuserQ['pin'] === $password)) {
            $_SESSION['bool'] = $bool;
            if ($resultQ['access_level'] == 1) {
                //$_SESSION['pass']= $password;
                $_SESSION['login_user'] = $username;
                header('Location:Administrator.php');
                exit();
            }
            if ($FRuserQ['access_level'] == 2) {
                $_SESSION['login_user'] = $username;
                header('Location:Teacher.php');
                exit();
            }
            if ($FRuserQ['access_level'] == 3) {
                $_SESSION['login_user'] = $username;
                header('Location:Student.php');
                exit();
            }
        } else {
            $errormsg = "Username or password do not match";
        }
        mysqli_close($conn);
    }
}
?>