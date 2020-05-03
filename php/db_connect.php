<?php
function OpenCon()
 {
 $dbhost = "10.35.47.224:3306";
 $dbuser = "k96125_pvgraf_db";
 $dbpass = "EVx8TX99WLCt";
 $db = "k96125_pvgraf";
 $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);

 return $conn;
 }

function CloseCon($conn)
 {
 $conn -> close();
 }

?>