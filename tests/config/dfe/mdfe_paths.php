<?php
/**
 * SEFAZ MDFe System WS list
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
        'MDFeRecepcaoEvento' => [
            'evIncCondutorMDFe' => [ // Include driver
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/incluirmotorista',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/incluirmotorista',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/incluirmotorista'
            ],
            'evInclusaoDFeMDFe' => [ // Include DFe
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/incluirdfe',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/incluirdfe',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/incluirdfe'
            ],
            'evCancMDFe' => [ // Cancel MDFe
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/cancelamento',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/cancelamento',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/cancelamento'
            ],
            'evEncMDFe' => [ // finish MDFe
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/encerramento',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/producao/:CNPJ/:YEAR:MONTH/encerramento',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/producao/encerramento'
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
        'MDFeRecepcaoEvento' => [
            'evIncCondutorMDFe' => [ // Include driver
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/incluirmotorista',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/incluirmotorista',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/incluirmotorista'
            ],
            'evInclusaoDFeMDFe' => [ // Include DFe
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/incluirdfe',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/incluirdfe',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/incluirdfe'
            ],
            'evCancMDFe' => [ // Cancel MDFe
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/cancelamento',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/cancelamento',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/cancelamento'
            ],
            'evEncMDFe' => [ // finish MDFe
                'request' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/encerramento',
                'response' => ROOT_DIR . '/storage/xmls/cte/eventos/homologacao/:CNPJ/:YEAR:MONTH/encerramento',
                'document' => ROOT_DIR . '/storage/xmls/cte/:CNPJ/:YEAR:MONTH/homologacao/encerramento'
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