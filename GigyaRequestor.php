<?php

namespace Exercise\GigyaBundle;

use Symfony\Bridge\Monolog\Logger;

class GigyaRequestor
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var \Symfony\Bridge\Monolog\Logger
     */
    protected $logger;

    /**
     * @param string $apiKey
     * @param string $secretKey
     */
    public function __construct($apiKey, $secretKey, Logger $logger)
    {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
        $this->logger = $logger;
    }

    /**
     * @param  string  $method
     * @param  array   $parameters
     * @param  boolean $https
     * @return GSResponse
     * @throws \GSException If request failed
     */
    public function sendRequest($method, array $parameters = array(), $https = false)
    {
        $gsOject = new \GSObject();
        foreach ($parameters as $key => $value) {
            $gsOject->put($key, $value);
        }

        $request = new \GSRequest($this->apiKey, $this->secretKey, $method, $gsOject, $https);
        $request->setCurlOptionsArray(array(
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        $params = json_encode($request->getParams()->serialize());
        $requestLogEntry = sprintf("method: %s; params: %s", $method, $params);
        $response = $request->send();
        $responseLogEntry = sprintf("response: %s", $response->getResponseText());
        $this->logger->debug($requestLogEntry);
        $this->logger->debug($responseLogEntry);
        if ($response->getErrorCode() == 0) {
            return $response->getData();
        }

        throw new \GSException(sprintf("%s: %s", $response->getErrorCode(), $response->getErrorMessage()));
    }
}
