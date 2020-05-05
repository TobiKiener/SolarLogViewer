<?php
set_time_limit(2);
$beginn = microtime(true);
include 'db_connect.php';

$timestamp = time();

//$path = $_SERVER["DOCUMENT_ROOT"];
$path = dirname(__DIR__);

$conn = OpenCon();
echo "Connected Successfully <br>";

//Get  latest entry in databse
$getLatest = "SELECT UNIX_TIMESTAMP(`date_recorded`) FROM sandbox ORDER BY `date_recorded` DESC LIMIT 1";

$result = $conn->query($getLatest);
$latestDate = $result->fetch_row()[0];

$k=0;
while(true) {
	$k += 1;
    $filedate = date("ymd", $latestDate);
	$filename = $path."/Daten/min".$filedate.".csv";
	echo "Import " . $filename . "<br>";
	
	if (file_exists($filename)){
		echo "File exist.<br>";
		$fp = fopen($filename, "r");
		$currentRow = 0;
		while( !feof($fp) ) {
		  if( !$line = fgetcsv($fp, 1000, ';', '"')) {
			 continue;
		  }
		  $currentRow +=1;		
		  //Skip header
		  if($currentRow==1) {
			 continue;
		  }


			$date = explode('.',$line[0]);
			$mysql_date = $date[2] . '-' . $date[1] . '-' . $date[0];
			$datetime = $mysql_date . ' ' . $line[1];

			$importSQL = "INSERT INTO `sandbox` (`id`, `date_recorded`, `date_added`, `INV1_Pac`, `INV1_DayEnergy`, `INV1_Status`, `INV1_Pdc_STR1`, `INV1_Pdc_STR2`, `INV1_Udc_STR1`, `INV1_Udc_STR2`, `INV1_Temp`, `INV2_Pac`, `INV2_DayEnergy`, `INV2_Status`, `INV2_Pdc_STR1`, `INV2_Pdc_STR2`, `INV2_Udc_STR1`, `INV2_Udc_STR2`, `INV2_Temp`, `INV3_Pac`, `INV3_DayEnergy`, `INV3_Status`, `INV3_Pdc_STR1`, `INV3_Pdc_STR2`, `INV3_Udc_STR1`, `INV3_Udc_STR2`, `INV3_Temp`) VALUES (NULL, '$datetime', CURRENT_TIMESTAMP, '$line[3]', '$line[4]', '$line[5]','$line[6]', '$line[7]', '$line[8]','$line[9]', '$line[10]', '$line[12]','$line[13]', '$line[14]', '$line[15]','$line[16]', '$line[17]', '$line[18]','$line[19]', '$line[21]', '$line[22]','$line[23]', '$line[24]', '$line[25]','$line[26]', '$line[27]', '$line[28]')";
			$conn->query($importSQL);
			
			
			//Dont run forever
			if ($currentRow>999) {
				
				echo "Exceeded File Limits (>999lines)<br>";
				break;
			}
		}
		fclose($fp);
	}else{
		echo "File does not exist.<br>";
	}
	//Stop after todays file
	if (date('Ymd') == date('Ymd', $latestDate)) {
		break;
	}
	//Dont run forever
	if ($k>9999) {
		break;
	}
	//Add 1 day to import next day file
	$latestDate += (60*60*24);
}  



CloseCon($conn);
$dauer = microtime(true) - $beginn; 
echo "Finished<br>";
echo "Runtime: $dauer Sek.<br>";
?>

