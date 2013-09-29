<?php

namespace Exercise\GigyaBundle\Model;

/**
 * Defines that user is able to keep gigya required data
 */
interface GigyaUserInterface
{
    /**
     * @return string
     */
    public function getUid();

    /**
     * @param  string $uid
     * @return self
     */
    public function setUid($uid);

    /**
     * Should return raw password to send it to gigya
     *
     * @return string
     */
    public function getRawPassword();

    /**
     * @return string
     */
    public function getUsername();

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @return array
     */
    public function getGigyaProfile();
}
