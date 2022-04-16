<?php
/**
 * SEFAZ MDFe System WS list
 *
 * @author aalves
 */
/**
 * DTeRecepcaoLote
 * DTeRetRecepcao
 * DTeRecepcaoEvento
 */
return [
    '1' => [
        'signed' => ROOT_DIR . '/storage/xmls/dte/:CNPJ/:YEAR:MONTH/producao/assinadas',
        'authorized' => ROOT_DIR . '/storage/xmls/dte/:CNPJ/:YEAR:MONTH/producao/validadas',
        'DTeRecepcao' => [
            'request' => ROOT_DIR . '/storage/xmls/dte/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/dte/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'DTeRetRecepcao' => [
            'request' => ROOT_DIR . '/storage/xmls/dte/mensagens/producao/:CNPJ/:YEAR:MONTH/consulta',
            'response' => ROOT_DIR . '/storage/xmls/dte/mensagens/producao/:CNPJ/:YEAR:MONTH/consulta'
        ],
        'DTeRecepcaoEvento' => [
            'request' => ROOT_DIR . '/storage/xmls/mdfe/eventos/producao/:CNPJ/:YEAR:MONTH',
            'response' => ROOT_DIR . '/storage/xmls/mdfe/eventos/producao/:CNPJ/:YEAR:MONTH'
        ]
    ],
    '2' => [
        'signed' => ROOT_DIR . '/storage/xmls/dte/:CNPJ/:YEAR:MONTH/homologacao/assinadas',
        'authorized' => ROOT_DIR . '/storage/xmls/dte/:CNPJ/:YEAR:MONTH/homologacao/validadas',
        'DTeRecepcao' => [
            'request' => ROOT_DIR . '/storage/xmls/dte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/dte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'DTeRetRecepcao' => [
            'request' => ROOT_DIR . '/storage/xmls/dte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/consulta',
            'response' => ROOT_DIR . '/storage/xmls/dte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/consulta'
        ],
        'DTeRecepcaoEvento' => [
            'request' => ROOT_DIR . '/storage/xmls/mdfe/eventos/homologacao/:CNPJ/:YEAR:MONTH',
            'response' => ROOT_DIR . '/storage/xmls/mdfe/eventos/homologacao/:CNPJ/:YEAR:MONTH'
        ]
    ]
];