Openpay Sdk Documentation:


This docmetation basically for non composer php. if you want to use our sdk for composer based php like laravel go to in lib folder and take OpenPayLaravel folder. and delete the other one. There is an instruction for the use.

Non composer php framework/Custom php:


1. Copy the lib folder into the root of the project.

2. Include the Openpay.php in the checkout page 

   like require(dirname(__FILE__) . '/lib/Openpay/Common/Openpay.php'); // For Customs
   like require($_SERVER['DOCUMENT_ROOT'].'/'.str_replace('index.php','',$_SERVER['SCRIPT_NAME']).'/lib/Openpay/Common/Openpay.php'); //For Codeigniter

3. Then you have to set the basic parameters like this 

$current_url='http://phpsdk.openpaytestandtrain.com.au';
/***************************User Parameter from site***************************/
$PurchasePrice = 170.00;//Format : 100.00(Not more than $1 million)
$JamCallbackURL = $current_url."/openpay-au-sdk/callback.php";//Not more than 250 characters
$JamCancelURL = $current_url."/openpay-au-sdk/cancel.php";//Not more than 250 characters
$JamFailURL = $current_url."/openpay-au-sdk/failure.php";//Not more than 250 characters
$form_url = "https://retailer.myopenpay.com.au/WebSalesTraining/";
$JamRetailerOrderNo = '10000478';//Consumer site order number
$JamEmail = 'gautamtest@gmail.com';//Not more than 150 characters
$JamFirstName = 'Test';//First name(Not more than 50 characters)
$JamOtherNames = 'Devloper';//Middle name(Not more than 50 characters)
$JamFamilyName = 'Test';//Last name(Not more than 50 characters)
$JamDateOfBirth = '04 Nov 1982';//dd mmm yyyy
$JamAddress1 = '15/520 Collins Street';//Not more than 100 characters
$JamAddress2 = '';//Not more than 100 characters
$JamSubrub = 'Melbourne';//Not more than 100 characters
$JamState = 'VIC';//Not more than 3 characters
$JamPostCode = '3000';//Not more than 4 characters
$JamDeliveryDate = '01 Jan 2019';//dd mmm yyyy

4. Now you have to call the Call-1 new online order menthods like this :

//First check the Min Max price range based on purchase price
try {
      if($PurchasePrice)				Validation::_minmaxPrice($PurchasePrice);		
    }
catch(Exception $e){
      $this->session->set_flashdata('minmax_error', $e->getMessage());
    }

$Method = "NewOnlineOrder";
$obj = new NewOnlineOrder(URL,$Method,$PurchasePrice,JAMTOKEN, AUTHTOKEN,'','','','','');
$responsecall1 = $obj->_checkorder();
$outputcall1 = json_decode($responsecall1,true);
$openErrorStatus = new ErrorHandler();
if($openErrorStatus !=''){
	$openErrorStatus->_checkstatus($outputcall1['status']);	
} 

5. Store cal-1 response in log file use this code:

//Something to write to txt log
$log  = "Call-time: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
         "Log: ".$responsecall1.PHP_EOL.
        "-------------------------".PHP_EOL;
//Save string to log, use FILE_APPEND to append.
file_put_contents('./lib/Openpay/Log/log'.date("j.n.Y").'.log', $log, FILE_APPEND);

6. Now we got plan id and ready for payment so here it is

if($outputcall1)
{
	$JamPlanID = $outputcall1['PlanID'];//Plan ID retrieved from Web Call 1 API
	$pagegurl = $form_url.'?JamCallbackURL='.$JamCallbackURL.'&JamCancelURL='.$JamCancelURL.'&JamFailURL='.$JamFailURL.'&JamAuthToken='.urlencode(JAMTOKEN).'&JamPlanID='.urlencode( (string) $JamPlanID).'&JamRetailerOrderNo='.urlencode( $JamRetailerOrderNo ).'&JamPrice='.urlencode($PurchasePrice).'&JamEmail='.urlencode($JamEmail).'&JamFirstName='.urlencode($JamFirstName).'&JamOtherNames='.urlencode($JamOtherNames).'&JamFamilyName='.urlencode($JamFamilyName).'&JamDateOfBirth='.urlencode($JamDateOfBirth).'&JamAddress1='.urlencode($JamAddress1).'&JamAddress2='.urlencode($JamAddress2).'&JamSubrub='.urlencode($JamSubrub).'&JamState='.urlencode($JamState).'&JamPostCode='.urlencode($JamPostCode).'&JamDeliveryDate='.urlencode($JamDeliveryDate);

	try {
	  	if($JamDateOfBirth)
	  		Validation::_validateDate($JamDateOfBirth);	 
	  	if($JamDateOfBirth)
	  		Validation::_validateDate($JamDeliveryDate);
	  	if($JamState)
	  		Validation::_validateState($JamState);
	  	if($JamPostCode)
	  		Validation::_validatePostcode($JamPostCode);	  	
		$charge = OpenpayCharge::_charge($pagegurl);
	}
	catch(Exception $e) {
	  	echo 'Message: ' .$e->getMessage();
	}
}

7. After the process is complete, the Jam system will redirect to the URL supplied along with a response value for the transaction.

Success Url : [JamCallbackURL]?status=SUCCESS&planid=3000000022284&orderid=1402

Success Result :
	Array
	(
   	 	[status] => 0
    	 	[reason] => Array
        		(
        		)

    		[PlanID] => 3000000022284
    		[PurchasePrice] => 110.0000
	)
Cancel Url : [JamCancelURL or JamCallbackURL]?status=CANCELLED&planid=3000000022284&orderid=1402

Cancel Result :
	Array ( [status] => CANCELLED [planid] => 3000000022284 [orderid] => 1402 ) 

Failure Url : [JamFailURL or JamCallbackURL]?status=FAILURE&planid=3000000022284&orderid=1402

Failure Result :
	Array ( [status] => FAILURE [planid] => 3000000022284 [orderid] => 1402 )

8. Add the payment capture Api(call-3) at the successful page

require(dirname(__FILE__) . '/lib/Openpay/Common/Openpay.php');
$plan_id=$_GET['planid']; // For Customs

require($_SERVER['DOCUMENT_ROOT'].'/'.str_replace('index.php','',$_SERVER['SCRIPT_NAME']).'/lib/Openpay/Common/Openpay.php'); // For Codeigniter

$Method = "OnlineOrderCapturePayment";
$obj = new OnlineOrderCapturePayment(URL,$Method,'',JAMTOKEN,AUTHTOKEN,$plan_id);
$response = $obj->_checkorder(); 
$output = json_decode($response,true); 
$openErrorStatus = new ErrorHandler();
if($openErrorStatus !=''){
	$openErrorStatus->_checkstatus($output['status']);	
}

//Something to write to txt log
$log  = "Call 3 log time: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
         "Log: ".$response.PHP_EOL.
        "-------------------------".PHP_EOL;
//Save string to log, use FILE_APPEND to append.
file_put_contents('./lib/Openpay/Log/log'.date("j.n.Y").'.log', $log, FILE_APPEND);
echo '<pre>';print_r($output);die;

Results : 
Call-time: 202.191.214.153 - January 3, 2019, 9:35 am
Log: {"status":"0","reason":{},"PlanID":"3000000022292","EncryptedPlanID":"fhlexjSQG29NTGcYmFArHW0XWgWJtmJDO7VB6Y5Nyzg="}
-------------------------
Call 3 log time: 202.191.214.153 - January 3, 2019, 9:36 am
Log: {"status":"0","reason":{},"PlanID":"3000000022292","PurchasePrice":"330.0000"}

9.Check your order status

$PlanID = '3000000019868';//Plan ID retrieved from Web Call 1 API
$Method = "OnlineOrderStatus";
$obj = new OnlineOrderStatus(URL,$Method,'',JAMTOKEN, AUTHTOKEN, $PlanID);
$response = $obj->_checkorder(); 
$output = json_decode($response,true); 
$openErrorStatus = new ErrorHandler();
if($openErrorStatus !=''){
	$openErrorStatus->_checkstatus($output['status']);
}




Results : 

1.After Purchase 
order_status log time: 202.191.214.153 - January 3, 2019, 9:08 am
Log: {"status":"0","reason":{},"PlanID":"3000000022284","OrderStatus":"Approved","PlanStatus":"Active","PurchasePrice":"110.0000"}

2.After Full Refund
order_status log time: 202.191.214.153 - January 3, 2019, 9:44 am
Log: {"status":"0","reason":{},"PlanID":"3000000022277","OrderStatus":"Approved","PlanStatus":"Finished","PurchasePrice":"20.0000"}




10. For Refund Process

 

$PlanID = '3000000020110';//Plan ID retrieved from Web Call 1 API

$Method = "OnlineOrderReduction";



$ReducePriceBy = 50.00;//The amount you want to refund

$type = False;// make True if want to refund full Plan price 

$obj = new PlanPurchasePriceReductionCall(URL, $Method, '', JAMTOKEN, AUTHTOKEN, $PlanID,'', $ReducePriceBy, $type);
$response = $obj->_checkorder(); 
$output = json_decode($response,true);
$openErrorStatus = new ErrorHandler();

if($openErrorStatus !=''){
$openErrorStatus->_checkstatus($output['status']);
	
}

//Something to write to txt log
$log  = "order_status log time: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
         "Log: ".$response.PHP_EOL.
        "-------------------------".PHP_EOL;
//Save string to log, use FILE_APPEND to append.
file_put_contents('./lib/Openpay/Log/log'.date("j.n.Y").'.log', $log, FILE_APPEND);
echo '<pre>';print_r($output);die;

Refund process will be excute as per the following steps.

1. At the time of full refund the $ReducePriceBy should be set null and $type should be set False.

2.For Partial refund $ReducePriceBy should be set as needed and $type should be set True.

3.Retailers will get refund upto a certain amount which will be set by the Openpay merchant.Once the retailer has reached maximum refund amount limit they will get a message like “Invalid Web Sales Plan Status For Partial Refund”

Results :

1. For certain amount refund 
order_refund log time: 202.191.214.153 - January 3, 2019, 9:26 am
Log: {"status":"0","reason":{},"PlanID":"3000000022291"}

2. For full refund
order_status log time: 202.191.214.153 - January 3, 2019, 9:41 am
Log: {"status":"0","reason":{},"PlanID":"3000000022284","OrderStatus":"Approved","PlanStatus":"Refunded","PurchasePrice":"110.0000"}

3. When reaches maximum refund limit
order_refund log time: 202.191.214.153 - January 3, 2019, 9:28 am
Log: {"status":"12711","reason":"Invalid Web Sales Plan Status For Partial Refund","PlanID":"3000000022291"}


11. For Plan Dispatch

This call supports Retailers that are set up to not receive any payment for their Plans until their system has issued a dispatch notice. This allows those retailers to make adjustments to their orders as needed prior to fulfilment and then receive the payment and reconciliation information after the dispatch event occurs.

$PlanID = '3000000020110';//Plan ID retrieved from Web Call 1 API

$Method = "OnlineOrderDispatchPlan";

$obj = new OnlineOrderDispatchPlan(URL, $Method, '', JAMTOKEN, AUTHTOKEN, $PlanID);

$response = $obj->_checkOrderDispatchPlan(); 
$output = json_decode($response,true);
$openErrorStatus = new ErrorHandler();

if($openErrorStatus !=''){

	$openErrorStatus->_checkstatus($output['status']);	

}
//Something to write to txt log
$log  = "order_status log time: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
         "Log: ".$response.PHP_EOL.
        "-------------------------".PHP_EOL;
//Save string to log, use FILE_APPEND to append.
file_put_contents('./lib/Openpay/Log/log'.date("j.n.Y").'.log', $log, FILE_APPEND);
echo '<pre>';print_r($output);die;

Results : 
order_dispatch log time: 202.191.214.153 - January 3, 2019, 9:44 am
Log: {"status":"0","reason":{},"PlanID":"3000000022291"}