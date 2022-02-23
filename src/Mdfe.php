<?php
declare(strict_types = 1);
namespace Inspire\Dfe;

use Inspire\Support\Message\System\SystemMessage;
use Inspire\Validator\Variable;
use Inspire\Support\Xml\Xml;
use Inspire\Support\Arrays;

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
 * Web Service - MDFeRetRecepcao
 * Web Service - MDFeConsulta
 * Web Service - MDFeConsNaoEnc
 * Web Service - MDFeStatusServico
 * Web Service - MDFeRecepcaoEvento
 * Web Service - MDFeDistribuicaoDFe
 *
 * @author aalves
 */
class Mdfe extends Dfe
{

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
        $body = new Xml(str_replace('<xml>__xml__</xml>', // Replace special mark
        implode('', Xml::clearXmlDeclaration($MDFe)), // Set documents
        Xml::arrayToXml($body))); // Convert array to XML
        return $this->send($body);
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
        return $this->send($body);
    }

    /**
     * Query document on webservice
     *
     * @param string $chMDFe
     * @return \Inspire\Support\Message\System\SystemMessage
     */
    public function MDFeConsulta(string $chMDFe): SystemMessage
    {
        if (! Variable::nfeAccessKey()->validate($chMDFe)) {
            return new SystemMessage("Invalid CTe key: {$chMDFe}", // Message
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
        return $this->send($body);
    }

    /**
     * Query document on webservice
     *
     * @param string $chMDFe
     * @return \Inspire\Support\Message\System\SystemMessage
     */
    public function MDFeConsNaoEnc(string $doc): SystemMessage
    {
        if ((strlen($doc) != 11 && strlen($doc) != 14) || // Length validation
        (strlen($doc) == 11 && ! Variable::cpf())->validate($doc) || // CPF validation
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

        $response = $this->send($body);
        /**
         * Save files
         *
         * @var array|null $paths
         */
        $paths = $response->getExtra('paths');
        if ($paths !== null) {
            $baseName = "{$response->getExtra('parse.cUF')}_{$this->CNPJ}_" . date('Y_m_d-H_i_s');
            // Save sent file
            $fileSent = "{$paths['request']}/{$baseName}-consMDFeNaoEnc.xml";
            file_put_contents($fileSent, $body->getXml());
            // Save response file, if server has processed request succefully
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retConsMDFeNaoEnc.xml";
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
        $response = $this->send($body);
        /**
         * Save files
         *
         * @var array|null $paths
         */
        $paths = $response->getExtra('paths');
        if ($paths !== null) {
            $baseName = strtr("{$response->getExtra('parse.cUF')}-{$response->getExtra('parse.dhRecbto')}", [
                '-' => '_',
                'T' => '_',
                ':' => '_'
            ]);
            // Save sent file
            $fileSent = "{$paths['request']}/{$baseName}-consStatServMDFe.xml";
            file_put_contents($fileSent, $body->getXml());
            // Save response file, if server has processed request succefully
            if ($response->isOk()) {
                $fileResponse = "{$paths['response']}/{$baseName}-retConsStatServMDFe.xml";
                file_put_contents($fileResponse, $response->getExtra('xml'));
            }
        }
        return $response;
    }

    /**
     * Cancellation event
     *
     * @param string $chMDFe
     * @param int $nSeqEvent
     * @param string $nProt
     * @param string $xJust
     * @return SystemMessage
     */
    public function cancel(string $chMDFe, int $nSeqEvent, string $nProt, string $xJust): SystemMessage
    {
        if (! Variable::nfeAccessKey()->validate($chMDFe)) {
            return new SystemMessage("Invalid MDFe key: {$chMDFe}", // Message
            '1', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
        $initialize = $this->prepare('MdfeRecepcaoEvento');
        if (! $initialize->isOk()) {
            return $initialize;
        }
        $type = '110111';
        $detEvent = [
            '@attributes' => [
                'versaoEvento' => $this->urlVersion
            ],
            'evCancMDFee' => [
                'descEvento' => $this->eventCode[$type],
                'nProt' => $nProt,
                'xJust' => $xJust
            ]
        ];
        $xmlEvent = $this->event($chMDFe, $nSeqEvent, $type, $detEvent);
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
                    'nSeqEvento' => str_pad((string) $nSeqEvent, 2, '0', STR_PAD_LEFT),
                    'detEvento' => $detEvent
                ]
            ]
        ];
        return new Xml($this->sign(Xml::arrayToXml($body), 'infEvento', 'Id', 'eventoMDFe'));
    }
}