<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class Address
 *
 * @package AppBundle\Document
 */
class Address implements AddressInterface
{
    /**
     * @var null|string
     */
    private $street1;

    /**
     * @var null|string
     */
    private $street2;

    /**
     * @var null|string
     */
    private $city;

    /**
     * @var null|string
     */
    private $state;

    /**
     * @var null|string
     */
    private $zip;

    /**
     * @var null|string
     */
    private $phone;

    /**
     * @return string
     */
    public function getStreet1()
    {
        return $this->street1;
    }

    /**
     * @return string
     */
    public function getStreet2()
    {
        return $this->street2;
    }

    /**
     * @return string
     */
    public function getCityRaw()
    {
        return $this->getCity();
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getStateRaw()
    {
        return $this->getState();
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getFullAddress():string
    {
        return sprintf('%s %s, %s, %s', $this->street1, $this->street2, $this->city, $this->state);
    }
}