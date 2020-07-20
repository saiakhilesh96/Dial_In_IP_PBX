<!DOCTYPE html>
<?php
include_once '../Controller.php';
if ($_SESSION['bool'] != 1) {
    header('Location:../ATSLogin.php');
    exit();
}
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Users</title>
        <link rel="stylesheet" href="../StyleSheets/Recharge.css" type="text/css" media="all">
        <link rel="stylesheet" type="text/css" href="../StyleSheets/bootstrap.css"/>
        <script type="text/javascript" src="../JSfiles/jquery.min.js"></script>
        <script type="text/javascript" src="../JSfiles/bootstrap.min.js"></script>
        <link href="../StyleSheets/infragistics.theme.css" rel="stylesheet" />
        <link href="../StyleSheets/infragistics.css" rel="stylesheet" />
        <script src="../JSfiles/jquery-ui.min.js"></script>
        <script src="../JSfiles/infragistics.core.js"></script>
        <!--<script type="text/javascript" src="../JSfiles/Security.js"></script>-->
        <script src="../JSfiles/infragistics.lob.js"></script>
        <script type="text/javascript" src="../JSfiles/users.js"></script>
        <style>
            .lookandfeel{
                text-align: center;
                color: #337ab7;
            }
            .dropdown {
                position: relative;
                width: 200px;
            }
            .dropdown select
            {
                width: 100%;
            }
            .dropdown > * {
                box-sizing: border-box;
                height: 2em;
            }
            .dropdown select {
            }
            .dropdown input {
                position: absolute;
                width: calc(100% - 20px);
            }
        </style>
    </head>
    <body>
        <header class="headercontrol">
            <h2 class="textcontrol">USERS</h2>
            <a href="../Administrator.php" class="homebutton btn btn-info" ><img src="../IMG/home.png" width="30px" height="30px"></a>
            <!--            <div align="center">  
                            <button name="create_excel" id="create_excel" class="btn btn-info">Export</button>  
                        </div>-->
        </header>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">New User</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <br>
                    <!--<h3>Records:</h3>-->
                    <div class="records_content"></div>
                </div>
            </div>
        </div>
        <!-- /Content Section -->
        <!-- Bootstrap Modals -->
        <!-- Modal - Add New User -->
        <div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add New User</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <!--<label for="start_time">Starting Date</label>-->
                            <input type="text" id="user_id" placeholder="User ID" class="form-control" onkeypress="return isNumberOnly(event)"/>
                        </div>
                        <div class="form-group">
                            <!--<label for="start_time">Starting Date</label>-->
                            <input type="text" id="user_name" placeholder="User Name" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <!--<label for="start_time">Starting Date</label>-->
                            <input type="text" id="batch" placeholder="Batch" class="form-control" onkeypress="return isNumberOnly(event)"/>
                        </div>
                        <div class="form-group">
                            <!--<label for="start_time">Starting Date</label>-->
                            <input type="text" id="room" placeholder="Room Number" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <!--<label style="margin-left: 0%;margin-top: -1%;">User Group:</label>-->
                            <div class="dropdown">
                                <!--<input type="text" id="group_name" placeholder="User Group"/>-->
                                <select name="usergroup" id="usergroup" onchange="this.previousElementSibling.value = this.value; this.previousElementSibling.focus()">
                                <!--<select name="slotname" id="slot_name">-->
                                    <?php
//                                    include('./db_connection.php');
                                    include_once '../DBconnection.php';
                                    $group_name = "SELECT ug_id,ug_name FROM USER_GROUP";
                                    $group_nameresult = mysqli_query($conn, $group_name);
                                    echo "<option class='lookandfeel' hidden= true> User Group </option>";
                                    while ($row = mysqli_fetch_array($group_nameresult)) {
                                        //echo "<option class='lookandfeel' value=" . $row['ts_name'] . ">" . $row['ts_name'] . "</option>";
                                        echo "<option class='lookandfeel' value=" . $row['ug_id'] . ">" . $row['ug_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <!--<label style="margin-left: 0%;margin-top: -1%;">Course:</label>-->
                            <div class="dropdown">
                                <!--<input type="text" id="group_name" placeholder="User Group"/>-->
                                <select name="course" id="course" onchange="this.previousElementSibling.value = this.value; this.previousElementSibling.focus()">
                                <!--<select name="slotname" id="slot_name">-->
                                    <?php
                                    include_once '../DBconnection.php';
                                    $course_name = "SELECT course_title,course_id FROM COURSE";
                                    $course_nameresult = mysqli_query($conn, $course_name);
                                    echo "<option class='lookandfeel' hidden= true> Course </option>";
                                    while ($row = mysqli_fetch_array($course_nameresult)) {
                                        //echo "<option class='lookandfeel' value=" . $row['ts_name'] . ">" . $row['ts_name'] . "</option>";
                                        echo "<option class='lookandfeel' value=" . $row['course_id'] . ">" . $row['course_title'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <!--<label for="update_day" style="margin-left: 0%;margin-top: -1%;">Day of the week:</label>-->
                            <div class="dropdown">
                                <!--<input type="text" id="weekday" placeholder="Day of the week"/>-->
                                <select name="usertype" id="usertype">
                                    <option class='lookandfeel' hidden="true">Type of User</option>
                                    <option class='lookandfeel' value=1>ADMIN</option>
                                    <option class='lookandfeel' value=2>STAFF</option>
                                    <option class='lookandfeel' value=3>STUDENT</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick='erase_user_details()' data-dismiss="modal">Cancel</button>
                        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>-->
                        <button type="button" class="btn btn-primary" onclick="addUser()">Add User</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- // Modal -->

        <!-- Modal - Update existing user (yet to be done) -->
        <div class="modal fade" id="update_user_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Update Time slot</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="update_slot_name">Slot Name</label>
                            <input type="text" id="update_slot_name" placeholder="Slot Name" class="form-control"/>
                        </div>

                        <div class="form-group">
                            <label for="update_start_date">Start Date</label>
                            <input type="text" id="update_start_date" placeholder="Start Date" class="form-control"/>
                        </div>



                        <div class="form-group">
                            <label for="update_start">Start Time</label>
                            <input type="text" id="update_start_time" placeholder="Start Time" class="form-control"/>
                        </div>

                        <div class="form-group">
                            <label for="update_end_date">End Date</label>
                            <input type="text" id="update_end_date" placeholder="End Date" class="form-control"/>
                        </div>

                        <div class="form-group">
                            <label for="update_end">End Time</label>
                            <input type="text" id="update_end_time" placeholder="End Time" class="form-control"/>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="UpdateUser()">Update</button>
                        <input type="hidden" id="hidden_slot_id">
                        <input type="hidden" id="hidden_start_date">
                        <input type="hidden" id="hidden_start_time">
                        <input type="hidden" id="hidden_end_date">
                        <input type="hidden" id="hidden_end_time">
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>