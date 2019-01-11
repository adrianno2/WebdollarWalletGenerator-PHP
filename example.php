<?php 

require_once("vendor/autoload.php"); 

use Elliptic\EdDSA;

function encodeBase64($addressWIF) {
    
    $addressWIF = base64_encode(hex2bin($addressWIF));
    $addressWIF = str_replace('O', '#', $addressWIF);
    $addressWIF = str_replace('l', '@', $addressWIF);
    $addressWIF = str_replace('/', '$', $addressWIF);

    return $addressWIF;
}

function checksum($hex){
    
    $checksum = hash('sha256', hash('sha256', hex2bin($hex), true), true);
    $checksum = substr(bin2hex($checksum), 0, 8);
    
    return $checksum;
}

function generateAddressWIF($unencodedAddress){
    
    $prefix = "584043fe"; //BASE64 HEX  WEBD$
    $suffix = "FF"; //ending BASE64 HEX
    $unencodedAddress = "00" . $unencodedAddress;
    $checksum = checksum($unencodedAddress);
    $addressWIF = $prefix . $unencodedAddress . $checksum . $suffix;
    
    return encodeBase64($addressWIF);
}

function genwallet($privateKey){
    
    $ec =  new EdDSA('ed25519');
    
    $kp = $ec->keyFromSecret($privateKey);
    
    $privateKey = $privateKey . $kp->getPublic('hex');
    $publicKey = $kp->getPublic('hex');
    
    $privateKeyAndVersion = "80" . $privateKey;
    $checksum = checksum($privateKeyAndVersion);
    $privateKeyWIF = $privateKeyAndVersion . $checksum;
    $unencodedAddress = hash('ripemd160', hash('sha256', hex2bin($publicKey), true));
    $addressWIF = generateAddressWIF($unencodedAddress);
    $wallet = '{"version":"0.1","address":"' . $addressWIF . '","publicKey":"' . $publicKey . '","privateKey":"' . $privateKeyWIF . '"}';
    
    return $wallet;
}

$privateKey = bin2hex(openssl_random_pseudo_bytes(32));
$wallet = genwallet($privateKey);
echo $wallet;
