<?php
include 'cte_base.php';
use Inspire\Dfe\Cte;
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
    $cte = new Cte([
        'mod' => '57',
        'version' => '3.00',
        'saveFiles' => true,
        'xUF' => 'RS',
        'tpAmb' => 2,
        'CNPJ' => $CNPJ,
        'schemaPath' => ROOT_DIR . '/tests/schemas/CTe3.00a'
        // Optionals
        // 'date' => '2021-02-28',
        // 'UF' => '43'
    ], $cert);
    $send = $cte->evCECTe('[chave]', 5, [
        'nProt' => '[nProt]',
        'dhEntrega' => '2022-04-03T23:25:10-03:00',
        'nDoc' => '01654782',
        'xNome' => 'Test Name',
        'latitude' => null,
        'longitude' => null,
        'chNFe' => [
            '[chave nfe]'
        ],
        'base64ce' => 'base64 encoded'
    ]);
    if ($send->isOk()) {
        var_dump($send->getExtra());
    } else {
        echo "ERROR: {$send->getMessage()['message']}\n";
    }
} catch (Exception $ex) {
    var_dump($ex->getMessage());
}