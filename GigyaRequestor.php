<?php

namespace Exercise\GigyaBundle;

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
     * @param string $apiKey
     * @param string $secretKey
     */
    public function __construct($apiKey, $secretKey)
    {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
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
        $response = $request->send();
        if ($response->getErrorCode() == 0) {
            return $response->getData();
        }

        throw new \GSException(sprintf("%s: %s", $response->getErrorCode(), $response->getErrorMessage()));
    }
}
