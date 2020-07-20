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
        <title>Live Calls</title>
    </head>
    
    <link rel="stylesheet" href="../StyleSheets/stylingforcalllives.css" type="text/css" media="all">
    <link rel="stylesheet" href="../StyleSheets/bootstrap.min.css">
    <script type="text/javascript" src="../JSfiles/jquery.min.js"></script>
    <script type="text/javascript" src="../JSfiles/Security.js"></script>
    <script type="text/javascript" src="../JSfiles/bootstrap.min.js"></script>
    <body>
        <header class="headercontrol">
            
            <h2 class="textcontrol">Live Calls</h2>
            <a href="../Administrator.php" class="homebutton btn btn-info" ><img src="../IMG/home.png" width="30px" height="30px"></a>
            <img style="margin-top: -5%; margin-left: 5%; position: absolute;" src="../IMG/dialin_logo.png" width="9%" height="8%">
        </header>
        <br />
        <div class="container">
            <div id="order_table">  
                <?php
                include_once '../DBconnection.php';
                $usercalllogs = "select * from USERCALL_DETAILS where final_status= 'CALL LIVE'";
                $resultofcalllogs = mysqli_query($conn, $usercalllogs);
                if ($resultofcalllogs->num_rows > 0) {
                    echo "<div class='container wrap'>";
                    echo "<table class='table head'>";
                    echo "<thead align='center'>";
                    echo "<tbody>";
                    echo "<tr>";
                    echo "<td style= 'width:2em;'>StartTime</td>";
                    echo "<td style= 'width:2em;'>UserId</td>";
                    echo "<td style= 'width:3em;'>Name</td>";
                    echo "<td style= 'width:3em;'>Class</td>";
//                    echo "<td style= 'width:3em;'>Speaking to</td>";
                    echo "<td style= 'width:3em;'>Source</td>";
                    echo "<td style= 'width:3em;'>Destination</td>";
                    echo "<td style= 'width:2em;'>AnswerTime</td>";
                    echo "<td style= 'width:2em;'>Bill Seconds</td>";
                    echo "<td style= 'width:2em;'>Call Direction</td>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                    echo "<div class='container wrap inner_table'>";
                    //echo "<form method='post' action='Rulesdata.php'>";
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
                        if ($row['call_dir'] == 0) {
                            $call_dir = "INTERCOM CALL";
                        } else if ($row['call_dir'] == 1) {
                            $call_dir = "INCOMING CALL";
                        } else if ($row['call_dir'] == 2) {
                            $call_dir = "OUTGOING CALL";
                        } else if ($row['call_dir'] == 3) {
                            $call_dir = "CALL CENTER";
                        }
                        echo "<tr>";
                        echo "<td style='width:2em;color:green;'>" . $row['start_time'] . "</td>";
                        echo "<td style='width:2em;color:green;'>" . $row['user_id'] . "</td>";
                        echo "<td style='width:3em;color:green;'>" . $name . "</td>";
                        echo "<td style='width:3em;color:green;'>" . $course . "</td>";
//                        echo "<td style='width:3em;color:green;'>" . $rela . "</td>";
                        echo "<td style='width:3em;color:green;'>" . $row['src'] . "</td>";
                        echo "<td style='width:3em;color:green;'>" . $row['dst'] . "</td>";
                        echo "<td style='width:2em;color:green;'>" . $row['answer_time'] . "</td>";
                        echo "<td style='width:2em;color:green;'>" . $row['bill_sec'] . "</td>";
                        echo "<td style='width:2em;color:green;'>" . $call_dir . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    //echo"</form>";
                    echo "</div>";
                } else {
                    echo "<table class='table table-bordered'>";
                    echo "<tr>";
                    echo "<td align= 'center'>CURRENTLY THERE ARE NO LIVE CALLS</td>";
                    echo "</tr>";
                    echo "</table>";
                }
                ?>
            </div>  
        </div>
    </body>
    <?php include_once '../UserCallDetails/Openmenu.php'; ?>
    <script type="text/javascript">
        var auto_refresh = setInterval(
                function ()
                {
                    $('#order_table').load('livecalls.php').fadeIn("slow");
                }, 300); // refresh every 10000 milliseconds
    </script>
</html>
