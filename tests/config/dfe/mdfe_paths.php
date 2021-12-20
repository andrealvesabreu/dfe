<?php
/**
 * SEFAZ CTe System WS list
 *
 * @author aalves
 */
/**
 * MDFeRecepcao
 * MDFeRecepcaoSinc
 * MDFeRetRecepcao
 * MDFeConsulta
 * MDFeConsNaoEnc
 * MDFeStatusServico
 * MDFeRecepcaoEvento
 * MDFeDistribuicaoDFe
 */
return [
    '1' => [
        'signed' => ROOT_DIR . '/storage/xmls/mdfe/:CNPJ/:YEAR:MONTH/producao/assinadas',
        'authorized' => ROOT_DIR . '/storage/xmls/mdfe/:CNPJ/:YEAR:MONTH/producao/validadas',
        'event' => [
            // "Evento de cancelamento de MDFe"
            'cancel' => [
                'request' => ROOT_DIR . '/storage/xmls/mdfe/eventos/producao/:CNPJ/:YEAR:MONTH/cancel',
                'response' => ROOT_DIR . '/storage/xmls/mdfe/eventos/producao/:CNPJ/:YEAR:MONTH/cancel',
                'document' => ROOT_DIR . '/storage/xmls/mdfe/:CNPJ/:YEAR:MONTH/producao/canceladas'
            ]
        ],
        'MDFeRecepcao' => [
            'request' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'MDFeRecepcaoSinc' => [
            'request' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'MDFeRetRecepcao' => [
            'request' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'MDFeConsulta' => [
            'request' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/producao/:CNPJ/:YEAR:MONTH/consulta',
            'response' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/producao/:CNPJ/:YEAR:MONTH/consulta'
        ],
        'MDFeConsNaoEnc' => [
            'request' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/producao/:CNPJ/:YEAR:MONTH/consulta',
            'response' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/producao/:CNPJ/:YEAR:MONTH/consulta'
        ],
        'MDFeStatusServico' => [
            'request' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/producao/:CNPJ/:YEAR:MONTH/statusservico',
            'response' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/producao/:CNPJ/:YEAR:MONTH/statusservico'
        ],
        'MDFeDistribuicaoDFe' => [
            'request' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/producao/:CNPJ/:YEAR:MONTH/distdfe',
            'response' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/producao/:CNPJ/:YEAR:MONTH/distdfe'
        ]
    ],
    '2' => [
        'signed' => ROOT_DIR . '/storage/xmls/mdfe/:CNPJ/:YEAR:MONTH/homologacao/assinadas',
        'authorized' => ROOT_DIR . '/storage/xmls/mdfe/:CNPJ/:YEAR:MONTH/homologacao/validadas',
        'event' => [
            // "Evento de cancelamento de MDFe"
            'cancel' => [
                'request' => ROOT_DIR . '/storage/xmls/mdfe/eventos/homologacao/:CNPJ/:YEAR:MONTH/cancel',
                'response' => ROOT_DIR . '/storage/xmls/mdfe/eventos/homologacao/:CNPJ/:YEAR:MONTH/cancel',
                'document' => ROOT_DIR . '/storage/xmls/mdfe/:CNPJ/:YEAR:MONTH/homologacao/canceladas'
            ]
        ],
        'MDFeRecepcao' => [
            'request' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'MDFeRecepcaoSinc' => [
            'request' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'MDFeRetRecepcao' => [
            'request' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'MDFeConsulta' => [
            'request' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/homologacao/:CNPJ/:YEAR:MONTH/consulta',
            'response' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/homologacao/:CNPJ/:YEAR:MONTH/consulta'
        ],
        'MDFeConsNaoEnc' => [
            'request' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/homologacao/:CNPJ/:YEAR:MONTH/consulta',
            'response' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/homologacao/:CNPJ/:YEAR:MONTH/consulta'
        ],
        'MDFeStatusServico' => [
            'request' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/homologacao/:CNPJ/:YEAR:MONTH/statusservico',
            'response' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/homologacao/:CNPJ/:YEAR:MONTH/statusservico'
        ],
        'MDFeDistribuicaoDFe' => [
            'request' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/homologacao/:CNPJ/:YEAR:MONTH/distdfe',
            'response' => ROOT_DIR . '/storage/xmls/mdfe/mensagens/homologacao/:CNPJ/:YEAR:MONTH/distdfe'
        ]
    ]
];