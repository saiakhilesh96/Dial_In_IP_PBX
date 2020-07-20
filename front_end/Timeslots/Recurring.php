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
        <title>Recurring Timings</title>
        
        <link rel="stylesheet" href="../StyleSheets/Recharge.css" type="text/css" media="all">
        <link rel=" stylesheet" href="../StyleSheets/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="../StyleSheets/bootstrap.css"/>
        <?php include_once '../Timeslots/Openmenu.php'; ?>
        <script type="text/javascript" src="../JSfiles/jquery.min.js"></script>
        <script type="text/javascript" src="../JSfiles/bootstrap.min.js"></script>
        <link href="../StyleSheets/infragistics.theme.css" rel="stylesheet" />
        <link href="../StyleSheets/infragistics.css" rel="stylesheet" />
        <script src="../JSfiles/jquery-ui.min.js"></script>
        <script src="../JSfiles/infragistics.core.js"></script>
        <script type="text/javascript" src="../JSfiles/Security.js"></script>
        <script src="../JSfiles/infragistics.lob.js"></script>
        
        <script type="text/javascript" src="../JSfiles/recurring_script.js"></script>
    </head>
        
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
        <script type="text/javascript">
        $(document).ready(function () {
            $.datepicker.setDefaults({
                dateFormat: 'yy-mm-dd'
            });
            $(function () {
                $("#start_date").datepicker();
                $("#end_date").datepicker();
            });
            $(function () {
                $("#update_start_date").datepicker();
                $("#update_end_date").datepicker();
            });
        });
    </script>
    <body>
        <header class="headercontrol">
            <h2 class="textcontrol">Recurring Timeslots</h2>
            <!--<a href="Plan.php" ><input class="moneybtn btn btn-info"  type="button" value="Existing Plans"></a>-->
            <a href="../Administrator.php" class="homebutton btn btn-info" ><img src="../IMG/home.png" width="30px" height="30px"></a>
            <img style="margin-top: -5%; margin-left: 5%; position: absolute;" src="../IMG/dialin_logo.png" width="9%" height="8%">
<!--            <div align="center">  
                <button name="create_excel" id="create_excel" class="btn btn-info">Export</button>  
            </div>-->

        </header>
        <div class="container">
<!--            <div class="row">
                <div class="col-md-12">
                    <h1>RECURRING TIMINGS</h1>
                </div>
            </div>-->
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">New TimeSlot</button>
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
        <!-- Modal - Add New Recurring Time slot -->
        <div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add New Time Slot</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label style="margin-left: 0%;margin-top: -1%;">Slot Name:</label>
                            <div class="dropdown">
                                <input type="text" id="slot_name" placeholder="Slot Name"/>
                                <select name="slotname" onchange="this.previousElementSibling.value = this.value; this.previousElementSibling.focus()">
                                <!--<select name="slotname" id="slot_name">-->
                                    <?php
//                                    include('./db_connection.php');
                                    include_once '../DBconnection.php';
                                    $slot_name = "SELECT ts_id,ts_name FROM TIME_SLOT WHERE ts_flag='1'";
                                    $slot_nameresult = mysqli_query($conn, $slot_name);
                                    while ($row = mysqli_fetch_array($slot_nameresult)) {
                                        //echo "<option class='lookandfeel' value=" . $row['ts_name'] . ">" . $row['ts_name'] . "</option>";
                                        echo "<option class='lookandfeel' value=" . $row['ts_name'] . ">" . $row['ts_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="update_day" style="margin-left: 0%;margin-top: -1%;">Day of the week:</label>
                            <div class="dropdown">
                                <!--<input type="text" id="weekday" placeholder="Day of the week"/>-->
                                <select name="weekday" id="weekday">
                                    <option class='look' hidden="true">Day of the Week</option>
                                    <option class='lookandfeel' value=0>SUN</option>
                                    <option class='lookandfeel' value=1>MON</option>
                                    <option class='lookandfeel' value=2>TUE</option>
                                    <option class='lookandfeel' value=3>WED</option>
                                    <option class='lookandfeel' value=4>THU</option>
                                    <option class='lookandfeel' value=5>FRI</option>
                                    <option class='lookandfeel' value=6>SAT</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="start_time">Starting Time</label>
                            <input type="text" id="start_time" placeholder="Starting time" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label for="end_time">Ending Time</label>
                            <input type="text" id="end_time" placeholder="Ending time" class="form-control"/>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="addRecurringSlot()">Add Slot</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- // Modal -->

        <!-- Modal - Update existing time slot -->
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
                            <label for="update_day">Day</label>
                            <!--<input type="text" id="update_day" placeholder="Day of the week" class="form-control"/>-->
                            <select name="day" id="update_day">
                                    <option class='look' hidden="true">Day of the Week</option>
                                    <option class='lookandfeel' value=0>SUN</option>
                                    <option class='lookandfeel' value=1>MON</option>
                                    <option class='lookandfeel' value=2>TUE</option>
                                    <option class='lookandfeel' value=3>WED</option>
                                    <option class='lookandfeel' value=4>THU</option>
                                    <option class='lookandfeel' value=5>FRI</option>
                                    <option class='lookandfeel' value=6>SAT</option>
                                </select>
                        </div>

                        <div class="form-group">
                            <label for="update_start">Start Time</label>
                            <input type="text" id="update_start_time" placeholder="Start Time" class="form-control"/>
                        </div>
                        
                        <div class="form-group">
                            <label for="update_end">End Time</label>
                            <input type="text" id="update_end_time" placeholder="End Time" class="form-control"/>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="UpdateRecurringSlot()">Update</button>
                        <input type="hidden" id="hidden_slot_id">
                        <input type="hidden" id="hidden_day">
                        <input type="hidden" id="hidden_start_time">
                        <input type="hidden" id="hidden_end_time">
                    </div>
                </div>
            </div>
        </div>
        
    </body>
    
</html>