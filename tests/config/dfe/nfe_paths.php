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
        ]
    ]
];