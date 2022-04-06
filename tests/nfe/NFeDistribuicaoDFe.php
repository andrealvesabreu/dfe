<?php
include 'nfe_base.php';
use Inspire\Dfe\Nfe;
use Inspire\Dfe\Certificate;
use Inspire\Config\Config;
use Inspire\Validator\Variable;
use Inspire\Dfe\Cte\ParserResponse;
use Inspire\Support\Xml\Xml;

/**
 * Distributes documents and information of interest to the NF-e actor configuration and sent
 */
try {
    $nfe = new Nfe([
        'mod' => '55',
        'version' => '4.00',
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
    \Inspire\Dfe\Parser\Nfe::getDistDfeSchemas([
        'procNFe'
    ]);
    $send = $nfe->NfeDistribuicaoDFe(null, null, '[chave]');
    if ($send->isOk()) {
        var_dump($send->getExtra());
    } else {
        echo "ERROR: {$send->getMessage()['message']}\n";
    }
} catch (Exception $ex) {
    var_dump($ex->getMessage());
}