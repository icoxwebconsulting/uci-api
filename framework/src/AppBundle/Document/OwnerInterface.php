<?php

namespace AppBundle\Document;

/**
 * Interface Owner
 *
 * @package AppBundle\Document
 */
interface OwnerInterface
{
    /**
     * @return string
     */
    public function getConformedName();

    /**
     * @return string
     */
    public function getCIK();

    /**
     * @return string
     */
    public function getMailAddress();
}