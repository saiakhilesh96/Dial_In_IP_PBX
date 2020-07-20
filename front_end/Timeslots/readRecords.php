<?php

// include Database connection file 
//include("db_connection.php");
include_once '../DBconnection.php';

// Design initial table header 
$data = '<table class="table table-bordered table-striped">
                        <tr>
                            <th>S.No</th>
                            <th>Slot Name</th>
                            <th>Day</th>
                            <th>Starting Time</th>
                            <th>Ending Time</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>';

//$query = "SELECT * FROM PLAN";
$query = "SELECT b.ts_id,b.ts_name,a.weekday,a.start_time,a.end_time FROM RECURRING_TS AS a JOIN TIME_SLOT AS b WHERE a.ts_id=b.ts_id ORDER BY ts_id ASC";

if (!$result = mysqli_query($conn, $query)) {
    exit(mysqli_error($conn));
}

// if query results contains rows then featch those rows 
if (mysqli_num_rows($result) > 0) {
    $number = 1;
    $days = array("SUN" => '0', "MON" => '1', "TUE" => '2', "WED" => '3', "THU" => '4', "FRI" => '5', "SAT" => '6');
    $day = array('0' => "SUN", '1' => "MON", '2' => "TUE", '3' => "WED", '4' => "THU", '5' => "FRI", '6' => "SAT");
    
    while ($row = mysqli_fetch_assoc($result)) {
        $cur_day= $row['weekday'];
        $data .= '<tr id= slot'.$number.'>
                <td>' . $number . '</td>
                <td>' . $row['ts_name'] . '</td>
                <td>' . $day[$cur_day] . '</td>
                <td>' . $row['start_time'] . '</td>
                <td>' . $row['end_time'] . '</td>
                <td>
                    <button onclick="GetRecurringDetails(slot'.$number.','.$row['ts_id'].','.$row['weekday'].')" class="btn btn-warning fa fa-edit"></button>
                </td>
                <td>
                    <button onclick="DeleteRecurringSlot(slot'.$number.',' . $row['ts_id'] . ',' . $row['weekday'] . ')" class="btn btn-danger fa fa-trash-o"></button>
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