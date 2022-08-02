<?php

declare(strict_types=1);

namespace Inspire\Dfe\Parser;

use Inspire\Support\Arrays;
use Inspire\Support\Xml\Xml;

/**
 * Description of Base
 *
 * @author aalves
 */
class Base
{

    /**
     * Set types of documents to return on DFe sist services
     *
     * @var array
     */
    protected static ?array $unpack = null;

    /**
     * Define what kind of XML can be returned from DFe sist services
     *
     * @param array $schemas
     */
    public static function getDistDfeSchemas(array $schemas): void
    {
        self::$unpack = array_flip($schemas);
    }

    /**
     * Response codes and messages.
     * Group messages by status type
     */
    protected static array $messageType = [
        0 => 'Error on message sent. Fix package before resending',
        1 => 'OK',
        2 => 'Wait before try again',
        3 => 'Temporary error on service',
        4 => 'Permanent error on service',
        5 => 'Permanent error with carrier registration',
        6 => 'Certificate error',
        7 => 'Permanent error on document' // When there is no way to perform some action with one document
    ];
    /**
     * 
     * Métodos para NFe
     * 
     */

    /**
     * Pacote de registro de evento (protocolado)
     *
     * @param Xml $xml
     * @return array|null
     */
    public static function procEventoNFe(Xml $xml): ?array
    {
        $prot = Arrays::get(Xml::xmlToArray($xml->getXml()), 'procEventoNFe');
        Arrays::forget($prot, 'evento.infEvento.detEvento.@attributes');
        return [
            'evento' => [
                'versao' => Arrays::get($prot, 'evento.@attributes.versao'),
                'cOrgao' => Arrays::get($prot, 'evento.infEvento.cOrgao'),
                'tpAmb' => Arrays::get($prot, 'evento.infEvento.tpAmb'),
                'CNPJ' => Arrays::get($prot, 'evento.infEvento.CNPJ'),
                'chNFe' => Arrays::get($prot, 'evento.infEvento.chNFe'),
                'dhEvento' => Arrays::get($prot, 'evento.infEvento.dhEvento'),
                'tpEvento' => Arrays::get($prot, 'evento.infEvento.tpEvento'),
                'nSeqEvento' => Arrays::get($prot, 'evento.infEvento.nSeqEvento'),
                'detEvento' => Arrays::get($prot, 'evento.infEvento.detEvento'), // Array com específicos do evento
            ],
            'retEvento' => [
                'versao' => Arrays::get($prot, '@attributes.versao'),
                'tpAmb' => Arrays::get($prot, 'retEvento.infEvento.tpAmb'),
                'verAplic' => Arrays::get($prot, 'retEvento.infEvento.verAplic'),
                'cOrgao' => Arrays::get($prot, 'retEvento.infEvento.cOrgao'),
                'cStat' => Arrays::get($prot, 'retEvento.infEvento.cStat'),
                'xMotivo' => Arrays::get($prot, 'retEvento.infEvento.xMotivo'),
                'chNFe' => Arrays::get($prot, 'retEvento.infEvento.chNFe'),
                'tpEvento' => Arrays::get($prot, 'retEvento.infEvento.tpEvento'),
                'xEvento' => Arrays::get($prot, 'retEvento.infEvento.xEvento'),
                'nSeqEvento' => Arrays::get($prot, 'retEvento.infEvento.nSeqEvento'),
                'dhRegEvento' => Arrays::get($prot, 'retEvento.infEvento.dhRegEvento'),
                'nProt' => Arrays::get($prot, 'retEvento.infEvento.nProt'),
                'xml' => $xml->getXml()
            ]
        ];
    }

    /**
     * Retorno de resumo de NFe
     *
     * @param Xml $xml
     * @return array|null
     */
    public static function resNFe(Xml $xml): ?array
    {
        $resNFe = Xml::xmlToArray($xml->getXml());
        return [
            'versao' => Arrays::get($resNFe, 'resNFe.@attributes.versao'),
            'chNFe' => Arrays::get($resNFe, 'resNFe.chNFe'),
            'CNPJ' => Arrays::get($resNFe, 'resNFe.CNPJ'),
            'xNome' => Arrays::get($resNFe, 'resNFe.xNome'),
            'IE' => Arrays::get($resNFe, 'resNFe.IE'),
            'dhEmi' => Arrays::get($resNFe, 'resNFe.dhEmi'),
            'tpNF' => Arrays::get($resNFe, 'resNFe.tpNF'),
            'vNF' => Arrays::get($resNFe, 'resNFe.vNF'),
            'digVal' => Arrays::get($resNFe, 'resNFe.digVal'),
            'dhRecbto' => Arrays::get($resNFe, 'resNFe.dhRecbto'),
            'nProt' => Arrays::get($resNFe, 'resNFe.nProt'),
            'cSitNFe' => Arrays::get($resNFe, 'resNFe.cSitNFe'),
            'xml' => $xml->getXml()
        ];
    }

    /**
     * Parse mensagem de registro de evento em NFe
     *
     * @param Xml $xml
     * @return array|null
     */
    public static function resEvento(Xml $xml): ?array
    {
        $resEvento = Xml::xmlToArray($xml->getXml());
        return [
            'versao' => Arrays::get($resEvento, 'resEvento.@attributes.versao'),
            'cOrgao' => Arrays::get($resEvento, 'resEvento.cOrgao'),
            'CNPJ' => Arrays::get($resEvento, 'resEvento.CNPJ'),
            'chNFe' => Arrays::get($resEvento, 'resEvento.chNFe'),
            'dhEvento' => Arrays::get($resEvento, 'resEvento.dhEvento'),
            'tpEvento' => Arrays::get($resEvento, 'resEvento.tpEvento'),
            'nSeqEvento' => Arrays::get($resEvento, 'resEvento.nSeqEvento'),
            'xEvento' => Arrays::get($resEvento, 'resEvento.xEvento'),
            'dhRecbto' => Arrays::get($resEvento, 'resEvento.dhRecbto'),
            'nProt' => Arrays::get($resEvento, 'resEvento.nProt'),
            'xml' => $xml->getXml()
        ];
    }

    /**
     * 
     * Metodos para CTe
     * 
     */
    /**
     * Pacote de registro de evento (protocolado)
     *
     * @param Xml $xml
     * @return array|null
     */
    public static function procEventoCTe(Xml $xml): ?array
    {
        $prot = Arrays::get(Xml::xmlToArray($xml->getXml()), 'procEventoCTe');
        Arrays::forget($prot, 'eventoCTe.infEvento.detEvento.@attributes');
        return [
            'evento' => [
                'versao' => Arrays::get($prot, 'eventoCTe.@attributes.versao'),
                'cOrgao' => Arrays::get($prot, 'eventoCTe.infEvento.cOrgao'),
                'tpAmb' => Arrays::get($prot, 'eventoCTe.infEvento.tpAmb'),
                'CNPJ' => Arrays::get($prot, 'eventoCTe.infEvento.CNPJ'),
                'chCTe' => Arrays::get($prot, 'eventoCTe.infEvento.chCTe'),
                'dhEvento' => Arrays::get($prot, 'eventoCTe.infEvento.dhEvento'),
                'tpEvento' => Arrays::get($prot, 'eventoCTe.infEvento.tpEvento'),
                'nSeqEvento' => Arrays::get($prot, 'eventoCTe.infEvento.nSeqEvento'),
                'detEvento' => Arrays::get($prot, 'eventoCTe.infEvento.detEvento'), // Array com específicos do evento
            ],
            'retEvento' => [
                'versao' => Arrays::get($prot, '@attributes.versao'),
                'tpAmb' => Arrays::get($prot, 'retEventoCTe.infEvento.tpAmb'),
                'verAplic' => Arrays::get($prot, 'retEventoCTe.infEvento.verAplic'),
                'cOrgao' => Arrays::get($prot, 'retEventoCTe.infEvento.cOrgao'),
                'cStat' => Arrays::get($prot, 'retEventoCTe.infEvento.cStat'),
                'xMotivo' => Arrays::get($prot, 'retEventoCTe.infEvento.xMotivo'),
                'chCTe' => Arrays::get($prot, 'retEventoCTe.infEvento.chCTe'),
                'tpEvento' => Arrays::get($prot, 'retEventoCTe.infEvento.tpEvento'),
                'xEvento' => Arrays::get($prot, 'retEventoCTe.infEvento.xEvento'),
                'nSeqEvento' => Arrays::get($prot, 'retEventoCTe.infEvento.nSeqEvento'),
                'dhRegEvento' => Arrays::get($prot, 'retEventoCTe.infEvento.dhRegEvento'),
                'nProt' => Arrays::get($prot, 'retEventoCTe.infEvento.nProt'),
                'xml' => $xml->getXml()
            ]
        ];
    }
}
