<?php
/**
 * SEFAZ NFe System WS list
 *
 * @author aalves
 */
return [
    '1' => [
        'AM' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.am.gov.br/services2/services/NfeStatusServico4'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.am.gov.br/services2/services/NfeAutorizacao4'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsulta4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.am.gov.br/services2/services/NfeConsulta4'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.am.gov.br/services2/services/NfeInutilizacao4'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.am.gov.br/services2/services/NfeRetAutorizacao4'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://nfe.sefaz.am.gov.br/services2/services/RecepcaoEvento4'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => ''
            ]
        ],
        'AN' => [
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://www.nfe.fazenda.gov.br/NFeRecepcaoEvento4/NFeRecepcaoEvento4.asmx'
            ],
            'NfeDistribuicaoDFe' => [
                'method' => "nfeDistDFeInteresse",
                'operation' => "NFeDistribuicaoDFe",
                'version' => "1.00",
                'url' => 'https://www1.nfe.fazenda.gov.br/NFeDistribuicaoDFe/NFeDistribuicaoDFe.asmx'
            ],
            'NfeConsultaDest' => [
                'method' => "nfeConsultaNFDest",
                'operation' => "NfeConsultaDest",
                'version' => "1.01",
                'url' => 'https://www.nfe.fazenda.gov.br/NFeConsultaDest/NFeConsultaDest.asmx'
            ],
            'NfeDownloadNF' => [
                'method' => "nfeDownloadNF",
                'operation' => "NfeDownloadNF",
                'version' => "4.00",
                'url' => 'https://www.nfe.fazenda.gov.br/NfeDownloadNF/NfeDownloadNF.asmx'
            ],
            'RecepcaoEPEC' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "RecepcaoEvento",
                'version' => "4.00",
                'url' => 'https://www.nfe.fazenda.gov.br/RecepcaoEvento/RecepcaoEvento.asmx'
            ]
        ],
        'BA' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.ba.gov.br/webservices/NFeStatusServico4/NFeStatusServico4.asmx'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.ba.gov.br/webservices/NFeAutorizacao4/NFeAutorizacao4.asmx'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.ba.gov.br/webservices/NFeConsultaProtocolo4/NFeConsultaProtocolo4.asmx'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.ba.gov.br/webservices/NFeInutilizacao4/NFeInutilizacao4.asmx'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.ba.gov.br/webservices/NFeRetAutorizacao4/NFeRetAutorizacao4.asmx'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://nfe.sefaz.ba.gov.br/webservices/NFeRecepcaoEvento4/NFeRecepcaoEvento4.asmx'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => 'https://nfe.sefaz.ba.gov.br/webservices/CadConsultaCadastro4/CadConsultaCadastro4.asmx'
            ]
        ],
        'CE' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.ce.gov.br/nfe4/services/NFeStatusServico4'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.ce.gov.br/nfe4/services/NFeAutorizacao4'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.ce.gov.br/nfe4/services/NFeConsultaProtocolo4'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.ce.gov.br/nfe4/services/NFeInutilizacao4'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.ce.gov.br/nfe4/services/NFeRetAutorizacao4'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://nfe.sefaz.ce.gov.br/nfe4/services/NFeRecepcaoEvento4'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => 'https://nfe.sefaz.ce.gov.br/nfe4/services/CadConsultaCadastro4'
            ]
        ],
        'GO' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.go.gov.br/nfe/services/NFeStatusServico4'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.go.gov.br/nfe/services/NFeAutorizacao4'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.go.gov.br/nfe/services/NFeConsultaProtocolo4'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.go.gov.br/nfe/services/NFeInutilizacao4'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.go.gov.br/nfe/services/NFeRetAutorizacao4'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://nfe.sefaz.go.gov.br/nfe/services/NFeRecepcaoEvento4'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => 'https://nfe.sefaz.go.gov.br/nfe/services/CadConsultaCadastro4'
            ]
        ],
        'MG' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://nfe.fazenda.mg.gov.br/nfe2/services/NFeStatusServico4'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.fazenda.mg.gov.br/nfe2/services/NFeAutorizacao4'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://nfe.fazenda.mg.gov.br/nfe2/services/NFeConsultaProtocolo4'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.fazenda.mg.gov.br/nfe2/services/NFeInutilizacao4'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.fazenda.mg.gov.br/nfe2/services/NFeRetAutorizacao4'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://nfe.fazenda.mg.gov.br/nfe2/services/NFeRecepcaoEvento4'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => ''
            ]
        ],
        'MS' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.ms.gov.br/ws/NFeStatusServico4'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.ms.gov.br/ws/NFeAutorizacao4'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.ms.gov.br/ws/NFeConsultaProtocolo4'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.ms.gov.br/ws/NFeInutilizacao4'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.ms.gov.br/ws/NFeRetAutorizacao4'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://nfe.sefaz.ms.gov.br/ws/NFeRecepcaoEvento4'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => 'https://nfe.sefaz.ms.gov.br/ws/CadConsultaCadastro4'
            ]
        ],
        'MT' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.mt.gov.br/nfews/v2/services/NfeStatusServico4'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.mt.gov.br/nfews/v2/services/NfeAutorizacao4'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsulta4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.mt.gov.br/nfews/v2/services/NfeConsulta4'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.mt.gov.br/nfews/v2/services/NfeInutilizacao4'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.mt.gov.br/nfews/v2/services/NfeRetAutorizacao4'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://nfe.sefaz.mt.gov.br/nfews/v2/services/RecepcaoEvento4'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => 'https://nfe.sefaz.mt.gov.br/nfews/v2/services/CadConsultaCadastro4'
            ]
        ],
        'PE' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.pe.gov.br/nfe-service/services/NFeStatusServico4'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.pe.gov.br/nfe-service/services/NFeAutorizacao4'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.pe.gov.br/nfe-service/services/NFeConsultaProtocolo4'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.pe.gov.br/nfe-service/services/NFeInutilizacao4'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefaz.pe.gov.br/nfe-service/services/NFeRetAutorizacao4'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://nfe.sefaz.pe.gov.br/nfe-service/services/RecepcaoEvento4'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => 'https://nfe.sefaz.pe.gov.br/nfe-service/services/CadConsultaCadastro4'
            ]
        ],
        'PR' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://nfe.sefa.pr.gov.br/nfe/NFeStatusServico4'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefa.pr.gov.br/nfe/NFeAutorizacao4'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://nfe.sefa.pr.gov.br/nfe/NFeConsultaProtocolo4'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefa.pr.gov.br/nfe/NFeInutilizacao4'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacao",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefa.pr.gov.br/nfe/NFeRetAutorizacao4'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://nfe.sefa.pr.gov.br/nfe/NFeRecepcaoEvento4'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => 'https://nfe.sefa.pr.gov.br/nfe/CadConsultaCadastro4'
            ]
        ],
        'RS' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://nfe.sefazrs.rs.gov.br/ws/NfeStatusServico/NfeStatusServico4.asmx'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefazrs.rs.gov.br/ws/NfeAutorizacao/NFeAutorizacao4.asmx'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://nfe.sefazrs.rs.gov.br/ws/NfeConsulta/NfeConsulta4.asmx'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefazrs.rs.gov.br/ws/nfeinutilizacao/nfeinutilizacao4.asmx'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.sefazrs.rs.gov.br/ws/NfeRetAutorizacao/NFeRetAutorizacao4.asmx'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://nfe.sefazrs.rs.gov.br/ws/recepcaoevento/recepcaoevento4.asmx'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => 'https://cad.sefazrs.rs.gov.br/ws/cadconsultacadastro/cadconsultacadastro4.asmx'
            ]
        ],
        'SP' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://nfe.fazenda.sp.gov.br/ws/nfestatusservico4.asmx'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.fazenda.sp.gov.br/ws/nfeautorizacao4.asmx'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://nfe.fazenda.sp.gov.br/ws/nfeconsultaprotocolo4.asmx'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.fazenda.sp.gov.br/ws/nfeinutilizacao4.asmx'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.fazenda.sp.gov.br/ws/nferetautorizacao4.asmx'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://nfe.fazenda.sp.gov.br/ws/nferecepcaoevento4.asmx'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => 'https://nfe.fazenda.sp.gov.br/ws/cadconsultacadastro4.asmx'
            ]
        ],
        'SVAN' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://www.sefazvirtual.fazenda.gov.br/NFeStatusServico4/NFeStatusServico4.asmx'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://www.sefazvirtual.fazenda.gov.br/NFeAutorizacao4/NFeAutorizacao4.asmx'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://www.sefazvirtual.fazenda.gov.br/NFeConsultaProtocolo4/NFeConsultaProtocolo4.asmx'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://www.sefazvirtual.fazenda.gov.br/NFeInutilizacao4/NFeInutilizacao4.asmx'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://www.sefazvirtual.fazenda.gov.br/NFeRetAutorizacao4/NFeRetAutorizacao4.asmx'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://www.sefazvirtual.fazenda.gov.br/NFeRecepcaoEvento4/NFeRecepcaoEvento4.asmx'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => ''
            ]
        ],
        'SVRS' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://nfe.svrs.rs.gov.br/ws/NfeStatusServico/NfeStatusServico4.asmx'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.svrs.rs.gov.br/ws/NfeAutorizacao/NFeAutorizacao4.asmx'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://nfe.svrs.rs.gov.br/ws/NfeConsulta/NfeConsulta4.asmx'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.svrs.rs.gov.br/ws/nfeinutilizacao/nfeinutilizacao4.asmx'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.svrs.rs.gov.br/ws/NfeRetAutorizacao/NFeRetAutorizacao4.asmx'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://nfe.svrs.rs.gov.br/ws/recepcaoevento/recepcaoevento4.asmx'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => 'https://cad.svrs.rs.gov.br/ws/cadconsultacadastro/cadconsultacadastro4.asmx'
            ]
        ],
        'SVCAN' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://www.svc.fazenda.gov.br/NFeStatusServico4/NFeStatusServico4.asmx'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://www.svc.fazenda.gov.br/NFeAutorizacao4/NFeAutorizacao4.asmx'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://www.svc.fazenda.gov.br/NFeConsultaProtocolo4/NFeConsultaProtocolo4.asmx'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => ''
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://www.svc.fazenda.gov.br/NFeRetAutorizacao4/NFeRetAutorizacao4.asmx'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://www.svc.fazenda.gov.br/NFeRecepcaoEvento4/NFeRecepcaoEvento4.asmx'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => ''
            ]
        ],
        'SVCRS' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://nfe.svrs.rs.gov.br/ws/NfeStatusServico/NfeStatusServico4.asmx'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.svrs.rs.gov.br/ws/NfeAutorizacao/NFeAutorizacao4.asmx'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsulta4",
                'version' => "4.00",
                'url' => 'https://nfe.svrs.rs.gov.br/ws/NfeConsulta/NfeConsulta4.asmx'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.svrs.rs.gov.br/ws/nfeinutilizacao/nfeinutilizacao4.asmx'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe.svrs.rs.gov.br/ws/NfeRetAutorizacao/NFeRetAutorizacao4.asmx'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://nfe.svrs.rs.gov.br/ws/recepcaoevento/recepcaoevento4.asmx'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => 'https://cad.svrs.rs.gov.br/ws/cadconsultacadastro/cadconsultacadastro4.asmx'
            ]
        ]
    ],
    '2' => [
        'AM' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://homnfe.sefaz.am.gov.br/services2/services/NfeStatusServico4'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://homnfe.sefaz.am.gov.br/services2/services/NfeAutorizacao4'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsulta4",
                'version' => "4.00",
                'url' => 'https://homnfe.sefaz.am.gov.br/services2/services/NfeConsulta4'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://homnfe.sefaz.am.gov.br/services2/services/NfeInutilizacao4'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://homnfe.sefaz.am.gov.br/services2/services/NfeRetAutorizacao4'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://homnfe.sefaz.am.gov.br/services2/services/RecepcaoEvento4'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => ''
            ]
        ],
        'AN' => [
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://hom.nfe.fazenda.gov.br/NFeRecepcaoEvento4/NFeRecepcaoEvento4.asmx'
            ],
            'NfeDistribuicaoDFe' => [
                'method' => "nfeDistDFeInteresse",
                'operation' => "NFeDistribuicaoDFe",
                'version' => "1.01",
                'url' => 'https://hom.nfe.fazenda.gov.br/NFeDistribuicaoDFe/NFeDistribuicaoDFe.asmx'
            ],
            'NfeConsultaDest' => [
                'method' => "nfeConsultaNFDest",
                'operation' => "NfeConsultaDest",
                'version' => "1.01",
                'url' => 'https://hom.nfe.fazenda.gov.br/NFeConsultaDest/NFeConsultaDest.asmx'
            ],
            'NfeDownloadNF' => [
                'method' => "nfeDownloadNF",
                'operation' => "NfeDownloadNF",
                'version' => "4.00",
                'url' => 'https://hom.nfe.fazenda.gov.br/NfeDownloadNF/NfeDownloadNF.asmx'
            ],
            'RecepcaoEPEC' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "RecepcaoEvento",
                'version' => "4.00",
                'url' => 'https://hom.nfe.fazenda.gov.br/RecepcaoEvento/RecepcaoEvento.asmx'
            ]
        ],
        'BA' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://hnfe.sefaz.ba.gov.br/webservices/NFeStatusServico4/NFeStatusServico4.asmx'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://hnfe.sefaz.ba.gov.br/webservices/NFeAutorizacao4/NFeAutorizacao4.asmx'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://hnfe.sefaz.ba.gov.br/webservices/NFeConsultaProtocolo4/NFeConsultaProtocolo4.asmx'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://hnfe.sefaz.ba.gov.br/webservices/NFeInutilizacao4/NFeInutilizacao4.asmx'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://hnfe.sefaz.ba.gov.br/webservices/NFeRetAutorizacao4/NFeRetAutorizacao4.asmx'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://hnfe.sefaz.ba.gov.br/webservices/NFeRecepcaoEvento4/NFeRecepcaoEvento4.asmx'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => 'https://hnfe.sefaz.ba.gov.br/webservices/CadConsultaCadastro4/CadConsultaCadastro4.asmx'
            ]
        ],
        'CE' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://nfeh.sefaz.ce.gov.br/nfe4/services/NFeStatusServico4'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfeh.sefaz.ce.gov.br/nfe4/services/NFeAutorizacao4'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://nfeh.sefaz.ce.gov.br/nfe4/services/NFeConsultaProtocolo4'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://nfeh.sefaz.ce.gov.br/nfe4/services/NFeInutilizacao4'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfeh.sefaz.ce.gov.br/nfe4/services/NFeRetAutorizacao4'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://nfeh.sefaz.ce.gov.br/nfe4/services/NFeRecepcaoEvento4'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => ''
            ]
        ],
        'GO' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://homolog.sefaz.go.gov.br/nfe/services/NFeStatusServico4'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://homolog.sefaz.go.gov.br/nfe/services/NFeAutorizacao4'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://homolog.sefaz.go.gov.br/nfe/services/NFeConsultaProtocolo4'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://homolog.sefaz.go.gov.br/nfe/services/NFeInutilizacao4'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://homolog.sefaz.go.gov.br/nfe/services/NFeRetAutorizacao4'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://homolog.sefaz.go.gov.br/nfe/services/NFeRecepcaoEvento4'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => 'https://homolog.sefaz.go.gov.br/nfe/services/CadConsultaCadastro4'
            ]
        ],
        'MG' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://hnfe.fazenda.mg.gov.br/nfe2/services/NFeStatusServico4'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://hnfe.fazenda.mg.gov.br/nfe2/services/NFeAutorizacao4'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://hnfe.fazenda.mg.gov.br/nfe2/services/NFeConsultaProtocolo4'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://hnfe.fazenda.mg.gov.br/nfe2/services/NFeInutilizacao4'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://hnfe.fazenda.mg.gov.br/nfe2/services/NFeRetAutorizacao4'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://hnfe.fazenda.mg.gov.br/nfe2/services/NFeRecepcaoEvento4'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => ''
            ]
        ],
        'MS' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://hom.nfe.sefaz.ms.gov.br/ws/NFeStatusServico4'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://hom.nfe.sefaz.ms.gov.br/ws/NFeAutorizacao4'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://hom.nfe.sefaz.ms.gov.br/ws/NFeConsultaProtocolo4'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://hom.nfe.sefaz.ms.gov.br/ws/NFeInutilizacao4'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://hom.nfe.sefaz.ms.gov.br/ws/NFeRetAutorizacao4'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://hom.nfe.sefaz.ms.gov.br/ws/NFeRecepcaoEvento4'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => 'https://hom.nfe.sefaz.ms.gov.br/ws/CadConsultaCadastro4'
            ]
        ],
        'MT' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://homologacao.sefaz.mt.gov.br/nfews/v2/services/NfeStatusServico4'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://homologacao.sefaz.mt.gov.br/nfews/v2/services/NfeAutorizacao4'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsulta4",
                'version' => "4.00",
                'url' => 'https://homologacao.sefaz.mt.gov.br/nfews/v2/services/NfeConsulta4'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://homologacao.sefaz.mt.gov.br/nfews/v2/services/NfeInutilizacao4'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://homologacao.sefaz.mt.gov.br/nfews/v2/services/NfeRetAutorizacao4'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://homologacao.sefaz.mt.gov.br/nfews/v2/services/RecepcaoEvento4'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => 'https://homologacao.sefaz.mt.gov.br/nfews/v2/services/CadConsultaCadastro4'
            ]
        ],
        'PE' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://nfehomolog.sefaz.pe.gov.br/nfe-service/services/NFeStatusServico4'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfehomolog.sefaz.pe.gov.br/nfe-service/services/NFeAutorizacao4'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://nfehomolog.sefaz.pe.gov.br/nfe-service/services/NFeConsultaProtocolo4'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://nfehomolog.sefaz.pe.gov.br/nfe-service/services/NFeInutilizacao4'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfehomolog.sefaz.pe.gov.br/nfe-service/services/NFeRetAutorizacao4'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://nfehomolog.sefaz.pe.gov.br/nfe-service/services/RecepcaoEvento4'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => 'https://nfehomolog.sefaz.pe.gov.br/nfe-service/services/CadConsultaCadastro4'
            ]
        ],
        'PR' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://homologacao.nfe.sefa.pr.gov.br/nfe/NFeStatusServico4'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://homologacao.nfe.sefa.pr.gov.br/nfe/NFeAutorizacao4'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://homologacao.nfe.sefa.pr.gov.br/nfe/NFeConsultaProtocolo4'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://homologacao.nfe.sefa.pr.gov.br/nfe/NFeInutilizacao4'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacao",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://homologacao.nfe.sefa.pr.gov.br/nfe/NFeRetAutorizacao4'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://homologacao.nfe.sefa.pr.gov.br/nfe/NFeRecepcaoEvento4'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => 'https://homologacao.nfe.sefa.pr.gov.br/nfe/CadConsultaCadastro4'
            ]
        ],
        'RS' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://nfe-homologacao.sefazrs.rs.gov.br/ws/NfeStatusServico/NfeStatusServico4.asmx'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe-homologacao.sefazrs.rs.gov.br/ws/NfeAutorizacao/NFeAutorizacao4.asmx'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://nfe-homologacao.sefazrs.rs.gov.br/ws/NfeConsulta/NfeConsulta4.asmx'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://nfe-homologacao.sefazrs.rs.gov.br/ws/nfeinutilizacao/nfeinutilizacao4.asmx'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe-homologacao.sefazrs.rs.gov.br/ws/NfeRetAutorizacao/NFeRetAutorizacao4.asmx'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://nfe-homologacao.sefazrs.rs.gov.br/ws/recepcaoevento/recepcaoevento4.asmx'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => ''
            ]
        ],
        'SP' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://homologacao.nfe.fazenda.sp.gov.br/ws/nfestatusservico4.asmx'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://homologacao.nfe.fazenda.sp.gov.br/ws/nfeautorizacao4.asmx'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://homologacao.nfe.fazenda.sp.gov.br/ws/nfeconsultaprotocolo4.asmx'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://homologacao.nfe.fazenda.sp.gov.br/ws/nfeinutilizacao4.asmx'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://homologacao.nfe.fazenda.sp.gov.br/ws/nferetautorizacao4.asmx'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://homologacao.nfe.fazenda.sp.gov.br/ws/nferecepcaoevento4.asmx'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => 'https://homologacao.nfe.fazenda.sp.gov.br/ws/cadconsultacadastro4.asmx'
            ]
        ],
        'SVAN' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://hom.sefazvirtual.fazenda.gov.br/NFeStatusServico4/NFeStatusServico4.asmx'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://hom.sefazvirtual.fazenda.gov.br/NFeAutorizacao4/NFeAutorizacao4.asmx'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://hom.sefazvirtual.fazenda.gov.br/NFeConsultaProtocolo4/NFeConsultaProtocolo4.asmx'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://hom.sefazvirtual.fazenda.gov.br/NFeInutilizacao4/NFeInutilizacao4.asmx'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://hom.sefazvirtual.fazenda.gov.br/NFeRetAutorizacao4/NFeRetAutorizacao4.asmx'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://hom.sefazvirtual.fazenda.gov.br/NFeRecepcaoEvento4/NFeRecepcaoEvento4.asmx'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => ''
            ]
        ],
        'SVRS' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://nfe-homologacao.svrs.rs.gov.br/ws/NfeStatusServico/NfeStatusServico4.asmx'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe-homologacao.svrs.rs.gov.br/ws/NfeAutorizacao/NFeAutorizacao4.asmx'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://nfe-homologacao.svrs.rs.gov.br/ws/NfeConsulta/NfeConsulta4.asmx'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://nfe-homologacao.svrs.rs.gov.br/ws/nfeinutilizacao/nfeinutilizacao4.asmx'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe-homologacao.svrs.rs.gov.br/ws/NfeRetAutorizacao/NFeRetAutorizacao4.asmx'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://nfe-homologacao.svrs.rs.gov.br/ws/recepcaoevento/recepcaoevento4.asmx'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => ''
            ]
        ],
        'SVCAN' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://hom.svc.fazenda.gov.br/NFeStatusServico4/NFeStatusServico4.asmx'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://hom.svc.fazenda.gov.br/NFeAutorizacao4/NFeAutorizacao4.asmx'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsultaProtocolo4",
                'version' => "4.00",
                'url' => 'https://hom.svc.fazenda.gov.br/NFeConsultaProtocolo4/NFeConsultaProtocolo4.asmx'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => ''
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://hom.svc.fazenda.gov.br/NFeRetAutorizacao4/NFeRetAutorizacao4.asmx'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://hom.svc.fazenda.gov.br/NFeRecepcaoEvento4/NFeRecepcaoEvento4.asmx'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => ''
            ]
        ],
        'SVCRS' => [
            'NfeStatusServico' => [
                'method' => "nfeStatusServicoNF",
                'operation' => "NFeStatusServico4",
                'version' => "4.00",
                'url' => 'https://nfe-homologacao.svrs.rs.gov.br/ws/NfeStatusServico/NfeStatusServico4.asmx'
            ],
            'NfeAutorizacao' => [
                'method' => "nfeAutorizacaoLote",
                'operation' => "NFeAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe-homologacao.svrs.rs.gov.br/ws/NfeAutorizacao/NFeAutorizacao4.asmx'
            ],
            'NfeConsultaProtocolo' => [
                'method' => "nfeConsultaNF",
                'operation' => "NFeConsulta4",
                'version' => "4.00",
                'url' => 'https://nfe-homologacao.svrs.rs.gov.br/ws/NfeConsulta/NfeConsulta4.asmx'
            ],
            'NfeInutilizacao' => [
                'method' => "nfeInutilizacaoNF",
                'operation' => "NFeInutilizacao4",
                'version' => "4.00",
                'url' => 'https://nfe-homologacao.svrs.rs.gov.br/ws/nfeinutilizacao/nfeinutilizacao4.asmx'
            ],
            'NfeRetAutorizacao' => [
                'method' => "nfeRetAutorizacaoLote",
                'operation' => "NFeRetAutorizacao4",
                'version' => "4.00",
                'url' => 'https://nfe-homologacao.svrs.rs.gov.br/ws/NfeRetAutorizacao/NFeRetAutorizacao4.asmx'
            ],
            'RecepcaoEvento' => [
                'method' => "nfeRecepcaoEvento",
                'operation' => "NFeRecepcaoEvento4",
                'version' => "1.00",
                'url' => 'https://nfe-homologacao.svrs.rs.gov.br/ws/recepcaoevento/recepcaoevento4.asmx'
            ],
            'NfeConsultaCadastro' => [
                'method' => "consultaCadastro4",
                'operation' => "CadConsultaCadastro4",
                'version' => "2.00",
                'url' => ''
            ]
        ]
    ]
];
