<?php

// include Database connection file 
//include("db_connection.php");
include_once '../DBconnection.php';

// Design initial table header 
$data = '<table class="table table-bordered table-striped">
                        <tr>
                            <th>User ID</th>
                            <th>User Name</th>
                            <th>Batch</th>
                            <th>Course</th>
                            <th>Group</th>
                            <th>Room</th>
                            <th>Account Balance</th>
                            <th>Alloted duration</th>
                            <th>Time spent</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>';

//$query = "SELECT * FROM PLAN";
//$query = "SELECT b.ts_id,b.ts_name,a.weekday,a.start_time,a.end_time FROM RECURRING_TS AS a JOIN TIME_SLOT AS b WHERE a.ts_id=b.ts_id ORDER BY ts_id ASC";
$query = "SELECT a.user_id,a.user_name,a.batch,b.course_title,c.ug_name,a.room_no,a.balance,c.total_user_call_duration,a.time_used,a.access_level,a.status FROM USERS AS a JOIN COURSE AS b JOIN USER_GROUP AS c WHERE a.course_id=b.course_id AND a.ug_id=c.ug_id";

if (!$result = mysqli_query($conn, $query)) {
    exit(mysqli_error($conn));
}

// if query results contains rows then featch those rows 
if (mysqli_num_rows($result) > 0) {
    $number = 1;
    $access = array('1' => "ADMIN", '2' => "STAFF", '3' => "STUDENT");
    $status= array('0' => "INACTIVE", '1' => "ACTIVE");
    while ($row = mysqli_fetch_assoc($result)) {
        $user_type = $row['access_level'];
        $user_status= $row['status'];
        $data .= '<tr id= user' . $number . '>
                <td>' . $row['user_id'] . '</td>
                <td>' . $row['user_name'] . '</td>
                <td>' . $row['batch'] . '</td>
                <td>' . $row['course_title'] . '</td>
                <td>' . $row['ug_name'] . '</td>
                <td>' . $row['room_no'] . '</td>
                <td>' . $row['balance'] . '</td>
                <td>' . $row['total_user_call_duration'] . '</td>
                <td>' . $row['time_used'] . '</td>
                <td>' . $access[$user_type] . '</td>
                <td>' . $status[$user_status] . '</td>
                <td>
                    <button onclick="GetUserDetails(' . $row['user_id'] .  ')" class="btn btn-warning">Edit</button>
                </td>
                <td>
                    <button onclick="DeleteUser(' . $row['user_id'] . ')" class="btn btn-danger">Delete</button>
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