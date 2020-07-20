<!--                                   AUM SRI SAI RAM                          -->
<!DOCTYPE html>
<?php
 include_once '../Controller.php';
        if ($_SESSION['bool'] != 1) {
            header('Location:../ATSLogin.php');
            exit();
        }  
//        include_once '../Plan/Openmenu.php';
?>
<html>
    <head>
        <title>Whitelist Contacts</title>
    </head>
    
    <!--<link rel="stylesheet" href="style.css" type="text/css">-->
    <link rel="stylesheet" href="../StyleSheets/stylingforcontacts.css" type="text/css" media="all">
    <link rel="stylesheet" type="text/css" href="../StyleSheets/bootstrap.css"/>
    <link rel=" stylesheet" href="../StyleSheets/bootstrap-theme.min.css">
    <link rel=" stylesheet" href="../StyleSheets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../StyleSheets/bootstrap.min.css">
    <link href="../StyleSheets/infragistics.theme.css" rel="stylesheet" >
    <link href="../StyleSheets/infragistics.css" rel="stylesheet" >
    <script type="text/javascript" src="../JSfiles/jquery.min.js"></script>
    <script type="text/javascript" src="../JSfiles/Security.js"></script>
    <script type="text/javascript" src="../JSfiles/bootstrap.min.js"></script>
    <script src="../JSfiles/jquery-ui.min.js"></script>
    <script src="../JSfiles/infragistics.core.js"></script>
    <script src="../JSfiles/infragistics.lob.js"></script>
    <script src="../JSfiles/wl_validations.js"></script>
    <script language="javascript" src="contacts.js"></script>

    <script language="JavaScript">
        function edit_contact(user_id, phone_number)
        {
            //var oldphonenumber= phone_number;

            var relation_id = 'relation_' + user_id + phone_number; // To read the present mark from div 
            var data_relation = 'data_relation' + user_id + phone_number;  // To assign id value to text box 

            var pname_id = 'p_name_' + user_id + phone_number;
            var data_pname = 'data_pname' + user_id + phone_number;

            var phone_id = 'phone_number_' + user_id + phone_number;
            var data_phone = 'data_phone' + user_id + phone_number;

            var calltype_id = 'calltype_id_' + user_id + phone_number;
            var data_calltype = 'data_calltype' + user_id + phone_number;
            var calltype_name = 'calltype_name_' + user_id + phone_number;
            //    
            var speeddial_id = 'speed_dial_' + user_id + phone_number;
            var data_speeddial = 'data_speeddial' + user_id + phone_number;

            var startdate_id = 'start_date_' + user_id + phone_number;
            var data_startdate = 'data_startdate' + user_id + phone_number;

            var enddate_id = 'end_date_' + user_id + phone_number;
            var data_enddate = 'data_enddate' + user_id + phone_number;


            var sid = 's' + user_id + phone_number;
            var relation = document.getElementById(relation_id).innerHTML; // Read the present relation
            document.getElementById(relation_id).innerHTML = "<input class='form-control' title= '" + relation + "' size= '5' type=text id='" + data_relation + "' value='" + relation + "'>"; // Display text input

            var pname = document.getElementById(pname_id).innerHTML; // Read the present person name
            document.getElementById(pname_id).innerHTML = "<input class='form-control' size= '5' type=text id='" + data_pname + "' value='" + pname + "'>"; // Display text input 

            var phonenumber = document.getElementById(phone_id).innerHTML; // Read the present phone number
            document.getElementById(phone_id).innerHTML = "<input class='form-control' onkeypress='return isNumberOnly(event)' size= '7' type=text id='" + data_phone + "' value='" + phonenumber + "'>"; // Display text input 
<?php
require "./config.php"; // MySQL connection string
$optstr = "";
foreach ($dbo->query("select calltype_id, calltype_name from CALL_TYPE") as $row) {
    $optstr.= "<option value='" . $row['calltype_id'] . "'>" . $row['calltype_name'] . "</option>";
}
?>
            var calltype = document.getElementById(calltype_id).innerHTML; // Read the present call type
            document.getElementById(calltype_id).innerHTML = "<select class='form-control' id='" + data_calltype + "'><?php echo $optstr; ?></select>";//"<input class='form-control' size= '5' type=text id='" + data_calltype + "' value='" + calltype + "'>"; // Display text input 
            document.getElementById(calltype_id).style.visibility = "visible";
            document.getElementById(data_calltype).value = calltype;
            document.getElementById(calltype_name).style.visibility = "hidden";

            var speeddial = document.getElementById(speeddial_id).innerHTML; // Read the present speeddial digit
            document.getElementById(speeddial_id).innerHTML = "<input class='form-control' onkeypress='return isNumberOnly(event)' size= '2' type=text id='" + data_speeddial + "' value='" + speeddial + "'>"; // Display text input 
            var start = document.getElementById(startdate_id).innerHTML; // Read the present start date
            document.getElementById(startdate_id).innerHTML = "<input class='start_date' size= '9' type=text id='" + data_startdate + "' value='" + start + "'>"; // Display text input 

            var end = document.getElementById(enddate_id).innerHTML; // Read the present end date
            document.getElementById(enddate_id).innerHTML = "<input class='end_date' size= '9' type=text id='" + data_enddate + "' value='" + end + "'>"; // Display text input 

//            document.getElementById(sid).innerHTML = "<input type=button value=Update onclick=ajax('" + user_id + "','" + phone_number + "');>"; // Add different color to background
            document.getElementById(sid).innerHTML = "<button type=button class= 'btn btn-warning btn-xs' onclick=update(" + user_id + "," + phone_number + ")>Update</button>";
            $(".start_date").datepicker({
                dateFormat: 'dd-mm-yy',
                showOtherMonths: true,
                selectOtherMonths: true,
                autoclose: true,
                changeMonth: true,
                changeYear: true,
                //gotoCurrent: true,
                orientation: "top"
            });
            $(".end_date").datepicker({
                dateFormat: 'dd-mm-yy',
                showOtherMonths: true,
                selectOtherMonths: true,
                autoclose: true,
                changeMonth: true,
                changeYear: true,
                //gotoCurrent: true,
                orientation: "top"
            });
        } // end of function
    </script>
    <body>
        <header class="headercontrol">
            <h2 class="textcontrol">Whitelist Contacts</h2>
            <a href="../Administrator.php" class="homebutton btn btn-info" ><img src="../IMG/home.png" width="30px" height="30px"></a>
<!--            <div align="center">  
                <button name="create_excel" id="create_excel" class="btn btn-info">Export</button>  
            </div>-->
        <?php    include_once '../whitelist_contacts/Openmenu.php'; ?>
        <img style="margin-top: -5%; margin-left: 5%; position: absolute;" src="../IMG/dialin_logo.png" width="9%" height="8%">
        </header>


        <div class="container">
            <table>
                <tr>
                    <td class="arrangeuid">
                        <input  type="text" size= "10" name="user_id" id="user_id" class="form-control" placeholder="User Id" />
                    </td>
                    <td class="arrangesrc">
                        <input  type="text" size="13" name="user_id" id="phonenumber" class="form-control" placeholder="Contact Number" />
                    </td>                  
                    <td class="arrangefilter">
                        <input type="button" name="filter" id="filter" value="Filter" class="btn btn-info fa fa-filter" />
                        <!--<button class= 'btn btn-primary'>Filter<span class='fa fa-filter'></span></button>-->
                    </td>
                    <td class="arrangenew">
                        <button class= 'btn btn-info' data-target=#myModal data-toggle=modal>Add Contact <span class='fa fa-plus'></span></button>
                    </td>

                </tr>
            </table>
                <?Php
                echo "<div id=\"msgDsp\" STYLE=\" FONT-SIZE: 12px;font-family: Verdana;border-style: solid;border-width: 1px;"
                . "border-color:white;padding:0px;height:20px;width:250px;top:10px;z-index:1\">  </div>";

                $whitelistcontacts_qry = "SELECT a.user_id,b.user_name,c.course_title,a.relation,a.p_name,a.phone_number,"
                        . "d.o_prefix,d.i_prefix,d.calltype_id,d.calltype_name,a.speed_dial,a.start_date,a.end_date from WHITELIST_CONTACTS "
                        . "AS a JOIN USERS AS b JOIN COURSE AS c JOIN CALL_TYPE AS d WHERE a.calltype_id=d.calltype_id AND "
                        . "a.user_id=b.user_id AND b.course_id=c.course_id";
                //echo "<button class= 'btn btn-info' data-target=#myModal data-toggle=modal>Add Contact <span class='fa fa-plus'></span></button>";
                ?>
            <div id="order_table">
            <?php
                echo "<div class='wrap'>";
                echo "<table class='table head'>"
                . "<thead align='center'>"
                . "<tbody>"
                . "<tr>"
                . "<th style= 'width:1em; text-align: center'>UserID</th>"
                . "<th style= 'width:3em; text-align: center'>Name</th>"
                . "<th style= 'width:3em; text-align: left'>Course</th>"
                . "<th style= 'width:2em; text-align: left'>Relation</th>"
                . "<th style= 'width:2em; text-align: left'>Contact Name</th>"
                . "<th style= 'width:2.5em; text-align: left'>Contact Number</th>"
                . "<th style= 'width:0.5em; text-align: left'>I/O-Prefix</th>"
//                . "<th style= 'width:0.5em; text-align: center'>I-Prefix</th>"
                . "<th style= 'width:3em; text-align: center'>Calltype</th>"
                . "<th style= 'width:0.5em; text-align: center'>Speed dial</th>"
                . "<th style= 'width:2em; text-align: center'>Start date</th>"
                . "<th style= 'width:2em; text-align: left'>end date</th>"
                . "<th style= 'width:0.5em; text-align: center'>Edit</th>"
                . "<th style= 'width:0.5em; text-align: center'>Delete</th>"
                . "</tr>"   //the columns of the table are arranged
                . "</tbody>"
                . "</thead>"
                . "</table>";
                echo "</div>";
                echo "<div class='wrap inner_table'>";
                echo "<table class='table table-bordered'>";
                echo "<tbody align='center'>";
                foreach ($dbo->query($whitelistcontacts_qry) as $row) {
                    $sid = 's' . $row['user_id'] . $row['phone_number'];

                    //these ids are used
                    $user_id = 'user_id_' . $row['user_id'] . $row['phone_number'];
                    $username_id = 'user_name_' . $row['user_id'] . $row['phone_number'];
                    $usercourse_id = 'course_title_' . $row['user_id'] . $row['phone_number'];
                    $relation_id = 'relation_' . $row['user_id'] . $row['phone_number'];
                    $contactname_id = 'p_name_' . $row['user_id'] . $row['phone_number'];
                    $contactnumber_id = 'phone_number_' . $row['user_id'] . $row['phone_number'];
                    $oprefix_id = 'o_prefix_' . $row['user_id'] . $row['phone_number'];
                    $iprefix_id = 'i_prefix_' . $row['user_id'] . $row['phone_number'];
                    $calltypename_id = 'calltype_name_' . $row['user_id'] . $row['phone_number'];
                    $calltype_id = 'calltype_id_' . $row['user_id'] . $row['phone_number'];
                    $speeddial_id = 'speed_dial_' . $row['user_id'] . $row['phone_number'];
                    $startdate_id = 'start_date_' . $row['user_id'] . $row['phone_number'];
                    $enddate_id = 'end_date_' . $row['user_id'] . $row['phone_number'];

                    $start = $row['start_date'];
                    list($sy, $sm, $sd) = explode("-", $start);
                    $startdate = $sd . "-" . $sm . "-" . $sy;

                    $end = $row['end_date'];
                    list($ey, $em, $ed) = explode("-", $end);
                    $enddate = $ed . "-" . $em . "-" . $ey;

                    //the values in the table are printed
                    echo "<tr><td style= 'width:1em;'><div id=$user_id >$row[user_id]</div></td>"
                    . "<td style= 'width:3em;'><div id=$username_id>$row[user_name]</div></td>"
                    . "<td style= 'width:3em;'><div id=$usercourse_id>$row[course_title]</div> </td>"
                    . "<td style= 'width:2em;'><div id=$relation_id>$row[relation]</div> </td> "
                    . " <td style= 'width:2em;'><div id=$contactname_id>$row[p_name]</div></td>"
                    . " <td style= 'width:3em;'><div id=$contactnumber_id>$row[phone_number]</div></td>"
                    . " <td style= 'width:0.5em;'><div id=$iprefix_id>$row[i_prefix]</div></td>"
                    . " <td style= 'width:0.5em;'><div id=$oprefix_id>$row[o_prefix]</div></td>"
                    . " <td style= 'width:3em;'><div id=$calltypename_id>$row[calltype_name]</div><div id=$calltype_id style=\"visibility:hidden\">$row[calltype_id]</div></td>"
                    . " <td style= 'width:0.5em;'><div id=$speeddial_id>$row[speed_dial]</div></td> "
                    . "<td style= 'width:2em;'><div id=$startdate_id>$startdate</div></td>"
                    . " <td style= 'width:2em;'><div id=$enddate_id>$enddate</div></td> "
                    . "<td style= 'width:0.5em;'><div id=$sid><button type=button class= 'btn btn-warning' onclick=edit_contact('$row[user_id]','$row[phone_number]')><span class='fa fa-edit'></span></button></div></td>"
                    //. "<td><div id=$sid><button type=button class= 'btn btn-danger' onclick=deletecontact($row[user_id],$row[phone_number])><span class='fa fa-trash-o'></span></button></div></td>"
                    . "<td style= 'width:0.5em;'><div id=$sid><button type=button class= 'btn btn-danger' onclick=del($row[user_id],$row[phone_number])><span class='fa fa-trash-o'></span></button></div></td>"
                    . "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
//                mysqli_close($dbo);
                ?>
            </div>
        </div>
        <!--This is used for the new contact-->
        <div id="newcontact" class="overlay">
            <form method="POST" action="Addcontact.php" name="newcontactform">

                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close"
                                        data-dismiss="modal" aria-hidden="true">
                                    <!--&times;-->
                                </button>
                                <h4 class="modal-title" id="myModalLabel">
                                    New Whitelist Contact
                                </h4>
                            </div>
                            <div class="modal-body">

                                <input type="text"  name="user_id" id="user_id" class="form-control" placeholder="Enter User Id" onkeypress="return isNumberOnly(event)"><br>
                                <input type="text"  name="phone" id="phone" class="form-control" placeholder="Enter Contact Number" onkeypress="return isNumberOnly(event)"><br>
                                <input type="text"  name="relation" id="relation" class="form-control" placeholder="Enter Relation"><br>
                                <input type="text"  name="name" id="name" class="form-control" placeholder="Enter Contact Name"><br>
                                <input type="text"  name="speeddial" id="speeddial" class="form-control" placeholder="Enter Speed dial" onkeypress="return isNumberOnly(event)"><br>
                                <select class="calltypes" name="cType">
                                    <option class="look">Call Type</option>
                                    <?php
                                    $sql = "SELECT calltype_name FROM CALL_TYPE";
                                    foreach ($dbo->query($sql) as $row) {
                                        echo "<option class='lookandfeel' value=" . $row['calltype_name'] . ">" . $row['calltype_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                                <input name= "start" class='form-inline from' size= '12' type=text id="startdate" placeholder="Enter Start Date">
                                <input name= "end" class='form-inline to' size= '12' type=text id="enddate" placeholder="Enter End Date">
                            </div>

                            <div class="modal-footer">
                                <span style="color: red;font-size: 12px;margin-bottom: 2%;margin-left: -55%; position: absolute;"><?php
                                    if (isset($_GET['msg'])) {
                                        echo $_GET['msg'];
                                    };
                                    ?></span>
                                <button type="button" class="btn btn-primary" onclick='erase()' data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" onclick="AddContact()">Add Contact</button>
                            </div>
                        </div> 
                    </div> 
                </div>
            </form>
        </div>
        <script type="text/javascript">
            $(".from").datepicker({
                dateFormat: 'dd-mm-yy',
                showOtherMonths: true,
                selectOtherMonths: true,
                autoclose: true,
                changeMonth: true,
                changeYear: true,
                //gotoCurrent: true,
                orientation: "top"
            });
            $(".to").datepicker({
                dateFormat: 'dd-mm-yy',
                showOtherMonths: true,
                selectOtherMonths: true,
                autoclose: true,
                changeMonth: true,
                changeYear: true,
                //gotoCurrent: true,
                orientation: "top"
            });
        </script>
        <!--end of new contact-->

        <script type="text/javascript" >
            $(document).ready(function () {
                $('#filter').click(function () {
                    //                alert('sairam');
                    var user_id = $('#user_id').val();
                    var phonenumber = $('#phonenumber').val();
                    //                alert(user_id);
                    if (user_id !== '' || phonenumber !== '')
                    {
                        $.ajax({
                            url: "ContactsFilter.php",
                            method: "POST",
                            data: {phonenumber: phonenumber, user_id: user_id},
                            success: function (data)
                            {   
                                $("#user_id").val("");
                                $("#phonenumber").val("");
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
            $(document).ready(function () {
                $('#create_excel').click(function () {
                    var excel_data = $('#order_table').html();
                    var page = "ContactsFilter.php?data=" + excel_data;
                    window.location = page;
                });
            });
        </script>
    </body>
</html>