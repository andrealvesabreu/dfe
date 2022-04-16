<?php
declare(strict_types = 1);
namespace Inspire\Dfe\Parser;

use Inspire\Support\Xml\Xml;
use Inspire\Support\ {
    Arrays,
    Strings
};

/**
 * Description of Dte
 *
 * @author aalves
 */
class Dte extends Base
{

    /**
     * Parse SEFAZ response of MDFeRecepcao
     *
     * @param Xml $xml
     * @return array
     */
    public static function DTeRecepcao(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retEnviDTe');
        Arrays::forget($aData, [
            '@attributes'
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
    public static function DTeRetRecepcao(Xml $xml): array
    {
        /**
         * Arrays to easy parser
         *
         * $aData array
         */
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retConsReciDTe');
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
        if (isset($aData['protDTe'][0])) {
            foreach ($aData['protDTe'] as &$ct) {
                Arrays::set($ct, 'versao', Arrays::get($ct, '@attributes.versao'));
                Arrays::forget($ct, [
                    '@attributes',
                    'infProt.@attributes'
                ]);
            }
        } else {
            Arrays::set($aData['protDTe'], 'versao', Arrays::get($aData, 'protDTe.@attributes.versao'));
            Arrays::forget($aData, [
                'protDTe.@attributes',
                'protDTe.infProt.@attributes'
            ]);
            $aData['protDTe'] = [
                $aData['protDTe']
            ];
        }
        /**
         * Remapping array for MDFe keys (protDTe.[chMDFe].infProt)
         */
        foreach ($aData['protDTe'] as $i => $prot) {
            Arrays::forget($aData['protDTe'], $i);
            $prot['xml'] = $parserProt->xpathXml('/protDTe', $i)->asXML();
            Arrays::set($aData['protDTe'], Arrays::get($prot, 'infProt.chMDFe'), $prot);
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
     * 6 - Certificate error
     * 7 - Permanent error on document
     *
     * @var array
     */
    private static array $messages = [
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
        '108' => [ // Verified
            'type' => 3,
            'message' => 'Serviço Paralisado Momentaneamente (curto prazo)'
        ],
        '109' => [ // Verified
            'type' => 4,
            'message' => 'Serviço Paralisado sem Previsão'
        ],
        '132' => [ // Verified
            'type' => 1,
            'message' => 'Encerramento de DT-e homologado'
        ],
        '134' => [
            'type' => 0,
            'message' => 'Evento registrado com alerta para situação'
        ],
        '135' => [ // Verified
            'type' => 1,
            'message' => 'Evento registrado e vinculado a DT-e'
        ],
        '136' => [
            'type' => 0,
            'message' => 'Evento registrado, mas não vinculado a DT-e'
        ],
        '207' => [
            'type' => 0,
            'message' => 'Rejeição: CNPJ do emitente inválido'
        ],
        '215' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Falha no schema XML'
        ],
        '223' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: CNPJ / CPF do transmissor do arquivo difere do CNPJ / CPF do transmissor da consulta'
        ],
        '225' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Falha no Schema XML do DT-e'
        ],
        '238' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Cabeçalho - Versão do arquivo XML superior a Versão vigente'
        ],
        '239' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Versão do arquivo XML não suportada'
        ],
        '242' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Elemento DTeCabecMsg inexistente no SOAP Header'
        ],
        '248' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: UF do Recibo diverge da UF autorizadora'
        ],
        '405' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Município de Carregamento inexistente'
        ],
        '406' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Município de Descarregamento inexistente'
        ],
        '408' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Município do Emitente inexistente'
        ],
        '411' => [ // Verified
            'type' => 0,
            'message' => 'Rejeição: Campo versaoDados inexistente no elemento DTeCabecMsg do SOAP Header'
        ],
        '462' => [
            'type' => 0,
            'message' => 'Rejeição: Existe DT-e não encerrado há mais de 5 dias para placa com até 2 UF de percurso informadas [chDTe: 99999999999999999999999999999999999999999999][nProt:999999999999999]'
        ],
        '600' => [
            'type' => 0,
            'message' => 'Rejeição: Chave de Acesso difere da existente em BD'
        ],
        '609' => [
            'type' => 0,
            'message' => '[nProt:999999999999999][dhEnc:AAAA-MM-DDTHH:MM:SSTZD].[chDTe:99999999999999999999999999999999999999999999][nProt:999999999999999]'
        ],
        '611' => [
            'type' => 0,
            'message' => 'Rejeição: Existe DT-e não encerrado para esta placa, tipo de emitente e UF descarregamento'
        ],
        '678' => [
            'type' => 0,
            'message' => 'Rejeição: Uso Indevido'
        ],
        '683' => [
            'type' => 0,
            'message' => 'Rejeição: Placa do veículo de tração não vinculada ao RNTRC informado.'
        ],
        '684' => [
            'type' => 0,
            'message' => 'Rejeição: CIOT obrigatório para RNTRC informado.'
        ],
        '686' => [
            'type' => 0,
            'message' => 'Rejeição: Existe DT-e não encerrado há mais de 30 dias para o emitente [chDTe:99999999999999999999999999999999999999999999][nProt:999999999999999]'
        ],
        '700' => [
            'type' => 0,
            'message' => 'Rejeição: Preenchimento Incorreto Campo [<campo>] com erro. O valor [<Valor>], na linha <linha>, difere do especificado'
        ],
        '999' => [
            'type' => 0,
            'message' => 'Rejeição: Erro não catalogado (informar a msg de erro capturado no tratamento da exceção)'
        ]
    ];
}

