<!DOCTYPE html>
<?php
 include_once '../Controller.php';
        if ($_SESSION['bool'] != 1) {
            header('Location:../ATSLogin.php');
            exit();
        }  
//        include_once '../Plan/Openmenu.php';
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Plans</title>
        <?php
            include_once '../Plan/Openmenu.php';
        ?>
        <link rel="stylesheet" href="../StyleSheets/Plancss.css" type="text/css" media="all">
        <link rel="stylesheet" type="text/css" href="../StyleSheets/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="../StyleSheets/Menustyle_1.css"/>
        <script type="text/javascript" src="../JSfiles/jquery.min.js"></script>
        <link rel=" stylesheet" href="../StyleSheets/css/font-awesome.min.css">
        <script type="text/javascript" src="../JSfiles/bootstrap.min.js"></script>
        <link href="../StyleSheets/infragistics.theme.css" rel="stylesheet" />
        <link href="../StyleSheets/infragistics.css" rel="stylesheet" />
        <script src="../JSfiles/jquery-ui.min.js"></script>
        <script src="../JSfiles/infragistics.core.js"></script>
        <script src="../JSfiles/infragistics.lob.js"></script>
        <script type="text/javascript" src='../JSfiles/Security.js' ></script>
        <script type="text/javascript" src="../JSfiles/planscript.js"></script>
        
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
    </head>
    <body>
        
        <header class="headercontrol">
            <h2 class="textcontrol">Create Plan</h2>
            <!--<a href="Plan.php" ><input class="moneybtn btn btn-info"  type="button" value="Existing Plans"></a>-->
            <a href="../Plan/PlanSubscribe.php" title="Go back to previous Page" class="backbutton btn btn-info fa fa-arrow-left" >Back</a>
            <!--            <div align="center">  
                            <button name="create_excel" id="create_excel" class="btn btn-info">Export</button>  
                        </div>-->
            <img style="margin-top: -5%; margin-left: 5%; position: absolute;" src="../IMG/dialin_logo.png" width="9%" height="8%">
            
        </header>
        <div class="container">
            <!--            <div class="row">
                            <div class="col-md-12">
                                <h1>Plan Subscription</h1>
                            </div>
                        </div>-->
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New Plan</button>
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
        <!-- Modal - Add New Record/User -->
        <div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 style="text-align: center;" class="modal-title" id="myModalLabel">Add New Plan</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label style="margin-left: 0%;margin-top: -1%;">Plan Name:</label>
                            <div class="dropdown">
                                <input type="text" id="plan_name" placeholder="Plan Name"/>
                                <select name="planname" onchange="this.previousElementSibling.value = this.value; this.previousElementSibling.focus()">
                                    <?php
//                                    include('./db_connection.php');
                                    include_once '../DBconnection.php';
                                    $plan_name = "SELECT plan_name FROM PLAN_MASTER";
                                    $plan_nameresult = mysqli_query($conn, $plan_name);
                                    while ($row = mysqli_fetch_array($plan_nameresult)) {
                                        echo "<option class='lookandfeel' value=" . $row['plan_name'] . ">" . $row['plan_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label style="margin-left: 0%;margin-top: -1%;">CallType Name:</label>
                            <div class="dropdown">
                                <input type="text" id="calltype_name" placeholder="CallType Name"/>
                                <select name="calltypename" onchange="this.previousElementSibling.value = this.value; this.previousElementSibling.focus()">
                                    <?php
//                                    include('./db_connection.php');
                                    include_once '../DBconnection.php';
                                    $ct_name = "SELECT calltype_name FROM CALL_TYPE";
                                    $Rct_name = mysqli_query($conn, $ct_name);
                                    while ($row = mysqli_fetch_array($Rct_name)) {
                                        echo "<option class='lookandfeel' value=" . $row['calltype_name'] . ">" . $row['calltype_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div style="margin-left: 50%;margin-top: -15%" class="form-group">
                            <label>O_Prefix:</label>
                            <div class="dropdown">
                                <input type="text" id="o_prefix" placeholder="O_Prefix"/>
                                <select name="o_prefix" onchange="this.previousElementSibling.value = this.value; this.previousElementSibling.focus()">
                                    <?php
//                                    include('./db_connection.php');
                                    include_once '../DBconnection.php';
                                    $o_prefix = "SELECT o_prefix FROM CALL_TYPE";
                                    $Ro_prefix = mysqli_query($conn, $o_prefix);
                                    while ($row = mysqli_fetch_array($Ro_prefix)) {
                                        echo "<option class='lookandfeel' value=" . $row['o_prefix'] . ">" . $row['o_prefix'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div style="margin-left: 50%;margin-top: -1%" class="form-group">
                            <label>I_Prefix:</label>
                            <div class="dropdown">
                                <input type="text" id="i_prefix" placeholder="I_Prefix"/>
                                <select name="i_prefix" onchange="this.previousElementSibling.value = this.value; this.previousElementSibling.focus()">
                                    <?php
//                                    include('./db_connection.php');
                                    include_once '../DBconnection.php';
                                    $i_prefix = "SELECT i_prefix FROM CALL_TYPE";
                                    $Ri_prefix = mysqli_query($conn, $i_prefix);
                                    while ($row = mysqli_fetch_array($Ri_prefix)) {
                                        echo "<option class='lookandfeel' value=" . $row['i_prefix'] . ">" . $row['i_prefix'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="charge_paise">Charge Paise</label>
                            <input type="text" id="charge_paise" placeholder="Charge Paise" class="form-control" onkeypress="return isNumberOnly(event)" />
                        </div>

                        <div class="form-group">
                            <label for="duration">Duration/sec</label>
                            <input type="text" id="duration_sec" placeholder="Duration/sec" class="form-control" onkeypress="return isNumberOnly(event)"/>
                        </div>

                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="text" id="start_date" placeholder="Start Date" class="form-control" onkeypress="return isNumberOnly(event)"/>
                        </div>

                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="text" id="end_date" placeholder="End Date" class="form-control" onkeypress="return isNumberOnly(event)"/>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="addRecord()">Add Record</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- // Modal -->

        <!-- Modal - Update User details -->
        <div class="modal fade" id="update_user_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 style="text-align: center;" class="modal-title" id="myModalLabel">Update Plan</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Plan Name</label>
                            <input type="text" id="update_plan_name" placeholder="Plan Name" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label>Calltype Name</label>
                            <input type="text" id="update_calltype_name" placeholder="Calltype Name" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label>O_prefix</label>
                            <input type="text" id="update_o_prefix" placeholder="O Prefix" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label>I_prefix</label>
                            <input type="text" id="update_i_prefix" placeholder="I Prefix" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label>Charge Paise</label>
                            <input type="text" id="update_charge_paise" placeholder="Charge Paise" class="form-control" onkeypress="return isNumberOnly(event)"/>
                        </div>

                        <div class="form-group">
                            <label>Duration Sec</label>
                            <input type="text" id="update_duration_sec" placeholder="Duration sec" class="form-control" onkeypress="return isNumberOnly(event)"/>
                        </div>

                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="text" id="update_start_date" placeholder="Start Date" class="form-control" onkeypress="return isNumberOnly(event)"/>
                        </div>

                        <div class="form-group">
                            <label>End Date</label>
                            <input type="text" id="update_end_date" placeholder="End Date" class="form-control" onkeypress="return isNumberOnly(event)"/>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="UpdateUserDetails()">Save Changes</button>
                        <input type="hidden" id="hidden_plan_id">
                        <input type="hidden" id="hidden_calltype_id">
                        <input type="hidden" id="hidden_startdate_id">
                        <input type="hidden" id="hidden_enddate_id">
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>