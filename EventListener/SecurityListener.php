<?php

namespace Exercise\GigyaBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Exercise\GigyaBundle\Model\GigyaUserInterface;
use Exercise\GigyaBundle\Rest\Accounts;
use Exercise\GigyaBundle\Rest\IdentityStorage;
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
     * @var \Exercise\GigyaBundle\Rest\IdentityStorage
     */
    protected $storage;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $om;

    /**
     * @param Gigya $gigya
     */
    public function __construct(Accounts $gigya, IdentityStorage $storage, ObjectManager $om)
    {
        $this->gigya = $gigya;
        $this->storage = $storage;
        $this->om = $om;
    }

    /**
     * @param  InteractiveLoginEvent $event
     */
    public function onLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        if (!($user instanceof GigyaUserInterface)) {
            return;
        }

        $identifier = $this->gigya->getLoginIdentifier();
        if (!($uid = $user->getUid())) {
            $gigyaUser = $this->storage->findAccountsByField($identifier, $user->{sprintf("get%s", $identifier)}(), 1);
            if ($gigyaUser instanceof \GSObject) {
                $uid = $gigyaUser->getString('UID');
            }
        }

        if ($uid) {
            $user->setUid($uid);
            $this->gigya->login($user, $event->getRequest()->request->get('_password'));
            $this->om->flush($user);
        } else {
            $initRegistration = $this->gigya->initRegistration();
            $token = $initRegistration->getString('regToken');
            $registration = $this->gigya->register($user, $token);
            $user->setUid($registration->getString('UID'));
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
            $this->gigya->logout($uid);
        }
    }
}
