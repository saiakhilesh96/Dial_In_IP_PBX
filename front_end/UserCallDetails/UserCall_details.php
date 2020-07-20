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
        <title>Call Logs</title>
    </head>
    <!-- jquery-ui.js,jquery-ui.css,modernizr-2.8.3.js,jquery-1.11.3.min.js NOT REQUIRED     -->
    <!--<link rel=" stylesheet" href="StyleSheets/bootstrap-theme.min.css">-->
    <link rel="stylesheet" href="../StyleSheets/stylingforcalllogs.css" type="text/css" media="all">
    <link rel="stylesheet" href="../StyleSheets/bootstrap.min.css">
    <!--<script src="JSfiles/jquery-1.11.3.min.js"></script>-->
    <link href="../StyleSheets/infragistics.theme.css" rel="stylesheet" />
    <link href="../StyleSheets/infragistics.css" rel="stylesheet" />
    <script type="text/javascript" src="../JSfiles/jquery.min.js"></script>
    <!--<script type="text/javascript" src="JSfiles/jquery-ui.js"></script>   NOT REQUIRED-->
    <script type="text/javascript" src="../JSfiles/Security.js"></script>
    <script type="text/javascript" src="../JSfiles/bootstrap.min.js"></script>
    <!--<link rel="stylesheet" href="StyleSheets/jquery-ui.css" type="text/css" media="all"/>     NOT REQUIRED-->

<!--<script src="JSfiles/modernizr-2.8.3.js"></script>        NOT REQUIRED-->
<!--<script src="JSfiles/jquery-1.11.3.min.js"></script>    NOT REQUIRED-->
<?php include_once '../UserCallDetails/Openmenu.php'; ?>
    <script src="../JSfiles/jquery-ui.min.js"></script>
    <script src="../JSfiles/infragistics.core.js"></script>
    <script src="../JSfiles/infragistics.lob.js"></script>
    
    <body>
        <header class="headercontrol">
            <h2 class="textcontrol">Call Logs</h2>
            
            <a href="../Administrator.php" class="homebutton btn btn-info" ><img src="../IMG/home.png" width="30px" height="30px"></a>
            <img style="margin-top: -5%; margin-left: 5%; position: absolute;" src="../IMG/dialin_logo.png" width="9%" height="8%">
            <div align="center">  
                <button name="create_excel" id="create_excel" class="btn btn-info">Export</button>  
            </div>
            <div class="arrangecheckbox"><input type="text" name="callstatus"  id="callstatus"  placeholder="Call Status" /></div>
            
            <div>
                <select class="QuickFilter form-control btn btn-info" id="QuickFilter">
                    <option class="look">Quick filter</option>
                    <option class="lookandfeel" value="1">Today</option>
                    <option class="lookandfeel" value="2">Yesterday</option>
                    <option class="lookandfeel" value="3">Last week</option>
                    <option class="lookandfeel" value="4">This Month</option>
                    <option class="lookandfeel" value="5">Last Month</option>
                    <option class="lookandfeel" value="6">This Year</option>
                    <option class="lookandfeel" value="7">Last Year</option>
                </select>
            </div>
            <div>
                <select class="category form-control btn btn-info" id="category">
                    <option class="look">Search by</option>
                    <option class="lookandfeel" selected="ALL" value="3">All</option>
                    <option class="lookandfeel" value="0">Intercom</option>
                    <option class="lookandfeel" value="1">Incoming</option>
                    <option class="lookandfeel" value="2">Outgoing</option>
                </select>
            </div>
            
        </header>
        <br />
        <div class="container">
            <table>
                <tr>
                    <td class="arrangeuid">
                        <input type="text" name="user_id" id="user_id" class="form-control" placeholder="User Id" />
                    </td>
                    <td class="arrangesrc">
                        <input type="text" name="src" id="src" class="form-control" placeholder="Source" />
                    </td>
                    <td class="arrangedst">
                        <input type="text" name="dst"  id="dst" class="form-control" placeholder="Destination" />
                    </td>
                    <td class="arrangefrom">
                        <input type="text" name="from_date"  id="from_date" class="form-control" placeholder="From Date" />
                    </td>
                    <td class="arrangeto">
                        <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" />
                    </td>                  
                    <td class="arrangefilter">
                        <input type="button" name="filter" id="filter" value="Filter" class="btn btn-info" />
                    </td>
                </tr>
            </table>
            <?php
            include_once '../DBconnection.php';
            $usercalllogs = "select * from USERCALL_DETAILS order by start_time desc";
            $resultofcalllogs = mysqli_query($conn, $usercalllogs);
            ?>
            <div id="order_table">
                <?php
                if ($resultofcalllogs->num_rows > 0) {
                    echo "<div class='container wrap'>";
                    echo "<table class='table head'>";
                    echo "<thead align='center'>";
                    echo "<tbody>";
                    echo "<tr>";
                    echo "<td style= 'width:2em;'>Datetime</td>";
                    echo "<td style= 'width:2em;'>UserId</td>";
                    echo "<td style= 'width:3em;'>Name</td>";
                    echo "<td style= 'width:3em;'>Course</td>";
                    echo "<td style= 'width:3em;'>Source</td>";
                    echo "<td style= 'width:3em;'>Destination</td>";
                    echo "<td style= 'width:2em;'>Call Duration</td>";
                    echo "<td style= 'width:2em;'>Bill Seconds</td>";
                    echo "<td style= 'width:2.5em;'>Call Status</td>";
                    echo "<td style= 'width:1.5em;'>Duration/sec</td>";
                    echo "<td style= 'width:1.5em;'>Call Rate</td>";
                    echo "<td style= 'width:2em;'>Call Cost</td>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                    echo "<div class='container wrap inner_table'>";
                    echo "<table class='table table-bordered'>";
                    echo "<tbody align='center'>";
                    while ($row = $resultofcalllogs->fetch_assoc()) {
                        $u_data = "select user_name,course_id from USERS where user_id= '" . $row['user_id'] . "'";
                        $Ru_date = mysqli_query($conn, $u_data);
                        $FUResults = mysqli_fetch_array($Ru_date);
                        $name = $FUResults['user_name'];
                        //echo "name ".$name;
                        
                        $c_date = "select course_title from COURSE where course_id='" . $FUResults['course_id'] . "'";
                        $Rc_date = mysqli_query($conn, $c_date);
                        $FCResults = mysqli_fetch_array($Rc_date);
                        $course = $FCResults['course_title'];
                        //echo "course ".$course;
                        $chargeP= $row['charge_paise'];
                        $chargePaise= ($chargeP) / (100);
                        echo "<tr>";
                        echo "<td style='width:2em;'>" . $row['start_time'] . "</td>";
                        echo "<td style='width:2em;'>" . $row['user_id'] . "</td>";
                        echo "<td style='width:3em;'>" . $name . "</td>";
                        echo "<td style='width:3em;'>" . $course . "</td>";
                        echo "<td style='width:3em;'> " . $row['src'] . "</td>";
                        echo "<td style='width:3em;'>" . $row['dst'] . "</td>";
                        echo "<td style='width:2em;'>" . $row['call_dur'] . "</td>";
                        echo "<td style='width:2em;'>" . $row['bill_sec'] . "</td>";
                        echo "<td style='width:2.5em;'>" . $row['call_status'] . "</div></td>";
                        echo "<td style='width:1em;'>" . $row['duration_sec'] . "</div></td>";
                        echo "<td style='width:1em;'>" . $chargePaise . "</div></td>";
                        echo "<td style='width:2em;'>" . $row['call_cost'] . "</div></td>";
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
        </div>
    </body>
    <script type="text/javascript">
        $(document).ready(function () {
            $.datepicker.setDefaults({
                dateFormat: 'yy-mm-dd'
            });
            $(function () {
                $("#from_date").datepicker();
                $("#to_date").datepicker();
            });
            $('#filter').click(function () {
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                var user_id = $('#user_id').val();
                var src = $('#src').val();
                var dst = $('#dst').val();
                var callstatus = $('#callstatus').val();
                var category = $('#category').val();
                var QuickFilter = $('#QuickFilter').val();
                if ((from_date !== '' && to_date !== '') || user_id !== '' || src !== '' || dst !== '' || callstatus !== '' || category === '3' || category === '0' || category === '1' || category === '2' || QuickFilter === '1' || QuickFilter === '2' || QuickFilter === '3' || QuickFilter === '4' || QuickFilter === '5' || QuickFilter === '6' || QuickFilter === '7')
                {
                    $.ajax({
                        url: "Filter.php",
                        method: "POST",
                        data: {from_date: from_date, to_date: to_date, user_id: user_id, src: src, dst: dst, callstatus: callstatus, category: category, QuickFilter: QuickFilter},
                        success: function (data)
                        {
                            $('#order_table').html(data);
                        }
                    });
                } else
                {
                    alert("Please Select Data");
                }
            });
        });
    </script>
    <script>
        var call_status = [
            {Name: "EMPTY USERID"},
            {Name: "EMPTY PIN"},
            {Name: "INVALID USER"},
            {Name: "INVALID PIN"},
            {Name: "INVALID TIME"},
            {Name: "INACTIVE USER"},
            {Name: "EMPTY SPEED DIAL"},
            {Name: "INVALID SPEED DIAL"},
            {Name: "INVALID I-PREFIX"},
            {Name: "INVALID PERIOD"},
            {Name: "INVALID EXTENSION"},
            {Name: "INTERNAL ERROR"},
            {Name: "DUPLICATE USER"},
            {Name: "CALL LIVE"},
            {Name: "CALL DENIED"},
            {Name: "CANCEL"},
            {Name: "CALL ANSWERED"},
            {Name: "NO RULES"},
            {Name: "INSUFFICIENT BALANCE"},
            {Name: "INSUFFICIENT TIME BALANCE"},
            {Name: "INVALID WHITELIST PERIOD"},
            {Name: "INVALID PLAN"},
            {Name: "BUSY"},
            {Name: "CONGESTION"},
            {Name: "NO ANSWER"},
            {Name: "PIN MISMATCH"},
            {Name: "NEW PIN EMPTY"},
        ];

        $(function () {
            $("#callstatus").igCombo({
                width: 150,
                dataSource: call_status,
                textKey: "Name",
                valueKey: "Name",
                multiSelection: {
                    enabled: true
                },
                dropDownOrientation: "bottom"
            });
//            $("#checkboxSelectCombo").igCombo({
//                width: 300,
//                dataSource: colors,
//                textKey: "Name",
//                valueKey: "Name",
//                multiSelection: {
//                    enabled: true,
//                    showCheckboxes: true
//                },
//                dropDownOrientation: "bottom"
//            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#create_excel').click(function () {
                var excel_data = $('#order_table').html();
                var page = "Filter.php?data=" + excel_data;
                window.location = page;
            });
        });
    </script>
</html>