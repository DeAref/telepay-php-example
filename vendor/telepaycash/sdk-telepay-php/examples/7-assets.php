<?php
namespace Examples;

require __DIR__ . '/../vendor/autoload.php';

use TelePay\TelePayAssetInput;
use TelePay\TelePayClient;
use TelePay\TelePayEnvironment;

$clientSecret = "YOUR API SECRET KEY";

$environment = new TelePayEnvironment($clientSecret);
$telepay = new TelePayClient($environment);

echo "Assets: \n";
$assetsResponse = $telepay->getAssets();
print_r($assetsResponse);

echo "Asset detail";
$asset = new TelePayAssetInput("TON", "TON", "mainnet");
$assetDetail = $telepay->getAsset($asset);
print_r($assetDetail);