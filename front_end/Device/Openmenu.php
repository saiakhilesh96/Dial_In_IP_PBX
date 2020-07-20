<?php
if ($_SESSION['bool'] != 1) {
    header('Location:ATSLogin.php');
    exit();
}
?>
<html>
    <head>
        <title>OpenMenu</title>
    </head>
    <link rel="stylesheet"  href="../StyleSheets/Menustyle_1.css" type="text/css" media="all">
    <script type="text/javascript" src="../JSfiles/Security.js" ></script>
    <script type="text/javascript" src="../JSfiles/jquery.js" ></script>
    <body>
        <div id="box">
            <div id="mySidenav" class="sidenav">
                <a href="" class="closebtn text" onclick="closeNav()">&times;</a>
                <!--<a href="">Configure</a>-->
                <!--                <a href="#">UserAccount</a>
                                <a href="#">TimeSlots</a>-->
                <a class="text" href="../Plan/PlanSubscribe.php">Plan Subscription</a>
                <a class="text" href="DeviceAllocation.php">Device Allocation</a>
                <a class="text" href="../Recharges/Recharges.php">Recharges</a>
                <a class="text" href="../whitelist_contacts/Contacts.php">WhiteList Contacts</a>
                <a class="text" href="../UserCallDetails/UserCall_details.php">Call Logs</a>
                <a class="text" href="../UserCallDetails/Live_calls.php">Live Calls</a>
                <a class="text" href="../UserCallDetails/Call_Summary.php">Summary Of Calls</a>
                <a class="text" href="../Timeslots/Recurring.php">Recurring TimeSlots</a>
                <a class="text" href="../Timeslots/NonRecurring.php">NonRecurring TimeSlots</a>
                <!--<a href="Rules/Rules.php">Rules</a>-->
                <!--                <div class="rulesdropdown">
                                    <a class="rulesbtn">Call Details</a>
                                    <div class="rulesdropdown-content">
                                        <a class="design" href="UserCallDetails/UserCall_details.php">Call Logs</a>
                                        <a class="design" href="UserCallDetails/Live_calls.php">Live Calls</a>
                                        <a class="design" href="UserCallDetails/Call_Summary.php">Summary Of Calls</a>
                                    </div>
                                </div>-->
                <!--<a class="text" href="../Timeslots/Recurring.php">Time Slot</a>-->
<!--                <div class="rulesdropdown">
                    <a class="rulesbtn">Time Slots</a>
                    <div class="rulesdropdown-content">
                        <a class="design" href="Timeslots/Recurring.php">Recurring</a>
                        <a class="design" href="#">Non Recurring</a>
                        <a class="design" href="UserCallDetails/Call_Summary.php">Summary Of Calls</a>
                    </div>
                </div>-->
            </div></div>    
        <span class="OpenBtn" id="link" title="Open Menu" onclick="openNav()">&#9776</span>

        <script>
            function openNav() {
                document.getElementById("mySidenav").style.width = "240px";
            }

            function closeNav() {
                document.getElementById("mySidenav").style.width = "0";
            }
            var box = $('#box');
            var link = $('#link');

            link.click(function () {
                box.show();
                return false;
            });

            $(document).click(function () {
                box.hide();
            });

            box.click(function (e) {
                e.stopPropagation();
            });
        </script>
    </body>
</html>