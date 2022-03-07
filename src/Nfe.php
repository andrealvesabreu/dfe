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
 * Web Service - NfeDistribuicaoDFe
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
     * Distribution of documents and information of interest to the NF-e actor
     *
     * @param int $nsu
     * @return SystemMessage
     */
    public function NfeDistribuicaoDFe(int $nsu): SystemMessage
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
}