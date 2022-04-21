<?php
SESSION_START();
include $_SERVER['DOCUMENT_ROOT']."/version.php";
$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
$javascriptLib = "../Common Data/Libraries/Javascript/";
$templates = "../Common Data/Templates/";
set_include_path($path);	
include('PHP Modules/mysqliConnection.php');
ini_set("display_errors", "on");
include('classes/fpdf.php');
include('PHP Modules/gerald_functions.php');
include('PHP Modules/anthony_retrieveText.php');
include('PHP Modules/anthony_wholeNumber.php');
include('PHP Modules/rose_prodfunctions.php');
$requestData= $_REQUEST;
$sqlData = isset($requestData['sqlData']) ? $requestData['sqlData'] : '';
$totalRecords = isset($requestData['totalRecords']) ? $requestData['totalRecords'] : '';

$totalData = $totalRecords;
$totalFiltered = $totalRecords;

$data = array();
$x = $requestData['start'];
$sql = $sqlData." ".$requestData['start'].", ".$requestData['length'];
$processQuery = $db->query($sql);
while($getCPARResult = $processQuery->fetch_assoc())
{
	$cparDisposition = $getCPARResult['cparDisposition'];
		
	$extendQuery = '';
	$sql = "SELECT listId,cparId, lotNumber, quantity, prsNumber FROM qc_cparlotnumber WHERE cparId LIKE '".$getCPARResult['cparId']."' AND status != 2 ".$cparLotQuery."";
	if($customerId!='')
	{
		$sql = "
			SELECT d.cparId, d.lotNumber, d.quantity, d.prsNumber,d.listId FROM sales_customer as a
			INNER JOIN cadcam_parts as b ON b.customerId = a.customerId
			INNER JOIN ppic_lotlist as c ON c.partId = b.partId AND identifier = 1
			INNER JOIN qc_cparlotnumber as d ON d.lotNumber = c.lotNumber AND d.cparId LIKE '".$getCPARResult['cparId']."' AND d.status != 2 
			WHERE a.customerId = ".$customerId."
		";
	}
	$getCPARLotNumber = $db->query($sql);
	while($getCPARLotNumberResult = $getCPARLotNumber->fetch_array())
	{
		$cparId = $getCPARLotNumberResult['cparId'];
		$cparLotNumber = $getCPARLotNumberResult['lotNumber'];
		$quantity = $getCPARLotNumberResult['quantity'];
		$prsNumber = $getCPARLotNumberResult['prsNumber'];
		$cparLotListId = $getCPARLotNumberResult['listId'];

		//----------------------------- PAUL ----------------------------------------------------------//
		if(strstr($cparId, "CPAR-CUS") !== FALSE) $extendQuery = "AND updateStatus != 2";
		else if(strstr($cparId, "CPAR-SUB") !== FALSE) $extendQuery = "AND updateStatus = 2";
		else $extendQuery = '';
		
		$totalDeliveredQty = $totalPercentage = $dppm = 0;
		$sql = "SELECT SUM(Quantity) AS sumQty FROM purchasing_drcustomer WHERE lotNumber LIKE '".$getCPARLotNumberResult['lotNumber']."' ".$extendQuery."";
		$queryDr = $db->query($sql);
		if($queryDr AND $queryDr->num_rows > 0)
		{
			$resultDr = $queryDr->fetch_assoC();
			$totalDeliveredQty = $resultDr['sumQty']; //total delivered qty;
			
			if($quantity > 0 AND $totalDeliveredQty > 0) $dppm = (($quantity * 1000000) / $totalDeliveredQty);
		}
				//------------------------------------ PERCENTAGE -------------------------------------------------//
		if(strstr($cparId, "CPAR-CUS") !== FALSE OR strstr($cparId, "CPAR-SUB") !== FALSE)
		{
			if($quantity > 0 AND $totalDeliveredQty > 0) $totalPercentage = ($quantity / $totalDeliveredQty);
		}
		else if(strstr($cparId, "CPAR-INT") !== FALSE)
		{
			$sql = "SELECT poId FROM ppic_lotlist WHERE lotNumber LIKE '".$cparLotNumber."' AND identifier = 1 LIMIT 1";
			$queryLot = $db->query($sql);
			if($queryLot AND $queryLot->num_rows > 0)
			{
				$resultLot = $queryLot->fetch_assoc();
				$poIdLot = $resultLot['poId']; 
				
				$sql = "SELECT lotNumber, partId FROM ppic_lotlist WHERE poId = ".$poIdLot." AND identifier = 1 GROUP BY partId";
				$queryLot1 = $db->query($sql);
				$resultLot1 = $queryLot1->num_rows;
				
				if($resutLot1 == 1) //without subparts;
				{
					$sql = "SELECT workingQuantity FROM ppic_lotlist WHERE poId = ".$poIdLot." AND identifier = 1 LIMIT 1";
					$queryQty = $db->query($sql);
					if($queryQty AND $queryQty->num_rows > 0)
					{
						$resultQty = $queryQty->fetch_assoc();
						$workingQuantityPercentage = $resultQty['workingQuantity'];
						if($quantity > 0 AND $workingQuantityPercentage > 0) $totalPercentage = ($quantity / $workingQuantityPercentage);
					}	
				}
				else if($resultLot1 > 1) //with subparts;
				{
					$sql = "SELECT SUM(workingQuantity) AS sumWorkingQty FROM ppic_lotlist WHERE poId = ".$poIdLot." AND identifier = 1 GROUP BY partId";
					$queryQty = $db->query($sql);
					if($queryQty AND $queryQty->num_rows > 0)
					{
						$resultQty = $queryQty->fetch_assoc();
						$sumWorkingQty = $resultQty['sumWorkingQty'];
						if($quantity > 0 AND $sumWorkingQty > 0) $totalPercentage = ($quantity / $sumWorkingQty);
					}							
				}
			}				
		}
		//------------------------------------ END of PERCENTAGE -------------------------------------------------//
		if($getCPARResult['cparId'] AND $getCPARLotNumberResult['cparId'])
		{
				$customerAlias = "N/A";
				// --------------------------------- Ace: Retrieve Customer Data -----------------------------------------
				$sql = "SELECT poId, partId FROM ppic_lotlist WHERE lotNumber = '".$getCPARLotNumberResult['lotNumber']."'";
				$poIdQuery = $db->query($sql);
				if($poIdQuery->num_rows > 0)
				{					
					$poIdQueryResult = $poIdQuery->fetch_assoc();
					$partId = $poIdQueryResult['partId'];					
					$sql = "SELECT customerId FROM sales_polist WHERE poId = ".$poIdQueryResult['poId'];
					$customerIdQuery = $db->query($sql);
					if($customerIdQuery->num_rows > 0)
					{
						$customerIdQueryResult = $customerIdQuery->fetch_assoc();		
						$sql = "SELECT customerAlias FROM sales_customer WHERE customerId = ".$customerIdQueryResult['customerId'];
						$customerAliasQuery = $db->query($sql);
						$customerAliasQueryResult = $customerAliasQuery->fetch_assoc();	
						
						$customerAlias = $customerAliasQueryResult['customerAlias'];
						
					}
				}

					$totalAmount = 0;
					$totalPercentage=round($totalPercentage,2);
					$dppm=number_format($dppm);
					if($totalPercentage == 0) $totalPercentage = '';
					if($dppm == 0) $dppm = '';
					$nesteddata = array();
					$cpartId = "";
					$explodecparid = explode("-",$getCPARLotNumberResult['cparId']);
					$cpartId = $explodecparid[0]."-";
					$cpartId .="<br>".$explodecparid[1]."-".$explodecparid[2]."-";
					$cpartId .="<br>".$explodecparid[3]."-".$explodecparid[4];
					$nesteddata[] = $cpartId;
					$nesteddata[] = wordwrap($customerAlias, 3, "<br>");
                    $sql = "SELECT partNumber, materialSpecId FROM cadcam_parts WHERE partId = ".$partId;
                    $queryPartNumber = $db->query($sql);
                    if($queryPartNumber AND $queryPartNumber->num_rows > 0)
                    {
                        $resultPartNumber =$queryPartNumber->fetch_assoc();
                        $partNumber = $resultPartNumber['partNumber'];
                        $materialSpecId = $resultPartNumber['materialSpecId'];

                        $sql = "SELECT materialTypeId, metalThickness FROM cadcam_materialspecs WHERE materialSpecId = ".$materialSpecId;
                        $querySpecs = $db->query($sql);
                        if($querySpecs AND $querySpecs->num_rows > 0)
                        {
                            $resultSpecs = $querySpecs->fetch_assoc();
                            $materialTypeId = $resultSpecs['materialTypeId'];
                            $metalThickness = $resultSpecs['metalThickness'];
                        }

                        $sql = "SELECT materialType FROM engineering_materialtype WHERE materialTypeId = ".$materialTypeId;
                        $queryType = $db->query($sql);
                        if($queryType AND $queryType->num_rows > 0)
                        {
                            $resultType = $queryType->fetch_assoc();
                            $materialType = $resultType['materialType'];
                        }
                    }

					if($_GET['country'] == 2 OR $_SESSION['idNumber'] == '0412' )
					{
						$nesteddata[] = $partNumber;
                    }
                    
                    $nesteddata[] = $materialType." t".$metalThickness;
					$lote = "";
					$lote = "<a target ='_blank' style='color:blue' href='../16 Lot Details Management Software/ace_lotDetails.php?submitButton=SUBMIT&inputLot=".$getCPARLotNumberResult['lotNumber']."'>".$getCPARLotNumberResult['lotNumber']."</a>";
					if(strstr($cparId, "CPAR-CUS") !== FALSE)
					{
					$lote .= "
					<br>
					<a target ='_blank' style='color:blue' href='../16 Lot Details Management Software/ace_lotDetails.php?submitButton=SUBMIT&inputLot=".$getCPARLotNumberResult['prsNumber']."'>".$getCPARLotNumberResult['prsNumber']."</a>";
					}
					$nesteddata[] = $lote;
					$nesteddata[] = "<p style='text-align:right'>".$getCPARLotNumberResult['quantity']."</p>";
					$nesteddata[] = "<p style='text-align:right'>".$totalDeliveredQty."</p>";
					$nesteddata[] = "<p style='text-align:right'>".$totalPercentage."</p>";
					$nesteddata[] = "<p style='text-align:right'>".$dppm."</p>";
					// --------------------- Price and Output ----------------------------
					$price = 0; 
					$currency = 0;
					$sql ="SELECT * FROM ppic_lotlist WHERE lotNumber LIKE '".$getCPARLotNumberResult['lotNumber']."' and identifier = 1";
					$tableQueryb1 = $db->query($sql);
					if($tableQueryb1->num_rows > 0)
					{
						$tableQueryResultb1 = $tableQueryb1->fetch_array();
												  
						$partId = $tableQueryResultb1['partId'];
						$poId = $tableQueryResultb1['poId'];
						
						$sql = "SELECT * FROM sales_pricelist where arkPartId = ".$partId;
						$tableQueryb4 = $db->query($sql);
						$tableQueryResultb4 = $tableQueryb4->fetch_array();
						$price = $tableQueryResultb4['price'];
						$currency = $tableQueryResultb4['currency'];
						
						
						if($price < 1)
						{
							$sql ="SELECT * FROM sales_polist WHERE poId = ".$poId;
							$tableQueryb2 = $db->query($sql);
							$tableQueryResultb2 = $tableQueryb2->fetch_array();
							 
							$price = $tableQueryResultb2['price'];
							$poIdpartID = $tableQueryResultb2['partId'];
							$sql = "SELECT * FROM sales_pricelist WHERE arkPartId = ".$poIdpartID;
							$tableQueryb3 = $db->query($sql);
							$tableQueryResultb3 = $tableQueryb3->fetch_array();
							$currency = $tableQueryResultb3['currency'];
							
						}
					}
					
					if($currency == 1)
					{
						$price2 = "$";  
						$price3 = ($price*1);
					}
					else if($currency == 2)
					{
						$price2 = "P";  
						$price3 = ($price/40); 
					}
					else if($currency == 3)
					{
						$price2 = "Y"; 
						$price3 = ($price/120); 
					}
					else
					{
						$price2 = "$"; 
						$price3 = ($price*1);
					}
					
					$totalAmount = ($price3 * $getCPARLotNumberResult['quantity']);
					// --------------------- End Price and Output ----------------------------
					$nesteddata[] = "<p style='text-align:right'>".($price2."".$price)."</p>";
					$nesteddata[] = "<p style='text-align:right'>$".number_format($totalAmount,4,'.',',')."</p>";
					$nesteddata[] = wordwrap($getCPARResult['cparInfoSource'],5,"<br>");
					$nesteddata[] = wordwrap($getCPARResult['cparDetails'],5,"<br>");
					if(strpos($getCPARResult['cparMoreDetails'],"/") !== false)
					{
						$explodeIt = explode("/",$getCPARResult['cparMoreDetails']);
						$nesteddata[] = $explodeIt[0]."/<br>".$explodeIt[1];
					}
					else
					{
						$nesteddata[] = wordwrap($getCPARResult['cparMoreDetails'],5,"<br>");
					}
					if(strpos($cparDisposition, 'Scrap') !== false)
					{
						$nesteddata[]  = "Scrap";
					}
					else if(strpos($cparDisposition, 'Return') !== false)
					{
						$nesteddata[]  = "RTS";
					}
					else
					{
						$nesteddata[]  = $cparDisposition;
					}
				//echo "<td width='auto'>".$getCPARResult['cparAnalysis']."</td>";
					if(strpos($getCPARResult['cparAnalysis'], 'Method') !== false)
					{
						$nesteddata[]  = "Method";
					}
					else
					{
						$nesteddata[]  = $getCPARResult['cparAnalysis'];
					}
					$nesteddata[]  = wordwrap($getCPARResult['cparSection'],6,"<br>");
					$nesteddata[]  = wordwrap($getCPARResult['cparDetectProcess'],6,"<br>");

					if(strpos($getCPARResult['cparDetectPerson'],"/") !== false)
					{
						$explodeIt = explode("/",$getCPARResult['cparDetectPerson']);
						$nesteddata[] = $explodeIt[0]."/<br>".$explodeIt[1];
					}
					elseif(strpos($getCPARResult['cparDetectPerson'],",") !== false)
					{
						$explodeIt = explode(",",$getCPARResult['cparDetectPerson']);
						$nesteddata[] = $explodeIt[0].",<br>".$explodeIt[1];
					}
					else
					{
						$nesteddata[] = wordwrap($getCPARResult['cparDetectPerson'],5,"<br>");
					}

					$explodeIssueDate = explode("-",$getCPARResult['cparIssueDate']);
					$nesteddata[] = $explodeIssueDate[0]."<br>-".$explodeIssueDate[1]."-".$explodeIssueDate[2];

					$dateToday = date_create(date('Y-m-d'));
					$dueDate = date_create($getCPARResult['cparDueDate']);
					$diff = date_diff($dateToday, $dueDate);
					$value = $diff->format('%R%a');
					if($value == 0)
					{
						if($getCPARResult['cparStatus'] == 'Closed')
						{
							$nesteddata[] = "<p style='text-align:center'>N / A</p>";
							//echo "<td width='50'  align='center'>N / A</td>";
						}
						else
						{
							//echo "<td width='auto' align='center' style = 'background-color:lightyellow;'>Yellow</td>";
							$nesteddata[] = "Yellow";
							//echo "<td width='50'  align='center'>".$value."</td>";
						}
					}
					else if($value > 2)
					{
						if($getCPARResult['cparStatus'] == 'Closed')
						{
							$nesteddata[] = "<p style='text-align:center'>N / A</p>";
							//echo "<td width='50'  align='center'>N / A</td>";
						}
						else
						{
							$nesteddata[] = "Blue";
							//echo "<td width='auto' align='center' style = 'background-color:lightblue;'>Blue</td>";
							//echo "<td width='50'  align='center'>".$value."</td>";
						}
					}
					else if($value >= 1)
					{
						if($getCPARResult['cparStatus'] == 'Closed')
						{
							$nesteddata[] = "<p style='text-align:center'>N / A</p>";
							//echo "<td width='50'  align='center'>N / A</td>";
						}
						else
						{
							$nesteddata[] = "Green";
							//echo "<td width='auto' align='center' style = 'background-color:lightgreen;'>Green</td>";
							//echo "<td width='50'  align='center'>".$value."</td>";
						}
					}
					else if($value < 0 && $value >= -4)
					{
						if($getCPARResult['cparStatus'] == 'Closed')
						{
							$nesteddata[] = "<p style='text-align:center'>N / A</p>";
							//echo "<td width='50'  align='center'>N / A</td>";
						}
						else
						{
							$nesteddata[] = "Orange";
							//echo "<td width='auto' align='center' style = 'background-color:orange;'>Orange</td>";
							//echo "<td width='50'  align='center'>".$value."</td>";
						}
					}
					else
					{				
						if($getCPARResult['cparStatus'] == 'Closed')
						{
							$nesteddata[] = "<p style='text-align:center'>N / A</p>";
							//echo "<td width='50'  align='center'>N / A</td>";
						}
						else
						{
							$nesteddata[] = "Red";
							//echo "<td width='auto' align='center' style = 'background-color:pink;'>Red</td>";
							//echo "<td width='50'  align='center'>".$value."</td>";
						}
					}
					$detectDate = date_create($getCPARResult['cparIssueDate']);
					$diff1 = date_diff($dateToday, $detectDate);
					$value1 = $diff1->format('%R%a');

										// ************************************* Interim Alert ****************************************
					if($getCPARResult['cparInterimAction'] == '')
					{
						if($value1 == 0)
						{
							$nesteddata[] =  $value1;
							//echo "<td width='auto' align=center style = 'background-color:lightyellow;'>".$value1."</td>";
						}
						else if($value1 > 2)
						{
							//echo "<td width='auto' align=center style = 'background-color:lightblue;'>".$value1."</td>";
							$nesteddata[] =  $value1;
						}
						else if($value1 >= 1)
						{
							//echo "<td width='auto' align=center style = 'background-color:lightgreen;'>".$value1."</td>";
							$nesteddata[] =  $value1;
						}
						else if($value1 < 0 && $value1 >= -4)
						{
							//echo "<td width='auto' align=center style = 'background-color:orange;'>".$value1."</td>";
							$nesteddata[] =  $value1;
						}
						else
						{
							//echo "<td width='auto' align=center style = 'background-color:pink;'>".$value1."</td>";
							$nesteddata[] =  $value1;
						}
					}
					else
					{
						$nesteddata[] = "OK";
					}
										// ************************************* Corrective Alert ****************************************
					if($getCPARResult['cparCorrectiveProcess'] == '')
					{
						if($value1 <= 0 && $value1 >= -7)
						{
							$nesteddata[] =  $value1;
						}
						else if($value1 > 2)
						{
							$nesteddata[] =  $value1;
						}
						else if($value1 >= 1)
						{
							$nesteddata[] =  $value1;
						}
						else if($value1 <= -8 && $value1 >= -11)
						{
							$nesteddata[] =  $value1;
						}
						else
						{
							$nesteddata[] =  $value1;
						}
					}
					else
					{
						$nesteddata[] = "<p style='text-align:center'>OK</p>";
					}
					// ************************************* Verification Alert ****************************************
					if($getCPARResult['cparVerification'] == '')
					{
						if($value1 <= 0 && $value1 >= -30)
						{
							$nesteddata[] =  $value1;
						}
						else if($value1 > 2)
						{
							$nesteddata[] =  $value1;
						}
						else if($value1 >= 1)
						{
							$nesteddata[] =  $value1;
						}
						else if($value1 <= -31 && $value1 >= -34)
						{
							$nesteddata[] =  $value1;
						}
						else
						{
							$nesteddata[] =  $value1;
						}
					}
					else
					{
						$nesteddata[] = "<p style='text-align:center'>OK</p>";
					}
					$nesteddata[] = "<p style='text-align:center'>".$getCPARResult['cparStatus']."</p>";
					$action = "";
					if($_SESSION['userID'] == 'ariel' OR $_SESSION['userID'] == 'isabel' OR $_SESSION['userID'] == 'freya' OR $_SESSION['userType'] == '0' OR $_SESSION['userType'] == '10' AND (isset($_SESSION['userID'])))
						{
							$action .=  "<a href = 'anthony_cparEdit.php?cparId=".$getCPARResult['listId']."'><img src='images/edit1.png' width='18' height='18' alt='Edit' title='Edit'></a>";
						}
						if ($getCPARResult['cparIssueDate'] < '2018-05-03')
						{
							$action .=  "<a onclick = \"window.open('anthony_converter.php?cparId=".$getCPARResult['listId']."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/print.png' width='18' height='18' alt='Print' title='Print'></a>";
						}
						elseif ($getCPARResult['cparIssueDate'] >= '2018-05-03' AND  $getCPARResult['cparIssueDate']<= '2018-10-04')
						{
							$action .=  "<a onclick = \"window.open('anthony_converterV3.php?cparId=".$getCPARResult['listId']."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/print.png' width='18' height='18' alt='Print' title='Print'></a>";
						}
						elseif($getCPARResult['cparIssueDate'] >= '2018-10-04' AND  $getCPARResult['cparIssueDate']<= '2019-09-09')
						{
							$action .=  "<a onclick = \"window.open('pipz_correctiveActionReportPdf.php?listId=".$cparLotListId."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/print.png' width='18' height='18' alt='Print' title='Print'></a>";
						}
						else
						{
							$action .=  "<a onclick = \"window.open('rhay_correctiveActionReportPdf.php?listId=".$cparLotListId."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/print.png' width='18' height='18' alt='Print' title='Print'></a>";
						}
							
						$location = $_SERVER['DOCUMENT_ROOT']."/Document Management System/CPAR Folder/".$getCPARResult['cparId'].".pdf";
						if(file_exists($location))
						{
							$action .=  "<a onclick = \"window.open('anthony_viewPDF.php?cparId=".$getCPARResult['listId']."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/view1.png' width='18' height='18' alt='Print' title='Print'></a>";
						}
						else
						{
							$location = "../../Document Management System/CPAR Folder/".$getCPARResult['cparId'].".jpg";
							if(file_exists($location))
							{
								$action .=  "<a onclick = \"window.open('".$location."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/view1.png' width='18' height='18' alt='Print' title='Print'></a>";
							}
							else
							{
								$location = "../../Document Management System/CPAR Folder/".$getCPARResult['cparId']."(".$cparLotNumber.")".".pdf";
								if(file_exists($location))
								{
									$action .=  "<a onclick = \"window.open('".$location."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/view1.png' width='18' height='18' alt='Print' title='Print'></a>";
								}
								else
								{
									$location = "../../Document Management System/CPAR Folder/".$getCPARResult['cparId']."(".$cparLotNumber.")".".jpg";
									if(file_exists($location))
									{
										$action .=  "<a onclick = \"window.open('".$location."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/view1.png' width='18' height='18' alt='Print' title='Print'></a>";
									}
									else
									{
										$location = "../../Document Management System/CPAR Folder/".$getCPARResult['cparId']."(".$cparLotNumber.")".".jpeg";
										if(file_exists($location))
										{
											$action .=  "<a onclick = \"window.open('".$location."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/view1.png' width='18' height='18' alt='Print' title='Print'></a>";
										}
										else
										{
											$location = "../../Document Management System/CPAR Folder/".$getCPARResult['cparId']."(".$cparLotNumber.")".".png";
											if(file_exists($location))
											{
												$action .=  "<a onclick = \"window.open('".$location."',  '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=300, width=800, height=700');\"><img src='../Common Data/Templates/images/view1.png' width='18' height='18' alt='Print' title='Print'></a>";
											}
										}
									}
								}
							}							
						}
					$nesteddata[] = $action;
					$data[] = $nesteddata;
		}
	}
}
$json_data = array(
"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
"recordsTotal"    => intval( $totalData ),  // total number of records
"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
"data"            => $data// total data array
);

echo json_encode($json_data); 
