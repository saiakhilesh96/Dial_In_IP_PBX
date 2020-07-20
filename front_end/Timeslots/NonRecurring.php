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
        <title>NonRecurring Timings</title>
        <link rel="stylesheet" href="../StyleSheets/Recharge.css" type="text/css" media="all">
        <link rel="stylesheet" type="text/css" href="../StyleSheets/bootstrap.css"/>
        <link rel=" stylesheet" href="../StyleSheets/css/font-awesome.min.css">
        <?php include_once '../Timeslots/Openmenu.php'; ?>
        <script type="text/javascript" src="../JSfiles/jquery.min.js"></script>
        <script type="text/javascript" src="../JSfiles/bootstrap.min.js"></script>
        <link href="../StyleSheets/infragistics.theme.css" rel="stylesheet" />
        <link href="../StyleSheets/infragistics.css" rel="stylesheet" />
        <script src="../JSfiles/jquery-ui.min.js"></script>
        <script src="../JSfiles/infragistics.core.js"></script>
        <!--<script type="text/javascript" src="../JSfiles/Security.js"></script>-->
        <script src="../JSfiles/infragistics.lob.js"></script>
        <script type="text/javascript" src="../JSfiles/non_recurring_script.js"></script>
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
                    dateFormat: 'dd-mm-yy'
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
    </head>
    <body>
        <header class="headercontrol">
            <h2 class="textcontrol">NonRecurring Timeslots</h2>
            <a href="../Administrator.php" class="homebutton btn btn-info" ><img src="../IMG/home.png" width="30px" height="30px"></a>
            <!--            <div align="center">  
                            <button name="create_excel" id="create_excel" class="btn btn-info">Export</button>  
                        </div>-->
        </header>
        <div class="container">
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
        <!-- Modal - Add New Non Recurring Time slot -->
        <div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" align="center" id="myModalLabel">Add New Time Slot</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label style="margin-left: 0%;margin-top: -1%;">Slot Name:</label>
                            <input type="text" id="slot_name" placeholder="Slot Name" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label for="start_time">Starting Date</label>
                            <input type="text" id="start_date" placeholder="Starting date" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label for="start_time">Ending Date</label>
                            <input type="text" id="end_date" placeholder="Ending Date" class="form-control"/>
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
                        <button type="button" class="btn btn-primary" onclick='erase_details()' data-dismiss="modal">Cancel</button>
                        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>-->
                        <button type="button" class="btn btn-primary" onclick="addNonRecurringSlot()">Add Slot</button>
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
                        <button type="button" class="btn btn-primary" onclick="UpdateNonRecurringSlot()">Update</button>
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