<?php
declare(strict_types = 1);
namespace Inspire\Dfe\Cte;

use Inspire\Dfe\Xml\Xml;
use Inspire\Core\Utils\Arrays;

/**
 * Description of ParserResponse
 *
 * @author aalves
 */
class ParserResponse
{

    /**
     * Parse SEFAZ response of CteRecepcao
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
        return $aData;
    }

    /**
     * Parse SEFAZ response of CteRecepcaoOS
     *
     * @param Xml $xml
     * @return array
     */
    public static function CteRecepcaoOS(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retEnviCte');
        Arrays::forget($aData, [
            '@attributes',
            'protCTe.@attributes'
        ]);
        return $aData;
    }

    /**
     * Parse SEFAZ response of CteRetRecepcao
     *
     * @param Xml $xml
     * @return array
     */
    public static function CteRetRecepcao(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retConsReciCTe');
        Arrays::forget($aData, [
            '@attributes',
            'protCTe.@attributes'
        ]);
        return $aData;
    }

    /**
     * Parse SEFAZ response of CteInutilizacao
     *
     * @param Xml $xml
     * @return array
     */
    public static function CteInutilizacao(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retInutCTe');
        Arrays::forget($aData, [
            '@attributes'
        ]);
        return $aData;
    }

    /**
     * Parse SEFAZ response of CteConsulta
     *
     * @param Xml $xml
     * @return array
     */
    public static function CteConsulta(Xml $xml): array
    {
        $xml->load($xml->getXml());
        $parser = [
            'versao' => $xml->xpath("/@versao", 0),
            'tpAmb' => $xml->xpath("/tpAmb", 0),
            'verAplic' => $xml->xpath("/verAplic", 0),
            'cStat' => $xml->xpath("/cStat", 0),
            'xMotivo' => $xml->xpath("/xMotivo", 0),
            'cUF' => $xml->xpath("/cUF", 0),
            'aProt' => []
        ];
        $prot = $xml->xpath("/protCTe");
        if ($prot && is_array($prot)) {
            $prot = $prot[0];
            $parser['aProt'][Arrays::get($prot, 'infProt.chCTe')] = [
                'versao' => Arrays::get($prot, '@attributes.versao'),
                'tpAmb' => Arrays::get($prot, 'infProt.tpAmb'),
                'verAplic' => Arrays::get($prot, 'infProt.verAplic'),
                'chCTe' => Arrays::get($prot, 'infProt.chCTe'),
                'dhRecbto' => Arrays::get($prot, 'infProt.dhRecbto'),
                'nProt' => Arrays::get($prot, 'infProt.nProt'),
                'digVal' => Arrays::get($prot, 'infProt.digVal'),
                'cStat' => Arrays::get($prot, 'infProt.cStat'),
                'xMotivo' => Arrays::get($prot, 'infProt.xMotivo'),
                'xml' => $xml->xpathXml('/protCTe', 0)->asXML()
            ];
        }
        $prot = $xml->xpath("/retCancCTe");
        if ($prot && is_array($prot)) {
            $prot = $prot[0];
            $parser['retCancCTe'] = [
                'versao' => Arrays::get($prot, '@attributes.versao'),
                'tpAmb' => Arrays::get($prot, 'infProt.tpAmb'),
                'verAplic' => Arrays::get($prot, 'infProt.verAplic'),
                'chCTe' => Arrays::get($prot, 'infProt.chCTe'),
                'dhRecbto' => Arrays::get($prot, 'infProt.dhRecbto'),
                'nProt' => Arrays::get($prot, 'infProt.nProt'),
                'digVal' => Arrays::get($prot, 'infProt.digVal'),
                'cStat' => Arrays::get($prot, 'infProt.cStat'),
                'xMotivo' => Arrays::get($prot, 'infProt.xMotivo'),
                'xml' => $xml->xpathXml('/retCancCTe', 0)->asXML()
            ];
        }
        $protEventos = $xml->xpath("/procEventoCTe");
        if (! empty($protEventos)) {
            $parser['procEventoCTe'] = [];
            foreach ($protEventos as $i => $prot) {
                $prot = $prot['retEventoCTe'];
                array_push($parser['procEventoCTe'], [
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
                    'xml' => $xml->xpathXml('/procEventoCTe', $i)->asXML()
                ]);
            }
        }
        return $parser;
    }

    /**
     * Parse SEFAZ response of CteStatusServico
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
        Arrays::forget($aData, [
            '@attributes'
        ]);
        return $aData;
    }
}

