<?php
session_start();
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
set_include_path($path);	
include('PHP Modules/mysqliConnection.php');

$sql = "SELECT cparId FROM qc_cpar WHERE listId = ".$_GET['cparId']." ";
$getCPAR = $db->query($sql);
$getCPARResult = $getCPAR->fetch_array();

if($getCPARResult['cparId'] != NULL)
{
	$fileLocation = "../../Document Management System/CPAR Folder/".$getCPARResult['cparId'].".pdf";
	
	header('Content-type: application/pdf');
	header('Content-Disposition: inline; filename='.$getCPARResult['cparId'].'');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: '.filesize($fileLocation));
	header('Accept-Ranges: bytes');

	readfile($fileLocation);
}
?>
