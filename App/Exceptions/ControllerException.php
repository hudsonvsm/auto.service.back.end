<?php
namespace App\Exceptions;

use \Exception;
use \Throwable;

/**
 * Class ControllerException
 *
 * @package App\Exceptions
 */
class ControllerException extends Exception
{
    /**
     * ControllerException constructor.
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