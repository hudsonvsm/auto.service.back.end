<?php


namespace App\Service;

use Mladenov\IRequest;

class AuthRequest implements IRequest
{
    private $options;
    private $headers;

    public function __construct(array $options, array $headers)
    {
        $this->headers = $headers;
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }


}