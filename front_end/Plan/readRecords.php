<?php
// include Database connection file 
//include("db_connection.php");
include_once '../DBconnection.php';

// Design initial table header 
$data = '<table class="table table-bordered table-striped">
                        <tr>
                            <th>No.</th>
                            <th>Plan Name</th>
                            <th>CallType Name</th>
                            <th>O_prefix</th>
                            <th>I_prefix</th>
                            <th>Charge Paise</th>
                            <th>Duration Sec</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>';

$query = "SELECT * FROM PLAN";

if (!$result = mysqli_query($conn, $query)) {
    exit(mysqli_error($conn));
}

// if query results contains rows then featch those rows 
if (mysqli_num_rows($result) > 0) {
    $number = 1;
    while ($row = mysqli_fetch_assoc($result)) {

        //selecting Plan name from plan master
        $plan_name = "select plan_name from PLAN_MASTER where plan_id= '" . $row['plan_id'] . "'";
        $Rplan_name = mysqli_query($conn, $plan_name);
        $FRplan_name = mysqli_fetch_array($Rplan_name);
        //selecting Calltype Name from Call type
        $calltype_name = "select calltype_name,o_prefix,i_prefix from CALL_TYPE where calltype_id= '" . $row['calltype_id'] . "'";
        $Rcalltype_name = mysqli_query($conn, $calltype_name);
        $FRcalltype_name = mysqli_fetch_array($Rcalltype_name);
        $data .= '<tr id=plan'.$number.'>
                <td>' . $number . '</td>
                <td>' . $FRplan_name['plan_name'] . '</td>
                <td>' . $FRcalltype_name['calltype_name'] . '</td>
                <td>' . $FRcalltype_name['o_prefix'] . '</td>
                <td>' . $FRcalltype_name['i_prefix'] . '</td>
                <td>' . $row['charge_paise'] . '</td>
                <td>' . $row['duration_sec'] . '</td>
                <td>' . $row['start_date'] . '</td>
                <td>' . $row['end_date'] . '</td>
                <td>
                    <button onclick="GetUserDetails(plan'.$number.','.$row['plan_id'].','.$row['calltype_id'].')" class="btn btn-warning fa fa-edit"></button>
                </td>
                <td>                        
                    <button onclick="DeletePlan(plan'.$number.','.$row['plan_id'].','.$row['calltype_id'].')" class="btn btn-danger fa fa-trash-o"></button>
                </td>
            </tr>';
        $number++;
    }
} else {
    // records now found 
    $data .= '<tr><td colspan="6">Records not found!</td></tr>';
}

$data .= '</table>';

echo $data;
?>