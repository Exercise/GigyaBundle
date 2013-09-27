<?php

namespace Exercise\GigyaBundle\Model;

/**
 * Defines that user is able to keep gigya uid data
 */
interface GigyaUserInterface
{
    /**
     * @return integer
     */
    public function getId();

    /**
     * @return string
     */
    public function getUid();

    /**
     * @param  string $uid
     * @return self
     */
    public function setUid($uid);
}
