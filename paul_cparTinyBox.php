<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	//$path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
	$javascriptLib = "../Common Data/Libraries/Javascript/";
	$templates = "../Common Data/Templates/";
	set_include_path($path);	
	include('PHP Modules/mysqliConnection.php');
	ini_set("display_errors", "on");
	
	if($_POST['cparId']!='')
	{
		$cparId = $_POST['cparId'];
		?>
		<center>
			<form id='formId' method='POST' action='paul_cparSqlV2.php'></form>
			<input type='hidden' name='cparId' form='formId' value='<?php echo $cparId; ?>' required>
			<label>Assign to:</label>
			<select name='assignEmployee' form='formId' required autofocus>
				<option></option>
				<?php
					$sql = "SELECT departmentId FROM hr_employee WHERE idNumber LIKE '".$_SESSION['idNumber']."' LIMIT 1";
					$queryDepartment = $db->query($sql);
					if($queryDepartment->num_rows > 0)
					{
						$resultDepartment = $queryDepartment->fetch_assoc();
						$departmentId = $resultDepartment['departmentId'];
					}
					
					$sql = "SELECT idNumber, CONCAT(firstName,' ',surName) AS fullName FROM hr_employee WHERE departmentId = ".$departmentId." AND status = 1 AND idNumber != '".$_SESSION['idNumber']."'";
					$query = $db->query($sql);
					if($query AND $query->num_rows > 0)
					{
						while($result = $query->fetch_assoc())
						{
							$idNumber = $result['idNumber'];
							$fullName = $result['fullName'];
							?>
							<option value='<?php echo $idNumber; ?>'><?php echo $fullName; ?></option>
							<?php
						}
					}
				?>
			</select>
			
			<p></p>
			
			<input type='Submit' name='Assign' value='SUBMIT' form='formId'>
		</center>
		<?php
	}
	else
	{
		echo "<center><h1 style='color:red;'>ERROR!</h1></center>";
	}
?>
