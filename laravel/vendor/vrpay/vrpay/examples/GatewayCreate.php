<?php

spl_autoload_register(function($class) {
    $root = dirname(__DIR__);
    $classFile = $root . '/lib/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($classFile)) {
        require_once $classFile;
    }
});

// $instanceName is a part of the url where you access your VR pay installation.
// https://{$instanceName}.vr-pay-secure.de
$instanceName = 'YOUR_INSTANCE_NAME';

// $secret is the VR pay secret for the communication between the applications
// if you think someone got your secret, just regenerate it in the VR pay administration
$secret = 'YOUR_SECRET';

$vrpay = new \VRpay\VRpay($instanceName, $secret);

$gateway = new \VRpay\Models\Request\Gateway();

// amount multiplied by 100
$gateway->setAmount(89.25 * 100);

// currency ISO code
$gateway->setCurrency('CHF');

//success and failed url in case that merchant redirects to payment site instead of using the modal view
$gateway->setSuccessRedirectUrl('https://www.merchant-website.com/success');
$gateway->setFailedRedirectUrl('https://www.merchant-website.com/failed');

// optional: payment service provider(s) to use (see http://developers.vr-pay-secure.de/docs/miscellaneous)
// empty array = all available psps
$gateway->setPsp([]);
//$gateway->setPsp(array(4));
//$gateway->setPm(['mastercard']);

// optional: whether charge payment manually at a later date
$gateway->setPreAuthorization(false);

// optional: reference id of merchant (e. g. order number)
$gateway->setReferenceId(975382);

// optional: add contact information which should be stored along with payment
$gateway->addField($type = 'title', $value = 'mister');
$gateway->addField($type = 'forename', $value = 'Max');
$gateway->addField($type = 'surname', $value = 'Mustermann');
$gateway->addField($type = 'company', $value = 'Max Musterfirma');
$gateway->addField($type = 'street', $value = 'Musterweg 1');
$gateway->addField($type = 'postcode', $value = '1234');
$gateway->addField($type = 'place', $value = 'Musterort');
$gateway->addField($type = 'country', $value = 'AT');
$gateway->addField($type = 'phone', $value = '+43123456789');
$gateway->addField($type = 'email', $value = 'max.muster@example.com');
$gateway->addField($type = 'date_of_birth', $value = '03.06.1985');
$gateway->addField($type = 'custom_field_1', $value = '123456789', $name = array(
    1 => 'Benutzerdefiniertes Feld (DE)',
    2 => 'Benutzerdefiniertes Feld (EN)',
    3 => 'Benutzerdefiniertes Feld (FR)',
    4 => 'Benutzerdefiniertes Feld (IT)',
));

try {
    $response = $vrpay->create($gateway);
    var_dump($response);
} catch (\VRpay\VRpayException $e) {
    print $e->getMessage();
}
