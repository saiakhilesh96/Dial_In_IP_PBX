<?php
//$host_name = "localhost";
//$database = "DIALIN";      // Change your database name
//$username = "root";     // Your database user id 
//$password = "sairam";   // Your database password

$ini_array = parse_ini_file("../dbproperties.ini");
$host_name= $ini_array['host'];
$database=$ini_array['database'];
$username=$ini_array['user']; 
$password=$ini_array['pin'];
//////// Do not Edit below /////////
try 
{
	$dbo = new PDO('mysql:host='.$host_name.';dbname='.$database, $username, $password);
        
} 
catch (PDOException $e) 
{
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
?>
