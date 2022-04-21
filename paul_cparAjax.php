<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	//$path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
	$javascriptLib = "../Common Data/Libraries/Javascript/";
	$templates = "../Common Data/Templates/";
	set_include_path($path);	
	include('PHP Modules/mysqliConnection.php');
	
	$sql = "SELECT CONCAT(firstName,' ',surName) AS fullName FROM hr_employee WHERE idNumber LIKE '".$_POST['idValue']."' AND status = 1";
	$query = $db->query($sql);
	if($query AND $query->num_rows > 0)
	{
		$result = $query->fetch_assoc();
		echo $fullName = $result['fullName'];
	}
?>
