<?php
declare(strict_types = 1);
namespace Inspire\Dfe\Parser;

use Inspire\Support\Xml\Xml;
use Inspire\Support\ {
    Arrays,
    Strings
};

/**
 * Description of Cte
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
     * Parse response Distribui????o documentos e informa????es de interesse do ator do CT-e
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
            'message' => 'Inutiliza????o de n??mero homologado'
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
            'message' => 'Lote n??o localizado'
        ],
        '107' => [
            'type' => 1,
            'message' => 'Servi??o em Opera????o'
        ],
        '108' => [ // Verified
            'type' => 3,
            'message' => 'Servi??o Paralisado Momentaneamente (curto prazo)'
        ],
        '109' => [ // Verified
            'type' => 4,
            'message' => 'Servi??o Paralisado sem Previs??o'
        ],
        '110' => [ // Verified
            'type' => 4,
            'message' => 'Uso Denegado'
        ],
        '111' => [
            'type' => 1,
            'message' => 'Consulta cadastro com uma ocorr??ncia'
        ],
        '112' => [
            'type' => 1,
            'message' => 'Consulta cadastro com mais de uma ocorr??ncia'
        ],
        '113' => [
            'type' => 1,
            'message' => 'Servi??o SVC em opera????o. Desativa????o prevista para a UF em dd/mm/aa, ??s hh:mm horas'
        ],
        '114' => [
            'type' => 0,
            'message' => 'SVC-[SP/RS] desabilitada pela SEFAZ de Origem'
        ],
        '134' => [
            'type' => 1,
            'message' => 'Evento registrado e vinculado ao CT-e com alerta para situa????o do documento. [Alerta Situa????o do CT-e: XXXXXXXXXX]'
        ],
        '135' => [
            'type' => 1,
            'message' => 'Evento registrado e vinculado a CT-e'
        ],
        '136' => [
            'type' => 3,
            'message' => 'Evento registrado, mas n??o vinculado a CT-e'
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
            'message' => 'Rejei????o: O n??mero m??ximo de numera????o de CT-e a inutilizar ultrapassou o limite'
        ],
        '202' => [
            'type' => 0,
            'message' => 'Rejei????o: Falha no reconhecimento da autoria ou integridade do arquivo digital'
        ],
        '203' => [ // Verified
            'type' => 5,
            'message' => 'Rejei????o: Emissor n??o habilitado para emiss??o do CT-e'
        ],
        '204' => [ // Verified
            'type' => 7,
            'message' => 'Rejei????o: Duplicidade de CT-e[nProt:999999999999999][dhAut: AAAA-MM-DDTHH:MM:SS TZD]'
        ],
        '205' => [
            'type' => 4,
            'message' => 'Rejei????o: CT-e est?? denegado na base de dados da SEFAZ'
        ],
        '206' => [
            'type' => 0,
            'message' => 'Rejei????o: N??mero de CT-e j?? est?? inutilizado na Base de dados da SEFAZ'
        ],
        '207' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ do emitente inv??lido'
        ],
        '208' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ do destinat??rio inv??lido'
        ],
        '209' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do emitente inv??lida'
        ],
        '210' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do destinat??rio inv??lida'
        ],
        '211' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do substituto inv??lida'
        ],
        '212' => [
            'type' => 0,
            'message' => 'Rejei????o: Data de emiss??o CT-e posterior a data de recebimento'
        ],
        '213' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: CNPJ-Base do Emitente difere do CNPJ-Base do Certificado Digital'
        ],
        '214' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Tamanho da mensagem excedeu o limite estabelecido'
        ],
        '215' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Falha no schema XML'
        ],
        '216' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Chave de Acesso difere da cadastrada'
        ],
        '217' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e n??o consta na base de dados da SEFAZ'
        ],
        '218' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e j?? est?? cancelado na base de dados da SEFAZ[nProt:999999999999999][dhCanc: AAAA-MM-DDTHH:MM:SS TZD].'
        ],
        '219' => [
            'type' => 0,
            'message' => 'Rejei????o: Circula????o do CT-e verificada'
        ],
        '220' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e autorizado h?? mais de 7 dias (168 horas)'
        ],
        '221' => [
            'type' => 0,
            'message' => 'Rejei????o: Confirmado a presta????o do servi??o do CT-e pelo destinat??rio'
        ],
        '222' => [
            'type' => 0,
            'message' => 'Rejei????o: Protocolo de Autoriza????o de Uso difere do cadastrado'
        ],
        '223' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ do transmissor do lote difere do CNPJ do transmissor da consulta'
        ],
        '224' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: A faixa inicial ?? maior que a faixa final'
        ],
        '225' => [
            'type' => 0,
            'message' => 'Rejei????o: Falha no Schema XML do CT-e'
        ],
        '226' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: C??digo da UF do Emitente diverge da UF autorizadora'
        ],
        '227' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Erro na composi????o do Campo ID'
        ],
        '228' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Data de Emiss??o muito atrasada'
        ],
        '229' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do emitente n??o informada'
        ],
        '230' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do emitente n??o cadastrada'
        ],
        '231' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do emitente n??o vinculada ao CNPJ'
        ],
        '232' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do destinat??rio n??o informada'
        ],
        '233' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do destinat??rio n??o cadastrada'
        ],
        '235' => [
            'type' => 0,
            'message' => 'Rejei????o: Inscri????o SUFRAMA inv??lida'
        ],
        '236' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Chave de Acesso com d??gito verificador inv??lido'
        ],
        '237' => [
            'type' => 0,
            'message' => 'Rejei????o: CPF do destinat??rio inv??lido'
        ],
        '238' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Cabe??alho - Vers??o do arquivo XML superior a Vers??o vigente'
        ],
        '239' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Cabe??alho - Vers??o do arquivo XML n??o suportada'
        ],
        '240' => [ // Verified
            'type' => 5,
            'message' => 'Rejei????o: Cancelamento/Inutiliza????o - Irregularidade Fiscal do Emitente'
        ],
        '241' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Um n??mero da faixa j?? foi utilizado'
        ],
        '242' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Elemento cteCabecMsg inexistente no SOAP Header'
        ],
        '243' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: XML Mal Formado'
        ],
        '245' => [
            'type' => 5,
            'message' => 'Rejei????o: CNPJ Emitente n??o cadastrado'
        ],
        '247' => [
            'type' => 0,
            'message' => 'Rejei????o: Sigla da UF do Emitente diverge da UF autorizadora'
        ],
        '248' => [
            'type' => 0,
            'message' => 'Rejei????o: UF do Recibo diverge da UF autorizadora'
        ],
        '249' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: UF da Chave de Acesso diverge da UF autorizadora'
        ],
        '250' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: UF diverge da UF autorizadora'
        ],
        '251' => [
            'type' => 0,
            'message' => 'Rejei????o: UF/Munic??pio destinat??rio n??o pertence a SUFRAMA'
        ],
        '252' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Ambiente informado diverge do Ambiente de recebimento'
        ],
        '253' => [
            'type' => 0,
            'message' => 'Rejei????o: D??gito Verificador da chave de acesso composta inv??lido'
        ],
        '256' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Um n??mero de CT-e da faixa est?? inutilizado na Base de dados da SEFAZ'
        ],
        '257' => [
            'type' => 5,
            'message' => 'Rejei????o: Solicitante n??o habilitado para emiss??o do CT-e'
        ],
        '258' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ da consulta inv??lido'
        ],
        '259' => [
            'type' => 5,
            'message' => 'Rejei????o: CNPJ da consulta n??o cadastrado como contribuinte na UF'
        ],
        '260' => [
            'type' => 0,
            'message' => 'Rejei????o: IE da consulta inv??lida'
        ],
        '261' => [
            'type' => 5,
            'message' => 'Rejei????o: IE da consulta n??o cadastrada como contribuinte na UF'
        ],
        '262' => [
            'type' => 0,
            'message' => 'Rejei????o: UF n??o fornece consulta por CPF'
        ],
        '263' => [
            'type' => 0,
            'message' => 'Rejei????o: CPF da consulta inv??lido'
        ],
        '264' => [
            'type' => 0,
            'message' => 'Rejei????o: CPF da consulta n??o cadastrado como contribuinte na UF'
        ],
        '265' => [
            'type' => 0,
            'message' => 'Rejei????o: Sigla da UF da consulta difere da UF do Web Service'
        ],
        '266' => [
            'type' => 0,
            'message' => 'Rejei????o: S??rie utilizada n??o permitida no Web Service'
        ],
        '267' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e Complementar refer??ncia um CT-e inexistente'
        ],
        '268' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e Complementar refer??ncia outro CT-e Complementar'
        ],
        '269' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ Emitente do CT-e Complementar difere do CNPJ do CT complementado'
        ],
        '280' => [ // Verified
            'type' => 6,
            'message' => 'Rejei????o: Certificado Transmissor inv??lido'
        ],
        '281' => [ // Verified
            'type' => 6,
            'message' => 'Rejei????o: Certificado Transmissor Data Validade'
        ],
        '282' => [ // Verified
            'type' => 6,
            'message' => 'Rejei????o: Certificado Transmissor sem CNPJ'
        ],
        '283' => [ // Verified
            'type' => 6,
            'message' => 'Rejei????o: Certificado Transmissor - erro Cadeia de Certifica????o'
        ],
        '284' => [ // Verified
            'type' => 6,
            'message' => 'Rejei????o: Certificado Transmissor revogado'
        ],
        '285' => [ // Verified
            'type' => 6,
            'message' => 'Rejei????o: Certificado Transmissor difere ICP-Brasil'
        ],
        '286' => [ // Verified
            'type' => 6,
            'message' => 'Rejei????o: Certificado Transmissor erro no acesso a LCR'
        ],
        '289' => [
            'type' => 0,
            'message' => 'Rejei????o: C??digo da UF informada diverge da UF solicitada'
        ],
        '290' => [ // Verified
            'type' => 6,
            'message' => 'Rejei????o: Certificado Assinatura inv??lido'
        ],
        '291' => [ // Verified
            'type' => 6,
            'message' => 'Rejei????o: Certificado Assinatura Data Validade'
        ],
        '292' => [ // Verified
            'type' => 6,
            'message' => 'Rejei????o: Certificado Assinatura sem CNPJ'
        ],
        '293' => [ // Verified
            'type' => 6,
            'message' => 'Rejei????o: Certificado Assinatura - erro Cadeia de Certifica????o'
        ],
        '294' => [ // Verified
            'type' => 6,
            'message' => 'Rejei????o: Certificado Assinatura revogado'
        ],
        '295' => [ // Verified
            'type' => 6,
            'message' => 'Rejei????o: Certificado Assinatura difere ICP-Brasil'
        ],
        '296' => [ // Verified
            'type' => 6,
            'message' => 'Rejei????o: Certificado Assinatura erro no acesso a LCR'
        ],
        '297' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Assinatura difere do calculado'
        ],
        '298' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Assinatura difere do padr??o do Projeto'
        ],
        '299' => [
            'type' => 0,
            'message' => 'Rejei????o: XML da ??rea de cabe??alho com codifica????o diferente de UTF-8'
        ],
        '401' => [
            'type' => 0,
            'message' => 'Rejei????o: CPF do remetente inv??lido'
        ],
        '402' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: XML da ??rea de dados com codifica????o diferente de UTF-8'
        ],
        '404' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Uso de prefixo de namespace n??o permitido'
        ],
        '405' => [
            'type' => 0,
            'message' => 'Rejei????o: C??digo do pa??s do emitente: d??gito inv??lido'
        ],
        '406' => [
            'type' => 0,
            'message' => 'Rejei????o: C??digo do pa??s do destinat??rio: d??gito inv??lido'
        ],
        '407' => [
            'type' => 0,
            'message' => 'Rejei????o: O CPF s?? pode ser informado no campo emitente para o CT-e avulso'
        ],
        '408' => [
            'type' => 0,
            'message' => 'Rejei????o: Lote com CT-e de diferentes UF'
        ],
        '409' => [
            'type' => 0,
            'message' => 'Rejei????o: Campo cUF inexistente no elemento cteCabecMsg do SOAP Header'
        ],
        '410' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: UF informada no campo cUF n??o ?? atendida pelo WebService'
        ],
        '411' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Campo versaoDados inexistente no elemento cteCabecMsg do SOAP Header'
        ],
        '414' => [
            'type' => 0,
            'message' => 'Rejei????o: C??digo de Munic??pio diverge da UF de t??rmino da presta????o'
        ],
        '415' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ do remetente inv??lido'
        ],
        '416' => [
            'type' => 0,
            'message' => 'Rejei????o: CPF do remetente inv??lido'
        ],
        '418' => [
            'type' => 0,
            'message' => 'Rejei????o: C??digo de Munic??pio diverge da UF de localiza????o remetente'
        ],
        '419' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do remetente inv??lida',
            'rule' => ''
        ],
        '421' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do remetente n??o cadastrada'
        ],
        '422' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do remetente n??o vinculada ao CNPJ'
        ],
        '424' => [
            'type' => 0,
            'message' => 'Rejei????o: C??digo de Munic??pio diverge da UF de localiza????o destinat??rio'
        ],
        '426' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do destinat??rio n??o cadastrada'
        ],
        '427' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do destinat??rio n??o vinculada ao CNPJ'
        ],
        '428' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ do expedidor inv??lido'
        ],
        '429' => [
            'type' => 0,
            'message' => 'Rejei????o: CPF do expedidor inv??lido'
        ],
        '431' => [
            'type' => 0,
            'message' => 'Rejei????o: C??digo de Munic??pio diverge da UF de localiza????o expedidor'
        ],
        '432' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do expedidor inv??lida'
        ],
        '434' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do expedidor n??o cadastrada'
        ],
        '435' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do expedidor n??o vinculada ao CNPJ'
        ],
        '436' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ do recebedor inv??lido'
        ],
        '437' => [
            'type' => 0,
            'message' => 'Rejei????o: CPF do recebedor inv??lido'
        ],
        '439' => [
            'type' => 0,
            'message' => 'Rejei????o: C??digo de Munic??pio diverge da UF de localiza????o recebedor'
        ],
        '440' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do recebedor inv??lida'
        ],
        '442' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do recebedor n??o cadastrada'
        ],
        '443' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do recebedor n??o vinculada ao CNPJ'
        ],
        '444' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ do tomador inv??lido'
        ],
        '445' => [
            'type' => 0,
            'message' => 'Rejei????o: CPF do tomador inv??lido'
        ],
        '447' => [
            'type' => 0,
            'message' => 'Rejei????o: C??digo de Munic??pio diverge da UF de localiza????o tomador'
        ],
        '448' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do tomador inv??lida'
        ],
        '450' => [
            'type' => 0,
            'message' => 'Rejei????o: D??gito Verificador inv??lido na Chave de acesso de CT-e Multimodal'
        ],
        '451' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e Multimodal inv??lida (Ano < 2009 ou Ano maior que Ano corrente)'
        ],
        '452' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e Multimodal inv??lida (M??s = 0 ou M??s > 12)'
        ],
        '453' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e Multimodal inv??lida (CNPJ zerado ou digito inv??lido)'
        ],
        '454' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e Multimodal inv??lida (modelo diferente de 57)'
        ],
        '456' => [
            'type' => 0,
            'message' => 'Rejei????o: C??digo de Munic??pio diverge da UF de in??cio da presta????o'
        ],
        '457' => [
            'type' => 0,
            'message' => 'Rejei????o: O lote cont??m CT-e de mais de um estabelecimento emissor'
        ],
        '458' => [
            'type' => 0,
            'message' => 'Rejei????o: Grupo de CT-e normal n??o informado para CT-e normal'
        ],
        '459' => [
            'type' => 0,
            'message' => 'Rejei????o: Grupo de CT-e complementar n??o informado para CT-e complementar'
        ],
        '460' => [
            'type' => 0,
            'message' => 'Rejei????o: N??o informado os dados do remetente indicado como tomador do servi??o'
        ],
        '461' => [
            'type' => 0,
            'message' => 'Rejei????o: N??o informado os dados do expedidor indicado como tomador do servi??o'
        ],
        '462' => [
            'type' => 0,
            'message' => 'Rejei????o: N??o informado os dados do recebedor indicado como tomador do servi??o'
        ],
        '463' => [
            'type' => 0,
            'message' => 'Rejei????o: N??o informado os dados do destinat??rio indicado como tomador do servi??o'
        ],
        '464' => [
            'type' => 0,
            'message' => 'Rejei????o: Evento Cancelado MDF-e sem existir previamente o evento MDF-e Autorizado'
        ],
        '469' => [
            'type' => 0,
            'message' => 'Rejei????o: Remetente deve ser informado para tipo de servi??o diferente de redespacho intermedi??rio ou Servi??o vinculado a multimodal'
        ],
        '470' => [
            'type' => 0,
            'message' => 'Rejei????o: Destinat??rio deve ser informado para tipo de servi??o diferente de redespacho intermedi??rio ou servi??o vinculado a multimodal'
        ],
        '471' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Ano de inutiliza????o n??o pode ser superior ao Ano atual'
        ],
        '472' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Ano de inutiliza????o n??o pode ser inferior a 2008'
        ],
        '473' => [
            'type' => 0,
            'message' => 'Rejei????o: Tipo Autorizador do Recibo diverge do ??rg??o Autorizador'
        ],
        '474' => [
            'type' => 0,
            'message' => 'Rejei????o: Expedidor deve ser informado para tipo de servi??o de redespacho intermedi??rio e servi??o vinculado a multimodal'
        ],
        '475' => [
            'type' => 0,
            'message' => 'Rejei????o: Recebedor deve ser informado para tipo de servi??o de redespacho intermedi??rio e servi??o vinculado a multimodal'
        ],
        '478' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e Multimodal inv??lida (n??mero CT = 0)'
        ],
        '479' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e Multimodal inv??lida (Tipo de emiss??o inv??lido)'
        ],
        '480' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de Acesso de CT-e anterior inv??lida (Tipo de emiss??o inv??lido)'
        ],
        '481' => [
            'type' => 0,
            'message' => 'Rejei????o: IE deve ser informada para tomador Contribuinte'
        ],
        '482' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do tomador isento deve ser preenchida com ???ISENTO???'
        ],
        '483' => [
            'type' => 0,
            'message' => 'Rejei????o: IE n??o pode ser informada para tomador n??o contribuinte'
        ],
        '489' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do tomador n??o cadastrada'
        ],
        '490' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do tomador n??o vinculada ao CNPJ'
        ],
        '491' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e complementado ?? diferente de Normal ou Substitui????o'
        ],
        '493' => [
            'type' => 0,
            'message' => 'Rejei????o: C??digo de Munic??pio diverge da UF de envio'
        ],
        '495' => [
            'type' => 0,
            'message' => 'Rejei????o: Solicitante n??o autorizado para consulta'
        ],
        '496' => [
            'type' => 0,
            'message' => 'Rejei????o: Grupo CT-e de Anula????o n??o informado para o CT-e de Anula????o'
        ],
        '497' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e objeto da anula????o inexistente'
        ],
        '498' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e objeto da anula????o deve estar com a situa????o autorizada (n??o pode estar cancelado ou denegado)'
        ],
        '499' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e de anula????o deve ter tipo de emiss??o = normal'
        ],
        '500' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e objeto da anula????o deve ter Tipo = 0 (normal) ou 3 (Substitui????o)'
        ],
        '501' => [
            'type' => 0,
            'message' => 'Rejei????o: Autoriza????o do CT-e de Anula????o deve ocorrer em at?? 60 dias da data de autoriza????o do CT-e objeto de anula????o'
        ],
        '502' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e de anula????o deve ter o valor do ICMS e de presta????o iguais ao CT-e original'
        ],
        '503' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e substituto deve ter tipo de emiss??o = normal'
        ],
        '504' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de Acesso de NF-e inv??lida (UF inv??lida)'
        ],
        '505' => [
            'type' => 0,
            'message' => 'Rejei????o: Grupo CT-e de Substitui????o n??o informado para o CT-e de Substitui????o'
        ],
        '507' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Chave de Acesso inv??lida (Tipo de emiss??o inv??lido)'
        ],
        '508' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de Acesso de NF-e inv??lida (Tipo de emiss??o inv??lido)'
        ],
        '509' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de Acesso de MDF-e inv??lida (Tipo de emiss??o inv??lido)'
        ],
        '510' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ do emitente do CT-e substituto deve ser igual ao informado no CT-e substitu??do'
        ],
        '511' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ/CPF do remetente do CT-e substituto deve ser igual ao informado no CT- e substitu??do'
        ],
        '512' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ/CPF do destinat??rio do CT-e substituto deve ser igual ao informado no CT-e substitu??do'
        ],
        '513' => [
            'type' => 0,
            'message' => 'Rejeicao: UF nao atendida pela SVC-[SP/RS]'
        ],
        '515' => [
            'type' => 0,
            'message' => 'Rejei????o: O tpEmis informado s?? ?? v??lido na conting??ncia SVC'
        ],
        '516' => [
            'type' => 0,
            'message' => 'Rejei????o: O tpEmis informado ?? incompat??vel com SVC-[SP/RS]'
        ],
        '517' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e informado em SVC deve ser Normal'
        ],
        '518' => [ // Verified
            'type' => 4,
            'message' => 'Rejei????o: Servi??o indispon??vel na SVC'
        ],
        '519' => [
            'type' => 0,
            'message' => 'Rejei????o: CFOP inv??lido para opera????o'
        ],
        '520' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e n??o pode receber mais do que 10 CT-e Complementares'
        ],
        '521' => [
            'type' => 0,
            'message' => 'Rejei????o: Os documentos de transporte anterior devem ser informados para os tipos de servi??o Subcontrata????o, Redespacho e Redespacho Intermedi??rio'
        ],
        '522' => [
            'type' => 0,
            'message' => 'Rejei????o: Nro Item Alterado inv??lido. Preencher com valor num??rico (01 ??? 99)'
        ],
        '523' => [
            'type' => 0,
            'message' => 'Rejei????o: Vedado cancelamento quando existir evento Carta de Corre????o'
        ],
        '524' => [
            'type' => 0,
            'message' => 'Rejei????o: CFOP inv??lido, informar 5932 ou 6932'
        ],
        '525' => [
            'type' => 0,
            'message' => 'Rejei????o: Carta de corre????o inv??lida (campo/grupo ???xxxx??? informado n??o existe no schema do CT-e ou n??o existe no grupo informado)'
        ],
        '526' => [
            'type' => 0,
            'message' => 'Rejei????o: Preencher informa????es dos containers somente para redespacho intermedi??rio e servi??o vinculado a multimodal'
        ],
        '527' => [
            'type' => 0,
            'message' => 'Rejei????o: NF-e duplicada no CT-e[chNFe: 99999999999999999999999999999999999999999999]'
        ],
        '528' => [
            'type' => 0,
            'message' => 'Rejei????o: Vedado cancelamento se exitir MDF-e autorizado para o CT-e'
        ],
        '530' => [
            'type' => 0,
            'message' => 'Rejei????o: Se ambiente SVC, rejeitar eventos diferentes de EPEC, Libera????o EPEC e Cancelamento'
        ],
        '531' => [
            'type' => 0,
            'message' => 'Rejei????o: Valor a receber deve ser menor ou igual Valor da Presta????o'
        ],
        '532' => [
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio do Remetente inexistente'
        ],
        '533' => [
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio do Destinat??rio inexistente'
        ],
        '534' => [
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio do Expedidor inexistente'
        ],
        '535' => [
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio do Recebedor inexistente'
        ],
        '536' => [
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio do Tomador inexistente'
        ],
        '537' => [
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio de Envio inexistente'
        ],
        '538' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e anterior inv??lida (UF inv??lida)'
        ],
        '539' => [ // Verified
            'type' => 7,
            'message' => 'Rejei????o: Duplicidade de CT-e, com diferen??a na Chave de Acesso [chCTe: [9999999999999999999999999999999999999999999][nProt:999999999999999][dhAut: AAAA-MM-DDTHH:MM:SS TZD]'
        ],
        '540' => [
            'type' => 0,
            'message' => 'Rejei????o: Grupo de documentos informado inv??lido para remetente que emite NF-e'
        ],
        '541' => [
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio de in??cio da presta????o inexistente'
        ],
        '542' => [
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio de t??rmino da presta????o inexistente'
        ],
        '543' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de CT-e duplicada em documentos anteriores'
        ],
        '544' => [
            'type' => 0,
            'message' => 'Rejei????o: D??gito Verificador inv??lido na Chave de acesso de CT-e anterior'
        ],
        '545' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e anterior inv??lida (Ano < 2009 ou Ano maior que Ano corrente'
        ],
        '546' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e anterior inv??lida (M??s = 0 ou M??s > 12)'
        ],
        '547' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e anterior inv??lida (CNPJ zerado ou digito inv??lido)'
        ],
        '548' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e anterior inv??lida (modelo diferente de 57)'
        ],
        '549' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e anterior inv??lida (n??mero CT = 0)'
        ],
        '550' => [
            'type' => 0,
            'message' => 'Rejei????o: O CNPJ/CPF do expedidor do CT-e substituto deve ser igual ao informado no CT-e substitu??do'
        ],
        '551' => [
            'type' => 0,
            'message' => 'Rejei????o: O CNPJ/CPF do recebedor do CT-e substituto deve ser igual ao informado no CT-e substitu??do'
        ],
        '552' => [
            'type' => 0,
            'message' => 'Rejei????o: O CNPJ/CPF do tomador do CT-e substituto deve ser igual ao informado no CT- e substitu??do'
        ],
        '553' => [
            'type' => 0,
            'message' => 'Rejei????o: A IE do emitente do CT-e substituto deve ser igual ao informado no CT-e substitu??do'
        ],
        '554' => [
            'type' => 0,
            'message' => 'Rejei????o: A IE do remetente do CT-e substituto deve ser igual ao informado no CT-e substitu??do'
        ],
        '555' => [
            'type' => 0,
            'message' => 'Rejei????o: A IE do destinat??rio do CT-e substituto deve ser igual ao informado no CT-e substitu??do'
        ],
        '556' => [
            'type' => 0,
            'message' => 'Rejei????o: A IE do expedidor do CT-e substituto deve ser igual ao informado no CT-e substitu??do'
        ],
        '557' => [
            'type' => 0,
            'message' => 'Rejei????o: A IE do recebedor do CT-e substituto deve ser igual ao informado no CT-e substitu??do'
        ],
        '558' => [
            'type' => 0,
            'message' => 'Rejei????o: A IE do tomador do CT-e substituto deve ser igual ao informado no CT-e substitu??do'
        ],
        '559' => [
            'type' => 0,
            'message' => 'Rejei????o: A UF de in??cio da presta????o deve ser igual ao informado no CT-e substitu??do'
        ],
        '560' => [
            'type' => 0,
            'message' => 'Rejei????o: A UF de fim da presta????o deve ser igual ao informado no CT-e substitu??do'
        ],
        '561' => [
            'type' => 0,
            'message' => 'Rejei????o: O valor da presta????o do servi??o deve ser menor ou igual ao informado no CT-e substitu??do'
        ],
        '562' => [
            'type' => 0,
            'message' => 'Rejei????o: O valor do ICMS do CT-e substituto deve ser menor ou igual ao informado no CT-e substitu??do'
        ],
        '563' => [
            'type' => 0,
            'message' => 'Rejei????o: A substitui????o de um CT-e deve ocorrer no prazo m??ximo de 60 dias contados da data de autoriza????o do CT-e objeto de Substitui????o'
        ],
        '564' => [
            'type' => 0,
            'message' => 'Rejei????o: O CT-e de anula????o n??o pode ser cancelado'
        ],
        '565' => [
            'type' => 0,
            'message' => 'Rejei????o: O CT-e s?? pode ser anulado pelo emitente'
        ],
        '566' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e objeto da anula????o n??o pode ter sido anulado anteriormente'
        ],
        '567' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e objeto da anula????o n??o pode ter sido substitu??do anteriormente'
        ],
        '568' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e a ser substitu??do inexistente'
        ],
        '569' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e a ser substitu??do deve estar com a situa????o autorizada (n??o pode estar cancelado ou denegado)'
        ],
        '570' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e a ser substitu??do n??o pode ter sido substitu??do anteriormente'
        ],
        '571' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e a ser substitu??do deve ter Tipo = 0 (normal) ou 3 (Substitui????o)'
        ],
        '572' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e de anula????o inexistente'
        ],
        '573' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e de anula????o informado deve ter Tipo=2(Anula????o)'
        ],
        '574' => [
            'type' => 0,
            'message' => 'Rejei????o: Vedado o cancelamento de CT-e do tipo substituto (tipo=3)'
        ],
        '575' => [
            'type' => 0,
            'message' => 'Rejei????o: Vedado o cancelamento se possuir CT-e de Anula????o associado'
        ],
        '576' => [
            'type' => 0,
            'message' => 'Rejei????o: Vedado o cancelamento se possuir CT-e de Substitui????o associado'
        ],
        '577' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e a ser substitu??do n??o pode ter sido anulado anteriormente quando informados os documentos emitidos pelo tomador contribuinte'
        ],
        '578' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso do CT-e anulado deve ser igual ao substitu??do'
        ],
        '579' => [
            'type' => 0,
            'message' => 'Rejei????o: Vers??o informada para o modal n??o suportada'
        ],
        '580' => [
            'type' => 0,
            'message' => 'Rejei????o: Falha no Schema XML espec??fico para o modal'
        ],
        '581' => [
            'type' => 0,
            'message' => 'Rejei????o: Campo Valor da Carga deve ser informado para o modal'
        ],
        '582' => [
            'type' => 0,
            'message' => 'Rejei????o: Grupo Tr??fego M??tuo deve ser informado'
        ],
        '583' => [
            'type' => 0,
            'message' => 'Rejei????o: Ferrovia emitente deve ser a de origem quando respFat=1'
        ],
        '584' => [
            'type' => 0,
            'message' => 'Rejei????o: Referenciar o CT-e que foi emitido pela ferrovia de origem'
        ],
        '585' => [
            'type' => 0,
            'message' => 'Rejei????o: IE Emitente n??o autorizada a emitir CT-e para o modal informado'
        ],
        '586' => [
            'type' => 0,
            'message' => 'Rejei????o: Data e Justificativa de entrada em conting??ncia n??o devem ser informadas para tipo de emiss??o diferente de FS-DA.'
        ],
        '587' => [
            'type' => 0,
            'message' => 'Rejei????o: Data e Justificativa de entrada em conting??ncia devem ser informadas'
        ],
        '588' => [
            'type' => 0,
            'message' => 'Rejei????o: Data de entrada em conting??ncia posterior a data de emiss??o.'
        ],
        '589' => [
            'type' => 0,
            'message' => 'Rejei????o: O lote cont??m CT-e de mais de um modal'
        ],
        '590' => [
            'type' => 0,
            'message' => 'Rejei????o: O lote cont??m CT-e de mais de uma vers??o de modal'
        ],
        '591' => [
            'type' => 0,
            'message' => 'Rejei????o: D??gito Verificador inv??lido na Chave de acesso de NF-e transportada'
        ],
        '592' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso inv??lida (Ano < 2009 ou Ano maior que Ano corrente)'
        ],
        '593' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso inv??lida (M??s = 0 ou M??s > 12)'
        ],
        '594' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso inv??lida (CNPJ zerado ou digito inv??lido)'
        ],
        '595' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso inv??lida (modelo diferente de 57 ou 67)'
        ],
        '596' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso inv??lida (n??mero CT = 0)'
        ],
        '598' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Usar somente o namespace padrao do CT-e'
        ],
        '599' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: N??o ?? permitida a presen??a de caracteres de edi????o no in??cio/fim da mensagem ou entre as tags da mensagem'
        ],
        '600' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Chave de Acesso difere da existente em BD'
        ],
        '601' => [
            'type' => 0,
            'message' => 'Rejei????o: Quantidade de documentos informados no remetente excede limite de 2000'
        ],
        '602' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de NF-e inv??lida (Ano < 2005 ou Ano maior que Ano corrente)'
        ],
        '603' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de NF-e inv??lida (M??s = 0 ou M??s > 12)'
        ],
        '604' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de NF-e inv??lida (CNPJ zerado ou digito inv??lido)'
        ],
        '605' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de NF-e inv??lida (modelo diferente de 55)'
        ],
        '606' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de NF-e inv??lida (numero NF = 0)'
        ],
        '608' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e Multimodal inv??lida (UF inv??lida)'
        ],
        '609' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de Acesso de MDF-e inv??lida (UF inv??lida)'
        ],
        '610' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Chave de Acesso inv??lida (UF inv??lida)'
        ],
        '611' => [
            'type' => 0,
            'message' => 'Rejei????o: Registro Passagem CT-e inexistente'
        ],
        '612' => [
            'type' => 0,
            'message' => 'Rejei????o: Registro Passagem CT-e, Nro Protocolo difere'
        ],
        '614' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do Substituto Tribut??rio inv??lida'
        ],
        '615' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e objeto da anula????o inv??lida (modelo diferente de 67)'
        ],
        '616' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e substituido inv??lida (modelo diferente de 67)'
        ],
        '617' => [
            'type' => 0,
            'message' => 'Rejei????o: UF do tomador n??o aceita ISENTO com Inscri????o Estadual'
        ],
        '618' => [
            'type' => 0,
            'message' => 'Rejei????o: GTV informada em duplicidade no evento'
        ],
        '619' => [
            'type' => 0,
            'message' => 'Rejei????o: GTV j?? informada em outro evento para o mesmo CT-e'
        ],
        '627' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ do autor do evento inv??lido'
        ],
        '628' => [
            'type' => 0,
            'message' => 'Rejei????o: Erro Atributo ID do evento n??o corresponde a concatena????o dos campos (???ID??? + tpEvento + chCTe + nSeqEvento)'
        ],
        '629' => [
            'type' => 0,
            'message' => 'Rejei????o: O tpEvento informado inv??lido'
        ],
        '630' => [
            'type' => 0,
            'message' => 'Rejei????o: Falha no Schema XML espec??fico para o evento'
        ],
        '631' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Duplicidade de evento[nProt:999999999999999][dhRegEvento: AAAA-MM-DDTHH:MM:SS TZD]'
        ],
        '632' => [
            'type' => 0,
            'message' => 'Rejei????o: O autor do evento diverge do emissor do CT-e'
        ],
        '633' => [
            'type' => 0,
            'message' => 'Rejei????o: O autor do evento n??o ?? um ??rg??o autorizado a gerar o evento'
        ],
        '634' => [
            'type' => 0,
            'message' => 'Rejei????o: A data do evento n??o pode ser menor que a data de emiss??o do CT-e'
        ],
        '635' => [
            'type' => 0,
            'message' => 'Rejei????o: A data do evento n??o pode ser maior que a data do processamento'
        ],
        '636' => [
            'type' => 0,
            'message' => 'Rejei????o: O n??mero sequencial do evento ?? maior que o permitido'
        ],
        '637' => [
            'type' => 0,
            'message' => 'Rejei????o: A data do evento n??o pode ser menor que a data de autoriza????o do CT-e'
        ],
        '638' => [
            'type' => 0,
            'message' => 'Rejei????o: J?? existe CT-e autorizado com esta numera????o'
        ],
        '639' => [
            'type' => 0,
            'message' => 'Rejei????o: Existe EPEC emitido h?? mais de 7 dias (168h) sem a emiss??o do CT-e no ambiente normal de autoriza????o'
        ],
        '640' => [
            'type' => 0,
            'message' => 'Rejei????o: Tipo de emiss??o do CT-e difere de EPEC com EPEC autorizado na SVC-XX para este documento.'
        ],
        '641' => [
            'type' => 0,
            'message' => 'Rejei????o: O evento pr??vio deste CT-e n??o foi autorizado na SVC ou ainda n??o foi sincronizado.[OBS: Em caso de atraso na sincroniza????o, favor aguardar alguns instantes para nova tentativa de transmiss??o]'
        ],
        '642' => [
            'type' => 0,
            'message' => 'Rejei????o: Os valores de ICMS, Presta????o e Total da Carga do CT-e devem ser iguais aos informados no EPEC'
        ],
        '643' => [
            'type' => 0,
            'message' => 'Rejei????o: As informa????es do tomador de servi??o do CT-e devem ser iguais as informadas no EPEC'
        ],
        '644' => [
            'type' => 0,
            'message' => 'Rejei????o: A informa????o do modal do CT-e deve ser igual a informada no EPEC'
        ],
        '645' => [
            'type' => 0,
            'message' => 'Rejei????o: A UF de in??cio e fim de presta????o do CT-e devem ser iguais as informadas no EPEC.'
        ],
        '646' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e emitido em ambiente de homologa????o com Raz??o Social do remetente diferente de CT-E EMITIDO EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL'
        ],
        '647' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e emitido em ambiente de homologa????o com Raz??o Social do expedidor diferente de CT-E EMITIDO EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL'
        ],
        '648' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e emitido em ambiente de homologa????o com Raz??o Social do recebedor diferente de CT-E EMITIDO EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL'
        ],
        '649' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e emitido em ambiente de homologa????o com Raz??o Social do destinat??rio diferente de CT-E EMITIDO EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL'
        ],
        '650' => [
            'type' => 0,
            'message' => 'Rejei????o: Valor total do servi??o superior ao limite permitido (R$ 9.999.999,99)'
        ],
        '651' => [
            'type' => 0,
            'message' => 'Rejei????o: Referenciar o CT-e Multimodal que foi emitido pelo OTM'
        ],
        '652' => [
            'type' => 0,
            'message' => 'Rejei????o: NF-e n??o pode estar cancelada ou denegada'
        ],
        '653' => [
            'type' => 0,
            'message' => 'Rejei????o: Tipo de evento n??o ?? permitido em ambiente de autoriza????o Normal'
        ],
        '654' => [
            'type' => 0,
            'message' => 'Rejei????o: Tipo de evento n??o ?? permitido em ambiente de autoriza????o SVC'
        ],
        '655' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e complementado deve estar com a situa????o autorizada (n??o pode estar cancelado ou denegado)'
        ],
        '656' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e complementado n??o pode ter sido anulado'
        ],
        '657' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e complementado n??o pode ter sido substitu??do'
        ],
        '658' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e objeto da anula????o n??o pode ter sido complementado'
        ],
        '659' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e substitu??do n??o pode ter sido complementado'
        ],
        '660' => [
            'type' => 0,
            'message' => 'Rejei????o: Vedado o cancelamento se possuir CT-e Complementar associado'
        ],
        '661' => [
            'type' => 0,
            'message' => 'Rejei????o: NF-e inexistente na base de dados da SEFAZ'
        ],
        '662' => [
            'type' => 0,
            'message' => 'Rejei????o: NF-e com diferen??a de Chave de Acesso'
        ],
        '664' => [
            'type' => 0,
            'message' => 'Rejei????o: Evento n??o permitido para CT-e Substitu??do/Anulado'
        ],
        '667' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ-Base do Tomador deve ser igual ao CNPJ-Base do Emitente do CT-e Multimodal[chCTe: 99999999999999999999999999999999999999999999]'
        ],
        '668' => [
            'type' => 0,
            'message' => 'Rejei????o: CPF do funcion??rio do registro de passagem inv??lido'
        ],
        '669' => [
            'type' => 0,
            'message' => 'Rejei????o: Segundo c??digo de barras deve ser informado para CT-e emitido em conting??ncia FS-DA'
        ],
        '670' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: S??rie utilizada n??o permitida no webservice'
        ],
        '671' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e complementado no CT-e Complementar com diferen??a de Chave de Acesso[chCTe: 99999999999999999999999999999999999999999999][nProt:999999999999999][dhAut: AAAA-MM-DDTHH:MM:SS TZD].'
        ],
        '672' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e de Anula????o com diferen??a de Chave de Acesso[chCTe: 99999999999999999999999999999999999999999999] [nProt:999999999999999][dhAut: AAAA-MM-DDTHH:MM:SS TZD].'
        ],
        '673' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e Substitu??do com diferen??a de Chave de Acesso[chCTe: 99999999999999999999999999999999999999999999][nProt:999999999999999][dhAut: AAAA-MM-DDTHH:MM:SS TZD]'
        ],
        '674' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e Objeto de Anula????o com diferen??a de Chave de Acesso[chCTe: 99999999999999999999999999999999999999999999][nProt:999999999999999][dhAut: AAAA-MM-DDTHH:MM:SS TZD]'
        ],
        '675' => [
            'type' => 0,
            'message' => 'Rejei????o: Valor do imposto n??o corresponde ?? base de c??lculo X al??quota'
        ],
        '676' => [
            'type' => 0,
            'message' => 'Rejei????o: CFOP informado inv??lido'
        ],
        '677' => [
            'type' => 0,
            'message' => 'Rejei????o: ??rg??o de recep????o do evento inv??lido'
        ],
        '678' => [
            'type' => 0,
            'message' => 'Rejei????o: Consumo Indevido[Descri????o: XXXXXXXXXXXXXXXXXXXXXXXXXXXX]'
        ],
        '679' => [ // Verified
            'type' => 7,
            'message' => 'Rejei????o: O modal do CT-e deve ser Multimodal para Evento Registros do Multimodal'
        ],
        '680' => [
            'type' => 0,
            'message' => 'Rejei????o: Tipo de Emiss??o diferente de EPEC'
        ],
        '681' => [
            'type' => 0,
            'message' => 'Rejei????o: Informa????o n??o pode ser alterada por carta de corre????o'
        ],
        '682' => [ // Verified
            'type' => 1,
            'message' => 'Rejei????o: J?? existe pedido de inutiliza????o com a mesma faixa de inutiliza????o [nProt:999999999999999]'
        ],
        '683' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de MDF-e inv??lida (Ano < 2012 ou Ano maior que Ano corrente)'
        ],
        '684' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de MDF-e inv??lida (M??s = 0 ou M??s > 12)'
        ],
        '685' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de MDF-e inv??lida (CNPJ zerado ou digito inv??lido)'
        ],
        '686' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de MDF-e inv??lida (modelo diferente de 58)'
        ],
        '687' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de MDF-e inv??lida (n??mero MDF = 0)'
        ],
        '690' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e Multimodal referenciado inexistente na base de dados da SEFAZ[chCTe: 99999999999999999999999999999999999999999999]'
        ],
        '691' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e Multimodal referenciado existe com diferen??a de chave de acesso[chCTe: 99999999999999999999999999999999999999999999][nProt:999999999999999][dhAut: AAAA-MM-DDTHH:MM:SS TZD]'
        ],
        '692' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e Multimodal referenciado n??o pode estar cancelado ou denegado[chCTe: 99999999999999999999999999999999999999999999]'
        ],
        '693' => [
            'type' => 0,
            'message' => 'Rejei????o: Grupo Documentos Transportados deve ser informado para tipo de servi??o diferente de redespacho intermedi??rio e servi??o vinculado a multimodal'
        ],
        '694' => [
            'type' => 0,
            'message' => 'Rejei????o: Grupo Documentos Transportados n??o pode ser informado para tipo de servi??o redespacho intermedi??rio e servi??o vinculado a multimodal'
        ],
        '695' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e com emiss??o anterior ao evento pr??vio (EPEC)'
        ],
        '696' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Existe EPEC aguardando CT-e nessa faixa de numera????o'
        ],
        '697' => [
            'type' => 0,
            'message' => 'Rejei????o: Data de emiss??o do CT-e deve ser menor igual ?? data de autoriza????o da EPEC'
        ],
        '698' => [
            'type' => 0,
            'message' => 'Rejei????o: Evento Pr??vio autorizado h?? mais de 7 dias (168 horas)'
        ],
        '699' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ autorizado para download inv??lido'
        ],
        '700' => [
            'type' => 0,
            'message' => 'Rejei????o: CPF autorizado para download inv??lido'
        ],
        '701' => [
            'type' => 0,
            'message' => 'Rejei????o: D??gito Verificador inv??lido na Chave de acesso de CT-e da Ferrovia de Origem'
        ],
        '702' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e da Ferrovia de Origem inv??lida (Ano < 2009 ou Ano maior que Ano corrente)'
        ],
        '703' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e da Ferrovia de Origem inv??lida (M??s = 0 ou M??s > 12)'
        ],
        '704' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e da Ferrovia de Origem inv??lida (CNPJ zerado ou digito inv??lido)'
        ],
        '705' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e da Ferrovia de Origem inv??lida (modelo diferente de 57)'
        ],
        '706' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e da Ferrovia de Origem inv??lida (n??mero CT = 0)'
        ],
        '707' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e da Ferrovia de Origem inv??lida (Tipo de emiss??o inv??lido)'
        ],
        '708' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e da Ferrovia de Origem inv??lida (UF inv??lida)'
        ],
        '709' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e da Ferrovia de Origem referenciado inexistente na base de dados da SEFAZ'
        ],
        '710' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e da Ferrovia de Origem referenciado existe com diferen??a de chave de acesso'
        ],
        '711' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e da Ferrovia de Origem referenciado n??o pode estar cancelado ou denegado'
        ],
        '712' => [
            'type' => 0,
            'message' => 'Rejei????o: C??digo de Munic??pio diverge da UF de localiza????o do emitente'
        ],
        '713' => [
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio do Emitente inexistente'
        ],
        '714' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de CT-e duplicada na rela????o de CT-e Multimodal'
        ],
        '715' => [
            'type' => 0,
            'message' => 'Rejei????o: Documento autorizado ao XML duplicado no CT-e'
        ],
        '716' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do Remetente n??o informada'
        ],
        '717' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do Expedidor n??o informada'
        ],
        '718' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do Recebedor n??o informada'
        ],
        '719' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do Tomador n??o informada'
        ],
        '720' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e EPEC deve ser do tipo Normal'
        ],
        '721' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso inv??lida (modelo diferente de 67)'
        ],
        '722' => [
            'type' => 0,
            'message' => 'Rejei????o: Tomador do servi??o deve ser remetente ou destinat??rio para CT-e Globalizado'
        ],
        '723' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e Globalizado deve conter apenas NF-e nos documentos transportados'
        ],
        '724' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e Globalizado deve conter NF-e com CNPJ diferentes para m??ltiplos remetentes'
        ],
        '725' => [
            'type' => 0,
            'message' => 'Rejei????o: Raz??o Social do Remetente do CT-e Globalizado inv??lido'
        ],
        '726' => [
            'type' => 0,
            'message' => 'Rejei????o: Raz??o Social do Destinat??rio do CT-e Globalizado inv??lido'
        ],
        '727' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ do remetente do CT-e Globalizado deve ser o mesmo do emitente do CT- e'
        ],
        '728' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ do destinat??rio do CT-e Globalizado deve ser o mesmo do emitente do CT-e'
        ],
        '729' => [
            'type' => 0,
            'message' => 'Rejei????o: NF-e de m??ltiplos emitentes informadas nos documentos transportados sem indicador de CT-e Globalizado'
        ],
        '730' => [
            'type' => 0,
            'message' => 'Rejei????o: Raz??o Social inv??lida para remetente/destinat??rio sem indicador de CT-e Globalizado'
        ],
        '731' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Consulta a uma Chave de Acesso muito antiga'
        ],
        '732' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso inv??lida (modelo diferente de 57)'
        ],
        '733' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ do documento anterior deve ser o mesmo indicado no grupo emiDocAnt'
        ],
        '734' => [
            'type' => 0,
            'message' => 'Rejei????o: As NF-e transportadas do CT-e substituto devem ser iguais ??s informadas no CT-e substitu??do'
        ],
        '735' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e de anula????o para CT-e com tomador contribuinte exige evento de Presta????o de Servi??o em Desacordo'
        ],
        '736' => [
            'type' => 0,
            'message' => 'Rejei????o: Existe CT-e de anula????o autorizado h?? mais de 15 dias sem a autoriza????o do CT-e Substituto'
        ],
        '738' => [
            'type' => 0,
            'message' => 'Rejei????o: A indica????o do tomador do CT-e de substitui????o deve ser igual a do CT-e substitu??do'
        ],
        '743' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e Globalizado n??o pode ser utilizado para opera????o interestadual'
        ],
        '744' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e Globalizado para tomador remetente com NF-e de emitentes diferentes'
        ],
        '745' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ base do tomador deve ser igual ao CNPJ base indicado no grupo emiDocAnt'
        ],
        '746' => [
            'type' => 0,
            'message' => 'Rejei????o: Tipo de Servi??o inv??lido para o tomador informado'
        ],
        '747' => [
            'type' => 0,
            'message' => 'Rejei????o: Documentos anteriores informados para Tipo de Servi??o Normal'
        ],
        '748' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e referenciado em documentos anteriores inexistente na base de dados da SEFAZ'
        ],
        '749' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e referenciado em documentos anteriores existe com diferen??a de chave de acesso'
        ],
        '750' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e referenciado em documentos anteriores n??o pode estar cancelado ou denegado'
        ],
        '751' => [
            'type' => 0,
            'message' => 'Rejei????o: UF de in??cio e Fim da presta????o devem estar preenchidas para Transporte de Pessoas'
        ],
        '752' => [
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio de in??cio e Fim da presta????o devem estar preenchidos para Transporte de Pessoas'
        ],
        '753' => [
            'type' => 0,
            'message' => 'Rejei????o: Percurso inv??lido'
        ],
        '754' => [
            'type' => 0,
            'message' => 'Rejei????o: Os documentos referenciados devem estar preenchidos para excesso de bagagem'
        ],
        '755' => [ // Verified
            'type' => 7,
            'message' => 'Rejei????o: Autor do evento presta????o do servi??o em desacordo deve ser o tomador do servi??o do CT-e'
        ],
        '756' => [
            'type' => 0,
            'message' => 'Rejei????o: Data de emiss??o do CT-e deve ser igual ?? data de emiss??o da EPEC'
        ],
        '757' => [
            'type' => 0,
            'message' => 'Rejei????o: O tomador do servi??o deve estar informado para Transporte de Pessoas e Valores'
        ],
        '758' => [
            'type' => 0,
            'message' => 'Rejei????o: Existe CT-e OS de Transporte de Valores autorizado h?? mais de 45 dias sem informar as GTV[chCTe: 99999999999999999999999999999999999999999999]'
        ],
        '760' => [
            'type' => 0,
            'message' => 'Rejei????o: INSS deve ser preenchido para tomador pessoa jur??dica'
        ],
        '761' => [
            'type' => 0,
            'message' => 'Rejei????o: D??gito Verificador inv??lido na Chave de acesso de CT-e objeto da anula????o'
        ],
        '762' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e objeto da anula????o inv??lida (Ano < 2009 ou Ano maior que Ano corrente)'
        ],
        '763' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e objeto da anula????o inv??lida (M??s = 0 ou M??s > 12)'
        ],
        '764' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e objeto da anula????o inv??lida (CNPJ zerado ou digito inv??lido)'
        ],
        '765' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e objeto da anula????o inv??lida (modelo diferente de 57)'
        ],
        '766' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e objeto da anula????o inv??lida (n??mero CT = 0)'
        ],
        '767' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e objeto da anula????o inv??lida (Tipo de emiss??o invalido)'
        ],
        '768' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e objeto da anula????o inv??lida (UF inv??lida)'
        ],
        '769' => [
            'type' => 0,
            'message' => 'Rejei????o: D??gito Verificador inv??lido na Chave de acesso de CT-e substitu??do'
        ],
        '770' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e substitu??do inv??lida (Ano < 2009 ou Ano maior que Ano corrente)'
        ],
        '771' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e substitu??do inv??lida (M??s = 0 ou M??s > 12)'
        ],
        '772' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e substitu??do inv??lida (CNPJ zerado ou digito inv??lido)'
        ],
        '773' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e substitu??do inv??lida (modelo diferente de 57)'
        ],
        '774' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e substitu??do inv??lida (n??mero CT = 0)'
        ],
        '775' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e substitu??do inv??lida (Tipo de emiss??o inv??lido)'
        ],
        '776' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e substitu??do inv??lida (UF inv??lida)'
        ],
        '777' => [
            'type' => 0,
            'message' => 'Rejei????o: D??gito Verificador inv??lido na Chave de acesso de CT-e complementado'
        ],
        '778' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e complementado inv??lida (Ano < 2009 ou Ano maior que Ano corrente)'
        ],
        '779' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e complementado inv??lida (M??s = 0 ou M??s > 12)'
        ],
        '780' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e complementado inv??lida (CNPJ zerado ou digito inv??lido)'
        ],
        '781' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e complementado inv??lida (modelo diferente de 57)'
        ],
        '782' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e complementado inv??lida (n??mero CT = 0)'
        ],
        '783' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e complementado inv??lida (Tipo de emiss??o inv??lido)'
        ],
        '784' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e complementado inv??lida (UF inv??lida)'
        ],
        '785' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de CT-e complementado inv??lida (modelo diferente de 67)'
        ],
        '786' => [
            'type' => 0,
            'message' => 'Rejei????o: Grupo de informa????es da partilha com a UF de fim da presta????o deve estar preenchido'
        ],
        '787' => [
            'type' => 0,
            'message' => 'Rejei????o: Data do evento de Presta????o do Servi??o em desacordo deve ocorrer em at?? 45 dias da autoriza????o do CT-e'
        ],
        '788' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e em situa????o que impede liberar prazo de cancelamento'
        ],
        '789' => [
            'type' => 0,
            'message' => 'Rejei????o: UF n??o tem permiss??o de liberar prazo de cancelamento para o CT-e informado'
        ],
        '790' => [
            'type' => 0,
            'message' => 'Rejei????o: Data de in??cio da vig??ncia inferior a data atual'
        ],
        '791' => [
            'type' => 0,
            'message' => 'Rejei????o: Data de fim da vig??ncia superior a 6 meses da data atual'
        ],
        '792' => [
            'type' => 0,
            'message' => 'Rejei????o: Data de fim da vig??ncia inferior a data de in??cio da vig??ncia'
        ],
        '793' => [
            'type' => 0,
            'message' => 'Rejei????o: Evento de Libera????o de prazo de cancelamento inexistente'
        ],
        '794' => [
            'type' => 0,
            'message' => 'Rejei????o: Evento de Libera????o de prazo de cancelamento j?? est?? anulado'
        ],
        '795' => [
            'type' => 0,
            'message' => 'Rejei????o: J?? existe CT-e autorizado para a EPEC'
        ],
        '796' => [
            'type' => 0,
            'message' => 'Rejei????o: UF n??o tem permiss??o de liberar EPEC para o CT-e informado'
        ],
        '797' => [
            'type' => 0,
            'message' => 'Rejei????o: A EPEC deve estar em situa????o de bloqueio para ser liberada pelo evento'
        ],
        '798' => [
            'type' => 0,
            'message' => 'Rejei????o: Os dados espec??ficos do modal devem estar preenchidos para Transporte de Pessoas e Excesso de Bagagem'
        ],
        '799' => [
            'type' => 0,
            'message' => 'Rejei????o: Identifica????o do tomador utilizada em outro papel no CT-e (CNPJ/CPF ou IE)'
        ],
        '800' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ/CPF do remetente do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '801' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ/CPF do destinat??rio do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '802' => [
            'type' => 0,
            'message' => 'Rejei????o: O CNPJ/CPF do expedidor do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '803' => [
            'type' => 0,
            'message' => 'Rejei????o: O CNPJ/CPF do recebedor do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '804' => [
            'type' => 0,
            'message' => 'Rejei????o: O CNPJ/CPF do tomador do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '805' => [
            'type' => 0,
            'message' => 'Rejei????o: A IE do emitente do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '806' => [
            'type' => 0,
            'message' => 'Rejei????o: A IE do remetente do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '807' => [
            'type' => 0,
            'message' => 'Rejei????o: A IE do destinat??rio do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '808' => [
            'type' => 0,
            'message' => 'Rejei????o: A IE do expedidor do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '809' => [
            'type' => 0,
            'message' => 'Rejei????o: A IE do recebedor do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '810' => [
            'type' => 0,
            'message' => 'Rejei????o: A IE do tomador do CT-e complementar deve ser igual ao informado no CT-e complementado'
        ],
        '811' => [
            'type' => 0,
            'message' => 'Rejei????o: A UF de in??cio da presta????o deve ser igual ao informado no CT-e complementado'
        ],
        '812' => [
            'type' => 0,
            'message' => 'Rejei????o: A UF de fim da presta????o deve ser igual ao informado no CT-e complementado'
        ],
        '813' => [
            'type' => 0,
            'message' => 'Rejei????o: Tipo de Documento inv??lido para opera????o interestadual'
        ],
        '860' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso da NF-e indicada no comprovante de entrega inv??lida'
        ],
        '861' => [
            'type' => 0,
            'message' => 'Rejei????o: NF-e em duplicidade no evento comprovante de entrega'
        ],
        '862' => [
            'type' => 0,
            'message' => 'Rejei????o: Vedado o cancelamento quando houver evento de Comprovante de Entrega associado'
        ],
        '863' => [
            'type' => 0,
            'message' => 'Rejei????o: NF-e j?? possui comprovante de entrega para este CT-e'
        ],
        '864' => [
            'type' => 0,
            'message' => 'Rejei????o: NF-e n??o possui rela????o com este CT-e'
        ],
        '865' => [
            'type' => 0,
            'message' => 'Rejei????o: Comprovante de entrega deve relacionar NF-e para CT-e de tipo de servi??o Normal'
        ],
        '866' => [
            'type' => 0,
            'message' => 'Rejei????o: Protocolo do evento a ser cancelado n??o existe, n??o est?? associado ao CT-e ou j?? est?? cancelado'
        ],
        '869' => [
            'type' => 0,
            'message' => 'Rejei????o: Evento n??o permitido para CT-e Complementar ou Anula????o'
        ],
        '870' => [
            'type' => 0,
            'message' => 'Rejei????o: N??o ?? permitido mais de um comprovante de entrega para CT-e (exceto CT-e Globalizado)'
        ],
        '871' => [
            'type' => 0,
            'message' => 'Rejei????o: Comprovante de entrega n??o pode informar NF-e para CT-e de tipo de servi??o diferente de Normal'
        ],
        '872' => [
            'type' => 0,
            'message' => 'Rejei????o: Data e hora da entrega inv??lida'
        ],
        '873' => [
            'type' => 0,
            'message' => 'Rejei????o: Data e hora do hash do comprovante de entrega inv??lida'
        ],
        '999' => [
            'type' => 0,
            'message' => 'Rejei????o: Erro n??o catalogado (informar a mensagem de erro capturado no tratamento da exce????o)'
        ],
        '301' => [
            'type' => 0,
            'message' => 'Uso Denegado: Irregularidade fiscal do emitente'
        ]
    ];
}

