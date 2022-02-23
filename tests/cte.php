<?php
declare(strict_types = 1);
use Inspire\Dfe\Cte;
use Inspire\Dfe\Certificate;
use Inspire\Config\Config;

define('APP_NAME', 'test');
define('ROOT_DIR', dirname(__DIR__));

include dirname(__DIR__) . '/vendor/autoload.php';
Config::loadFromFolder('config');
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
        'xUF' => 'PR',
        'xUFAut' => 'PR',
        'tpAmb' => 1,
        'CNPJ' => '94001641000538',
        'date' => '2019-02-28',
        'schemaPath' => ROOT_DIR . '/tests/schemas/CTe3.00a'
    ], $cert);
    // var_dump($cte->CteStatusServico());
    // var_dump($cte->CteConsulta('43211227270649000189579990000000011867642116'));
    var_dump($cte->CteRetRecepcao('411000204879037')); // 411000204412859
} catch (Exception $ex) {
    var_dump($ex->getMessage());
}