<?php
include_once './Controller.php';
if ($_SESSION['bool'] != 1) {
    header('Location:ATSLogin.php');
    exit();
}
?>                         
<html>
    <head>
        <title>Dial-In</title>
        <link rel="stylesheet"  href="StyleSheets/Mystyle.css" type="text/css" media="all">
        <link rel="stylesheet"  href="StyleSheets/dropdown.css" type="text/css" media="all">
        <link rel="stylesheet" href="StyleSheets/PopUploadfiles.css">
        <!--<link rel="stylesheet" href="StyleSheets/bootstrap.min.css">-->
        <script type="text/javascript" src='JSfiles/Security.js' ></script>
        <link rel="stylesheet" href="StyleSheets/Confirmarion_css.css">
        <script src='JSfiles/jquery.min.js'></script>
        <script src="JSfiles/JSfor_confirmation.js"></script>
    </head>
    <body>
        <header  class="site-header">
            <div class="site-header-main">
                <div class="site-branding">
                    <p class="site-title">Dial-In</p>
                </div>
                <div class="dropdown">
                    <div class="site-header-menu">
                        <nav class="main-navigation" >
                            <ul id="menu-nav" class="primary-menu">
                                <li class='menu-item'><a  class="dropbtn">Import<span style="margin-left: 5px;">&triangledown;</span></a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="dropdown-content">
                        <a href="#Course">Course</a>
                        <a href="#Device">Device</a>
                        <a href="#Plan">Plan</a>
                        <a href="#Timesolt">Time Slot</a>
                        <a href="#UserGroup">User Group</a>
                        <a href="#Users">Users</a>
                        <a href="#Contacts">Contacts</a>
                    </div>
                </div>
                <!--For the Course Upload-->
                <div id="Course" class="overlay">
                    <div class="popup">
                        <h2>Course Data Upload</h2>
                        <a class="close" href="#">&Chi;</a>
                        <div class="form-control">
                            <form class='arrange' enctype="multipart/form-data" action="DBforExcel_Models/CourseModule.php" method="POST">
                                <label>Select File:</label>
                                <!--<input type="text" class="size" name="filepath" placeholder="Ex:- /home/imca5/Desktop/Excel_Formats/">-->
                                <input type="hidden" name="MAX_FILE_SIZE" value="50000" />
                                <input type="file" name="filename">
                                <input type="submit" class='uploadbtn' value="Upload">
                            </form>
                            <p style="margin-left: 25%;">The spreadsheet must look like this</p>
                            <img style="margin-left: 25%;" src="IMG/Course.png" width="40%" height="20%">
                        </div>
                    </div>
                </div>
                <!--For the Device Upload-->
                <div id="Device" class="overlay">
                    <div class="popup">
                        <h2>Device Data Upload</h2>
                        <a class="close" href="#">&Chi;</a>
                        <div class="form-control">
                            <form method="post" class='arrange' enctype="multipart/form-data" action="DBforExcel_Models/DeviceModule.php">
                                <label>Select File:</label>
                                <!--<input type="text" class="size" name="filepath" placeholder="Ex:- /home/imca5/Desktop/Excel_Formats/">-->
                                <input type="file" id="filepath" name="filename">
                                <input type="submit" class='uploadbtn' value="Upload">
                            </form>
<!--                            <p>Give only the path of the excel sheet in text field don't give excel sheet name.
                            Follow the example from input field.</p>-->
                        </div>
                    </div>
                </div>
                <!--For the Plan Upload-->
                <div id="Plan" class="overlay">
                    <div class="popup">
                        <h2>Plansubscription Data Upload</h2>
                        <a class="close" href="#">&Chi;</a>
                        <div class="form-control">
                            <form method="post" class='arrange' enctype="multipart/form-data" action="DBforExcel_Models/PlanModule.php">
                                <label>Select File:</label>
                                <!--<input type="text" class="size" name="filepath" placeholder="Ex:- /home/imca5/Desktop/Excel_Formats/">-->
                                <input type="file" id="filepath" name="filename">
                                <input type="submit" class='uploadbtn' value="Upload">
                            </form>
<!--                            <p>Give only the path of the excel sheet in text field don't give excel sheet name.
                                Follow the example from input field.</p>-->
                        </div>
                    </div>
                </div>
                <!--For the Timesolt Upload-->
                <div id="Timesolt" class="overlay">
                    <div class="popup">
                        <h2>TimeSlot Data Upload</h2>
                        <a class="close" href="#">&Chi;</a>
                        <div class="form-control">
                            <form method="post" class='arrange' enctype="multipart/form-data" action="DBforExcel_Models/TimesoltModule.php">
                                <label>Select File:</label>
                                <!--<input type="text" class="size" name="filepath" placeholder="Ex:- /home/imca5/Desktop/Excel_Formats/">-->
                                <input type="file" id="filepath" name="filename">
                                <input type="submit" class='uploadbtn' value="Upload">
                            </form>
<!--                            <p>Give only the path of the excel sheet in text field don't give excel sheet name.
                                Follow the example from input field.</p>-->
                        </div>
                    </div>
                </div>
                <!--For the UserGroup Upload-->
                <div id="UserGroup" class="overlay">
                    <div class="popup">
                        <h2> UserGroup Data Upload</h2>
                        <a class="close" href="#">&Chi;</a>
                        <div class="form-control">
                            <form method="post" class='arrange' enctype="multipart/form-data" action="DBforExcel_Models/UserGroupModule.php">
                                <label>Select File:</label>
                                <!--<input type="text" class="size" name="filepath" placeholder="Ex:- /home/imca5/Desktop/Excel_Formats/">-->
                                <input type="file" id="filepath" name="filename">
                                <input type="submit" class='uploadbtn' value="Upload">
                            </form>
<!--                            <p>Give only the path of the excel sheet in text field don't give excel sheet name.
                                Follow the example from input field.</p>-->
                        </div>
                    </div>
                </div>
                <!--For the Users Upload-->
                <div id="Users" class="overlay">
                    <div class="popup">
                        <h2>Users Data Upload</h2>
                        <a class="close" href="#">&Chi;</a>
                        <div class="form-control">
                            <form method="post" class='arrange' enctype="multipart/form-data" action="DBforExcel_Models/UsersModule.php">
                                <label>Select File:</label>
                                <!--<input type="text" class="size" name="filepath" placeholder="Ex:- /home/imca5/Desktop/Excel_Formats/">-->
                                <input type="file" id="filepath" name="filename">
                                <input type="submit" class='uploadbtn' value="Upload">
                            </form>
<!--                            <p>Give only the path of the excel sheet in text field don't give excel sheet name.
                                Follow the example from input field.</p>-->
                        </div>
                    </div>
                </div>
                <!--For the WhiteList Contacts Upload-->
                <div id="Contacts" class="overlay">
                    <div class="popup">
                        <h2>Contacts Data Upload</h2>
                        <a class="close" href="#">&Chi;</a>
                        <div class="form-control">
                            <form method="post" class='arrange' enctype="multipart/form-data" action="DBforExcel_Models/ContactsModule.php">
                                <label>Select File:</label>
                                <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                                <!--<input type="text" class="size" name="filepath" placeholder="Ex:- /home/imca5/Desktop/Excel_Formats/">-->
                                <input type="file" id="filepath" name="filename">
                                <input type="submit" class='uploadbtn' value="Upload">
                            </form>
<!--                            <p>Give only the path of the excel sheet in text field don't give excel sheet name.
                                Follow the example from input field.</p>-->
                        </div>
                    </div>
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
                            <li class="menu-item"><a class="dropdown-content1" href="#AboutUs">AboutUs</a></li>
                            <li class="menu-item"><a class="cd-popup-trigger">Logout</a></li>
                        </ul>
                        <div class="cd-popup" role="alert">
                            <div class="cd-popup-container">
                                <p class="font">Are you sure you want to logout?</p>
                                <ul class="cd-buttons">
                                    <li><a href="logout.php">Yes</a></li>
                                    <li><a href="Administrator.php">No</a></li>
                                </ul>
                                <a href="" class="cd-popup-close img-replace"><span class="fa fa-window-close"></span></a>
                            </div>
                        </div>
                    </nav>
                </div>
                <div id="AboutUs" class="overlay1">
                    <div class="popup">
                        <a style="position: absolute;margin-top: -2%;margin-left: 94.5%;color: black; font-size: 25px;" href="#">X</a>
                        <div>
                            <img src="IMG/swami.jpg" width="20%" height="20%">
                            <img src="IMG/swami.jpg" width="20%" height="20%">
                            <p>Guides</p>
                        </div>
                        <div>
                            <img src="IMG/1.jpg" width="20%" height="20%">
                            <img src="IMG/1.jpg" width="20%" height="20%">
                            <!--<img src="IMG/swami.jpg" width="40%" height="40%">-->
                            <p>Learners</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </body>
</html>
