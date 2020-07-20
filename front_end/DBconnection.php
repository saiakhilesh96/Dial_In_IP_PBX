<?php
$ini_array = parse_ini_file("dbproperties.ini");
$host_name= $ini_array['host'];
$database=$ini_array['database'];
$username=$ini_array['user']; 
$password=$ini_array['pin'];

//$servername = "localhost";
//$username = "root";
//$password = "sairam";
//$dbname = "DIALIN";
$conn = mysqli_connect($host_name, $username, $password, $database);
//$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
//    print '<script>alert("Succussfully Connected!!!")</script>';
}
