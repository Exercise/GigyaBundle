<?php

namespace Exercise\GigyaBundle\EventListener;

use Exercise\GigyaBundle\Model\GigyaUserInterface;
use Exercise\GigyaBundle\Gigya;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

class SecurityListener implements LogoutHandlerInterface
{
    /**
     * @var \Exercise\GigyaBundle\Gigya
     */
    protected $gigya;

    /**
     * @param Gigya $gigya
     */
    public function __construct(Gigya $gigya)
    {
        $this->gigya = $gigya;
    }

    /**
     * @param  InteractiveLoginEvent $event
     */
    public function onLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        if (($user instanceof GigyaUserInterface) && ($uid = $user->getUid())) {
            if (!$this->gigya->isLoggedIn($uid)) {
                $this->gigya->login($uid);
            }
        }
    }

    /**
     * @param  Request        $request
     * @param  Response       $response
     * @param  TokenInterface $token
     */
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        if ($token->getUser() instanceof GigyaUserInterface) {
            $uid = $token->getUser()->getUid();
            if ($this->gigya->isLoggedIn($uid)) {
                $this->gigya->logout($uid);
            }
        }
    }
}
