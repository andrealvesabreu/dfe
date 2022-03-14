<?php
include 'cte_base.php';
use Inspire\Dfe\Cte;
use Inspire\Dfe\Certificate;
use Inspire\Config\Config;
use Inspire\Validator\Variable;
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
        'schemaPath' => ROOT_DIR . '/tests/schemas/CTe3.00a'
        // Optionals
        // 'date' => '2021-02-28',
        // 'UF' => '43'
    ], $cert);
    $send = $cte->CteRecepcao('123465789', [
        'onecte',
        'anothercte'
    ]);
    if ($send->isOk()) {
        var_dump($send->getExtra());
    } else {
        echo "ERROR: {$send->getMessage()['message']}\n";
    }
} catch (Exception $ex) {
    var_dump($ex->getMessage());
}