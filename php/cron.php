<?php
set_time_limit(3);
include 'db_connect.php';
echo "test connection";

$beginn = microtime(true);
//$path = $_SERVER["DOCUMENT_ROOT"];
$path = dirname(__DIR__);


$conn = OpenCon();
echo "Connected Successfully";
$timestamp = time();
$hour =  date("G", $timestamp);
$datum = date("ymd", $timestamp);
$gestern = date("ymd", strtotime("-1 days"));

if($hour<3) {
	$filename = $path."/Daten/min".$gestern.".csv";
} else {
	$filename = $path."/Daten/min".$datum.".csv";
}

$fp = fopen($filename, "r");

$firstRow = true;
while( !feof($fp) ) {
  if( !$line = fgetcsv($fp, 1000, ';', '"')) {
	 continue;
  }
  if($firstRow) {
	 $firstRow = false;
	 continue;
  }


	$date = explode('.',$line[0]);
	$mysql_date = $date[2] . '-' . $date[1] . '-' . $date[0];
	$datetime = $mysql_date . ' ' . $line[1];

	$importSQL = "INSERT INTO `sandbox` (`id`, `date_recorded`, `date_added`, `INV1_Pac`, `INV1_DayEnergy`, `INV1_Status`, `INV1_Pdc_STR1`, `INV1_Pdc_STR2`, `INV1_Udc_STR1`, `INV1_Udc_STR2`, `INV1_Temp`, `INV2_Pac`, `INV2_DayEnergy`, `INV2_Status`, `INV2_Pdc_STR1`, `INV2_Pdc_STR2`, `INV2_Udc_STR1`, `INV2_Udc_STR2`, `INV2_Temp`, `INV3_Pac`, `INV3_DayEnergy`, `INV3_Status`, `INV3_Pdc_STR1`, `INV3_Pdc_STR2`, `INV3_Udc_STR1`, `INV3_Udc_STR2`, `INV3_Temp`) VALUES (NULL, '$datetime', CURRENT_TIMESTAMP, '$line[3]', '$line[4]', '$line[5]','$line[6]', '$line[7]', '$line[8]','$line[9]', '$line[10]', '$line[12]','$line[13]', '$line[14]', '$line[15]','$line[16]', '$line[17]', '$line[18]','$line[19]', '$line[21]', '$line[22]','$line[23]', '$line[24]', '$line[25]','$line[26]', '$line[27]', '$line[28]')";
	echo $importSQL;
	$conn->query($importSQL); 
}

fclose($fp);


CloseCon($conn);
?>

