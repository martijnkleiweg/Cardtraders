<?php

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

require '../vendor/autoload.php';

// For test payments we want to enable the sandbox mode. If you want to put live
// payments through then this setting needs changing to `false`.
$enableSandbox = true;

// PayPal settings. Change these to your account details and the relevant URLs
// for your site.
$paypalConfig = [
    'client_id' => 'AaB5_eNsIAnh2aN-QDT6L3XFFcsBEFsWGNwjCGG3b6irzfY_wlYywjnNldJTnBQqPGK2e7DC2x02kgxX',
    'client_secret' => 'EHpJG_3Id2YjjVmE_yvVNzCMBmCQ9MpWJqj2xgs8aJd0VLkQ4Qc5tl69hFr7cd07U31Btuhbu5fZiG9p',
    'return_url' => 'http://86.93.145.183/cardtrader/user/paypalapi/response.php',
    'cancel_url' => 'http://86.93.145.183/cardtrader/user/paymentcancelled.php'
];

// Database settings. Change these for your database configuration.
$dbConfig = [
    'host' => 'localhost',
    'username' => 'gcadmin',
    'password' => 'GCpass@86',
    'name' => 'giftcard'
];

$apiContext = getApiContext($paypalConfig['client_id'], $paypalConfig['client_secret'], $enableSandbox);

/**
 * Set up a connection to the API
 *
 * @param string $clientId
 * @param string $clientSecret
 * @param bool   $enableSandbox Sandbox mode toggle, true for test payments
 * @return \PayPal\Rest\ApiContext
 */
function getApiContext($clientId, $clientSecret, $enableSandbox = false)
{
    $apiContext = new ApiContext(
        new OAuthTokenCredential($clientId, $clientSecret)
    );

    $apiContext->setConfig([
        'mode' => $enableSandbox ? 'sandbox' : 'live'
    ]);

    return $apiContext;
}