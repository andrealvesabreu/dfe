<?php
declare(strict_types = 1);
use Inspire\Dfe\Cte;
use Inspire\Dfe\Certificate;
use Inspire\Core\System\Config;

define('APP_NAME', 'test');
define('ROOT_DIR', dirname(__DIR__));

include dirname(__DIR__) . '/vendor/autoload.php';
Config::loadFromFolder('config');
echo ROOT_DIR . '/tests/cests/cert_priKEY.pem';
try {

    $cert = new Certificate(ROOT_DIR . '/tests/certs/cert_priKEY.pem', // pri
    ROOT_DIR . '/tests/certs/cert_pubKEY.pem', // pub
    ROOT_DIR . '/tests/certs/cert_certKEY.pem'); // cert
    $cert->check(ROOT_DIR . '/tests/certs/cert.pfx', '132465213');
    $cte = new Cte([
        // 'UF' => '43',
        'mod' => '57',
        'version' => '3.00',
        'saveFiles' => true,
        'xUF' => 'MS',
        'xUFAut' => 'MS',
        'tpAmb' => 1,
        'CNPJ' => '94001641000538',
        'date' => '2019-02-28'
    ], $cert);
    var_dump($cte->CteStatusServico());
} catch (Exception $ex) {
    var_dump($ex->getMessage());
}