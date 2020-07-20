<?php
include_once './Controller.php';
?>
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet"  href="StyleSheets/StyleSheet.css">
        <script type="text/javascript" src='JSfiles/Security.js' ></script>
    </head>
    <body>
        <div class="content" >
            <div id="wrapper">
                <div id="login_form">
                    <h3>Welcome</h3>
                    <form method="post" name='loginform' action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <span style="color: red"><?php echo $errormsg; ?></span>
                        <input type="text" name="username" id="username" placeholder="Username">
                        <br>
                        <input type="password" name="password" id="password" placeholder="Password">
                        <br>
                        <input type="submit" name="submit" value="LOGIN"><br>
                        <!--<a href="#"><label class="forgotpass">Forgot Password?</label></a>
                        Need to write code for forgot password-->
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
