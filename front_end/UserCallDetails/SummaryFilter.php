<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>SummaryOfCalls</title>
    </head>
    <link rel="stylesheet" href="../StyleSheets/SummUP.css" type="text/css" media="all">
    <link rel="stylesheet" href="../StyleSheets/bootstrap.min.css">
    <body>
        <?php
        header('Content-Type: application/vnd.ms-excel');
        header('Content-disposition: attachment; filename= SummaryOfCalls.xls');
        echo $_GET["data"];
        include_once '../DBconnection.php';
        $userid = $_POST['user_id'];
        $src = $_POST['src'];
        $fromdate = $_POST["from_date"];
        $todate = $_POST["to_date"];
        $category = $_POST['category'];
        $bool = 0;
        $qry = "select user_id,src,dst,sum(call_cost),sum(bill_sec) from (select user_id,src,dst,call_cost,bill_sec from USERCALL_DETAILS";
        if ($userid != null || $src != null || $fromdate != null || $todate != null || $category != null) {
            $qry = $qry . " where";
        }
        if ($userid != null) {
            $qry = $qry . " user_id like '%" . $userid . "%' and call_status= 'CALL ANSWERED'";
            $bool = 1;
        }
        if ($src != null) {
            if ($bool) {
                $qry = $qry . " and";
            } else {
                $bool = 1;
            }
            $qry = $qry . " src like '%" . $src . "%' or dst like '%" . $src . "%' and call_status= 'CALL ANSWERED'";
        }
        if ($category != null) {
            if ($category == 3) {
                $category = "''0','1','2''";
            }
            if ($bool) {
                    $qry = $qry . " and";
                } else {
                    $bool = 1;
                }
            $qry = $qry . " call_dir in ('" . $category . "') and call_status= 'CALL ANSWERED'";
        }

        if ($fromdate != null && $todate != null) {
            if ($bool) {
                $qry = $qry . " and";
            } else {
                $bool = 1;
            }
            $qry = $qry . " date(start_time) BETWEEN '" . $fromdate . "' and '" . $todate . "' and call_status= 'CALL ANSWERED'";
            //echo "".$qry;
        }
        $qry = $qry . " ) as a group by a.user_id,a.src,a.dst;";
        
        //------------This is Generating based on search----------------
        $tocost = "select sum(call_cost),sum(bill_sec) from USERCALL_DETAILS";
        
        if ($userid != null || $src != null || $fromdate != null || $todate != null || $category != null) {
            $tocost = $tocost . " where  call_cost > 0 and call_status='CALL ANSWERED'";
        }
        if ($userid != null) {
            $tocost = $tocost . " and user_id like '%" . $userid . "%'";
            $bool = 1;
        }
        if ($src != null) {
            if ($bool) {
                $tocost = $tocost . " and";
            } else {
                $bool = 1;
            }
            $tocost = $tocost . " (src like '%" . $src . "%' or dst like '%" . $src . "%')";
        }
        if ($category != null) {
            if ($category == 3) {
                $category = "''0','1','2''";
                if ($bool) {
                    $tocost = $tocost . " and";
                } else {
                    $bool = 1;
                }
            }
            $tocost = $tocost . " and call_dir in ('" . $category . "') and call_status= 'CALL ANSWERED';";
        }
//        $tocost = "select sum(call_cost),sum(bill_sec) from USERCALL_DETAILS where call_cost > 0;";
        $resultOfTimeNCost = mysqli_query($conn, $tocost);
        $TimespentNcost= mysqli_fetch_array($resultOfTimeNCost);
        echo "<div id= arrGenerate>";
//        $cost_date = mysqli_query($conn, $tocost);
//        $TotalCost = mysqli_fetch_array($cost_date);
        $Totaltime = $TimespentNcost['sum(bill_sec)'];
        $billmintc = (int) ($Totaltime / 60);
        $billsectc = (int) ($Totaltime % 60);
        $Totaltimespent = $billmintc . "min " . $billsectc . "sec";
        $mydate = getdate(date("U"));
        echo "Generated on " . "$mydate[weekday], $mydate[month] $mydate[mday], $mydate[year], $mydate[hours]:$mydate[minutes]:$mydate[seconds] " . "Total Time Spent is " . $Totaltimespent . " and Total Call Cost is " . $TimespentNcost['sum(call_cost)']  ;
        echo "</div>";
        
        $result = mysqli_query($conn, $qry);
        if ($result->num_rows > 0) {
            echo "<div class='container wrap'>";
            echo "<table class='table head'>";
            echo "<thead align='center'>";
            echo "<tbody>";
            echo "<tr>";
            echo "<td style= 'width:3em;'>UserId</td>";
            echo "<td style= 'width:3em;'>Name</td>";
            echo "<td style= 'width:3em;'>Course</td>";
            echo "<td style= 'width:3em;'>Source</td>";
            echo "<td style= 'width:3em;'>Destination</td>";
            echo "<td style= 'width:2em;'>Bill Seconds</td>";
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

                $billSeconds = $row['sum(bill_sec)'];
                $billmin = (int) ($billSeconds / 60);
                $billsec = (int) ($billSeconds % 60);
                $billSeconds = $billmin . "min " . $billsec . "sec";
                echo "<tr>";
                echo "<td style='width:2em;'>" . $row['user_id'] . "</td>";
                echo "<td style='width:3em;'>" . $name . "</td>";
                echo "<td style='width:3em;'>" . $course . "</td>";
                echo "<td style='width:3em;'>" . $row['src'] . "</td>";
                echo "<td style='width:3em;'>" . $row['dst'] . "</td>";
                echo "<td style='width:2em;'>" . $billSeconds . "</td>";
                echo "<td style='width:2em;'>" . $row['sum(call_cost)'] . "</td>";
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
//        echo $qry . "<br/><br/>";
//        echo $tocost . "<br/>";
        mysqli_close($conn);
        ?>
    </body>
</html>