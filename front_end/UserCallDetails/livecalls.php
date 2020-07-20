<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Call Logs</title>
    </head>
    <link rel="stylesheet" href="../StyleSheets/stylingforcalllives.css" type="text/css" media="all">
    <link rel="stylesheet" href="../StyleSheets/bootstrap.min.css">
    <body>
        <div class="container">
            <div id="order_table">
                <?php
                include_once '../DBconnection.php';
                $qry = "select * from USERCALL_DETAILS where final_status= 'CALL LIVE' order by start_time desc";
                $result = mysqli_query($conn, $qry);
                if ($result->num_rows > 0) {
                    echo "<div class='container wrap'>";
                    echo "<table class='table head'>";
                    echo "<thead align='center'>";
                    echo "<tbody>";
                    echo "<tr>";
                    echo "<td style= 'width:2em;'>StartTime</td>";
                    echo "<td style= 'width:2em;'>UserId</td>";
                    echo "<td style= 'width:3em;'>Name</td>";
                    echo "<td style= 'width:3em;'>Class</td>";
//            echo "<td style= 'width:3em;'>Speaking to</td>";
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
                    echo "<table class='table table-bordered'>";
                    echo "<tbody align='center'>";
                    while ($row = $result->fetch_assoc()) {
                        $u_data = "select user_name,course_id from USERS where user_id= '" . $row['user_id'] . "'";
                        $Ru_date = mysqli_query($conn, $u_data);
                        $FUResults = mysqli_fetch_array($Ru_date);
                        $name = $FUResults['user_name'];
                        $c_date = "select course_title from COURSE where course_id='" . $FUResults['course_id'] . "'";
                        $Rc_date = mysqli_query($conn, $c_date);
                        $FCResults = mysqli_fetch_array($Rc_date);
                        $course = $FCResults['course_title'];
                        if ($row['call_dir'] == 0) {
                            $call_dir = "INTERCOM CALL";
                        } else if ($row['call_dir'] == 1) {
                            $call_dir = "INCOMING CALL";
                        } else if ($row['call_dir'] == 2) {
                            $call_dir = "OUTGOING CALL";
                        } else if ($row['call_dir'] == 3) {
                            $call_dir = "CALL CENTER";
                        }
//                $relation = " select * from USERCALL_DETAILS as ud join WHITELIST_CONTACTS as wc where (ud.user_id= wc.user_id) and wc.user_id = '".$row['user_id']."'";
////                echo "" . $relation;
//                $Relation = mysqli_query($conn, $relation);
//                $FRelation = mysqli_fetch_array($Relation);
//                $re= $FRelation['relation'];
//                $pe= $FRelation['src'];
//                echo " relation ".$re;
//                echo " src from join ".$pe;
//                echo "src from ud ".$row['src'];
//                $pos= strpos($row['src'], $pe);
//                if($pos === TRUE){
//                    echo "The string is found <br />";
//                    $rela= $FRelation['relation'];
//                }
                        echo "<tr>";
                        echo "<td style='width:2em;color:green;'>" . $row['start_time'] . "</td>";
                        echo "<td style='width:2em;color:green;'>" . $row['user_id'] . "</td>";
                        echo "<td style='width:3em;color:green;'>" . $name . "</td>";
                        echo "<td style='width:3em;color:green;'>" . $course . "</td>";
//                echo "<td style='width:3em;color:green;'>" . $rela . "</td>";
                        echo "<td style='width:3em;color:green;'>" . $row['src'] . "</td>";
                        echo "<td style='width:3em;color:green;'>" . $row['dst'] . "</td>";
                        echo "<td style='width:2em;color:green;'>" . $row['answer_time'] . "</td>";
                        echo "<td style='width:2em;color:green;'>" . $row['bill_sec'] . "</td>";
                        echo "<td style='width:2em;color:green;'>" . $call_dir . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                } else {
                    echo "<table class='table table-bordered'>";
                    echo "<tr>";
                    echo "<td align= 'center'>CURRENTLY THERE ARE NO LIVE CALLS</td>";
                    echo "</tr>";
                    echo "</table>";
                }
                mysqli_close($conn);
                ?>
            </div>
        </div>
    </body>
</html>