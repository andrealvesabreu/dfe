<?php
declare(strict_types = 1);
namespace Inspire\Dfe\Parser;

/**
 * Description of Base
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

    /**
     * Response codes and messages.
     * Group messages by status type
     */
    protected static array $messageType = [
        0 => 'Error on message sent. Fix package before resending',
        1 => 'OK',
        2 => 'Wait before try again',
        3 => 'Temporary error on service',
        4 => 'Permanent error on service',
        5 => 'Permanent error with carrier registration',
        6 => 'Certificate error',
        7 => 'Permanent error on document' // When there is no way to perform some action with one document
    ];
}

