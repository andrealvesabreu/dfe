<?php
declare(strict_types = 1);
namespace Inspire\Dfe\Parser;

use Inspire\Support\Xml\Xml;
use Inspire\Support\ {
    Arrays,
    Strings
};

/**
 * Description of ParserResponse
 *
 * @author aalves
 */
class Cte extends Base
{

    /**
     * Parse SEFAZ response of CteRecepcao
     * Fully implemented
     *
     * @param Xml $xml
     * @return array
     */
    public static function CteRecepcao(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retEnviCte');
        Arrays::forget($aData, [
            '@attributes',
            'protCTe.@attributes'
        ]);
        /**
         * Adding control info
         */
        Arrays::set($aData, 'cType', self::$messages[$aData['cStat']]['type']);
        Arrays::set($aData, 'xReason', self::$messageType[$aData['cType']]);
        Arrays::set($aData, 'bStat', $aData['cType'] == 1);
        return $aData;
    }

    /**
     * Parse SEFAZ response of CteRecepcaoOS
     * Fully implemented
     *
     * @param Xml $xml
     * @return array
     */
    public static function CteRecepcaoOS(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retEnviOS');
        Arrays::forget($aData, [
            '@attributes',
            'protCTe.@attributes'
        ]);
        /**
         * Adding control info
         */
        Arrays::set($aData, 'cType', self::$messages[$aData['cStat']]['type']);
        Arrays::set($aData, 'xReason', self::$messageType[$aData['cType']]);
        Arrays::set($aData, 'bStat', $aData['cType'] == 1);
        return $aData;
    }

    /**
     * Parse SEFAZ response of CteRetRecepcao
     * Fully implemented
     *
     * @param Xml $xml
     * @return array
     */
    public static function CteRetRecepcao(Xml $xml): array
    {
        /**
         * Arrays to easy parser
         *
         * $aData array
         */
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retConsReciCTe');
        /**
         * XML to get real protocol, if available
         */
        $parserProt = new Xml($xml->getXml(), true);
        /**
         * Forgetting all @attributes
         */
        Arrays::forget($aData, [
            '@attributes'
        ]);
        if (isset($aData['protCTe'][0])) {
            foreach ($aData['protCTe'] as &$ct) {
                Arrays::set($ct, 'versao', Arrays::get($ct, '@attributes.versao'));
                Arrays::forget($ct, [
                    '@attributes',
                    'infProt.@attributes'
                ]);
                /**
                 * Adding control info
                 */
                Arrays::set($ct, 'cType', self::$messages[$ct['infProt']['cStat']]['type']);
                Arrays::set($ct, 'xReason', self::$messageType[$ct['cType']]);
                Arrays::set($ct, 'bStat', $ct['cType'] == 1);
            }
        } else {
            Arrays::set($aData['protCTe'], 'versao', Arrays::get($aData, 'protCTe.@attributes.versao'));
            Arrays::forget($aData, [
                'protCTe.@attributes',
                'protCTe.infProt.@attributes'
            ]);
            /**
             * Adding control info
             */
            Arrays::set($aData, 'protCTe.cType', self::$messages[Arrays::get($aData, 'protCTe.infProt.cStat')]['type']);
            Arrays::set($aData, 'protCTe.xReason', self::$messageType[Arrays::get($aData, 'protCTe.cType')]);
            Arrays::set($aData, 'protCTe.bStat', Arrays::get($aData, 'protCTe.cType') == 1);
            $aData['protCTe'] = [
                $aData['protCTe']
            ];
        }
        /**
         * Remapping array for CTe keys (protCTe.[chCTe].infProt)
         */
        foreach ($aData['protCTe'] as $i => $prot) {
            Arrays::forget($aData['protCTe'], $i);
            $prot['xml'] = $parserProt->xpathXml('/protCTe', $i)->asXML();
            Arrays::set($aData['protCTe'], Arrays::get($prot, 'infProt.chCTe'), $prot);
        }
        /**
         * Adding control info
         */
        Arrays::set($aData, 'cType', self::$messages[$aData['cStat']]['type']);
        Arrays::set($aData, 'xReason', self::$messageType[$aData['cType']]);
        Arrays::set($aData, 'bStat', $aData['cType'] == 1);
        return $aData;
    }

    /**
     * Parse SEFAZ response of CteInutilizacao
     * Fully implemented
     *
     * @param Xml $xml
     * @return array
     */
    public static function CteInutilizacao(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retInutCTe');
        Arrays::set($aData['infInut'], 'versao', Arrays::get($aData, '@attributes.versao'));
        Arrays::forget($aData, [
            '@attributes',
            'infInut.@attributes'
        ]);
        /**
         * Adding control info
         */
        Arrays::set($aData, 'cType', self::$messages[$aData['infInut']['cStat']]['type']);
        Arrays::set($aData, 'xReason', self::$messageType[$aData['cType']]);
        Arrays::set($aData, 'bStat', $aData['cType'] == 1);
        return $aData;
    }

    /**
     * Parse SEFAZ response of CteConsulta
     * Fully implemented
     *
     * @param Xml $xml
     * @return array
     */
    public static function CteConsulta(Xml $xml): array
    {
        /**
         * Arrays to easy parser
         *
         * $aData array
         */
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retConsSitCTe');
        $parser = [
            'versao' => Arrays::get($aData, '@attributes.versao'),
            'tpAmb' => Arrays::get($aData, 'tpAmb'),
            'verAplic' => Arrays::get($aData, 'verAplic'),
            'cStat' => Arrays::get($aData, 'cStat'),
            'xMotivo' => Arrays::get($aData, 'xMotivo'),
            'cUF' => Arrays::get($aData, 'cUF')
        ];
        /**
         * Forgetting all @attributes
         */
        Arrays::forget($aData, [
            '@attributes'
        ]);
        /**
         * Adding control info
         */
        Arrays::set($parser, 'cType', self::$messages[$aData['cStat']]['type']);
        Arrays::set($parser, 'xReason', self::$messageType[$parser['cType']]);
        Arrays::set($parser, 'bStat', $parser['cType'] == 1);
        /**
         * If there is a CTe
         */
        if ($parser['bStat']) {
            /**
             * XML to get all XML (protocol and events)
             */
            $parserProt = new Xml($xml->getXml(), true);
            /**
             * Remapping protocol data (remove attributes, add version and XML)
             */
            Arrays::set($aData, 'protCTe.xml', $parserProt->xpathXml('/protCTe', 0)->asXML());
            Arrays::set($aData['protCTe'], 'versao', Arrays::get($aData, 'protCTe.@attributes.versao'));
            Arrays::forget($aData, [
                'protCTe.@attributes',
                'protCTe.infProt.@attributes'
            ]);
            $parser['protCTe'] = $aData['protCTe'];
            /**
             * Check if there is a retCancCTe group (deprecated)
             */
            // if (isset($aData['retCancCTe']) && ! empty($aData['retCancCTe'])) {
            // $prot = $aData['retCancCTe'];
            // $parser['retCancCTe'] = [
            // 'versao' => Arrays::get($prot, '@attributes.versao'),
            // 'tpAmb' => Arrays::get($prot, 'infProt.tpAmb'),
            // 'verAplic' => Arrays::get($prot, 'infProt.verAplic'),
            // 'chCTe' => Arrays::get($prot, 'infProt.chCTe'),
            // 'dhRecbto' => Arrays::get($prot, 'infProt.dhRecbto'),
            // 'nProt' => Arrays::get($prot, 'infProt.nProt'),
            // 'digVal' => Arrays::get($prot, 'infProt.digVal'),
            // 'cStat' => Arrays::get($prot, 'infProt.cStat'),
            // 'xMotivo' => Arrays::get($prot, 'infProt.xMotivo'),
            // 'xml' => $parserProt->xpathXml('/retCancCTe', 0)->asXML()
            // ];
            // }
            /**
             * Check if there is a procEventoCTe group
             */
            if (isset($aData['procEventoCTe']) && ! empty($aData['procEventoCTe'])) {
                if (! isset($aData['procEventoCTe'][0])) {
                    $aData['procEventoCTe'] = [
                        $aData['procEventoCTe']
                    ];
                }
                /**
                 * Getting data for each events
                 */
                $parser['procEventoCTe'] = [];
                foreach ($aData['procEventoCTe'] as $i => $prot) {
                    $prot = $prot['retEventoCTe'];
                    $parser['procEventoCTe'][] = [
                        'versao' => Arrays::get($prot, '@attributes.versao'),
                        'tpAmb' => Arrays::get($prot, 'infEvento.tpAmb'),
                        'verAplic' => Arrays::get($prot, 'infEvento.verAplic'),
                        'cOrgao' => Arrays::get($prot, 'infEvento.cOrgao'),
                        'cStat' => Arrays::get($prot, 'infEvento.cStat'),
                        'xMotivo' => Arrays::get($prot, 'infEvento.xMotivo'),
                        'chCTe' => Arrays::get($prot, 'infEvento.chCTe'),
                        'tpEvento' => Arrays::get($prot, 'infEvento.tpEvento'),
                        'xEvento' => Arrays::get($prot, 'infEvento.xEvento'),
                        'nSeqEvento' => Arrays::get($prot, 'infEvento.nSeqEvento'),
                        'dhRegEvento' => Arrays::get($prot, 'infEvento.dhRegEvento'),
                        'nProt' => Arrays::get($prot, 'infEvento.nProt'),
                        'xml' => $parserProt->xpathXml('/procEventoCTe', $i)->asXML()
                    ];
                }
            }
        }
        return $parser;
    }

    /**
     * Parse SEFAZ response of CteStatusServico
     * Fully implemented
     *
     * @param Xml $xml
     * @return array
     */
    public static function CteStatusServico(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retConsStatServCte');
        Arrays::forget($aData, [
            '@attributes'
        ]);
        return $aData;
    }

    /**
     * Parse SEFAZ response of CteRecepcaoEvento
     *
     * @param Xml $xml
     * @return array
     */
    public static function CteRecepcaoEvento(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retEventoCTe');
        Arrays::set($aData, 'infEvento.versao', Arrays::get($aData, '@attributes.versao'));
        Arrays::forget($aData, [
            'infEvento.@attributes',
            '@attributes'
        ]);
        /**
         * Adding control info
         */
        Arrays::set($aData, 'cType', self::$messages[$aData['infEvento']['cStat']]['type']);
        Arrays::set($aData, 'xReason', self::$messageType[$aData['cType']]);
        Arrays::set($aData, 'bStat', $aData['cType'] == 1);
        return $aData;
    }

    /**
     * Parse response Distribuição documentos e informações de interesse do ator do CT-e
     * Fully implemented
     *
     * @param Xml $xml
     * @return array
     */
    public static function CTeDistribuicaoDFe(Xml $xml): array
    {
        /**
         * Arrays to easy parser
         *
         * $aData array
         */
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retDistDFeInt');
        /**
         * Forgetting all @attributes
         */
        Arrays::forget($aData, [
            '@attributes'
        ]);
        /**
         * Adding control info
         */
        Arrays::set($aData, 'cType', self::$messages[$aData['cStat']]['type']);
        Arrays::set($aData, 'xReason', self::$messageType[$aData['cType']]);
        Arrays::set($aData, 'bStat', $aData['cType'] == 1);
        Arrays::set($aData, 'isLast', intval($aData['ultNSU']) == intval($aData['maxNSU']));
        /**
         * Ig there is some event or document, process data
         */
        if ($aData['bStat'] && ! empty($aData['loteDistDFeInt'])) {
            $loteDistDFeInt = [];
            /**
             * Noraliza package case there is a single document
             */
            if (! isset($aData['loteDistDFeInt']['docZip'][0])) {
                $aData['loteDistDFeInt']['docZip'] = [
                    $aData['loteDistDFeInt']['docZip']
                ];
            }
            $docs = $aData['loteDistDFeInt']['docZip'];
            foreach ($docs as $doc) {
                $schema = strtok(Arrays::get($doc, '@attributes.schema'), '_');
                if ((self::$unpack !== null && Arrays::keyCheck($schema, self::$unpack)) || // Some type of package is set and actual package is one of that
                self::$unpack === null) {
                    $loteDistDFeInt[] = [
                        'NSU' => Strings::toInt(Arrays::get($doc, '@attributes.NSU')),
                        'schema' => $schema,
                        'xml' => gzdecode(base64_decode($doc['@value']))
                    ];
                }
            }
            $aData['aDocs'] = $loteDistDFeInt;
            unset($aData['loteDistDFeInt']);
        }
        return $aData;
    }

    /**
     *
     * @param string $code
     * @return number[]|string[]
     */
    public static function getResponseCode(string $code): ?array
    {
        return self::$messages[$code];
    }

    /*
     * @var array
     */
    private static array $messages = [
        '100' => [ // Verified
            'type' => 1,
            'message' => 'Autorizado o uso do CT-e'
        ],
        '101' => [ // Verified
            'type' => 1,
            'message' => 'Cancelamento de CT-e homologado'
        ],
        '102' => [ // Verified
            'type' => 1,
            'message' => 'Inutilização de número homologado'
        ],
        '103' => [ // Verified
            'type' => 1,
            'message' => 'Lote recebido com sucesso'
        ],
        '104' => [ // Verified
            'type' => 1,
            'message' => 'Lote processado'
        ],
        '105' => [
            'type' => 2,
            'message' => 'Lote em processamento'
        ],
        '106' => [
            'type' => 0,
            'message' => 'Lote não localizado'
        ],
        '107' => [
            'type' => 1,
            'message' => 'Serviço em Operação'
        ],
        '108' => [ // Verified
            'type' => 3,
            'message' => 'Serviço Paralisado Momentaneamente (curto prazo)'
        ],
        '109' => [ // Verified
            'type' => 4,
            'message' => 'Serviço Paralisado sem Previsão'
        ],
        '110' => [ // Verified
            'type' => 4,
            'message' => 'Uso Denegado'
        ],
        '111' => [
            'type' => 1,
            'message' => 'Consulta cadastro com uma ocorrência'
        ],
        '112' => [
            'type' => 1,
            'message' => 'Consulta cadastro com mais de uma ocorrência'
        ],
        '113' => [
            'type' => 1,
            'message' => 'Serviço SVC em operação. Desativação prevista para a UF em dd/mm/aa, às hh:mm horas'
        ],
        '114' => [
            'type' => 0,
            'message' => 'SVC-[SP/RS] desabilitada pela SEFAZ de Origem'
        ],
        '134' => [
            'type' => 1,
            'message' => 'Evento registrado e vinculado ao CT-e com alerta para situação do documento. [Alerta Situação do CT-e: XXXXXXXXXX]'
        ],
        '135' => [
            'type' => 1,
            'message' => 'Evento registrado e vinculado a CT-e'
        ],
        '136' => [
            'type' => 3,
            'message' => 'Evento registrado, mas não vinculado a CT-e'
        ],
        '137' => [ // Verified
            'type' => 1,
            'message' => 'Nenhum documento localizado'
        ],
        '138' => [ // Verified
            'type' => 1,
            'message' => 'Documento localizado'
        ],
        '201' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: O número máximo de numeração de CT-e a inutilizar ultrapassou o limite'
        ],
        '202' => [
            'type' => 0,
            'message' => 'Rejeição: Falha no reconhecimento da autoria ou integridade do arquivo digital'
        ],
        '203' => [ // Verified
            'type' => 5,
            'message' => 'Rejeição: Emissor não habilitado para emissão do CT-e'
        ],
        '204' => [ // Verified
            'type' => 7,
            'message' => 'Rejeição: Duplicidade de CT-e[nProt:999999999999999][dhAut: AAAA-MM-DDTHH:MM:SS TZD]'
        ],
        '205' => [
            'type' => 4,
            'message' => 'Rejeição: CT-e está denegado na base de dados da SEFAZ'
        ],
        '206' => [
            'type' => 0,
            'message' => 'Rejeição: Número de CT-e já está inutilizado na Base de dados da SEFAZ'
        ],
        '207' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do emitente inválido'
        ],
        '208' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do destinatário inválido'
        ],
        '209' => [
            'type' => 0,
            'message' => 'Rejeição: IE do emitente inválida'
        ],
        '210' => [
            'type' => 0,
            'message' => 'Rejeição: IE do destinatário inválida'
        ],
        '211' => [
            'type' => 0,
            'message' => 'Rejeição: IE do substituto inválida'
        ],
        '212' => [
            'type' => 0,
            'message' => 'Rejeição: Data de emissão CT-e posterior a data de recebimento'
        ],
        '213' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: CNPJ-Base do Emitente difere do CNPJ-Base do Certificado Digital'
        ],
        '214' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Tamanho da mensagem excedeu o limite estabelecido'
        ],
        '215' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Falha no schema XML'
        ],
        '216' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso difere da cadastrada'
        ],
        '217' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e não consta na base de dados da SEFAZ'
        ],
        '218' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e já está cancelado na base de dados da SEFAZ[nProt:999999999999999][dhCanc: AAAA-MM-DDTHH:MM:SS TZD].'
        ],
        '219' => [
            'type' => 0,
            'message' => 'Rejeição: Circulação do CT-e verificada'
        ],
        '220' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e autorizado há mais de 7 dias (168 horas)'
        ],
        '221' => [
            'type' => 0,
            'message' => 'Rejeição: Confirmado a prestação do serviço do CT-e pelo destinatário'
        ],
        '222' => [
            'type' => 0,
            'message' => 'Rejeição: Protocolo de Autorização de Uso difere do cadastrado'
        ],
        '223' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do transmissor do lote difere do CNPJ do transmissor da consulta'
        ],
        '224' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: A faixa inicial é maior que a faixa final'
        ],
        '225' => [
            'type' => 0,
            'message' => 'Rejeição: Falha no Schema XML do CT-e'
        ],
        '226' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Código da UF do Emitente diverge da UF autorizadora'
        ],
        '227' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Erro na composição do Campo ID'
        ],
        '228' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Data de Emissão muito atrasada'
        ],
        '229' => [
            'type' => 0,
            'message' => 'Rejeição: IE do emitente não informada'
        ],
        '230' => [
            'type' => 0,
            'message' => 'Rejeição: IE do emitente não cadastrada'
        ],
        '231' => [
            'type' => 0,
            'message' => 'Rejeição: IE do emitente não vinculada ao CNPJ'
        ],
        '232' => [
            'type' => 0,
            'message' => 'Rejeição: IE do destinatário não informada'
        ],
        '233' => [
            'type' => 0,
            'message' => 'Rejeição: IE do destinatário não cadastrada'
        ],
        '235' => [
            'type' => 0,
            'message' => 'Rejeição: Inscrição SUFRAMA inválida'
        ],
        '236' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso com dígito verificador inválido'
        ],
        '237' => [
            'type' => 0,
            'message' => 'Rejeição: CPF do destinatário inválido'
        ],
        '238' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Cabeçalho - Versão do arquivo XML superior a Versão vigente'
        ],
        '239' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Cabeçalho - Versão do arquivo XML não suportada'
        ],
        '240' => [ // Verified
            'type' => 5,
            'message' => 'Rejeição: Cancelamento/Inutilização - Irregularidade Fiscal do Emitente'
        ],
        '241' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Um número da faixa já foi utilizado'
        ],
        '242' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Elemento cteCabecMsg inexistente no SOAP Header'
        ],
        '243' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: XML Mal Formado'
        ],
        '245' => [
            'type' => 5,
            'message' => 'Rejeição: CNPJ Emitente não cadastrado'
        ],
        '247' => [
            'type' => 0,
            'message' => 'Rejeição: Sigla da UF do Emitente diverge da UF autorizadora'
        ],
        '248' => [
            'type' => 0,
            'message' => 'Rejeição: UF do Recibo diverge da UF autorizadora'
        ],
        '249' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: UF da Chave de Acesso diverge da UF autorizadora'
        ],
        '250' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: UF diverge da UF autorizadora'
        ],
        '251' => [
            'type' => 0,
            'message' => 'Rejeição: UF/Município destinatário não pertence a SUFRAMA'
        ],
        '252' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Ambiente informado diverge do Ambiente de recebimento'
        ],
        '253' => [
            'type' => 0,
            'message' => 'Rejeição: Dígito Verificador da chave de acesso composta inválido'
        ],
        '256' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Um número de CT-e da faixa está inutilizado na Base de dados da SEFAZ'
        ],
        '257' => [
            'type' => 5,
            'message' => 'Rejeição: Solicitante não habilitado para emissão do CT-e'
        ],
        '258' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ da consulta inválido'
        ],
        '259' => [
            'type' => 5,
            'message' => 'Rejeição: CNPJ da consulta não cadastrado como contribuinte na UF'
        ],
        '260' => [
            'type' => 0,
            'message' => 'Rejeição: IE da consulta inválida'
        ],
        '261' => [
            'type' => 5,
            'message' => 'Rejeição: IE da consulta não cadastrada como contribuinte na UF'
        ],
        '262' => [
            'type' => 0,
            'message' => 'Rejeição: UF não fornece consulta por CPF'
        ],
        '263' => [
            'type' => 0,
            'message' => 'Rejeição: CPF da consulta inválido'
        ],
        '264' => [
            'type' => 0,
            'message' => 'Rejeição: CPF da consulta não cadastrado como contribuinte na UF'
        ],
        '265' => [
            'type' => 0,
            'message' => 'Rejeição: Sigla da UF da consulta difere da UF do Web Service'
        ],
        '266' => [
            'type' => 0,
            'message' => 'Rejeição: Série utilizada não permitida no Web Service'
        ],
        '267' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e Complementar referência um CT-e inexistente'
        ],
        '268' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e Complementar referência outro CT-e Complementar'
        ],
        '269' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ Emitente do CT-e Complementar difere do CNPJ do CT complementado'
        ],
        '280' => [ // Verified
            'type' => 6,
            'message' => 'Rejeição: Certificado Transmissor inválido'
        ],
        '281' => [ // Verified
            'type' => 6,
            'message' => 'Rejeição: Certificado Transmissor Data Validade'
        ],
        '282' => [ // Verified
            'type' => 6,
            'message' => 'Rejeição: Certificado Transmissor sem CNPJ'
        ],
        '283' => [ // Verified
            'type' => 6,
            'message' => 'Rejeição: Certificado Transmissor - erro Cadeia de Certificação'
        ],
        '284' => [ // Verified
            'type' => 6,
            'message' => 'Rejeição: Certificado Transmissor revogado'
        ],
        '285' => [ // Verified
            'type' => 6,
            'message' => 'Rejeição: Certificado Transmissor difere ICP-Brasil'
        ],
        '286' => [ // Verified
            'type' => 6,
            'message' => 'Rejeição: Certificado Transmissor erro no acesso a LCR'
        ],
        '289' => [
            'type' => 0,
            'message' => 'Rejeição: Código da UF informada diverge da UF solicitada'
        ],
        '290' => [ // Verified
            'type' => 6,
            'message' => 'Rejeição: Certificado Assinatura inválido'
        ],
        '291' => [ // Verified
            'type' => 6,
            'message' => 'Rejeição: Certificado Assinatura Data Validade'
        ],
        '292' => [ // Verified
            'type' => 6,
            'message' => 'Rejeição: Certificado Assinatura sem CNPJ'
        ],
        '293' => [ // Verified
            'type' => 6,
            'message' => 'Rejeição: Certificado Assinatura - erro Cadeia de Certificação'
        ],
        '294' => [ // Verified
            'type' => 6,
            'message' => 'Rejeição: Certificado Assinatura revogado'
        ],
        '295' => [ // Verified
            'type' => 6,
            'message' => 'Rejeição: Certificado Assinatura difere ICP-Brasil'
        ],
        '296' => [ // Verified
            'type' => 6,
            'message' => 'Rejeição: Certificado Assinatura erro no acesso a LCR'
        ],
        '297' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Assinatura difere do calculado'
        ],
        '298' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Assinatura difere do padrão do Projeto'
        ],
        '299' => [
            'type' => 0,
            'message' => 'Rejeição: XML da área de cabeçalho com codificação diferente de UTF-8'
        ],
        '401' => [
            'type' => 0,
            'message' => 'Rejeição: CPF do remetente inválido'
        ],
        '402' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: XML da área de dados com codificação diferente de UTF-8'
        ],
        '404' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Uso de prefixo de namespace não permitido'
        ],
        '405' => [
            'type' => 0,
            'message' => 'Rejeição: Código do país do emitente: dígito inválido'
        ],
        '406' => [
            'type' => 0,
            'message' => 'Rejeição: Código do país do destinatário: dígito inválido'
        ],
        '407' => [
            'type' => 0,
            'message' => 'Rejeição: O CPF só pode ser informado no campo emitente para o CT-e avulso'
        ],
        '408' => [
            'type' => 0,
            'message' => 'Rejeição: Lote com CT-e de diferentes UF'
        ],
        '409' => [
            'type' => 0,
            'message' => 'Rejeição: Campo cUF inexistente no elemento cteCabecMsg do SOAP Header'
        ],
        '410' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: UF informada no campo cUF não é atendida pelo WebService'
        ],
        '411' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Campo versaoDados inexistente no elemento cteCabecMsg do SOAP Header'
        ],
        '414' => [
            'type' => 0,
            'message' => 'Rejeição: Código de Município diverge da UF de término da prestação'
        ],
        '415' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do remetente inválido'
        ],
        '416' => [
            'type' => 0,
            'message' => 'Rejeição: CPF do remetente inválido'
        ],
        '418' => [
            'type' => 0,
            'message' => 'Rejeição: Código de Município diverge da UF de localização remetente'
        ],
        '419' => [
            'type' => 0,
            'message' => 'Rejeição: IE do remetente inválida',
            'rule' => ''
        ],
        '421' => [
            'type' => 0,
            'message' => 'Rejeição: IE do remetente não cadastrada'
        ],
        '422' => [
            'type' => 0,
            'message' => 'Rejeição: IE do remetente não vinculada ao CNPJ'
        ],
        '424' => [
            'type' => 0,
            'message' => 'Rejeição: Código de Município diverge da UF de localização destinatário'
        ],
        '426' => [
            'type' => 0,
            'message' => 'Rejeição: IE do destinatário não cadastrada'
        ],
        '427' => [
            'type' => 0,
            'message' => 'Rejeição: IE do destinatário não vinculada ao CNPJ'
        ],
        '428' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do expedidor inválido'
        ],
        '429' => [
            'type' => 0,
            'message' => 'Rejeição: CPF do expedidor inválido'
        ],
        '431' => [
            'type' => 0,
            'message' => 'Rejeição: Código de Município diverge da UF de localização expedidor'
        ],
        '432' => [
            'type' => 0,
            'message' => 'Rejeição: IE do expedidor inválida'
        ],
        '434' => [
            'type' => 0,
            'message' => 'Rejeição: IE do expedidor não cadastrada'
        ],
        '435' => [
            'type' => 0,
            'message' => 'Rejeição: IE do expedidor não vinculada ao CNPJ'
        ],
        '436' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do recebedor inválido'
        ],
        '437' => [
            'type' => 0,
            'message' => 'Rejeição: CPF do recebedor inválido'
        ],
        '439' => [
            'type' => 0,
            'message' => 'Rejeição: Código de Município diverge da UF de localização recebedor'
        ],
        '440' => [
            'type' => 0,
            'message' => 'Rejeição: IE do recebedor inválida'
        ],
        '442' => [
            'type' => 0,
            'message' => 'Rejeição: IE do recebedor não cadastrada'
        ],
        '443' => [
            'type' => 0,
            'message' => 'Rejeição: IE do recebedor não vinculada ao CNPJ'
        ],
        '444' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do tomador inválido'
        ],
        '445' => [
            'type' => 0,
            'message' => 'Rejeição: CPF do tomador inválido'
        ],
        '447' => [
            'type' => 0,
            'message' => 'Rejeição: Código de Município diverge da UF de localização tomador'
        ],
        '448' => [
            'type' => 0,
            'message' => 'Rejeição: IE do tomador inválida'
        ],
        '450' => [
            'type' => 0,
            'message' => 'Rejeição: Dígito Verificador inválido na Chave de acesso de CT-e Multimodal'
        ],
        '451' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e Multimodal inválida (Ano < 2009 ou Ano maior que Ano corrente)'
        ],
        '452' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e Multimodal inválida (Mês = 0 ou Mês > 12)'
        ],
        '453' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e Multimodal inválida (CNPJ zerado ou digito inválido)'
        ],
        '454' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e Multimodal inválida (modelo diferente de 57)'
        ],
        '456' => [
            'type' => 0,
            'message' => 'Rejeição: Código de Município diverge da UF de início da prestação'
        ],
        '457' => [
            'type' => 0,
            'message' => 'Rejeição: O lote contém CT-e de mais de um estabelecimento emissor'
        ],
        '458' => [
            'type' => 0,
            'message' => 'Rejeição: Grupo de CT-e normal não informado para CT-e normal'
        ],
        '459' => [
            'type' => 0,
            'message' => 'Rejeição: Grupo de CT-e complementar não informado para CT-e complementar'
        ],
        '460' => [
            'type' => 0,
            'message' => 'Rejeição: Não informado os dados do remetente indicado como tomador do serviço'
        ],
        '461' => [
            'type' => 0,
            'message' => 'Rejeição: Não informado os dados do expedidor indicado como tomador do serviço'
        ],
        '462' => [
            'type' => 0,
            'message' => 'Rejeição: Não informado os dados do recebedor indicado como tomador do serviço'
        ],
        '463' => [
            'type' => 0,
            'message' => 'Rejeição: Não informado os dados do destinatário indicado como tomador do serviço'
        ],
        '464' => [
            'type' => 0,
            'message' => 'Rejeição: Evento Cancelado MDF-e sem existir previamente o evento MDF-e Autorizado'
        ],
        '469' => [
            'type' => 0,
            'message' => 'Rejeição: Remetente deve ser informado para tipo de serviço diferente de redespacho intermediário ou Serviço vinculado a multimodal'
        ],
        '470' => [
            'type' => 0,
            'message' => 'Rejeição: Destinatário deve ser informado para tipo de serviço diferente de redespacho intermediário ou serviço vinculado a multimodal'
        ],
        '471' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Ano de inutilização não pode ser superior ao Ano atual'
        ],
        '472' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Ano de inutilização não pode ser inferior a 2008'
        ],
        '473' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo Autorizador do Recibo diverge do Órgão Autorizador'
        ],
        '474' => [
            'type' => 0,
            'message' => 'Rejeição: Expedidor deve ser informado para tipo de serviço de redespacho intermediário e serviço vinculado a multimodal'
        ],
        '475' => [
            'type' => 0,
            'message' => 'Rejeição: Recebedor deve ser informado para tipo de serviço de redespacho intermediário e serviço vinculado a multimodal'
        ],
        '478' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e Multimodal inválida (número CT = 0)'
        ],
        '479' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e Multimodal inválida (Tipo de emissão inválido)'
        ],
        '480' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso de CT-e anterior inválida (Tipo de emissão inválido)'
        ],
        '481' => [
            'type' => 0,
            'message' => 'Rejeição: IE deve ser informada para tomador Contribuinte'
        ],
        '482' => [
            'type' => 0,
            'message' => 'Rejeição: IE do tomador isento deve ser preenchida com “ISENTO”'
        ],
        '483' => [
            'type' => 0,
            'message' => 'Rejeição: IE não pode ser informada para tomador não contribuinte'
        ],
        '489' => [
            'type' => 0,
            'message' => 'Rejeição: IE do tomador não cadastrada'
        ],
        '490' => [
            'type' => 0,
            'message' => 'Rejeição: IE do tomador não vinculada ao CNPJ'
        ],
        '491' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e complementado é diferente de Normal ou Substituição'
        ],
        '493' => [
            'type' => 0,
            'message' => 'Rejeição: Código de Município diverge da UF de envio'
        ],
        '495' => [
            'type' => 0,
            'message' => 'Rejeição: Solicitante não autorizado para consulta'
        ],
        '496' => [
            'type' => 0,
            'message' => 'Rejeição: Grupo CT-e de Anulação não informado para o CT-e de Anulação'
        ],
        '497' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e objeto da anulação inexistente'
        ],
        '498' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e objeto da anulação deve estar com a situação autorizada (não pode estar cancelado ou denegado)'
        ],
        '499' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e de anulação deve ter tipo de emissão = normal'
        ],
        '500' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e objeto da anulação deve ter Tipo = 0 (normal) ou 3 (Substituição)'
        ],
        '501' => [
            'type' => 0,
            'message' => 'Rejeição: Autorização do CT-e de Anulação deve ocorrer em até 60 dias da data de autorização do CT-e objeto de anulação'
        ],
        '502' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e de anulação deve ter o valor do ICMS e de prestação iguais ao CT-e original'
        ],
        '503' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e substituto deve ter tipo de emissão = normal'
        ],
        '504' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso de NF-e inválida (UF inválida)'
        ],
        '505' => [
            'type' => 0,
            'message' => 'Rejeição: Grupo CT-e de Substituição não informado para o CT-e de Substituição'
        ],
        '507' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso inválida (Tipo de emissão inválido)'
        ],
        '508' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso de NF-e inválida (Tipo de emissão inválido)'
        ],
        '509' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso de MDF-e inválida (Tipo de emissão inválido)'
        ],
        '510' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do emitente do CT-e substituto deve ser igual ao informado no CT-e substituído'
        ],
        '511' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ/CPF do remetente do CT-e substituto deve ser igual ao informado no CT- e substituído'
        ],
        '512' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ/CPF do destinatário do CT-e substituto deve ser igual ao informado no CT-e substituído'
        ],
        '513' => [
            'type' => 0,
            'message' => 'Rejeicao: UF nao atendida pela SVC-[SP/RS]'
        ],
        '515' => [
            'type' => 0,
            'message' => 'Rejeição: O tpEmis informado só é válido na contingência SVC'
        ],
        '516' => [
            'type' => 0,
            'message' => 'Rejeição: O tpEmis informado é incompatível com SVC-[SP/RS]'
        ],
        '517' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e informado em SVC deve ser Normal'
        ],
        '518' => [ // Verified
            'type' => 4,
            'message' => 'Rejeição: Serviço indisponível na SVC'
        ],
        '519' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP inválido para operação'
        ],
        '520' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e não pode receber mais do que 10 CT-e Complementares'
        ],
        '521' => [
            'type' => 0,
            'message' => 'Rejeição: Os documentos de transporte anterior devem ser informados para os tipos de serviço Subcontratação, Redespacho e Redespacho Intermediário'
        ],
        '522' => [
            'type' => 0,
            'message' => 'Rejeição: Nro Item Alterado inválido. Preencher com valor numérico (01 – 99)'
        ],
        '523' => [
            'type' => 0,
            'message' => 'Rejeição: Vedado cancelamento quando existir evento Carta de Correção'
        ],
        '524' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP inválido, informar 5932 ou 6932'
        ],
        '525' => [
            'type' => 0,
            'message' => 'Rejeição: Carta de correção inválida (campo/grupo “xxxx” informado não existe no schema do CT-e ou não existe no grupo informado)'
        ],
        '526' => [
            'type' => 0,
            'message' => 'Rejeição: Preencher informações dos containers somente para redespacho intermediário e serviço vinculado a multimodal'
        ],
        '527' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e duplicada no CT-e[chNFe: 99999999999999999999999999999999999999999999]'
        ],
        '528' => [
            'type' => 0,
            'message' => 'Rejeição: Vedado cancelamento se exitir MDF-e autorizado para o CT-e'
        ],
        '530' => [
            'type' => 0,
            'message' => 'Rejeição: Se ambiente SVC, rejeitar eventos diferentes de EPEC, Liberação EPEC e Cancelamento'
        ],
        '531' => [
            'type' => 0,
            'message' => 'Rejeição: Valor a receber deve ser menor ou igual Valor da Prestação'
        ],
        '532' => [
            'type' => 0,
            'message' => 'Rejeição: Município do Remetente inexistente'
        ],
        '533' => [
            'type' => 0,
            'message' => 'Rejeição: Município do Destinatário inexistente'
        ],
        '534' => [
            'type' => 0,
            'message' => 'Rejeição: Município do Expedidor inexistente'
        ],
        '535' => [
            'type' => 0,
            'message' => 'Rejeição: Município do Recebedor inexistente'
        ],
        '536' => [
            'type' => 0,
            'message' => 'Rejeição: Município do Tomador inexistente'
        ],
        '537' => [
            'type' => 0,
            'message' => 'Rejeição: Município de Envio inexistente'
        ],
        '538' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e anterior inválida (UF inválida)'
        ],
        '539' => [ // Verified
            'type' => 7,
            'message' => 'Rejeição: Duplicidade de CT-e, com diferença na Chave de Acesso [chCTe: [9999999999999999999999999999999999999999999][nProt:999999999999999][dhAut: AAAA-MM-DDTHH:MM:SS TZD]'
        ],
        '540' => [
            'type' => 0,
            'message' => 'Rejeição: Grupo de documentos informado inválido para remetente que emite NF-e'
        ],
        '541' => [
            'type' => 0,
            'message' => 'Rejeição: Município de início da prestação inexistente'
        ],
        '542' => [
            'type' => 0,
            'message' => 'Rejeição: Município de término da prestação inexistente'
        ],
        '543' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de CT-e duplicada em documentos anteriores'
        ],
        '544' => [
            'type' => 0,
            'message' => 'Rejeição: Dígito Verificador inválido na Chave de acesso de CT-e anterior'
        ],
        '545' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e anterior inválida (Ano < 2009 ou Ano maior que Ano corrente'
        ],
        '546' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e anterior inválida (Mês = 0 ou Mês > 12)'
        ],
        '547' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e anterior inválida (CNPJ zerado ou digito inválido)'
        ],
        '548' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e anterior inválida (modelo diferente de 57)'
        ],
        '549' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e anterior inválida (número CT = 0)'
        ],
        '550' => [
            'type' => 0,
            'message' => 'Rejeição: O CNPJ/CPF do expedidor do CT-e substituto deve ser igual ao informado no CT-e substituído'
        ],
        '551' => [
            'type' => 0,
            'message' => 'Rejeição: O CNPJ/CPF do recebedor do CT-e substituto deve ser igual ao informado no CT-e substituído'
        ],
        '552' => [
            'type' => 0,
            'message' => 'Rejeição: O CNPJ/CPF do tomador do CT-e substituto deve ser igual ao informado no CT- e substituído'
        ],
        '553' => [
            'type' => 0,
            'message' => 'Rejeição: A IE do emitente do CT-e substituto deve ser igual ao informado no CT-e substituído'
        ],
        '554' => [
            'type' => 0,
            'message' => 'Rejeição: A IE do remetente do CT-e substituto deve ser igual ao informado no CT-e substituído'
        ],
        '555' => [
            'type' => 0,
            'message' => 'Rejeição: A IE do destinatário do CT-e substituto deve ser igual ao informado no CT-e substituído'
        ],
        '556' => [
            'type' => 0,
            'message' => 'Rejeição: A IE do expedidor do CT-e substituto deve ser igual ao informado no CT-e substituído'
        ],
        '557' => [
            'type' => 0,
            'message' => 'Rejeição: A IE do recebedor do CT-e substituto deve ser igual ao informado no CT-e substituído'
        ],
        '558' => [
            'type' => 0,
            'message' => 'Rejeição: A IE do tomador do CT-e substituto deve ser igual ao informado no CT-e substituído'
        ],
        '559' => [
            'type' => 0,
            'message' => 'Rejeição: A UF de início da prestação deve ser igual ao informado no CT-e substituído'
        ],
        '560' => [
            'type' => 0,
            'message' => 'Rejeição: A UF de fim da prestação deve ser igual ao informado no CT-e substituído'
        ],
        '561' => [
            'type' => 0,
            'message' => 'Rejeição: O valor da prestação do serviço deve ser menor ou igual ao informado no CT-e substituído'
        ],
        '562' => [
            'type' => 0,
            'message' => 'Rejeição: O valor do ICMS do CT-e substituto deve ser menor ou igual ao informado no CT-e substituído'
        ],
        '563' => [
            'type' => 0,
            'message' => 'Rejeição: A substituição de um CT-e deve ocorrer no prazo máximo de 60 dias contados da data de autorização do CT-e objeto de Substituição'
        ],
        '564' => [
            'type' => 0,
            'message' => 'Rejeição: O CT-e de anulação não pode ser cancelado'
        ],
        '565' => [
            'type' => 0,
            'message' => 'Rejeição: O CT-e só pode ser anulado pelo emitente'
        ],
        '566' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e objeto da anulação não pode ter sido anulado anteriormente'
        ],
        '567' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e objeto da anulação não pode ter sido substituído anteriormente'
        ],
        '568' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e a ser substituído inexistente'
        ],
        '569' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e a ser substituído deve estar com a situação autorizada (não pode estar cancelado ou denegado)'
        ],
        '570' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e a ser substituído não pode ter sido substituído anteriormente'
        ],
        '571' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e a ser substituído deve ter Tipo = 0 (normal) ou 3 (Substituição)'
        ],
        '572' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e de anulação inexistente'
        ],
        '573' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e de anulação informado deve ter Tipo=2(Anulação)'
        ],
        '574' => [
            'type' => 0,
            'message' => 'Rejeição: Vedado o cancelamento de CT-e do tipo substituto (tipo=3)'
        ],
        '575' => [
            'type' => 0,
            'message' => 'Rejeição: Vedado o cancelamento se possuir CT-e de Anulação associado'
        ],
        '576' => [
            'type' => 0,
            'message' => 'Rejeição: Vedado o cancelamento se possuir CT-e de Substituição associado'
        ],
        '577' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e a ser substituído não pode ter sido anulado anteriormente quando informados os documentos emitidos pelo tomador contribuinte'
        ],
        '578' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso do CT-e anulado deve ser igual ao substituído'
        ],
        '579' => [
            'type' => 0,
            'message' => 'Rejeição: Versão informada para o modal não suportada'
        ],
        '580' => [
            'type' => 0,
            'message' => 'Rejeição: Falha no Schema XML específico para o modal'
        ],
        '581' => [
            'type' => 0,
            'message' => 'Rejeição: Campo Valor da Carga deve ser informado para o modal'
        ],
        '582' => [
            'type' => 0,
            'message' => 'Rejeição: Grupo Tráfego Mútuo deve ser informado'
        ],
        '583' => [
            'type' => 0,
            'message' => 'Rejeição: Ferrovia emitente deve ser a de origem quando respFat=1'
        ],
        '584' => [
            'type' => 0,
            'message' => 'Rejeição: Referenciar o CT-e que foi emitido pela ferrovia de origem'
        ],
        '585' => [
            'type' => 0,
            'message' => 'Rejeição: IE Emitente não autorizada a emitir CT-e para o modal informado'
        ],
        '586' => [
            'type' => 0,
            'message' => 'Rejeição: Data e Justificativa de entrada em contingência não devem ser informadas para tipo de emissão diferente de FS-DA.'
        ],
        '587' => [
            'type' => 0,
            'message' => 'Rejeição: Data e Justificativa de entrada em contingência devem ser informadas'
        ],
        '588' => [
            'type' => 0,
            'message' => 'Rejeição: Data de entrada em contingência posterior a data de emissão.'
        ],
        '589' => [
            'type' => 0,
            'message' => 'Rejeição: O lote contém CT-e de mais de um modal'
        ],
        '590' => [
            'type' => 0,
            'message' => 'Rejeição: O lote contém CT-e de mais de uma versão de modal'
        ],
        '591' => [
            'type' => 0,
            'message' => 'Rejeição: Dígito Verificador inválido na Chave de acesso de NF-e transportada'
        ],
        '592' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso inválida (Ano < 2009 ou Ano maior que Ano corrente)'
        ],
        '593' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso inválida (Mês = 0 ou Mês > 12)'
        ],
        '594' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso inválida (CNPJ zerado ou digito inválido)'
        ],
        '595' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso inválida (modelo diferente de 57 ou 67)'
        ],
        '596' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso inválida (número CT = 0)'
        ],
        '598' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Usar somente o namespace padrao do CT-e'
        ],
        '599' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Não é permitida a presença de caracteres de edição no início/fim da mensagem ou entre as tags da mensagem'
        ],
        '600' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso difere da existente em BD'
        ],
        '601' => [
            'type' => 0,
            'message' => 'Rejeição: Quantidade de documentos informados no remetente excede limite de 2000'
        ],
        '602' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de NF-e inválida (Ano < 2005 ou Ano maior que Ano corrente)'
        ],
        '603' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de NF-e inválida (Mês = 0 ou Mês > 12)'
        ],
        '604' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de NF-e inválida (CNPJ zerado ou digito inválido)'
        ],
        '605' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de NF-e inválida (modelo diferente de 55)'
        ],
        '606' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de NF-e inválida (numero NF = 0)'
        ],
        '608' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e Multimodal inválida (UF inválida)'
        ],
        '609' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso de MDF-e inválida (UF inválida)'
        ],
        '610' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso inválida (UF inválida)'
        ],
        '611' => [
            'type' => 0,
            'message' => 'Rejeição: Registro Passagem CT-e inexistente'
        ],
        '612' => [
            'type' => 0,
            'message' => 'Rejeição: Registro Passagem CT-e, Nro Protocolo difere'
        ],
        '614' => [
            'type' => 0,
            'message' => 'Rejeição: IE do Substituto Tributário inválida'
        ],
        '615' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e objeto da anulação inválida (modelo diferente de 67)'
        ],
        '616' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e substituido inválida (modelo diferente de 67)'
        ],
        '617' => [
            'type' => 0,
            'message' => 'Rejeição: UF do tomador não aceita ISENTO com Inscrição Estadual'
        ],
        '618' => [
            'type' => 0,
            'message' => 'Rejeição: GTV informada em duplicidade no evento'
        ],
        '619' => [
            'type' => 0,
            'message' => 'Rejeição: GTV já informada em outro evento para o mesmo CT-e'
        ],
        '627' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do autor do evento inválido'
        ],
        '628' => [
            'type' => 0,
            'message' => 'Rejeição: Erro Atributo ID do evento não corresponde a concatenação dos campos (“ID” + tpEvento + chCTe + nSeqEvento)'
        ],
        '629' => [
            'type' => 0,
            'message' => 'Rejeição: O tpEvento informado inválido'
        ],
        '630' => [
            'type' => 0,
            'message' => 'Rejeição: Falha no Schema XML específico para o evento'
        ],
        '631' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Duplicidade de evento[nProt:999999999999999][dhRegEvento: AAAA-MM-DDTHH:MM:SS TZD]'
        ],
        '632' => [
            'type' => 0,
            'message' => 'Rejeição: O autor do evento diverge do emissor do CT-e'
        ],
        '633' => [
            'type' => 0,
            'message' => 'Rejeição: O autor do evento não é um órgão autorizado a gerar o evento'
        ],
        '634' => [
            'type' => 0,
            'message' => 'Rejeição: A data do evento não pode ser menor que a data de emissão do CT-e'
        ],
        '635' => [
            'type' => 0,
            'message' => 'Rejeição: A data do evento não pode ser maior que a data do processamento'
        ],
        '636' => [
            'type' => 0,
            'message' => 'Rejeição: O número sequencial do evento é maior que o permitido'
        ],
        '637' => [
            'type' => 0,
            'message' => 'Rejeição: A data do evento não pode ser menor que a data de autorização do CT-e'
        ],
        '638' => [
            'type' => 0,
            'message' => 'Rejeição: Já existe CT-e autorizado com esta numeração'
        ],
        '639' => [
            'type' => 0,
            'message' => 'Rejeição: Existe EPEC emitido há mais de 7 dias (168h) sem a emissão do CT-e no ambiente normal de autorização'
        ],
        '640' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo de emissão do CT-e difere de EPEC com EPEC autorizado na SVC-XX para este documento.'
        ],
        '641' => [
            'type' => 0,
            'message' => 'Rejeição: O evento prévio deste CT-e não foi autorizado na SVC ou ainda não foi sincronizado.[OBS: Em caso de atraso na sincronização, favor aguardar alguns instantes para nova tentativa de transmissão]'
        ],
        '642' => [
            'type' => 0,
            'message' => 'Rejeição: Os valores de ICMS, Prestação e Total da Carga do CT-e devem ser iguais aos informados no EPEC'
        ],
        '643' => [
            'type' => 0,
            'message' => 'Rejeição: As informações do tomador de serviço do CT-e devem ser iguais as informadas no EPEC'
        ],
        '644' => [
            'type' => 0,
            'message' => 'Rejeição: A informação do modal do CT-e deve ser igual a informada no EPEC'
        ],
        '645' => [
            'type' => 0,
            'message' => 'Rejeição: A UF de início e fim de prestação do CT-e devem ser iguais as informadas no EPEC.'
        ],
        '646' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e emitido em ambiente de homologação com Razão Social do remetente diferente de CT-E EMITIDO EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL'
        ],
        '647' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e emitido em ambiente de homologação com Razão Social do expedidor diferente de CT-E EMITIDO EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL'
        ],
        '648' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e emitido em ambiente de homologação com Razão Social do recebedor diferente de CT-E EMITIDO EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL'
        ],
        '649' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e emitido em ambiente de homologação com Razão Social do destinatário diferente de CT-E EMITIDO EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL'
        ],
        '650' => [
            'type' => 0,
            'message' => 'Rejeição: Valor total do serviço superior ao limite permitido (R$ 9.999.999,99)'
        ],
        '651' => [
            'type' => 0,
            'message' => 'Rejeição: Referenciar o CT-e Multimodal que foi emitido pelo OTM'
        ],
        '652' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e não pode estar cancelada ou denegada'
        ],
        '653' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo de evento não é permitido em ambiente de autorização Normal'
        ],
        '654' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo de evento não é permitido em ambiente de autorização SVC'
        ],
        '655' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e complementado deve estar com a situação autorizada (não pode estar cancelado ou denegado)'
        ],
        '656' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e complementado não pode ter sido anulado'
        ],
        '657' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e complementado não pode ter sido substituído'
        ],
        '658' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e objeto da anulação não pode ter sido complementado'
        ],
        '659' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e substituído não pode ter sido complementado'
        ],
        '660' => [
            'type' => 0,
            'message' => 'Rejeição: Vedado o cancelamento se possuir CT-e Complementar associado'
        ],
        '661' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e inexistente na base de dados da SEFAZ'
        ],
        '662' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e com diferença de Chave de Acesso'
        ],
        '664' => [
            'type' => 0,
            'message' => 'Rejeição: Evento não permitido para CT-e Substituído/Anulado'
        ],
        '667' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ-Base do Tomador deve ser igual ao CNPJ-Base do Emitente do CT-e Multimodal[chCTe: 99999999999999999999999999999999999999999999]'
        ],
        '668' => [
            'type' => 0,
            'message' => 'Rejeição: CPF do funcionário do registro de passagem inválido'
        ],
        '669' => [
            'type' => 0,
            'message' => 'Rejeição: Segundo código de barras deve ser informado para CT-e emitido em contingência FS-DA'
        ],
        '670' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Série utilizada não permitida no webservice'
        ],
        '671' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e complementado no CT-e Complementar com diferença de Chave de Acesso[chCTe: 99999999999999999999999999999999999999999999][nProt:999999999999999][dhAut: AAAA-MM-DDTHH:MM:SS TZD].'
        ],
        '672' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e de Anulação com diferença de Chave de Acesso[chCTe: 99999999999999999999999999999999999999999999] [nProt:999999999999999][dhAut: AAAA-MM-DDTHH:MM:SS TZD].'
        ],
        '673' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e Substituído com diferença de Chave de Acesso[chCTe: 99999999999999999999999999999999999999999999][nProt:999999999999999][dhAut: AAAA-MM-DDTHH:MM:SS TZD]'
        ],
        '674' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e Objeto de Anulação com diferença de Chave de Acesso[chCTe: 99999999999999999999999999999999999999999999][nProt:999999999999999][dhAut: AAAA-MM-DDTHH:MM:SS TZD]'
        ],
        '675' => [
            'type' => 0,
            'message' => 'Rejeição: Valor do imposto não corresponde à base de cálculo X alíquota'
        ],
        '676' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP informado inválido'
        ],
        '677' => [
            'type' => 0,
            'message' => 'Rejeição: Órgão de recepção do evento inválido'
        ],
        '678' => [
            'type' => 0,
            'message' => 'Rejeição: Consumo Indevido[Descrição: XXXXXXXXXXXXXXXXXXXXXXXXXXXX]'
        ],
        '679' => [ // Verified
            'type' => 7,
            'message' => 'Rejeição: O modal do CT-e deve ser Multimodal para Evento Registros do Multimodal'
        ],
        '680' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo de Emissão diferente de EPEC'
        ],
        '681' => [
            'type' => 0,
            'message' => 'Rejeição: Informação não pode ser alterada por carta de correção'
        ],
        '682' => [ // Verified
            'type' => 1,
            'message' => 'Rejeição: Já existe pedido de inutilização com a mesma faixa de inutilização [nProt:999999999999999]'
        ],
        '683' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de MDF-e inválida (Ano < 2012 ou Ano maior que Ano corrente)'
        ],
        '684' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de MDF-e inválida (Mês = 0 ou Mês > 12)'
        ],
        '685' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de MDF-e inválida (CNPJ zerado ou digito inválido)'
        ],
        '686' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de MDF-e inválida (modelo diferente de 58)'
        ],
        '687' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de MDF-e inválida (número MDF = 0)'
        ],
        '690' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e Multimodal referenciado inexistente na base de dados da SEFAZ[chCTe: 99999999999999999999999999999999999999999999]'
        ],
        '691' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e Multimodal referenciado existe com diferença de chave de acesso[chCTe: 99999999999999999999999999999999999999999999][nProt:999999999999999][dhAut: AAAA-MM-DDTHH:MM:SS TZD]'
        ],
        '692' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e Multimodal referenciado não pode estar cancelado ou denegado[chCTe: 99999999999999999999999999999999999999999999]'
        ],
        '693' => [
            'type' => 0,
            'message' => 'Rejeição: Grupo Documentos Transportados deve ser informado para tipo de serviço diferente de redespacho intermediário e serviço vinculado a multimodal'
        ],
        '694' => [
            'type' => 0,
            'message' => 'Rejeição: Grupo Documentos Transportados não pode ser informado para tipo de serviço redespacho intermediário e serviço vinculado a multimodal'
        ],
        '695' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e com emissão anterior ao evento prévio (EPEC)'
        ],
        '696' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Existe EPEC aguardando CT-e nessa faixa de numeração'
        ],
        '697' => [
            'type' => 0,
            'message' => 'Rejeição: Data de emissão do CT-e deve ser menor igual à data de autorização da EPEC'
        ],
        '698' => [
            'type' => 0,
            'message' => 'Rejeição: Evento Prévio autorizado há mais de 7 dias (168 horas)'
        ],
        '699' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ autorizado para download inválido'
        ],
        '700' => [
            'type' => 0,
            'message' => 'Rejeição: CPF autorizado para download inválido'
        ],
        '701' => [
            'type' => 0,
            'message' => 'Rejeição: Dígito Verificador inválido na Chave de acesso de CT-e da Ferrovia de Origem'
        ],
        '702' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e da Ferrovia de Origem inválida (Ano < 2009 ou Ano maior que Ano corrente)'
        ],
        '703' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e da Ferrovia de Origem inválida (Mês = 0 ou Mês > 12)'
        ],
        '704' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e da Ferrovia de Origem inválida (CNPJ zerado ou digito inválido)'
        ],
        '705' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e da Ferrovia de Origem inválida (modelo diferente de 57)'
        ],
        '706' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e da Ferrovia de Origem inválida (número CT = 0)'
        ],
        '707' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e da Ferrovia de Origem inválida (Tipo de emissão inválido)'
        ],
        '708' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e da Ferrovia de Origem inválida (UF inválida)'
        ],
        '709' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e da Ferrovia de Origem referenciado inexistente na base de dados da SEFAZ'
        ],
        '710' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e da Ferrovia de Origem referenciado existe com diferença de chave de acesso'
        ],
        '711' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e da Ferrovia de Origem referenciado não pode estar cancelado ou denegado'
        ],
        '712' => [
            'type' => 0,
            'message' => 'Rejeição: Código de Município diverge da UF de localização do emitente'
        ],
        '713' => [
            'type' => 0,
            'message' => 'Rejeição: Município do Emitente inexistente'
        ],
        '714' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de CT-e duplicada na relação de CT-e Multimodal'
        ],
        '715' => [
            'type' => 0,
            'message' => 'Rejeição: Documento autorizado ao XML duplicado no CT-e'
        ],
        '716' => [
            'type' => 0,
            'message' => 'Rejeição: IE do Remetente não informada'
        ],
        '717' => [
            'type' => 0,
            'message' => 'Rejeição: IE do Expedidor não informada'
        ],
        '718' => [
            'type' => 0,
            'message' => 'Rejeição: IE do Recebedor não informada'
        ],
        '719' => [
            'type' => 0,
            'message' => 'Rejeição: IE do Tomador não informada'
        ],
        '720' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e EPEC deve ser do tipo Normal'
        ],
        '721' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso inválida (modelo diferente de 67)'
        ],
        '722' => [
            'type' => 0,
            'message' => 'Rejeição: Tomador do serviço deve ser remetente ou destinatário para CT-e Globalizado'
        ],
        '723' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e Globalizado deve conter apenas NF-e nos documentos transportados'
        ],
        '724' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e Globalizado deve conter NF-e com CNPJ diferentes para múltiplos remetentes'
        ],
        '725' => [
            'type' => 0,
            'message' => 'Rejeição: Razão Social do Remetente do CT-e Globalizado inválido'
        ],
        '726' => [
            'type' => 0,
            'message' => 'Rejeição: Razão Social do Destinatário do CT-e Globalizado inválido'
        ],
        '727' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do remetente do CT-e Globalizado deve ser o mesmo do emitente do CT- e'
        ],
        '728' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do destinatário do CT-e Globalizado deve ser o mesmo do emitente do CT-e'
        ],
        '729' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e de múltiplos emitentes informadas nos documentos transportados sem indicador de CT-e Globalizado'
        ],
        '730' => [
            'type' => 0,
            'message' => 'Rejeição: Razão Social inválida para remetente/destinatário sem indicador de CT-e Globalizado'
        ],
        '731' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Consulta a uma Chave de Acesso muito antiga'
        ],
        '732' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso inválida (modelo diferente de 57)'
        ],
        '733' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do documento anterior deve ser o mesmo indicado no grupo emiDocAnt'
        ],
        '734' => [
            'type' => 0,
            'message' => 'Rejeição: As NF-e transportadas do CT-e substituto devem ser iguais às informadas no CT-e substituído'
        ],
        '735' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e de anulação para CT-e com tomador contribuinte exige evento de Prestação de Serviço em Desacordo'
        ],
        '736' => [
            'type' => 0,
            'message' => 'Rejeição: Existe CT-e de anulação autorizado há mais de 15 dias sem a autorização do CT-e Substituto'
        ],
        '738' => [
            'type' => 0,
            'message' => 'Rejeição: A indicação do tomador do CT-e de substituição deve ser igual a do CT-e substituído'
        ],
        '743' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e Globalizado não pode ser utilizado para operação interestadual'
        ],
        '744' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e Globalizado para tomador remetente com NF-e de emitentes diferentes'
        ],
        '745' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ base do tomador deve ser igual ao CNPJ base indicado no grupo emiDocAnt'
        ],
        '746' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo de Serviço inválido para o tomador informado'
        ],
        '747' => [
            'type' => 0,
            'message' => 'Rejeição: Documentos anteriores informados para Tipo de Serviço Normal'
        ],
        '748' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e referenciado em documentos anteriores inexistente na base de dados da SEFAZ'
        ],
        '749' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e referenciado em documentos anteriores existe com diferença de chave de acesso'
        ],
        '750' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e referenciado em documentos anteriores não pode estar cancelado ou denegado'
        ],
        '751' => [
            'type' => 0,
            'message' => 'Rejeição: UF de início e Fim da prestação devem estar preenchidas para Transporte de Pessoas'
        ],
        '752' => [
            'type' => 0,
            'message' => 'Rejeição: Município de início e Fim da prestação devem estar preenchidos para Transporte de Pessoas'
        ],
        '753' => [
            'type' => 0,
            'message' => 'Rejeição: Percurso inválido'
        ],
        '754' => [
            'type' => 0,
            'message' => 'Rejeição: Os documentos referenciados devem estar preenchidos para excesso de bagagem'
        ],
        '755' => [ // Verified
            'type' => 7,
            'message' => 'Rejeição: Autor do evento prestação do serviço em desacordo deve ser o tomador do serviço do CT-e'
        ],
        '756' => [
            'type' => 0,
            'message' => 'Rejeição: Data de emissão do CT-e deve ser igual à data de emissão da EPEC'
        ],
        '757' => [
            'type' => 0,
            'message' => 'Rejeição: O tomador do serviço deve estar informado para Transporte de Pessoas e Valores'
        ],
        '758' => [
            'type' => 0,
            'message' => 'Rejeição: Existe CT-e OS de Transporte de Valores autorizado há mais de 45 dias sem informar as GTV[chCTe: 99999999999999999999999999999999999999999999]'
        ],
        '760' => [
            'type' => 0,
            'message' => 'Rejeição: INSS deve ser preenchido para tomador pessoa jurídica'
        ],
        '761' => [
            'type' => 0,
            'message' => 'Rejeição: Dígito Verificador inválido na Chave de acesso de CT-e objeto da anulação'
        ],
        '762' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e objeto da anulação inválida (Ano < 2009 ou Ano maior que Ano corrente)'
        ],
        '763' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e objeto da anulação inválida (Mês = 0 ou Mês > 12)'
        ],
        '764' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e objeto da anulação inválida (CNPJ zerado ou digito inválido)'
        ],
        '765' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e objeto da anulação inválida (modelo diferente de 57)'
        ],
        '766' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e objeto da anulação inválida (número CT = 0)'
        ],
        '767' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e objeto da anulação inválida (Tipo de emissão invalido)'
        ],
        '768' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e objeto da anulação inválida (UF inválida)'
        ],
        '769' => [
            'type' => 0,
            'message' => 'Rejeição: Dígito Verificador inválido na Chave de acesso de CT-e substituído'
        ],
        '770' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e substituído inválida (Ano < 2009 ou Ano maior que Ano corrente)'
        ],
        '771' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e substituído inválida (Mês = 0 ou Mês > 12)'
        ],
        '772' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e substituído inválida (CNPJ zerado ou digito inválido)'
        ],
        '773' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e substituído inválida (modelo diferente de 57)'
        ],
        '774' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e substituído inválida (número CT = 0)'
        ],
        '775' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e substituído inválida (Tipo de emissão inválido)'
        ],
        '776' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e substituído inválida (UF inválida)'
        ],
        '777' => [
            'type' => 0,
            'message' => 'Rejeição: Dígito Verificador inválido na Chave de acesso de CT-e complementado'
        ],
        '778' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e complementado inválida (Ano < 2009 ou Ano maior que Ano corrente)'
        ],
        '779' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e complementado inválida (Mês = 0 ou Mês > 12)'
        ],
        '780' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e complementado inválida (CNPJ zerado ou digito inválido)'
        ],
        '781' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e complementado inválida (modelo diferente de 57)'
        ],
        '782' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e complementado inválida (número CT = 0)'
        ],
        '783' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e complementado inválida (Tipo de emissão inválido)'
        ],
        '784' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e complementado inválida (UF inválida)'
        ],
        '785' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de CT-e complementado inválida (modelo diferente de 67)'
        ],
        '786' => [
            'type' => 0,
            'message' => 'Rejeição: Grupo de informações da partilha com a UF de fim da prestação deve estar preenchido'
        ],
        '787' => [
            'type' => 0,
            'message' => 'Rejeição: Data do evento de Prestação do Serviço em desacordo deve ocorrer em até 45 dias da autorização do CT-e'
        ],
        '788' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e em situação que impede liberar prazo de cancelamento'
        ],
        '789' => [
            'type' => 0,
            'message' => 'Rejeição: UF não tem permissão de liberar prazo de cancelamento para o CT-e informado'
        ],
        '790' => [
            'type' => 0,
            'message' => 'Rejeição: Data de início da vigência inferior a data atual'
        ],
        '791' => [
            'type' => 0,
            'message' => 'Rejeição: Data de fim da vigência superior a 6 meses da data atual'
        ],
        '792' => [
            'type' => 0,
            'message' => 'Rejeição: Data de fim da vigência inferior a data de início da vigência'
        ],
        '793' => [
            'type' => 0,
            'message' => 'Rejeição: Evento de Liberação de prazo de cancelamento inexistente'
        ],
        '794' => [
            'type' => 0,
            'message' => 'Rejeição: Evento de Liberação de prazo de cancelamento já está anulado'
        ],
        '795' => [
            'type' => 0,
            'message' => 'Rejeição: Já existe CT-e autorizado para a EPEC'
        ],
        '796' => [
            'type' => 0,
            'message' => 'Rejeição: UF não tem permissão de liberar EPEC para o CT-e informado'
        ],
        '797' => [
            'type' => 0,
            'message' => 'Rejeição: A EPEC deve estar em situação de bloqueio para ser liberada pelo evento'
        ],
        '798' => [
            'type' => 0,
            'message' => 'Rejeição: Os dados específicos do modal devem estar preenchidos para Transporte de Pessoas e Excesso de Bagagem'
        ],
        '799' => [
            'type' => 0,
            'message' => 'Rejeição: Identificação do tomador utilizada em outro papel no CT-e (CNPJ/CPF ou IE)'
        ],
        '800' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ/CPF do remetente do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '801' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ/CPF do destinatário do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '802' => [
            'type' => 0,
            'message' => 'Rejeição: O CNPJ/CPF do expedidor do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '803' => [
            'type' => 0,
            'message' => 'Rejeição: O CNPJ/CPF do recebedor do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '804' => [
            'type' => 0,
            'message' => 'Rejeição: O CNPJ/CPF do tomador do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '805' => [
            'type' => 0,
            'message' => 'Rejeição: A IE do emitente do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '806' => [
            'type' => 0,
            'message' => 'Rejeição: A IE do remetente do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '807' => [
            'type' => 0,
            'message' => 'Rejeição: A IE do destinatário do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '808' => [
            'type' => 0,
            'message' => 'Rejeição: A IE do expedidor do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '809' => [
            'type' => 0,
            'message' => 'Rejeição: A IE do recebedor do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '810' => [
            'type' => 0,
            'message' => 'Rejeição: A IE do tomador do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '811' => [
            'type' => 0,
            'message' => 'Rejeição: A UF de início da prestação deve ser igual ao informado no CT-e complementado'
        ],
        '812' => [
            'type' => 0,
            'message' => 'Rejeição: A UF de fim da prestação deve ser igual ao informado no CT-e complementado'
        ],
        '813' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo de Documento inválido para operação interestadual'
        ],
        '860' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso da NF-e indicada no comprovante de entrega inválida'
        ],
        '861' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e em duplicidade no evento comprovante de entrega'
        ],
        '862' => [
            'type' => 0,
            'message' => 'Rejeição: Vedado o cancelamento quando houver evento de Comprovante de Entrega associado'
        ],
        '863' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e já possui comprovante de entrega para este CT-e'
        ],
        '864' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e não possui relação com este CT-e'
        ],
        '865' => [
            'type' => 0,
            'message' => 'Rejeição: Comprovante de entrega deve relacionar NF-e para CT-e de tipo de serviço Normal'
        ],
        '866' => [
            'type' => 0,
            'message' => 'Rejeição: Protocolo do evento a ser cancelado não existe, não está associado ao CT-e ou já está cancelado'
        ],
        '869' => [
            'type' => 0,
            'message' => 'Rejeição: Evento não permitido para CT-e Complementar ou Anulação'
        ],
        '870' => [
            'type' => 0,
            'message' => 'Rejeição: Não é permitido mais de um comprovante de entrega para CT-e (exceto CT-e Globalizado)'
        ],
        '871' => [
            'type' => 0,
            'message' => 'Rejeição: Comprovante de entrega não pode informar NF-e para CT-e de tipo de serviço diferente de Normal'
        ],
        '872' => [
            'type' => 0,
            'message' => 'Rejeição: Data e hora da entrega inválida'
        ],
        '873' => [
            'type' => 0,
            'message' => 'Rejeição: Data e hora do hash do comprovante de entrega inválida'
        ],
        '999' => [
            'type' => 0,
            'message' => 'Rejeição: Erro não catalogado (informar a mensagem de erro capturado no tratamento da exceção)'
        ],
        '301' => [
            'type' => 0,
            'message' => 'Uso Denegado: Irregularidade fiscal do emitente'
        ]
    ];
}

