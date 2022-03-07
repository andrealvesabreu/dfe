<?php
declare(strict_types = 1);
namespace Inspire\Dfe;

use Inspire\Support\Xml\Xml;
use Inspire\Support\Message\System\SystemMessage;
use Inspire\Support\ {
    Strings,
    Arrays
};
use Inspire\Config\Config;
use Inspire\Validator\Variable;

/**
 * Description of Dfe
 *
 * @author aalves
 */
class Dfe
{

    /**
     * Events pre-defined by the DFe system
     *
     * @var array
     */
    protected array $eventCode = [
        '110110' => 'Carta de Correcao',
        '110111' => 'Cancelamento',
        '110112' => 'Encerramento',
        '110113' => 'EPEC', // Event intended to meet requests for issuance in CT-e contingency.
        '110114' => 'Inclusão de Condutor', // Event destined to the inclusion of driver
        '110115' => 'Inclusão de DF-e',
        '110160' => 'Registro Multimodal', // Event designed to link information on services provided to CT-e multimodal. Note that, if a CT-e is issued that is already linked to the multimodal CT-e, it is not necessary to inform it for this event.
        '110170' => 'Informações da GTV', // Event so that the CT-e OS issuer of the Transport of Values ​​type of service can inform the GTV related to the provision of the service
        '110180' => 'Comprovante de Entrega do CT-e', // Proof of delivery event
        '110181' => 'Cancelamento do Comprovante de Entrega do CT-e', // Proof of delivery cancellation event
        '610110' => 'Prestação do Serviço em Desacordo'
    ];

    /**
     * List of services available by document model
     *
     * @var array
     */
    protected array $servicesAvailable = [
        '55' => [
            'NfeStatusServico',
            'NfeDistribuicaoDFe'
        ],
        '57' => [
            'CteRecepcao',
            'CteRetRecepcao',
            'CteInutilizacao',
            'CteConsulta',
            'CteStatusServico',
            'CteRecepcaoEvento',
            'CTeDistribuicaoDFe'
        ],
        '58' => [
            'MDFeRecepcao',
            'MDFeRetRecepcao',
            'MDFeRecepcaoEvento',
            'MDFeConsulta',
            'MDFeStatusServico',
            'MDFeConsNaoEnc',
            'MDFeDistribuicaoDFe',
            'MDFeRecepcaoSinc'
        ],
        '67' => [
            'CteRecepcaoOS',
            'CteRetRecepcao',
            'CteInutilizacao',
            'CteConsulta',
            'CteStatusServico',
            'CteRecepcaoEvento',
            'CTeDistribuicaoDFe'
        ]
    ];

    /**
     * Global configuration
     *
     * @var array|null
     */
    private ?array $baseConfig = [
        '23' => [
            'normal' => [
                'AC' => 'PE',
                'AL' => 'PE',
                'AM' => 'PE',
                'AN' => 'PE',
                'AP' => 'PE',
                'BA' => 'PE',
                'CE' => 'PE',
                'DF' => 'PE',
                'ES' => 'PE',
                'GO' => 'PE',
                'MA' => 'PE',
                'MG' => 'PE',
                'MS' => 'PE',
                'MT' => 'PE',
                'PA' => 'PE',
                'PB' => 'PE',
                'PE' => 'PE',
                'PI' => 'PE',
                'PR' => 'PE',
                'RJ' => 'PE',
                'RN' => 'PE',
                'RO' => 'PE',
                'RR' => 'PE',
                'RS' => 'PE',
                'SC' => 'PE',
                'SE' => 'PE',
                'SP' => 'PE',
                'TO' => 'AN'
            ]
        ],
        '55' => [
            'normal' => [
                'AC' => 'SVRS',
                'AL' => 'SVRS',
                'AM' => 'AM',
                'AN' => 'AN',
                'AP' => 'SVRS',
                'BA' => 'BA',
                'CE' => 'SVRS',
                'DF' => 'SVRS',
                'ES' => 'SVRS',
                'GO' => 'GO',
                'MA' => 'SVAN',
                'MG' => 'MG',
                'MS' => 'MS',
                'MT' => 'MT',
                'PA' => 'SVRS',
                'PB' => 'SVRS',
                'PE' => 'PE',
                'PI' => 'SVRS',
                'PR' => 'PR',
                'RJ' => 'SVRS',
                'RN' => 'SVRS',
                'RO' => 'SVRS',
                'RR' => 'SVRS',
                'RS' => 'RS',
                'SC' => 'SVRS',
                'SE' => 'SVRS',
                'SP' => 'SP',
                'TO' => 'SVRS',
                'SVAN' => 'SVAN',
                'SVRS' => 'SVRS',
                'SVCAN' => 'SVCAN',
                'SVCRS' => 'SVCRS'
            ],
            'contingency' => [
                'AC' => 'SVRS',
                'AL' => 'SVRS',
                'AM' => 'SVCRS',
                'AN' => 'SVCRS',
                'AP' => 'SVRS',
                'BA' => 'SVCRS',
                'CE' => 'SVCRS',
                'DF' => 'SVRS',
                'ES' => 'SVRS',
                'GO' => 'SVCRS',
                'MA' => 'SVAN',
                'MG' => 'SVCRS',
                'MS' => 'SVCRS',
                'MT' => 'SVCRS',
                'PA' => 'SVAN',
                'PB' => 'SVRS',
                'PE' => 'SVSP',
                'PI' => 'SVAN',
                'PR' => 'SVCRS',
                'RJ' => 'SVRS',
                'RN' => 'SVRS',
                'RO' => 'SVRS',
                'RR' => 'SVRS',
                'RS' => 'SVCRS',
                'SC' => 'SVRS',
                'SE' => 'SVRS',
                'SP' => 'SVCRS',
                'TO' => 'SVRS',
                'SVAN' => 'SVCRS',
                'SVRS' => 'SVCRS',
                'SVCAN' => 'SVCRS',
                'SVCRS' => 'SVCRS'
            ]
        ],
        '57' => [
            'normal' => [
                'AC' => 'SVRS',
                'AL' => 'SVRS',
                'AM' => 'SVRS',
                'AN' => 'AN',
                'AP' => 'SVRS',
                'BA' => 'SVRS',
                'CE' => 'SVRS',
                'DF' => 'SVRS',
                'ES' => 'SVRS',
                'GO' => 'SVRS',
                'MA' => 'SVRS',
                'MG' => 'MG',
                'MS' => 'MS',
                'MT' => 'MT',
                'PA' => 'SVRS',
                'PB' => 'SVRS',
                'PE' => 'SVSP',
                'PI' => 'SVRS',
                'PR' => 'PR',
                'RJ' => 'SVRS',
                'RN' => 'SVRS',
                'RO' => 'SVRS',
                'RR' => 'SVRS',
                'RS' => 'RS',
                'SC' => 'SVRS',
                'SE' => 'SVRS',
                'SP' => 'SP',
                'TO' => 'SVRS',
                'SVAN' => 'SVAN',
                'SVRS' => 'SVRS',
                'SVCAN' => 'SVCAN',
                'SVCRS' => 'SVCRS'
            ],
            'contingency' => [
                'AC' => 'SVRS',
                'AL' => 'SVRS',
                'AM' => 'SVRS',
                'AN' => 'AN',
                'AP' => 'SVRS',
                'BA' => 'SVRS',
                'CE' => 'SVRS',
                'DF' => 'SVRS',
                'ES' => 'SVRS',
                'GO' => 'SVRS',
                'MA' => 'SVAN',
                'MG' => 'SVSP',
                'MS' => 'SVRS',
                'MT' => 'SVRS',
                'PA' => 'SVRS',
                'PB' => 'SVRS',
                'PE' => 'SVSP',
                'PI' => 'SVAN',
                'PR' => 'SVSP',
                'RJ' => 'SVRS',
                'RN' => 'SVRS',
                'RO' => 'SVRS',
                'RR' => 'SVRS',
                'RS' => 'SVRS',
                'SC' => 'SVRS',
                'SE' => 'SVRS',
                'SP' => 'SVRS',
                'TO' => 'SVRS',
                'SVAN' => 'SVAN',
                'SVRS' => 'SVRS',
                'SVSP' => 'SVRS',
                'SVCAN' => 'SVCAN',
                'SVCRS' => 'SVCRS'
            ]
        ],
        '58' => [
            'normal' => [
                'AC' => 'RS',
                'AL' => 'RS',
                'AM' => 'RS',
                'AN' => 'RS',
                'AP' => 'RS',
                'BA' => 'RS',
                'CE' => 'RS',
                'DF' => 'RS',
                'ES' => 'RS',
                'GO' => 'RS',
                'MA' => 'RS',
                'MG' => 'RS',
                'MS' => 'RS',
                'MT' => 'RS',
                'PA' => 'RS',
                'PB' => 'RS',
                'PE' => 'RS',
                'PI' => 'RS',
                'PR' => 'RS',
                'RJ' => 'RS',
                'RN' => 'RS',
                'RO' => 'RS',
                'RR' => 'RS',
                'RS' => 'RS',
                'SC' => 'RS',
                'SE' => 'RS',
                'SP' => 'RS',
                'TO' => 'RS'
            ]
        ],
        '65' => [
            'normal' => [
                'AC' => 'SVRS',
                'AL' => 'SVRS',
                'AM' => 'AM',
                'AN' => 'AN',
                'AP' => 'SVRS',
                'BA' => 'SVRS',
                'CE' => 'CE',
                'DF' => 'SVRS',
                'ES' => 'SVRS',
                'GO' => 'SVRS',
                'MA' => 'SVRS',
                'MG' => 'MG',
                'MS' => 'MS',
                'MT' => 'MT',
                'PA' => 'SVRS',
                'PB' => 'SVRS',
                'PE' => 'SVSP',
                'PI' => 'SVRS',
                'PR' => 'PR',
                'RJ' => 'SVRS',
                'RN' => 'SVRS',
                'RO' => 'SVRS',
                'RR' => 'SVRS',
                'RS' => 'RS',
                'SC' => 'SVRS',
                'SE' => 'SVRS',
                'SP' => 'SP',
                'TO' => 'SVRS',
                'SVAN' => 'SVAN',
                'SVRS' => 'SVRS',
                'SVCAN' => 'SVCAN'
            ]
        ]
    ];

    /**
     * Specific DFe configuration (WS configuration)
     *
     * @var array|null
     */
    private ?array $config = [];

    /**
     * Save files of requests/responses?
     *
     * @var bool
     */
    protected bool $saveFiles = true;

    /**
     * Enable cache from saved files?
     * Cache will only save response data of last unique request
     * It could help to prevent resend duplicated messages and "abuse of service" error
     *
     * @var bool
     */
    private bool $enableCache = false;

    /**
     * Time to keep it on cache
     *
     * @var int
     */
    private int $cacheTTL = 900;

    /**
     * SOAP Envelope Composite Data and URLs segment
     */

    /**
     * WS version in use
     *
     * @var string|null
     */
    protected ?string $urlVersion = null;

    /**
     * URL of specific service
     *
     * @var string|null
     */
    protected ?string $urlWsdl = null;

    /**
     * WS service URL
     *
     * @var string|null
     */
    protected ?string $urlService = null;

    /**
     * WS method name
     *
     * @var string|null
     */
    protected ?string $urlMethod = null;

    /**
     * WS service namespace
     *
     * @var string|null
     */
    protected ?string $urlOperation = null;

    /**
     * Namespace URL
     *
     * @var string|null
     */
    protected ?string $urlNamespace = null;

    /**
     * Portal URL of each DFe
     *
     * @var string|null
     */
    protected ?string $urlPortal = null;

    /**
     * Base dir for XSD schemas
     * System will validate schemas if it is set different of null
     *
     * @var string|null
     */
    protected ?string $schemaPath = null;

    /**
     * URL variables of authorizing webservices
     */

    /**
     * Contingency enabled
     *
     * @var bool
     */
    private bool $contingency = false;

    /**
     * DFe Model (23, 55, 57, 58, 65)
     *
     * @var string|null
     */
    protected ?string $mod = null;

    /**
     * Literal model of DFe (gnre, nfe, cte, mdfe, nfce)
     *
     * @var string|null
     */
    protected ?string $xMod = null;

    /**
     * List of literal models
     *
     * @var array
     */
    private array $listMod = [
        '23' => 'gnre',
        '55' => 'nfe',
        '65' => 'nfce',
        '57' => 'cte',
        '58' => 'mdfe'
    ];

    /**
     * Version of DFe in use (to be used to determine URLs and validations)
     *
     * @var string|null
     */
    protected ?string $version = null;

    /**
     * UF acronym
     *
     * @var string|null
     */
    protected ?string $xUF = null;

    /**
     * UF IBGE code
     *
     * @var int
     */
    protected ?int $cUF = null;

    /**
     * Acronym of the responsible UF (authorizing WS)
     *
     * @var string|null
     */
    protected ?string $xUFAut = null;

    /**
     * Environment: 1 - production, 2 - approval
     *
     * @var int|null
     */
    protected ?int $tpAmb = null;

    /**
     * CNPJ of the issuing company
     *
     * @var string|null
     */
    protected ?string $CNPJ = null;

    /**
     * Date in format YYYY-MM-DD
     *
     * @var string
     */
    protected string $date;

    /**
     * Data to digital sign
     *
     * @var array
     */
    protected array $sign = [
        'nsDSIG' => 'http://www.w3.org/2000/09/xmldsig#', // Namespace do Signature
        'nsCannonMethod' => 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315', // Algorithm do CanonicalizationMethod
        'nsSignatureMethod' => 'http://www.w3.org/2000/09/xmldsig#rsa-sha1', // Algorithm do SignatureMethod
        'nsDigestMethod' => 'http://www.w3.org/2000/09/xmldsig#sha1', // Algorithm do DigestMethod
        'signAlgorithm' => OPENSSL_ALGO_SHA1, // Signature Algorithm. Included for documents that do not use SHA1
        'nsTransformMethod' => [ // Algorithm for Transform elements
            'http://www.w3.org/2000/09/xmldsig#enveloped-signature',
            'http://www.w3.org/TR/2001/REC-xml-c14n-20010315'
        ]
    ];

    /**
     * Certificates
     *
     * @var Certificate|null
     */
    protected ?Certificate $cert = null;

    /**
     * cUFlist
     *
     * @var array
     */
    protected array $cUFlist = [
        'AC' => 12,
        'AL' => 27,
        'AM' => 13,
        'AN' => 91,
        'AP' => 16,
        'BA' => 29,
        'CE' => 23,
        'DF' => 53,
        'ES' => 32,
        'GO' => 52,
        'MA' => 21,
        'MG' => 31,
        'MS' => 50,
        'MT' => 51,
        'PA' => 15,
        'PB' => 25,
        'PE' => 26,
        'PI' => 22,
        'PR' => 41,
        'RJ' => 33,
        'RN' => 24,
        'RO' => 11,
        'RR' => 14,
        'RS' => 43,
        'SC' => 42,
        'SE' => 28,
        'SP' => 35,
        'TO' => 17,
        'SVAN' => 91
    ];

    /**
     * xUFlist
     *
     * @var array
     */
    protected array $xUFlist = [
        '12' => 'AC',
        '27' => 'AL',
        '13' => 'AM',
        '91' => 'AN',
        '16' => 'AP',
        '29' => 'BA',
        '23' => 'CE',
        '53' => 'DF',
        '32' => 'ES',
        '52' => 'GO',
        '21' => 'MA',
        '31' => 'MG',
        '50' => 'MS',
        '51' => 'MT',
        '15' => 'PA',
        '25' => 'PB',
        '26' => 'PE',
        '22' => 'PI',
        '41' => 'PR',
        '33' => 'RJ',
        '24' => 'RN',
        '11' => 'RO',
        '14' => 'RR',
        '43' => 'RS',
        '42' => 'SC',
        '28' => 'SE',
        '35' => 'SP',
        '17' => 'TO',
        '91' => 'SVAN'
    ];

    /**
     *
     * @param array $config
     * @param Certificate $cert
     */
    public function __construct(?array $config, ?Certificate $cert)
    {
        $this->date = date('Y-m-d');
        if (is_array($config) && ! empty($config) && $cert !== null) {
            $this->config($config, $cert);
        }
    }

    /**
     * Set configuration to use in this instance
     *
     * @param array $cfg
     * @return SystemMessage
     */
    public function config(array $config, Certificate $cert)
    {
        /**
         * Check if there's all required fields in $config array
         */
        $missing = array_diff_key(array_flip([
            'mod',
            'version',
            'saveFiles',
            'xUF',
            // 'xUFAut',
            'tpAmb',
            'CNPJ'
        ]), $config);
        /**
         * If some required field is missing
         */
        if (! empty($missing)) {
            throw new \Exception("You must provide all required fields in configuration. This fields are missing: " . implode(', ', array_flip($missing)));
        } /**
         * If there is a field 'date' check if it has a valid value
         */
        else if (isset($config['date']) && ! Variable::date()->validate($config['date'])) {
            throw new \Exception("Date '{$config['date']}' is not a valid date in format YYY-MM-DD.");
        } /**
         * Check if there is sme service available for document model
         */
        else if (! isset($this->servicesAvailable[$config['mod']]) || empty($this->servicesAvailable[$config['mod']])) {
            throw new \Exception("There is no services available for document model {$config['mod']}");
        } /**
         * Check if schema path is valid
         */
        else if (isset($config['schemaPath']) && (! file_exists($config['schemaPath']) || //
        ! is_dir($config['schemaPath']) || //
        ! is_readable($config['schemaPath']))) {
            throw new \Exception("Schema path '{$config['schemaPath']}' is not valid.");
        }
        /**
         * Clear information about authorizing webservice 
         */
        $this->xUFAut = null;
        /**
         * Set values to class attributes
         */
        foreach ($config as $k => $value) {
            if (property_exists($this, $k)) {
                $this->{$k} = $value;
            }
        }
        // If UF code is not explicit set
        if ($this->cUF === null) {
            $this->cUF = $this->cUFlist[$this->xUF];
        }
        $this->cert = $cert;
    }

    /**
     *
     * @param string $service
     * @return SystemMessage
     */
    public function prepare(string $service): SystemMessage
    {
        /**
         * Check if service is available for this document model
         */
        if (! in_array($service, $this->servicesAvailable[$this->mod])) {
            return new SystemMessage("Service {$service} is not available for document type {$this->mod}", // Message
            '1', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
        /**
         * Read WS settings for this DFe type
         */
        if ($this->config === null || empty($this->config)) {
            switch ($this->mod) {
                case 23:
                    $this->config = Config::get('dfe.gnre.' . Strings::integer($this->version));
                    $this->urlPortal = 'http://www.gnre.pe.gov.br/webservice';
                    break;
                case 55:
                case 65:
                    $this->config = Config::get('dfe.nfe.' . Strings::integer($this->version));
                    $this->urlPortal = 'http://www.portalfiscal.inf.br/nfe';
                    break;
                case 57:
                    $this->config = Config::get('dfe.cte.' . Strings::integer($this->version));
                    $this->urlPortal = 'http://www.portalfiscal.inf.br/cte';
                    break;
                case 58:
                    $this->config = Config::get('dfe.mdfe.' . Strings::integer($this->version));
                    $this->urlPortal = 'http://www.portalfiscal.inf.br/mdfe';
                    break;
                default:
                    return false;
            }
        }
        $this->xMod = $this->listMod[$this->mod];

        /**
         * Defines the authorizing webservice to be used
         */
        $type = $this->contingency ? 'contingency' : 'normal';
        if ($this->xUFAut === null) {
            $this->xUFAut = Arrays::get($this->baseConfig, "{$this->mod}.{$type}.{$this->xUF}");
        }
        /**
         * Query the authorizing UF parameters
         */
        $dadosAut = Arrays::get($this->config, "{$this->tpAmb}.{$this->xUFAut}.{$service}");
        if ($dadosAut === null) {
            return new SystemMessage("There is no configuration available for '{$this->xUFAut}' in '{$service}' service.", // Message
            '1', // System code
            SystemMessage::MSG_ERROR, // System status code
            false); // System status
        }
        // Get version
        $this->urlVersion = $dadosAut['version'];
        // Get from the service url
        $this->urlWsdl = $dadosAut['url'];
        // Get from service url
        $this->urlService = $service;
        // Get method
        $this->urlMethod = $dadosAut['method'];
        // Prepare service namespace
        $this->urlOperation = $dadosAut['operation'];
        // Prepare method namespace
        switch ($this->mod) {
            case 23:
                $this->urlNamespace = sprintf("%s/%s", $this->urlPortal, $this->urlOperation);
                break;
            default:
                $this->urlNamespace = sprintf("%s/wsdl/%s", $this->urlPortal, $this->urlOperation);
                break;
        }
        return new SystemMessage("OK", // Message
        '1', // System code
        SystemMessage::MSG_OK, // System status code
        true); // System status
    }

    /**
     * getHeader
     *
     * @param string $mod
     * @return Xml|null
     */
    public function getHeader(): ?Xml
    {
        switch ($this->mod) {
            case 23:
                return new Xml("<gnreCabecMsg xmlns=\"http://www.gnre.pe.gov.br/wsdl/{$this->urlMethod}\"><versaoDados>{$this->urlVersion}</versaoDados></gnreCabecMsg>");
            case 55:
            case 65:
                return new Xml("<nfeCabecMsg xmlns=\"{$this->urlNamespace}\"><cUF>{$this->cUF}</cUF><versaoDados>{$this->urlVersion}</versaoDados></nfeCabecMsg>");
            case 57:
                return new Xml("<cteCabecMsg xmlns=\"{$this->urlNamespace}\"><cUF>{$this->cUF}</cUF><versaoDados>{$this->urlVersion}</versaoDados></cteCabecMsg>");
            case 58:
                return new Xml("<mdfeCabecMsg xmlns=\"{$this->urlNamespace}\"><cUF>{$this->cUF}</cUF><versaoDados>{$this->urlVersion}</versaoDados></mdfeCabecMsg>");
            default:
                return null;
        }
    }

    /**
     *
     * @param Xml $body
     * @param bool $addMethod
     * @return SystemMessage
     */
    protected function send(Xml $body, ?bool $addMethod = null): SystemMessage
    {
        /**
         * Prevent erros processing if parser class does not exists
         */
        $parser = __NAMESPACE__ . '\\' . ucfirst($this->xMod) . '\ParserResponse';
        if (! class_exists($parser)) {
            throw new \Exception("Parser class {$parser} does not exists.");
        }
        /**
         * Initialize a Soap object
         *
         * @var \Inspire\Dfe\Soap $soap
         */
        $soap = new Soap([
            'cert' => $this->cert,
            'namespace' => $this->urlNamespace,
            'method' => $this->urlMethod,
            'service' => $this->urlService,
            'wsdl' => $this->urlWsdl,
            'mod' => $this->mod
        ]);
        /**
         * request => Save file sent to WS
         * response => Save WS response on file
         * document => Save document file, like authorized and canceled
         */
        $paths = $this->parsePath($body);
        /**
         * Send request
         *
         * @var \Inspire\Support\Message\System\SystemMessage $result
         */
        $result = $soap->send($this->getHeader(), $body, $addMethod);
        $result->setExtra([
            'paths' => $paths
        ]);
        /**
         * If there is no problem to connect server
         */
        if ($result->isOk()) {
            /**
             * Process response with ParserResponse
             *
             * @var mixed $parsed
             */
            $parsed = call_user_func([
                $parser,
                $this->urlService
            ], new Xml($result->__toString()));
            $result->addExtra([
                'parse' => $parsed,
                'xml' => $result->__toString()
            ]);
        }
        return $result;
    }

    /**
     * Create the path using predefined variables
     * Allowed variables:
     * :DOC => DFe name (gnre/nfe/cte/mdfe)
     * :CNPJ => CNPJ of issuer
     * :YEAR => Year from current date or custom date set in 'config' method
     * :MONTH => Month from current date or custom date set in 'config' method
     * :DAY => Day from current date or custom date set in 'config' method
     *
     * @return mixed
     */
    private function parsePath(Xml $xml): array
    {
        $resp = [
            'request' => null,
            'response' => null,
            'document' => null
        ];
        if (! $this->saveFiles) {
            return $resp;
        }
        $saveDocument = false;
        switch ($this->urlService) {
            case 'CteRecepcaoEvento':
            case 'MDFeRecepcaoEvento':
                $match = [];
                if (preg_match('/tpEvento>(.*?)<\/tpEvento/', $xml->getXml(), $match) == 1) {
                    switch ($match[1]) {
                        case '110110':
                            $resp = Config::get("dfe.{$this->xMod}.paths.{$this->tpAmb}.event.evcce");
                            break;
                        case '110111':
                            $resp = Config::get("dfe.{$this->xMod}.paths.{$this->tpAmb}.event.cancel");
                            break;
                        case '110180':
                            $resp = Config::get("dfe.{$this->xMod}.paths.{$this->tpAmb}.event.evce");
                            break;
                        case '110181':
                            $resp = Config::get("dfe.{$this->xMod}.paths.{$this->tpAmb}.event.evcancce");
                            break;
                        default:
                            throw new \Exception("Invalid event type for {$this->xMod}: {$match[1]}");
                    }
                    $saveDocument = true;
                }
                break;
            case 'CteRecepcao':
            case 'CteRecepcaoOS':
            case 'MDFeRecepcao':
            case 'MDFeRecepcaoSinc':
                $resp = [
                    'request' => Config::get("dfe.{$this->xMod}.paths.{$this->tpAmb}.{$this->urlService}.request"),
                    'response' => Config::get("dfe.{$this->xMod}.paths.{$this->tpAmb}.{$this->urlService}.response"),
                    'document' => Config::get("dfe.{$this->xMod}.paths.{$this->tpAmb}.signed")
                ];
                $saveDocument = true;
                break;
            case 'CteRetRecepcao':
            case 'MDFeRetRecepcao':
                $resp = [
                    'request' => Config::get("dfe.{$this->xMod}.paths.{$this->tpAmb}.{$this->urlService}.request"),
                    'response' => Config::get("dfe.{$this->xMod}.paths.{$this->tpAmb}.{$this->urlService}.response"),
                    'document' => Config::get("dfe.{$this->xMod}.paths.{$this->tpAmb}.authorized"),
                    'signed' => Config::get("dfe.{$this->xMod}.paths.{$this->tpAmb}.signed")
                ];
                $saveDocument = true;
                break;
            default:
                $resp = [
                    'request' => Config::get("dfe.{$this->xMod}.paths.{$this->tpAmb}.{$this->urlService}.request"),
                    'response' => Config::get("dfe.{$this->xMod}.paths.{$this->tpAmb}.{$this->urlService}.response")
                ];
                break;
        }
        if (! isset($resp['request']) || empty($resp['request']) || ! preg_match('/^(\.\.\/(?:\.\.\/)*)?(?!.*?\/\/)(?!(?:.*\/)?\.+(?:\/|$)).+$/', $resp['request'], $match) > 0) {
            throw new \Exception("You must provide a valid request file path when Save mode is enabled.");
        } else if (! isset($resp['response']) || empty($resp['response']) || ! preg_match('/^(\.\.\/(?:\.\.\/)*)?(?!.*?\/\/)(?!(?:.*\/)?\.+(?:\/|$)).+$/', $resp['response'], $match) > 0) {
            throw new \Exception("You must provide a valid request file path when Save mode is enabled.");
        } else if ($saveDocument && (! isset($resp['document']) || empty($resp['document']) || ! preg_match('/^(\.\.\/(?:\.\.\/)*)?(?!.*?\/\/)(?!(?:.*\/)?\.+(?:\/|$)).+$/', $resp['document'], $match) > 0)) {
            throw new \Exception("You must provide a valid request file path when Save mode is enabled.");
        }
        foreach ($resp as &$folder) {
            $folder = str_replace([
                ':DOC',
                ':CNPJ',
                ':YEAR',
                ':MONTH',
                ':DAY'
            ], [
                $this->xMod,
                $this->CNPJ,
                strtok($this->date, '-'),
                strtok('-'),
                strtok('-')
            ], $folder);
            if (! file_exists($folder) && ! mkdir($folder, 0755, true)) {
                throw new \Exception("Directory {$folder} does not exists and was not possible to create it.");
            }
        }
        return $resp;
    }

    /**
     * Get public key from certificate
     *
     * @return string
     */
    protected function getPublicKey(): string
    {
        return preg_replace('/[\n\r]/', '', preg_replace('/-----.*[\n]?/', '', $this->cert->getPubKey()));
    }

    /**
     * Returns the acronym of the UF based on the IBGE code
     *
     * @param string $cUF
     * @return NULL|string
     */
    public function getXuf(string $cUF): ?string
    {
        return $this->xUFlist[$cUF] ?? null;
    }

    /**
     * Returns the code of the UF based on the IBGE acronym
     *
     * @param string $xUF
     * @return string|NULL
     */
    public function getCuf(string $xUF): ?string
    {
        return $this->cUFlist[$xUF] ?? null;
    }

    /**
     * Adapted of NFePHP project
     *
     * @param string $xml
     * @param string $modal
     * @return string|NULL
     */
    public function getModalXML(string $xml, string $modal): ?string
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->loadXML($xml);
        $modal = $dom->getElementsByTagName($modal)->item(0);
        switch ($this->mod) {
            case 57:
                $modal->setAttribute('xmlns', 'http://www.portalfiscal.inf.br/cte');
                break;
            case 58:
                $modal->setAttribute('xmlns', 'http://www.portalfiscal.inf.br/mdfe');
                break;
        }
        $xml = $dom->saveXML($modal);
        if ($xml) {
            return $xml;
        }
        return null;
    }

    /**
     * Get URL of SEFAZ portal
     *
     * @return string|NULL
     */
    public function getUrlPortal(): ?string
    {
        return $this->urlPortal;
    }

    /**
     * FULLY PHP based Signer for XML files this signer only uses native PHP commands to sign XML files
     *
     * @param string $docxml
     * @param string $tagid
     * @param string $attr
     * @param string $parent
     * @throws \Exception
     * @return string
     */
    public function sign(string $docxml, string $tagid, string $attr = 'Id', ?string $parent = null): string
    {
        if (! function_exists('openssl_get_privatekey')) {
            throw new \Exception('Não existe a função openssl_get_privatekey');
        }
        if (empty($tagid)) {
            throw new \Exception('Uma tag deve ser indicada para que seja assinada!');
        }
        if (empty($docxml)) {
            throw new \Exception('Um xml deve ser passado para que seja assinado!');
        }
        // Obter o chave privada para assinatura
        $fp = fopen($this->cert->getPriKeyFile(), "r");
        $priv_key = fread($fp, 8192);
        fclose($fp);
        $pkeyid = openssl_get_privatekey($priv_key);
        // carrega o documento no DOM
        $xmldoc = new \DOMDocument();
        $xmldoc->preservWhiteSpace = false; // elimina espaços em branco
        $xmldoc->formatOutput = false;
        $xmldoc->loadXML(str_replace([
            "\r\n",
            "\n",
            "\r",
            "\t"
        ], '', $docxml), //
        LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        if (empty($parent)) {
            $root = $xmldoc->documentElement;
        } else {
            $root = $xmldoc->getElementsByTagName($parent)->item(0);
        }
        // extrair a tag com os dados a serem assinados
        $node = $xmldoc->getElementsByTagName($tagid)->item(0);
        // extrai os dados da tag para uma string
        $dados = $node->C14N(false, false, NULL, NULL);
        // calcular o hash dos dados
        $hashValue = hash('sha1', $dados, true);
        // converte o valor para base64 para serem colocados no xml
        $digValue = base64_encode($hashValue);
        // monta a tag da assinatura digital
        $Signature = $xmldoc->createElementNS($this->sign['nsDSIG'] ?? null, 'Signature');
        $root->appendChild($Signature);
        $SignedInfo = $xmldoc->createElement('SignedInfo');
        $Signature->appendChild($SignedInfo);
        // Cannocalization
        $newNode = $xmldoc->createElement('CanonicalizationMethod');
        $SignedInfo->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->sign['nsCannonMethod'] ?? null);
        // SignatureMethod
        $newNode = $xmldoc->createElement('SignatureMethod');
        $SignedInfo->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->sign['nsSignatureMethod'] ?? null);
        // Reference
        $Reference = $xmldoc->createElement('Reference');
        $SignedInfo->appendChild($Reference);

        $id = trim($node->getAttribute($attr));
        if (! empty($id)) {
            $Reference->setAttribute('URI', '#' . $id);
        } else {
            $Reference->setAttribute('URI', '');
        }
        if (isset($this->sign['nsTransformMethod']) && is_array($this->sign['nsTransformMethod'])) {
            // Transforms
            $Transforms = $xmldoc->createElement('Transforms');
            $Reference->appendChild($Transforms);
            foreach ($this->sign['nsTransformMethod'] as $Algorithm) {
                $newNode = $xmldoc->createElement('Transform');
                $Transforms->appendChild($newNode);
                $newNode->setAttribute('Algorithm', $Algorithm);
            }
        }
        // DigestMethod
        $newNode = $xmldoc->createElement('DigestMethod');
        $Reference->appendChild($newNode);
        $newNode->setAttribute('Algorithm', $this->sign['nsDigestMethod'] ?? null);
        // DigestValue
        $newNode = $xmldoc->createElement('DigestValue', $digValue);
        $Reference->appendChild($newNode);
        // extrai os dados a serem assinados para uma string
        $dados = $SignedInfo->C14N(false, false, NULL, NULL);
        // inicializa a variavel que irá receber a assinatura
        $signature = '';
        // executa a assinatura digital usando o resource da chave privada
        $resp = openssl_sign($dados, $signature, $pkeyid);
        // codifica assinatura para o padrao base64
        $signatureValue = base64_encode($signature);
        // SignatureValue
        $newNode = $xmldoc->createElement('SignatureValue', $signatureValue);
        $Signature->appendChild($newNode);
        // KeyInfo
        $KeyInfo = $xmldoc->createElement('KeyInfo');
        $Signature->appendChild($KeyInfo);
        // X509Data
        $X509Data = $xmldoc->createElement('X509Data');
        $KeyInfo->appendChild($X509Data);
        // X509Certificate
        $newNode = $xmldoc->createElement('X509Certificate', $this->getPublicKey());
        $X509Data->appendChild($newNode);
        // grava na string o objeto DOM
        $docxml = $xmldoc->saveXML();
        // libera a memoria
        openssl_free_key($pkeyid);
        // retorna o documento assinado
        return $docxml;
    }
}
