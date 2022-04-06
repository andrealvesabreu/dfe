<?php
declare(strict_types = 1);
namespace Inspire\Dfe;

use Inspire\Validator\ {
    Variable,
    XsdSchema
};
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
 * Web Service - CteRecepcao OK
 * Web Service - CteRecepcaoOS
 * Web Service - CteRetRecepcao OK
 * Web Service - CteInutilizacao OK
 * Web Service - CteConsulta OK
 * Web Service - CteStatusServico OK
 * Web Service - CteConsultaCadastro ?
 * Web Service - CteRecepcaoEvento OK
 * Web Service - CTeDistribuicaoDFe OK
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
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/enviCTe_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
        }
        /**
         * Send to webservice
         */
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
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/CTeOS_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
        }
        /**
         * Send to webservice
         */
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
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/consReciCTe_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
        }
        /**
         * Send to webservice
         */
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
                        if (Arrays::get($protCTe, 'infProt.cStat') == '100') {
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
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/inutCTe_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
        }
        /**
         * Send to webservice
         */
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
     * Fully implemented
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
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/consSitCTe_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
        }
        /**
         * Send to webservice
         */
        $response = $this->send($body);
        /**
         * Save files
         *
         * @var array|null $paths
         */
        $paths = $response->getExtra('paths');
        if ($paths !== null) {
            $baseName = "{$chCTe}-" . date('Y_m_d-H_i_s');
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-consSitCTe.xml";
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
                $fileResponse = "{$paths['response']}/{$baseName}-retConsSitCTe.xml";
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
     * Query WS status
     * Fully implemented
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
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/consStatServCTe_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
        }
        /**
         * Send to webservice
         */
        $response = $this->send($body);
        /**
         * Save files
         *
         * @var array|null $paths
         */
        $paths = $response->getExtra('paths');
        if ($paths !== null) {
            $baseName = date('Y_m_d-H_i_s');
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-consStatServCTe.xml";
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
                $fileResponse = "{$paths['response']}/{$baseName}-retConsStatServCTe.xml";
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
     * Create event general part
     * Fully implemented
     *
     * @param string $chDFe
     * @param int $nSeqEvent
     * @param string $tpEvent
     * @param array $detEvent
     * @return string
     */
    protected function event(string $chDFe, int $nSeqEvent, string $tpEvent, array $detEvent): Xml
    {
        $nSeqEvent = str_pad((string) $nSeqEvent, 2, '0', STR_PAD_LEFT);
        $body = [
            'eventoCTe' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'infEvento' => [
                    '@attributes' => [
                        'Id' => "ID{$tpEvent}{$chDFe}{$nSeqEvent}"
                    ],
                    'cOrgao' => $this->cUF,
                    'tpAmb' => $this->tpAmb,
                    'CNPJ' => $this->CNPJ,
                    'chCTe' => $chDFe,
                    'dhEvento' => date('c'),
                    'tpEvento' => $tpEvent,
                    'nSeqEvento' => intval($nSeqEvent),
                    'detEvento' => $detEvent
                ]
            ]
        ];
        return new Xml($this->sign(Xml::arrayToXml($body), 'infEvento', 'Id', 'eventoCTe'));
    }

    /**
     * Document correction event
     * Fully implemented
     *
     * @param string $chCTe
     * @param int $nSeqEvent
     * @param array $infCorrecao
     *            multi dimensional array with the following structure:
     *            [
     *            [
     *            'grupoAlterado' => 'test1',
     *            'campoAlterado' => 'field1',
     *            'valorAlterado' => 'value1',
     *            'nroItemAlterado' => 1
     *            ],
     *            [
     *            'grupoAlterado' => 'test2',
     *            'campoAlterado' => 'field2',
     *            'valorAlterado' => 'value2'
     *            ],
     *            [
     *            'grupoAlterado' => 'test3',
     *            'campoAlterado' => 'field3',
     *            'valorAlterado' => 'value3',
     *            'nroItemAlterado' => 5
     *            ],
     *            ...
     *            ]
     * @return SystemMessage
     */
    public function evCCeCTe(string $chCTe, int $nSeqEvent, array $infCorrecao): SystemMessage
    {
        if (! Variable::nfeAccessKey()->validate($chCTe)) {
            return new SystemMessage("Invalid CTe key: {$chCTe}", // Message
            '0', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
        $initialize = $this->prepare('CteRecepcaoEvento');
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $tpEvent = '110110';
        $detEvent = [
            '@attributes' => [
                'versaoEvento' => $this->urlVersion
            ],
            'evCCeCTe' => [
                'descEvento' => $this->eventCode[$tpEvent]['desc'],
                'infCorrecao' => $infCorrecao,
                'xCondUso' => 'A Carta de Correcao e disciplinada pelo Art. 58-B do CONVENIO/SINIEF 06/89: Fica permitida a utilizacao de carta de correcao, para regularizacao de erro ocorrido na emissao de documentos fiscais relativos a prestacao de servico de transporte, desde que o erro nao esteja relacionado com: I - as variaveis que determinam o valor do imposto tais como: base de calculo, aliquota, diferenca de preco, quantidade, valor da prestacao;II - a correcao de dados cadastrais que implique mudanca do emitente, tomador, remetente ou do destinatario;III - a data de emissao ou de saida.'
            ]
        ];
        $body = $this->event($chCTe, $nSeqEvent, $tpEvent, $detEvent);
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            /**
             * Validate main event structure
             */
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/eventoCTe_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
            /**
             * Validate specific event structure
             */
            XsdSchema::validate(Xml::arrayToXml([
                'evCCeCTe' => $detEvent['evCCeCTe']
            ]), "{$this->schemaPath}/evCCeCTe_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
        }
        /**
         * Send to webservice
         */
        $response = $this->send($body);
        /**
         * Save files
         *
         * @var array|null $paths
         */
        $paths = $response->getExtra('paths');
        if ($paths !== null) {
            $baseName = "{$chCTe}-{$tpEvent}-{$nSeqEvent}";
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-eventoCTe.xml";
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
                $fileResponse = "{$paths['response']}/{$baseName}-retEventoCTe.xml";
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
                if ($response->getExtra('parse.infEvento.cStat') == 135) {
                    $CCe = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><procEventoCTe versao=\"{$response->getExtra('parse.infEvento.versao')}\">" . Xml::clearXmlDeclaration($body->getXml()) . Xml::clearXmlDeclaration($response->getExtra('data.received')) . "</procEventoCTe>";
                    $fileCC = "{$paths['document']}/{$baseName}-procEventoCTe.xml";
                    $response->addExtra([
                        'parse.procXML' => $CCe,
                        'parse.pathXML' => $fileCC
                    ]);
                    file_put_contents($fileCC, $CCe);
                }
            }
        }
        return $response;
    }

    /**
     * Cancellation event
     * Fully implemented
     *
     * @param string $chCTe
     * @param int $nSeqEvent
     * @param string $nProt
     * @param string $xJust
     * @return SystemMessage
     */
    public function evCancCTe(string $chCTe, int $nSeqEvent, string $nProt, string $xJust): SystemMessage
    {
        if (! Variable::nfeAccessKey()->validate($chCTe)) {
            return new SystemMessage("Invalid CTe key: {$chCTe}", // Message
            '0', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
        $initialize = $this->prepare('CteRecepcaoEvento');
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $tpEvent = '110111';
        $detEvent = [
            '@attributes' => [
                'versaoEvento' => $this->urlVersion
            ],
            'evCancCTe' => [
                'descEvento' => $this->eventCode[$tpEvent]['desc'],
                'nProt' => $nProt,
                'xJust' => $xJust
            ]
        ];
        $body = $this->event($chCTe, $nSeqEvent, $tpEvent, $detEvent);
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            /**
             * Validate main event structure
             */
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/eventoCTe_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
            /**
             * Validate specific event structure
             */
            XsdSchema::validate(Xml::arrayToXml([
                'evCancCTe' => $detEvent['evCancCTe']
            ]), "{$this->schemaPath}/evCancCTe_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
        }
        /**
         * Send to webservice
         */
        $response = $this->send($body);
        /**
         * Save files
         *
         * @var array|null $paths
         */
        $paths = $response->getExtra('paths');
        if ($paths !== null) {
            $baseName = "{$chCTe}-{$tpEvent}-{$nSeqEvent}";
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-eventoCTe.xml";
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
                $fileResponse = "{$paths['response']}/{$baseName}-retEventoCTe.xml";
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
                if ($response->getExtra('parse.infEvento.cStat') == 135) {
                    $canc = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><procEventoCTe versao=\"{$response->getExtra('parse.infEvento.versao')}\">" . Xml::clearXmlDeclaration($body->getXml()) . Xml::clearXmlDeclaration($response->getExtra('data.received')) . "</procEventoCTe>";
                    $fileCanc = "{$paths['document']}/{$baseName}-procEventoCTe.xml";
                    $response->addExtra([
                        'parse.procXML' => $canc,
                        'parse.pathXML' => $fileCanc
                    ]);
                    file_put_contents($fileCanc, $canc);
                }
            }
        }
        return $response;
    }

    /**
     * Event intended to meet requests for issuance in CT-e contingency.
     *
     * @param string $chCTe
     * @param int $nSeqEvent
     * @param string $nProt
     * @param string $xJust
     * @return SystemMessage
     */
    // public function evEPECCTe(string $chCTe, int $nSeqEvent, string $nProt, string $xJust): SystemMessage
    // {
    // if (! Variable::nfeAccessKey()->validate($chCTe)) {
    // return new SystemMessage("Invalid CTe key: {$chCTe}", // Message
    // '0', // System code
    // SystemMessage::MSG_ERROR, // System status code
    // false); // System status
    // }
    // $initialize = $this->prepare('CteRecepcaoEvento');
    // if (! $initialize->isOk()) {
    // return $initialize;
    // }
    // $tpEvent = '110110';
    // $detEvent = [
    // '@attributes' => [
    // 'versaoEvento' => $this->urlVersion
    // ],
    // 'evEPECCTe' => [
    // 'descEvento' => $this->eventCode[$tpEvent]['desc'],
    // 'nProt' => $nProt,
    // 'xJust' => $xJust
    // ]
    // ];
    // }

    /**
     * Event designed to link information on services provided to CT-e multimodal.
     * Note that, if a CT-e is issued that is already linked to the multimodal CT-e, it is not necessary to inform it for this event.
     * Fully implemented
     *
     * @param string $chCTe
     * @param int $nSeqEvent
     * @param string $nProt
     * @param string $xJust
     * @return SystemMessage
     */
    public function evRegMultimodal(string $chCTe, int $nSeqEvent, string $xRegistro, string $nDoc): SystemMessage
    {
        if (! Variable::nfeAccessKey()->validate($chCTe)) {
            return new SystemMessage("Invalid CTe key: {$chCTe}", // Message
            '0', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
        $initialize = $this->prepare('CteRecepcaoEvento');
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $tpEvent = '110160';
        $detEvent = [
            '@attributes' => [
                'versaoEvento' => $this->urlVersion
            ],
            'evRegMultimodal' => [
                'descEvento' => $this->eventCode[$tpEvent]['desc'],
                'xRegistro' => $xRegistro,
                'nDoc' => $nDoc
            ]
        ];
        $body = $this->event($chCTe, $nSeqEvent, $tpEvent, $detEvent);
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            /**
             * Validate main event structure
             */
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/eventoCTe_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
            /**
             * Validate specific event structure
             */
            XsdSchema::validate(Xml::arrayToXml([
                'evRegMultimodal' => $detEvent['evRegMultimodal']
            ]), "{$this->schemaPath}/evRegMultimodal_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
        }
        /**
         * Send to webservice
         */
        $response = $this->send($body);
        /**
         * Save files
         *
         * @var array|null $paths
         */
        $paths = $response->getExtra('paths');
        if ($paths !== null) {
            $baseName = "{$chCTe}-{$tpEvent}-{$nSeqEvent}";
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-eventoCTe.xml";
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
                $fileResponse = "{$paths['response']}/{$baseName}-retEventoCTe.xml";
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
                if ($response->getExtra('parse.infEvento.cStat') == 135) {
                    $canc = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><procEventoCTe versao=\"{$response->getExtra('parse.infEvento.versao')}\">" . Xml::clearXmlDeclaration($body->getXml()) . Xml::clearXmlDeclaration($response->getExtra('data.received')) . "</procEventoCTe>";
                    $fileCanc = "{$paths['document']}/{$baseName}-procEventoCTe.xml";
                    $response->addExtra([
                        'parse.procXML' => $canc,
                        'parse.pathXML' => $fileCanc
                    ]);
                    file_put_contents($fileCanc, $canc);
                }
            }
        }
        return $response;
    }

    /**
     * Sending proof of delivery
     * Fully implemented
     *
     * @param string $chCTe
     * @param int $nSeqEvent
     * @param array $evCECTe
     *            array with the following structure:
     *            [
     *            'nProt' => '',
     *            'dhEntrega' => '',
     *            'nDoc' => '',
     *            'xNome' => 'Test Name',
     *            'latitude' => null,
     *            'longitude' => null,
     *            'chNFe' => [],
     *            'base64ce' => 'base64encoded'
     *            ]
     * @return SystemMessage
     */
    public function evCECTe(string $chCTe, int $nSeqEvent, array $evCECTe): SystemMessage
    {
        if (! Variable::nfeAccessKey()->validate($chCTe)) {
            return new SystemMessage("Invalid CTe key: {$chCTe}", // Message
            '0', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
        $initialize = $this->prepare('CteRecepcaoEvento');
        if (! $initialize->isOk()) {
            return $initialize;
        }
        /**
         * Check if there is a base64ce value
         */
        if (! isset($evCECTe['base64ce']) || // If not exists
        ! Variable::base64()->validate($evCECTe['base64ce'])) { // If is not a valid base64 string
            return new SystemMessage("Invalid base64 string for base64ce", // Message
            '0', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }

        $tpEvent = '110180';
        $infEntrega = [];
        if (isset($evCECTe['chNFe']) && // Has info
        is_array($evCECTe['chNFe']) && // Is array
        ! empty($evCECTe['chNFe'])) {
            foreach ($evCECTe['chNFe'] as $chNFe) {
                if (! Variable::nfeAccessKey()->validate($chNFe)) {
                    return new SystemMessage("Invalid NFe key: {$chNFe}", // Message
                    '0', // System code
                    SystemMessage::MSG_ERROR, // System status code
                    false); // System status
                }
                $infEntrega[] = [
                    'chNFe' => $chNFe
                ];
            }
        }
        /**
         * Hash (SHA1) no formato Base64 resultante da concatenação: Chave de acesso do CT-e +
         * Base64 da imagem capturada da entrega (Exemplo: imagem capturada da assinatura eletrônica, digital do recebedor, foto, etc)
         * NT 2019_001_Comprovante de Entrega
         */
        $hashEntrega = base64_encode(pack('H*', hash('sha1', "{$chCTe}{$evCECTe['base64ce']}")));

        $aEvCECTe = [
            'descEvento' => $this->eventCode[$tpEvent]['desc'],
            'nProt' => $evCECTe['nProt'] ?? null,
            'dhEntrega' => $evCECTe['dhEntrega'] ?? null,
            'nDoc' => $evCECTe['nDoc'] ?? null,
            'xNome' => $evCECTe['xNome'] ?? null,
            'latitude' => $evCECTe['latitude'] ?? null,
            'longitude' => $evCECTe['longitude'] ?? null,
            'hashEntrega' => $hashEntrega,
            'dhHashEntrega' => $evCECTe['dhHashEntrega'] ?? date('c', strtotime('-5 seconds')),
            'infEntrega' => $infEntrega
        ];
        if ($aEvCECTe['latitude'] === null || $aEvCECTe['longitude'] === null) {
            unset($aEvCECTe['latitude'], $aEvCECTe['longitude']);
        }
        if (empty($aEvCECTe['infEntrega'])) {
            unset($aEvCECTe['infEntrega']);
        }
        $detEvent = [
            '@attributes' => [
                'versaoEvento' => $this->urlVersion
            ],
            'evCECTe' => $aEvCECTe
        ];
        $body = $this->event($chCTe, $nSeqEvent, $tpEvent, $detEvent);
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            /**
             * Validate main event structure
             */
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/eventoCTe_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
            /**
             * Validate specific event structure
             */
            XsdSchema::validate(Xml::arrayToXml([
                'evCECTe' => $detEvent['evCECTe']
            ]), "{$this->schemaPath}/evCECTe_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
        }
        /**
         * Send to webservice
         */
        $response = $this->send($body);
        /**
         * Save files
         *
         * @var array|null $paths
         */
        $paths = $response->getExtra('paths');
        if ($paths !== null) {
            $baseName = "{$chCTe}-{$tpEvent}-{$nSeqEvent}";
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-eventoCTe.xml";
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
                $fileResponse = "{$paths['response']}/{$baseName}-retEventoCTe.xml";
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
                if ($response->getExtra('parse.infEvento.cStat') == 135) {
                    $canc = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><procEventoCTe versao=\"{$response->getExtra('parse.infEvento.versao')}\">" . Xml::clearXmlDeclaration($body->getXml()) . Xml::clearXmlDeclaration($response->getExtra('data.received')) . "</procEventoCTe>";
                    $fileCanc = "{$paths['document']}/{$baseName}-procEventoCTe.xml";
                    $response->addExtra([
                        'parse.procXML' => $canc,
                        'parse.pathXML' => $fileCanc
                    ]);
                    file_put_contents($fileCanc, $canc);
                }
            }
        }
        return $response;
    }

    /**
     *
     * Proof of delivery cancellation event
     * Fully implemented
     *
     * @param string $chCTe
     * @param int $nSeqEvent
     * @param string $nProt
     * @param string $nProtCE
     * @return SystemMessage
     */
    public function evCancCECTe(string $chCTe, int $nSeqEvent, string $nProt, string $nProtCE): SystemMessage
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
            'evCancCECTe' => [
                'descEvento' => $this->eventCode[$tpEvent]['desc'],
                'nProt' => $nProt,
                'nProtCE' => $nProtCE
            ]
        ];
        $body = $this->event($chCTe, $nSeqEvent, $tpEvent, $detEvent);
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            /**
             * Validate main event structure
             */
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/eventoCTe_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
            /**
             * Validate specific event structure
             */
            XsdSchema::validate(Xml::arrayToXml([
                'evCancCECTe' => $detEvent['evCancCECTe']
            ]), "{$this->schemaPath}/evCancCECTe_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
        }
        /**
         * Send to webservice
         */
        $response = $this->send($body);
        /**
         * Save files
         *
         * @var array|null $paths
         */
        $paths = $response->getExtra('paths');
        if ($paths !== null) {
            $baseName = "{$chCTe}-{$tpEvent}-{$nSeqEvent}";
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-eventoCTe.xml";
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
                $fileResponse = "{$paths['response']}/{$baseName}-retEventoCTe.xml";
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
                if ($response->getExtra('parse.infEvento.cStat') == 135) {
                    $canc = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><procEventoCTe versao=\"{$response->getExtra('parse.infEvento.versao')}\">" . Xml::clearXmlDeclaration($body->getXml()) . Xml::clearXmlDeclaration($response->getExtra('data.received')) . "</procEventoCTe>";
                    $fileCanc = "{$paths['document']}/{$baseName}-procEventoCTe.xml";
                    $response->addExtra([
                        'parse.procXML' => $canc,
                        'parse.pathXML' => $fileCanc
                    ]);
                    file_put_contents($fileCanc, $canc);
                }
            }
        }
        return $response;
    }

    /**
     *
     * Performance event in disagreement
     * Fully implemented
     *
     * @param string $chCTe
     * @param int $nSeqEvent
     * @param string $xObs
     * @return SystemMessage
     */
    public function evPrestDesacordo(string $chCTe, int $nSeqEvent, string $xObs): SystemMessage
    {
        $initialize = $this->prepare('CteRecepcaoEvento');
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $tpEvent = '610110';
        $detEvent = [
            '@attributes' => [
                'versaoEvento' => $this->urlVersion
            ],
            'evPrestDesacordo' => [
                'descEvento' => $this->eventCode[$tpEvent]['desc'],
                'indDesacordoOper' => 1,
                'xObs' => $xObs
            ]
        ];
        $body = $this->event($chCTe, $nSeqEvent, $tpEvent, $detEvent);
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            /**
             * Validate main event structure
             */
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/eventoCTe_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
            /**
             * Validate specific event structure
             */
            XsdSchema::validate(Xml::arrayToXml([
                'evPrestDesacordo' => $detEvent['evPrestDesacordo']
            ]), "{$this->schemaPath}/evPrestDesacordo_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
        }
        /**
         * Send to webservice
         */
        $response = $this->send($body);
        /**
         * Save files
         *
         * @var array|null $paths
         */
        $paths = $response->getExtra('paths');
        if ($paths !== null) {
            $baseName = "{$chCTe}-{$tpEvent}-{$nSeqEvent}";
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-eventoCTe.xml";
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
                $fileResponse = "{$paths['response']}/{$baseName}-retEventoCTe.xml";
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
                if ($response->getExtra('parse.infEvento.cStat') == 135) {
                    $canc = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><procEventoCTe versao=\"{$response->getExtra('parse.infEvento.versao')}\">" . Xml::clearXmlDeclaration($body->getXml()) . Xml::clearXmlDeclaration($response->getExtra('data.received')) . "</procEventoCTe>";
                    $fileCanc = "{$paths['document']}/{$baseName}-procEventoCTe.xml";
                    $response->addExtra([
                        'parse.procXML' => $canc,
                        'parse.pathXML' => $fileCanc
                    ]);
                    file_put_contents($fileCanc, $canc);
                }
            }
        }
        return $response;
    }

    /**
     *
     * Distribution of documents and information of interest to the CT-e actor
     * Fully implemented
     *
     * @param int $ultNSU
     * @param int $NSU
     * @return SystemMessage
     */
    public function CTeDistribuicaoDFe(?int $ultNSU = null, ?int $NSU = null): SystemMessage
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
                'CNPJ' => $this->CNPJ
            ]
        ];
        $pathPart = '';
        if ($ultNSU !== null) {
            $pathPart = str_pad((string) $ultNSU, 15, '0', STR_PAD_LEFT);
            $tag = 'distNSU';
            $value = [
                'ultNSU' => $pathPart
            ];
        } else if ($NSU !== null) {
            $pathPart = str_pad((string) $NSU, 15, '0', STR_PAD_LEFT);
            $tag = 'consNSU';
            $value = [
                'NSU' => $pathPart
            ];
        } else {
            return new SystemMessage('You must provide last NSU, an NSU or a valid CTe', // Message
            '1', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
        Arrays::set($body, "distDFeInt.{$tag}", $value);
        $body = Xml::arrayToXml($body, null, true);
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/distDFeInt_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
        }
        /**
         * Send to webservice
         */
        $response = $this->send($body, true);
        /**
         * Save files
         *
         * @var array|null $paths
         */
        $paths = $response->getExtra('paths');
        if ($paths !== null) {
            $baseName = "{$this->CNPJ}_{$pathPart}_{$response->getExtra('parse.cUF')}_" . date('Y_m_d-H_i_s');
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-distDFeInt.xml";
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
                $fileResponse = "{$paths['response']}/{$baseName}-retDistDFeInt.xml";
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
}