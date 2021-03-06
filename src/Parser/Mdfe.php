<?php
declare(strict_types = 1);
namespace Inspire\Dfe\Parser;

use Inspire\Support\Xml\Xml;
use Inspire\Support\ {
    Arrays,
    Strings
};

/**
 * Description of Mdfe
 *
 * @author aalves
 */
class Mdfe extends Base
{

    /**
     * Parse SEFAZ response of MDFeRecepcao
     *
     * @param Xml $xml
     * @return array
     */
    public static function MDFeRecepcao(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retEnviMDFe');
        Arrays::forget($aData, [
            '@attributes'
        ]);
        return $aData;
    }

    /**
     * Parse SEFAZ response of MDFeRecepcaoSinc
     *
     * @param Xml $xml
     * @return array
     */
    public static function MDFeRecepcaoSinc(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retMDFe');
        Arrays::forget($aData, [
            '@attributes',
            'protMDFe.@attributes'
        ]);
        return $aData;
    }

    /**
     * Parse SEFAZ response of MDFeRetRecepcao
     * Fully implemented
     *
     * @param Xml $xml
     * @return array
     */
    public static function MDFeRetRecepcao(Xml $xml): array
    {
        /**
         * Arrays to easy parser
         *
         * $aData array
         */
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retConsReciMDFe');
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
        if (isset($aData['protMDFe'][0])) {
            foreach ($aData['protMDFe'] as &$ct) {
                Arrays::set($ct, 'versao', Arrays::get($ct, '@attributes.versao'));
                Arrays::forget($ct, [
                    '@attributes',
                    'infProt.@attributes'
                ]);
            }
        } else {
            Arrays::set($aData['protMDFe'], 'versao', Arrays::get($aData, 'protMDFe.@attributes.versao'));
            Arrays::forget($aData, [
                'protMDFe.@attributes',
                'protMDFe.infProt.@attributes'
            ]);
            $aData['protMDFe'] = [
                $aData['protMDFe']
            ];
        }
        /**
         * Remapping array for MDFe keys (protMDFe.[chMDFe].infProt)
         */
        foreach ($aData['protMDFe'] as $i => $prot) {
            Arrays::forget($aData['protMDFe'], $i);
            $prot['xml'] = $parserProt->xpathXml('/protMDFe', $i)->asXML();
            Arrays::set($aData['protMDFe'], Arrays::get($prot, 'infProt.chMDFe'), $prot);
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
     * Parse SEFAZ response of MDFeConsulta
     * Fully implemented
     *
     * @param Xml $xml
     * @return array
     */
    public static function MDFeConsulta(Xml $xml): array
    {
        /**
         * Arrays to easy parser
         *
         * $aData array
         */
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retConsSitMDFe');
        /**
         * Forgetting all @attributes
         */
        Arrays::forget($aData, [
            '@attributes'
        ]);
        $parser = [
            'versao' => Arrays::get($aData, '@attributes.versao'),
            'tpAmb' => Arrays::get($aData, 'tpAmb'),
            'verAplic' => Arrays::get($aData, 'verAplic'),
            'cStat' => Arrays::get($aData, 'cStat'),
            'xMotivo' => Arrays::get($aData, 'xMotivo'),
            'cUF' => Arrays::get($aData, 'cUF')
        ];
        /**
         * Adding control info
         */
        Arrays::set($parser, 'cType', self::$messages[$aData['cStat']]['type']);
        Arrays::set($parser, 'xReason', self::$messageType[$parser['cType']]);
        Arrays::set($parser, 'bStat', $parser['cType'] == 1);
        /**
         * If there is a MDFe
         */
        if ($parser['bStat']) {
            /**
             * XML to get all XML (protocol and events)
             */
            $parserProt = new Xml($xml->getXml(), true);
            /**
             * Remapping protocol data (remove attributes, add version and XML)
             */
            Arrays::set($aData, 'protMDFe.xml', $parserProt->xpathXml('/protMDFe', 0)->asXML());
            Arrays::set($aData['protMDFe'], 'versao', Arrays::get($aData, 'protMDFe.@attributes.versao'));
            Arrays::forget($aData, [
                'protMDFe.@attributes',
                'protMDFe.infProt.@attributes'
            ]);
            $parser['protMDFe'] = $aData['protMDFe'];
            /**
             * Check if there is a procEventoMDFe group
             */
            if (isset($aData['procEventoMDFe']) && ! empty($aData['procEventoMDFe'])) {
                if (! isset($aData['procEventoMDFe'][0])) {
                    $aData['procEventoMDFe'] = [
                        $aData['procEventoMDFe']
                    ];
                }
                /**
                 * Getting data for each events
                 */
                $parser['procEventoMDFe'] = [];
                foreach ($aData['procEventoMDFe'] as $i => $prot) {
                    $prot = $prot['retEventoMDFe'];
                    $parser['procEventoMDFe'][] = [
                        'versao' => Arrays::get($prot, '@attributes.versao'),
                        'tpAmb' => Arrays::get($prot, 'infEvento.tpAmb'),
                        'verAplic' => Arrays::get($prot, 'infEvento.verAplic'),
                        'cOrgao' => Arrays::get($prot, 'infEvento.cOrgao'),
                        'cStat' => Arrays::get($prot, 'infEvento.cStat'),
                        'xMotivo' => Arrays::get($prot, 'infEvento.xMotivo'),
                        'chMDFe' => Arrays::get($prot, 'infEvento.chMDFe'),
                        'tpEvento' => Arrays::get($prot, 'infEvento.tpEvento'),
                        'xEvento' => Arrays::get($prot, 'infEvento.xEvento'),
                        'nSeqEvento' => Arrays::get($prot, 'infEvento.nSeqEvento'),
                        'dhRegEvento' => Arrays::get($prot, 'infEvento.dhRegEvento'),
                        'nProt' => Arrays::get($prot, 'infEvento.nProt'),
                        'xml' => $parserProt->xpathXml('/procEventoMDFe', $i)->asXML()
                    ];
                }
            }
        }
        return $parser;
    }

    /**
     * Parse SEFAZ response of MDFeConsNaoEnc
     * Fully implemented
     *
     * @param Xml $xml
     * @return array
     */
    public static function MDFeConsNaoEnc(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retConsMDFeNaoEnc');
        Arrays::forget($aData, [
            '@attributes'
        ]);
        return $aData;
    }

    /**
     * Parse SEFAZ response of MDFeStatusServico
     * Fully implemented
     *
     * @param Xml $xml
     * @return array
     */
    public static function MDFeStatusServico(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retConsStatServMDFe');
        Arrays::forget($aData, [
            '@attributes'
        ]);
        return $aData;
    }

    /**
     * Parse SEFAZ response of MDFeRecepcaoEvento
     *
     * @param Xml $xml
     * @return array
     */
    public static function MDFeRecepcaoEvento(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retEventoMDFe');
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
     * Parse response Distribui????o documentos e informa????es de interesse do ator do MDF-e
     * Fully implemented
     *
     * @param Xml $xml
     * @return array
     */
    public static function MDFeDistribuicaoDFe(Xml $xml): array
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

    /**
     * Response codes and messages.
     * Group messages by status type
     *
     * 0 - Error on message sent. Fix package before resending
     * 1 - OK
     * 2 - Wait before try again
     * 3 - Temporary error on service
     * 4 - Permanent error on service
     * 5 - Permanent error with carrier registration
     *
     * @var array
     */
    private static array $messages = [
        '100' => [ // Verified
            'type' => 1,
            'message' => 'Autorizado o uso do MDF-e'
        ],
        '101' => [ // Verified
            'type' => 1,
            'message' => 'Cancelamento de MDF-e homologado'
        ],
        '103' => [ // Verified
            'type' => 1,
            'message' => 'Arquivo recebido com sucesso'
        ],
        '104' => [ // Verified
            'type' => 1,
            'message' => 'Arquivo processado'
        ],
        '105' => [
            'type' => 0,
            'message' => 'Arquivo em processamento'
        ],
        '106' => [
            'type' => 0,
            'message' => 'Arquivo n??o localizado'
        ],
        '107' => [
            'type' => 0,
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
        '111' => [
            'type' => 0,
            'message' => 'Consulta N??o Encerrados localizou MDF-e nessa situa????o'
        ],
        '112' => [
            'type' => 0,
            'message' => 'Consulta N??o Encerrados n??o localizou MDF-e nessa situa????o'
        ],
        '132' => [ // Verified
            'type' => 1,
            'message' => 'Encerramento de MDF-e homologado'
        ],
        '134' => [
            'type' => 0,
            'message' => 'Evento registrado com alerta para situa????o'
        ],
        '135' => [ // Verified
            'type' => 1,
            'message' => 'Evento registrado e vinculado a MDF-e'
        ],
        '136' => [
            'type' => 0,
            'message' => 'Evento registrado, mas n??o vinculado a MDF-e'
        ],
        '137' => [ // Verified
            'type' => 1,
            'message' => 'Nenhum documento localizado'
        ],
        '138' => [ // Verified
            'type' => 1,
            'message' => 'Documento localizado'
        ],
        '202' => [
            'type' => 0,
            'message' => 'Rejei????o: CPF do Emitente difere do CPF do Certificado Digital'
        ],
        '203' => [
            'type' => 0,
            'message' => 'Rejei????o: Emissor n??o habilitado para emiss??o do MDF-e'
        ],
        '204' => [
            'type' => 0,
            'message' => 'Rejei????o: Duplicidade de MDF-e [nProt:999999999999999][dhAut: AAAA-MM-DDTHH:MM:SS TZD].'
        ],
        '207' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ do emitente inv??lido'
        ],
        '209' => [
            'type' => 0,
            'message' => 'Rejei????o: IE do emitente inv??lida'
        ],
        '210' => [
            'type' => 0,
            'message' => 'Rejei????o: CPF do emitente inv??lido'
        ],
        '212' => [
            'type' => 0,
            'message' => 'Rejei????o: Data/hora de emiss??o MDF-e posterior a data/hora de recebimento'
        ],
        '213' => [
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
        '216' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de Acesso difere da cadastrada'
        ],
        '217' => [
            'type' => 0,
            'message' => 'Rejei????o: MDF-e n??o consta na base de dados da SEFAZ'
        ],
        '218' => [
            'type' => 0,
            'message' => 'Rejei????o: MDF-e j?? est?? cancelado na base de dados da SEFAZ. [nProt:999999999999999][dhCanc: AAAA-MM-DDTHH:MM:SS TZD].'
        ],
        '219' => [
            'type' => 0,
            'message' => 'Rejei????o: Circula????o do MDF-e verificada'
        ],
        '220' => [
            'type' => 0,
            'message' => 'Rejei????o: MDF-e autorizado h?? mais de 24 horas'
        ],
        '222' => [
            'type' => 0,
            'message' => 'Rejei????o: Protocolo de Autoriza????o de Uso difere do cadastrado'
        ],
        '223' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ / CPF do transmissor do arquivo difere do CNPJ / CPF do transmissor da consulta'
        ],
        '225' => [
            'type' => 0,
            'message' => 'Rejei????o: Falha no Schema XML do MDF-e'
        ],
        '226' => [
            'type' => 0,
            'message' => 'Rejei????o: C??digo da UF do Emitente diverge da UF autorizadora'
        ],
        '227' => [
            'type' => 0,
            'message' => 'Rejei????o: Erro na composi????o do Campo ID'
        ],
        '228' => [
            'type' => 0,
            'message' => 'Rejei????o: Data de emiss??o muito atrasada'
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
            'message' => 'Rejei????o: IE do emitente n??o vinculada ao CNPJ / CPF'
        ],
        '232' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ do emitente com s??rie incompat??vel'
        ],
        '233' => [
            'type' => 0,
            'message' => 'Rejei????o: CPF do emitente com s??rie incompat??vel'
        ],
        '234' => [
            'type' => 0,
            'message' => 'Rejei????o: Tipo de emitente inv??lido para emitente pessoa f??sica'
        ],
        '236' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de Acesso inv??lida [Motivo: XXXXXXXXXXXX]'
        ],
        '238' => [
            'type' => 0,
            'message' => 'Rejei????o: Cabe??alho - Vers??o do arquivo XML superior a Vers??o vigente'
        ],
        '239' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Cabe??alho - Vers??o do arquivo XML n??o suportada'
        ],
        '242' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Elemento cteCabecMsg inexistente no SOAP Header'
        ],
        '243' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: XML Mal Formado'
        ],
        '244' => [
            'type' => 0,
            'message' => 'Rejei????o: Falha na descompacta????o da ??rea de dados'
        ],
        '247' => [
            'type' => 0,
            'message' => 'Rejei????o: Sigla da UF do Emitente diverge da UF autorizadora'
        ],
        '248' => [
            'type' => 0,
            'message' => 'Rejei????o: UF do Recibo diverge da UF autorizadora'
        ],
        '249' => [
            'type' => 0,
            'message' => 'Rejei????o: UF da Chave de Acesso diverge da UF autorizadora'
        ],
        '252' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Ambiente informado diverge do Ambiente de recebimento'
        ],
        '253' => [
            'type' => 0,
            'message' => 'Rejei????o: Digito Verificador da chave de acesso composta inv??lido'
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
        '287' => [ // Verified
            'type' => 6,
            'message' => 'Rejei????o: Certificado Transmissor sem CNPJ ou CPF'
        ],
        '290' => [
            'type' => 0,
            'message' => 'Rejei????o: Certificado Assinatura inv??lido'
        ],
        '291' => [
            'type' => 0,
            'message' => 'Rejei????o: Certificado Assinatura Data Validade'
        ],
        '292' => [
            'type' => 0,
            'message' => 'Rejei????o: Certificado Assinatura sem CNPJ / CPF'
        ],
        '293' => [
            'type' => 0,
            'message' => 'Rejei????o: Certificado Assinatura - erro Cadeia de Certifica????o'
        ],
        '294' => [
            'type' => 0,
            'message' => 'Rejei????o: Certificado Assinatura revogado'
        ],
        '295' => [
            'type' => 0,
            'message' => 'Rejei????o: Certificado Assinatura difere ICP-Brasil'
        ],
        '296' => [
            'type' => 0,
            'message' => 'Rejei????o: Certificado Assinatura erro no acesso a LCR'
        ],
        '297' => [
            'type' => 0,
            'message' => 'Rejei????o: Assinatura difere do calculado'
        ],
        '298' => [
            'type' => 0,
            'message' => 'Rejei????o: Assinatura difere do padr??o do Projeto'
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
            'message' => 'Rejei????o: Munic??pio de Carregamento inexistente'
        ],
        '406' => [
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio de Descarregamento inexistente'
        ],
        '407' => [
            'type' => 0,
            'message' => 'Rejei????o: C??digo de Munic??pio diverge da UF do Emitente do MDF-e'
        ],
        '408' => [
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio do Emitente inexistente'
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
        '454' => [
            'type' => 0,
            'message' => 'Rejei????o: Tipo de Transportador para Carga Pr??pria e Propriet??rio do ve??culo diferente do emitente deve ser TAC'
        ],
        '456' => [
            'type' => 0,
            'message' => 'Rejei????o: C??digo de Munic??pio diverge da UF de Carregamento do MDF-e'
        ],
        '457' => [
            'type' => 0,
            'message' => 'Rejei????o: Tipo de Transportador deve ser diferente de TAC'
        ],
        '458' => [
            'type' => 0,
            'message' => 'Rejei????o: Tipo de Transportador n??o deve ser informado para Emitente de Carga Pr??pria propriet??rio do ve??culo'
        ],
        '459' => [
            'type' => 0,
            'message' => 'Rejei????o: Documento autorizado ao XML duplicado no MDF-e'
        ],
        '460' => [
            'type' => 0,
            'message' => 'Rejei????o: Consulta a uma Chave de Acesso muito antiga'
        ],
        '462' => [
            'type' => 0,
            'message' => 'Rejei????o: Existe MDF-e n??o encerrado h?? mais de 5 dias para placa com at?? 2 UF de percurso informadas [chMDFe: 99999999999999999999999999999999999999999999][nProt:999999999999999]'
        ],
        '473' => [
            'type' => 0,
            'message' => 'Rejei????o: Tipo Autorizador do Recibo diverge do ??rg??o Autorizador'
        ],
        '479' => [
            'type' => 0,
            'message' => 'Rejei????o: Endere??o do site da UF da Consulta via QR Code diverge do previsto'
        ],
        '480' => [
            'type' => 0,
            'message' => 'Rejei????o: O QR Code do MDF-e deve ser informado'
        ],
        '481' => [
            'type' => 0,
            'message' => 'Rejei????o: Par??metro chave de acesso do QR Code divergente do MDF-e'
        ],
        '482' => [
            'type' => 0,
            'message' => 'Rejei????o: Par??metro sign n??o informado no QR Code para emiss??o em conting??ncia'
        ],
        '488' => [
            'type' => 0,
            'message' => 'Rejei????o: Par??metro sign n??o deve ser informado no QR Code para emiss??o Normal'
        ],
        '493' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: N??mero do NSU informado superior ao maior NSU da base de dados do Ambiente Nacional'
        ],
        '496' => [
            'type' => 0,
            'message' => 'Rejei????o: Assinatura do QR-Code difere do calculado'
        ],
        '539' => [
            'type' => 0,
            'message' => 'Rejei????o: Duplicidade de MDF-e, com diferen??a na Chave de Acesso [chMDFe: 99999999999999999999999999999999999999999999][nProt:999999999999999] [dhAut: AAAA-MM-DDTHH:MM:SS TZD].'
        ],
        '540' => [
            'type' => 0,
            'message' => 'Rejei????o: N??o deve ser informado Conhecimento de Transporte para tipo de emitente Prestador Servi??o de Transporte que emitir?? CT-e Globalizado'
        ],
        '541' => [
            'type' => 0,
            'message' => 'Rejei????o: Tipo de emitente inv??lido para opera????es interestaduais ou com exterior'
        ],
        '542' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ/CPF do respons??vel pelo seguro deve ser informado para o tipo de respons??vel informado'
        ],
        '577' => [
            'type' => 0,
            'message' => 'Rejei????o: Duplicidade de condutor'
        ],
        '578' => [
            'type' => 0,
            'message' => 'Rejei????o: Informa????es dos tomadores ?? obrigat??ria para esta opera????o'
        ],
        '579' => [
            'type' => 0,
            'message' => 'Rejei????o: Vers??o informada para o modal n??o suportada'
        ],
        '580' => [
            'type' => 0,
            'message' => 'Rejei????o: Falha no Schema XML espec??fico para o modal'
        ],
        '598' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Usar somente o namespace padrao do MDF-e'
        ],
        '599' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: N??o ?? permitida a presen??a de caracteres de edi????o no in??cio/fim da mensagem ou entre as tags da mensagem'
        ],
        '600' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de Acesso difere da existente em BD'
        ],
        '601' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso do CT-e informado inv??lida [chCTe: 99999999999999999999999999999999999999999999][Motivo: XXXXXXXXXXXX]'
        ],
        '602' => [
            'type' => 0,
            'message' => 'Rejei????o: Segundo C??digo de Barras deve ser informado para CT-e em conting??ncia FS-DA'
        ],
        '603' => [
            'type' => 0,
            'message' => 'Rejei????o: Segundo C??digo de Barras n??o deve ser informado para CT-e com este tipo de emiss??o'
        ],
        '604' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso da NF-e informada inv??lida [chNFe: 99999999999999999999999999999999999999999999][Motivo: XXXXXXXXXXXX]'
        ],
        '606' => [
            'type' => 0,
            'message' => 'Rejei????o: Segundo C??digo de Barras deve ser informado para NF-e em conting??ncia (FS-DA e FS-IA)'
        ],
        '607' => [
            'type' => 0,
            'message' => 'Rejei????o: Segundo C??digo de Barras n??o deve ser informado para NF-e com este tipo de emiss??o'
        ],
        '609' => [
            'type' => 0,
            'message' => 'Rejei????o: MDF-e j?? est?? encerrado na base de dados da SEFAZ [nProt:999999999999999][dhEnc: AAAA-MM-DDTHH:MM:SS TZD].'
        ],
        '611' => [
            'type' => 0,
            'message' => 'Rejei????o: Existe MDF-e n??o encerrado para esta placa, tipo de emitente e UF descarregamento [chMDFe: 99999999999999999999999999999999999999999999][nProt:999999999999999]'
        ],
        '612' => [
            'type' => 0,
            'message' => 'Rejei????o: C??digo de Munic??pio diverge da UF de descarregamento do MDF-e'
        ],
        '614' => [
            'type' => 0,
            'message' => 'Rejei????o: C??digo de Munic??pio diverge da UF de encerramento do MDF-e'
        ],
        '615' => [
            'type' => 0,
            'message' => 'Rejei????o: Data de encerramento anterior ?? data de autoriza????o do MDF-e'
        ],
        '616' => [
            'type' => 0,
            'message' => 'Rejei????o: Nenhum grupo de documentos foi informado (CT-e, CT, NF-e, MDF-e). Retornar Munic??pio de Descarregamento sem DF-e vinculado'
        ],
        '627' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ do autor do evento inv??lido'
        ],
        '628' => [
            'type' => 0,
            'message' => 'Rejei????o: Erro Atributo ID do evento n??o corresponde ?? concatena????o dos campos (???ID??? + tpEvento + chMDFe + nSeqEvento)'
        ],
        '629' => [
            'type' => 0,
            'message' => 'Rejei????o: O tpEvento informado inv??lido'
        ],
        '630' => [
            'type' => 0,
            'message' => 'Rejei????o: Falha no Schema XML espec??fico para o evento'
        ],
        '631' => [
            'type' => 0,
            'message' => 'Rejei????o: Duplicidade de evento [nProt:999999999999999][dhRegEvento: AAAA-MM-DDTHH:MM:SS TZD]'
        ],
        '632' => [
            'type' => 0,
            'message' => 'Rejei????o: O autor do evento diverge do emissor do MDF-e'
        ],
        '633' => [
            'type' => 0,
            'message' => 'Rejei????o: O autor do evento n??o ?? um ??rg??o autorizado a gerar o evento'
        ],
        '634' => [
            'type' => 0,
            'message' => 'Rejei????o: A data do evento n??o pode ser menor que a data de emiss??o do MDF-e'
        ],
        '635' => [
            'type' => 0,
            'message' => 'Rejei????o: A data do evento n??o pode ser maior que a data do processamento'
        ],
        '636' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: O n??mero sequencial do evento ?? maior que o permitido'
        ],
        '637' => [
            'type' => 0,
            'message' => 'Rejei????o: A data do evento n??o pode ser menor que a data de autoriza????o do MDF-e'
        ],
        '638' => [
            'type' => 0,
            'message' => 'Rejei????o: N??o deve ser informada Nota Fiscal para tipo de emitente Prestador Servi??o de Transporte'
        ],
        '639' => [
            'type' => 0,
            'message' => 'Rejei????o: N??o deve ser informado Conhecimento de Transporte Eletr??nico para tipo de emitente Transporte de Carga Pr??pria.'
        ],
        '644' => [
            'type' => 0,
            'message' => 'Rejei????o: Evento de inclus??o de condutor s?? pode ser registrado para o modal rodovi??rio'
        ],
        '645' => [
            'type' => 0,
            'message' => 'Rejei????o: CPF do condutor inv??lido'
        ],
        '646' => [
            'type' => 0,
            'message' => 'Rejei????o: Placa de ve??culo formato inv??lido (UF Carregamento e Descarregamento <> ???EX???)'
        ],
        '647' => [
            'type' => 0,
            'message' => 'Rejei????o: MDF-e s?? pode ser referenciado por manifesto do modal aquavi??rio'
        ],
        '648' => [
            'type' => 0,
            'message' => 'Rejei????o: MDF-e s?? pode ser referenciado quando UF de Carregamento/Descarregamento for igual a AM ou AP'
        ],
        '649' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de MDF-e informada inv??lida [chMDFe: 99999999999999999999999999999999999999999999][Motivo: XXXXXXXXXXXX]'
        ],
        '655' => [
            'type' => 0,
            'message' => 'Rejei????o: MDF-e referenciado n??o existe na base de dados da SEFAZ'
        ],
        '656' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de Acesso do MDF-e referenciado difere da existente em BD'
        ],
        '657' => [
            'type' => 0,
            'message' => 'Rejei????o: MDF-e referenciado j?? est?? cancelado na base de dados da SEFAZ'
        ],
        '658' => [
            'type' => 0,
            'message' => 'Rejei????o: Modal do MDF-e referenciado diferente de Rodovi??rio'
        ],
        '659' => [
            'type' => 0,
            'message' => 'Rejei????o: Tipo do Emitente do MDF-e referenciado difere de Transportador de Carga Pr??pria'
        ],
        '660' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ autorizado para download inv??lido'
        ],
        '661' => [
            'type' => 0,
            'message' => 'Rejei????o: CPF autorizado para download inv??lido'
        ],
        '662' => [
            'type' => 0,
            'message' => 'Rejei????o: Existe MDF-e n??o encerrado para esta placa, tipo de emitente no sentido oposto da viagem'
        ],
        '663' => [
            'type' => 0,
            'message' => 'Rejei????o: Percurso informado inv??lido'
        ],
        '666' => [
            'type' => 0,
            'message' => 'Rejei????o: Ano do MDF-e informado na chave de acesso inv??lido'
        ],
        '667' => [
            'type' => 0,
            'message' => 'Rejei????o: Quantidade informada no grupo de totalizadores n??o confere com a quantidade de documentos relacionada'
        ],
        '668' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de Acesso de CT-e duplicada [chCTe: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX]'
        ],
        '669' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de Acesso de NF-e duplicada [chNFe: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX]'
        ],
        '671' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e informado n??o existe na base de dados da SEFAZ [chCTe: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX]'
        ],
        '672' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e informado com diferen??a de chave de acesso [chCTe: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX]'
        ],
        '673' => [
            'type' => 0,
            'message' => 'Rejei????o: CT-e informado n??o pode estar cancelado/denegado na base da SEFAZ [chCTe: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX]'
        ],
        '675' => [
            'type' => 0,
            'message' => 'Rejei????o: NF-e informada n??o existe na base de dados da SEFAZ [chNFe: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX]'
        ],
        '676' => [
            'type' => 0,
            'message' => 'Rejei????o: NF-e informada com diferen??a de chave de acesso [chNFe: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX]'
        ],
        '677' => [
            'type' => 0,
            'message' => 'Rejei????o: NF-e informada n??o pode estar cancelada/denegada na base da SEFAZ [chNFe: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX]'
        ],
        '678' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Uso Indevido'
        ],
        '680' => [
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio de descarregamento duplicado no MDF-e'
        ],
        '681' => [
            'type' => 0,
            'message' => 'Rejei????o: RNTRC informado inexistente.'
        ],
        '682' => [
            'type' => 0,
            'message' => 'Rejei????o: RNTRC situa????o inv??lida.'
        ],
        '683' => [
            'type' => 0,
            'message' => 'Rejei????o: Placa do ve??culo de tra????o n??o vinculada ao RNTRC informado.'
        ],
        '684' => [
            'type' => 0,
            'message' => 'Rejei????o: CIOT obrigat??rio para RNTRC informado.'
        ],
        '685' => [
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio de carregamento duplicado no MDF-e'
        ],
        '686' => [
            'type' => 0,
            'message' => 'Rejei????o: Existe MDF-e n??o encerrado h?? mais de 30 dias para o emitente [chMDFe: 99999999999999999999999999999999999999999999][nProt:999999999999999]'
        ],
        '687' => [
            'type' => 0,
            'message' => 'Rejei????o: RNTRC deve estar associado ao transportador indicado'
        ],
        '688' => [
            'type' => 0,
            'message' => 'Rejei????o: RNTRC deve ser informado para Prestador de Servi??o de Transporte'
        ],
        '689' => [
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio de encerramento deve ser 9999999 para encerramento no exterior'
        ],
        '698' => [
            'type' => 0,
            'message' => 'Rejei????o: Seguro da carga ?? obrigat??rio para modal Prestador de Servi??o de Transporte no modal rodovi??rio'
        ],
        '699' => [
            'type' => 0,
            'message' => 'Rejei????o: Dados do seguro de carga incompletos para o modal rodovi??rio'
        ],
        '700' => [
            'type' => 0,
            'message' => 'Rejei????o: CPF do autor do evento inv??lido'
        ],
        '701' => [
            'type' => 0,
            'message' => 'Rejei????o: Tipo de evento incompat??vel com emitente pessoa f??sica'
        ],
        '702' => [
            'type' => 0,
            'message' => 'Rejei????o: Modal inv??lido para entrega parcial (apenas modal a??reo)'
        ],
        '703' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Carregamento e Descarregamento inv??lidos para MDF-e com indica????o de carregamento posterior'
        ],
        '704' => [
            'type' => 0,
            'message' => 'Rejei????o: MDF-e com indica????o de carregamento posterior n??o permitido para opera????es interestaduais ou com o exterior'
        ],
        '705' => [
            'type' => 0,
            'message' => 'Rejei????o: Modal inv??lido para MDF-e com indica????o de carregamento posterior (apenas modal rodovi??rio)'
        ],
        '706' => [
            'type' => 0,
            'message' => 'Rejei????o: N??o informar documentos transportados para MDF-e com indica????o de carregamento posterior (usar evento inclus??o de DF-e)'
        ],
        '707' => [
            'type' => 0,
            'message' => 'Rejei????o: MDF-e com indica????o de carregamento posterior com tipo de emitente diferente de transporte pr??prio'
        ],
        '708' => [ // Verified
            'type' => 7,
            'message' => 'Rejei????o: MDF-e deve possui indica????o de carregamento posterior para inclus??o de DF-e'
        ],
        '709' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de acesso de NF-e inv??lida no evento de inclus??o [Motivo: CNPJ/CPF inv??lido / Modelo diferente de 55 / Ano inv??lido (< 2006) / M??s inv??lido (0 ou > 12) / Tipo de emiss??o inv??lido / UF inv??lida / N??mero zerado / DV inv??lido]'
        ],
        '710' => [
            'type' => 0,
            'message' => 'Rejei????o: Cancelamento n??o ?? permitido para MDF-e com indica????o de carregamento posterior que j?? realizou inser????o de DF-e'
        ],
        '711' => [
            'type' => 0,
            'message' => 'Rejei????o: NF-e j?? est?? vinculada ao MDF-e por outro evento'
        ],
        '712' => [
            'type' => 0,
            'message' => 'Rejei????o: Existe MDF-e com indica????o de carregamento posterior sem inclus??o de DF-e para o emitente h?? mais de 168 horas'
        ],
        '713' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ do desenvolvedor do sistema inv??lido (zerado ou d??gito inv??lido)'
        ],
        '714' => [
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio de encerramento inexistente'
        ],
        '715' => [
            'type' => 0,
            'message' => 'Rejei????o: N??o ?? permitido encerrar MDF-e com indica????o de carregamento posterior sem inclus??o de DF-e associada'
        ],
        '716' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ / CPF do respons??vel pela gera????o do CIOT inv??lido'
        ],
        '717' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ / CPF do contratante do transporte inv??lido'
        ],
        '718' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ / CPF do propriet??rio do ve??culo de tra????o inv??lido'
        ],
        '719' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ / CPF do propriet??rio do ve??culo reboque inv??lido'
        ],
        '720' => [
            'type' => 0,
            'message' => 'Rejei????o: Obrigat??ria as informa????es do respons??vel t??cnico'
        ],
        '721' => [
            'type' => 0,
            'message' => 'Rejei????o: Obrigat??ria a informa????o do identificador do CSRT e do Hash do CSRT'
        ],
        '730' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: NSU solicitado muito antigo [NSUMin: 999999999999999]'
        ],
        '997' => [
            'type' => 0,
            'message' => 'Rejei????o: XML do MDF-e referenciado indispon??vel no momento da valida????o (Existem situa????es em que o ambiente de autoriza????o trabalha com um banco de dados separado para o arquivo XML)'
        ],
        '999' => [
            'type' => 0,
            'message' => 'Rejei????o: Erro n??o catalogado (informar a msg de erro capturado no tratamento da exce????o)'
        ]
    ];
}

