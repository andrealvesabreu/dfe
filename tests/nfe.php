<?php
declare(strict_types = 1);
use Inspire\Dfe\Nfe;
use Inspire\Dfe\Certificate;
use Inspire\Config\Config;

define('APP_NAME', 'test');
define('ROOT_DIR', dirname(__DIR__));
$cUFlist = [
    'AC' => 12,
    'AL' => 27,
    'AM' => 13,
    'AP' => 16,
    'BA' => 29,
    'CE' => 23,
    'DF' => 53,
    'ES' => 32,
    'GO' => 52,
    'MA' => 21,
    'MG' => 31,
    'MS' => 50,
    'MT' => 51,
    'PA' => 15,
    'PB' => 25,
    'PE' => 26,
    'PI' => 22,
    'PR' => 41,
    'RJ' => 33,
    'RN' => 24,
    'RO' => 11,
    'RR' => 14,
    'RS' => 43,
    'SC' => 42,
    'SE' => 28,
    'SP' => 35,
    'TO' => 17
];
include dirname(__DIR__) . '/vendor/autoload.php';
Config::loadFromFolder('config');
try {
    $cert = new Certificate(ROOT_DIR . '/tests/certs/cert_priKEY.pem', // pri
    ROOT_DIR . '/tests/certs/cert_pubKEY.pem', // pub
    ROOT_DIR . '/tests/certs/cert_certKEY.pem'); // cert
    $cert->check(ROOT_DIR . '/tests/certs/cert.pfx', '132465213');
    $nfe = null;
    foreach ($cUFlist as $x => $c) {
        if ($nfe == null) {
            $nfe = new Nfe([
                'mod' => '55',
                'version' => '4.00',
                'saveFiles' => true,
                'xUF' => $x,
                'cUF' => $c,
                // 'xUFAut' => $x,
                'tpAmb' => 1,
                'CNPJ' => '[CNPJ]',
                'date' => '2022-03-05',
                'schemaPath' => ROOT_DIR . '/tests/schemas/NFe4.00a'
            ], $cert);
        } else {
            $nfe->config([
                'mod' => '55',
                'version' => '4.00',
                'saveFiles' => true,
                'xUF' => $x,
                'cUF' => $c,
                // 'xUFAut' => $x,
                'tpAmb' => 1,
                'CNPJ' => '[CNPJ]',
                'date' => '2022-03-05',
                'schemaPath' => ROOT_DIR . '/tests/schemas/NFe4.00a'
            ], $cert);
        }
        var_dump($nfe->NfeStatusServico());
        sleep(3);
    }
    // $nfe = new Nfe([
    // 'mod' => '55',
    // 'version' => '4.00',
    // 'saveFiles' => true,
    // 'xUF' => 'AN',
    // 'cUF' => 43,
    // // 'xUFAut' => $x,
    // 'tpAmb' => 1,
    // 'CNPJ' => '[CNPJ]',
    // 'date' => '2022-03-05',
    // 'schemaPath' => ROOT_DIR . '/tests/schemas/NFe4.00a'
    // ], $cert);
    // var_dump($nfe->NFeDistribuicaoDFe(100050));
} catch (Exception $ex) {
    var_dump($ex->getMessage());
}