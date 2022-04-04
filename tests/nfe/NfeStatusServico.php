<?php
include 'nfe_base.php';
use Inspire\Dfe\Certificate;
use Inspire\Config\Config;
use Inspire\Dfe\Nfe;

/**
 * Service Status Query configuration and sent
 */
try {
    $nfe = new Nfe([
        'mod' => '55',
        'version' => '4.00',
        'saveFiles' => true,
        'xUF' => 'RS',
        'tpAmb' => 1,
        'CNPJ' => $CNPJ,
        'schemaPath' => ROOT_DIR . '/tests/schemas/NFe4.00a'
        // Optionals
        // 'date' => '2021-02-28',
        // 'UF' => '43'
    ], $cert);
    $send = $nfe->NfeStatusServico();
    if ($send->isOk()) {
        var_dump($send->getExtra());
    } else {
        echo "ERROR: {$send->getMessage()['message']}\n";
    }
} catch (Exception $ex) {
    var_dump($ex->getMessage());
}