<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	set_include_path($path);
	include('PHP Modules/mysqliConnection.php');
	include('PHP Modules/anthony_wholeNumber.php');
	include('PHP Modules/anthony_retrieveText.php');
	include('PHP Modules/gerald_functions.php');
	ini_set("display_errors", "on");
	
	$requestData= $_REQUEST;
	$sqlData = isset($requestData['sqlData']) ? $requestData['sqlData'] : '';
	$totalRecords = isset($requestData['totalRecords']) ? $requestData['totalRecords'] : '';
	
	$exportFlag = (isset($_POST['exportFlag'])) ? $_POST['exportFlag'] : '';

	$editFlag = 0;
	$sql = "
		SELECT * FROM system_userpermission a
		INNER JOIN system_permissiondetails b ON b.permissionId = a.permissionId
		WHERE b.permissionName LIKE '%leader%' AND a.idNumber LIKE '".$_SESSION['idNumber']."'
	";
	$query = $db->query($sql);
	if($query AND $query->num_rows > 0)
	{
		$editFlag = 1;
	}
	
	$totalData = $totalRecords;
	$totalFiltered = $totalRecords;
	
	if($exportFlag!='')
	{
		$filename = "CPAR LIST (".date('ymdHis').").xls";
		header('Content-type: application/ms-excel');
		header('Content-Disposition: attachment; filename='.$filename);
		
		?>
		<table class='table table-bordered table-condensed table-striped' id="mainTableId">
			<thead class='w3-indigo thead' style='text-transform:uppercase;'>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L334');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L24');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L28');?></th>
				<!--th style='vertical-align:middle;' class='w3-center'><?php //echo displayText('L566');?></th-->
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L45');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L31');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L351');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L352');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L353');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L267');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L354');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L355');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L336');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L1330');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L348');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L337');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L338');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L339');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L340');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L341');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L342');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L113');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L344');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L356');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L357');?></th>
				<th style='vertical-align:middle;' class='w3-center'><?php echo displayText('L172');?></th>
			</thead>
			<tbody class='tbody'>
		<?php
	}
	
	$data = array();
	$sql = $sqlData;
	if($exportFlag=='') $sql.=" LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	$counter = $requestData['start'];
	$query = $db->query($sql);
	if($query AND $query->num_rows > 0)
	{
		while($result = $query->fetch_assoc())
		{
			$cparLotListId = $result['listId'];
			$cparId = $result['cparId'];
			$cparLotNumber = $lotNumber = $result['lotNumber'];
			$quantity = $result['quantity'];
			
			$poId = $partId = $identifier = '';
			$sql = "SELECT poId, partId, identifier FROM ppic_lotlist WHERE lotNumber LIKE '".$lotNumber."' LIMIT 1";
			$queryLotList = $db->query($sql);
			if($queryLotList AND $queryLotList->num_rows > 0)
			{
				$resultLotList = $queryLotList->fetch_assoc();
				$poId = $resultLotList['poId'];
				$partId = $resultLotList['partId'];
				$identifier = $resultLotList['identifier'];
			}
			
			$partNumber = $customerId = $material = '';
			if($identifier == 1)
			{
				$materialSpecId = '';
				$sql = "SELECT partNumber, customerId, materialSpecId FROM cadcam_parts WHERE partId = ".$partId." LIMIT 1";
				$queryParts = $db->query($sql);
				if($queryParts AND $queryParts->num_rows > 0)
				{
					$resultParts = $queryParts->fetch_assoc();
					$partNumber = $resultParts['partNumber'];
					$customerId = $resultParts['customerId'];
					$materialSpecId = $resultParts['materialSpecId'];
				}
				
				if($exportFlag!='')
				{
					$materialTypeId = $metalThickness = '';
					$sql = "SELECT materialTypeId, metalThickness FROM cadcam_materialspecs WHERE materialSpecId = ".$materialSpecId." LIMIT 1";
					$queryMaterialSpecs = $db->query($sql);
					if($queryMaterialSpecs AND $queryMaterialSpecs->num_rows > 0)
					{
						$resultMaterialSpecs = $queryMaterialSpecs->fetch_assoc();
						$materialTypeId = $resultMaterialSpecs['materialTypeId'];
						$metalThickness = $resultMaterialSpecs['metalThickness'];
					}
					
					$materialType = '';
					$sql = "SELECT materialType FROM engineering_materialtype WHERE materialTypeId = ".$materialTypeId;
					$queryMaterialType = $db->query($sql);
					if($queryMaterialType AND $queryMaterialType->num_rows > 0)
					{
						$resultMaterialType = $queryMaterialType->fetch_assoc();
						$materialType = $resultMaterialType['materialType'];
					}
					
					$material = $materialType." t".$metalThickness;
				}
			}

			$poQuantity = $poPrice = 0;
			$poCurrency = ''; 			
			$customerAlias = '';
			if($identifier == 1 OR $identifier == 2)
			{
				$sql = "SELECT customerId, poQuantity, price, currency FROM sales_polist WHERE poId = ".$poId." LIMIT 1";
				$queryPoList = $db->query($sql);
				if($queryPoList AND $queryPoList->num_rows > 0)
				{
					$resultPoList = $queryPoList->fetch_assoc();
					$customerId = $resultPoList['customerId'];
					$poQuantity = $resultPoList['poQuantity'];
					$poPrice = $resultPoList['price'];
					$poCurrency = $resultPoList['currency'];
				}
				
				$sql = "SELECT customerAlias FROM sales_customer WHERE customerId = ".$customerId." LIMIT 1";
				$queryCustomer = $db->query($sql);
				if($queryCustomer AND $queryCustomer->num_rows > 0)
				{
					$resultCustomer = $queryCustomer->fetch_assoc();
					$customerAlias = $resultCustomer['customerAlias'];
				}
			}
			else if($identifier == 4)
			{
				$sql = "SELECT itemQuantity, supplierAlias FROM purchasing_pocontents WHERE poContentId = ".$poId	." LIMIT 1";
				$queryPoContents = $db->query($sql);
				if($queryPoContents AND $queryPoContents->num_rows > 0)
				{
					$resultPoContents = $queryPoContents->fetch_assoc();
					$customerAlias = $resultPoContents['supplierAlias'];
					$poQuantity = $resultPoContents['itemQuantity'];
				}
			}
			
			if(strstr($cparId, "CPAR-CUS") !== FALSE) $extendQuery = "AND updateStatus != 2";
			else if(strstr($cparId, "CPAR-SUB") !== FALSE) $extendQuery = "AND updateStatus = 2";
			else $extendQuery = '';
			
			$totalDeliveredQty = $totalPercentage = $dppm = 0;
			$sql = "SELECT SUM(Quantity) AS sumQty FROM purchasing_drcustomer WHERE lotNumber LIKE '".$lotNumber."' ".$extendQuery."";
			$queryDrCustomer = $db->query($sql);
			if($queryDrCustomer AND $queryDrCustomer->num_rows > 0)
			{
				$resultDrCustomer = $queryDrCustomer->fetch_assoC();
				$totalDeliveredQty = $resultDrCustomer['sumQty']; //total delivered qty;
				
				if($quantity > 0 AND $totalDeliveredQty > 0) $dppm = (($quantity * 1000000) / $totalDeliveredQty);
			}
			
			if(strstr($cparId, "CPAR-CUS") !== FALSE OR strstr($cparId, "CPAR-SUB") !== FALSE)
			{
				if($quantity > 0 AND $totalDeliveredQty > 0) $totalPercentage = ($quantity / $totalDeliveredQty);
			}
			else if(strstr($cparId, "CPAR-INT") !== FALSE)
			{
				if($quantity > 0 AND $poQuantity > 0) $totalPercentage = ($quantity / $poQuantity);
			}
			
			$totalPercentage = round($totalPercentage,2);
			$dppm = number_format($dppm);
			if($totalPercentage == 0) $totalPercentage = '';
			if($dppm == 0) $dppm = '';

			$price = 0;
			$currency = '';			
			if($identifier==1)
			{
				$sql = "SELECT price, currency FROM sales_pricelist where arkPartId = ".$partId." LIMIT 1";
				$queryPriceList = $db->query($sql);
				if($queryPriceList AND $queryPriceList->num_rows > 0)
				{
					$resultPriceList = $queryPriceList->fetch_assoc();
					$price = $resultPriceList['price'];
					$currency = $resultPriceList['currency'];
				}
			}
			
			if($price < 1)
			{
				$price = $poPrice;
				$currency = $poCurrency;
			}
			
			$signCurrency = $priceDollar = '';
			if($currency == 1)
			{
				$signCurrency = "$";  
				$priceDollar = ($price*1);
			}
			else if($currency == 2)
			{
				$signCurrency = "P";  
				$priceDollar = ($price/40); 
			}
			else if($currency == 3)
			{
				$signCurrency = "Y"; 
				$priceDollar = ($price/120); 
			}
			else
			{
				$signCurrency = "$"; 
				$priceDollar = ($price*1);
			}
			
			$totalAmount = ($priceDollar * $quantity);
			
			$listId = $cparSection = $cparIssueDate = $cparDueDate = $cparInfoSource = $cparMaker = $cparDetails = $cparMoreDetails = $cparDisposition = $cparCause = $cparSourcePerson = $cparDetectProcess = $cparDetectPerson = $cparInterimAction = $cparAnalysis = $cparCorrectiveProcess = $cparVerification = $cparStatus = '';
			$sql = "SELECT listId, cparSection, cparIssueDate, cparDueDate, cparInfoSource, cparMaker, cparDetails, cparMoreDetails,cparDisposition, cparCause, cparSourcePerson, cparDetectProcess, cparDetectPerson, cparInterimAction, cparAnalysis, cparCorrectiveProcess, cparVerification, cparStatus FROM qc_cpar WHERE cparId LIKE '".$cparId."' LIMIT 1";
			$queryCpar = $db->query($sql);
			if($queryCpar AND $queryCpar->num_rows > 0)
			{
				$resultCpar = $queryCpar->fetch_assoc();
				$listId = $resultCpar['listId'];
				$cparSection = $resultCpar['cparSection'];
				$cparIssueDate = $resultCpar['cparIssueDate'];
				$cparDueDate = $resultCpar['cparDueDate'];
				$cparInfoSource = $resultCpar['cparInfoSource'];
				$cparMaker = $resultCpar['cparMaker'];
				$cparDetails = $resultCpar['cparDetails'];
				$cparMoreDetails = $resultCpar['cparMoreDetails'];
				$cparDisposition = $resultCpar['cparDisposition'];
				$cparCause = $resultCpar['cparCause'];
				$cparSourcePerson = $resultCpar['cparSourcePerson'];
				$cparDetectProcess = $resultCpar['cparDetectProcess'];
				$cparDetectPerson = $resultCpar['cparDetectPerson'];
				$cparInterimAction = $resultCpar['cparInterimAction'];
				$cparAnalysis = $resultCpar['cparAnalysis'];
				$cparCorrectiveProcess = $resultCpar['cparCorrectiveProcess'];
				$cparVerification = $resultCpar['cparVerification'];
				$cparStatus = $resultCpar['cparStatus'];
			}
			
			$dateToday = date_create(date('Y-m-d'));
			
			if($cparStatus == 'Closed')
			{
				$cusDelAlarm = "N/A";
			}
			else
			{
				$dueDate = date_create($cparDueDate);
				$diff = date_diff($dateToday, $dueDate);
				$value = $diff->format('%R%a');
				if($value == 0)
				{
					$cusDelAlarm = "Yellow";
				}
				else if($value > 2)
				{
					$cusDelAlarm = "Blue";
				}
				else if($value >= 1)
				{
					$cusDelAlarm = "Green";
				}
				else if($value < 0 && $value >= -4)
				{
					$cusDelAlarm = "Orange";
				}
				else
				{
					$cusDelAlarm = "Red";
				}
			}

			$detectDate = date_create($cparIssueDate);
			$diff1 = date_diff($dateToday, $detectDate);
			$value1 = $diff1->format('%R%a');
			
			$interim = "OK";
			if($cparInterimAction == '')
			{
				$interim = $value1;
			}
			
			$COR = "OK";
			if($cparCorrectiveProcess == '')
			{
				$COR = $value1;
			}
			
			$VER = "OK";
			if($cparVerification == '')
			{
				$VER = $value1;
			}
			
			$action = "";
			if(($_SESSION['idNumber'] == '0291' OR $_SESSION['idNumber'] == '0282' OR $_SESSION['idNumber'] == '0197' OR $_SESSION['idNumber'] == '0228' OR $_SESSION['idNumber'] == '0215' OR $_SESSION['idNumber'] == '0063' OR $_SESSION['userID'] == 'ariel' OR $_SESSION['userID'] == 'isabel' OR $_SESSION['userID'] == 'freya' OR $_SESSION['userType'] == '0' OR $_SESSION['userType'] == '10' OR $editFlag==1) AND (isset($_SESSION['userID'])))
			{
				$action .=  "<a href = 'anthony_cparEdit.php?cparId=".$listId."'><img src='images/edit1.png' width='18' height='18' alt='Edit' title='Edit'></a>";
			}
			if ($cparIssueDate < '2018-05-03')
			{
				$action .=  "<a onclick = \"window.open('anthony_converter.php?cparId=".$listId."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/print.png' width='18' height='18' alt='Print' title='Print'></a>";
			}
			elseif ($cparIssueDate >= '2018-05-03' AND  $cparIssueDate<= '2018-10-04')
			{
				$action .=  "<a onclick = \"window.open('anthony_converterV3.php?cparId=".$listId."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/print.png' width='18' height='18' alt='Print' title='Print'></a>";
			}
			elseif($cparIssueDate >= '2018-10-04' AND  $cparIssueDate<= '2019-09-09')
			{
				$action .=  "<a onclick = \"window.open('pipz_correctiveActionReportPdf.php?listId=".$cparLotListId."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/print.png' width='18' height='18' alt='Print' title='Print'></a>";
			}
			else
			{
				$action .=  "<a onclick = \"window.open('rhay_correctiveActionReportPdf.php?listId=".$cparLotListId."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/print.png' width='18' height='18' alt='Print' title='Print'></a>";
			}
				
			$location = $_SERVER['DOCUMENT_ROOT']."/Document Management System/CPAR Folder/".$cparId.".pdf";
			if(file_exists($location))
			{
				$action .=  "<a onclick = \"window.open('anthony_viewPDF.php?cparId=".$listId."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/view1.png' width='18' height='18' alt='Print' title='Print'></a>";
			}
			else
			{
				$location = "../../Document Management System/CPAR Folder/".$cparId.".jpg";
				if(file_exists($location))
				{
					$action .=  "<a onclick = \"window.open('".$location."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/view1.png' width='18' height='18' alt='Print' title='Print'></a>";
				}
				else
				{
					$location = "../../Document Management System/CPAR Folder/".$cparId."(".$cparLotNumber.")".".pdf";
					if(file_exists($location))
					{
						$action .=  "<a onclick = \"window.open('".$location."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/view1.png' width='18' height='18' alt='Print' title='Print'></a>";
					}
					else
					{
						$location = "../../Document Management System/CPAR Folder/".$cparId."(".$cparLotNumber.")".".jpg";
						if(file_exists($location))
						{
							$action .=  "<a onclick = \"window.open('".$location."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/view1.png' width='18' height='18' alt='Print' title='Print'></a>";
						}
						else
						{
							$location = "../../Document Management System/CPAR Folder/".$cparId."(".$cparLotNumber.")".".jpeg";
							if(file_exists($location))
							{
								$action .=  "<a onclick = \"window.open('".$location."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/view1.png' width='18' height='18' alt='Print' title='Print'></a>";
							}
							else
							{
								$location = "../../Document Management System/CPAR Folder/".$cparId."(".$cparLotNumber.")".".png";
								if(file_exists($location))
								{
									$action .=  "<a onclick = \"window.open('".$location."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/view1.png' width='18' height='18' alt='Print' title='Print'></a>";
								}
							}
						}
					}
				}							
			}
			
			if($exportFlag=='')
			{
				// ------------------------------ Shorthand ------------------------------ //
				$explodecparid = explode("-",$cparId);
				$cparId = $explodecparid[0]."-";
				$cparId .="<br>".$explodecparid[1]."-".$explodecparid[2]."-";
				$cparId .="<br>".$explodecparid[3]."-".$explodecparid[4];
				
				$customerAlias = wordwrap($customerAlias, 3, "<br>");
				
				$cparInfoSource = wordwrap($cparInfoSource,5,"<br>");
				$cparDetails = wordwrap($cparDetails,5,"<br>");
				
				if(strpos($cparMoreDetails,"/") !== false)
				{
					$explodeIt = explode("/",$cparMoreDetails);
					$cparMoreDetails = $explodeIt[0]."/<br>".$explodeIt[1];
				}
				else
				{
					$cparMoreDetails = wordwrap($cparMoreDetails,5,"<br>");
				}
				
				if(strpos($cparDisposition, 'Scrap') !== false)
				{
					$cparDisposition = "Scrap";
				}
				else if(strpos($cparDisposition, 'Return') !== false)
				{
					$cparDisposition = "RTS";
				}
				
				if(strpos($cparAnalysis, 'Method') !== false)
				{
					$cparAnalysis = "Method";
				}
				
				$cparSection = wordwrap($cparSection,6,"<br>");
				$cparDetectProcess = wordwrap($cparDetectProcess,6,"<br>");
				
				if(strpos($cparDetectPerson,"/") !== false)
				{
					$explodeIt = explode("/",$cparDetectPerson);
					$cparDetectPerson = $explodeIt[0]."/<br>".$explodeIt[1];
				}
				elseif(strpos($cparDetectPerson,",") !== false)
				{
					$explodeIt = explode(",",$cparDetectPerson);
					$cparDetectPerson = $explodeIt[0].",<br>".$explodeIt[1];
				}
				else
				{
					$cparDetectPerson = wordwrap($cparDetectPerson,5,"<br>");
				}
				
				$explodeIssueDate = explode("-",$cparIssueDate);
				$cparIssueDate = $explodeIssueDate[0]."<br>-".$explodeIssueDate[1]."-".$explodeIssueDate[2];
				// ------------------------------ Shorthand ------------------------------ //
				
				$nestedData=array(); 
				$nestedData[] = $cparId;
				$nestedData[] = $customerAlias;
				$nestedData[] = $partNumber;
				//~ $nestedData[] = $material;
				$nestedData[] = $lotNumber;
				$nestedData[] = $quantity;
				$nestedData[] = $totalDeliveredQty;
				$nestedData[] = $totalPercentage;
				$nestedData[] = $dppm;
				$nestedData[] = $signCurrency.$price;
				$nestedData[] = $totalAmount;
				$nestedData[] = $cparInfoSource;
				$nestedData[] = $cparDetails;
				$nestedData[] = $cparMoreDetails;
				$nestedData[] = $cparDisposition;
				$nestedData[] = $cparAnalysis;
				$nestedData[] = $cparSection;
				$nestedData[] = $cparDetectProcess;
				$nestedData[] = $cparDetectPerson;
				$nestedData[] = $cparIssueDate;
				$nestedData[] = $cusDelAlarm;
				$nestedData[] = $interim;
				$nestedData[] = $COR;
				$nestedData[] = $VER;
				$nestedData[] = $cparStatus;
				$nestedData[] = $action;
			}
			else
			{
				$sql = "SELECT ";
				
				echo "
					<tr>
						<td>".$cparId."</td>
						<td>".$customerAlias."</td>
						<td>".$partNumber."</td>
						<td>".$lotNumber."</td>
						<td>".$quantity."</td>
						<td>".$totalDeliveredQty."</td>
						<td>".$totalPercentage."</td>
						<td>".$dppm."</td>
						<td>".$signCurrency.$price."</td>
						<td>".$totalAmount."</td>
						<td>".$cparInfoSource."</td>
						<td>".$cparDetails."</td>
						<td>".$cparMoreDetails."</td>
						<td>".$cparDisposition."</td>
						<td>".$cparAnalysis."</td>
						<td>".$cparSection."</td>
						<td>".$cparSourcePerson."</td>
						<td>".$cparDetectProcess."</td>
						<td>".$cparDetectPerson."</td>
						<td>".$cparIssueDate."</td>
						<td>".$cusDelAlarm."</td>
						<td>".$interim."</td>
						<td>".$COR."</td>
						<td>".$VER."</td>
						<td>".$cparStatus."</td>
						<td>".$material."</td>
					</tr>
				";
			}
			
			
			$data[] = $nestedData;
		}
	}
	
	if($exportFlag=='')
	{	
		$json_data = array(
					"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
					"recordsTotal"    => intval( $totalData ),  // total number of records
					"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
					"data"            => $data   // total data array
					);

		echo json_encode($json_data);  // send data as json format
	}
	else
	{
		echo "</tbody></table>";
	}
?>
