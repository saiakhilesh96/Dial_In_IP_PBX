<!DOCTYPE html>
<?php
include_once '../Controller.php';
if ($_SESSION['bool'] != 1) {
    header('Location:../ATSLogin.php');
    exit();
}
//include_once './Moneybox.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Recharges</title>
    </head>
    
    
    <link rel="stylesheet" href="../StyleSheets/MoneyboxPopup.css">
    <link rel="stylesheet" href="../StyleSheets/Recharge.css" type="text/css" media="all">
    <!--<link rel="stylesheet" href="StyleSheets/stylingforcalllogs.css" type="text/css" media="all">-->
    <link rel="stylesheet" href="../StyleSheets/bootstrap.min.css">
    <?php include_once '../Recharges/Openmenu.php'; ?>
    <!--<link rel="stylesheet"  href="StyleSheets/StyleSheet.css">-->
    <script src="../JSfiles/M_validation.js" type="text/javascript"></script>
    <script type="text/javascript" src="../JSfiles/jquery.min.js"></script>
    <script type="text/javascript" src="../JSfiles/bootstrap.min.js"></script>
    <script type="text/javascript" src='../JSfiles/Security.js' ></script>
    <script src="../JSfiles/jquery-ui.min.js"></script>
    <link href="../StyleSheets/infragistics.theme.css" rel="stylesheet" />
    <link href="../StyleSheets/infragistics.css" rel="stylesheet" />
    <style type="text/css">
        /*    body{
                font-family: Arail, sans-serif;
            }*/
        /* Formatting search box */
        .search-box{
            width: 215px;
            position: relative;
            display: inline-block;
            font-size: 14px;
        }
        .search-box input[type="text"]{
            height: 32px;
            padding: 5px 10px;
            border: 1px solid #CCCCCC;
            font-size: 14px;
        }
        .result{
            position: absolute; 
            left: 0;
            height: 203px;
            overflow: auto;
        }
        .search-box input[type="text"], .result{
            width: 100%;
            box-sizing: border-box;
        }
        /* Formatting result items */
        .result p{
            margin: 0;
            padding: 7px 10px;
            border: 1px solid #CCCCCC;
            border-top: none;
            cursor: pointer;
            background-color: #DCE6F7;
        }
        .result p:hover{
            background: #819FF7;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.search-box input[type="text"]').on("keyup input", function () {
                /* Get input value on change */
                var inputVal = $(this).val();
                var resultDropdown = $(this).siblings(".result");
                if (inputVal.length) {
                    $.post("MoneyBox.php", {term: inputVal}).done(function (data) {
                        // Display the returned data in browser
                        resultDropdown.html(data);
                    });
                } else {
                    resultDropdown.empty();
                }
            });

            // Set search input value on click of result item
//            $(document).on("click", ".result p", function () {
//                $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
//                $(this).parents(".result").empty();
//            });
        });
    </script>
    <body>
        <header class="headercontrol">
            <h2 class="textcontrol">Recharges</h2>
            <a href="#moneybox" ><input class="moneybtn btn btn-info"  type="button" value="MoneyBox"></a>
            <a href="../Administrator.php" class="homebutton btn btn-info" ><img src="../IMG/home.png" width="30px" height="30px"></a>
            <div align="center">  
                <button name="create_excel" id="create_excel" class="btn btn-info">Export</button>  
            </div>
            
            <img style="margin-top: -5%; margin-left: 5%; position: absolute;" src="../IMG/dialin_logo.png" width="9%" height="8%">
        </header>
        <!--<span style="color: red"><?php // echo $err;    ?></span>-->
        <div id="moneybox" class="overlay">
            <div class="passpopup">
                <h3 style="text-align: center; font-family: swaseeda; color:#fff">Money Box</h3><hr />
                <a class="myclosebtn"  href="">&Chi;</a>
                <form method="post" name="MoneyForm" action="Moneybox.php">
                    <span style="color: red;font-size: 12px;margin-top: 23%;position: absolute;"><?php
                        if (isset($_GET['msg'])) {
                            echo $_GET['msg'];
                        };
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
        </div>
        <br />
        <div class="container">
            <table>
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
            </table>
            <?php
            include_once '../DBconnection.php';
            $recharges = "select * from RECHARGES order by recharge_time desc";
            $rechargeresult = mysqli_query($conn, $recharges);
            ?>
            <div id="rechargetable">
                <?php
                if ($rechargeresult->num_rows > 0) {
                    echo "<div class='container wrap'>";
                    echo "<table class='table head'>";
                    echo "<thead align='center'>";
                    echo "<tbody>";
                    echo "<tr>";
                    echo "<td style= 'width:3em;text-align:center;'>Recharge Time</td>";
//                    echo "<td style= 'width:2em;'>Recharge Owner</td>";
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
//                        echo "<td style='width:2em;'>" . $Recharger_name . "</td>";
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
                    alert("Please Select Data");
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#create_excel').click(function () {
                var excel_data = $('#rechargetable').html();
                var page = "RechargeFilter.php?data=" + excel_data;
                window.location = page;
            });
        });
    </script>
</html>