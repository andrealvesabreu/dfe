<?php
declare(strict_types = 1);
use Inspire\Dfe\Certificate;
use Inspire\Config\Config;
use Inspire\Validator\Variable;

define('APP_NAME', 'test');
define('ROOT_DIR', dirname(dirname(__DIR__)));

include dirname(dirname(__DIR__)) . '/vendor/autoload.php';
Config::loadFromFolder('../config');
/**
 * Instantiate a Certificate object
 *
 * @var \Inspire\Dfe\Certificate $cert
 */
$cert = new Certificate(ROOT_DIR . '/tests/certs/cert_priKEY_.pem', // Private key path (PEM format)
ROOT_DIR . '/tests/certs/cert_pubKEY_.pem', // Public key path (PEM format)
ROOT_DIR . '/tests/certs/cert_certKEY_.pem'); // Certificate path (PEM format)
/**
 * Check if certificate is valid
 * If PEM files foes not exists, it will create them
 */
$validate = $cert->check(ROOT_DIR . '/tests/certs/cert_.pfx', // Certificate path (PFX/p12 format)
'190617'); // Certificate password
echo "Certificate is valid ultill {$validate->getExtra('expiresIn')}\n\n";

$CNPJ = '';