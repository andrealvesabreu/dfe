<?php
declare(strict_types = 1);
namespace Inspire\Dfe\Xml;

/**
 * Description of Parser
 *
 * @author aalves
 */
class XML2Array extends \LSS\XML2Array
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
    public static function init($version = '1.0', $encoding = 'UTF-8', $format_output = true)
    {
        self::$xml = new \DOMDocument(self::$version ?? $version, self::$encoding ?? $encoding);
        self::$xml->formatOutput = self::$format_output ?? $format_output;
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

