<?php
include_once './TeacherHeader.php';
include_once './Controller.php';
if ($_SESSION['bool'] != 1) {
    header('Location:ATSLogin.php');
    exit();
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Teacher</title>
        <script type="text/javascript" src='JSfiles/Security.js' ></script>
        <style>
            .styling{
                color: #ffffff;
            }
        </style>
    </head>
    <body>
        <div id="profile">
            <b id="welcome">Welcome : <i><?php echo $_SESSION['login_user']; ?></i></b>
            <!--<b id="logout"><a name="logout" href="logout.php">Log Out</a></b>
            <b id="logout"><a name="logout" href="Student.php">Student</a></b>-->
        </div>
        <?php
        echo "<h1 class= 'styling'>Welcome Teacher</h1>";
        // put your code here
        ?>
    </body>
</html>
