<?php
include 'cte_base.php';
use Inspire\Dfe\Cte;
use Inspire\Dfe\Certificate;
use Inspire\Config\Config;
use Inspire\Validator\Variable;
use Inspire\Dfe\Dfe;

// var_dump(Variable::nfeAccessKey()->validate(''));
// exit;

/**
 * Cargo Transport CT-e Reception Service configuration and sent
 */
try {
    $cte = new Cte([
        'mod' => '57',
        'version' => '3.00',
        'saveFiles' => true,
        'xUF' => 'RS',
        'tpAmb' => 2,
        'CNPJ' => $CNPJ,
        'schemaBasePath' => ROOT_DIR . '/tests/schemas'
        // Optionals
        // 'date' => '2021-02-28',
        // 'UF' => '43'
    ], $cert);
//     echo $cte->sign('', 'infCte', 'Id');
//     exit;
    $send = $cte->CteRecepcao('123465789', [
        'xml'
    ]);
    if ($send->isOk()) {
        var_dump($send->getExtra());
    } else {
        echo "ERROR: {$send->getMessage()['message']}\n";
    }
} catch (Exception $ex) {
    var_dump($ex->getMessage());
}