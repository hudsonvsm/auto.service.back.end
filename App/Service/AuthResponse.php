<?php
namespace App\Service;

use App\Exceptions\AccessException;
use Mladenov\IResponse;

/**
 * Class AuthResponse
 * @package App\Service
 */
class AuthResponse implements IResponse
{
    private $values;
    public function __construct()
    {
        $this->values = array();
    }

    /**
     * @param string $data
     * @throws AccessException
     */
    public function parseData(string $data) : void
    {
        $parsedArrayData = json_decode($data, true);
        if ($parsedArrayData['error'] !== null) {
            throw new AccessException($parsedArrayData['error_description'], 401);
        }

        $this->values = $parsedArrayData;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }
}