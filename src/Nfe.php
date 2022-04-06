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
 * Description of Nfe
 *
 * @author aalves
 */

/**
 * Class implements methods from SEFAZ
 *
 * Web Service - NfeAutorizacao
 * Web Service - NfeRetAutorizacao
 * Web Service - NfeInutilizacao
 * Web Service - NfeConsulta
 * Web Service - NfeStatusServico OK
 * Web Service - NfeConsultaCadastro
 * Web Service - NFeRecepcaoEvento
 * Web Service - NFeDistribuicaoDFe OK
 *
 * @author aalves
 */
class Nfe extends Dfe
{

    /**
     * Query WS status
     *
     * @return \Inspire\Support\Message\System\SystemMessage
     */
    public function NfeStatusServico(): SystemMessage
    {
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $body = [
            'consStatServ' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'tpAmb' => $this->tpAmb,
                'cUF' => $this->cUF,
                'xServ' => 'STATUS'
            ]
        ];
        $body = Xml::arrayToXml($body, null, true);
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/consStatServ_v{$this->urlVersion}.xsd", $this->urlPortal);
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
             * Save file sent
             */
            $fileSent = "{$paths['request']}/{$baseName}-consStatServ.xml";
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
                $fileResponse = "{$paths['response']}/{$baseName}-retConsStatServ.xml";
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
            'envEvento' => [
                '@attributes' => [
                    'xmlns' => $this->urlPortal,
                    'versao' => $this->urlVersion
                ],
                'idLote' => 10,
                'evento' => [
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
                        'chNFe' => $chDFe,
                        'dhEvento' => date('c'),
                        'tpEvento' => $tpEvent,
                        'nSeqEvento' => intval($nSeqEvent),
                        'verEvento' => $this->urlVersion,
                        'detEvento' => $detEvent
                    ]
                ]
            ]
        ];
        return new Xml($this->sign(Xml::arrayToXml($body), 'infEvento', 'Id', 'evento'));
    }

    /**
     *
     * @param string $chNFe
     * @param int $nSeqEvent
     * @param string $tpEvent
     * @param string $xJust
     * @return SystemMessage
     */
    public function confRecebto(string $chNFe, int $nSeqEvent, string $tpEvent, string $xJust): SystemMessage
    {
        if (! Variable::nfeAccessKey()->validate($chNFe)) {
            return new SystemMessage("Invalid NFe key: {$chNFe}", // Message
            '0', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
        $initialize = $this->prepare('RecepcaoEvento');
        if (! $initialize->isOk()) {
            return $initialize;
        }
        if (! in_array($tpEvent, [
            '210200',
            '210210',
            '210220',
            '210240'
        ])) {
            return new SystemMessage("Invalid event code: {$tpEvent}.\nThe event code must be one of: 210200, 210210, 210220 e 210240", // Message
            '0', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
        if ($tpEvent == '210220' || $tpEvent == '210240') {
            $detEvent = [
                '@attributes' => [
                    'versao' => $this->urlVersion
                ],
                'descEvento' => $this->eventCode[$tpEvent]['desc'],
                'xJust' => $xJust
            ];
        } else {
            $detEvent = [
                '@attributes' => [
                    'versao' => $this->urlVersion
                ],
                'descEvento' => $this->eventCode[$tpEvent]['desc']
            ];
        }
        /**
         * Mount event XML
         */
        $body = $this->event($chNFe, $nSeqEvent, $tpEvent, $detEvent);
        /**
         * Validate XML before send
         */
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            /**
             * Validate main event structure
             */
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/envConfRecebto_v{$this->urlVersion}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
            /**
             * Validate specific event structure
             */
            XsdSchema::validate(Xml::arrayToXml([
                'detEvento' => $detEvent
            ]), "{$this->schemaPath}/e{$tpEvent}_v{$this->urlVersion}.xsd", $this->urlPortal);
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
            $baseName = "{$chNFe}-{$tpEvent}-{$nSeqEvent}";
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
     * Distribution of documents and information of interest to the NF-e actor
     * Fully implemented
     *
     * @param int $ultNSU
     * @param int $NSU
     * @param string $chNFe
     * @return SystemMessage
     */
    public function NfeDistribuicaoDFe(?int $ultNSU = null, ?int $NSU = null, ?string $chNFe = null): SystemMessage
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
                'cUFAutor' => $this->cUF,
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
        } else if ($chNFe !== null && Variable::nfeAccessKey()->validate($chNFe)) {
            $pathPart = $chNFe;
            $tag = 'consChNFe';
            $value = [
                'chNFe' => $chNFe
            ];
        } else {
            return new SystemMessage('You must provide last NSU, an NSU or a valid NFe', // Message
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
             * Save file sent
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
}