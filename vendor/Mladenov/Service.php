<?php
namespace Mladenov;

use App\Exceptions\AccessException;

class Service implements IService
{
    private $request;
    private $response;

    /**
     * Service constructor.
     * @param IResponse $response
     * @param IRequest $request
     */
    public function __construct(IResponse $response, IRequest $request)
    {
        $this->request = $request;
        $this->response = $response;
    }


    /**
     * @return IResponse
     * @throws AccessException
     */
    public function executeCurl() : IResponse
    {
        $ch = curl_init();

        curl_setopt_array($ch, $this->request->getOptions());
        if (count($this->request->getHeaders())) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->request->getHeaders());
        }

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            $result = '{"Error":"' . curl_error($ch).'"}';
        }
        $this->response->parseData($result);

        curl_close ($ch);

        return $this->response;
    }

    /**
     * @param IRequest $request
     */
    public function setRequest(IRequest $request) : void
    {
        $this->request = $request;
    }

    /**
     * @param IResponse $response
     */
    public function setResponse(IResponse $response) : void
    {
        $this->response = $response;
    }
}