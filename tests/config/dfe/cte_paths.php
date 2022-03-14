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
        'signed' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/assinadas', // Signed file path. Required
        'authorized' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/validadas', // Authorized file path. Required
        'CteRecepcao' => [ // Cargo Transport CT-e Reception Service request and response
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'CTeRecepcaoOS' => [ // Cargo Transport CT-e OS Reception Service request and response
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'CteRetRecepcao' => [ // CT-e Batch Processing Inquiry request and response
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'CteInutilizacao' => [ // Invalidate CT -e numbering request and response
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/inutilizacao',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/inutilizacao'
        ],
        'CteConsulta' => [ // Check current status of CT -e request and response
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/consulta',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/consulta'
        ],
        'CteStatusServico' => [ // Service Status Query request and response
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/statusservico',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/statusservico'
        ],
        // 'CteConsultaCadastro'=>[
        // 'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/statusservico',
        // 'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/statusservico'
        // ],
        'CteRecepcaoEvento' => [ // Events. Each event must have 3 index: request, response and document paths
            'evCCeCTe' => [ // Document correction event
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evcce',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evcce',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/cartacorrecao'
            ],
            'evCancCTe' => [ // Document cancellation event
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evcanc',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evcanc',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/canceladas'
            ],
            'evEPECCTe' => [ // Event intended to meet requests for issuance in CT-e contingency.
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evepec',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evepec',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/contingency'
            ],
            'evRegMultimodal' => [ // Event designed to link information on services provided to CT-e multimodal. Note that, if a CT-e is issued that is already linked to the multimodal CT-e, it is not necessary to inform it for this event.
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evregmult',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evregmult',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/multimodal'
            ],
            'evGTV' => [ // Event so that the CT-e OS issuer of the Transport of Values type of service can inform the GTV related to the provision of the service
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evgtv',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evgtv',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/gtv'
            ],
            'evCECTe' => [ // Proof of delivery event
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evce',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evce',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/eventos/evce'
            ],
            'evCancCECTe' => [ // Proof of delivery cancellation event
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evcancce',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/evcancce',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/eventos/evcancce'
            ]
        ],
        'CTeDistribuicaoDFe' => [ // Distributes documents and information of interest to the CT-e actor request and response
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/distdfe',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/producao/:CNPJ/:YEAR:MONTH/distdfe'
        ]
    ],
    '2' => [
        'signed' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/assinadas', // Signed file path. Required
        'authorized' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/validadas', // Authorized file path. Required
        'CteRecepcao' => [ // Cargo Transport CT-e Reception Service request and response
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'CTeRecepcaoOS' => [ // Cargo Transport CT-e OS Reception Service request and response
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'CteRetRecepcao' => [ // CT-e Batch Processing Inquiry request and response
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao',
            'document' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/emissao'
        ],
        'CteInutilizacao' => [ // Invalidate CT -e numbering request and response
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/inutilizacao',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/inutilizacao',
            'document' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/inutilizacao'
        ],
        'CteConsulta' => [ // Check current status of CT -e request and response
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/consulta',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/consulta'
        ],
        'CteStatusServico' => [ // Service Status Query request and response
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/statusservico',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/statusservico'
        ],
        // 'CteConsultaCadastro'=>[
        // 'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/statusservico',
        // 'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/statusservico'
        // ],
        'CteRecepcaoEvento' => [ // Events. Each event must have 3 index: request, response and document paths
            'evCCeCTe' => [ // Document correction event
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evcce',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evcce',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/cartacorrecao'
            ],
            'evCancCTe' => [ // Document cancellation event
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evcanc',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evcanc',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/canceladas'
            ],
            'evEPECCTe' => [ // Event intended to meet requests for issuance in CT-e contingency.
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evepec',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evepec',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/contingency'
            ],
            'evRegMultimodal' => [ // Event designed to link information on services provided to CT-e multimodal. Note that, if a CT-e is issued that is already linked to the multimodal CT-e, it is not necessary to inform it for this event.
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evregmult',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evregmult',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/multimodal'
            ],
            'evGTV' => [ // Event so that the CT-e OS issuer of the Transport of Values type of service can inform the GTV related to the provision of the service
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evgtv',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evgtv',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/gtv'
            ],
            'evCECTe' => [ // Proof of delivery event
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evce',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evce',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/eventos/evce'
            ],
            'evCancCECTe' => [ // Proof of delivery cancellation event
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evcancce',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/evcancce',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/eventos/evcancce'
            ]
        ],
        'CTeDistribuicaoDFe' => [ // Distributes documents and information of interest to the CT-e actor request and response
            'request' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/distdfe',
            'response' => ROOT_DIR . '/storage/xmls/cte/mensagens/homologacao/:CNPJ/:YEAR:MONTH/distdfe'
        ]
    ]
];