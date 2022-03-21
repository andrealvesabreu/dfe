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
 * Web Service - NfeStatusServico
 * Web Service - NfeConsultaCadastro
 * Web Service - NFeRecepcaoEvento
 * Web Service - NFeDistribuicaoDFe
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
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/consStatServ_v{$this->version}.xsd", $this->urlPortal);
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
            $fileSent = "{$paths['request']}/{$baseName}-consStatServNFe.xml";
            file_put_contents($fileSent, $body->getXml());
            // Save response file, if server has processed request succefully
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retConsStatServNFe.xml";
                file_put_contents($fileResponse, $response->getExtra('xml'));
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
            XsdSchema::validate($body->getXml(), "{$this->schemaPath}/distDFeInt_v{$this->version}.xsd", $this->urlPortal);
            if (XsdSchema::hasErrors()) {
                return XsdSchema::getSystemErrors()[0];
            }
        }
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
}