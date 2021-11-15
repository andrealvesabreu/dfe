<?php
/**
 * SEFAZ CTe System WS list
 *
 * @author aalves
 */
/**
 * CteRecepcao,
 * CTeRecepcaoOS
 * CteRetRecepcao,
 * CteInutilizacao,
 * CteConsulta,
 * CteStatusServico,
 * CteRecepcaoEvento,
 * CTeDistribuicaoDFe
 */
return [
    '1' => [
        'signed' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/assinadas',
        'authorized' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/validadas',
        'event' => [
            // "Evento de Carta de correção eletronica"
            'evcce' => [
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evcce',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evcce',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/cartacorrecao'
            ],
            // "Evento de Comprovante de entrega"
            'evce' => [
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evce',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evce',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/eventos/evce'
            ],
            // "Evento de cancelamento de Comprovante de entrega"
            'evcancce' => [
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evcancce',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evcancce',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/eventos/evcancce'
            ],
            // "Evento de cancelamento de CTe"
            'cancel' => [
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/cancel',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/cancel',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/canceladas'
            ]
        ],
        'CteRecepcao' => [
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'CTeRecepcaoOS' => [
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'CteRetRecepcao' => [
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'CteInutilizacao' => [
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/inutilizacao',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/inutilizacao'
        ],
        'CteConsulta' => [
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/consulta',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/consulta'
        ],
        'CteStatusServico' => [
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/statusservico',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/statusservico'
        ],
        'CTeDistribuicaoDFe' => [
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/distdfe',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/distdfe'
        ]
    ],
    '2' => [
        'signed' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/assinadas',
        'authorized' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/validadas',
        'event' => [
            // "Evento de Carta de correção eletronica"
            'evcce' => [
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evcce',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evcce',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/cartacorrecao'
            ],
            // "Evento de Comprovante de entrega"
            'evce' => [
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evce',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evce',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/eventos/evce'
            ],
            // "Evento de cancelamento de Comprovante de entrega"
            'evcancce' => [
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evcancce',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evcancce',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/eventos/evcancce'
            ],
            // "Evento de cancelamento de CTe"
            'cancel' => [
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/cancel',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/cancel',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/canceladas'
            ]
        ],
        'CteRecepcao' => [
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'CTeRecepcaoOS' => [
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'CteRetRecepcao' => [
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'CteInutilizacao' => [
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/inutilizacao',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/inutilizacao'
        ],
        'CteConsulta' => [
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/consulta',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/consulta'
        ],
        'CteStatusServico' => [
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/statusservico',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/statusservico'
        ],
        'CTeDistribuicaoDFe' => [
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/distdfe',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/distdfe'
        ]
    ]
];