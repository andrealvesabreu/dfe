<?php
declare(strict_types = 1);
namespace Inspire\Dfe;

use Inspire\Validator\ {
    Variable,
    XsdSchema
};
use Inspire\Dfe\Cte\ParserResponse;
use Inspire\Support\Message\System\SystemMessage;
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

/**
 * Class implements methods from SEFAZ
 *
 * Web Service - CteRecepcao
 * Web Service - CteRecepcaoOS
 * Web Service - CteRetRecepcao
 * Web Service - CteInutilizacao
 * Web Service - CteConsulta
 * Web Service - CteStatusServico
 * Web Service - CteConsultaCadastro
 * Web Service - CteRecepcaoEvento
 * Web Service - CTeDistribuicaoDFe
 *
 * @author aalves
 */
class Cte extends Dfe
{

    /**
     * Send a lot to webservice
     *
     * @param string $idLot
     * @param array $CTe
     * @return SystemMessage
     */
    public function CteRecepcao(string $idLot, array $CTe): SystemMessage
    {
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $body = [
            'enviCTe' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'idLote' => str_pad($idLot, 15, '0', STR_PAD_LEFT),
                'xml' => '__xml__'
            ]
        ];
        $body = new Xml(str_replace('<xml>__xml__</xml>', // Replace special mark
        implode('', Xml::clearXmlDeclaration($CTe)), // Set documents
        Xml::arrayToXml($body))); // Convert array to XML
        return $this->send($body);
    }

    /**
     * Send a lot to webservice
     *
     * @param string $idLot
     * @param array $CTe
     * @return SystemMessage
     */
    public function CteRecepcaoOS(string $idLot, array $CTe): SystemMessage
    {
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $body = [
            'enviCTe' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'idLote' => str_pad($idLot, 15, '0', STR_PAD_LEFT),
                '@mixed' => implode('', Xml::clearXmlDeclaration($CTe))
            ]
        ];
        $body = Xml::arrayToXml($body, null, true);
        return $this->send($body);
    }

    /**
     * Query authorization status
     *
     * @param string $rec
     * @return SystemMessage
     */
    public function CteRetRecepcao(string $rec): SystemMessage
    {
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $body = [
            'consReciCTe' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'tpAmb' => $this->tpAmb,
                'nRec' => $rec
            ]
        ];
        $body = Xml::arrayToXml($body, null, true);
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/consReciCTe_v{$this->version}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
        }
        $response = $this->send($body);
        /**
         * Save files
         *
         * @var array|null $paths
         */
        $paths = $response->getExtra('paths');
        if ($paths !== null) {
            $baseName = $rec . '-' . date('Y_m_d-H_i_s');
            // Save sent file
            $fileSent = "{$paths['request']}/{$baseName}-consReciCTe.xml";
            file_put_contents($fileSent, $body->getXml());
            // Save response file, if server has processed request succefully
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retconsReciCTe.xml";
                file_put_contents($fileResponse, $response->getExtra('xml'));
                /**
                 * Check for errors
                 */
                foreach ($response->getExtra('parse')['protCTe'] as $protCTe) {
                    $parseCode = ParserResponse::getResponseCode(Arrays::get($protCTe, 'infProt.cStat'));
                    print_r($parseCode);
                    $xtp = new Xml('/home/aalves/eclipse-workspace/dfe/storage/xmls/cte/94001641000538/201902/producao/assinadas/41211294001641000457570020000769711942720311-cte.xml');
                    var_dump($xtp->xpathXml('/infCte/rem/IE', 0));
                }
                // if(Parse)
            }
        }
        return $response;
    }

    /**
     * Send request to disable the number
     *
     * @param int $nSerie
     * @param int $nIni
     * @param int $nFin
     * @param string $xJust
     * @param int $year
     * @return \Inspire\Support\Message\System\SystemMessage
     */
    public function CteInutilizacao(int $nSerie, int $nIni, int $nFin, string $xJust, ?int $year = null): SystemMessage
    {
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $body = [
            'inutCTe' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'infInut' => [
                    '@attributes' => [
                        'Id' => 'ID' . $this->cUF . $this->CNPJ . '57' . str_pad((string) $nSerie, 3, '0', STR_PAD_LEFT) . str_pad((string) $nIni, 9, '0', STR_PAD_LEFT) . str_pad((string) $nFin, 9, '0', STR_PAD_LEFT)
                    ],
                    'tpAmb' => $this->tpAmb,
                    'xServ' => 'INUTILIZAR',
                    'cUF' => $this->cUF,
                    'ano' => $year ?? date('y'),
                    'CNPJ' => $this->CNPJ,
                    'mod' => '57',
                    'serie' => $nSerie,
                    'nCTIni' => $nIni,
                    'nCTFin' => $nFin,
                    'xJust' => Strings::removeAccents($xJust)
                ]
            ]
        ];
        $body = $this->sign(Xml::arrayToXml($body), 'infInut', 'Id', 'inutCTe');
        return $this->send(new Xml($body));
    }

    /**
     * Query document on webservice
     *
     * @param string $chCTe
     * @return \Inspire\Support\Message\System\SystemMessage
     */
    public function CteConsulta(string $chCTe): SystemMessage
    {
        if (! Variable::nfeAccessKey()->validate($chCTe)) {
            return new SystemMessage("Invalid CTe key: {$chCTe}", // Message
            '1', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $body = [
            'consSitCTe' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'tpAmb' => $this->tpAmb,
                'xServ' => 'CONSULTAR',
                'chCTe' => $chCTe
            ]
        ];
        $body = Xml::arrayToXml($body, null, true);
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/consSitCTe_v{$this->version}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
        }
        $response = $this->send($body);
        /**
         * Save files
         *
         * @var array|null $paths
         */
        $paths = $response->getExtra('paths');
        if ($paths !== null) {
            $baseName = $chCTe . '-' . date('Y_m_d-H_i_s');
            // Save sent file
            $fileSent = "{$paths['request']}/{$baseName}-consSitCTe.xml";
            file_put_contents($fileSent, $body->getXml());
            // Save response file, if server has processed request succefully
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retConsSitCTe.xml";
                file_put_contents($fileResponse, $response->getExtra('xml'));
            }
        }
        return $response;
    }

    /**
     * Query WS status
     *
     * @return \Inspire\Support\Message\System\SystemMessage
     */
    public function CteStatusServico(): SystemMessage
    {
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $body = [
            'consStatServCte' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'tpAmb' => $this->tpAmb,
                'xServ' => 'STATUS'
            ]
        ];
        $body = Xml::arrayToXml($body, null, true);
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/consStatServCTe_v{$this->version}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
        }
        $response = $this->send($body);
        /**
         * Save files
         *
         * @var array|null $paths
         */
        $paths = $response->getExtra('paths');
        if ($paths !== null) {
            $dateWs = date('Y_m_d-H_i_s'); // substr($response->getExtra('parse.dhRecbto'), 0, 19);
            $baseName = str_replace([
                '-',
                'T',
                ':'
            ], [
                '_',
                '-',
                '_'
            ], "{$response->getExtra('parse.cUF')}-{$dateWs}");
            // Save sent file
            $fileSent = "{$paths['request']}/{$baseName}-consStatServCTe.xml";
            file_put_contents($fileSent, $body->getXml());
            // Save response file, if server has processed request succefully
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retConsStatServCTe.xml";
                file_put_contents($fileResponse, $response->getExtra('xml'));
            }
        }
        return $response;
    }

    // /**
    // * Query company registration
    // * Available over NFe webservices, only
    // *
    // * @param string $idLot
    // * @param array $CTe
    // * @return SystemMessage
    // */
    // public function CteConsultaCadastro(string $UF, ?string $cpfCnpj = null, ?string $IE = null): SystemMessage
    // {
    // if ($cpfCnpj === null && $IE === null) {
    // return new SystemMessage("Please fill one document do query.", // Message
    // '1', // System code
    // SystemMessage::MSG_ERROR, // System status code
    // false); // System status
    // }
    // if ((strlen($cpfCnpj) == 11 && ! Variable::cpf()->validate($cpfCnpj)) || (strlen($cpfCnpj) == 14 && ! Variable::cnpj()->validate($cpfCnpj))) {
    // return new SystemMessage("Invalid CTe CPF/CNPJ: {$cpfCnpj}", // Message
    // '1', // System code
    // SystemMessage::MSG_ERROR, // System status code
    // false); // System status
    // }
    // $initialize = $this->prepare(__FUNCTION__);
    // if (! $initialize->isOk()) {
    // return $initialize;
    // }
    // $tag = $value = null;
    // if (strlen($cpfCnpj) == 14) {
    // $tag = 'CNPJ';
    // $value = $cpfCnpj;
    // } elseif (strlen($cpfCnpj) == 11) {
    // $tag = 'CPF';
    // $value = $cpfCnpj;
    // } else {
    // $tag = 'IE';
    // $value = $IE;
    // }
    // $body = [
    // 'ConsCad' => [
    // '@attributes' => [
    // 'xmlns' => $this->urlPortal,
    // 'versao' => $this->urlVersion
    // ],
    // 'infCons' => [
    // 'xServ' => 'CONS-CAD',
    // 'UF' => $UF,
    // $tag => $value
    // ]
    // ]
    // ];
    // $body = Xml::arrayToXml($body, null, true);
    // return $this->send($body);
    // }

    /**
     * Cancellation event
     *
     * @param string $chCTe
     * @param int $nSeqEvent
     * @param string $nProt
     * @param string $xJust
     * @return SystemMessage
     */
    public function cancel(string $chCTe, int $nSeqEvent, string $nProt, string $xJust): SystemMessage
    {
        if (! Variable::nfeAccessKey()->validate($chCTe)) {
            return new SystemMessage("Invalid CTe key: {$chCTe}", // Message
            '1', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
        $initialize = $this->prepare('CteRecepcaoEvento');
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $type = '110111';
        $detEvent = [
            '@attributes' => [
                'versaoEvento' => $this->urlVersion
            ],
            'evCancCTe' => [
                'descEvento' => $this->eventCode[$type],
                'nProt' => $nProt,
                'xJust' => $xJust
            ]
        ];
        $xmlEvent = $this->event($chCTe, $nSeqEvent, $type, $detEvent);
        return $this->send($xmlEvent);
    }

    /**
     * Sending proof of delivery
     *
     * @param string $chCTe
     * @param int $nSeqEvent
     * @param array $evCECTe
     * @return SystemMessage
     */
    public function deliveryReceipt(string $chCTe, int $nSeqEvent, array $evCECTe): SystemMessage
    {
        $initialize = $this->prepare('CteRecepcaoEvento');
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $tpEvent = '110180';
        $detEvent = [
            '@attributes' => [
                'versaoEvento' => $this->urlVersion
            ],
            'evCECTe' => $evCECTe
        ];
        $xmlEvent = $this->event($chCTe, $nSeqEvent, $tpEvent, $detEvent);
        return $this->send($xmlEvent);
    }

    /**
     * Proof of delivery cancellation event
     *
     * @param string $chCTe
     * @param int $nSeqEvent
     * @param array $evCancCECTe
     * @return SystemMessage
     */
    public function deliveryReceiptCancel(string $chCTe, int $nSeqEvent, array $evCancCECTe): SystemMessage
    {
        $initialize = $this->prepare('CteRecepcaoEvento');
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $tpEvent = '110181';
        $detEvent = [
            '@attributes' => [
                'versaoEvento' => $this->urlVersion
            ],
            'evCancCECTe' => $evCancCECTe
        ];
        $xmlEvent = $this->event($chCTe, $nSeqEvent, $tpEvent, $detEvent);
        return $this->send($xmlEvent);
    }

    /**
     * Distribution of documents and information of interest to the CT-e actor
     *
     * @param int $nsu
     * @return SystemMessage
     */
    public function CTeDistribuicaoDFe(int $nsu): SystemMessage
    {
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $body = [
            'distDFeInt' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'tpAmb' => $this->tpAmb,
                'cUFAutor' => $this->cUF,
                'CNPJ' => $this->CNPJ,
                'distNSU' => [
                    'ultNSU' => str_pad((string) $nsu, 15, '0', STR_PAD_LEFT)
                ]
            ]
        ];
        $body = Xml::arrayToXml($body, null, true);
        return $this->send($body, true);
    }

    /**
     * Create event general part
     *
     * @param string $chDFe
     * @param int $nSeqEvent
     * @param string $tipo
     * @param array $detEvent
     * @return string
     */
    protected function event(string $chDFe, int $nSeqEvent, string $type, array $detEvent): Xml
    {
        $body = [
            'eventoCTe' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'infEvento' => [
                    '@attributes' => [
                        'Id' => "ID{$type}{$chDFe}{$nSeqEvent}"
                    ],
                    'cOrgao' => $this->cUF,
                    'tpAmb' => $this->tpAmb,
                    'CNPJ' => $this->CNPJ,
                    'chCTe' => $chDFe,
                    'dhEvento' => date('c'),
                    'tpEvento' => $type,
                    'nSeqEvento' => str_pad((string) $nSeqEvent, 2, '0', STR_PAD_LEFT),
                    'detEvento' => $detEvent
                ]
            ]
        ];
        return new Xml($this->sign(Xml::arrayToXml($body), 'infEvento', 'Id', 'eventoCTe'));
    }
}