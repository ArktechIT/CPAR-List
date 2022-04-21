<?php
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
//$path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
$javascriptLib = "../Common Data/Libraries/Javascript/";
$templates = "../Common Data/Templates/";
set_include_path($path);	
include('PHP Modules/mysqliConnection.php');

$nonConformance = $_POST['nonConformance'];
$sql = "SELECT listId FROM `qc_nonconformance` WHERE nonConformance = '".$nonConformance."'";
$queryNonConformance = $db->query($sql);
if($queryNonConformance AND $queryNonConformance->num_rows > 0)
while($resultNonConformance = $queryNonConformance->fetch_assoc())
{
    $nonConformanceListId = $resultNonConformance['listId'];
}

if($nonConformanceListId == '90')
{
    echo "<input type='text' name='cparMoreDetails' value='' style ='border-radius:5px; height:40px;width:100%;background-color:#fdfd96'>"; 
}
else
{
?>
    <select name = "cparMoreDetails" id="" style = "border-radius:5px; height:40px;width:100%;background-color:#fdfd96;" required>
    <option></option>
<?php


    $sql = "SELECT details FROM `qc_nonconformancedetails` WHERE nonConformanceId = '".$nonConformanceListId."' ORDER BY details ASC";
    $queryNonConformanceDetails = $db->query($sql);
    if($queryNonConformanceDetails AND $queryNonConformanceDetails->num_rows > 0)
    {
        while($resultNonConformanceDetails = $queryNonConformanceDetails->fetch_assoc())
        {
            $nonConformanceDetails = $resultNonConformanceDetails['details'];
            echo "<option>".$nonConformanceDetails."</option>";
        }
    }
}    
?>
</select>