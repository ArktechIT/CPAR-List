<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	//$path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
	$javascriptLib = "../Common Data/Libraries/Javascript/";
	$templates = "../Common Data/Templates/";
	set_include_path($path);	
	include('PHP Modules/mysqliConnection.php');
	ini_set("display_errors", "on");

    $q = $_GET['q'];
	$my_data=$db->real_escape_string($q);
	 
	//$sql = "SELECT firstName, surName FROM hr_employee WHERE status = 1 AND surName LIKE '$my_data%' ORDER BY surName ASC";
	$sql = "SELECT firstName, surName FROM hr_employee WHERE (status = 1 or status = 2) AND (surName LIKE '$my_data%' or firstName LIKE '$my_data%') ORDER BY surName ASC";
    $result = $db->query($sql);
	while($row = $result->fetch_array())
	{
		echo $row['surName'].", ".$row['firstName']."\n";	
	}
?>
