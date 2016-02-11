<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once '../lib/paysonapi.php';

// Your merchant ID and apikey. Information about the merchant and the integration.
$merchantId = "";
$apiKey = "";
$environment = true;
if ($environment == false) {
    //$environment = false;
    $merchantId = "ENTER YOUR MERCHANT ID";
    $apiKey     = "ENTER YOUT API KEY";
} else {
    $environment = true;
    $merchantId = "4";
    $apiKey = "2acab30d-fe50-426f-90d7-8c60a7eb31d4";
}

// Get the details about this purchase with the checkoutID.
$checkoutID = 'ENTER THE CHECKOUT ID';
$callPaysonApi = new  PaysonEmbedded\PaysonApi($merchantId, $apiKey, $environment);

$callPaysonApi->doRequest('GET', $checkoutID);

if($callPaysonApi->getResponsObject()->status == 'canceled'){
    echo '<H3> canceled .... </H3>';
}elseif($callPaysonApi->getResponsObject()->status == 'readyToShip'){
     echo "Purchase has been completed <br /><h4>Details</h4><pre>" ;
    echo '<pre>';print_r($callPaysonApi->getResponsObject());echo '</pre>';
}elseif($callPaysonApiConf->getResponsObject()->status == 'denied'){
    echo "The purchase is denied by any reason <br /><h4>Details</h4><pre>" ;
    echo '<pre>';print_r($callPaysonApi->getResponsObject());echo '</pre>';
}else {
    echo '<H3>Something happened when .... </H3>';
}
?>