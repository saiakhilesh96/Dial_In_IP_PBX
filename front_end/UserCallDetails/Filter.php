<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Call Logs</title>
    </head>
    <link rel="stylesheet" href="../StyleSheets/stylingforcalllogs.css" type="text/css" media="all">
    <link rel="stylesheet" href="../StyleSheets/bootstrap.min.css">
    <body>
        <?php
        header('Content-Type: application/vnd.ms-excel');
        header('Content-disposition: attachment; filename= CallLogs.xls');
        echo $_POST["data"];
        include_once '../DBconnection.php';
        $userid = $_POST['user_id'];
        $src = $_POST['src'];
        $dest = $_POST['dst'];
        $callstatus = $_POST['callstatus'];
        $fromdate = $_POST["from_date"];
        $todate = $_POST["to_date"];
        $category = $_POST['category'];
        $QuickFilter = $_POST['QuickFilter'];
        if ($category == 'Search by') {
            $category = '';
        }
        if ($QuickFilter == 'Quick filter') {
            $QuickFilter = '';
        }
        if ($category == 3) {
            $category = '';
        }
        $bool = 0;
        $tocost = "select sum(call_cost) from USERCALL_DETAILS where  call_cost > 0";
        $cost_date = mysqli_query($conn, $tocost);
        $TotalCost = mysqli_fetch_array($cost_date);
        echo "<div id= arrGenerate>";
        $mydate = getdate(date("U"));
        echo "Generated on " . "$mydate[weekday], $mydate[month] $mydate[mday], $mydate[year], $mydate[hours]:$mydate[minutes]:$mydate[seconds] " . " Total Call Cost is " . $TotalCost['sum(call_cost)'];
        echo "</div>";
        $qry = "select * from USERCALL_DETAILS";
        if ($userid != null || $src != null || $dest != null || $callstatus != null || $fromdate != null || $todate != null || $category != null || $QuickFilter != null) {

            $qry = $qry . " where";
        }
        if ($userid != null) {
            $qry = $qry . " user_id like '%" . $userid . "%'";
            $bool = 1;
        }
        if ($src != null) {
            if ($bool) {
                $qry = $qry . " and";
            } else {
                $bool = 1;
            }
            $qry = $qry . " src like '%" . $src . "%'";
        }
        if ($dest != null) {
            if ($bool) {
                $qry = $qry . " and";
            } else {
                $bool = 1;
            }
            $qry = $qry . " dst like '%" . $dest . "%'";
        }
        if ($callstatus != null) {
            if ($bool) {
                $qry = $qry . " and";
            } else {
                $bool = 1;
            }
            //$qry = $qry . " call_status = '" . $callstatus . "'";
            $parts = explode(',', $callstatus);
            $i = 0;
            while ($i != count($parts)) {
                $forming = $forming . "'" . $parts[$i] . "',";
                $i++;
            }
            $forming = "(" . $forming . ")";
            $forming = str_replace(",' ", ",'", $forming);
            $forming = str_replace(",)", "", $forming);
            $qry = $qry . " call_status in " . $forming . ")";
            //echo "".$qry;
        }
        if ($category != null) {
            if ($bool) {
                $qry = $qry . " and";
            } else {
                $bool = 1;
            }
            $qry = $qry . " call_dir = '" . $category . "'";
        }
        if ($fromdate != null && $todate != null) {
            if ($bool) {
                $qry = $qry . " and";
            } else {
                $bool = 1;
            }
            $qry = $qry . " date(start_time) BETWEEN '" . $fromdate . "' and '" . $todate . "'";
            //echo "".$qry;
        }
        if ($QuickFilter != null) {
            if ($bool) {
                $qry = $qry . " and";
            } else {
                $bool = 1;
            }
            if ($QuickFilter == 1) {
                $qry = $qry . " DAY(start_time) = DAY(NOW())";
                //echo "".$qry;
            } elseif ($QuickFilter == 2) {
                $qry = $qry . " DAY(start_time) = DAY(NOW())-1";
                //echo "".$qry;
            } elseif ($QuickFilter == 3) {
                $qry = $qry . " start_time > DATE_SUB(NOW(), INTERVAL 1 WEEK)";
            } elseif ($QuickFilter == 4) {
                $qry = $qry . " start_time > DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())-1) DAY) and  date(DATE_SUB(NOW(), INTERVAL 1 DAY))";
            } elseif ($QuickFilter == 5) {
                $qry = $qry . " start_time BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())+30) DAY) and DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())) DAY)";
            } elseif ($QuickFilter == 6) {
                $qry = $qry . " start_time BETWEEN DATE_SUB(CURDATE(),INTERVAL (DAY(CURDATE())+30) DAY) and curdate()";
            } elseif ($QuickFilter == 7) {
                $currentYear = date("Y-m-d");
                list($lastyear, $m, $d) = explode("-", $currentYear);
                $lastyear = $lastyear - 1;
                $qry = $qry . " start_time like '" . $lastyear . "%'";
            }
        }
        $qry = $qry . " order by start_time desc";
        $result = mysqli_query($conn, $qry);
        if ($result->num_rows > 0) {
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
            while ($row = $result->fetch_assoc()) {
                $u_data = "select user_name,course_id from USERS where user_id= '" . $row['user_id'] . "'";
                $Ru_date = mysqli_query($conn, $u_data);
                $FUResults = mysqli_fetch_array($Ru_date);
                $name = $FUResults['user_name'];
                $c_date = "select course_title from COURSE where course_id='" . $FUResults['course_id'] . "'";
                $Rc_date = mysqli_query($conn, $c_date);
                $FCResults = mysqli_fetch_array($Rc_date);
                $course = $FCResults['course_title'];

                $chargeP = $row['charge_paise'];
                $chargePaise = ($chargeP) / (100);

                echo "<tr>";
                echo "<td style='width:2em;'>" . $row['start_time'] . "</td>";
                echo "<td style='width:2em;'>" . $row['user_id'] . "</td>";
                echo "<td style='width:3em;'>" . $name . "</td>";
                echo "<td style='width:3em;'>" . $course . "</td>";
                echo "<td style='width:3em;'>'" . $row['src'] . "</td>";
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
    </body>
</html>