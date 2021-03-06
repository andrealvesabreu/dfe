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
            'message' => 'Arquivo n??o localizado'
        ],
        '108' => [ // Verified
            'type' => 3,
            'message' => 'Servi??o Paralisado Momentaneamente (curto prazo)'
        ],
        '109' => [ // Verified
            'type' => 4,
            'message' => 'Servi??o Paralisado sem Previs??o'
        ],
        '132' => [ // Verified
            'type' => 1,
            'message' => 'Encerramento de DT-e homologado'
        ],
        '134' => [
            'type' => 0,
            'message' => 'Evento registrado com alerta para situa????o'
        ],
        '135' => [ // Verified
            'type' => 1,
            'message' => 'Evento registrado e vinculado a DT-e'
        ],
        '136' => [
            'type' => 0,
            'message' => 'Evento registrado, mas n??o vinculado a DT-e'
        ],
        '207' => [
            'type' => 0,
            'message' => 'Rejei????o: CNPJ do emitente inv??lido'
        ],
        '215' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Falha no schema XML'
        ],
        '223' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: CNPJ / CPF do transmissor do arquivo difere do CNPJ / CPF do transmissor da consulta'
        ],
        '225' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Falha no Schema XML do DT-e'
        ],
        '238' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Cabe??alho - Vers??o do arquivo XML superior a Vers??o vigente'
        ],
        '239' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Vers??o do arquivo XML n??o suportada'
        ],
        '242' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Elemento DTeCabecMsg inexistente no SOAP Header'
        ],
        '248' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: UF do Recibo diverge da UF autorizadora'
        ],
        '405' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio de Carregamento inexistente'
        ],
        '406' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio de Descarregamento inexistente'
        ],
        '408' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Munic??pio do Emitente inexistente'
        ],
        '411' => [ // Verified
            'type' => 0,
            'message' => 'Rejei????o: Campo versaoDados inexistente no elemento DTeCabecMsg do SOAP Header'
        ],
        '462' => [
            'type' => 0,
            'message' => 'Rejei????o: Existe DT-e n??o encerrado h?? mais de 5 dias para placa com at?? 2 UF de percurso informadas [chDTe: 99999999999999999999999999999999999999999999][nProt:999999999999999]'
        ],
        '600' => [
            'type' => 0,
            'message' => 'Rejei????o: Chave de Acesso difere da existente em BD'
        ],
        '609' => [
            'type' => 0,
            'message' => '[nProt:999999999999999][dhEnc:AAAA-MM-DDTHH:MM:SSTZD].[chDTe:99999999999999999999999999999999999999999999][nProt:999999999999999]'
        ],
        '611' => [
            'type' => 0,
            'message' => 'Rejei????o: Existe DT-e n??o encerrado para esta placa, tipo de emitente e UF descarregamento'
        ],
        '678' => [
            'type' => 0,
            'message' => 'Rejei????o: Uso Indevido'
        ],
        '683' => [
            'type' => 0,
            'message' => 'Rejei????o: Placa do ve??culo de tra????o n??o vinculada ao RNTRC informado.'
        ],
        '684' => [
            'type' => 0,
            'message' => 'Rejei????o: CIOT obrigat??rio para RNTRC informado.'
        ],
        '686' => [
            'type' => 0,
            'message' => 'Rejei????o: Existe DT-e n??o encerrado h?? mais de 30 dias para o emitente [chDTe:99999999999999999999999999999999999999999999][nProt:999999999999999]'
        ],
        '700' => [
            'type' => 0,
            'message' => 'Rejei????o: Preenchimento Incorreto Campo [<campo>] com erro. O valor [<Valor>], na linha <linha>, difere do especificado'
        ],
        '999' => [
            'type' => 0,
            'message' => 'Rejei????o: Erro n??o catalogado (informar a msg de erro capturado no tratamento da exce????o)'
        ]
    ];
}

