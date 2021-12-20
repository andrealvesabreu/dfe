<?php
declare(strict_types = 1);
use Inspire\Dfe\Mdfe;
use Inspire\Dfe\Certificate;
use Inspire\Core\System\Config;

define('APP_NAME', 'test');
define('ROOT_DIR', dirname(__DIR__));

include dirname(__DIR__) . '/vendor/autoload.php';
Config::loadFromFolder('config');
try {

    $cert = new Certificate(ROOT_DIR . '/tests/certs/cert_priKEY.pem', // pri
    ROOT_DIR . '/tests/certs/cert_pubKEY.pem', // pub
    ROOT_DIR . '/tests/certs/cert_certKEY.pem'); // cert
    $cert->check(ROOT_DIR . '/tests/certs/cert.pfx', '132465213');
    // $cte = new Mdfe([
    // // 'UF' => '43',
    // 'mod' => '58',
    // 'version' => '3.00',
    // 'saveFiles' => true,
    // 'xUF' => 'RS',
    // 'xUFAut' => 'RS',
    // 'tpAmb' => 1,
    // 'CNPJ' => '94001641000538',
    // 'date' => '2019-02-28'
    // ], $cert);
    // var_dump($cte->MDFeStatusServico());

    $cte = new Mdfe([
        // 'UF' => '43',
        'mod' => '58',
        'version' => '3.00',
        'saveFiles' => true,
        'xUF' => 'RS',
        'xUFAut' => 'RS',
        'tpAmb' => 1,
        'CNPJ' => '94001641000104',
        'date' => '2021-12-06'
    ], $cert);
    var_dump($cte->MDFeConsNaoEnc('94001641000104'));
} catch (Exception $ex) {
    var_dump($ex->getMessage());
}