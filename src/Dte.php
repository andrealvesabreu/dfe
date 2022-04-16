<?php
declare(strict_types = 1);
namespace Inspire\Dfe;

use Inspire\Support\Message\System\SystemMessage;
use Inspire\Validator\Variable;
use Inspire\Support\Xml\Xml;
use Inspire\Support\Arrays;
use Inspire\Validator\XsdSchema;

/**
 * Description of Dte
 *
 * @author aalves
 */

/**
 * Class implements methods from SEFAZ
 *
 * Web Service - DTeRecepcao
 * Web Service - DTeRetRecepcao
 * Web Service - DTeRecepcaoEvento
 *
 * @author aalves
 */
class Dte extends Dfe
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
     * getHeader
     *
     * @param string $mod
     * @return Xml|null
     */
    public function getHeader(): ?Xml
    {
        return new Xml("<dteCabecMsg xmlns=\"{$this->urlNamespace}\"><cUF>{$this->cUF}</cUF><versaoDados>{$this->urlVersion}</versaoDados></dteCabecMsg>");
    }

    /**
     * Send a lot to webservice
     *
     * @param string $idLot
     * @param array $DTe
     * @return SystemMessage
     */
    public function DTeRecepcao(string $idLot, array $DTe): SystemMessage
    {
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $body = [
            'enviDTe' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'idLote' => str_pad($idLot, 15, '0', STR_PAD_LEFT),
                'xml' => '__xml__'
            ]
        ];
        $noXmlDeclaration = Xml::clearXmlDeclaration($DTe);
        $body = new Xml(str_replace('<xml>__xml__</xml>', // Replace special mark
        implode('', $noXmlDeclaration), // Set documents
        Xml::arrayToXml($body))); // Convert array to XML
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/enviDTe_v{$this->xsdVersion}.xsd", $this->urlPortal);
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
                if (preg_match('/<infDTe Id=\"DTe(.+?)"/', $signed, $mdfeKey) && // If a match of MDFe key was found
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
            $fileSent = "{$paths['request']}/{$baseName}-enviDTe.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->setExtra('paths.request', $fileSent);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retEnviDTe.xml";
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
     * Query authorization status
     * Fully implemented
     *
     * @param string $rec
     * @return SystemMessage
     */
    public function DTeRetRecepcao(string $rec): SystemMessage
    {
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $body = [
            'consReciDTe' => [
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
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/consReciDTe_v{$this->xsdVersion}.xsd", $this->urlPortal);
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
            $fileSent = "{$paths['request']}/{$baseName}-consReciDTe.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->setExtra('paths.request', $fileSent);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retConsReciDTe.xml";
                file_put_contents($fileResponse, $response->getExtra('data.received'));
                /**
                 * Update response path to include file name
                 */
                $response->setExtra('paths.response', $fileResponse);
                /**
                 * If webservice provided a response
                 */
                if ($response->getExtra('parse.bStat')) {
                    foreach ($response->getExtra('parse.protDTe') as $cteKey => $protDTe) {
                        if (Arrays::get($protDTe, 'infProt.cStat') == '100') {
                            /**
                             * Load signed file in an array
                             *
                             * @var array $signedVersions
                             */
                            $signedFile = "{$paths['signed']}/{$protDTe['infProt']['chDTe']}.xml";
                            $signedVersions = file($signedFile);
                            $digVal = "<DigestValue>{$protDTe['infProt']['digVal']}</DigestValue>";
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
                                    $prot->loadXml(rtrim($protDTe['xml'], PHP_EOL));

                                    /**
                                     * Create a new Dom object to add signed and protocol to a new file
                                     *
                                     * @var \DOMDocument $procDTe
                                     */
                                    $procDTe = new \DOMDocument('1.0', 'utf-8');
                                    $procDTe->formatOutput = false;
                                    $procDTe->preserveWhiteSpace = false;
                                    /**
                                     * Tag mdfeProc
                                     *
                                     * @var \DOMElement $mdfeProc
                                     */
                                    $mdfeProc = $procDTe->createElement('mdfeProc');
                                    $procDTe->appendChild($mdfeProc);
                                    /**
                                     * Attribute versao
                                     *
                                     * @var \DOMElement $versao
                                     */
                                    $versao = $mdfeProc->appendChild($procDTe->createAttribute('versao'));
                                    $versao->appendChild($procDTe->createTextNode($protDTe['versao']));
                                    /**
                                     * Attribute xmlns
                                     *
                                     * @var \DOMElement $xmlns
                                     */
                                    $xmlns = $mdfeProc->appendChild($procDTe->createAttribute('xmlns'));
                                    $xmlns->appendChild($procDTe->createTextNode('http://www.portalfiscal.inf.br/dte'));
                                    /**
                                     * Append MDFe tag
                                     *
                                     * @var \DOMElement $MDFe
                                     */
                                    $node = $procDTe->importNode($signed->getElementsByTagName('MDFe')
                                        ->item(0), true);
                                    $mdfeProc->appendChild($node);
                                    /**
                                     * Append protDTe tag
                                     *
                                     * @var \DOMElement $protDTe
                                     */
                                    $nodep = $procDTe->importNode($prot->getElementsByTagName('protDTe')
                                        ->item(0), true);
                                    $mdfeProc->appendChild($nodep);
                                    /**
                                     * Normalize XML
                                     *
                                     * @var string $procXML
                                     */
                                    $procXML = str_replace('xmlns="http://www.portalfiscal.inf.br/dte" xmlns="http://www.w3.org/2000/09/xmldsig#"', 'xmlns="http://www.portalfiscal.inf.br/dte"', //
                                    str_replace(array(
                                        ' standalone="no"',
                                        'default:',
                                        ':default',
                                        "\n",
                                        "\r",
                                        "\t"
                                    ), '', $procDTe->saveXML()));
                                    $procDTeFile = "{$paths['document']}/{$protDTe['infProt']['chDTe']}-procMDFe.xml";
                                    file_put_contents($procDTeFile, $procXML);
                                    $protDTe['procXML'] = $procXML;
                                    $protDTe['pathXML'] = $procDTeFile;
                                    $response->setExtra("parse.protDTe.{$cteKey}", $protDTe);
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
     * Create event general part
     *
     * @param string $chDFe
     * @param string $type
     * @param string $lat
     * @param string $lng
     * @return Xml
     */
    protected function event(string $chDFe, string $type, string $lat, string $lng): Xml
    {
        $body = [
            'eventoDTe' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'infEvento' => [
                    '@attributes' => [
                        'versao' => $this->urlVersion
                    ],
                    'tpAmb' => $this->tpAmb,
                    'chDTe' => $chDFe,
                    'CNPJ' => $this->CNPJ,
                    'dhEvento' => date('c'),
                    'cEvento' => $type,
                    'xEvento' => $this->eventCode[$type]['desc'],
                    'localizacao' => [
                        'latitude' => $lat,
                        'longitude' => $lng
                    ]
                ]
            ]
        ];
        return new Xml($this->sign(Xml::arrayToXml($body), 'infEvento', 'Id', 'eventoDTe'));
    }
}