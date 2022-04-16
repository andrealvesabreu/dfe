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
     * Parse response Distribuição documentos e informações de interesse do ator do MDF-e
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
            'message' => 'Arquivo não localizado'
        ],
        '107' => [
            'type' => 0,
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
        '111' => [
            'type' => 0,
            'message' => 'Consulta Não Encerrados localizou MDF-e nessa situação'
        ],
        '112' => [
            'type' => 0,
            'message' => 'Consulta Não Encerrados não localizou MDF-e nessa situação'
        ],
        '132' => [ // Verified
            'type' => 1,
            'message' => 'Encerramento de MDF-e homologado'
        ],
        '134' => [
            'type' => 0,
            'message' => 'Evento registrado com alerta para situação'
        ],
        '135' => [ // Verified
            'type' => 1,
            'message' => 'Evento registrado e vinculado a MDF-e'
        ],
        '136' => [
            'type' => 0,
            'message' => 'Evento registrado, mas não vinculado a MDF-e'
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
            'message' => 'Rejeição: CPF do Emitente difere do CPF do Certificado Digital'
        ],
        '203' => [
            'type' => 0,
            'message' => 'Rejeição: Emissor não habilitado para emissão do MDF-e'
        ],
        '204' => [
            'type' => 0,
            'message' => 'Rejeição: Duplicidade de MDF-e [nProt:999999999999999][dhAut: AAAA-MM-DDTHH:MM:SS TZD].'
        ],
        '207' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do emitente inválido'
        ],
        '209' => [
            'type' => 0,
            'message' => 'Rejeição: IE do emitente inválida'
        ],
        '210' => [
            'type' => 0,
            'message' => 'Rejeição: CPF do emitente inválido'
        ],
        '212' => [
            'type' => 0,
            'message' => 'Rejeição: Data/hora de emissão MDF-e posterior a data/hora de recebimento'
        ],
        '213' => [
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
        '216' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso difere da cadastrada'
        ],
        '217' => [
            'type' => 0,
            'message' => 'Rejeição: MDF-e não consta na base de dados da SEFAZ'
        ],
        '218' => [
            'type' => 0,
            'message' => 'Rejeição: MDF-e já está cancelado na base de dados da SEFAZ. [nProt:999999999999999][dhCanc: AAAA-MM-DDTHH:MM:SS TZD].'
        ],
        '219' => [
            'type' => 0,
            'message' => 'Rejeição: Circulação do MDF-e verificada'
        ],
        '220' => [
            'type' => 0,
            'message' => 'Rejeição: MDF-e autorizado há mais de 24 horas'
        ],
        '222' => [
            'type' => 0,
            'message' => 'Rejeição: Protocolo de Autorização de Uso difere do cadastrado'
        ],
        '223' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ / CPF do transmissor do arquivo difere do CNPJ / CPF do transmissor da consulta'
        ],
        '225' => [
            'type' => 0,
            'message' => 'Rejeição: Falha no Schema XML do MDF-e'
        ],
        '226' => [
            'type' => 0,
            'message' => 'Rejeição: Código da UF do Emitente diverge da UF autorizadora'
        ],
        '227' => [
            'type' => 0,
            'message' => 'Rejeição: Erro na composição do Campo ID'
        ],
        '228' => [
            'type' => 0,
            'message' => 'Rejeição: Data de emissão muito atrasada'
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
            'message' => 'Rejeição: IE do emitente não vinculada ao CNPJ / CPF'
        ],
        '232' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do emitente com série incompatível'
        ],
        '233' => [
            'type' => 0,
            'message' => 'Rejeição: CPF do emitente com série incompatível'
        ],
        '234' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo de emitente inválido para emitente pessoa física'
        ],
        '236' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso inválida [Motivo: XXXXXXXXXXXX]'
        ],
        '238' => [
            'type' => 0,
            'message' => 'Rejeição: Cabeçalho - Versão do arquivo XML superior a Versão vigente'
        ],
        '239' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Cabeçalho - Versão do arquivo XML não suportada'
        ],
        '242' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Elemento cteCabecMsg inexistente no SOAP Header'
        ],
        '243' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: XML Mal Formado'
        ],
        '244' => [
            'type' => 0,
            'message' => 'Rejeição: Falha na descompactação da área de dados'
        ],
        '247' => [
            'type' => 0,
            'message' => 'Rejeição: Sigla da UF do Emitente diverge da UF autorizadora'
        ],
        '248' => [
            'type' => 0,
            'message' => 'Rejeição: UF do Recibo diverge da UF autorizadora'
        ],
        '249' => [
            'type' => 0,
            'message' => 'Rejeição: UF da Chave de Acesso diverge da UF autorizadora'
        ],
        '252' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Ambiente informado diverge do Ambiente de recebimento'
        ],
        '253' => [
            'type' => 0,
            'message' => 'Rejeição: Digito Verificador da chave de acesso composta inválido'
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
        '287' => [ // Verified
            'type' => 6,
            'message' => 'Rejeição: Certificado Transmissor sem CNPJ ou CPF'
        ],
        '290' => [
            'type' => 0,
            'message' => 'Rejeição: Certificado Assinatura inválido'
        ],
        '291' => [
            'type' => 0,
            'message' => 'Rejeição: Certificado Assinatura Data Validade'
        ],
        '292' => [
            'type' => 0,
            'message' => 'Rejeição: Certificado Assinatura sem CNPJ / CPF'
        ],
        '293' => [
            'type' => 0,
            'message' => 'Rejeição: Certificado Assinatura - erro Cadeia de Certificação'
        ],
        '294' => [
            'type' => 0,
            'message' => 'Rejeição: Certificado Assinatura revogado'
        ],
        '295' => [
            'type' => 0,
            'message' => 'Rejeição: Certificado Assinatura difere ICP-Brasil'
        ],
        '296' => [
            'type' => 0,
            'message' => 'Rejeição: Certificado Assinatura erro no acesso a LCR'
        ],
        '297' => [
            'type' => 0,
            'message' => 'Rejeição: Assinatura difere do calculado'
        ],
        '298' => [
            'type' => 0,
            'message' => 'Rejeição: Assinatura difere do padrão do Projeto'
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
            'message' => 'Rejeição: Município de Carregamento inexistente'
        ],
        '406' => [
            'type' => 0,
            'message' => 'Rejeição: Município de Descarregamento inexistente'
        ],
        '407' => [
            'type' => 0,
            'message' => 'Rejeição: Código de Município diverge da UF do Emitente do MDF-e'
        ],
        '408' => [
            'type' => 0,
            'message' => 'Rejeição: Município do Emitente inexistente'
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
        '454' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo de Transportador para Carga Própria e Proprietário do veículo diferente do emitente deve ser TAC'
        ],
        '456' => [
            'type' => 0,
            'message' => 'Rejeição: Código de Município diverge da UF de Carregamento do MDF-e'
        ],
        '457' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo de Transportador deve ser diferente de TAC'
        ],
        '458' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo de Transportador não deve ser informado para Emitente de Carga Própria proprietário do veículo'
        ],
        '459' => [
            'type' => 0,
            'message' => 'Rejeição: Documento autorizado ao XML duplicado no MDF-e'
        ],
        '460' => [
            'type' => 0,
            'message' => 'Rejeição: Consulta a uma Chave de Acesso muito antiga'
        ],
        '462' => [
            'type' => 0,
            'message' => 'Rejeição: Existe MDF-e não encerrado há mais de 5 dias para placa com até 2 UF de percurso informadas [chMDFe: 99999999999999999999999999999999999999999999][nProt:999999999999999]'
        ],
        '473' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo Autorizador do Recibo diverge do Órgão Autorizador'
        ],
        '479' => [
            'type' => 0,
            'message' => 'Rejeição: Endereço do site da UF da Consulta via QR Code diverge do previsto'
        ],
        '480' => [
            'type' => 0,
            'message' => 'Rejeição: O QR Code do MDF-e deve ser informado'
        ],
        '481' => [
            'type' => 0,
            'message' => 'Rejeição: Parâmetro chave de acesso do QR Code divergente do MDF-e'
        ],
        '482' => [
            'type' => 0,
            'message' => 'Rejeição: Parâmetro sign não informado no QR Code para emissão em contingência'
        ],
        '488' => [
            'type' => 0,
            'message' => 'Rejeição: Parâmetro sign não deve ser informado no QR Code para emissão Normal'
        ],
        '493' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Número do NSU informado superior ao maior NSU da base de dados do Ambiente Nacional'
        ],
        '496' => [
            'type' => 0,
            'message' => 'Rejeição: Assinatura do QR-Code difere do calculado'
        ],
        '539' => [
            'type' => 0,
            'message' => 'Rejeição: Duplicidade de MDF-e, com diferença na Chave de Acesso [chMDFe: 99999999999999999999999999999999999999999999][nProt:999999999999999] [dhAut: AAAA-MM-DDTHH:MM:SS TZD].'
        ],
        '540' => [
            'type' => 0,
            'message' => 'Rejeição: Não deve ser informado Conhecimento de Transporte para tipo de emitente Prestador Serviço de Transporte que emitirá CT-e Globalizado'
        ],
        '541' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo de emitente inválido para operações interestaduais ou com exterior'
        ],
        '542' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ/CPF do responsável pelo seguro deve ser informado para o tipo de responsável informado'
        ],
        '577' => [
            'type' => 0,
            'message' => 'Rejeição: Duplicidade de condutor'
        ],
        '578' => [
            'type' => 0,
            'message' => 'Rejeição: Informações dos tomadores é obrigatória para esta operação'
        ],
        '579' => [
            'type' => 0,
            'message' => 'Rejeição: Versão informada para o modal não suportada'
        ],
        '580' => [
            'type' => 0,
            'message' => 'Rejeição: Falha no Schema XML específico para o modal'
        ],
        '598' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Usar somente o namespace padrao do MDF-e'
        ],
        '599' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Não é permitida a presença de caracteres de edição no início/fim da mensagem ou entre as tags da mensagem'
        ],
        '600' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso difere da existente em BD'
        ],
        '601' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso do CT-e informado inválida [chCTe: 99999999999999999999999999999999999999999999][Motivo: XXXXXXXXXXXX]'
        ],
        '602' => [
            'type' => 0,
            'message' => 'Rejeição: Segundo Código de Barras deve ser informado para CT-e em contingência FS-DA'
        ],
        '603' => [
            'type' => 0,
            'message' => 'Rejeição: Segundo Código de Barras não deve ser informado para CT-e com este tipo de emissão'
        ],
        '604' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso da NF-e informada inválida [chNFe: 99999999999999999999999999999999999999999999][Motivo: XXXXXXXXXXXX]'
        ],
        '606' => [
            'type' => 0,
            'message' => 'Rejeição: Segundo Código de Barras deve ser informado para NF-e em contingência (FS-DA e FS-IA)'
        ],
        '607' => [
            'type' => 0,
            'message' => 'Rejeição: Segundo Código de Barras não deve ser informado para NF-e com este tipo de emissão'
        ],
        '609' => [
            'type' => 0,
            'message' => 'Rejeição: MDF-e já está encerrado na base de dados da SEFAZ [nProt:999999999999999][dhEnc: AAAA-MM-DDTHH:MM:SS TZD].'
        ],
        '611' => [
            'type' => 0,
            'message' => 'Rejeição: Existe MDF-e não encerrado para esta placa, tipo de emitente e UF descarregamento [chMDFe: 99999999999999999999999999999999999999999999][nProt:999999999999999]'
        ],
        '612' => [
            'type' => 0,
            'message' => 'Rejeição: Código de Município diverge da UF de descarregamento do MDF-e'
        ],
        '614' => [
            'type' => 0,
            'message' => 'Rejeição: Código de Município diverge da UF de encerramento do MDF-e'
        ],
        '615' => [
            'type' => 0,
            'message' => 'Rejeição: Data de encerramento anterior à data de autorização do MDF-e'
        ],
        '616' => [
            'type' => 0,
            'message' => 'Rejeição: Nenhum grupo de documentos foi informado (CT-e, CT, NF-e, MDF-e). Retornar Município de Descarregamento sem DF-e vinculado'
        ],
        '627' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do autor do evento inválido'
        ],
        '628' => [
            'type' => 0,
            'message' => 'Rejeição: Erro Atributo ID do evento não corresponde à concatenação dos campos (“ID” + tpEvento + chMDFe + nSeqEvento)'
        ],
        '629' => [
            'type' => 0,
            'message' => 'Rejeição: O tpEvento informado inválido'
        ],
        '630' => [
            'type' => 0,
            'message' => 'Rejeição: Falha no Schema XML específico para o evento'
        ],
        '631' => [
            'type' => 0,
            'message' => 'Rejeição: Duplicidade de evento [nProt:999999999999999][dhRegEvento: AAAA-MM-DDTHH:MM:SS TZD]'
        ],
        '632' => [
            'type' => 0,
            'message' => 'Rejeição: O autor do evento diverge do emissor do MDF-e'
        ],
        '633' => [
            'type' => 0,
            'message' => 'Rejeição: O autor do evento não é um órgão autorizado a gerar o evento'
        ],
        '634' => [
            'type' => 0,
            'message' => 'Rejeição: A data do evento não pode ser menor que a data de emissão do MDF-e'
        ],
        '635' => [
            'type' => 0,
            'message' => 'Rejeição: A data do evento não pode ser maior que a data do processamento'
        ],
        '636' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: O número sequencial do evento é maior que o permitido'
        ],
        '637' => [
            'type' => 0,
            'message' => 'Rejeição: A data do evento não pode ser menor que a data de autorização do MDF-e'
        ],
        '638' => [
            'type' => 0,
            'message' => 'Rejeição: Não deve ser informada Nota Fiscal para tipo de emitente Prestador Serviço de Transporte'
        ],
        '639' => [
            'type' => 0,
            'message' => 'Rejeição: Não deve ser informado Conhecimento de Transporte Eletrônico para tipo de emitente Transporte de Carga Própria.'
        ],
        '644' => [
            'type' => 0,
            'message' => 'Rejeição: Evento de inclusão de condutor só pode ser registrado para o modal rodoviário'
        ],
        '645' => [
            'type' => 0,
            'message' => 'Rejeição: CPF do condutor inválido'
        ],
        '646' => [
            'type' => 0,
            'message' => 'Rejeição: Placa de veículo formato inválido (UF Carregamento e Descarregamento <> ‘EX’)'
        ],
        '647' => [
            'type' => 0,
            'message' => 'Rejeição: MDF-e só pode ser referenciado por manifesto do modal aquaviário'
        ],
        '648' => [
            'type' => 0,
            'message' => 'Rejeição: MDF-e só pode ser referenciado quando UF de Carregamento/Descarregamento for igual a AM ou AP'
        ],
        '649' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de MDF-e informada inválida [chMDFe: 99999999999999999999999999999999999999999999][Motivo: XXXXXXXXXXXX]'
        ],
        '655' => [
            'type' => 0,
            'message' => 'Rejeição: MDF-e referenciado não existe na base de dados da SEFAZ'
        ],
        '656' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso do MDF-e referenciado difere da existente em BD'
        ],
        '657' => [
            'type' => 0,
            'message' => 'Rejeição: MDF-e referenciado já está cancelado na base de dados da SEFAZ'
        ],
        '658' => [
            'type' => 0,
            'message' => 'Rejeição: Modal do MDF-e referenciado diferente de Rodoviário'
        ],
        '659' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo do Emitente do MDF-e referenciado difere de Transportador de Carga Própria'
        ],
        '660' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ autorizado para download inválido'
        ],
        '661' => [
            'type' => 0,
            'message' => 'Rejeição: CPF autorizado para download inválido'
        ],
        '662' => [
            'type' => 0,
            'message' => 'Rejeição: Existe MDF-e não encerrado para esta placa, tipo de emitente no sentido oposto da viagem'
        ],
        '663' => [
            'type' => 0,
            'message' => 'Rejeição: Percurso informado inválido'
        ],
        '666' => [
            'type' => 0,
            'message' => 'Rejeição: Ano do MDF-e informado na chave de acesso inválido'
        ],
        '667' => [
            'type' => 0,
            'message' => 'Rejeição: Quantidade informada no grupo de totalizadores não confere com a quantidade de documentos relacionada'
        ],
        '668' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso de CT-e duplicada [chCTe: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX]'
        ],
        '669' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso de NF-e duplicada [chNFe: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX]'
        ],
        '671' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e informado não existe na base de dados da SEFAZ [chCTe: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX]'
        ],
        '672' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e informado com diferença de chave de acesso [chCTe: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX]'
        ],
        '673' => [
            'type' => 0,
            'message' => 'Rejeição: CT-e informado não pode estar cancelado/denegado na base da SEFAZ [chCTe: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX]'
        ],
        '675' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e informada não existe na base de dados da SEFAZ [chNFe: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX]'
        ],
        '676' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e informada com diferença de chave de acesso [chNFe: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX]'
        ],
        '677' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e informada não pode estar cancelada/denegada na base da SEFAZ [chNFe: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX]'
        ],
        '678' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Uso Indevido'
        ],
        '680' => [
            'type' => 0,
            'message' => 'Rejeição: Município de descarregamento duplicado no MDF-e'
        ],
        '681' => [
            'type' => 0,
            'message' => 'Rejeição: RNTRC informado inexistente.'
        ],
        '682' => [
            'type' => 0,
            'message' => 'Rejeição: RNTRC situação inválida.'
        ],
        '683' => [
            'type' => 0,
            'message' => 'Rejeição: Placa do veículo de tração não vinculada ao RNTRC informado.'
        ],
        '684' => [
            'type' => 0,
            'message' => 'Rejeição: CIOT obrigatório para RNTRC informado.'
        ],
        '685' => [
            'type' => 0,
            'message' => 'Rejeição: Município de carregamento duplicado no MDF-e'
        ],
        '686' => [
            'type' => 0,
            'message' => 'Rejeição: Existe MDF-e não encerrado há mais de 30 dias para o emitente [chMDFe: 99999999999999999999999999999999999999999999][nProt:999999999999999]'
        ],
        '687' => [
            'type' => 0,
            'message' => 'Rejeição: RNTRC deve estar associado ao transportador indicado'
        ],
        '688' => [
            'type' => 0,
            'message' => 'Rejeição: RNTRC deve ser informado para Prestador de Serviço de Transporte'
        ],
        '689' => [
            'type' => 0,
            'message' => 'Rejeição: Município de encerramento deve ser 9999999 para encerramento no exterior'
        ],
        '698' => [
            'type' => 0,
            'message' => 'Rejeição: Seguro da carga é obrigatório para modal Prestador de Serviço de Transporte no modal rodoviário'
        ],
        '699' => [
            'type' => 0,
            'message' => 'Rejeição: Dados do seguro de carga incompletos para o modal rodoviário'
        ],
        '700' => [
            'type' => 0,
            'message' => 'Rejeição: CPF do autor do evento inválido'
        ],
        '701' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo de evento incompatível com emitente pessoa física'
        ],
        '702' => [
            'type' => 0,
            'message' => 'Rejeição: Modal inválido para entrega parcial (apenas modal aéreo)'
        ],
        '703' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Carregamento e Descarregamento inválidos para MDF-e com indicação de carregamento posterior'
        ],
        '704' => [
            'type' => 0,
            'message' => 'Rejeição: MDF-e com indicação de carregamento posterior não permitido para operações interestaduais ou com o exterior'
        ],
        '705' => [
            'type' => 0,
            'message' => 'Rejeição: Modal inválido para MDF-e com indicação de carregamento posterior (apenas modal rodoviário)'
        ],
        '706' => [
            'type' => 0,
            'message' => 'Rejeição: Não informar documentos transportados para MDF-e com indicação de carregamento posterior (usar evento inclusão de DF-e)'
        ],
        '707' => [
            'type' => 0,
            'message' => 'Rejeição: MDF-e com indicação de carregamento posterior com tipo de emitente diferente de transporte próprio'
        ],
        '708' => [ // Verified
            'type' => 7,
            'message' => 'Rejeição: MDF-e deve possui indicação de carregamento posterior para inclusão de DF-e'
        ],
        '709' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de acesso de NF-e inválida no evento de inclusão [Motivo: CNPJ/CPF inválido / Modelo diferente de 55 / Ano inválido (< 2006) / Mês inválido (0 ou > 12) / Tipo de emissão inválido / UF inválida / Número zerado / DV inválido]'
        ],
        '710' => [
            'type' => 0,
            'message' => 'Rejeição: Cancelamento não é permitido para MDF-e com indicação de carregamento posterior que já realizou inserção de DF-e'
        ],
        '711' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e já está vinculada ao MDF-e por outro evento'
        ],
        '712' => [
            'type' => 0,
            'message' => 'Rejeição: Existe MDF-e com indicação de carregamento posterior sem inclusão de DF-e para o emitente há mais de 168 horas'
        ],
        '713' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do desenvolvedor do sistema inválido (zerado ou dígito inválido)'
        ],
        '714' => [
            'type' => 0,
            'message' => 'Rejeição: Município de encerramento inexistente'
        ],
        '715' => [
            'type' => 0,
            'message' => 'Rejeição: Não é permitido encerrar MDF-e com indicação de carregamento posterior sem inclusão de DF-e associada'
        ],
        '716' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ / CPF do responsável pela geração do CIOT inválido'
        ],
        '717' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ / CPF do contratante do transporte inválido'
        ],
        '718' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ / CPF do proprietário do veículo de tração inválido'
        ],
        '719' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ / CPF do proprietário do veículo reboque inválido'
        ],
        '720' => [
            'type' => 0,
            'message' => 'Rejeição: Obrigatória as informações do responsável técnico'
        ],
        '721' => [
            'type' => 0,
            'message' => 'Rejeição: Obrigatória a informação do identificador do CSRT e do Hash do CSRT'
        ],
        '730' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: NSU solicitado muito antigo [NSUMin: 999999999999999]'
        ],
        '997' => [
            'type' => 0,
            'message' => 'Rejeição: XML do MDF-e referenciado indisponível no momento da validação (Existem situações em que o ambiente de autorização trabalha com um banco de dados separado para o arquivo XML)'
        ],
        '999' => [
            'type' => 0,
            'message' => 'Rejeição: Erro não catalogado (informar a msg de erro capturado no tratamento da exceção)'
        ]
    ];
}

