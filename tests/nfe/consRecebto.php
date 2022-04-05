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
        'version' => '1.00',
        'saveFiles' => true,
        'xUF' => 'SC',
        'tpAmb' => 1,
        'CNPJ' => $CNPJ,
        // 'xUFAut' => 'AN',
        'schemaPath' => ROOT_DIR . '/tests/schemas/Evento_ManifestaDest_PL_v101'
        // Optionals
        // 'date' => '2021-02-28'
        // 'UF' => '43',
    ], $cert);
    $send = $nfe->confRecebto('[chave]', '[sq]', '[evento]', '[xJust]');
    if ($send->isOk()) {
        var_dump($send->getExtra());
    } else {
        echo "ERROR: {$send->getMessage()['message']}\n";
    }
} catch (Exception $ex) {
    var_dump($ex->getMessage());
}