<?php
include 'cte_base.php';
use Inspire\Dfe\Cte;
use Inspire\Dfe\Certificate;
use Inspire\Config\Config;
use Inspire\Validator\Variable;
use Inspire\Dfe\Cte\ParserResponse;
use Inspire\Support\Xml\Xml;

/**
 * Distributes documents and information of interest to the CT-e actor configuration and sent
 */
try {
    $cte = new Cte([
        'mod' => '57',
        'version' => '3.00',
        'saveFiles' => true,
        'xUF' => 'RS',
        'tpAmb' => 1,
        'CNPJ' => $CNPJ,
        'xUFAut' => 'AN',
        'schemaBasePath' => ROOT_DIR . '/tests/schemas'
        // Optionals
        // 'date' => '2021-02-28'
        // 'UF' => '43',
    ], $cert);
    \Inspire\Dfe\Parser\Cte::getDistDfeSchemas([
        'procCTe'
    ]);
    $send = $cte->CTeDistribuicaoDFe(12640);
//     $send = $cte->CTeDistribuicaoDFe(null, 12651);
    if ($send->isOk()) {
        var_dump($send->getExtra());
    } else {
        echo "ERROR: {$send->getMessage()['message']}\n";
    }
} catch (Exception $ex) {
    var_dump($ex->getMessage());
}