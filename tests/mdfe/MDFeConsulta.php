<?php
include 'mdfe_base.php';
use Inspire\Dfe\Mdfe;
use Inspire\Dfe\Certificate;
use Inspire\Config\Config;
use Inspire\Validator\Variable;
use Inspire\Dfe\Cte\ParserResponse;
use Inspire\Support\Xml\Xml;

/**
 * Check current status of MDF -e configuration and sent
 */
try {
    $mdfe = new Mdfe([
        'mod' => '58',
        'version' => '3.00',
        'saveFiles' => true,
        'xUF' => 'RS',
        'tpAmb' => 1,
        'CNPJ' => $CNPJ,
        'schemaPath' => ROOT_DIR . '/tests/schemas/MDFe3.00a'
        // Optionals
        // 'date' => '2021-02-28',
        // 'UF' => '43'
    ], $cert);
    $send = $mdfe->MDFeConsulta('[chave]');
    if ($send->isOk()) {
        var_dump($send->getExtra());
    } else {
        echo "ERROR: {$send->getMessage()['message']}\n";
    }
} catch (Exception $ex) {
    var_dump($ex->getMessage());
}