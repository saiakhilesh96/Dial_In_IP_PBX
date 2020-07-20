<?php

// include Database connection file 
//include("db_connection.php");
include_once '../DBconnection.php';

// Design initial table header 
$data = '<table class="table table-bordered table-striped">
                        <tr>
                            <th>S.No</th>
                            <th>Slot Name</th>
                            <th>Start Date</th>
                            <th>Start Time</th>
                            <th>End Date</th>
                            <th>End Time</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>';

//$query = "SELECT * FROM PLAN";
$query = "SELECT b.ts_id,b.ts_name,a.start_date,a.start_time,a.end_date,a.end_time FROM NONRECURRING_TS AS a JOIN TIME_SLOT AS b WHERE a.ts_id=b.ts_id ORDER BY ts_id ASC";

if (!$result = mysqli_query($conn, $query)) {
    exit(mysqli_error($conn));
}

// if query results contains rows then featch those rows 
if (mysqli_num_rows($result) > 0) {
    $number = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $start = $row['start_date'];
        list($sy, $sm, $sd) = explode("-", $start);
        $startdate = $sd . "-" . $sm . "-" . $sy;

        $end = $row['end_date'];
        list($ey, $em, $ed) = explode("-", $end);
        $enddate = $ed . "-" . $em . "-" . $ey;

        $data .= '<tr id= slot' . $number . '>
                <td>' . $number . '</td>
                <td>' . $row['ts_name'] . '</td>
                <td>' . $startdate . '</td>
                <td>' . $row['start_time'] . '</td>
                <td>' . $enddate . '</td>
                <td>' . $row['end_time'] . '</td>    
                <td>
                    <button onclick="GetNonRecurringDetails(slot' . $number . ',' . $row['ts_id'] . ')" class="btn btn-warning fa fa-edit"></button>
                </td>
                <td>
                    <button onclick="DeleteNonRecurringSlot(slot' . $number . ',' . $row['ts_id'] . ')" class="btn btn-danger fa fa-trash-o"></button>
                </td>
            </tr>';
        $number++;
    }
} else {
    //No records 
    $data .= '<tr><td colspan="6">Records not found!</td></tr>';
}

$data .= '</table>';

echo $data;
?>