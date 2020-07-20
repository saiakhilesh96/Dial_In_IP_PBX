<?php
include_once './StudentHeader.php';
include_once './Controller.php';
if ($_SESSION['bool'] != 1) {
    header('Location:ATSLogin.php');
    exit();
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Student</title>
        <script type="text/javascript" src='JSfiles/Security.js' ></script>
        <style>
            .styling{
                font-family: Tibetan Machine Uni;
                margin-left: 45%;
                color: #ffffff;
            }
        </style>
    </head>
    <body>
        <div>
            <h1 class= 'styling'>Welcome Student</h1>
            <!--<b style="color: white;">Welcome : <i><?php echo $_SESSION['login_user']; ?></i></b>-->
            <!--<b id="logout"><a name="logout" href="logout.php">Log Out</a></b>-->
        </div>
    </body>
</html>
