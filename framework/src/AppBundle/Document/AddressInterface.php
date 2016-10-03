<?php

namespace AppBundle\Document;

/**
 * Interface Address
 *
 * @package AppBundle\Document
 */
Interface AddressInterface
{
    /**
     * @return string
     */
    public function getStreet1();

    /**
     * @return string
     */
    public function getStreet2();

    /**
     * @return string
     */
    public function getCity();

    /**
     * @return string
     */
    public function getState();

    /**
     * @return string
     */
    public function getZip();

    /**
     * @return string
     */
    public function getPhone();

    /**
     * @return string
     */
    public function getFullAddress():string;
}