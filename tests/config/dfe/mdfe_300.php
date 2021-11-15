<?php
/**
 * SEFAZ MDFe System WS list
 *
 * @author aalves
 */
return [
    '1' => [
        'RS' => [
            'MDFeRecepcao' => [
                'method' => 'mdfeRecepcaoLote',
                'operation' => 'MDFeRecepcao',
                'version' => '3.00',
                'url' => 'https://mdfe.svrs.rs.gov.br/ws/MDFerecepcao/MDFeRecepcao.asmx'
            ],
            'MDFeRetRecepcao' => [
                'method' => 'mdfeRetRecepcao',
                'operation' => 'MDFeRetRecepcao',
                'version' => '3.00',
                'url' => 'https://mdfe.svrs.rs.gov.br/ws/MDFeRetRecepcao/MDFeRetRecepcao.asmx'
            ],
            'MDFeConsulta' => [
                'method' => 'mdfeConsultaMDF',
                'operation' => 'MDFeConsulta',
                'version' => '3.00',
                'url' => 'https://mdfe.svrs.rs.gov.br/ws/MDFeConsulta/MDFeConsulta.asmx'
            ],
            'MDFeStatusServico' => [
                'method' => 'mdfeStatusServicoMDF',
                'operation' => 'MDFeStatusServico',
                'version' => '3.00',
                'url' => 'https://mdfe.svrs.rs.gov.br/ws/MDFeStatusServico/MDFeStatusServico.asmx'
            ],
            'MDFeConsNaoEnc' => [
                'method' => 'mdfeConsNaoEnc',
                'operation' => 'MDFeConsNaoEnc',
                'version' => '3.00',
                'url' => 'https://mdfe.svrs.rs.gov.br/ws/MDFeConsNaoEnc/MDFeConsNaoEnc.asmx'
            ],
            'MDFeRecepcaoEvento' => [
                'method' => 'mdfeRecepcaoEvento',
                'operation' => 'MDFeRecepcaoEvento',
                'version' => '3.00',
                'url' => 'https://mdfe.svrs.rs.gov.br/ws/MDFeRecepcaoEvento/MDFeRecepcaoEvento.asmx'
            ],
            'MDFeDistribuicaoDFe' => [
                'method' => 'mdfeDistDFeInteresse',
                'operation' => 'MDFeDistribuicaoDFe',
                'version' => '1.00',
                'url' => 'https://mdfe.svrs.rs.gov.br/ws/MDFeDistribuicaoDFe/MDFeDistribuicaoDFe.asmx'
            ]
        ]
    ],
    '2' => [
        'RS' => [
            'MDFeRecepcao' => [
                'method' => 'mdfeRecepcaoLote',
                'operation' => 'MDFeRecepcao',
                'version' => '3.00',
                'url' => 'https://mdfe-homologacao.svrs.rs.gov.br/ws/MDFerecepcao/MDFeRecepcao.asmx'
            ],
            'MDFeRetRecepcao' => [
                'method' => 'mdfeRetRecepcao',
                'operation' => 'MDFeRetRecepcao',
                'version' => '3.00',
                'url' => 'https://mdfe-homologacao.svrs.rs.gov.br/ws/MDFeRetRecepcao/MDFeRetRecepcao.asmx'
            ],
            'MDFeConsulta' => [
                'method' => 'mdfeConsultaMDF',
                'operation' => 'MDFeConsulta',
                'version' => '3.00',
                'url' => 'https://mdfe-homologacao.svrs.rs.gov.br/ws/MDFeConsulta/MDFeConsulta.asmx'
            ],
            'MDFeStatusServico' => [
                'method' => 'mdfeStatusServicoMDF',
                'operation' => 'MDFeStatusServico',
                'version' => '3.00',
                'url' => 'https://mdfe-homologacao.svrs.rs.gov.br/ws/MDFeStatusServico/MDFeStatusServico.asmx'
            ],
            'MDFeConsNaoEnc' => [
                'method' => 'mdfeConsNaoEnc',
                'operation' => 'MDFeConsNaoEnc',
                'version' => '3.00',
                'url' => 'https://mdfe-homologacao.svrs.rs.gov.br/ws/MDFeConsNaoEnc/MDFeConsNaoEnc.asmx'
            ],
            'MDFeRecepcaoEvento' => [
                'method' => 'mdfeRecepcaoEvento',
                'operation' => 'MDFeRecepcaoEvento',
                'version' => '3.00',
                'url' => 'https://mdfe-homologacao.svrs.rs.gov.br/ws/MDFeRecepcaoEvento/MDFeRecepcaoEvento.asmx'
            ],
            'MDFeDistribuicaoDFe' => [
                'method' => 'mdfeDistDFeInteresse',
                'operation' => 'MDFeDistribuicaoDFe',
                'version' => '1.00',
                'url' => 'https://mdfe-homologacao.svrs.rs.gov.br/ws/MDFeDistribuicaoDFe/MDFeDistribuicaoDFe.asmx'
            ]
        ]
    ]
];