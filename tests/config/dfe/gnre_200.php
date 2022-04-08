<?php
/**
 * SEFAZ NFe System WS list
 *
 * @author aalves
 */
return [
    '1' => [
        'PE' => [
            'GnreRecepcaoLote' => [
                'method' => "processar",
                'operation' => "GnreLoteRecepcao",
                'version' => "2.00",
                'url' => 'https://www.gnre.pe.gov.br/gnreWS/services/GnreLoteRecepcao'
            ],
            'GnreResultadoLote' => [
                'method' => "consultar",
                'operation' => "GnreResultadoLote",
                'version' => "1.00",
                'url' => 'https://www.gnre.pe.gov.br/gnreWS/services/GnreResultadoLote'
            ],
            'GnreLoteRecepcaoConsulta' => [
                'method' => "processar",
                'operation' => "GnreLoteRecepcaoConsulta",
                'version' => "2.00",
                'url' => 'https://www.gnre.pe.gov.br/gnreWS/services/GnreLoteRecepcaoConsulta'
            ],
            'GnreResultadoLoteConsulta' => [
                'method' => "consultar",
                'operation' => "GnreResultadoLoteConsulta",
                'version' => "1.00",
                'url' => 'https://www.gnre.pe.gov.br/gnreWS/services/GnreResultadoLoteConsulta'
            ],
            'GnreConfigUF' => [
                'method' => "consultar",
                'operation' => "GnreConfigUF",
                'version' => "1.00",
                'url' => 'https://www.gnre.pe.gov.br/gnreWS/services/GnreConfigUF'
            ]
        ]
    ],
    '2' => [
        'PE' => [
            'GnreRecepcaoLote' => [
                'method' => "processar",
                'operation' => "GnreLoteRecepcao",
                'version' => "2.00",
                'url' => 'https://www.testegnre.pe.gov.br/gnreWS/services/GnreLoteRecepcao'
            ],
            'GnreResultadoLote' => [
                'method' => "consultar",
                'operation' => "GnreResultadoLote",
                'version' => "1.00",
                'url' => 'https://www.testegnre.pe.gov.br/gnreWS/services/GnreResultadoLote'
            ],
            'GnreLoteRecepcaoConsulta' => [
                'method' => "processar",
                'operation' => "GnreLoteRecepcaoConsulta",
                'version' => "2.00",
                'url' => 'https://www.testegnre.pe.gov.br/gnreWS/services/GnreLoteRecepcaoConsulta'
            ],
            'GnreResultadoLoteConsulta' => [
                'method' => "consultar",
                'operation' => "GnreResultadoLoteConsulta",
                'version' => "1.00",
                'url' => 'https://www.testegnre.pe.gov.br/gnreWS/services/GnreResultadoLoteConsulta'
            ],
            'GnreConfigUF' => [
                'method' => "consultar",
                'operation' => "GnreConfigUF",
                'version' => "1.00",
                'url' => 'https://www.testegnre.pe.gov.br/gnreWS/services/GnreConfigUF'
            ]
        ]
    ]
];
