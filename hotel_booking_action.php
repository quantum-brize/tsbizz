<?php 
include "inc.php"; 
include "config/logincheck.php"; 
$page='hotels';

$randnumber=rand(111111,999999);

 if($totalwalletBalance>=base64_decode(base64_decode(base64_decode($_REQUEST['ppid']))) && base64_decode(base64_decode(base64_decode($_REQUEST['ppid'])))>350 && $_REQUEST['ResultIndex']!='' && $_REQUEST['HotelCode']!='' && $_REQUEST['HotelName']!=''){

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('serialize_precision','-1');

if(isset($_REQUEST['RoomIndex']))
{


$requestDetail=json_decode($_SESSION['hotelSearchRequestSES'],true);
$roomGuestArr=$requestDetail['RoomGuests'];


/************* Hotel Booking - BlockRoom Request to check price changes *****************/
	$RoomIndex=$_REQUEST['RoomIndex'];
	
	$roomDataArr=$_SESSION['roomData'];
	
	$roomIndexForDataArr=$roomDataArr[$RoomIndex-1];
	
	
/*	echo "<pre>";
	print_r($roomIndexForDataArr);
	
	echo "<br>**************<br>";*/
	
	
	
	
	
	$RoomTypeCode=$_REQUEST['RoomTypeCode'];
	$RoomTypeName=$_REQUEST['RoomTypeName'];
	$RatePlanCode=$_REQUEST['RatePlanCode'];
	$BedTypeCode=$_REQUEST['BedTypeCode'];
	$SmokingPreference=$_REQUEST['SmokingPreference'];
	$Supplements=$_REQUEST['Supplements'];
	
	$ResultIndex=$_REQUEST['ResultIndex'];
	$HotelCode=$_REQUEST['HotelCode'];
	$HotelName=$_REQUEST['HotelName'];
	$NoOfRooms=$_REQUEST['NoOfRooms'];
	$ClientReferenceNo=$_REQUEST['ClientReferenceNo'];
	$IsVoucherBooking=$_REQUEST['IsVoucherBooking'];

$hotelBlockRoomArr=array();
$room=1;
if(count($roomGuestArr)>0)
{
  foreach($roomGuestArr as $roomGuestArrValue)			  
  {	
	$adultCount=$roomGuestArrValue['NoOfAdults'];
	$childCount=$roomGuestArrValue['NoOfChild'];
	
	$hotelPassengerArr=array();
		
	
	$hotelBlockRoomDetail= array (
		  'RoomIndex' => $RoomIndex,
		  'RoomTypeCode' => $RoomTypeCode,
		  'RoomTypeName' => $RoomTypeName,
		  'RatePlanCode' => $RatePlanCode,
		  'BedTypeCode' => NULL,
		  'SmokingPreference' => 0,
		  'Supplements' => NULL,
		  'Price' => 
		  array (
			'CurrencyCode' => $roomIndexForDataArr['Price']['CurrencyCode'],
			'RoomPrice' => $roomIndexForDataArr['Price']['RoomPrice'],
			'Tax' => $roomIndexForDataArr['Price']['Tax'],
			'ExtraGuestCharge' => $roomIndexForDataArr['Price']['ExtraGuestCharge'],
			'ChildCharge' => $roomIndexForDataArr['Price']['ChildCharge'],
			'OtherCharges' => $roomIndexForDataArr['Price']['OtherCharges'],
			'Discount' => $roomIndexForDataArr['Price']['Discount'],
			'PublishedPrice' => $roomIndexForDataArr['Price']['PublishedPrice'],
			'PublishedPriceRoundedOff' => $roomIndexForDataArr['Price']['PublishedPriceRoundedOff'],
			'OfferedPrice' => $roomIndexForDataArr['Price']['OfferedPrice'],
			'OfferedPriceRoundedOff' => $roomIndexForDataArr['Price']['OfferedPriceRoundedOff'],
			'AgentCommission' => $roomIndexForDataArr['Price']['AgentCommission'],
			'AgentMarkUp' => $roomIndexForDataArr['Price']['AgentMarkUp'],
			'ServiceTax' => $roomIndexForDataArr['Price']['ServiceTax'],
			'TCS' => $roomIndexForDataArr['Price']['TCS'],
			'TDS' => $roomIndexForDataArr['Price']['TDS'],
		  )
		 );
		 
		 
	$hotelBlockRoomArr[]=$hotelBlockRoomDetail;
	$room++;
	}

}	
		  
	$requestBlockRoomArr=array(
	
		'EndUserIp' => $_SERVER['SERVER_ADDR'],
		'TokenId' => $_SESSION['hotelTokenId'],
		'TraceId' => $_SESSION['hotelTraceId'],	
		'ResultIndex' => decode($ResultIndex),
		'HotelCode' => decode($HotelCode),
		'HotelName' => $HotelName,
		'GuestNationality' => 'IN',
		'NoOfRooms' => $NoOfRooms,
		'ClientReferenceNo' => '0',
		'IsVoucherBooking' => 'true',	
		'HotelRoomsDetails'=>$hotelBlockRoomArr
				
	);	
		
	
}



$ch2 = curl_init();
$url2 = 'http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc/rest/BlockRoom/';

$header = array('Content-Type: application/json', 'Accept-Encoding: gzip');
$postdata=str_replace('\/','/',json_encode($requestBlockRoomArr,JSON_NUMERIC_CHECK ));

echo "<br>**************************<br>";
echo $postdata;
echo "<br>**************************<br>";

curl_setopt($ch2 , CURLOPT_URL, $url2);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch2, CURLOPT_POSTFIELDS, $postdata);
curl_setopt($ch2, CURLOPT_HTTPHEADER, $header);

$information2 = curl_getinfo($ch2);
echo $result2 = curl_exec($ch2);
$blockroomArr = json_decode($result2,true); 	
//echo "<pre>";
//print_r($hotelInfoArr);
echo "<pre>";
/*print_r($blockroomArr);*/

echo "<br>***************Booking************************<br>";

$hotelBookRoomArr=array();
$room=1;
if(count($roomGuestArr)>0)
{
  foreach($roomGuestArr as $roomGuestArrValue)			  
  {	
	$adultCount=$roomGuestArrValue['NoOfAdults'];
	$childCount=$roomGuestArrValue['NoOfChild'];
	
	 $hotelPassengerArr=array();
	
	 if($adultCount>0)
	 {
			  for($a=1;$a<=$adultCount;$a++)
			  {
					$Title=$_REQUEST['titleAdt'.$a];	
					
					$FirstName=$_REQUEST['firstNameAdt'.$a];
					$LastName=$_REQUEST['lastNameAdt'.$a];
					
					if($guestname=='' && trim($FirstName)!=''){
					 $guestname=$FirstName.' '.$LastName;
					}
					
					
					$MiddleName=null;
					$Phoneno=null;
					$Email=null;
					$PaxType=1;
					if($a==1)
					{
						$LeadPassenger=true;
					}
					else
					{
						$LeadPassenger=false;
					}
					$Age=0;
					$PassportNo=null;
					$PassportIssueDate="0001-01-01T00: 00: 00";
					$PassportExpDate="0001-01-01T00: 00: 00";
					$PAN="EBQPS3333T";
					
					$hotelPassArr=array();
					$hotelPassArr['Title']=$Title;
					$hotelPassArr['FirstName']=$FirstName;  
					$hotelPassArr['MiddleName']=$MiddleName;
					$hotelPassArr['LastName']=$LastName;
					$hotelPassArr['Phoneno']=$Phoneno;
					$hotelPassArr['Email']=$Email;
					$hotelPassArr['PaxType']=$PaxType;
					$hotelPassArr['LeadPassenger']=$LeadPassenger;
					$hotelPassArr['Age']=$Age;
					$hotelPassArr['PassportNo']=$PassportNo;
					$hotelPassArr['PassportIssueDate']=$PassportIssueDate;
					$hotelPassArr['PassportExpDate']=$PassportExpDate;
					$hotelPassArr['PAN']=$PAN;
					
				$hotelPassengerArr[]=$hotelPassArr;	
					
					
					
					//if($guestname=='' && trim($FirstName)!=''){
					// $guestname=$FirstName.' '.$LastName;
					//}	
			  
			//     $namevalue ='title="'.$Title.'",firstName="'.$FirstName.'"lastName="'.$LastName.'",BookingNumber="'.$randnumber.'",paxType="adult"';   
// addlistinggetlastid('hotelBookingPaxDetailMaster',$namevalue); 
			  
			  
			  }	 
	 
	 }
	 


	 if($childCount>0)
	 {
			  $ChildAge=$roomGuestArrValue['ChildAge'];
			  
			  for($c=1;$c<=$childCount;$c++)
			  {
					$chdAge=$ChildAge[$c-1];
					
					$Title='Mr.';	
					$FirstName=$_REQUEST['firstNameChd'.$c];
					$LastName=$_REQUEST['lastNameChd'.$c];
					$MiddleName=null;
					$Phoneno=null;
					$Email=null;
					$PaxType=2;
					$LeadPassenger=true;
					$Age=$chdAge;
					$PassportNo=null;
					$PassportIssueDate="0001-01-01T00: 00: 00";
					$PassportExpDate="0001-01-01T00: 00: 00";
					$PAN="EBQPS3333T";
					
					$hotelPassArr=array();
					$hotelPassArr['Title']=$Title;
					$hotelPassArr['FirstName']=$FirstName;
					$hotelPassArr['MiddleName']=$MiddleName;
					$hotelPassArr['LastName']=$LastName;
					$hotelPassArr['Phoneno']=$Phoneno;
					$hotelPassArr['Email']=$Email;
					$hotelPassArr['PaxType']=$PaxType;
					$hotelPassArr['LeadPassenger']=$LeadPassenger;
					$hotelPassArr['Age']=$Age;
					$hotelPassArr['PassportNo']=$PassportNo;
					$hotelPassArr['PassportIssueDate']=$PassportIssueDate;
					$hotelPassArr['PassportExpDate']=$PassportExpDate;
					$hotelPassArr['PAN']=$PAN;
					
				$hotelPassengerArr[]=$hotelPassArr;	
				
				
					//if($guestname=='' && trim($FirstName)!=''){
					// $guestname=$FirstName.' '.$LastName;
					//}
				
				  //  $namevalue ='title="'.$Title.'",firstName="'.$FirstName.'"lastName="'.$LastName.'",BookingNumber="'.$randnumber.'",ageChild="'.$Age.'",paxType="child"';   
 //addlistinggetlastid('hotelBookingPaxDetailMaster',$namevalue); 
					
			  
			  }	 
	 
	 }


	
	
	
	$hotelBlockRoomDetail= array (
		  'RoomIndex' => $RoomIndex,
		  'RoomTypeCode' => $RoomTypeCode,
		  'RoomTypeName' => $RoomTypeName,
		  'RatePlanCode' => $RatePlanCode,
		  'BedTypeCode' => NULL,
		  'SmokingPreference' => 0,
		  'Supplements' => NULL,
		  'Price' => 
		  array (
			'CurrencyCode' => $roomIndexForDataArr['Price']['CurrencyCode'],
			'RoomPrice' => $roomIndexForDataArr['Price']['RoomPrice'],
			'Tax' => $roomIndexForDataArr['Price']['Tax'],
			'ExtraGuestCharge' => $roomIndexForDataArr['Price']['ExtraGuestCharge'],
			'ChildCharge' => $roomIndexForDataArr['Price']['ChildCharge'],
			'OtherCharges' => $roomIndexForDataArr['Price']['OtherCharges'],
			'Discount' => $roomIndexForDataArr['Price']['Discount'],
			'PublishedPrice' => $roomIndexForDataArr['Price']['PublishedPrice'],
			'PublishedPriceRoundedOff' => $roomIndexForDataArr['Price']['PublishedPriceRoundedOff'],
			'OfferedPrice' => $roomIndexForDataArr['Price']['OfferedPrice'],
			'OfferedPriceRoundedOff' => $roomIndexForDataArr['Price']['OfferedPriceRoundedOff'],
			'AgentCommission' => $roomIndexForDataArr['Price']['AgentCommission'],
			'AgentMarkUp' => $roomIndexForDataArr['Price']['AgentMarkUp'],
			'ServiceTax' => $roomIndexForDataArr['Price']['ServiceTax'],
			'TCS' => $roomIndexForDataArr['Price']['TCS'],
			'TDS' => $roomIndexForDataArr['Price']['TDS'],
		  ),
		  
		   'HotelPassenger' => $hotelPassengerArr
		  
		 );
		 
		 
	$hotelBookRoomArr[]=$hotelBlockRoomDetail;
	$room++;
	}

}




	$requestBookingRoomArr=array(
	
		'EndUserIp' => $_SERVER['SERVER_ADDR'],
		'TokenId' => $_SESSION['hotelTokenId'],
		'TraceId' => $_SESSION['hotelTraceId'],	
		'ResultIndex' => decode($ResultIndex),
		'HotelCode' => decode($HotelCode),
		'HotelName' => $HotelName,
		'GuestNationality' => 'IN',
		'NoOfRooms' => $NoOfRooms,
		'ClientReferenceNo' => '0',
		'IsVoucherBooking' => 'true',	
		'HotelRoomsDetails'=>$hotelBookRoomArr
				
	);	



$ch2 = curl_init();
$url2 = 'http://api.tektravels.com/BookingEngineService_Hotel/hotelservice.svc/rest/Book/';

$header = array('Content-Type: application/json', 'Accept-Encoding: gzip');
$postdataBooking=str_replace('\/','/',json_encode($requestBookingRoomArr,JSON_NUMERIC_CHECK ));

//echo "<br>**************************<br>";
//echo $postdata;
//echo "<br>**************************<br>";

curl_setopt($ch2 , CURLOPT_URL, $url2);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch2, CURLOPT_POSTFIELDS, $postdataBooking);
curl_setopt($ch2, CURLOPT_HTTPHEADER, $header);

$information2 = curl_getinfo($ch2);
  $result2 = curl_exec($ch2);
$bookRoomDetailArr = json_decode($result2,true); 

$hotelBasicJsonArr=json_decode($_SESSION['hotelBasicJson'],true);

if($bookRoomDetailArr['BookResult']['ResponseStatus']==1 && $bookRoomDetailArr['BookResult']['Status']==1)
{
	
	$ConfirmationNo=$bookRoomDetailArr['BookResult']['ConfirmationNo'];
	$BookingRefNo=$bookRoomDetailArr['BookResult']['BookingRefNo'];
	$BookingId=$bookRoomDetailArr['BookResult']['BookingId'];
	$IsPriceChanged=$bookRoomDetailArr['BookResult']['IsPriceChanged'];
	$IsCancellationPolicyChanged=$bookRoomDetailArr['BookResult']['IsCancellationPolicyChanged'];
	
	$Destination=$hotelBasicJsonArr['Destination'];
	$CheckIn=$hotelBasicJsonArr['CheckIn'];
	$CheckOutDate=$hotelBasicJsonArr['CheckOut'];
	$CheckOutDate=$hotelBasicJsonArr['CheckOut'];
	//$CheckIn=$hotelBasicJsonArr['CheckIn'];
	
	// price 
	
	
	
	

$guestname = trim($guestname);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$companyName = trim($_POST['companyName']);
$gstNo = trim($_POST['gstNo']);
$gstEmail = trim($_POST['gstEmail']);
$address = addslashes($_POST['address']);
 



if($guestname!='' && $email!=''){
$rs5=GetPageRecord('*','clientMaster',' email="'.$email.'"'); 
$count=mysqli_num_rows($rs5);
$editresult=mysqli_fetch_array($rs5);
if($count>0){
$clientId = $editresult['id'];
}else{
$namevalue ='clientType="1",name="'.$guestname.'",email="'.$email.'",phone="'.$phone.'",address="'.$address.'",addDate="'.date('Y-m-d h:i:s').'"';  
$clientId = addlistinggetlastid('clientMaster',$namevalue);  
}
}
	
	
//echo "<br>**************<br>";
	
/*echo $namevalue ='confirmationNo="'.$ConfirmationNo.'",BookingRefNo="'.$BookingRefNo.'",BookingNumber="'.$BookingId.'",Destination="'.$Destination.'",CheckIn="'.$CheckIn.'",CheckOutDate="'.$CheckOutDate.'",HotelName="'.$HotelName.'",HotelCode="'.decode($HotelCode).'",journeyDate="'.$CheckIn.'", TotalRoom="'.$NoOfRooms.'",baseFare="'.$_SESSION['roomPriceOfRoom'].'" ,tax="'.$_SESSION['TaxOfRoom'].'",totalFare="'.$_SESSION['PublishedPriceOfRoom'].'" ,requestJSON="'.$postdataBooking.'",responseJSON="'.$result2.'"';  */

$adminbase_fare=$_SESSION['PublishedPriceOfRoom'];
$adminbase_tax=$_SESSION['TaxOfRoom'];
$adminbase_totalFare=$_SESSION['PublishedPriceOfRoom'];

$agentbase_fare=$_SESSION['PublishedPriceOfRoom'];
$agentbase_tax=$_SESSION['TaxOfRoom'];
$agentbase_totalFare=$_SESSION['PublishedPriceOfRoom'];

$CancellationPolicy=$roomIndexForDataArr['CancellationPolicy'];


 $namevalue ='confirmationNo="'.$ConfirmationNo.'",BookingRefNo="'.$BookingRefNo.'",BookingNumber="'.$BookingId.'",Destination="'.$Destination.'",CheckIn="'.$CheckIn.'",CheckOutDate="'.$CheckOutDate.'",HotelName="'.$HotelName.'",HotelCode="'.decode($HotelCode).'", TotalRoom="'.$NoOfRooms.'",baseFare="'.$adminbase_fare.'" ,tax="'.$adminbase_tax.'",totalFare="'.$adminbase_totalFare.'",agentBaseFare="'.$agentbase_fare.'",agentTax="'.$agentbase_tax.'",agentTotalFare="'.$_SESSION['balancesheetamount'].'",agentOtherCharges="'.$_SESSION['balancesheetothercharges'].'",addDate="'.date('Y-m-d').'",status=2,agentId="'.($_SESSION['agentUserid']).'",clientId="'.$clientId.'",Rating="'.$_SESSION['hotelstarcategoryrating'].'",RoomType="'.$_SESSION['syshotelroomname'].'",Address="'.addslashes($_SESSION['HotelDestinationAddress']).'",CancellationPolicy="'.$CancellationPolicy.'" ';  

$bookinglastId = addlistinggetlastid('hotelBookingMaster',$namevalue); 

// add pax Master Detail

$hotelBookRoomArr=array();
$room2=1;
if(count($roomGuestArr)>0)
{
  foreach($roomGuestArr as $roomGuestArrValue)			  
  {	
	$adultCount=$roomGuestArrValue['NoOfAdults'];
	$childCount=$roomGuestArrValue['NoOfChild'];
	
	 $hotelPassengerArr=array();
	
	 if($adultCount>0)
	 {
			  for($a=1;$a<=$adultCount;$a++)
			  {
					$FirstName='';
					
					$Title=$_REQUEST['titleAdt'.$a];	
					$FirstName=$_REQUEST['firstNameAdt'.$a];
					$LastName=$_REQUEST['lastNameAdt'.$a];
					
					if(trim($FirstName)!=''){
					 $guestname=$FirstName.' '.$LastName;
					}
					
					$namevalue ='bookingTableId="'.$bookinglastId.'",title="'.$Title.'",firstName="'.$FirstName.'",lastName="'.$LastName.'",BookingNumber="'.$randnumber.'",paxType="adult"';   
					addlistinggetlastid('hotelBookingPaxDetailMaster',$namevalue); 
			  
			  
			  }	 
	 
	 }
	 

	 if($childCount>0)
	 {
			  $ChildAge=$roomGuestArrValue['ChildAge'];
			  
			  for($c=1;$c<=$childCount;$c++)
			  {
					$chdAge=$ChildAge[$c-1];
					
					$FirstName='';
					$Title='Mr.';	
					$FirstName=$_REQUEST['firstNameChd'.$c];
					$LastName=$_REQUEST['lastNameChd'.$c];
					$Age=$chdAge;

					if(trim($FirstName)!=''){
					 $guestname=$FirstName.' '.$LastName;
					}
					
					$namevalue ='bookingTableId="'.$bookinglastId.'",title="'.$Title.'",firstName="'.$FirstName.'",lastName="'.$LastName.'",BookingNumber="'.$randnumber.'",paxType="child",ageChild="'.$Age.'" ';   
					addlistinggetlastid('hotelBookingPaxDetailMaster',$namevalue); 

			  
			  }	 
	 
	 }

	$room2++;
	}

}



// End Pax Details


	
$a ='bookingId="'.$BookingId.'",bookingType="hotel",agentId="'.($_SESSION['agentUserid']).'",amount="'.$_SESSION['balancesheetamount'].'",paymentType="Debit",addedBy="'.$_SESSION['userid'].'",addDate="'.date('Y-m-d H:i:s').'"';
addlistinggetlastid('sys_balanceSheet',$a);




    $namevalue2 ='BookingNumber="'.$BookingId.'"'; 
	$where='BookingNumber="'.$randnumber.'"';
	updatelisting('hotelBookingPaxDetailMaster',$namevalue2,$where);
		
 ?>

<script> 
 window.parent.location.href = "<?php echo $fullurl; ?>hotels-bookings"; 
</script>

<?php

}

if($BookingId==''){ ?>
<script>
alert('Something Went Wrong. Please Try Again.');
window.parent.location.href = "<?php echo $fullurl; ?>hotels"; 

</script>

<?php

exit();
}


} else { 
?>

<script> alert('Your account balance is low. Please recharge for continue to this booking.'); 
window.parent.location.href = "<?php echo $fullurl; ?>"; 

</script>
<?php } ?>


 


