<?php
require(dirname(dirname(__FILE__)) . '/Common/Openpay.php');
$GLOBALS['url'] = "https://retailer.myopenpay.com.au/ServiceTraining/JAMServiceImpl.svc/";
$GLOBALS['jamtoken'] = "30000000000000889|155f5b95-a40a-4ae5-8273-41ae83fec8c9";
$token = explode('|',$GLOBALS['jamtoken']);
$GLOBALS['authtoken'] = $token[0];
print_r($_GET);


/*************************************Web Call 3 API****************************/
$Method = "OnlineOrderCapturePayment";
$obj = new OnlineOrderCapturePayment($url,$Method,$_GET['planid'],$jamtoken,$authtoken);
$output = json_decode($obj->checkorder(),true);
echo 'OnlineOrderCapturePayment';print_r($output);
$openErrorStatus = new ErrorHandler();
echo $openErrorStatus->checkstatus($output['status']);
/*************************************Web Call 3 API****************************/


