<?php
require_once '/home/devlools/public_html/E-Commerce/vendor/autoload.php';

$safepay = new \Safepay\SafepayClient('db66ce3d786e743884cf2330315ba9362447ec233ef335976146ba0bc90f1150');
$safepay->setApiBase('https://sandbox.api.getsafepay.com');

$tracker  = $safepay->order->setup([
    "merchant_api_key" => "sec_6181161f-b5fc-4a9e-b913-e7150cd6c9d6",
    "intent"           => "CYBERSOURCE",
    "mode"             => "payment",
    "currency"         => "PKR",
    "amount"           => 100000,
]);

$passport = $safepay->passport->create();

$url = \Safepay\Checkout::constructUrl([
    "environment"  => "sandbox",
    "token"        => $tracker->token,
    "tbt"          => $passport->token,
    "source"       => "custom",
    "redirect_url" => "https://devlura.site/E-Commerce/public/checkout/safepay/callback",
    "cancel_url"   => "https://devlura.site/E-Commerce/public/cart",
]);

echo "<h2>Token: {$tracker->token}</h2>";
echo "<h3><a href='$url' target='_blank'>👉 Test Payment Page</a></h3>";