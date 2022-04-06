<?php
include 'mdfe_base.php';
use Inspire\Dfe\Nfe;
use Inspire\Dfe\Certificate;
use Inspire\Config\Config;
use Inspire\Validator\Variable;
use Inspire\Dfe\Cte\ParserResponse;
use Inspire\Support\Xml\Xml;
use Inspire\Dfe\Mdfe;

/**
 * Distributes documents and information of interest to the MDF-e actor configuration and sent
 */
try {
    $mdfe = new Mdfe([
        'mod' => '58',
        'version' => '1.00',
        'saveFiles' => true,
        'xUF' => 'RS',
        'tpAmb' => 1,
        'CNPJ' => $CNPJ,
        'xUFAut' => 'RS',
        'schemaBasePath' => ROOT_DIR . '/tests/schemas'
        // Optionals
        // 'date' => '2021-02-28'
        // 'UF' => '43',
    ], $cert);
//     \Inspire\Dfe\Parser\Mdfe::getDistDfeSchemas([
//         'procEventoMDFe'
//     ]);
    $send = $mdfe->MDFeDistribuicaoDFe('[NSU]');
    if ($send->isOk()) {
        var_dump($send->getExtra());
    } else {
        echo "ERROR: {$send->getMessage()['message']}\n";
    }
} catch (Exception $ex) {
    var_dump($ex->getMessage());
}