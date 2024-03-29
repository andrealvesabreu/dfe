<?php
declare(strict_types = 1);
namespace Inspire\Dfe;

use Inspire\Support\Message\System\SystemMessage;
use Inspire\Validator\Variable;
use Inspire\Support\Xml\Xml;
use Inspire\Support\Arrays;
use Inspire\Validator\XsdSchema;

/**
 * Description of Mdfe
 *
 * @author aalves
 */

/**
 * Class implements methods from SEFAZ
 *
 * Web Service - MDFeRecepcao
 * Web Service - MDFeRecepcaoSinc
 * Web Service - MDFeRetRecepcao OK
 * Web Service - MDFeConsulta OK
 * Web Service - MDFeConsNaoEnc OK
 * Web Service - MDFeStatusServico OK
 * Web Service - MDFeRecepcaoEvento
 * Web Service - MDFeDistribuicaoDFe OK
 *
 * @author aalves
 */
class Mdfe extends Dfe
{

    /**
     *
     * @param array $config
     * @param Certificate $cert
     */
    public function __construct(array $config, Certificate $cert)
    {
        $config['mod'] = '58';
        parent::__construct($config, $cert);
    }

    /**
     * Send a lot to webservice
     *
     * @param string $idLot
     * @param array $MDFe
     * @return SystemMessage
     */
    public function MDFeRecepcao(string $idLot, array $MDFe): SystemMessage
    {
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $body = [
            'enviMDFe' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'idLote' => str_pad($idLot, 15, '0', STR_PAD_LEFT),
                'xml' => '__xml__'
            ]
        ];
        $noXmlDeclaration = Xml::clearXmlDeclaration($MDFe);
        $body = new Xml(str_replace('<xml>__xml__</xml>', // Replace special mark
        implode('', $noXmlDeclaration), // Set documents
        Xml::arrayToXml($body))); // Convert array to XML
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/enviMDFe_v{$this->xsdVersion}.xsd", $this->urlPortal);
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
             * Multiple versions of every MDFe can be sent.
             * Just append a new line if file already exists
             */
            $signedDocsPaths = [];
            foreach ($noXmlDeclaration as $signed) {
                $mdfeKey = [];
                if (preg_match('/<infMDFe Id=\"MDFe(.+?)"/', $signed, $mdfeKey) && // If a match of MDFe key was found
                Variable::nfeAccessKey()->validate($mdfeKey[1])) { // If matched key is valid
                    $signedDocPath = "{$paths['document']}/{$mdfeKey[1]}.xml";
                    $signedDocsPaths[] = $signedDocPath;
                    file_put_contents($signedDocPath, $signed . PHP_EOL, FILE_APPEND);
                }
            }
            /**
             * Update document path to include all signed files as array
             */
            $response->setExtra('paths.document', $signedDocsPaths);
            $baseName = "{$idLot}-" . date('Y_m_d-H_i_s');
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-enviMDFe.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->setExtra('paths.request', $fileSent);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retEnviMDFe.xml";
                file_put_contents($fileResponse, $response->getExtra('data.received'));
                /**
                 * Update response path to include file name
                 */
                $response->setExtra('paths.response', $fileResponse);
            }
        }
        return $response;
    }

    /**
     * Send a lot to webservice
     *
     * @param string $idLot
     * @param array $MDFe
     * @return SystemMessage
     */
    public function MDFeRecepcaoSinc(string $idLot, array $MDFe): SystemMessage
    {
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $body = [
            'enviMDFe' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'idLote' => str_pad($idLot, 15, '0', STR_PAD_LEFT),
                'xml' => '__xml__'
            ]
        ];
        $body = new Xml(str_replace('<xml>__xml__</xml>', // Replace special mark
        implode('', Xml::clearXmlDeclaration($MDFe)), // Set documents
        Xml::arrayToXml($body))); // Convert array to XML
        return $this->send($body);
    }

    /**
     * Query authorization status
     * Fully implemented
     *
     * @param string $rec
     * @return SystemMessage
     */
    public function MDFeRetRecepcao(string $rec): SystemMessage
    {
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $body = [
            'consReciMDFe' => [
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
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/consReciMDFe_v{$this->xsdVersion}.xsd", $this->urlPortal);
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
            $fileSent = "{$paths['request']}/{$baseName}-consReciMDFe.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->setExtra('paths.request', $fileSent);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retConsReciMDFe.xml";
                file_put_contents($fileResponse, $response->getExtra('data.received'));
                /**
                 * Update response path to include file name
                 */
                $response->setExtra('paths.response', $fileResponse);
                /**
                 * If webservice provided a response
                 */
                if ($response->getExtra('parse.bStat')) {
                    foreach ($response->getExtra('parse.protMDFe') as $cteKey => $protMDFe) {
                        if (Arrays::get($protMDFe, 'infProt.cStat') == '100') {
                            /**
                             * Load signed file in an array
                             *
                             * @var array $signedVersions
                             */
                            $signedFile = "{$paths['signed']}/{$protMDFe['infProt']['chMDFe']}.xml";
                            $signedVersions = file($signedFile);
                            $digVal = "<DigestValue>{$protMDFe['infProt']['digVal']}</DigestValue>";
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
                                    $prot->loadXml(rtrim($protMDFe['xml'], PHP_EOL));

                                    /**
                                     * Create a new Dom object to add signed and protocol to a new file
                                     *
                                     * @var \DOMDocument $procMDFe
                                     */
                                    $procMDFe = new \DOMDocument('1.0', 'utf-8');
                                    $procMDFe->formatOutput = false;
                                    $procMDFe->preserveWhiteSpace = false;
                                    /**
                                     * Tag mdfeProc
                                     *
                                     * @var \DOMElement $mdfeProc
                                     */
                                    $mdfeProc = $procMDFe->createElement('mdfeProc');
                                    $procMDFe->appendChild($mdfeProc);
                                    /**
                                     * Attribute versao
                                     *
                                     * @var \DOMElement $versao
                                     */
                                    $versao = $mdfeProc->appendChild($procMDFe->createAttribute('versao'));
                                    $versao->appendChild($procMDFe->createTextNode($protMDFe['versao']));
                                    /**
                                     * Attribute xmlns
                                     *
                                     * @var \DOMElement $xmlns
                                     */
                                    $xmlns = $mdfeProc->appendChild($procMDFe->createAttribute('xmlns'));
                                    $xmlns->appendChild($procMDFe->createTextNode('http://www.portalfiscal.inf.br/mdfe'));
                                    /**
                                     * Append MDFe tag
                                     *
                                     * @var \DOMElement $MDFe
                                     */
                                    $node = $procMDFe->importNode($signed->getElementsByTagName('MDFe')
                                        ->item(0), true);
                                    $mdfeProc->appendChild($node);
                                    /**
                                     * Append protMDFe tag
                                     *
                                     * @var \DOMElement $protMDFe
                                     */
                                    $nodep = $procMDFe->importNode($prot->getElementsByTagName('protMDFe')
                                        ->item(0), true);
                                    $mdfeProc->appendChild($nodep);
                                    /**
                                     * Normalize XML
                                     *
                                     * @var string $procXML
                                     */
                                    $procXML = str_replace('xmlns="http://www.portalfiscal.inf.br/mdfe" xmlns="http://www.w3.org/2000/09/xmldsig#"', 'xmlns="http://www.portalfiscal.inf.br/mdfe"', //
                                    str_replace(array(
                                        ' standalone="no"',
                                        'default:',
                                        ':default',
                                        "\n",
                                        "\r",
                                        "\t"
                                    ), '', $procMDFe->saveXML()));
                                    $procMDFeFile = "{$paths['document']}/{$protMDFe['infProt']['chMDFe']}-procMDFe.xml";
                                    file_put_contents($procMDFeFile, $procXML);
                                    $protMDFe['procXML'] = $procXML;
                                    $protMDFe['pathXML'] = $procMDFeFile;
                                    $response->setExtra("parse.protMDFe.{$cteKey}", $protMDFe);
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
     * Query document on webservice
     * Fully implemented
     *
     * @param string $chMDFe
     * @return \Inspire\Support\Message\System\SystemMessage
     */
    public function MDFeConsulta(string $chMDFe): SystemMessage
    {
        if (! Variable::nfeAccessKey()->validate($chMDFe)) {
            return new SystemMessage("Invalid MDFe key: {$chMDFe}", // Message
            '1', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $body = [
            'consSitMDFe' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'tpAmb' => $this->tpAmb,
                'xServ' => 'CONSULTAR',
                'chMDFe' => $chMDFe
            ]
        ];
        $body = Xml::arrayToXml($body, null, true);
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/consSitMDFe_v{$this->xsdVersion}.xsd", $this->urlPortal);
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
            $baseName = "{$chMDFe}-" . date('Y_m_d-H_i_s');
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-consSitMDFe.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->setExtra('paths.request', $fileSent);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retConsSitMDFe.xml";
                file_put_contents($fileResponse, $response->getExtra('data.received'));
                /**
                 * Update response path to include file name
                 */
                $response->setExtra('paths.response', $fileResponse);
            }
        }
        return $response;
    }

    /**
     * Query document on webservice
     * Fully implemented
     *
     * @param string $chMDFe
     * @return \Inspire\Support\Message\System\SystemMessage
     */
    public function MDFeConsNaoEnc(?string $doc): SystemMessage
    {
        if ($doc === null) {
            $doc = $this->CNPJ;
        }
        if ((strlen($doc) != 11 && strlen($doc) != 14) || // Length validation
        (strlen($doc) == 11 && ! Variable::cpf()->validate($doc)) || // CPF validation
        (strlen($doc) == 14 && ! Variable::cnpj()->validate($doc))) // CNPJ validation
        {
            return new SystemMessage("Invalid CPF/CNPJ: {$doc}", // Message
            '1', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $body = [
            'consMDFeNaoEnc' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'tpAmb' => $this->tpAmb,
                'xServ' => 'CONSULTAR NÃO ENCERRADOS'
            ]
        ];
        $tag = strlen($doc) == 11 ? 'CPF' : 'CNPJ';
        Arrays::set($body, "consMDFeNaoEnc.{$tag}", $doc);
        $body = Xml::arrayToXml($body, null, true);
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/consMDFeNaoEnc_v{$this->xsdVersion}.xsd", $this->urlPortal);
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
            $baseName = "{$response->getExtra('parse.cUF')}_{$this->CNPJ}_" . date('Y_m_d-H_i_s');
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-consMDFeNaoEnc.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->setExtra('paths.request', $fileSent);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retConsMDFeNaoEnc.xml";
                file_put_contents($fileResponse, $response->getExtra('data.received'));
                /**
                 * Update response path to include file name
                 */
                $response->setExtra('paths.response', $fileResponse);
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
    public function MDFeStatusServico(): SystemMessage
    {
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $body = [
            'consStatServMDFe' => [
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
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/consStatServMDFe_v{$this->xsdVersion}.xsd", $this->urlPortal);
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
            $fileSent = "{$paths['request']}/{$baseName}-consStatServMDFe.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->setExtra('paths.request', $fileSent);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retConsStatServMDFe.xml";
                file_put_contents($fileResponse, $response->getExtra('data.received'));
                /**
                 * Update response path to include file name
                 */
                $response->setExtra('paths.response', $fileResponse);
            }
        }
        return $response;
    }

    /**
     *
     * Distribution of documents and information of interest to the MDF-e actor
     * Fully implemented
     *
     * @param int $ultNSU
     * @param int $NSU
     * @return SystemMessage
     */
    public function MDFeDistribuicaoDFe(?int $ultNSU = null, ?int $NSU = null): SystemMessage
    {
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $pathPart = '';
        $body = [
            'distDFeInt' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'tpAmb' => $this->tpAmb,
                'CNPJ' => $this->CNPJ
            ]
        ];
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
            return new SystemMessage('You must provide last NSU, an NSU or a valid MDFe', // Message
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
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/distDFeInt_v{$this->xsdVersion}.xsd", $this->urlPortal);
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
            $response->setExtra('paths.request', $fileSent);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retDistDFeInt.xml";
                file_put_contents($fileResponse, $response->getExtra('data.received'));
                /**
                 * Update response path to include file name
                 */
                $response->setExtra('paths.response', $fileResponse);
            }
        }
        return $response;
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
        $nSeqEvent = str_pad((string) $nSeqEvent, 2, '0', STR_PAD_LEFT);
        $body = [
            'eventoMDFe' => [
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
                    'chMDFe' => $chDFe,
                    'dhEvento' => date('c'),
                    'tpEvento' => $type,
                    'nSeqEvento' => intval($nSeqEvent),
                    'detEvento' => $detEvent
                ]
            ]
        ];
        return new Xml($this->sign(Xml::arrayToXml($body), 'infEvento', 'Id', 'eventoMDFe'));
    }

    /**
     * Cancellation event
     * Fully implemented
     *
     * @param string $chMDFe
     * @param string $nProt
     * @param string $xJust
     * @return SystemMessage
     */
    public function evCancMDFe(string $chMDFe, string $nProt, string $xJust): SystemMessage
    {
        if (! Variable::nfeAccessKey()->validate($chMDFe)) {
            return new SystemMessage("Invalid MDFe key: {$chMDFe}", // Message
            '1', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
        $initialize = $this->prepare('MDFeRecepcaoEvento');
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $tpEvent = '110111';
        $detEvent = [
            '@attributes' => [
                'versaoEvento' => $this->urlVersion
            ],
            'evCancMDFe' => [
                'descEvento' => $this->eventCode[$tpEvent]['desc'],
                'nProt' => $nProt,
                'xJust' => $xJust
            ]
        ];
        $body = $this->event($chMDFe, 1, $tpEvent, $detEvent);
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            /**
             * Validate main event structure
             */
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/eventoMDFe_v{$this->xsdVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
            /**
             * Validate specific event structure
             */
            XsdSchema::validate(Xml::arrayToXml([
                'evCancMDFe' => $detEvent['evCancMDFe']
            ]), "{$this->schemaPath}/evCancMDFe_v{$this->xsdVersion}.xsd", $this->urlPortal);
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
            $baseName = "{$chMDFe}-{$tpEvent}-1";
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-eventoMDFe.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->setExtra('paths.request', $fileSent);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retEventoMDFe.xml";
                file_put_contents($fileResponse, $response->getExtra('data.received'));
                /**
                 * Update response path to include file name
                 */
                $response->setExtra('paths.response', $fileResponse);

                /**
                 * Save protocol
                 */
                if ($response->getExtra('parse.infEvento.cStat') == 135) {
                    $include = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><procEventoMDFe versao=\"{$response->getExtra('parse.infEvento.versao')}\">" . Xml::clearXmlDeclaration($body->getXml()) . Xml::clearXmlDeclaration($response->getExtra('data.received')) . "</procEventoMDFe>";
                    $fileInclude = "{$paths['document']}/{$baseName}-procEventoMDFe.xml";
                    $response->setExtras([
                        'parse.procXML' => $include,
                        'parse.pathXML' => $fileInclude
                    ]);
                    file_put_contents($fileInclude, $include);
                }
            }
        }
        return $response;
    }

    /**
     * Finish event
     * Fully implemented
     *
     * @param string $chMDFe
     * @param string $nProt
     * @param string $xJust
     * @return SystemMessage
     */
    public function evEncMDFe(string $chMDFe, string $nProt, string $dtEnc, string $cMun): SystemMessage
    {
        if (! Variable::nfeAccessKey()->validate($chMDFe)) {
            return new SystemMessage("Invalid MDFe key: {$chMDFe}", // Message
            '1', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
        $initialize = $this->prepare('MDFeRecepcaoEvento');
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $tpEvent = '110112';
        $detEvent = [
            '@attributes' => [
                'versaoEvento' => $this->urlVersion
            ],
            'evEncMDFe' => [
                'descEvento' => $this->eventCode[$tpEvent]['desc'],
                'nProt' => $nProt,
                'dtEnc' => $dtEnc,
                'cUF' => substr($cMun, 0, 2),
                'cMun' => $cMun
            ]
        ];
        $body = $this->event($chMDFe, 1, $tpEvent, $detEvent);
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            /**
             * Validate main event structure
             */
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/eventoMDFe_v{$this->xsdVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
            /**
             * Validate specific event structure
             */
            XsdSchema::validate(Xml::arrayToXml([
                'evEncMDFe' => $detEvent['evEncMDFe']
            ]), "{$this->schemaPath}/evEncMDFe_v{$this->xsdVersion}.xsd", $this->urlPortal);
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
            $baseName = "{$chMDFe}-{$tpEvent}-1";
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-eventoMDFe.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->setExtra('paths.request', $fileSent);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retEventoMDFe.xml";
                file_put_contents($fileResponse, $response->getExtra('data.received'));
                /**
                 * Update response path to include file name
                 */
                $response->setExtra('paths.response', $fileResponse);

                /**
                 * Save protocol
                 */
                if ($response->getExtra('parse.infEvento.cStat') == 135) {
                    $include = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><procEventoMDFe versao=\"{$response->getExtra('parse.infEvento.versao')}\">" . Xml::clearXmlDeclaration($body->getXml()) . Xml::clearXmlDeclaration($response->getExtra('data.received')) . "</procEventoMDFe>";
                    $fileInclude = "{$paths['document']}/{$baseName}-procEventoMDFe.xml";
                    $response->setExtras([
                        'parse.procXML' => $include,
                        'parse.pathXML' => $fileInclude
                    ]);
                    file_put_contents($fileInclude, $include);
                }
            }
        }
        return $response;
    }

    /**
     * Including driver
     * Fully implemented
     *
     * @param string $chMDFe
     * @param int $nSeqEvent
     * @param string $nProt
     * @param string $xJust
     * @return SystemMessage
     */
    public function evIncCondutorMDFe(string $chMDFe, int $nSeqEvent, string $xNome, string $CPF): SystemMessage
    {
        if (! Variable::nfeAccessKey()->validate($chMDFe)) {
            return new SystemMessage("Invalid MDFe key: {$chMDFe}", // Message
            '1', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
        $initialize = $this->prepare('MDFeRecepcaoEvento');
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $tpEvent = '110114';
        $detEvent = [
            '@attributes' => [
                'versaoEvento' => $this->urlVersion
            ],
            'evIncCondutorMDFe' => [
                'descEvento' => $this->eventCode[$tpEvent]['desc'],
                'condutor' => [
                    'xNome' => $xNome,
                    'CPF' => $CPF
                ]
            ]
        ];
        $body = $this->event($chMDFe, $nSeqEvent, $tpEvent, $detEvent);
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            /**
             * Validate main event structure
             */
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/eventoMDFe_v{$this->xsdVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
            /**
             * Validate specific event structure
             */
            XsdSchema::validate(Xml::arrayToXml([
                'evIncCondutorMDFe' => $detEvent['evIncCondutorMDFe']
            ]), "{$this->schemaPath}/evIncCondutorMDFe_v{$this->xsdVersion}.xsd", $this->urlPortal);
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
            $baseName = "{$chMDFe}-{$tpEvent}-{$nSeqEvent}";
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-eventoMDFe.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->setExtra('paths.request', $fileSent);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retEventoMDFe.xml";
                file_put_contents($fileResponse, $response->getExtra('data.received'));
                /**
                 * Update response path to include file name
                 */
                $response->setExtra('paths.response', $fileResponse);

                /**
                 * Save protocol
                 */
                if ($response->getExtra('parse.infEvento.cStat') == 135) {
                    $include = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><procEventoMDFe versao=\"{$response->getExtra('parse.infEvento.versao')}\">" . Xml::clearXmlDeclaration($body->getXml()) . Xml::clearXmlDeclaration($response->getExtra('data.received')) . "</procEventoMDFe>";
                    $fileInclude = "{$paths['document']}/{$baseName}-procEventoMDFe.xml";
                    $response->setExtras([
                        'parse.procXML' => $include,
                        'parse.pathXML' => $fileInclude
                    ]);
                    file_put_contents($fileInclude, $include);
                }
            }
        }
        return $response;
    }

    /**
     * Adding DFe to a MDFE
     * Fully implemented
     *
     * @param string $chMDFe
     * @param int $nSeqEvent
     * @param string $nProt
     * @param string $cMunCarrega
     * @param string $xMunCarrega
     * @param array $infDoc
     * @return SystemMessage
     */
    public function evInclusaoDFeMDFe(string $chMDFe, int $nSeqEvent, string $nProt, string $cMunCarrega, string $xMunCarrega, array $infDoc): SystemMessage
    {
        if (! Variable::nfeAccessKey()->validate($chMDFe)) {
            return new SystemMessage("Invalid MDFe key: {$chMDFe}", // Message
            '1', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
        $initialize = $this->prepare('MDFeRecepcaoEvento');
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $tpEvent = '110115';
        $detEvent = [
            '@attributes' => [
                'versaoEvento' => $this->urlVersion
            ],
            'evIncDFeMDFe' => [
                'descEvento' => $this->eventCode[$tpEvent]['desc'],
                'nProt' => $nProt,
                'cMunCarrega' => $cMunCarrega,
                'xMunCarrega' => $xMunCarrega,
                'infDoc' => $infDoc
            ]
        ];
        $body = $this->event($chMDFe, $nSeqEvent, $tpEvent, $detEvent);
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            /**
             * Validate main event structure
             */
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/eventoMDFe_v{$this->xsdVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
            /**
             * Validate specific event structure
             */
            XsdSchema::validate(Xml::arrayToXml([
                'evIncDFeMDFe' => $detEvent['evIncDFeMDFe']
            ]), "{$this->schemaPath}/evInclusaoDFeMDFe_v{$this->xsdVersion}.xsd", $this->urlPortal);
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
            $baseName = "{$chMDFe}-{$tpEvent}-{$nSeqEvent}";
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-eventoMDFe.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->setExtra('paths.request', $fileSent);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retEventoMDFe.xml";
                file_put_contents($fileResponse, $response->getExtra('data.received'));
                /**
                 * Update response path to include file name
                 */
                $response->setExtra('paths.response', $fileResponse);

                /**
                 * Save protocol
                 */
                if ($response->getExtra('parse.infEvento.cStat') == 135) {
                    $include = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><procEventoMDFe versao=\"{$response->getExtra('parse.infEvento.versao')}\">" . Xml::clearXmlDeclaration($body->getXml()) . Xml::clearXmlDeclaration($response->getExtra('data.received')) . "</procEventoMDFe>";
                    $fileInclude = "{$paths['document']}/{$baseName}-procEventoMDFe.xml";
                    $response->setExtras([
                        'parse.procXML' => $include,
                        'parse.pathXML' => $fileInclude
                    ]);
                    file_put_contents($fileInclude, $include);
                }
            }
        }
        return $response;
    }
}