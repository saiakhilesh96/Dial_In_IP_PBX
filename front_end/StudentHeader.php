<?php
include_once './Controller.php';
if ($_SESSION['bool'] != 1) {
    header('Location:ATSLogin.php');
    exit();
}
?>
<html>
    <head>
        <title>SaiTeleCommService</title>
        <link rel="stylesheet"  href="StyleSheets/Mystyle.css" type="text/css" media="all">
        <link rel="stylesheet"  href="StyleSheets/dropdown.css" type="text/css" media="all">
        <link rel="stylesheet" href="StyleSheets/PopUploadfiles.css">
        <script type="text/javascript" src='JSfiles/Security.js' ></script>
        <link rel="stylesheet" href="StyleSheets/Confirmarion_css.css">
        <script src='JSfiles/jquery.min.js'></script>
        <script src="JSfiles/JSfor_confirmation.js"></script>
    </head>
    <body>
        <header  class="site-header">
            <div class="site-header-main">
                <div class="site-branding">
                    <p class="site-title">SaiTeleCommService</p>
                </div>
                <!-- This for settings ------>
                <div class="dropdown">
                    <div class="site-header-menu">
                        <nav class="main-navigation" >
                            <ul id="menu-nav" class="primary-menu">
                                <li class='menu-item'><a class="dropbtn">Settings</a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="dropdown-content">
                        <a href="#ChangePass">Change Password</a>
                    </div>
                </div>
                <!-- Info of changing password -->
                <div id="ChangePass" class="overlay">
                    <div class="passpopup">
                        <h3 style="text-align: center;">Change Password</h3><hr>
                        <a class="close" href="#">&Chi;</a>
                        <div class="form-control">
                            <form method="post" name="changepassform" action="PassChange.php">
                                <label style="position: absolute;margin-left: 19%; margin-top: 5%;">Old Password:</label><input style="margin-left: 52%; margin-top: 6%;" type="password" name="oldpass" placeholder="Old Password"><br>
                                <label style="position: absolute;margin-left: 17%; margin-top: 2%;">New Password:</label><input style="position: absolute;margin-left: 48%; margin-top: 3%;" type="password" name="newpass" placeholder="New Password"><br>
                                <label style="position: absolute;margin-left: 1%; margin-top: 3%;">Confirm New Password:</label><input style="margin-left: 52%; margin-top: 4%;" type="password" name="confnewpass" placeholder="Confirm New Password"><br>
                                <input type="submit" name="submit" class="ChangePassBtn" value="Change"/>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="site-header-menu">
                    <nav class="main-navigation" >
                        <ul id="menu-nav" class="primary-menu">
                            <!--<li  class="menu-item"><a href="Uploadfilename.php">Import</a></li>
                            <li  class="menu-item"><a href="#">Settings</a></li>-->
                            <li class="menu-item"><a href="#">AboutUs</a></li>
                            <li  class="menu-item"><a class="cd-popup-trigger">Logout</a></li>
                        </ul>
                        <div class="cd-popup" role="alert">
                            <div class="cd-popup-container">
                                <p class="font">Are you sure you want to logout?</p>
                                <ul class="cd-buttons">
                                    <li><a href="logout.php">Yes</a></li>
                                    <li><a href="Student.php">No</a></li>
                                </ul>
                                <a href="Student.php" class="cd-popup-close img-replace">X</a>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </header>
    </body>
</html>
