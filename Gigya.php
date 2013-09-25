<?php

namespace Exercise\GigyaBundle;

class Gigya
{
    const METHOD_GET_USER_INFO = 'socialize.getUserInfo';
    const METHOD_NOTIFY_LOGIN = 'socialize.notifyLogin';
    const METHOD_LOGOUT = 'socialize.logout';

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
     * Checks if user is logged in by uid
     *
     * @param  string  $uid
     * @return boolean
     */
    public function isLoggedIn($uid)
    {
        $response = $this->sendRequest(self::METHOD_GET_USER_INFO, array('uid' => $uid));

        return $response->getBool('isLoggedIn');
    }

    /**
     * @param  string $uid
     * @return GSResponse
     */
    public function login($uid)
    {
        return $this->sendRequest(self::METHOD_NOTIFY_LOGIN, array('siteUID' => $uid));
    }

    /**
     * @param  string $uid
     * @return GSResponse
     */
    public function logout($uid)
    {
        return $this->sendRequest(self::METHOD_LOGOUT, array('UID' => $uid));
    }

    /**
     * @param  string $method
     * @param  array $parameters
     * @return GSResponse
     * @throws \GSException If request failed
     */
    public function sendRequest($method, array $parameters = array())
    {
        $gsOject = new \GSObject();
        foreach ($parameters as $key => $value) {
            $gsOject->put($key, $value);
        }

        $request = new \GSRequest($this->apiKey, $this->secretKey, $method, $gsOject);
        $response = $request->send();
        if ($response->getErrorCode() == 0) {
            return $response->getData();
        }

        throw new \GSException($response->getErrorMessage());
    }
}
