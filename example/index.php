<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
/*
 * Payson API Integration example for PHP (Payson Checkout 2.0)
 *
 * More information can be found att https://api.payson.se
 *
 */

/*
 * On every page you need to use the API you
 * need to include the file lib/paysonapi.php
 * from where you installed it.
 */
require_once '../lib/paysonapi.php';

/*
 * Account information. Below is all the variables needed to perform a purchase with
 * payson. Replace the placeholders with your actual information 
 */

// Your merchant ID and apikey. Information about the merchant and the integration.
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

// URLs used by payson for redirection after a completed/canceled/notification purchase.
$checkoutUri     = "http://my.local/phpAPI/example/checkout.php";
$confirmationUri = "http://my.local/phpAPI/example/confirmation.php";
$notificationUri = "ttp://my.local/phpAPI/example/notification.php";
$termsUri        = "http://my.local/phpAPI/example/terms.php";

/*
 * To initiate a direct payment the steps are as follows
 *  1. Set up the details for the payment
 *  2. Initiate payment with Payson
 *  3. Verify that you get 0 errors
 *  4. Show the user snippet to complete the payment
 */
/*
 * Step 1: Set up details
 */

$paysonMerchant = new  PaysonEmbedded\PaysonMerchant($merchantId, $checkoutUri, $confirmationUri, $notificationUri, $termsUri, 1);
$payData = new  PaysonEmbedded\PayData();

//Set the currency.
$payData->setCurrencyCode('SEK');

//Set the list of products. Information about the order and the order items.
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
//$callPaysonApi->setCustomer(new  PaysonEmbedded\Customer('firstName', 'lastName', 'email', 'phone', 'identityNumber', 'city', 'country', 'postalCode', 'street'));

//Details about the language, theme, and forms of the checkout.
$callPaysonApi->setGui(new  PaysonEmbedded\Gui('sv', 'blue', 'none', 0));

/*
 * Step 2 Initiate payment
 */
$callPaysonApi->doRequest('post');
//$payData->setInvoiceFee(39);

/*
 * Step 3: verify that you get 0 errors.
 */
if(count($callPaysonApi->getpaysonResponsErrors()) == 0){
    $callPaysonApi->doRequest();
    /*
    * Step 4: Show snippet
     */

    //print_r($callPaysonApi->getResponsObject());
    echo $callPaysonApi->getResponsObject()->id;
    print '<div style=" width:500px; height:400px;">';
    echo $callPaysonApi->getResponsObject()->snippet; 
    print "</div>";

}else{
    //Show the errors
  foreach ($callPaysonApi->getpaysonResponsErrors() as $value){
     echo '<pre>'.$value->getErrorId() . '  --  ' . $value->getMessage() . '  --  ' . $value->getParameter() . '<pre>';    
  }
}
?>