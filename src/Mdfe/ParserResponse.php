<?php
declare(strict_types = 1);
namespace Inspire\Dfe\Mdfe;

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
     * Parse SEFAZ response of MDFeRecepcao
     *
     * @param Xml $xml
     * @return array
     */
    public static function MDFeRecepcao(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retEnviMDFe');
        Arrays::forget($aData, [
            '@attributes',
            'protMDFe.@attributes'
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
     *
     * @param Xml $xml
     * @return array
     */
    public static function MDFeRetRecepcao(Xml $xml): array
    {
        $aData = Arrays::get(Xml::xmlToArray($xml->getXml()), 'retConsReciMDFe');
        Arrays::forget($aData, [
            '@attributes',
            'protCTe.@attributes'
        ]);
        return $aData;
    }

    /**
     * Parse SEFAZ response of MDFeConsulta
     *
     * @param Xml $xml
     * @return array
     */
    public static function MDFeConsulta(Xml $xml): array
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
        $prot = $xml->xpath("/protMDFe");
        if ($prot && is_array($prot)) {
            $prot = $prot[0];
            $parser['aProt'][Arrays::get($prot, 'infProt.chMDFe')] = [
                'versao' => Arrays::get($prot, '@attributes.versao'),
                'tpAmb' => Arrays::get($prot, 'infProt.tpAmb'),
                'verAplic' => Arrays::get($prot, 'infProt.verAplic'),
                'chMDFe' => Arrays::get($prot, 'infProt.chMDFe'),
                'dhRecbto' => Arrays::get($prot, 'infProt.dhRecbto'),
                'nProt' => Arrays::get($prot, 'infProt.nProt'),
                'digVal' => Arrays::get($prot, 'infProt.digVal'),
                'cStat' => Arrays::get($prot, 'infProt.cStat'),
                'xMotivo' => Arrays::get($prot, 'infProt.xMotivo'),
                'xml' => $xml->xpathXml('/protMDFe', 0)->asXML()
            ];
        }
        $prot = $xml->xpath("/retCancMDFe");
        if ($prot && is_array($prot)) {
            $prot = $prot[0];
            $parser['retCancMDFe'] = [
                'versao' => Arrays::get($prot, '@attributes.versao'),
                'tpAmb' => Arrays::get($prot, 'infProt.tpAmb'),
                'verAplic' => Arrays::get($prot, 'infProt.verAplic'),
                'chMDFe' => Arrays::get($prot, 'infProt.chMDFe'),
                'dhRecbto' => Arrays::get($prot, 'infProt.dhRecbto'),
                'nProt' => Arrays::get($prot, 'infProt.nProt'),
                'digVal' => Arrays::get($prot, 'infProt.digVal'),
                'cStat' => Arrays::get($prot, 'infProt.cStat'),
                'xMotivo' => Arrays::get($prot, 'infProt.xMotivo'),
                'xml' => $xml->xpathXml('/retCancCTe', 0)->asXML()
            ];
        }
        $protEventos = $xml->xpath("/procEventoMDFe");
        if (! empty($protEventos)) {
            $parser['procEventoMDFe'] = [];
            foreach ($protEventos as $i => $prot) {
                $prot = $prot['retEventoMDFe'];
                array_push($parser['procEventoMDFe'], [
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
                    'xml' => $xml->xpathXml('/procEventoCTe', $i)->asXML()
                ]);
            }
        }
        return $parser;
    }

    /**
     * Parse SEFAZ response of MDFeConsNaoEnc
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
     * Parse SEFAZ response of CteStatusServico
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
        Arrays::forget($aData, [
            '@attributes'
        ]);
        return $aData;
    }
}

