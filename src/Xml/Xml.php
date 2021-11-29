<?php
declare(strict_types = 1);
namespace Inspire\Dfe\Xml;

use Inspire\Core\Message\XmlMessage;

/**
 * Description of Parser
 *
 * @author aalves
 */
class Xml extends XmlMessage
{

    /**
     *
     * @param mixed $data
     * @throws \Exception
     */
    public function __construct($data)
    {
        /**
         * Load XML if URL is provided as XML
         */
        if (preg_match('~^https?://[^\s]+$~i', $data) || is_readable($data)) {
            $xmlData = file_get_contents($data);
        } else {
            $xmlData = $data;
        }
        // echo substr($xmlData, 0, 50) . PHP_EOL;
        // if (! Strings::startsWith($xmlData, '<?xml')) {
        /* $xmlData = "<?xml version=\"1.0\"?>{$xmlData}"; */
        // }
        // echo $xmlData . PHP_EOL;
        // $xml = new \XMLReader();
        // $xml->xml($xmlData);
        // $xml->setParserProperty(\XMLReader::VALIDATE, true);
        // while ($xml->read()) {
        // if (! $xml->isValid()) {
        // throw new \Exception("You must provide a valid XML.");
        // }
        // }
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        simplexml_load_string($xmlData);
        if (! empty(libxml_get_errors())) {
            throw new \Exception("You must provide a valid XML.");
        }
        $this->stringXml = $xmlData;
    }

    /**
     * Get string XML
     *
     * @return string
     */
    public function getXml(): string
    {
        return $this->stringXml;
    }

    /**
     * Remove XML declaration from every index of array or an input string
     *
     * @param string|array $xml
     * @return string|array
     */
    public static function clearXmlDeclaration($xml)
    {
        if (is_array($xml)) {
            return array_map(function ($v) {
                return trim(preg_replace("/<\?xml.+?\?>/", "", $v));
            }, $xml);
        }
        return trim(preg_replace("/<\?xml.+?\?>/", "", $xml));
    }

    /**
     * Convert array to XML
     *
     * @param array $data
     * @param string $root
     * @param bool $self
     * @return \Inspire\Dfe\Xml\Xml|string
     */
    public static function arrayToXml(array $data, ?string $root = null, bool $self = false)
    {
        if (($root === null || empty($root)) && count($data) == 1) {
            $root = array_keys($data)[0];
            $data = $data[$root];
        }
        $xml = \Spatie\ArrayToXml\ArrayToXml::convert($data, $root);
        if ($self) {
            return new Xml($xml);
        }
        return $xml;
    }

    /**
     * Convert XML to array
     *
     * @param string $xml
     * @return array
     */
    public static function xmlToArray(string $xml): array
    {
        return XML2Array::createArray($xml);
    }

    /**
     * Get DOMnode from XML
     *
     * @param string $xpath
     * @param int $index
     * @return array|\DOMNode|null
     */
    public function xpathXml(string $xpath, int $index = null)
    {
        $out = [];
        if ($index !== null) {
            $out = null;
        }
        $result = $this->xml->xpath("/{$xpath}");
        if ($result && ! empty($result)) {
            if ($index !== null) {
                if (isset($result[$index])) {
                    $out = $result[$index];
                } else {
                    $out = null;
                }
            } else {
                foreach ($result as $res) {
                    $out[] = $res;
                }
            }
        } else {
            $out = null;
        }
        return $out;
    }
}

