<?php

namespace Exercise\GigyaBundle\Rest;

use Exercise\GigyaBundle\GigyaRequestor;

class Socialize
{
    const METHOD_GET_FRIENDS_INFO = 'socialize.getFriendsInfo';
    const METHOD_GET_USER_INFO = 'socialize.getUserInfo';
    const METHOD_NOTIFY_LOGIN = 'socialize.notifyLogin';
    const METHOD_LOGOUT = 'socialize.logout';
    const NOTIFY_REGISTRATION = 'socialize.notifyRegistration';

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
     * @param  string $uid
     * @return GSResponse
     */
    public function getFriends($uid)
    {
        return $this->requestor->sendRequest(self::METHOD_GET_FRIENDS_INFO, array('UID' => $uid));
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
     * @param $uid
     * @param $siteUID
     * @return \Exercise\GigyaBundle\GSResponse
     */
    public function notifyRegistration($uid, $siteUID)
    {
        return $this->requestor->sendRequest(self::NOTIFY_REGISTRATION, array(
            'UID' => $uid,
            'siteUID' => $siteUID
        ));
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
