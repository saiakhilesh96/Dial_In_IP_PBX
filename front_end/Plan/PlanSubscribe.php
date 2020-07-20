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
        <title>Plan Subscribe</title>
    </head>
    <!--<link rel="stylesheet" href="../StyleSheets/MoneyboxPopup.css">-->
    <link rel="stylesheet" href="../StyleSheets/Plancss.css" type="text/css" media="all">
    <!--<link rel="stylesheet" type="text/css" href="../StyleSheets/Menustyle_1.css"/>-->
    <!--<link rel="stylesheet" href="StyleSheets/stylingforcalllogs.css" type="text/css" media="all">-->
    <link rel="stylesheet" href="../StyleSheets/bootstrap.min.css">
    <!--<link rel="stylesheet"  href="StyleSheets/StyleSheet.css">-->
    <script src="../JSfiles/M_validation.js" type="text/javascript"></script>
    <script type="text/javascript" src="../JSfiles/jquery.min.js"></script>
    <script type="text/javascript" src="../JSfiles/bootstrap.min.js"></script>
    <script type="text/javascript" src="../JSfiles/jquery-ui.min.js"></script>
    <script type="text/javascript" src='../JSfiles/Security.js' ></script>
    <link href="../StyleSheets/infragistics.theme.css" rel="stylesheet" />
    <link href="../StyleSheets/infragistics.css" rel="stylesheet" />
    <body>
        <header class="headercontrol">
            <h2 class="textcontrol">Plans Subscribed</h2>
            <a href="Plan.php" ><input class="moneybtn btn btn-info"  type="button" value="Existing Plans"></a>
            <a href="../Administrator.php" class="homebutton btn btn-info" ><img src="../IMG/home.png" width="30px" height="30px"></a>
            <!--<div align="center">-->  
<!--                <button name="create_excel" id="create_excel" class="btn btn-info">Export</button>  -->
            <img style="margin-top: -5%; margin-left: 5%; position: absolute;" src="../IMG/dialin_logo.png" width="9%" height="8%">
            <!--</div>-->
        
        <?php  include_once '../Plan/Openmenu.php';    ?>
        
        </header>
        <!--<span style="color: red"><?php // echo $err;    ?></span>-->
<!--        <div id="moneybox" class="overlay">
            <div class="passpopup">
                <h3 style="text-align: center; font-family: swaseeda; color:#fff">Money Box</h3><hr />
                <a class="myclosebtn"  href="">&Chi;</a>
                <form method="post" name="MoneyForm" action="Moneybox.php">
                    <span style="color: red;font-size: 12px;margin-top: 23%;position: absolute;"><?php
//                        if (isset($_GET['msg'])) {
//                            echo $_GET['msg'];
//                        };
                        ?></span>
                    <table>
                        <tr>
                            <td class="arrangeuid">
                                <div class="search-box">
                                    <input type="text" name="user_id" autocomplete="on"  class="form-control" placeholder="User Id" onkeypress="return isNumberOnly(event)"/>
                                    <div class="result"></div>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="amount" id="arrangeamt" class="form-control"  placeholder="Amount" onkeypress="return isNumberKey(event)"/>
                            </td>
                            <td class="arrangedone">
                                <input type="button" style="padding: 10px 20px 10px 20px;" value="Done" onclick="SubmitMoneyTrans();" class="btn btn-info" />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>-->
        <br />
        <div class="container">
<!--            <table>
                <tr>
                    <td class="arrangeFuid">
                        <input type="text" name="user_id" id="user_id" size="12" class="form-control" placeholder="Search by UserId" />
                    </td>
                    <td class="arrangefrom">
                        <input type="text" name="from_date" size="26" id="from_date" class="form-control" placeholder="From Date" />
                    </td>
                    <td class="arrangeto">
                        <input type="text" name="to_date" size="26" id="to_date" class="form-control" placeholder="To Date" />
                    </td>                  
                    <td class="arrangefilter">
                        <input type="button" name="filter" style="padding: 10px 30px 10px 30px;" id="filter" value="Filter" class="btn btn-info" />
                    </td>
                </tr>
            </table>-->
            <?php
            include_once '../DBconnection.php';
            $allotedPlans = "select user_id,calltype_id from WHITELIST_CONTACTS group by user_id,calltype_id";
            $resultOfPlan = mysqli_query($conn, $allotedPlans);
            ?>
            <div id="rechargetable">
                <?php
                if ($resultOfPlan->num_rows > 0) {
                    echo "<div class='container wrap'>";
                    echo "<table class='table head'>";
                    echo "<thead align='center'>";
                    echo "<tbody>";
                    echo "<tr>";
                    echo "<td style= 'width:3em;text-align:center;'>User ID</td>";
//                    echo "<td style= 'width:2em;'>Recharge Owner</td>";
                    echo "<td style= 'width:3em;text-align:center;'>Name</td>";
                    echo "<td style= 'width:3em;text-align:center;'>Course</td>";
                    echo "<td style= 'width:3em;text-align:center;'>Plan Name</td>";
                    echo "<td style= 'width:2em;text-align:center;'>Call Type Name</td>";
                    echo "<td style= 'width:2em;text-align:center;'>Chagre Paise</td>";
                    echo "<td style= 'width:2em;text-align:center;'>Duration Sec</td>";
                    echo "<td style= 'width:2em;text-align:center;'>Start Date</td>";
                    echo "<td style= 'width:2em;text-align:center;'>End Date</td>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                    echo "<div class='container wrap inner_table'>";
                    echo "<table class='table table-bordered'>";
                    echo "<tbody align='center'>";
                    echo "<tr>";
                    while ($row = $resultOfPlan->fetch_assoc()) {
                        $u_data = "select user_name,course_id,ug_id from USERS where user_id= '" . $row['user_id'] . "'";
                        $Ru_date = mysqli_query($conn, $u_data);
                        $FUResults = mysqli_fetch_array($Ru_date);
                        
                        $c_date = "select course_title from COURSE where course_id='" . $FUResults['course_id'] . "'";
                        $Rc_date = mysqli_query($conn, $c_date);
                        $FCResults = mysqli_fetch_array($Rc_date);
                        $course = $FCResults['course_title'];
                        
//                        $ug_plan= "select plan_id from USER_GROUP where ug_id= '".$row['ug_id']."'";
//                        $Rug_plan = mysqli_query($conn, $ug_plan);
//                        $FRug_plan = mysqli_fetch_array($Rug_plan);
//                        
                      
//                        echo $plan;
//                        echo "plan id is ".$FRplan['plan_id'];
//                        echo "calltype id is ".$FRplan['calltype_id'];
//                        $getctId= "select calltype_id from WHITELIST_CONTACTS where user_id = '".$row['user_id']."'";
//                        $RgetctId = mysqli_query($conn, $getctId);
//                        $FRgetctId = mysqli_fetch_array($RgetctId);
                        
                        $calltype_name= "select calltype_name from CALL_TYPE where calltype_id ='".$row['calltype_id']."'";
                        $Rcalltype_name = mysqli_query($conn, $calltype_name);
                        $FRcalltype_name = mysqli_fetch_array($Rcalltype_name);
//                        echo $FRcalltype_name['calltype_name'];
                        
                        $plan= "select * from PLAN where calltype_id= '".$row['calltype_id']."';";
                        $Rplan = mysqli_query($conn, $plan);
                        $FRplan = mysqli_fetch_array($Rplan);
                        
                        $plan_name = "select plan_name from PLAN_MASTER where plan_id= '" . $FRplan['plan_id'] . "'";
                        $Rplan_name = mysqli_query($conn, $plan_name);
                        $FRplan_name = mysqli_fetch_array($Rplan_name);
                        
                        
                        
                        echo "<td style='width:3em;'>" . $row['user_id'] . "</td>";
//                        echo "<td style='width:2em;'>" . $Recharger_name . "</td>";
                        echo "<td style='width:3em;'>" . $FUResults['user_name'] . "</td>";
                        echo "<td style='width:3em;'>" . $course. "</td>";
                        echo "<td style='width:3em;'>" . $FRplan_name['plan_name'] . "</td>";
                        echo "<td style='width:3em;'>" . $FRcalltype_name['calltype_name']. "</td>";
                        echo "<td style='width:2em;'>" . $FRplan['charge_paise'] . "</td>";
                        echo "<td style='width:2em;'>" . $FRplan['duration_sec'] . "</td>";
                        echo "<td style='width:2em;'>" . $FRplan['start_date'] . "</td>";
                        echo "<td style='width:2em;'>" . $FRplan['end_date'] . "</td>";
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
                if ((from_date !== '' && to_date !== '') || user_id !== '')
                {
                    $.ajax({
                        url: "RechargeFilter.php",
                        method: "POST",
                        data: {from_date: from_date, to_date: to_date, user_id: user_id},
                        success: function (data)
                        {
                            $('#rechargetable').html(data);
                        }
                    });
                } else
                {
                    alert("I can not Filter on Empty");
                }
            });
        });
    </script>
</html>