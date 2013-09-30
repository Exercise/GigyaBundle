<?php

namespace Exercise\GigyaBundle\Rest;

use Exercise\GigyaBundle\GigyaRequestor;
use Exercise\GigyaBundle\Model\GigyaUserInterface;

class Accounts
{
    const METHOD_INIT_REGISTRATION = 'accounts.initRegistration';
    const METHOD_FINALIZE_REGISTRATION = 'accounts.finalizeRegistration';
    const METHOD_NOTIFY_LOGIN = 'accounts.notifyLogin';
    const METHOD_REGISTER = 'accounts.register';
    const METHOD_RESET_PASSWORD = 'accounts.resetPassword';
    const METHOD_LOGIN = 'accounts.login';
    const METHOD_LOGOUT = 'accounts.logout';

    /**
     * @var \Exercise\GigyaBundle\GigyaRequestor
     */
    protected $requestor;

    /**
     * @var string
     */
    protected $loginIdentifier;

    /**
     * @param GigyaRequestor $requestor
     * @param string         $loginIdentifier
     */
    public function __construct(GigyaRequestor $requestor, $loginIdentifier)
    {
        $this->requestor = $requestor;
        $this->loginIdentifier = $loginIdentifier;
    }

    /**
     * @return string
     */
    public function getLoginIdentifier()
    {
        return $this->loginIdentifier;
    }

    /**
     * @param  GigyaUserInterface $user
     * @return GSResponse
     */
    public function notifyLogin(GigyaUserInterface $user, array $providerData = null)
    {
        $parameters = array();
        if ($providerData) {
            $providerParameters = array();
            foreach (array('authToken', 'tokenSecret', 'tokenExpiration') as $key) {
                if (array_key_exists($key, $providerData)) {
                    $providerParameters[$key] = $providerData[$key];
                }
            }
            $parameters = array(
                'providerSessions' => json_encode($providerParameters)
            );
        }

        return $this->requestor->sendRequest(self::METHOD_NOTIFY_LOGIN, array_merge($parameters, array(
            'siteUID' => $user->getUid()
        )));
    }

    /**
     * @return GSResponse
     */
    public function initRegistration()
    {
        return $this->requestor->sendRequest(self::METHOD_INIT_REGISTRATION);
    }

    /**
     * @param  GigyaUserInterface $user
     * @param  string             $token
     * @param  boolean            $finalize
     * @return GSResponse
     */
    public function register(GigyaUserInterface $user, $token, $finalize = true)
    {
        $profile = array_filter($user->getGigyaProfile(), function($value) {
            return $value;
        });

        return $this->requestor->sendRequest(self::METHOD_REGISTER, array(
            $this->loginIdentifier => $this->getIdentifier($user),
            'password'             => $user->getRawPassword(),
            'regToken'             => $token,
            'profile'              => $profile,
            'finalizeRegistration' => $finalize
        ), true);
    }

    /**
     * @param  string $token
     * @return GSResponse
     */
    public function finalizeRegistration($token)
    {
        return $this->requestor->sendRequest(self::METHOD_FINALIZE_REGISTRATION, array(
            'regToken' => $token
        ));
    }

    /**
     * @param  GigyaUserInterface $user
     * @param  string             $password
     * @return GSResponse
     */
    public function login(GigyaUserInterface $user, $password)
    {
        return $this->requestor->sendRequest(self::METHOD_LOGIN, array(
            'loginID'  => $this->getIdentifier($user),
            'password' => $password
        ), true);
    }

    /**
     * @param  string     $uid
     * @return GSResponse
     */
    public function logout($uid)
    {
        return $this->requestor->sendRequest(self::METHOD_LOGOUT, array(
            'UID' => $uid
        ));
    }

    /**
     * @param  GigyaUserInterface $user
     * @return string
     */
    public function getIdentifier(GigyaUserInterface $user)
    {
        if ($this->loginIdentifier == 'username') {
            return $user->getUsername();
        }

        return $user->getEmail();
    }

    /**
     * @param  GigyaUserInterface $user
     * @return GSResponse
     */
    public function resetPasswordToken(GigyaUserInterface $user)
    {
        return $this->requestor->sendRequest(self::METHOD_RESET_PASSWORD, array(
            'loginID'   => $this->getIdentifier($user),
            'sendEmail' => false
        ), true);
    }

    /**
     * @param  string $token
     * @param  string $password
     * @return GSResponse
     */
    public function resetPassword($token, $password)
    {
        return $this->requestor->sendRequest(self::METHOD_RESET_PASSWORD, array(
            'passwordResetToken' => $token,
            'newPassword'        => $password,
        ), true);
    }
}
