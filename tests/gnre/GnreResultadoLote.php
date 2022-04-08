<?php
include 'gnre_base.php';
use Inspire\Dfe\Gnre;
use Inspire\Dfe\Certificate;
use Inspire\Config\Config;
use Inspire\Dfe\Cte\ParserResponse;
use Inspire\Support\Xml\Xml;
use Inspire\Validator\ {
    Variable,
    XsdSchema
};

/**
 * Check current status of CT -e configuration and sent
 */
try {
    $gnre = new Gnre([
        'version' => '2.00',
        'saveFiles' => true,
        'xUF' => 'RS',
        'tpAmb' => 2,
        'CNPJ' => $CNPJ,
        'schemaBasePath' => ROOT_DIR . '/tests/schemas'
        // Optionals
        // 'date' => '2021-02-28',
        // 'UF' => '43'
    ], $cert);
    $send = $gnre->GnreResultadoLote('[recibo]');
    if ($send->isOk()) {
        var_dump($send->getExtra());
    } else {
        echo "ERROR: {$send->getMessage()['message']}\n";
    }
} catch (Exception $ex) {
    var_dump($ex->getMessage());
}