<?php
include_once './Controller.php';
if ($_SESSION['bool'] != 1) {
    header('Location:ATSLogin.php');
    exit();
}
include_once './AdminHeader.php';
include_once './Openmenu.php';
?>
<html>
    <head>
        <title>Administrator</title>
        <script type="text/javascript" src='JSfiles/Security.js' ></script>
        <link rel="stylesheet" href="StyleSheets/snackbar.css">
        <style>
            /*{
                font-family: "Sawasdee";
            }*/
            .styling{
                font-family: Tibetan Machine Uni;
                margin-left: 40%;
                color: #ffffff;
            }
        </style>
    </head>
    <body onload="fun()">
        <div id="snackbar">Welcome Administrator</div>
        <div>
            <!--<h3 class= 'styling'>Welcome Administrator</h3>-->
            <b class= 'styling'>Welcome : <i><?php $name= $_SESSION['login_user']; echo $name;?></i></b>
            <!--<b id="welcome">Password : <i><?php //echo $_SESSION['pass']; ?></i></b>
            <b id="welcome">DBPassword : <i><?php //echo $_SESSION['dbpass']; ?></i></b>
            <b id="logout"><a name="logout" href="logout.php">Log Out</a></b>-->
        </div>
    </body>
    <?php
    //echo "<h1 class= 'styling'>Welcome Administrator</h1>";
    ?>
</html>
