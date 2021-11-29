<?php
/**
 *  OpenLSS - Lighter Smarter Simpler
 *
 *	This file is part of OpenLSS.
 *
 *	OpenLSS is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Lesser General Public License as
 *	published by the Free Software Foundation, either version 3 of
 *	the License, or (at your option) any later version.
 *
 *	OpenLSS is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Lesser General Public License for more details.
 *
 *	You should have received a copy of the
 *	GNU Lesser General Public License along with OpenLSS.
 *	If not, see <http://www.gnu.org/licenses/>.
 */
declare(strict_types = 1);
namespace Inspire\Dfe\Xml;

use DOMDocument;
use Exception;

/**
 * Description of XML2Array
 *
 * XML2Array: A class to convert XML to array in PHP
 * It returns the array which can be converted back to XML using the Array2XML script
 * It takes an XML string or a DOMDocument object as an input.
 *
 * See Array2XML: http://www.lalit.org/lab/convert-php-array-to-xml-with-attributes
 *
 * Author : Lalit Patel
 * Website: http://www.lalit.org/lab/convert-xml-to-array-in-php-xml2array
 * License: Apache License 2.0
 * http://www.apache.org/licenses/LICENSE-2.0
 * Version: 0.1 (07 Dec 2011)
 * Version: 0.2 (04 Mar 2012)
 * Fixed typo 'DomDocument' to 'DOMDocument'
 *
 * Usage:
 * $array = XML2Array::createArray($xml);
 */
class XML2Array
{

    /**
     * Format output
     *
     * @var bool|null
     */
    private static ?bool $format_output = null;

    /**
     * XML version
     *
     * @var string|null
     */
    private static ?string $version = null;

    /**
     * Initialize the root XML node [optional]
     *
     * @param
     *            $version
     * @param
     *            $encoding
     * @param
     *            $format_output
     */
    protected static $xml = null;

    /**
     * Default DOMDocument encoding
     *
     * @var string
     */
    protected static $encoding = 'UTF-8';

    /**
     * Prefix
     *
     * @var string
     */
    protected static $prefix_attributes = '@';

    /**
     * Initialize DOMDocument object
     *
     * @param string $version
     * @param string $encoding
     * @param boolean $format_output
     */
    public static function init($version = '1.0', $encoding = 'UTF-8', $format_output = false)
    {
        self::$xml = new \DOMDocument(self::$version ?? $version, self::$encoding ?? $encoding);
        self::$xml->formatOutput = self::$format_output ?? $format_output;
    }

    /**
     * Convert an XML to Array
     *
     * @param string $node_name
     *            - name of the root node to be converted
     * @param
     *            int - Bitwise OR of the libxml option constants see @link http://php.net/manual/libxml.constants.php
     * @param array $arr
     *            - aray to be converterd
     * @param mixed $callback
     *            - callback function
     * @return array
     */
    public static function &createArray($input_xml, $options = 0, $callback = null)
    {
        $xml = self::getXMLRoot();
        if (is_string($input_xml)) {
            $parsed = $xml->loadXML($input_xml, $options);
            if (! $parsed) {
                throw new Exception('[XML2Array] Error parsing the XML string.');
            }
        } else {
            if (get_class($input_xml) != 'DOMDocument') {
                throw new Exception('[XML2Array] The input XML object should be of type: DOMDocument.');
            }
            $xml = self::$xml = $input_xml;
        }
        $array[$xml->documentElement->tagName] = self::convert($xml->documentElement, $callback);
        self::$xml = null; // clear the xml node in the class for 2nd time use.
        return $array;
    }

    /**
     * Convert an Array to XML
     *
     * @param mixed $node
     *            - XML as a string or as an object of DOMDocument
     * @param mixed $callback
     *            - callback function
     * @return mixed
     */
    protected static function &convert($node, $callback = null)
    {
        $output = array();

        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
                $output[static::$prefix_attributes . 'cdata'] = trim($node->textContent);
                break;

            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;

            case XML_ELEMENT_NODE:
                // for each child node, call the covert function recursively
                for ($i = 0, $m = $node->childNodes->length; $i < $m; $i ++) {
                    if ($callback !== null) {
                        $callback($m = $node->childNodes->length, $i);
                    }
                    $child = $node->childNodes->item($i);
                    $v = self::convert($child);
                    if (isset($child->tagName)) {
                        $t = $child->tagName;

                        // avoid fatal error if the content looks like '<html><body>You are being <a href="https://some.url">redirected</a>.</body></html>'
                        if (isset($output) && ! is_array($output)) {
                            continue;
                        }
                        // assume more nodes of same kind are coming
                        if (! isset($output[$t])) {
                            $output[$t] = array();
                        }
                        $output[$t][] = $v;
                    } else {
                        // check if it is not an empty text node
                        if ($v !== '') {
                            $output = $v;
                        }
                    }
                }

                if (is_array($output)) {
                    // if only one node of its kind, assign it directly instead if array($value);
                    foreach ($output as $t => $v) {
                        if (is_array($v) && count($v) == 1) {
                            $output[$t] = $v[0];
                        }
                    }
                    if (empty($output)) {
                        // for empty nodes
                        $output = '';
                    }
                }

                // loop through the attributes and collect them
                if ($node->attributes->length) {
                    $a = array();
                    foreach ($node->attributes as $attrName => $attrNode) {
                        $a[$attrName] = (string) $attrNode->value;
                    }
                    // if its an leaf node, store the value in @value instead of directly storing it.
                    if (! is_array($output)) {
                        $output = array(
                            static::$prefix_attributes . 'value' => $output
                        );
                    }
                    $output[static::$prefix_attributes . 'attributes'] = $a;
                }
                break;
        }
        return $output;
    }

    /*
     * Get the root XML node, if there isn't one, create it.
     */
    protected static function getXMLRoot()
    {
        if (empty(self::$xml)) {
            self::init();
        }
        return self::$xml;
    }

    /**
     * Enable/disable default format DOMDocument
     *
     * @param bool $format
     */
    public static function format(bool $format)
    {
        self::$format_output = $format;
    }

    /**
     * Set default encoding for DOMDocument
     *
     * @param bool $encoding
     */
    public static function encoding(string $encoding)
    {
        self::$encoding = $encoding;
    }

    /**
     * Set default version for DOMDocument
     *
     * @param bool $format
     */
    public static function version(bool $version)
    {
        self::$version = $version;
    }
}

