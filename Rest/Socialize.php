<?php

namespace Users\Sites\Exercise\GigyaBundle\Rest;

use Exercise\GigyaBundle\GigyaRequestor;

class Socialize
{
    const SOCIALIZE_GET_USER_INFO = 'socialize.getUserInfo';
    const SOCIALIZE_NOTIFY_LOGIN = 'socialize.notifyLogin';
    const SOCIALIZE_LOGOUT = 'socialize.logout';

    /**
     * @param GigyaRequestor
     */
    public function __construct(GigyaRequestor $requestor)
    {
        $this->requestor = $requestor;
    }

    /**
     * @param  string $uid
     * @return GSResponse
     */
    public function getUser($uid)
    {
        return $this->requestor->sendRequest(self::METHOD_GET_USER_INFO, array('UID' => $uid));
    }

    /**
     * Checks if user is logged in by uid
     *
     * @param  string  $uid
     * @return boolean
     * @throws \GSException
     */
    public function isLoggedIn($uid)
    {
        try {
            return $this->getUser($uid)->getBool('isLoggedIn');
        } catch (\GSException $e) {
            return false;
        }
    }

    /**
     * @param  string $uid
     * @return GSResponse
     */
    public function login($uid)
    {
        return $this->requestor->sendRequest(self::METHOD_NOTIFY_LOGIN, array('siteUID' => $uid));
    }

    /**
     * @param  string $uid
     * @return GSResponse
     */
    public function logout($uid)
    {
        return $this->requestor->sendRequest(self::METHOD_LOGOUT, array('UID' => $uid));
    }
}
