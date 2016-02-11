<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once '../lib/paysonapi.php';

$merchantId = "";
$apiKey = "";
$environment = true;
if ($environment == false) {
    //$environment = false;
    $merchantId = "ENTER YOUR MERCHANT ID";
    $apiKey     = "ENTER YOUR API KEY";
} else {
    $environment = true;
    $merchantId = "4";
    $apiKey = "2acab30d-fe50-426f-90d7-8c60a7eb31d4";
}

$checkoutUri     = "http://my.local/phpAPI/example/checkout.php";
$confirmationUri = "http://my.local/phpAPI/example/confirmation.php";
$notificationUri = "ttp://my.local/phpAPI/example/notification.php";
$termsUri        = "http://my.local/phpAPI/example/terms.php";


$paysonMerchant = new  PaysonEmbedded\PaysonMerchant($merchantId, $checkoutUri, $confirmationUri, $notificationUri, $termsUri, 1);
$payData = new  PaysonEmbedded\PayData();

$payData->setCurrencyCode('SEK');

$orderItem = array();
$order = new  PaysonEmbedded\OrderItem('Test produk', 20, 1, 0.25, 'MD0', 'physical');
//$order2 = new  PaysonEmbedded\OrderItem('Test produk 2', 20, 1, 0.25, 'MD0', 'physical');
//$order->setDiscountRate(0.10);
//$order2 = new  PaysonEmbedded\OrderItem('discount', -20, 1, 0.1);
//$orde3->setType('discount');
$orderItem[] = $order;
//$orderItem[] = $order2;
//$orderItem[] = $order3;
$payData->setOrderItems($orderItem);

/* Every interaction with Payson goes through the PaysonApi object which you set up as follows.  
 * For the use of our test or live environment use one following parameters:
 * TRUE: Use test environment, FALSE: use live environment */
$callPaysonApi = new  PaysonEmbedded\PaysonApi($merchantId, $apiKey, $environment);
$callPaysonApi->setPaysonMerchant($paysonMerchant);
$callPaysonApi->setPayData($payData);

// Details about about the customer type and shipping address.
$callPaysonApi->setCustomer(new  PaysonEmbedded\Customer('firstName', 'lastName', 'email', 'phone', 'identityNumber', 'city', 'country', 'postalCode', 'street'));

//Details about the language, theme, and forms of the checkout.
$callPaysonApi->setGui(new  PaysonEmbedded\Gui('sv', 'gray', 'none', 0));

// Update the purchase with the checkoutID.
$checkoutID = '0c6d7ce8-7d5e-46bb-86bd-a5a900bd5700';

$callPaysonApi->doRequest('PUT', $checkoutID);

if(count($callPaysonApi->getpaysonResponsErrors()) == 0){
    $callPaysonApi->doRequest();
    echo $callPaysonApi->getResponsObject()->status;
    echo '<pre>'; print_r($callPaysonApi->getResponsObject());echo '</pre>';
}else{
    //Show the errors
  foreach ($callPaysonApi->getpaysonResponsErrors() as $value){
     echo '<pre>'.$value->getErrorId() . '  --  ' . $value->getMessage() . '  --  ' . $value->getParameter() . '<pre>';    
  }
}
