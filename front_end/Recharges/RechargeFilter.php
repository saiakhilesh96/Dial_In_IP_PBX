<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Recharges</title>
    </head>
    <link rel="stylesheet" href="../StyleSheets/Recharge.css" type="text/css" media="all">
    <link rel="stylesheet" href="../StyleSheets/bootstrap.min.css">
    <body>
        <?php
        header('Content-Type: application/vnd.ms-excel');
        header('Content-disposition: attachment; filename= Recharges.xls');
        echo $_POST["data"];
        include_once '../DBconnection.php';
        $userid = $_POST['user_id'];
        $fromdate = $_POST["from_date"];
        $todate = $_POST["to_date"];
        $bool = 0;
        $qry = "select * from RECHARGES";
        if ($userid != null || $owner != null || $user_name != null ||  $fromdate != null || $todate != null) {

            $qry = $qry . " where";
        }
        if ($userid != null) {
            $qry = $qry . " user_id like '%" . $userid . "%'";
            $bool = 1;
        }
        
        if ($fromdate != null && $todate != null) {
            if ($bool) {
                $qry = $qry . " and";
            } else {
                $bool = 1;
            }
            $qry = $qry . " date(recharge_time) BETWEEN '" . $fromdate . "' and '" . $todate . "'";
            //echo "".$qry;
        }
        $qry = $qry . " order by recharge_time desc";
        
        $rechargeresult = mysqli_query($conn, $qry);
        if ($rechargeresult->num_rows > 0) {
            echo "<div class='container wrap'>";
            echo "<table class='table head'>";
            echo "<thead align='center'>";
            echo "<tbody>";
            echo "<tr>";
            echo "<td style= 'width:3em;text-align:center;'>Recharge Time</td>";
//            echo "<td style= 'width:3em;text-align:center;'>Recharge Owner</td>";
            echo "<td style= 'width:3em;text-align:center;'>User Id</td>";
            echo "<td style= 'width:3em;text-align:center;'>Name</td>";
            echo "<td style= 'width:3em;text-align:center;'>Course</td>";
            echo "<td style= 'width:2em;text-align:center;'>Old Balance</td>";
            echo "<td style= 'width:2em;text-align:center;'>Amount</td>";
            echo "<td style= 'width:2em;text-align:center;'>Current Balance</td>";
            echo "</tr>";
            echo "</thead>";
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            echo "<div class='container wrap inner_table'>";
            echo "<table class='table table-bordered'>";
            echo "<tbody align='center'>";
            while ($row = $rechargeresult->fetch_assoc()) {
                $u_data = "select user_name,course_id from USERS where user_id= '" . $row['user_id'] . "'";
                $Ru_date = mysqli_query($conn, $u_data);
                $FUResults = mysqli_fetch_array($Ru_date);
                $name = $FUResults['user_name'];
                $recharger = "select user_name from USERS where user_id= '" . $row['recharger_id'] . "'";
                $RR_date = mysqli_query($conn, $recharger);
                $FRResults = mysqli_fetch_array($RR_date);
                $Recharger_name = $FRResults['user_name'];
                $c_date = "select course_title from COURSE where course_id='" . $FUResults['course_id'] . "'";
                $Rc_date = mysqli_query($conn, $c_date);
                $FCResults = mysqli_fetch_array($Rc_date);
                $course = $FCResults['course_title'];
                echo "<td style='width:3em;'>" . $row['recharge_time'] . "</td>";
//                echo "<td style='width:3em;'>" . $Recharger_name . "</td>";
                echo "<td style='width:3em;'>" . $row['user_id'] . "</td>";
                echo "<td style='width:3em;'>" . $name . "</td>";
                echo "<td style='width:3em;'>" . $course . "</td>";
                echo "<td style='width:2em;'>" . $row['old_balance'] . "</td>";
                echo "<td style='width:2em;'>" . $row['amount'] . "</td>";
                echo "<td style='width:2em;'>" . $row['new_balance'] . "</td>";
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
//        echo "".$qry;
        mysqli_close($conn);
        ?>
    </body>
</html>