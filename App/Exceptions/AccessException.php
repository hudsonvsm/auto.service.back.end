<?php
namespace App\Exceptions;

use \Exception;
use \Throwable;

/**
 * Class AccessException
 *
 * @package App\Exceptions
 */
class AccessException extends Exception
{
    /**
     * AccessException constructor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}