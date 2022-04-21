<?php
	include $_SERVER['DOCUMENT_ROOT']."/version.php";
	$path = $_SERVER['DOCUMENT_ROOT']."/".v."/Common Data/";
	//$path = $_SERVER['DOCUMENT_ROOT']."/V3/Common Data/";
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

	if(isset($_POST['excelExport']) AND $_POST['excelExport'] != '')
	{
		$mergeQuery = $_POST['mergeQuery'];
		$dateStart = $_POST['dateStart'];
		$dateEnd = $_POST['dateEnd'];
		if($dateStart != '' AND $dateEnd == '') $exportName = "CPAR (".$dateStart." to ".date('Y-m-d').")";
		else if($dateStart != '' AND $dateEnd != '') $exportName = "CPAR (".$dateStart." to ".$dateEnd.")";
		else $exportName = "CPAR (".date('F Y').")";
		
		$sql = "SELECT cparId, lotNumber, cparSection, cparQuantity, cparInfoSource, cparDetails, cparMoreDetails, cparDisposition, cparIssueDate, cparCause, cparSourcePerson, cparDetectProcess, cparDetectPerson, cparAnalysis, cparStatus FROM qc_cpar ".$mergeQuery." ORDER BY listId DESC";
		$getCPAR = $db->query($sql);
		
		// $filename = "".$exportName.".xls";
		// header('Content-type: application/ms-excel');
		// header('Content-Disposition: attachment; filename='.$filename);
		
		$tableContent = '';	
		$tableContent = "<table border = 1>";
		$tableContent = $tableContent."
		<tr>
			<td align='center'><b>Cpar ID</b></td>
			<td align='center'><b>Customer</b></td>
			<td align='center'><b>Lot Number</b></td>
			<td align='center'><b>Material Type</b></td>
			<td align='center'><b>Quantity</b></td>
			<td align='center'><b>Total Qty Delivered</b></td>
			<td align='center'><b>Source of Information</b></td>
			<td align='center'><b>Issue Date</b></td>
			<td align='center'><b>Details</b></td>
			<td align='center'><b>More Details</b></td>
			<td align='center'><b>CPAR Dispostion</b></td>
			<td align='center'><b>Analysis</b></td>
			<td align='center'><b>Cause</b></td>
			<td align='center'><b>Source Person</b></td>
			<td align='center'><b>Person Detected</b></td>
			<td align='center'><b>Process Detected</b></td>
			<td align='center'><b>Concern Group</b></td>
			<td align='center'><b>Status</b></td>
			<td align='center'><b>Price</b></td>
			<td align='center'><b>Amount</b></td>
		</tr>";
		while($getCPARResult = $getCPAR->fetch_assoc())
		{
				if(trim($getCPARResult['cparId']) != '')
				{
					$sql = "SELECT cparId, lotNumber, quantity, prsNumber FROM qc_cparlotnumber WHERE cparId LIKE '".$getCPARResult['cparId']."' AND status != 2";
					$getCparLotNumber = $db->query($sql);
					while($getCparLotNumberResult = $getCparLotNumber->fetch_array())
					{
						//~ if(strstr($getCparLotNumberResult['cparId'], "CPAR-CUS")) $extendQuery = "AND updateStatus != 2";
						//~ if(strstr($getCparLotNumberResult['cparId'], "CPAR-SUB")) $extendQuery = "AND updateStatus = 2";
						
						$deliveredQuantity = 0;
						$sql = "SELECT Quantity FROM purchasing_drcustomer WHERE lotNumber LIKE '".$getCparLotNumberResult['lotNumber']."'";
						$query = $db->query($sql);
						if($query AND $query->num_rows > 0)
						{
							while($result = $query->fetch_assoc())
							{
								$deliveredQuantity = $result['Quantity'];
							}
						}
						
						$customerAlias = "N/A";

						if ($getCparLotNumberResult['lotNumber'] == "") 
						{
							$getCparLotNumberResult['lotNumber'] = $getCparLotNumberResult['prsNumber'];
						}


						// --------------------------------- Ace: Retrieve Customer Data -----------------------------------------
						$sql = "SELECT poId FROM ppic_lotlist WHERE lotNumber = '".$getCparLotNumberResult['lotNumber']."'";
						$poIdQuery = $db->query($sql);
						if($poIdQuery->num_rows > 0)
						{					
							$poIdQueryResult = $poIdQuery->fetch_assoc();	
								$customerAlias = $sql;
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
							else
							{
								$customerAlias = "";
							}
						}





						// --------------------- Price and Output ----------------------------
						$price = 0; 
						$currency = 0;
						$sql ="SELECT * FROM ppic_lotlist WHERE lotNumber LIKE '".$getCparLotNumberResult['lotNumber']."' and identifier = 1";
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
							
							
							if($price <= 0)
							{
								$sql ="SELECT * FROM sales_polist WHERE poId = ".$poId;
								$tableQueryb2 = $db->query($sql);
								$tableQueryResultb2 = $tableQueryb2->fetch_array();
								 
								$price = $tableQueryResultb2['price'];
								$currency = $tableQueryResultb2['currency'];
								$poIdpartID = $tableQueryResultb2['partId'];
							//get AREA of Ka subparts START
							$ChildareaSum=0;$ExactChildarea=0;$ChildareaCounter=0; $ChildareaError=0;
							$subpartPrice="";$ChildareaSumStr="";
							$sql3 = "SELECT childId FROM cadcam_subparts where parentId = ".$poIdpartID." and identifier=1";
							$ppicQuery3 = $db->query($sql3);
							if($ppicQuery3->num_rows > 0)
							{	
								while($ppicQueryResult3 = $ppicQuery3->fetch_assoc())
								{
									$childId=$ppicQueryResult3['childId'];
									list($partNumber_A,$partName_A,$revisionId_A,$thick_A,$item_x_A,$item_y_A,$metalType_A,$customerId_A,$treatmentId_A,$itemWeight_A,$partNote_A,$itemLength_A,$itemWidth_A,$itemHeight_A,$treatmentName_A,$matL_A,$matW_A)=identifierdetails($childId,1,1);
									if($item_x_A>0){ $childMin=$item_x_A; }
									if($item_y_A>0){ $childMax=$item_y_A; }
									if($childMin==0 or $childMax==0)
									{
										if($itemLength_A>0){ $childMin=$itemLength_A; }
										if($itemWidth_A>0){ $childMax=$itemWidth_A; }
									}
									if($childMin==0 or $childMax==0)
									{
										$ChildareaError=1;
									}
									else
									{
										$ChildareaSum=($ChildareaSum+($childMin*$childMax));
										$ChildareaSumStr=$ChildareaSumStr."+".($childMin*$childMax);
										if($ppicQueryResult3['childId']==$partId)
										{
											$ExactChildarea=($childMin*$childMax);
										}
									}	
									$ChildareaCounter++;
								}		
							}
							if($ChildareaError==0 and $price>0)
							{	if ($ChildareaSum != 0)
								{
									$priceB=($price*($ExactChildarea/$ChildareaSum));
									$price=number_format(($price*($ExactChildarea/$ChildareaSum)), 2, '.', ',');
									$subpartPrice="<font color=green>sub</font>";
								}
							}
							//get AREA of Ka subparts END
								if($currency==0)
								{
									$sql = "SELECT * FROM sales_pricelist WHERE arkPartId = ".$poIdpartID;
									$tableQueryb3 = $db->query($sql);
									if($tableQueryb3 AND $tableQueryb3->num_rows > 0)
									{
										$tableQueryResultb3 = $tableQueryb3->fetch_array();
									}
									$currency = $tableQueryResultb3['currency'];
								}
								
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
						
						$totalAmount = ($price3 * $getCparLotNumberResult['quantity']);
						// --------------------- End Price and Output ----------------------------						
						
						if($deliveredQuantity == 0) $deliveredQuantity = '';
						$tableContent = $tableContent."
							<tr>
								<td>".$getCPARResult['cparId']."</td>
								<td>".$customerAlias."</td>
								<td>".$getCparLotNumberResult['lotNumber']."</td>
								<td>".$materialType." t".$metalThickness."</td>
								<td align = 'center'>".$getCparLotNumberResult['quantity']."</td>
								<td align = 'center'>".$deliveredQuantity."</td>
								<td>".$getCPARResult['cparInfoSource']."</td>
								<td>".$getCPARResult['cparIssueDate']."</td>
								<td>".$getCPARResult['cparDetails']."</td>
								<td>".$getCPARResult['cparMoreDetails']."</td>
								<td>".$getCPARResult['cparDisposition']."</td>
								<td>".$getCPARResult['cparAnalysis']."</td>
								<td>".$getCPARResult['cparCause']."</td>
								<td>".$getCPARResult['cparSourcePerson']."</td>
								<td>".$getCPARResult['cparDetectPerson']."</td>
								<td>".$getCPARResult['cparDetectProcess']."</td>
								<td>".$getCPARResult['cparSection']."</td>
								<td align = 'center'>".$getCPARResult['cparStatus']."</td>
								<td align = 'center'>".$price2." ".number_format($price,2,'.',',')."</td>
								<td align = 'center'>".$price2." ".$totalAmount."</td>";
						$tableContent = $tableContent."</tr>";	
					}
				}
		}
		$tableContent = $tableContent."</table>";

		$tableContent = mb_convert_encoding($tableContent, 'UTF-16LE', 'UTF-8'); 
		// Prepend BOM
		$tableContent = "\xFF\xFE" . $tableContent;
	
		header('Pragma: public');
		$filename = "".$exportName.".xls";
		header('Content-type: application/ms-excel');
		header('Content-Disposition: attachment; filename='.$filename);
		echo $tableContent;
	}
?>
