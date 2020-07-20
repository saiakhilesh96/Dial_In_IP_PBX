<html>
    <head>
        <title>Logout</title>
    </head>
    <?php
    include_once './Controller.php';
        $bool= $_SESSION['bool'];
        $bool= 0;
        session_destroy();
        header('Location:ATSLogin.php');
    ?>
</html>