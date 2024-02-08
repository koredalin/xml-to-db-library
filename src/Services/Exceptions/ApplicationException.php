<?php

namespace Library\Services\Exceptions;

use Exception;

/**
 * Description of ApplicationException
 *
 * @author H1
 */
class ApplicationException extends Exception
{
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Throwable $previous = null) {
        // some code

        // make sure everything is assigned properly
        $message = get_class($this).'. '.$message;
        parent::__construct($message, $code, $previous);
    }
}
