<?php
declare(strict_types=1);
namespace exceptions\DatabaseException;

use Exception;

class DatabaseException extends Exception
{

    public static function missingQueryMethod($queries = '') : static
    {
        return new static('Missing query methods: ' . $queries);
    }
}