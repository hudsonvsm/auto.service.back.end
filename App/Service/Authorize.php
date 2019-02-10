<?php

namespace App\Service;

use Mladenov\IService;
use Mladenov\Service;

class Authorize
{
    private $service;

    /**
     * Authorize constructor.
     */
    public function __construct()
    {

    }

    /***
     * @param string $userNameAndPass in format "UserName:Password"
     */
    public function setAuthLoginService(string $userNameAndPass): void
    {
        $this->service = new Service(
            new LoginResponse(),
            new AuthRequest(
                array(
                    CURLOPT_URL => 'http://localhost/auth.server/token.php',
                    CURLOPT_POSTFIELDS => "grant_type=client_credentials",
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_POST => 1,
                    CURLOPT_USERPWD => $userNameAndPass,
                ),
                array(
                    'Content-Type: application/x-www-form-urlencoded'
                )));
    }

    /**
     * @param string $accessToken
     */
    public function setAuthAccessService(string $accessToken): void
    {
        $this->service = new Service(
            new AuthResponse(),
            new AuthRequest(
                array(
                    CURLOPT_URL => 'http://localhost/auth.server/resource.php',
                    CURLOPT_POSTFIELDS => "access_token=" . $accessToken,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_POST => 1,
                ),
                array(
                    'Content-Type: application/x-www-form-urlencoded'
                )));
    }

    /**
     * @return IService
     */
    public function getService() : IService
    {
        return $this->service;
    }
}