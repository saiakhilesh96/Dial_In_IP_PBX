<!DOCTYPE html>
<?php
include_once '../Controller.php';
if ($_SESSION['bool'] != 1) {
    header('Location:../ATSLogin.php');
    exit();
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Device Allocation</title>
    </head>
        
    <link rel="stylesheet" href="../StyleSheets/Device_css.css" type="text/css" media="all">
    
    <link rel="stylesheet" href="../StyleSheets/bootstrap.min.css">
    
    <link rel=" stylesheet" href="../StyleSheets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../StyleSheets/bootstrap.css"/>
    <?php include_once '../Device/Openmenu.php'; ?>
    <!--<link rel="stylesheet" href="../StyleSheets/Menustyle_1.css" type="text/css" media="all">-->
    <!--<link rel="stylesheet" href="../StyleSheets/MoneyboxPopup.css" type="text/css" media="all">-->
    <script type="text/javascript" src="../JSfiles/jquery.min.js"></script>
    <script type="text/javascript" src="../JSfiles/bootstrap.min.js"></script>
    <script src="../JSfiles/jquery-ui.min.js"></script>
    <script type="text/javascript" src='../JSfiles/Security.js' ></script>
    <script type="text/javascript" src="../JSfiles/Devicescript.js"></script>

    <body>
        <header class="headercontrol">
            <h2 class="textcontrol">Device Allocation</h2>
            <a href="#sipDevice" data-toggle="modal" data-target="#sipDevice"><input class="filebtn btn btn-info"  type="button" value="Export Sip ID's"></a>
            <a href="../Administrator.php" class="homebutton btn btn-info" ><img src="../IMG/home.png" width="30px" height="30px"></a>
            
            <img style="margin-top: -5%; margin-left: 5%; position: absolute;" src="../IMG/dialin_logo.png" width="9%" height="8%">
        </header>
        <!--                <div id="sipDevice" class="overlay">
                            <div class="passpopup">
                                <h3 class="arr">Export Sip ID's to File</h3>
                                <a class="myclosebtn"  href="">&Chi;</a>
                                <form method="POST" name="check" enctype="multipart/form-data"  action="CreateSipDeviceID's.php">
                                    <span style="color: red;font-size: 12px;margin-top: 23%;position: absolute;"><?php
//                        if (isset($_GET['msg'])) {
//                            echo $_GET['msg'];
//                        };
        ?></span>
                                    <label class="arr">Read From SpreadSheet:</label>
                                    <input type="file" name="filename"/>
                                    <label class="arr">Write to a file:</label>
                                    <input type="file" name="filename1"/>
                                    <input type="submit" class='uploadbtn' value="Upload"/>
                                </form>
                            </div>
                        </div>-->
        <div class="modal fade" id="sipDevice" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 style="text-align: center;" class="modal-title" id="myModalLabel">Export Sip ID's to File</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" id="sipdevice">
                            <form method="POST" name="check" enctype="multipart/form-data"  action="CreateSipDeviceID's.php">
                                <span style="color: red;font-size: 12px;margin-top: 23%;position: absolute;"><?php
                                    if (isset($_GET['msg'])) {
                                        echo $_GET['msg'];
                                    };
                                    ?></span>
                                <label class="arr">Read From SpreadSheet:</label>
                                <input type="file" name="filename"/>
                                <label class="arr">Write to a file:</label>
                                <input type="file" name="filename1"/>
                                <!--<input type="submit" value="Upload"/>-->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Add New Device</button>
                    </div>
                </div>
            </div><br />
            <?php
            include_once '../DBconnection.php';
            $device = "select sip.sdg_id,sdg_desc,dg.sd_id,sd.sd_extension from SIP_DEVICE_GROUP as sip inner join DEVICE_GROUP as dg on (dg.sdg_id = sip.sdg_id) join SIP_DEVICES as sd on (dg.sd_id = sd.sd_id);";
            $Rdevice = mysqli_query($conn, $device);
            if ($Rdevice->num_rows > 0) {
                echo "<div class='container wrap'>";
                echo "<table class='table head'>";
                echo "<thead align='center'>";
                echo "<tbody>";
                echo "<tr>";
                echo "<td style= 'width:3em;text-align:center;'>Device Location</td>";
                echo "<td style= 'width:2em;text-align:center;'>Device ID</td>";
                echo "<td style= 'width:2em;text-align:center;'>Device Number</td>";
                echo "<td style= 'width:1em;text-align:center;'>Update</td>";
                echo "<td style= 'width:1em;text-align:center;'>Delete</td>";
                echo "</tr>";
                echo "</thead>";
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
                echo "<div class='container wrap inner_table' id= 'mytable'>";
                echo "<table class='table table-bordered'>";
                echo "<tbody align='center'>";
                while ($row = $Rdevice->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td style='width:3em;'>" . $row['sdg_desc'] . "</td>";
                    echo "<td style='width:2em;'>" . $row['sd_id'] . "</td>";
                    echo "<td style='width:2em;'>" . $row['sd_extension'] . "</td>";
                    echo "<td style='width:0.5em;'><button onclick= 'GetDeviceDetails(" . $row['sdg_id'] . "," . $row['sd_id'] . "," . $row['sd_extension'] . ")' class='btn btn-warning fa fa-edit'></button></td>";
                    echo "<td style='width:0.5em;'>"
                    . "<button onclick='DeleteDevice(" . $row['sdg_id'] . "," . $row['sd_id'] . "," . $row['sd_extension'] . ");' class='btn btn-danger fa fa-trash-o'></button>"
                    . "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
            } else {
                echo "<table class='table table-bordered putdown'>";
                echo "<tr>";
                echo "<td align= 'center'>NO DATA FOUND</td>";
                echo "</tr>";
                echo "</table>";
            }
            mysqli_close($conn);
            ?>
        </div>
        <div class="modal fade" id="add_new_record_modal" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 style="text-align: center;" class="modal-title" id="myModalLabel">Add New Device</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="location">Location of the Device</label><br />
                            <input  type="text" id="location" placeholder="Location" class="form-control" />
                        </div>

                        <div class="form-group">
                            <label for="deviceid">Device ID</label>
                            <input type="text" id="deviceid" placeholder="Device ID" class="form-control" onkeypress="return isNumberOnly(event)"/>
                        </div>

                        <div class="form-group">
                            <label for="devicenumber">Device Extension</label>
                            <input type="text" id="devicenumber" placeholder="Device Extension" class="form-control" onkeypress="return isNumberOnly(event)"/>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="addDevice()">Add</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="update_Device_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 style="text-align: center;" class="modal-title" id="myModalLabel">Update Device</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Location of the Device</label>
                            <input type="text" id="update_location"  class="form-control"/>
                        </div>

                        <div class="form-group">
                            <label>Device ID</label>
                            <input type="text" id="update_deviceid"  class="form-control" onkeypress="return isNumberOnly(event)"/>
                        </div>

                        <div class="form-group">
                            <label>Device Extension</label>
                            <input type="text" id="update_devicenumber"  class="form-control" onkeypress="return isNumberOnly(event)"/>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="UpdateDeviceDetails()">Save Changes</button>
                        <input type="hidden" id="hidden_sdg_id">
                        <input type="hidden" id="hidden_sd_id">
                        <input type="hidden" id="hidden_sd_extension">
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
