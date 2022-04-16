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
 * Description of Gnre
 *
 * @author aalves
 */

/**
 * Class implements methods from SEFAZ
 *
 * Web Service - GnreRecepcaoLote OK
 * Web Service - GnreResultadoLote OK
 * Web Service - GnreLoteRecepcaoConsulta
 * Web Service - GnreResultadoLoteConsulta OK
 * Web Service - GnreConfigUF OK
 *
 * @author aalves
 */
class Gnre extends Dfe
{

    public function __construct($config, $cert)
    {
        $config['mod'] = '23';
        parent::__construct($config, $cert);
    }

    /**
     * Query UF configuration
     * Fully implemented
     *
     * @param string $cRec
     * @return SystemMessage
     */
    public function GnreConfigUF(?string $cRec = null): SystemMessage
    {
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $body = [
            'TConsultaConfigUf' => [
                '@attributes' => [
                    'xmlns' => 'http://www.gnre.pe.gov.br'
                ],
                'ambiente' => $this->tpAmb,
                'uf' => $this->xUF
            ]
        ];
        if ($cRec !== null) {
            Arrays::set($body, 'TConsultaConfigUf.receita', $cRec);
            $baseName = "{$this->xUF}-{$cRec}";
        } else {
            $baseName = $this->xUF;
        }
        $body = Xml::arrayToXml($body, null, true);
        /**
         * Validate XML before send
         */
        if ($this->schemaPath != null) {
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/consulta_config_uf_v{$this->xsdVersion}.xsd", 'http://www.gnre.pe.gov.br');
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
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-envConfigUF.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->setExtra('paths.request', $fileSent);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retConfigUF.xml";
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
     * Reception Service.
     * Send a lot to webservice
     * Fully implemented
     *
     * @param string $cRec
     * @return SystemMessage
     */
    public function GnreRecepcaoLote(string $data): SystemMessage
    {
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $body = new Xml($data);
        /**
         * Validade input
         */
        if ($this->schemaPath != null) {
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/lote_gnre_v{$this->xsdVersion}.xsd", 'http://www.gnre.pe.gov.br');
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
            $baseName = "{$response->getExtra('parse.recibo.numero')}-" . date('Y-m-d_H-i-s');
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-envGnreRecepcaoLote.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->setExtra('paths.request', $fileSent);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retGnreRecepcaoLote.xml";
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
     * @param string $cRec
     * @return SystemMessage
     */
    public function GnreResultadoLote(string $nRec, string $incluirPDFGuias = 'S', string $incluirArquivoPagamento = 'N', string $incluirNoticias = 'N'): SystemMessage
    {
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }

        $body = [
            'TConsLote_GNRE' => [
                '@attributes' => [
                    'xmlns' => 'http://www.gnre.pe.gov.br'
                ],
                'ambiente' => $this->tpAmb,
                'numeroRecibo' => $nRec,
                'incluirPDFGuias' => $incluirPDFGuias,
                'incluirArquivoPagamento' => $incluirArquivoPagamento,
                'incluirNoticias' => $incluirNoticias
            ]
        ];
        $body = Xml::arrayToXml($body, null, true);
        /**
         * Validade input
         */
        if ($this->schemaPath != null) {
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/lote_gnre_consulta_v{$this->xsdVersion}.xsd", 'http://www.gnre.pe.gov.br');
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
            $baseName = "{$response->getExtra('parse.recibo.numero')}-" . date('Y-m-d_H-i-s');
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-envGnreResultadoLote.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->setExtra('paths.request', $fileSent);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retGnreResultadoLote.xml";
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
     * Query authorization status v2.00
     * Fully implemented
     *
     * @param string $cRec
     * @return SystemMessage
     */
    public function GnreResultadoLoteConsulta(string $nRec): SystemMessage
    {
        $initialize = $this->prepare(__FUNCTION__);
        if (! $initialize->isOk()) {
            return $initialize;
        }

        $body = [
            'TConsLote_GNRE' => [
                '@attributes' => [
                    'xmlns' => 'http://www.gnre.pe.gov.br'
                ],
                'ambiente' => $this->tpAmb,
                'numeroRecibo' => $nRec
            ]
        ];
        $body = Xml::arrayToXml($body, null, true);
        /**
         * Validade input
         */
        if ($this->schemaPath != null) {
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/lote_gnre_consulta_v{$this->xsdVersion}.xsd", 'http://www.gnre.pe.gov.br');
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
            $baseName = "{$response->getExtra('parse.numeroRecibo')}-" . date('Y-m-d_H-i-s');
            /**
             * Save sent file
             *
             * @var string $fileSent
             */
            $fileSent = "{$paths['request']}/{$baseName}-envGnreResultadoLoteConsulta.xml";
            file_put_contents($fileSent, $body->getXml());
            /**
             * Update request path to include file name
             */
            $response->setExtra('paths.request', $fileSent);
            /**
             * Save response file, if server has processed request succefully
             */
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retGnreResultadoLoteConsulta.xml";
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
     * Apply a filter on response of webservice
     *
     * @param string $str
     * @return mixed
     */
    protected function responseFilter(string $str)
    {
        return str_replace([
            ' xmlns:ns1="http://www.gnre.pe.gov.br"',
            ':ns1',
            'ns1:'
        ], '', $str);
    }

    /**
     * getHeader
     *
     * @param string $mod
     * @return Xml|null
     */
    public function getHeader(): ?Xml
    {
        return new Xml("<gnreCabecMsg xmlns=\"http://www.gnre.pe.gov.br/wsdl/{$this->urlMethod}\"><versaoDados>{$this->version}</versaoDados></gnreCabecMsg>");
    }
}