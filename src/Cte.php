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
     * Cargo Transport CT-e Reception Service
     * Send a lot to webservice
     * Fully implemented
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
                'idLote' => $idLot,
                'xml' => '__xml__'
            ]
        ];
        $noXmlDeclaration = Xml::clearXmlDeclaration($CTe);
        $body = new Xml(str_replace('<xml>__xml__</xml>', // Replace special mark
        implode('', $noXmlDeclaration), // Set documents
        Xml::arrayToXml($body))); // Convert array to XML
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/enviCTe_v{$this->version}.xsd", $this->urlPortal);
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
            /**
             * Save all signed files
             * Multiple versions of every CTe can be sent.
             * Just append a new line if file already exists
             */
            $signedDocsPaths = [];
            foreach ($noXmlDeclaration as $signed) {
                $cteKey = [];
                if (preg_match('/<infCte Id=\"CTe(.+?)"/', $signed, $cteKey) && // If a match of CTe key was found
                Variable::nfeAccessKey()->validate($cteKey[1])) { // If matched key is valid
                    $signedDocPath = "{$paths['document']}/{$cteKey[1]}.xml";
                    $signedDocsPaths[] = $signedDocPath;
                    file_put_contents($signedDocPath, $signed . PHP_EOL, FILE_APPEND);
                }
            }
            /**
             * Update document path to include all signed files as array
             */
            $response->addExtra([
                'paths.document' => $signedDocsPaths
            ]);
            $baseName = "{$idLot}-" . date('Y_m_d-H_i_s');
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-enviCTe.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->addExtra([
                'paths.request' => $fileSent
            ]);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retEnviCTe.xml";
                file_put_contents($fileResponse, $response->getExtra('data.received'));
                /**
                 * Update response path to include file name
                 */
                $response->addExtra([
                    'paths.response' => $fileResponse
                ]);
            }
        }
        return $response;
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
                'xml' => '__xml__'
            ]
        ];
        $noXmlDeclaration = Xml::clearXmlDeclaration($CTe);
        $body = new Xml(str_replace('<xml>__xml__</xml>', // Replace special mark
        implode('', $noXmlDeclaration), // Set documents
        Xml::arrayToXml($body))); // Convert array to XML
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/CTeOS_v{$this->version}.xsd", $this->urlPortal);
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
            /**
             * Save all signed files
             * Multiple versions of every CTe can be sent.
             * Just append a new line if file already exists
             */
            $signedDocsPaths = [];
            foreach ($noXmlDeclaration as $signed) {
                $cteKey = [];
                if (preg_match('/<infCte Id=\"CTe(.+?)"/', $signed, $cteKey) && // If a match of CTe key was found
                Variable::nfeAccessKey()->validate($cteKey[1])) { // If matched key is valid
                    $signedDocPath = "{$paths['document']}/{$cteKey[1]}.xml";
                    $signedDocsPaths[] = $signedDocPath;
                    file_put_contents($signedDocPath, $signed . PHP_EOL, FILE_APPEND);
                }
            }
            /**
             * Update document path to include all signed files as array
             */
            $response->addExtra([
                'paths.document' => $signedDocsPaths
            ]);
            $baseName = "{$idLot}-" . date('Y_m_d-H_i_s');
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-enviCTe.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->addExtra([
                'paths.request' => $fileSent
            ]);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retEnviCTe.xml";
                file_put_contents($fileResponse, $response->getExtra('data.received'));
                /**
                 * Update response path to include file name
                 */
                $response->addExtra([
                    'paths.response' => $fileResponse
                ]);
            }
        }
        return $response;
    }

    /**
     * Query authorization status
     * Fully implemented
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
            $baseName = "{$rec}-" . date('Y_m_d-H_i_s');
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-consReciCTe.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->addExtra([
                'paths.request' => $fileSent
            ]);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retConsReciCTe.xml";
                file_put_contents($fileResponse, $response->getExtra('data.received'));
                /**
                 * Update response path to include file name
                 */
                $response->addExtra([
                    'paths.response' => $fileResponse
                ]);
                /**
                 * If webservice provided a response
                 */
                if ($response->getExtra('parse.bStat')) {
                    foreach ($response->getExtra('parse.protCTe') as $cteKey => $protCTe) {
                        /**
                         * Load signed file in an array
                         *
                         * @var array $signedVersions
                         */
                        $signedFile = "{$paths['signed']}/{$protCTe['infProt']['chCTe']}.xml";
                        $signedVersions = file($signedFile);
                        $digVal = "<DigestValue>{$protCTe['infProt']['digVal']}</DigestValue>";
                        foreach ($signedVersions as $sv) {
                            if (strpos($sv, $digVal)) {
                                /**
                                 * Create a Dom object with signed file
                                 *
                                 * @var \DOMDocument $signed
                                 */
                                $signed = new \DOMDocument('1.0', 'utf-8');
                                $signed->formatOutput = false;
                                $signed->loadXml(rtrim($sv, PHP_EOL));
                                /**
                                 * Create a Dom object with protocol
                                 *
                                 * @var \DOMDocument $signed
                                 */
                                $prot = new \DOMDocument('1.0', 'utf-8');
                                $prot->formatOutput = false;
                                $prot->loadXml(rtrim($protCTe['xml'], PHP_EOL));

                                /**
                                 * Create a new Dom object to add signed and protocol to a new file
                                 *
                                 * @var \DOMDocument $procCte
                                 */
                                $procCte = new \DOMDocument('1.0', 'utf-8');
                                $procCte->formatOutput = false;
                                $procCte->preserveWhiteSpace = false;
                                /**
                                 * Tag cteProc
                                 *
                                 * @var \DOMElement $cteProc
                                 */
                                $cteProc = $procCte->createElement('cteProc');
                                $procCte->appendChild($cteProc);
                                /**
                                 * Attribute versao
                                 *
                                 * @var \DOMElement $versao
                                 */
                                $versao = $cteProc->appendChild($procCte->createAttribute('versao'));
                                $versao->appendChild($procCte->createTextNode($protCTe['versao']));
                                /**
                                 * Attribute xmlns
                                 *
                                 * @var \DOMElement $xmlns
                                 */
                                $xmlns = $cteProc->appendChild($procCte->createAttribute('xmlns'));
                                $xmlns->appendChild($procCte->createTextNode('http://www.portalfiscal.inf.br/cte'));
                                /**
                                 * Append CTe tag
                                 *
                                 * @var \DOMElement $CTe
                                 */
                                $node = $procCte->importNode($signed->getElementsByTagName('CTe')
                                    ->item(0), true);
                                $cteProc->appendChild($node);
                                /**
                                 * Append protCTe tag
                                 *
                                 * @var \DOMElement $protCTe
                                 */
                                $nodep = $procCte->importNode($prot->getElementsByTagName('protCTe')
                                    ->item(0), true);
                                $cteProc->appendChild($nodep);
                                /**
                                 * Normalize XML
                                 *
                                 * @var string $procXML
                                 */
                                $procXML = str_replace('xmlns="http://www.portalfiscal.inf.br/cte" xmlns="http://www.w3.org/2000/09/xmldsig#"', 'xmlns="http://www.portalfiscal.inf.br/cte"', //
                                str_replace(array(
                                    ' standalone="no"',
                                    'default:',
                                    ':default',
                                    "\n",
                                    "\r",
                                    "\t"
                                ), '', $procCte->saveXML()));
                                $procCTeFile = "{$paths['document']}/{$protCTe['infProt']['chCTe']}-procCTe.xml";
                                file_put_contents($procCTeFile, $procXML);
                                $protCTe['procXML'] = $procXML;
                                $protCTe['pathXML'] = $procCTeFile;
                                $response->addExtra([
                                    "parse.protCTe.{$cteKey}" => $protCTe
                                ]);
                                /**
                                 * Set the right document on signed file to skip invalid data
                                 */
                                if (count($signedVersions) > 0) {
                                    file_put_contents($signedFile, $sv);
                                }
                                break;
                            }
                        }
                    }
                }
            }
        }
        return $response;
    }

    /**
     * Send request to disable the number
     * Fully implemented
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
        $ID = 'ID' . $this->cUF . $this->CNPJ . '57' . str_pad((string) $nSerie, 3, '0', STR_PAD_LEFT) . str_pad((string) $nIni, 9, '0', STR_PAD_LEFT) . str_pad((string) $nFin, 9, '0', STR_PAD_LEFT);
        $body = [
            'inutCTe' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'infInut' => [
                    '@attributes' => [
                        'Id' => $ID
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
        $body = new Xml($this->sign(Xml::arrayToXml($body), 'infInut', 'Id', 'inutCTe'));
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/inutCTe_v{$this->version}.xsd", $this->urlPortal);
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
            $baseName = "{$ID}-" . date('Y_m_d-H_i_s');
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-inutCTe.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->addExtra([
                'paths.request' => $fileSent
            ]);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retInutCTe.xml";
                file_put_contents($fileResponse, $response->getExtra('data.received'));
                /**
                 * Update response path to include file name
                 */
                $response->addExtra([
                    'paths.response' => $fileResponse
                ]);
                /**
                 * Save protocol
                 */
                if ($response->getExtra('parse.infInut.cStat') == 102) {
                    // code 682 could be used too, when number is already invalidated
                    $inut = "<procInutCTe versao=\"{$response->getExtra('parse.infInut.versao')}\">" . Xml::clearXmlDeclaration($body->getXml()) . Xml::clearXmlDeclaration($response->getExtra('data.received')) . "</procInutCTe>";
                    $fileInut = "{$paths['document']}/{$response->getExtra('parse.infInut.nProt')}-procInutCTe.xml";
                    $response->addExtra([
                        'parse.procXML' => $inut,
                        'parse.pathXML' => $fileInut
                    ]);
                    file_put_contents($fileInut, $inut);
                }
            }
        }
        return $response;
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
                'descEvento' => $this->eventCode[$type]['desc'],
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