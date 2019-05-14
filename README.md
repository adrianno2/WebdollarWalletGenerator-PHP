# WebdollarWalletGenerator-PHP
Simple script to generate a Webdollar wallet in PHP


### Installation

This script uses [simplito/elliptic-php](https://github.com/simplito/elliptic-php)

You can install this library via Composer:

```sh
composer require simplito/elliptic-php
```

### Usage

see ``` example.php ```

To generate ``` $privateKey ``` you can use any function to generate a secure 32 bytes key like ``` random_bytes(32) ``` or ``` openssl_random_pseudo_bytes(32) ``` depending on your PHP version.
Send the ``` $privateKey ``` as hex to ``` genwallet() ``` function.

You can generate wallet from mnemonic by using [hash_pbkdf2](http://php.net/manual/en/function.hash-pbkdf2.php) like this:

```sh
$mnemonic = "obvious clerk essence hurry jar love recipe tenant belt sunset tiny reduce";
$iterations = 1000;
$salt = 'My secret password';
$privateKey = hash_pbkdf2('sha256', $mnemonic, $salt, $iterations, 64, false);
$wallet = genwallet($privateKey);
echo $wallet;
```
remember to save iterations count and the salt along the mnemonic or else you won't be able to recover your wallet! 
