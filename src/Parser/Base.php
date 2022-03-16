<?php
declare(strict_types = 1);
namespace Inspire\Dfe\Parser;

/**
 * Description of ParserResponse
 *
 * @author aalves
 */
class Base
{

    /**
     * Set types of documents to return on DFe sist services
     *
     * @var array
     */
    protected static ?array $unpack = null;

    /**
     * Define what kind of XML can be returned from DFe sist services
     *
     * @param array $schemas
     */
    public static function getDistDfeSchemas(array $schemas): void
    {
        self::$unpack = array_flip($schemas);
    }
}

