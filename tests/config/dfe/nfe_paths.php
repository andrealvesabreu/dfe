<?php
/**
 * SEFAZ NFe System WS list
 *
 * @author aalves
 */
/**
 * NfeAutorizacao
 * NfeRetAutorizacao
 * NfeInutilizacao
 * NfeConsulta
 * NfeStatusServico
 * NfeConsultaCadastro
 * NFeRecepcaoEvento
 * NfeDistribuicaoDFe
 */
return [
    '1' => [
        'signed' => ROOT_DIR . '/storage/xmls/nfe/:CNPJ/:YEAR:MONTH/producao/assinadas',
        'authorized' => ROOT_DIR . '/storage/xmls/nfe/:CNPJ/:YEAR:MONTH/producao/validadas',
        'NfeStatusServico' => [
            'request' => ROOT_DIR . '/storage/xmls/nfe/mensagens/producao/:CNPJ/:YEAR:MONTH/statusservico',
            'response' => ROOT_DIR . '/storage/xmls/nfe/mensagens/producao/:CNPJ/:YEAR:MONTH/statusservico'
        ],
        'NfeDistribuicaoDFe' => [
            'request' => ROOT_DIR . '/storage/xmls/nfe/mensagens/producao/:CNPJ/:YEAR:MONTH/distdfe',
            'response' => ROOT_DIR . '/storage/xmls/nfe/mensagens/producao/:CNPJ/:YEAR:MONTH/distdfe'
        ],
        'RecepcaoEvento' => [ // Events. Each event must have 3 index: request, response and document paths
            'e210200' => [ // Document correction event
                'request' => ROOT_DIR . '/storage/xmls/nfe/eventos/producao/:CNPJ/:YEAR:MONTH/e210200',
                'response' => ROOT_DIR . '/storage/xmls/nfe/eventos/producao/:CNPJ/:YEAR:MONTH/e210200',
                'document' => ROOT_DIR . '/storage/xmls/nfe/:CNPJ/:YEAR:MONTH/producao/e210200'
            ],
            'e210210' => [ // Document correction event
                'request' => ROOT_DIR . '/storage/xmls/nfe/eventos/producao/:CNPJ/:YEAR:MONTH/e210210',
                'response' => ROOT_DIR . '/storage/xmls/nfe/eventos/producao/:CNPJ/:YEAR:MONTH/e210210',
                'document' => ROOT_DIR . '/storage/xmls/nfe/:CNPJ/:YEAR:MONTH/producao/e210210'
            ],
            'e210220' => [ // Document correction event
                'request' => ROOT_DIR . '/storage/xmls/nfe/eventos/producao/:CNPJ/:YEAR:MONTH/e210220',
                'response' => ROOT_DIR . '/storage/xmls/nfe/eventos/producao/:CNPJ/:YEAR:MONTH/e210220',
                'document' => ROOT_DIR . '/storage/xmls/nfe/:CNPJ/:YEAR:MONTH/producao/e210220'
            ],
            'e210240' => [ // Document correction event
                'request' => ROOT_DIR . '/storage/xmls/nfe/eventos/producao/:CNPJ/:YEAR:MONTH/e210240',
                'response' => ROOT_DIR . '/storage/xmls/nfe/eventos/producao/:CNPJ/:YEAR:MONTH/e210240',
                'document' => ROOT_DIR . '/storage/xmls/nfe/:CNPJ/:YEAR:MONTH/producao/e210240'
            ]
        ]
    ],
    '2' => [
        'signed' => ROOT_DIR . '/storage/xmls/nfe/:CNPJ/:YEAR:MONTH/homologacao/assinadas',
        'authorized' => ROOT_DIR . '/storage/xmls/nfe/:CNPJ/:YEAR:MONTH/homologacao/validadas',
        'NfeStatusServico' => [
            'request' => ROOT_DIR . '/storage/xmls/nfe/mensagens/homologacao/:CNPJ/:YEAR:MONTH/statusservico',
            'response' => ROOT_DIR . '/storage/xmls/nfe/mensagens/homologacao/:CNPJ/:YEAR:MONTH/statusservico'
        ],
        'NfeDistribuicaoDFe' => [
            'request' => ROOT_DIR . '/storage/xmls/nfe/mensagens/homologacao/:CNPJ/:YEAR:MONTH/distdfe',
            'response' => ROOT_DIR . '/storage/xmls/nfe/mensagens/homologacao/:CNPJ/:YEAR:MONTH/distdfe'
        ],
        'RecepcaoEvento' => [ // Events. Each event must have 3 index: request, response and document paths
            'e210200' => [ // Document correction event
                'request' => ROOT_DIR . '/storage/xmls/nfe/eventos/homologacao/:CNPJ/:YEAR:MONTH/e210200',
                'response' => ROOT_DIR . '/storage/xmls/nfe/eventos/homologacao/:CNPJ/:YEAR:MONTH/e210200',
                'document' => ROOT_DIR . '/storage/xmls/nfe/:CNPJ/:YEAR:MONTH/homologacao/e210200'
            ],
            'e210210' => [ // Document correction event
                'request' => ROOT_DIR . '/storage/xmls/nfe/eventos/homologacao/:CNPJ/:YEAR:MONTH/e210210',
                'response' => ROOT_DIR . '/storage/xmls/nfe/eventos/homologacao/:CNPJ/:YEAR:MONTH/e210210',
                'document' => ROOT_DIR . '/storage/xmls/nfe/:CNPJ/:YEAR:MONTH/homologacao/e210210'
            ],
            'e210220' => [ // Document correction event
                'request' => ROOT_DIR . '/storage/xmls/nfe/eventos/homologacao/:CNPJ/:YEAR:MONTH/e210220',
                'response' => ROOT_DIR . '/storage/xmls/nfe/eventos/homologacao/:CNPJ/:YEAR:MONTH/e210220',
                'document' => ROOT_DIR . '/storage/xmls/nfe/:CNPJ/:YEAR:MONTH/homologacao/e210220'
            ],
            'e210240' => [ // Document correction event
                'request' => ROOT_DIR . '/storage/xmls/nfe/eventos/homologacao/:CNPJ/:YEAR:MONTH/e210240',
                'response' => ROOT_DIR . '/storage/xmls/nfe/eventos/homologacao/:CNPJ/:YEAR:MONTH/e210240',
                'document' => ROOT_DIR . '/storage/xmls/nfe/:CNPJ/:YEAR:MONTH/homologacao/e210240'
            ]
        ]
    ]
];