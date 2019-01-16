<h1>Openpay Sdk Documentation:</h1>


This docmetation basically for non composer php. if you want to use our sdk for composer based php like laravel go to in lib folder and take OpenPayLaravel folder. and delete the other one. There is an instruction for the use.

<h3>Non composer php framework/Custom php:</h3>


- **Copy the lib folder into the root of the project**

- **Include the Openpay.php in the checkout page** 

   _For Customs:_

  <pre style="background-color: #d3f1f3; color: black;">    like require(dirname(__FILE__) . '/lib/Openpay/Common/Openpay.php');</pre>
  
   ------------  
    

   _For Codeigniter:_

  <pre style="background-color: #d3f1f3; color: black;">    like require($_SERVER['DOCUMENT_ROOT'].'/'.str_replace('index.php','',$_SERVER[SCRIPT_NAME']).'/lib/Openpay/Common/Openpay.php');</pre>

   ----------------  
    

- **Then you have to set the basic parameters like this** 
<br><br>
**$current\_url**= http://phpsdk.openpaytestandtrain.com.au;
-----------------------------------------------------------------------------
 <br>                          
<h3>                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;User Parameters from site</h3>



<pre style="background-color: #d3f1f3; color: black;">      $PurchasePrice = 170.00;                                            //Format : 100.00(Not more than $1 million)
      
      $JamCallbackURL = $current_url."/openpay-au-sdk/callback.php";     //Not more than 250 characters
      
      $JamCancelURL = $current_url."/openpay-au-sdk/cancel.php";         //Not more than 250 characters
      
      $JamFailURL = $current_url."/openpay-au-sdk/failure.php";          //Not more than 250 characters
      
      $form_url = https://retailer.myopenpay.com.au/WebSalesTraining/;
      
      $JamRetailerOrderNo = '10000478';                                  //Consumer site order number
      
      $JamEmail = 'gautamtest@gmail.com';                               //Not more than 150 characters
      
      $JamFirstName = 'Test';                                           //First name(Not more than 50 characters)
      
      $JamOtherNames = 'Devloper';                                      //Middle name(Not more than 50 characters)
      
      $JamFamilyName = 'Test';                                          //Last name(Not more than 50 characters)
      
      $JamDateOfBirth = '04 Nov 1982';                                  //dd mmm yyyy
      
      $JamAddress1 = '15/520 Collins Street';                           //Not more than 100 characters
      
      $JamAddress2 = '';                                                //Not more than 100 characters
      
      $JamSubrub = 'Melbourne';                                         //Not more than 100 characters
      
      $JamState = 'VIC';                                                //Not more than 3 characters
      
      $JamPostCode = '3000';                                            //Not more than 4 characters
      
      $JamDeliveryDate = '01 Jan 2019';                                 //dd mmm yyyy
</pre>

<br>

- **Now you have to call the Call-1 new online order menthods like this**

<br>
&nbsp;&nbsp;&nbsp;&nbsp;1. First check the Min Max price range based on purchase price:

<pre style="background-color: #d3f1f3; color: black;">     try 
          {
            if($PurchasePrice)
            Validation::_minmaxPrice($PurchasePrice);       
          }
      catch(Exception $e)
          {
            $this->session->set_flashdata('minmax_error', $e->getMessage());
          }
     
      $Method = "NewOnlineOrder";
      
      $obj = new NewOnlineOrder(URL,$Method,$PurchasePrice,JAMTOKEN, AUTHTOKEN,'','','','','');
      
      $responsecall1 = $obj->_checkorder();
      
      $outputcall1 = json_decode($responsecall1,true);
      
      $openErrorStatus = new ErrorHandler();
      
      if($openErrorStatus !='')
      {
          $openErrorStatus->_checkstatus($outputcall1['status']); 
      } 
</pre>

<br>

- **Store cal-1 response in log file use this code**

<br>
&nbsp;&nbsp;&nbsp;&nbsp;1. Something to write to txt log:

<pre style="background-color: #d3f1f3; color: black;">      $log  = "Call-time: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
      "Log: ".$responsecall1.PHP_EOL.
      "-------------------------".PHP_EOL;</pre>


<br>
&nbsp;&nbsp;&nbsp;&nbsp;2. Save string to log, use FILE_APPEND to append:

<pre style="background-color: #d3f1f3; color: black;">     file_put_contents('./lib/Openpay/Log/log'.date("j.n.Y").'.log', $log, FILE_APPEND);</pre>

<br>

- **Now we got plan id and ready for payment so here it is**
<pre style="background-color: #d3f1f3; color: black;">
    if($outputcall1)<br>
    {
        $JamPlanID = $outputcall1['PlanID'];                      //Plan ID retrieved from Web Call 1 API
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
          catch(Exception $e) 
          {
              echo 'Message: ' .$e->getMessage();
          }
    }
</pre>

<br><br>

- **After the process is complete, the Jam system will redirect to the URL supplied along with a response value for the transaction.**


<h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Success Url :
</h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[JamCallbackURL]?status=SUCCESS&planid=3000000022284&orderid=1402


&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;**Success Result :**

<pre style="background-color: #d3f1f3; color: black;">        Array(
                [status] => 0 
                  [reason] => Array
                            (
                            ) 
                    [PlanID] => 3000000022284
                    [PurchasePrice] => 110.0000
              )</pre>


<br>
<h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cancel Url :</h5> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[JamCancelURL or JamCallbackURL]?status=CANCELLED&planid=3000000022284&orderid=1402



  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;**Cancel Result :**

<pre style="background-color: #d3f1f3; color: black;">        Array (
                [status] => CANCELLED 
                [planid] => 3000000022284 
                [orderid] => 1402 
              )</pre> 


<br>
<h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Failure Url :</h5> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[JamFailURL or JamCallbackURL]?status=FAILURE&planid=3000000022284&orderid=1402


&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;**Failure Result :**

<pre style="background-color: #d3f1f3; color: black;">        Array ( 
                [status] => FAILURE 
                [planid] => 3000000022284 
                [orderid] => 1402 
              )</pre>


<br>

- **Add the payment capture Api(call-3) at the successful page**
<br>


&nbsp;&nbsp;&nbsp;&nbsp;_For Customs:_

<pre style="background-color: #d3f1f3; color: black;">   require(dirname(__FILE__) . '/lib/Openpay/Common/Openpay.php');
   $plan_id=$_GET['planid'];</pre>
-------------------------------     

&nbsp;&nbsp;&nbsp;&nbsp;_For Codeigniter:_
<pre style="background-color: #d3f1f3; color: black;">    require($_SERVER['DOCUMENT_ROOT'].'/'.str_replace('index.php','',$_SERVER['SCRIPT_NAME']).'/lib/Openpay/Common/Openpay.php'); </pre>
-------------------------------
<br>


<pre style="background-color: #d3f1f3; color: black;">      $obj = new OnlineOrderCapturePayment(URL,$Method,'',JAMTOKEN,AUTHTOKEN,$plan_id);
      
      $response = $obj->_checkorder();
      
      $output = json_decode($response,true);
      
      $openErrorStatus = new ErrorHandler();
      
      if($openErrorStatus !='')
      {
          $openErrorStatus->_checkstatus($output['status']);  
      }</pre>
<br><br>

&nbsp;&nbsp;&nbsp;&nbsp;Something to write to txt log:

<pre style="background-color: #d3f1f3; color: black;">      $log  = "Call 3 log time: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
      "Log: ".$response.PHP_EOL.
      "-------------------------".PHP_EOL;</pre>
<br><br>

&nbsp;&nbsp;&nbsp;&nbsp;Save string to log, use FILE_APPEND to append:

<pre style="background-color: #d3f1f3; color: black;">      file_put_contents('./lib/Openpay/Log/log'.date("j.n.Y").'.log', $log, FILE_APPEND);

      print_r($output);
      die;
</pre>


<h5>Results :</h5>

<pre style="background-color: #d3f1f3; color: black;">    Call-time: 202.191.214.153 - January 3, 2019, 9:35 am
    Log: {
            "status":"0",
            "reason":{},
            "PlanID":"3000000022292",
            "EncryptedPlanID":"fhlexjSQG29NTGcYmFArHW0XWgWJtmJDO7VB6Y5Nyzg="
          }</pre>

<pre style="background-color: #d3f1f3; color: black;">    Call 3 log time: 202.191.214.153 - January 3, 2019, 9:36 am
    Log: {
            "status":"0",
            "reason":{},
            "PlanID":"3000000022292",
            "PurchasePrice":"330.0000"
          }
</pre>
<br>

- **Check your order status**


<pre style="background-color: #d3f1f3; color: black;">      $PlanID = '3000000019868';                                    //Plan ID retrieved from Web Call 1 API
      $Method = "OnlineOrderStatus";
      $obj = new OnlineOrderStatus(URL,$Method,'',JAMTOKEN, AUTHTOKEN, $PlanID);
      $response = $obj->_checkorder(); 
      $output = json_decode($response,true); 
      $openErrorStatus = new ErrorHandler();
      
      if($openErrorStatus !='')
      {
          $openErrorStatus->_checkstatus($output['status']);
      }</pre>



<h5>Results :</h5> <br>
1.After Purchase:

<pre style="background-color: #e7f3d3; color: black;">    order_status log time: 202.191.214.153 - January 3, 2019, 9:08 am</pre>
    
<pre style="background-color: #d3f1f3; color: black;">    Log: {
            "status":"0",
            "reason":{},
            "PlanID":"3000000022284",
            "OrderStatus":"Approved",
            "PlanStatus":"Active",
            "PurchasePrice":"110.0000"
          }</pre>


2.After Full Refund:

<pre style="background-color: #e7f3d3; color: black;">    order_status log time: 202.191.214.153 - January 3, 2019, 9:44 am</pre>
    
<pre style="background-color: #d3f1f3; color: black;">    Log: {  
            "status":"0",
            "reason":{},
            "PlanID":"3000000022277",
            "OrderStatus":"Approved",
            "PlanStatus":"Finished",
            "PurchasePrice":"20.0000"
          }
</pre>


<br>

- **For Refund Process**

 
<pre style="background-color: #d3f1f3; color: black;">
      $PlanID = '3000000020110';                                    //Plan ID retrieved from Web Call 1 API
      $Method = "OnlineOrderReduction";
      $ReducePriceBy = 50.00;                                       //The amount you want to refund
      $type = False;                                                //Make True if want to refund full Plan price 
      $obj = new PlanPurchasePriceReductionCall(URL, $Method, '', JAMTOKEN, AUTHTOKEN, $PlanID,'', $ReducePriceBy, $type);
      $response = $obj->_checkorder();
      $output = json_decode($response,true);
      $openErrorStatus = new ErrorHandler();
      if($openErrorStatus !='')
      {
        $openErrorStatus->_checkstatus($output['status']);
      }
</pre>
<br>

&nbsp;&nbsp;&nbsp;&nbsp;Something to write to txt log:
<pre style="background-color: #d3f1f3; color: black;">
      $log  = "order_status log time: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
      "Log: ".$response.PHP_EOL.
      "-------------------------".PHP_EOL;</pre>


<br>
&nbsp;&nbsp;&nbsp;&nbsp;Save string to log, use FILE_APPEND to append:
<pre style="background-color: #d3f1f3; color: black;">
    file_put_contents('./lib/Openpay/Log/log'.date("j.n.Y").'.log', $log, FILE_APPEND);
    print_r($output);
    die;</pre>

<br>

<h4>Refund process will be excute as per the following steps:</h4>
<pre style="background-color: #e3d1e9; color: black;">
&nbsp;&nbsp;&nbsp;&nbsp;1. At the time of full refund the $ReducePriceBy should be set null and $type should be set False.

&nbsp;&nbsp;&nbsp;&nbsp;2. For Partial refund $ReducePriceBy should be set as needed and $type should be set True.

&nbsp;&nbsp;&nbsp;&nbsp;3. Retailers will get refund upto a certain amount which will be set by the Openpay merchant.Once the retailer has reached 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;maximum refund amount limit they will get a message like �Invalid Web Sales Plan Status For Partial Refund�
</pre>
<br>

<h5>Results :</h5>

1. For certain amount refund:
<pre style="background-color: #e7f3d3; color: black;"> 
order_refund log time: 202.191.214.153 - January 3, 2019, 9:26 am</pre>
<pre style="background-color: #d3f1f3; color: black;">
    Log: {
            "status":"0",
            "reason":{},
            "PlanID":"3000000022291"
         }
       </pre>

2. For full refund
<pre style="background-color: #e7f3d3; color: black;">order_status log time: 202.191.214.153 - January 3, 2019, 9:41 am</pre>
<pre style="background-color: #d3f1f3; color: black;">
    Log: {
            "status":"0",
            "reason":{},
            "PlanID":"3000000022284",
            "OrderStatus":"Approved",
            "PlanStatus":"Refunded",
            "PurchasePrice":"110.0000"
          }
</pre>
3. When reaches maximum refund limit
<pre style="background-color: #e7f3d3; color: black;">order_refund log time: 202.191.214.153 - January 3, 2019, 9:28 am</pre>
<pre style="background-color: #d3f1f3; color: black;">
    Log: {
            "status":"12711",
            "reason":"Invalid Web Sales Plan Status For Partial Refund",
            "PlanID":"3000000022291"
          }
</pre>
<br>

- **For Plan Dispatch**

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This call supports Retailers that are set up to not receive any payment for their Plans until their system has issued a &nbsp;&nbsp;&nbsp;&nbsp;dispatch notice. This allows those retailers to make adjustments to their orders as needed prior to fulfilment and then receive &nbsp;&nbsp;&nbsp;&nbsp;the payment and reconciliation information after the dispatch event occurs.

<pre style="background-color: #d3f1f3; color: black;">
      $PlanID = '3000000020110';                                    //Plan ID retrieved from Web Call 1 API
      $Method = "OnlineOrderDispatchPlan";
      $obj = new OnlineOrderDispatchPlan(URL, $Method, '', JAMTOKEN, AUTHTOKEN, $PlanID);
      $response = $obj->_checkOrderDispatchPlan(); 
      $output = json_decode($response,true);
      $openErrorStatus = new ErrorHandler();

      if($openErrorStatus !='')
      {
        $openErrorStatus->_checkstatus($output['status']);  
      }
</pre>
<br>

- **Something to write to txt log**
<pre style="background-color: #d3f1f3; color: black;">
    $log  = "order_status log time: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
    "Log: ".$response.PHP_EOL.
    "-------------------------".PHP_EOL;
</pre>
<br>

- **Save string to log, use FILE_APPEND to append**
<pre style="background-color: #d3f1f3; color: black;">
    file_put_contents('./lib/Openpay/Log/log'.date("j.n.Y").'.log', $log, FILE_APPEND);
    print_r($output);
    die;
</pre>

<h5>Results :</h5>
 
<pre style="background-color: #e7f3d3; color: black;">order_dispatch log time: 202.191.214.153 - January 3, 2019, 9:44 am</pre>

<pre style="background-color: #d3f1f3; color: black;">    Log: {
            "status":"0",
            "reason":{},
            "PlanID":"3000000022291"
          }</pre>