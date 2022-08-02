<?php

declare(strict_types=1);

namespace Inspire\Dfe\Parser;

use Inspire\Support\Xml\Xml;
use Inspire\Support\{
    Arrays,
    Strings
};

/**
 * Description of Nfe
 *
 * @author aalves
 */
class Nfe extends Base
{

    /**
     * Parse SEFAZ response of NfeStatusServico
     *
     * @param Xml $xml
     * @return array
     */
    public static function NfeStatusServico(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retConsStatServ');
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
    public static function RecepcaoEvento(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retEnvEvento.retEvento');
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
     * Parse response Distribuição documentos e informações de interesse do ator do NF-e
     * Fully implemented
     *
     * @param Xml $xml
     * @return array
     */
    public static function NFeDistribuicaoDFe(Xml $xml): array
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
        if ($aData['bStat'] && !empty($aData['loteDistDFeInt'])) {
            $loteDistDFeInt = [];
            /**
             * Normaliza package case there is a single document
             */
            if (!isset($aData['loteDistDFeInt']['docZip'][0])) {
                $aData['loteDistDFeInt']['docZip'] = [
                    $aData['loteDistDFeInt']['docZip']
                ];
            }
            $docs = $aData['loteDistDFeInt']['docZip'];
            foreach ($docs as $doc) {
                $schema = strtok(Arrays::get($doc, '@attributes.schema'), '_');
                if ((self::$unpack !== null && Arrays::keyCheck($schema, self::$unpack)) || // Some type of package is set and actual package is one of that
                    self::$unpack === null
                ) {
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
        '100' => [
            'type' => 0,
            'message' => 'Autorizado o uso da NF-e'
        ],
        '101' => [
            'type' => 0,
            'message' => 'Cancelamento de NF-e homologado'
        ],
        '102' => [
            'type' => 0,
            'message' => 'Inutilização de número homologado'
        ],
        '103' => [
            'type' => 0,
            'message' => 'Lote recebido com sucesso'
        ],
        '104' => [
            'type' => 0,
            'message' => 'Lote processado'
        ],
        '105' => [
            'type' => 0,
            'message' => 'Lote em processamento'
        ],
        '106' => [
            'type' => 0,
            'message' => 'Lote não localizado'
        ],
        '107' => [
            'type' => 0,
            'message' => 'Serviço em Operação'
        ],
        '108' => [
            'type' => 0,
            'message' => 'Serviço Paralisado Momentaneamente (curto prazo)'
        ],
        '109' => [
            'type' => 0,
            'message' => 'Serviço Paralisado sem Previsão'
        ],
        '110' => [
            'type' => 0,
            'message' => 'Uso Denegado'
        ],
        '111' => [
            'type' => 0,
            'message' => 'Consulta cadastro com uma ocorrência'
        ],
        '112' => [
            'type' => 0,
            'message' => 'Consulta cadastro com mais de uma ocorrência'
        ],
        '124' => [
            'type' => 0,
            'message' => 'EPEC Autorizado'
        ],
        '128' => [
            'type' => 0,
            'message' => 'Lote de Evento Processado'
        ],
        '135' => [
            'type' => 0,
            'message' => 'Evento registrado e vinculado a NF-e'
        ],
        '136' => [
            'type' => 0,
            'message' => 'Evento registrado, mas não vinculado a NF-e'
        ],
        '137' => [
            'type' => 0,
            'message' => 'Nenhum documento localizado para o Destinatário'
        ],
        '138' => [ //Verified
            'type' => 1,
            'message' => 'Documento localizado para o Destinatário'
        ],
        '139' => [
            'type' => 0,
            'message' => 'Pedido de Download processado'
        ],
        '140' => [
            'type' => 0,
            'message' => 'Download disponibilizado'
        ],
        '142' => [
            'type' => 0,
            'message' => 'Ambiente de Contingência EPEC bloqueado para o Emitente'
        ],
        '150' => [
            'type' => 0,
            'message' => 'Autorizado o uso da NF-e, autorização fora de prazo'
        ],
        '151' => [
            'type' => 0,
            'message' => 'Cancelamento de NF-e homologado fora de prazo'
        ],
        '201' => [
            'type' => 0,
            'message' => 'Rejeição: Número máximo de numeração a inutilizar ultrapassou o limite'
        ],
        '202' => [
            'type' => 0,
            'message' => 'Rejeição: Falha no reconhecimento da autoria ou integridade do arquivo digital'
        ],
        '203' => [
            'type' => 0,
            'message' => 'Rejeição: Emissor não habilitado para emissão de NF-e'
        ],
        '204' => [ // Verified
            'type' => 7,
            'message' => 'Duplicidade de NF-e [nRec:999999999999999]'
        ],
        '205' => [
            'type' => 0,
            'message' => 'NF-e está denegada na base de dados da SEFAZ [nRec:999999999999999]'
        ],
        '206' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e já está inutilizada na Base de dados da SEFAZ'
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
            'message' => 'Rejeição: Data de emissão NF-e posterior a data de recebimento'
        ],
        '213' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ-Base do Emitente difere do CNPJ-Base do Certificado Digital'
        ],
        '214' => [
            'type' => 0,
            'message' => 'Rejeição: Tamanho da mensagem excedeu o limite estabelecido'
        ],
        '215' => [
            'type' => 0,
            'message' => 'Rejeição: Falha no schema XML'
        ],
        '216' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso difere da cadastrada'
        ],
        '217' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e não consta na base de dados da SEFAZ'
        ],
        '218' => [
            'type' => 0,
            'message' => 'NF-e já está cancelada na base de dados da SEFAZ [nRec:999999999999999]'
        ],
        '219' => [
            'type' => 0,
            'message' => 'Rejeição: Circulação da NF-e verificada'
        ],
        '220' => [
            'type' => 0,
            'message' => 'Rejeição: Prazo de Cancelamento superior ao previsto na Legislação'
        ],
        '221' => [
            'type' => 0,
            'message' => 'Rejeição: Confirmado o recebimento da NF-e pelo destinatário'
        ],
        '222' => [
            'type' => 0,
            'message' => 'Rejeição: Protocolo de Autorização de Uso difere do cadastrado'
        ],
        '223' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do transmissor do lote difere do CNPJ do transmissor da consulta'
        ],
        '224' => [
            'type' => 0,
            'message' => 'Rejeição: A faixa inicial é maior que a faixa final'
        ],
        '225' => [
            'type' => 0,
            'message' => 'Rejeição: Falha no Schema XML do lote de NFe'
        ],
        '226' => [
            'type' => 0,
            'message' => 'Rejeição: Código da UF do Emitente diverge da UF autorizadora'
        ],
        '227' => [
            'type' => 0,
            'message' => 'Rejeição: Erro na Chave de Acesso - Campo Id - falta a literal NFe'
        ],
        '228' => [
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
        '234' => [
            'type' => 0,
            'message' => 'Rejeição: IE do destinatário não vinculada ao CNPJ'
        ],
        '235' => [
            'type' => 0,
            'message' => 'Rejeição: Inscrição SUFRAMA inválida'
        ],
        '236' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso com dígito verificador inválido'
        ],
        '237' => [
            'type' => 0,
            'message' => 'Rejeição: CPF do destinatário inválido'
        ],
        '238' => [
            'type' => 0,
            'message' => 'Rejeição: Cabeçalho - Versão do arquivo XML superior a Versão vigente'
        ],
        '239' => [
            'type' => 0,
            'message' => 'Rejeição: Cabeçalho - Versão do arquivo XML não suportada'
        ],
        '240' => [
            'type' => 0,
            'message' => 'Rejeição: Cancelamento/Inutilização - Irregularidade Fiscal do Emitente'
        ],
        '241' => [
            'type' => 0,
            'message' => 'Rejeição: Um número da faixa já foi utilizado'
        ],
        '242' => [
            'type' => 0,
            'message' => 'Rejeição: Cabeçalho - Falha no Schema XML'
        ],
        '243' => [
            'type' => 0,
            'message' => 'Rejeição: XML Mal Formado'
        ],
        '244' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do Certificado Digital difere do CNPJ da Matriz e do CNPJ do Emitente'
        ],
        '245' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ Emitente não cadastrado'
        ],
        '246' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ Destinatário não cadastrado'
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
        '250' => [
            'type' => 0,
            'message' => 'Rejeição: UF diverge da UF autorizadora'
        ],
        '251' => [
            'type' => 0,
            'message' => 'Rejeição: UF/Município destinatário não pertence a SUFRAMA'
        ],
        '252' => [
            'type' => 0,
            'message' => 'Rejeição: Ambiente informado diverge do Ambiente de recebimento'
        ],
        '253' => [
            'type' => 0,
            'message' => 'Rejeição: Digito Verificador da chave de acesso composta inválida'
        ],
        '254' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e complementar não possui NF referenciada'
        ],
        '255' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e complementar possui mais de uma NF referenciada'
        ],
        '256' => [
            'type' => 0,
            'message' => 'Rejeição: Uma NF-e da faixa já está inutilizada na Base de dados da SEFAZ'
        ],
        '257' => [
            'type' => 0,
            'message' => 'Rejeição: Solicitante não habilitado para emissão da NF-e'
        ],
        '258' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ da consulta inválido'
        ],
        '259' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ da consulta não cadastrado como contribuinte na UF'
        ],
        '260' => [
            'type' => 0,
            'message' => 'Rejeição: IE da consulta inválida'
        ],
        '261' => [
            'type' => 0,
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
            'message' => 'Rejeição: NF Complementar referencia uma NF-e inexistente'
        ],
        '268' => [
            'type' => 0,
            'message' => 'Rejeição: NF Complementar referencia outra NF-e Complementar'
        ],
        '269' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ Emitente da NF Complementar difere do CNPJ da NF Referenciada'
        ],
        '270' => [
            'type' => 0,
            'message' => 'Rejeição: Código Município do Fato Gerador: dígito inválido'
        ],
        '271' => [
            'type' => 0,
            'message' => 'Rejeição: Código Município do Fato Gerador: difere da UF do emitente'
        ],
        '272' => [
            'type' => 0,
            'message' => 'Rejeição: Código Município do Emitente: dígito inválido'
        ],
        '273' => [
            'type' => 0,
            'message' => 'Rejeição: Código Município do Emitente: difere da UF do emitente'
        ],
        '274' => [
            'type' => 0,
            'message' => 'Rejeição: Código Município do Destinatário: dígito inválido'
        ],
        '275' => [
            'type' => 0,
            'message' => 'Rejeição: Código Município do Destinatário: difere da UF do Destinatário'
        ],
        '276' => [
            'type' => 0,
            'message' => 'Rejeição: Código Município do Local de Retirada: dígito inválido'
        ],
        '277' => [
            'type' => 0,
            'message' => 'Rejeição: Código Município do Local de Retirada: difere da UF do Local de Retirada'
        ],
        '278' => [
            'type' => 0,
            'message' => 'Rejeição: Código Município do Local de Entrega: dígito inválido'
        ],
        '279' => [
            'type' => 0,
            'message' => 'Rejeição: Código Município do Local de Entrega: difere da UF do Local de Entrega'
        ],
        '280' => [
            'type' => 0,
            'message' => 'Rejeição: Certificado Transmissor inválido'
        ],
        '281' => [
            'type' => 0,
            'message' => 'Rejeição: Certificado Transmissor Data Validade'
        ],
        '282' => [
            'type' => 0,
            'message' => 'Rejeição: Certificado Transmissor sem CNPJ'
        ],
        '283' => [
            'type' => 0,
            'message' => 'Rejeição: Certificado Transmissor - erro Cadeia de Certificação'
        ],
        '284' => [
            'type' => 0,
            'message' => 'Rejeição: Certificado Transmissor revogado'
        ],
        '285' => [
            'type' => 0,
            'message' => 'Rejeição: Certificado Transmissor difere ICP-Brasil'
        ],
        '286' => [
            'type' => 0,
            'message' => 'Rejeição: Certificado Transmissor erro no acesso a LCR'
        ],
        '287' => [
            'type' => 0,
            'message' => 'Rejeição: Código Município do FG - ISSQN: dígito inválido'
        ],
        '288' => [
            'type' => 0,
            'message' => 'Rejeição: Código Município do FG - Transporte: dígito inválido'
        ],
        '289' => [
            'type' => 0,
            'message' => 'Rejeição: Código da UF informada diverge da UF solicitada'
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
            'message' => 'Rejeição: Certificado Assinatura sem CNPJ'
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
            'message' => 'Rejeição: Assinatura difere do padrão do Sistema'
        ],
        '299' => [
            'type' => 0,
            'message' => 'Rejeição: XML da área de cabeçalho com codificação diferente de UTF-8'
        ],
        '301' => [
            'type' => 0,
            'message' => 'Uso Denegado: Irregularidade fiscal do emitente'
        ],
        '302' => [
            'type' => 0,
            'message' => 'Rejeição: Irregularidade fiscal do destinatário'
        ],
        '303' => [
            'type' => 0,
            'message' => 'Uso Denegado: Destinatário não habilitado a operar na UF'
        ],
        '304' => [
            'type' => 0,
            'message' => 'Rejeição: Pedido de Cancelamento para NF-e com evento da Suframa'
        ],
        '321' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e de devolução de mercadoria não possui documento fiscal referenciado'
        ],
        '323' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ autorizado para download inválido'
        ],
        '324' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do destinatário já autorizado para download'
        ],
        '325' => [
            'type' => 0,
            'message' => 'Rejeição: CPF autorizado para download inválido'
        ],
        '326' => [
            'type' => 0,
            'message' => 'Rejeição: CPF do destinatário já autorizado para download'
        ],
        '327' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP inválido para NF-e com finalidade de devolução de mercadoria'
        ],
        '328' => [
            'type' => 0,
            'message' => '329 Rejeição: CFOP de devolução de mercadoria para NF-e que não tem finalidade de devolução de mercadoria Rejeição: Número da DI /DSI inválido'
        ],
        '330' => [
            'type' => 0,
            'message' => 'Rejeição: Informar o Valor da AFRMM na importação por via marítima'
        ],
        '331' => [
            'type' => 0,
            'message' => 'Rejeição: Informar o CNPJ do adquirente ou do encomendante nesta forma de importação'
        ],
        '332' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do adquirente ou do encomendante da importação inválido'
        ],
        '333' => [
            'type' => 0,
            'message' => 'Rejeição: Informar a UF do adquirente ou do encomendante nesta forma de importação'
        ],
        '334' => [
            'type' => 0,
            'message' => 'Rejeição: Número do processo de drawback não informado na importação'
        ],
        '335' => [
            'type' => 0,
            'message' => 'Rejeição: Número do processo de drawback na importação inválido'
        ],
        '336' => [
            'type' => 0,
            'message' => 'Rejeição: Informado o grupo de exportação no item para CFOP que não é de exportação'
        ],
        '337' => [
            'type' => 0,
            'message' => 'Rejeição: Não informado o grupo de exportação no item'
        ],
        '338' => [
            'type' => 0,
            'message' => 'Rejeição: Número do processo de drawback não informado na exportação'
        ],
        '339' => [
            'type' => 0,
            'message' => 'Rejeição: Número do processo de drawback na exportação inválido'
        ],
        '340' => [
            'type' => 0,
            'message' => 'Rejeição: Não informado o grupo de exportação indireta no item'
        ],
        '341' => [
            'type' => 0,
            'message' => 'Rejeição: Número do registro de exportação inválido'
        ],
        '342' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso informada na Exportação Indireta com DV inválido'
        ],
        '343' => [
            'type' => 0,
            'message' => 'Rejeição: Modelo da NF-e informada na Exportação Indireta diferente de 55'
        ],
        '344' => [
            'type' => 0,
            'message' => 'Rejeição: Duplicidade de NF-e informada na Exportação Indireta (Chave de Acesso informada mais de uma vez) Rejeição: Chave de Acesso informada na Exportação Indireta não consta como NF-e referenciada'
        ],
        '346' => [
            'type' => 0,
            'message' => 'Rejeição: Somatório das quantidades informadas na Exportação Indireta não corresponde a quantidade total do item'
        ],
        '347' => [
            'type' => 0,
            'message' => 'Rejeição: Descrição do Combustível diverge da descrição adotada pela ANP'
        ],
        '348' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com grupo RECOPI'
        ],
        '349' => [
            'type' => 0,
            'message' => 'Rejeição: Número RECOPI não informado'
        ],
        '350' => [
            'type' => 0,
            'message' => 'Rejeição: Número RECOPI inválido'
        ],
        '351' => [
            'type' => 0,
            'message' => 'Rejeição: Valor do ICMS da Operação no CST=51 difere do produto BC e Alíquota'
        ],
        '352' => [
            'type' => 0,
            'message' => 'Rejeição: Valor do ICMS Diferido no CST=51 difere do produto Valor ICMS Operação e percentual diferimento'
        ],
        '355' => [
            'type' => 0,
            'message' => 'Rejeição: Valor do ICMS no CST=51 não corresponde a diferença do ICMS operação e ICMS diferido'
        ],
        '354' => [
            'type' => 0,
            'message' => 'Rejeição: Informado grupo de devolução de tributos para NF-e que não tem finalidade de devolução de mercadoria'
        ],
        '355' => [
            'type' => 0,
            'message' => 'Rejeição: Informar o local de saída do Pais no caso da exportação'
        ],
        '356' => [
            'type' => 0,
            'message' => 'Rejeição: Informar o local de saída do Pais somente no caso da exportação'
        ],
        '357' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso do grupo de Exportação Indireta inexistente [nRef: xxx]'
        ],
        '358' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso do grupo de Exportação Indireta cancelada ou denegada [nRef: xxx]'
        ],
        '359' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e de venda a Órgão Público sem informar a Nota de Empenho'
        ],
        '360' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e com Nota de Empenho inválida para a UF.'
        ],
        '361' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e com Nota de Empenho inexistente na UF.'
        ],
        '362' => [
            'type' => 0,
            'message' => 'Rejeição: Venda de combustível sem informação do Transportador'
        ],
        '364' => [
            'type' => 0,
            'message' => 'Rejeição: Total do valor da dedução do ISS difere do somatório dos itens'
        ],
        '365' => [
            'type' => 0,
            'message' => 'Rejeição: Total de outras retenções difere do somatório dos itens'
        ],
        '366' => [
            'type' => 0,
            'message' => 'Rejeição: Total do desconto incondicionado ISS difere do somatório dos itens'
        ],
        '367' => [
            'type' => 0,
            'message' => 'Rejeição: Total do desconto condicionado ISS difere do somatório dos itens'
        ],
        '368' => [
            'type' => 0,
            'message' => 'Rejeição: Total de ISS retido difere do somatório dos itens'
        ],
        '369' => [
            'type' => 0,
            'message' => 'Rejeição: Não informado o grupo avulsa na emissão pelo Fisco'
        ],
        '370' => [
            'type' => 0,
            'message' => 'Rejeição: Nota Fiscal Avulsa com tipo de emissão inválido'
        ],
        '401' => [
            'type' => 0,
            'message' => 'Rejeição: CPF do remetente inválido'
        ],
        '402' => [
            'type' => 0,
            'message' => 'Rejeição: XML da área de dados com codificação diferente de UTF-8'
        ],
        '403' => [
            'type' => 0,
            'message' => 'Rejeição: O grupo de informações da NF-e avulsa é de uso exclusivo do Fisco'
        ],
        '404' => [
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
            'message' => 'Rejeição: O CPF só pode ser informado no campo emitente para a NF-e avulsa'
        ],
        '408' => [
            'type' => 0,
            'message' => 'Rejeição: Evento não disponível para Autor pessoa física'
        ],
        '409' => [
            'type' => 0,
            'message' => 'Rejeição: Campo cUF inexistente no elemento nfeCabecMsg do SOAP Header'
        ],
        '410' => [
            'type' => 0,
            'message' => 'Rejeição: UF informada no campo cUF não é atendida pelo Web Service'
        ],
        '411' => [
            'type' => 0,
            'message' => 'Rejeição: Campo versaoDados inexistente no elemento nfeCabecMsg do SOAP Header'
        ],
        '416' => [
            'type' => 0,
            'message' => 'Rejeição: Falha na descompactação da área de dados'
        ],
        '417' => [
            'type' => 0,
            'message' => 'Rejeição: Total do ICMS superior ao valor limite estabelecido'
        ],
        '418' => [
            'type' => 0,
            'message' => 'Rejeição: Total do ICMS ST superior ao valor limite estabelecido'
        ],
        '420' => [
            'type' => 0,
            'message' => 'Rejeição: Cancelamento para NF-e já cancelada'
        ],
        '450' => [
            'type' => 0,
            'message' => 'Rejeição: Modelo da NF-e diferente de 55'
        ],
        '451' => [
            'type' => 0,
            'message' => 'Rejeição: Processo de emissão informado inválido'
        ],
        '452' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo Autorizador do Recibo diverge do Órgão Autorizador'
        ],
        '453' => [
            'type' => 0,
            'message' => 'Rejeição: Ano de inutilização não pode ser superior ao Ano atual'
        ],
        '454' => [
            'type' => 0,
            'message' => 'Rejeição: Ano de inutilização não pode ser inferior a 2006'
        ],
        '455' => [
            'type' => 0,
            'message' => 'Rejeição: Órgão Autor do evento diferente da UF da Chave de Acesso'
        ],
        '461' => [
            'type' => 0,
            'message' => 'Rejeição: Informado percentual de Gás Natural na mistura para produto diferente de GLP'
        ],
        '465' => [
            'type' => 0,
            'message' => 'Rejeição: Número de Controle da FCI inexistente'
        ],
        '466' => [
            'type' => 0,
            'message' => 'Rejeição: Evento com Tipo de Autor incompatível'
        ],
        '467' => [
            'type' => 0,
            'message' => 'Rejeição: Dados da NF-e divergentes do EPEC'
        ],
        '468' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e com Tipo Emissão = 4, sem EPEC correspondente'
        ],
        '471' => [
            'type' => 0,
            'message' => 'Rejeição: Informado NCM=00 indevidamente'
        ],
        '476' => [
            'type' => 0,
            'message' => 'Rejeição: Código da UF diverge da UF da primeira NF-e do Lote'
        ],
        '477' => [
            'type' => 0,
            'message' => 'Rejeição: Código do órgão diverge do órgão do primeiro evento do Lote'
        ],
        '478' => [
            'type' => 0,
            'message' => 'Rejeição: Local da entrega não informado para faturamento direto de veículos novos'
        ],
        '484' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso com tipo de emissão diferente de 4 (posição 35 da Chave de Acesso)'
        ],
        '485' => [
            'type' => 0,
            'message' => 'Rejeição: Duplicidade de numeração do EPEC (Modelo, CNPJ, Série e Número)'
        ],
        '489' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ informado inválido (DV ou zeros)'
        ],
        '490' => [
            'type' => 0,
            'message' => 'Rejeição: CPF informado inválido (DV ou zeros)'
        ],
        '491' => [
            'type' => 0,
            'message' => 'Rejeição: O tpEvento informado inválido'
        ],
        '492' => [
            'type' => 0,
            'message' => 'Rejeição: O verEvento informado inválido'
        ],
        '493' => [
            'type' => 0,
            'message' => 'Rejeição: Evento não atende o Schema XML específico'
        ],
        '494' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso inexistente'
        ],
        '501' => [
            'type' => 0,
            'message' => 'Rejeição: Pedido de Cancelamento intempestivo (NF-e autorizada a mais de 7 dias)'
        ],
        '502' => [
            'type' => 0,
            'message' => 'Rejeição: Erro na Chave de Acesso - Campo Id não corresponde à concatenação dos campos correspondentes'
        ],
        '503' => [
            'type' => 0,
            'message' => 'Rejeição: Série utilizada fora da faixa permitida no SCAN (900-999)'
        ],
        '504' => [
            'type' => 0,
            'message' => 'Rejeição: Data de Entrada/Saída posterior ao permitido'
        ],
        '505' => [
            'type' => 0,
            'message' => 'Rejeição: Data de Entrada/Saída anterior ao permitido'
        ],
        '506' => [
            'type' => 0,
            'message' => 'Rejeição: Data de Saída menor que a Data de Emissão'
        ],
        '507' => [
            'type' => 0,
            'message' => 'Rejeição: O CNPJ do destinatário/remetente não deve ser informado em operação com o exterior'
        ],
        '508' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do destinatário com conteúdo nulo só é válido em operação com exterior'
        ],
        '509' => [
            'type' => 0,
            'message' => 'Rejeição: Informado código de município diferente de “9999999” para operação com o exterior'
        ],
        '510' => [
            'type' => 0,
            'message' => 'Rejeição: Operação com Exterior e Código País destinatário é 1058 (Brasil) ou não informado'
        ],
        '511' => [
            'type' => 0,
            'message' => 'Rejeição: Não é de Operação com Exterior e Código País destinatário difere de 1058 (Brasil)'
        ],
        '512' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do Local de Retirada inválido'
        ],
        '513' => [
            'type' => 0,
            'message' => 'Rejeição: Código Município do Local de Retirada deve ser 9999999 para UF retirada = EX'
        ],
        '514' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do Local de Entrega inválido'
        ],
        '515' => [
            'type' => 0,
            'message' => 'Rejeição: Código Município do Local de Entrega deve ser 9999999 para UF entrega = EX'
        ],
        '516' => [
            'type' => 0,
            'message' => 'Rejeição: Falha no schema XML - inexiste a tag raiz esperada para a mensagem'
        ],
        '517' => [
            'type' => 0,
            'message' => 'Rejeição: Falha no schema XML - inexiste atributo versao na tag raiz da mensagem'
        ],
        '518' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP de entrada para NF-e de saída'
        ],
        '519' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP de saída para NF-e de entrada'
        ],
        '520' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP de Operação com Exterior e UF destinatário difere de EX'
        ],
        '521' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP de Operação Estadual e UF do emitente difere da UF do destinatário para destinatário contribuinte do ICMS.'
        ],
        '522' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP de Operação Estadual e UF emitente difere da UF remetente para remetente contribuinte do ICMS.'
        ],
        '523' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP não é de Operação Estadual e UF emitente igual a UF destinatário.'
        ],
        '524' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP de Operação com Exterior e não informado NCM'
        ],
        '525' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP de Importação e não informado dados da DI'
        ],
        '527' => [
            'type' => 0,
            'message' => 'Rejeição: Operação de Exportação com informação de ICMS incompatível'
        ],
        '528' => [
            'type' => 0,
            'message' => 'Rejeição: Valor do ICMS difere do produto BC e Alíquota'
        ],
        '529' => [
            'type' => 0,
            'message' => 'Rejeição: NCM de informação obrigatória para produto tributado pelo IPI'
        ],
        '530' => [
            'type' => 0,
            'message' => 'Rejeição: Operação com tributação de ISSQN sem informar a Inscrição Municipal'
        ],
        '531' => [
            'type' => 0,
            'message' => 'Rejeição: Total da BC ICMS difere do somatório dos itens'
        ],
        '532' => [
            'type' => 0,
            'message' => 'Rejeição: Total do ICMS difere do somatório dos itens'
        ],
        '533' => [
            'type' => 0,
            'message' => 'Rejeição: Total da BC ICMS-ST difere do somatório dos itens'
        ],
        '534' => [
            'type' => 0,
            'message' => 'Rejeição: Total do ICMS-ST difere do somatório dos itens'
        ],
        '535' => [
            'type' => 0,
            'message' => 'Rejeição: Total do Frete difere do somatório dos itens'
        ],
        '536' => [
            'type' => 0,
            'message' => 'Rejeição: Total do Seguro difere do somatório dos itens'
        ],
        '537' => [
            'type' => 0,
            'message' => 'Rejeição: Total do Desconto difere do somatório dos itens'
        ],
        '538' => [
            'type' => 0,
            'message' => 'Rejeição: Total do IPI difere do somatório dos itens'
        ],
        '539' => [ // Verified
            'type' => 7,
            'message' => 'Duplicidade de NF-e com diferença na Chave de Acesso [chNFe:99999999999999999999999999999999999999999999][nRec 999999999999999]'
        ],
        '540' => [
            'type' => 0,
            'message' => 'Rejeição: CPF do Local de Retirada inválido'
        ],
        '541' => [
            'type' => 0,
            'message' => 'Rejeição: CPF do Local de Entrega inválido'
        ],
        '542' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do Transportador inválido'
        ],
        '543' => [
            'type' => 0,
            'message' => 'Rejeição: CPF do Transportador inválido'
        ],
        '544' => [
            'type' => 0,
            'message' => 'Rejeição: IE do Transportador inválida'
        ],
        '545' => [
            'type' => 0,
            'message' => 'Rejeição: Falha no schema XML - versão informada na versaoDados do SOAPHeader diverge da versão da mensagem'
        ],
        '546' => [
            'type' => 0,
            'message' => 'Rejeição: Erro na Chave de Acesso - Campo Id - falta a literal NFe'
        ],
        '547' => [
            'type' => 0,
            'message' => 'Rejeição: Dígito Verificador da Chave de Acesso da NF-e Referenciada inválido'
        ],
        '548' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ da NF referenciada inválido.'
        ],
        '549' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ da NF referenciada de produtor inválido.'
        ],
        '550' => [
            'type' => 0,
            'message' => 'Rejeição: CPF da NF referenciada de produtor inválido.'
        ],
        '551' => [
            'type' => 0,
            'message' => 'Rejeição: IE da NF referenciada de produtor inválido.'
        ],
        '552' => [
            'type' => 0,
            'message' => 'Rejeição: Dígito Verificador da Chave de Acesso do CT-e Referenciado inválido'
        ],
        '553' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo autorizador do recibo diverge do Órgão Autorizador.'
        ],
        '554' => [
            'type' => 0,
            'message' => 'Rejeição: Série difere da faixa 0-899'
        ],
        '555' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo autorizador do protocolo diverge do Órgão Autorizador.'
        ],
        '556' => [
            'type' => 0,
            'message' => 'Rejeição: Justificativa de entrada em contingência não deve ser informada para tipo de emissão normal.'
        ],
        '557' => [
            'type' => 0,
            'message' => 'Rejeição: A Justificativa de entrada em contingência deve ser informada.'
        ],
        '558' => [
            'type' => 0,
            'message' => 'Rejeição: Data de entrada em contingência posterior a data de recebimento.'
        ],
        '559' => [
            'type' => 0,
            'message' => 'Rejeição: UF do Transportador não informada'
        ],
        '560' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ base do emitente difere do CNPJ base da primeira NF-e do lote recebido'
        ],
        '561' => [
            'type' => 0,
            'message' => 'Rejeição: Mês de Emissão informado na Chave de Acesso difere do Mês de Emissão da NF-e'
        ],
        '562' => [
            'type' => 0,
            'message' => 'Rejeição: Código Numérico informado na Chave de Acesso difere do Código Numérico da NF-e [chNFe:99999999999999999999999999999999999999999999]'
        ],
        '563' => [
            'type' => 0,
            'message' => 'Rejeição: Já existe pedido de Inutilização com a mesma faixa de inutilização'
        ],
        '564' => [
            'type' => 0,
            'message' => 'Rejeição: Total do Produto / Serviço difere do somatório dos itens'
        ],
        '565' => [
            'type' => 0,
            'message' => 'Rejeição: Falha no schema XML - inexiste a tag raiz esperada para o lote de NF-e'
        ],
        '567' => [
            'type' => 0,
            'message' => 'Rejeição: Falha no schema XML - versão informada na versaoDados do SOAPHeader diverge da versão do lote de NF-e'
        ],
        '568' => [
            'type' => 0,
            'message' => 'Rejeição: Falha no schema XML - inexiste atributo versao na tag raiz do lote de NF-e'
        ],
        '569' => [
            'type' => 0,
            'message' => 'Rejeição: Data de entrada em contingência muito atrasada'
        ],
        '570' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo de Emissão 3, 6 ou 7 só é válido nas contingências SCAN/SVC'
        ],
        '571' => [
            'type' => 0,
            'message' => 'Rejeição: O tpEmis informado diferente de 3 para contingência SCAN'
        ],
        '572' => [
            'type' => 0,
            'message' => 'Rejeição: Erro Atributo ID do evento não corresponde a concatenação dos campos (“ID” + tpEvento + chNFe + nSeqEvento)'
        ],
        '573' => [ // Verified
            'type' => 7,
            'message' => 'Rejeição: Duplicidade de Evento'
        ],
        '574' => [
            'type' => 0,
            'message' => 'Rejeição: O autor do evento diverge do emissor da NF-e'
        ],
        '575' => [
            'type' => 0,
            'message' => 'Rejeição: O autor do evento diverge do destinatário da NF-e'
        ],
        '576' => [
            'type' => 0,
            'message' => 'Rejeição: O autor do evento não é um órgão autorizado a gerar o evento'
        ],
        '577' => [
            'type' => 0,
            'message' => 'Rejeição: A data do evento não pode ser menor que a data de emissão da NF-e'
        ],
        '578' => [
            'type' => 0,
            'message' => 'Rejeição: A data do evento não pode ser maior que a data do processamento'
        ],
        '579' => [
            'type' => 0,
            'message' => 'Rejeição: A data do evento não pode ser menor que a data de autorização para NF-e não emitida em contingência'
        ],
        '580' => [
            'type' => 0,
            'message' => 'Rejeição: O evento exige uma NF-e autorizada'
        ],
        '587' => [
            'type' => 0,
            'message' => 'Rejeição: Usar somente o namespace padrão da NF-e'
        ],
        '588' => [
            'type' => 0,
            'message' => 'Rejeição: Não é permitida a presença de caracteres de edição no início/fim da mensagem ou entre as tags da mensagem'
        ],
        '589' => [
            'type' => 0,
            'message' => 'Rejeição: Número do NSU informado superior ao maior NSU da base de dados da SEFAZ'
        ],
        '590' => [
            'type' => 0,
            'message' => 'Rejeição: Informado CST para emissor do Simples Nacional (CRT=1)'
        ],
        '591' => [
            'type' => 0,
            'message' => 'Rejeição: Informado CSOSN para emissor que não é do Simples Nacional (CRT diferente de 1)'
        ],
        '592' => [
            'type' => 0,
            'message' => 'Rejeição: A NF-e deve ter pelo menos um item de produto sujeito ao ICMS'
        ],
        '593' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ-Base consultado difere do CNPJ-Base do Certificado Digital'
        ],
        '594' => [
            'type' => 0,
            'message' => 'Rejeição: O número de sequencia do evento informado é maior que o permitido'
        ],
        '595' => [
            'type' => 0,
            'message' => 'Rejeição: Obrigatória a informação da justificativa do evento.'
        ],
        '596' => [
            'type' => 0,
            'message' => 'Rejeição: Evento apresentado fora do prazo: [prazo vigente]'
        ],
        '597' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP de Importação e não informado dados de IPI'
        ],
        '598' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e emitida em ambiente de homologação com Razão Social do destinatário diferente de NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL'
        ],
        '599' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP de Importação e não informado dados de II'
        ],
        '601' => [
            'type' => 0,
            'message' => 'Rejeição: Total do II difere do somatório dos itens'
        ],
        '602' => [
            'type' => 0,
            'message' => 'Rejeição: Total do PIS difere do somatório dos itens sujeitos ao ICMS'
        ],
        '603' => [
            'type' => 0,
            'message' => 'Rejeição: Total do COFINS difere do somatório dos itens sujeitos ao ICMS'
        ],
        '604' => [
            'type' => 0,
            'message' => 'Rejeição: Total do vOutro difere do somatório dos itens'
        ],
        '605' => [
            'type' => 0,
            'message' => 'Rejeição: Total do vISS difere do somatório do vProd dos itens sujeitos ao ISSQN'
        ],
        '606' => [
            'type' => 0,
            'message' => 'Rejeição: Total do vBC do ISS difere do somatório dos itens'
        ],
        '607' => [
            'type' => 0,
            'message' => 'Rejeição: Total do ISS difere do somatório dos itens'
        ],
        '608' => [
            'type' => 0,
            'message' => 'Rejeição: Total do PIS difere do somatório dos itens sujeitos ao ISSQN'
        ],
        '609' => [
            'type' => 0,
            'message' => 'Rejeição: Total do COFINS difere do somatório dos itens sujeitos ao ISSQN'
        ],
        '610' => [
            'type' => 0,
            'message' => 'Rejeição: Total da NF difere do somatório dos Valores compõe o valor Total da NF.'
        ],
        '611' => [
            'type' => 0,
            'message' => 'Rejeição: cEAN inválido'
        ],
        '612' => [
            'type' => 0,
            'message' => 'Rejeição: cEANTrib inválido'
        ],
        '613' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso difere da existente em BD'
        ],
        '614' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso inválida (Código UF inválido)'
        ],
        '615' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso inválida (Ano menor que 06 ou Ano maior que Ano corrente)'
        ],
        '616' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso inválida (Mês menor que 1 ou Mês maior que 12)'
        ],
        '617' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso inválida (CNPJ zerado ou dígito inválido)'
        ],
        '618' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso inválida (modelo diferente de 55 e 65)'
        ],
        '619' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso inválida (número NF = 0)'
        ],
        '620' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso difere da existente em BD'
        ],
        '621' => [
            'type' => 0,
            'message' => 'Rejeição: CPF Emitente não cadastrado'
        ],
        '622' => [
            'type' => 0,
            'message' => 'Rejeição: IE emitente não vinculada ao CPF'
        ],
        '623' => [
            'type' => 0,
            'message' => 'Rejeição: CPF Destinatário não cadastrado'
        ],
        '624' => [
            'type' => 0,
            'message' => 'Rejeição: IE Destinatário não vinculada ao CPF'
        ],
        '625' => [
            'type' => 0,
            'message' => 'Rejeição: Inscrição SUFRAMA deve ser informada na venda com isenção para ZFM'
        ],
        '626' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP de operação isenta para ZFM diferente do previsto'
        ],
        '627' => [
            'type' => 0,
            'message' => 'Rejeição: O valor do ICMS desonerado deve ser informado'
        ],
        '628' => [
            'type' => 0,
            'message' => 'Rejeição: Total da NF superior ao valor limite estabelecido pela SEFAZ [Limite]'
        ],
        '629' => [
            'type' => 0,
            'message' => 'Rejeição: Valor do Produto difere do produto Valor Unitário de Comercialização e Quantidade Comercial'
        ],
        '630' => [
            'type' => 0,
            'message' => 'Rejeição: Valor do Produto difere do produto Valor Unitário de Tributação e Quantidade Tributável'
        ],
        '631' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ-Base do Destinatário difere do CNPJ-Base do Certificado Digital'
        ],
        '632' => [
            'type' => 0,
            'message' => 'Rejeição: Solicitação fora de prazo, a NF-e não está mais disponível para download'
        ],
        '633' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e indisponível para download devido a ausência de Manifestação do Destinatário'
        ],
        '634' => [
            'type' => 0,
            'message' => 'Rejeição: Destinatário da NF-e não tem o mesmo CNPJ raiz do solicitante do download'
        ],
        '635' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e com mesmo número e série já transmitida e aguardando processamento'
        ],
        '650' => [
            'type' => 0,
            'message' => 'Rejeição: Evento de "Ciência da Emissão" para NF-e Cancelada ou Denegada'
        ],
        '651' => [
            'type' => 0,
            'message' => 'Rejeição: Evento de "Desconhecimento da Operação" para NF-e Cancelada ou Denegada'
        ],
        '653' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e Cancelada, arquivo indisponível para download'
        ],
        '654' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e Denegada, arquivo indisponível para download'
        ],
        '655' => [
            'type' => 0,
            'message' => 'Rejeição: Evento de Ciência da Emissão informado após a manifestação final do destinatário'
        ],
        '656' => [
            'type' => 0,
            'message' => 'Rejeição: Consumo Indevido'
        ],
        '657' => [
            'type' => 0,
            'message' => 'Rejeição: Código do Órgão diverge do órgão autorizador'
        ],
        '658' => [
            'type' => 0,
            'message' => 'Rejeição: UF do destinatário da Chave de Acesso diverge da UF autorizadora'
        ],
        '660' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP de Combustível e não informado grupo de combustível da NF-e'
        ],
        '661' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e já existente para o número do EPEC informado'
        ],
        '662' => [
            'type' => 0,
            'message' => 'Rejeição: Numeração do EPEC está inutilizada na Base de Dados da SEFAZ'
        ],
        '663' => [
            'type' => 0,
            'message' => 'Rejeição: Alíquota do ICMS com valor superior a 4 por cento na operação de saída interestadual'
        ],
        'com' => [
            'type' => 0,
            'message' => 'produtos importados'
        ],
        '678' => [
            'type' => 0,
            'message' => 'Rejeição: NF referenciada com UF diferente da NF-e complementar'
        ],
        '679' => [
            'type' => 0,
            'message' => 'Rejeição: Modelo da NF-e referenciada diferente de 55/65'
        ],
        '680' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Duplicidade de NF-e referenciada (Chave de Acesso referenciada mais de uma vez)'
        ],
        '681' => [
            'type' => 0,
            'message' => 'Rejeição: Duplicidade de NF Modelo 1 referenciada (CNPJ, Modelo, Série e Número)'
        ],
        '682' => [
            'type' => 0,
            'message' => 'Rejeição: Duplicidade de NF de Produtor referenciada (IE, Modelo, Série e Número)'
        ],
        '683' => [
            'type' => 0,
            'message' => 'Rejeição: Modelo do CT-e referenciado diferente de 57'
        ],
        '684' => [
            'type' => 0,
            'message' => 'Rejeição: Duplicidade de Cupom Fiscal referenciado (Modelo, Número de Ordem e COO)'
        ],
        '685' => [
            'type' => 0,
            'message' => 'Rejeição: Total do Valor Aproximado dos Tributos difere do somatório dos itens'
        ],
        '686' => [
            'type' => 0,
            'message' => 'Rejeição: NF Complementar referencia uma NF-e cancelada'
        ],
        '687' => [
            'type' => 0,
            'message' => 'Rejeição: NF Complementar referencia uma NF-e denegada'
        ],
        '688' => [
            'type' => 0,
            'message' => 'Rejeição: NF referenciada de Produtor com IE inexistente [nRef: xxx]'
        ],
        '689' => [
            'type' => 0,
            'message' => 'Rejeição: NF referenciada de Produtor com IE não vinculada ao CNPJ/CPF informado [nRef: xxx]'
        ],
        '690' => [
            'type' => 0,
            'message' => 'Rejeição: Pedido de Cancelamento para NF-e com CT-e'
        ],
        '691' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso da NF-e diverge da Chave de Acesso do EPEC'
        ],
        '700' => [
            'type' => 0,
            'message' => 'Rejeição: Mensagem de Lote versão 3.xx. Enviar para o Web Service nfeAutorizacao'
        ],
        '701' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e não pode utilizar a versão 3.00'
        ],
        '702' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e não é aceita pela UF do Emitente'
        ],
        '703' => [
            'type' => 0,
            'message' => 'Rejeição: Data-Hora de Emissão posterior ao horário de recebimento'
        ],
        '704' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com Data-Hora de emissão atrasada'
        ],
        '705' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com data de entrada/saída'
        ],
        '706' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e para operação de entrada'
        ],
        '707' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e para operação interestadual ou com o exterior'
        ],
        '708' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e não pode referenciar documento fiscal'
        ],
        '709' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com formato de DANFE inválido'
        ],
        '710' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e com formato de DANFE inválido'
        ],
        '711' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e com contingência off-line'
        ],
        '712' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com contingência off-line para a UF'
        ],
        '713' => [
            'type' => 0,
            'message' => 'Rejeição: Tipo de Emissão diferente de 6 ou 7 para contingência da SVC acessada'
        ],
        '714' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com contingência DPEC inexistente'
        ],
        '715' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com finalidade inválida'
        ],
        '716' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e em operação não destinada a consumidor final'
        ],
        '717' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e em operação não presencial'
        ],
        '718' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e não deve informar IE de Substituto Tributário'
        ],
        '719' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e sem a identificação do destinatário'
        ],
        '720' => [
            'type' => 0,
            'message' => 'Rejeição: Na operação com Exterior deve ser informada tag idEstrangeiro'
        ],
        '721' => [
            'type' => 0,
            'message' => 'Rejeição: Operação interestadual deve informar CNPJ ou CPF.'
        ],
        '723' => [
            'type' => 0,
            'message' => 'Rejeição: Operação interna com idEstrangeiro informado deve ser para consumidor final'
        ],
        '724' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e sem o nome do destinatário'
        ],
        '725' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com CFOP inválido'
        ],
        '726' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e sem a informação de endereço do destinatário'
        ],
        '727' => [
            'type' => 0,
            'message' => 'Rejeição: Operação com Exterior e UF diferente de EX'
        ],
        '728' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e sem informação da IE do destinatário'
        ],
        '729' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com informação da IE do destinatário'
        ],
        '730' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com Inscrição Suframa'
        ],
        '731' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP de operação com Exterior e idDest <> 3'
        ],
        '732' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP de operação interestadual e idDest <> 2'
        ],
        '733' => [
            'type' => 0,
            'message' => 'Rejeição: CFOP de operação interna e idDest <> 1'
        ],
        '734' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com Unidade de Comercialização inválida'
        ],
        '735' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com Unidade de Tributação inválida'
        ],
        '736' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com grupo de Veículos novos'
        ],
        '737' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com grupo de Medicamentos'
        ],
        '738' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com grupo de Armamentos'
        ],
        '739' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com grupo de Combustível'
        ],
        '740' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com CST 51-Diferimento'
        ],
        '741' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com Partilha de ICMS entre UF'
        ],
        '742' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com grupo do IPI'
        ],
        '743' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com grupo do II'
        ],
        '745' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e sem grupo do PIS'
        ],
        '746' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com grupo do PIS-ST'
        ],
        '748' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e sem grupo da COFINS'
        ],
        '749' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com grupo da COFINS-ST'
        ],
        '750' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com valor total superior ao permitido para destinatário não identificado (Código) [Limite]'
        ],
        '751' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com valor total superior ao permitido para destinatário não identificado (Nome) [Limite]'
        ],
        '752' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com valor total superior ao permitido para destinatário não identificado (Endereço) [Limite]'
        ],
        '753' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com Frete'
        ],
        '754' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com dados do Transportador'
        ],
        '755' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com dados de Retenção do ICMS no Transporte'
        ],
        '756' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com dados do veículo de Transporte'
        ],
        '757' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com dados de Reboque do veículo de Transporte'
        ],
        '758' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com dados do Vagão de Transporte'
        ],
        '759' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com dados da Balsa de Transporte'
        ],
        '760' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com dados de cobrança (Fatura, Duplicata)'
        ],
        '762' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com dados de compras (Empenho, Pedido, Contrato)'
        ],
        '763' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com dados de aquisição de Cana'
        ],
        '764' => [
            'type' => 0,
            'message' => 'Rejeição: Solicitada resposta síncrona para Lote com mais de uma NF-e (indSinc=1)'
        ],
        '765' => [
            'type' => 0,
            'message' => 'Rejeição: Lote só poderá conter NF-e ou NFC-e'
        ],
        '766' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com CST 50-Suspensão'
        ],
        '767' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com somatório dos pagamentos diferente do total da Nota Fiscal'
        ],
        '768' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e não deve possuir o grupo de Formas de Pagamento'
        ],
        '769' => [
            'type' => 0,
            'message' => 'Rejeição: A critério da UF NFC-e deve possuir o grupo de Formas de Pagamento'
        ],
        '770' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e autorizada há mais de 24 horas.'
        ],
        '771' => [
            'type' => 0,
            'message' => 'Rejeição: Operação Interestadual e UF de destino com EX'
        ],
        '772' => [
            'type' => 0,
            'message' => 'Rejeição: Operação Interestadual e UF de destino igual à UF do emitente'
        ],
        '773' => [
            'type' => 0,
            'message' => 'Rejeição: Operação Interna e UF de destino difere da UF do emitente'
        ],
        '774' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com indicador de item não participante do total'
        ],
        '775' => [
            'type' => 0,
            'message' => 'Rejeição: Modelo da NFC-e diferente de 65'
        ],
        '776' => [
            'type' => 0,
            'message' => 'Rejeição: Solicitada resposta síncrona para UF que não disponibiliza este atendimento (indSinc=1)'
        ],
        '777' => [
            'type' => 0,
            'message' => 'Rejeição: Obrigatória a informação do NCM completo'
        ],
        '778' => [
            'type' => 0,
            'message' => 'Rejeição: Informado NCM inexistente'
        ],
        '779' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com NCM incompatível'
        ],
        '780' => [
            'type' => 0,
            'message' => 'Rejeição: Total da NFC-e superior ao valor limite estabelecido pela SEFAZ [Limite]'
        ],
        '781' => [
            'type' => 0,
            'message' => 'Rejeição: Emissor não habilitado para emissão da NFC-e'
        ],
        '782' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e não é autorizada pelo SCAN'
        ],
        '783' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e não é autorizada pela SVC'
        ],
        '784' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e não permite o evento de Carta de Correção'
        ],
        '785' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e com entrega a domicílio não permitida pela UF'
        ],
        '786' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e de entrega a domicílio sem dados do Transportador'
        ],
        '787' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e de entrega a domicílio sem a identificação do destinatário'
        ],
        '788' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e de entrega a domicílio sem o endereço do destinatário'
        ],
        '789' => [
            'type' => 0,
            'message' => 'Rejeição: NFC-e para destinatário contribuinte de ICMS'
        ],
        '790' => [
            'type' => 0,
            'message' => 'Rejeição: Operação com Exterior para destinatário Contribuinte de ICMS'
        ],
        '791' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e com indicação de destinatário isento de IE, com a informação da IE do destinatário'
        ],
        '792' => [
            'type' => 0,
            'message' => 'Rejeição: Informada a IE do destinatário para operação com destinatário no Exterior'
        ],
        '793' => [
            'type' => 0,
            'message' => 'Rejeição: Informado Capítulo do NCM inexistente'
        ],
        '794' => [
            'type' => 0,
            'message' => 'Rejeição: NF-e com indicativo de NFC-e com entrega a domicílio'
        ],
        '795' => [
            'type' => 0,
            'message' => 'Rejeição: Total do ICMS desonerado difere do somatório dos itens'
        ],
        '796' => [
            'type' => 0,
            'message' => 'Rejeição: Empresa sem Chave de Segurança para o QR-Code'
        ],
        '301' => [
            'type' => 0,
            'message' => 'Uso Denegado: Irregularidade fiscal do emitente'
        ],
        '302' => [
            'type' => 0,
            'message' => 'Uso Denegado: Irregularidade fiscal do destinatário'
        ],
        '303' => [
            'type' => 0,
            'message' => 'Uso Denegado: Destinatário não habilitado a operar na UF'
        ],
        '999' => [
            'type' => 0,
            'message' => 'Rejeição: Erro não catalogado (informar a mensagem de erro capturado no tratamento da exceção)'
        ]
    ];
}
