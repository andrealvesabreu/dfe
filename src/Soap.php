<?php
declare(strict_types = 1);
namespace Inspire\Dfe;

use Inspire\Support\Xml\Xml;
use Inspire\Support\Message\System\SystemMessage;
use Inspire\Support\Xml\Array2XML;

/**
 * Description of Soap
 *
 * @author aalves
 */
class Soap
{

    /**
     * Path to certificate in PEM format
     *
     * @var Certificate|null
     */
    private ?Certificate $cert = null;

    /**
     * Document type code
     *
     * @var string|null
     */
    private ?string $mod = null;

    /**
     * URL for the webervice WSDL
     *
     * @var string|null
     */
    private ?string $wsdl = null;

    /**
     * Namespace to use in SOAP envelope
     *
     * @var string|null
     */
    private ?string $namespace = null;

    /**
     * Webservice method
     *
     * @var string|null
     */
    private ?string $method = null;

    /**
     * Webservice service name
     *
     * @var string|null
     */
    private ?string $service = null;

    /**
     * Constructor can receive a configuration array
     *
     * @param array $config
     */
    public function __construct(?array $config = null)
    {
        if ($config !== null && is_array($config)) {
            $this->config($config);
        }
    }

    /**
     * Send request to SEFAZ webservice
     *
     * @param Xml $header
     * @param Xml $body
     * @param bool $addMethod
     * @return SystemMessage
     */
    public function send(Xml $header, Xml $body, ?bool $addMethod = false): SystemMessage
    {
        try {
            libxml_disable_entity_loader(false);
            $options = [
                'encoding' => 'UTF-8',
                'verifypeer' => false,
                'verifyhost' => false,
                'soap_version' => SOAP_1_2,
                'style' => SOAP_DOCUMENT,
                'use' => SOAP_LITERAL,
                'local_cert' => $this->cert->getCertKeyFile(),
                'keep_alive' => false,
                'trace' => true,
                'compression' => 0,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'stream_context' => stream_context_create([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true,
                        'local_cert' => $this->cert->getCertKeyFile(),
                        'local_pk' => $this->cert->getPriKeyFile(),
                        'ciphers' => 'DEFAULT:!DH'
                    ]
                ])
            ];
            $request = new \SoapClient("{$this->wsdl}?wsdl", $options);
            /**
             * Header
             */
            switch ($this->mod) {
                case 23:
                    $header = new \SoapHeader($this->namespace, 'gnreCabecMsg', new \SoapVar(Xml::clearXmlDeclaration($header->getXml()), XSD_ANYXML));
                    $tmpBody = "<gnreDadosMsg xmlns=\"{$this->namespace}\">" . Xml::clearXmlDeclaration($body->getXml()) . "</gnreDadosMsg>";
                    break;
                case 55:
                case 65:
                    $header = new \SoapHeader($this->namespace, 'nfeCabecMsg', new \SoapVar(Xml::clearXmlDeclaration($header->getXml()), XSD_ANYXML));
                    $tmpBody = "<nfeDadosMsg xmlns=\"{$this->namespace}\">" . Xml::clearXmlDeclaration($body->getXml()) . "</nfeDadosMsg>";
                    break;
                case 57:
                    $header = new \SoapHeader($this->namespace, 'cteCabecMsg', new \SoapVar(Xml::clearXmlDeclaration($header->getXml()), XSD_ANYXML));
                    $tmpBody = "<cteDadosMsg xmlns=\"{$this->namespace}\">" . Xml::clearXmlDeclaration($body->getXml()) . "</cteDadosMsg>";
                    break;
                case 58:
                    $header = new \SoapHeader($this->namespace, 'mdfeCabecMsg', new \SoapVar(Xml::clearXmlDeclaration($header->getXml()), XSD_ANYXML));
                    $tmpBody = "<mdfeDadosMsg xmlns=\"{$this->namespace}\">" . Xml::clearXmlDeclaration($body->getXml()) . "</mdfeDadosMsg>";
                    break;
            }
            if ($addMethod) {
                $body = new \SoapVar("<{$this->method} xmlns=\"{$this->namespace}\">{$tmpBody}</{$this->method}>", XSD_ANYXML);
            } else {
                $body = new \SoapVar($tmpBody, XSD_ANYXML);
            }
            $request->__setSoapHeaders($header);
            /**
             * Call webservice method
             */
            try {
                $resp = $request->__soapCall($this->method, [
                    $body
                ]);

                $respXML = null;
                /**
                 * If webservice response contains a generic field 'any' with a string XML
                 */
                if (property_exists($resp, 'any')) {
                    $respXML = $resp->any;
                } else {
                    /**
                     * Check if reponse contains a propert called {$this->method}Result
                     */
                    $res = $this->method . 'Result';
                    if (property_exists($resp, $res)) {
                        /**
                         * If {$this->method}Result contains a propert called 'any'
                         */
                        if (property_exists($resp->{$res}, 'any')) {
                            $respXML = $resp->{$res}->any;
                        } /**
                         * If not, i will consider that its a stdClass response representation
                         * Trying convert it to XML
                         */
                        else {
                            $responseData = json_decode(json_encode($resp), true);
                            if (is_array($responseData) && ! empty($responseData)) {
                                $respXML = Xml::arrayToXml($responseData, null, true)->getXml();
                            }
                        }
                    } /**
                     * There is no a propert called {$this->method}Result
                     * Consider that its a stdClass response representation
                     * Trying convert it to XML
                     */
                    else {
                        $responseData = json_decode(json_encode($resp), true);
                        if (is_array($responseData) && ! empty($responseData)) {
                            $respXML = Xml::arrayToXml($responseData, null, true)->getXml();
                        }
                    }
                }

                if (empty($respXML)) {
                    return new SystemMessage('Service unavailable', // Message
                    '231', // System code
                    SystemMessage::MSG_ERROR, // System status code
                    false); // System status
                }
                return new SystemMessage($respXML, // Message
                '1', // System code
                SystemMessage::MSG_OK, // System status code
                true); // System status
            } catch (\Exception $ex) {
                return new SystemMessage($ex->getMessage(), // Message
                '231', // System code
                SystemMessage::MSG_ERROR, // System status code
                false); // System status
            }
        } catch (\SoapFault $ex) {
            return new SystemMessage($ex->getMessage(), // Message
            '1', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        } catch (\Exception $ex) {
            return new SystemMessage($ex->getMessage(), // Message
            '1', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
    }

    /**
     * Set path to PEM certificate
     *
     * @param Certificate $cert
     */
    public function setCert(Certificate $cert)
    {
        $this->cert = $cert;
    }

    /**
     * Set namespace
     *
     * @param string $nm
     */
    public function setNamespace(string $nm)
    {
        $this->namespace = $nm;
    }

    /**
     * Set webservice method
     *
     * @param string $method
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    /**
     * Set service name
     *
     * @param string $service
     */
    public function setService(string $service)
    {
        $this->service = $service;
    }

    /**
     * Set URL for the webervice WSDL
     *
     * @param string $wsdl
     */
    public function setWsdl(string $wsdl)
    {
        $this->wsdl = $wsdl;
    }

    /**
     * Set document type code (23|55|57|58|65)
     *
     * @param string $mod
     */
    public function setMod(string $mod)
    {
        $this->mod = $mod;
    }

    /**
     * Set all configuration by seting it in an array
     *
     * @param array $config
     */
    public function config(array $config)
    {
        foreach ($config as $k => $value) {
            if (property_exists($this, $k)) {
                $this->{$k} = $value;
            }
        }
    }
}
