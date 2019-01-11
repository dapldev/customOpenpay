<?php 
/**
 * Class Openpay
 *
 * User have to access this file to use openpay sdk
 *
 * @package OpenPay\Common
 */
 
//define your url and tokens
define("URL","https://retailer.myopenpay.com.au/ServiceTraining/JAMServiceImpl.svc/"); // Change the url as per Test or Live Environment
define("JAMTOKEN","30000000000000889|155f5b95-a40a-4ae5-8273-41ae83fec8c9"); // Change the jamtoken as per Test or Live Environment
define('CALLBACK_URL','/checkout/callback'); // Success Url
define('CANCLE_URL','/checkout/cancel'); // Cancel Url
define('FAILURE_URL','/checkout/failure'); // Failure Url
$token = explode('|',JAMTOKEN);
define("AUTHTOKEN",$token[0]);
$service_url = '';



require(dirname(dirname(__FILE__)) . '/Validation/Validation.php');
require(dirname(dirname(__FILE__)) . '/Core/ApiConnection.php');
require(dirname(dirname(__FILE__)) . '/Exception/ErrorHandler.php');
require(dirname(dirname(__FILE__)) . '/Api/MinMaxPurchasePrice.php');//Min/Max
require(dirname(dirname(__FILE__)) . '/Api/NewOnlineOrder.php');//call-1
require(dirname(dirname(__FILE__)) . '/Api/OpenpayCharge.php');//call-2
require(dirname(dirname(__FILE__)) . '/Api/OnlineOrderCapturePayment.php');//call-3
require(dirname(dirname(__FILE__)) . '/Api/OnlineOrderStatus.php');//call-4
require(dirname(dirname(__FILE__)) . '/Api/PlanPurchasePriceReductionCall.php');//refund 
require(dirname(dirname(__FILE__)) . '/Api/OnlineOrderDispatchPlan.php');//Plan Dispatch Call 
require(dirname(dirname(__FILE__)) . '/Api/OnlineOrderFraudAlert.php');//Customer Fraud Alert Call 
