<?php

namespace AppBundle\Document;

/**
 * Class Company
 *
 * @package AppBundle\Document
 */
class Company implements CompanyInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var null|string
     */
    private $cik;

    /**
     * @var null|string
     */
    private $conformedName;

    /**
     * @var null|string
     */
    private $irsNumber;

    /**
     * @var null|string
     */
    private $stateOfIncorporation;

    /**
     * @var null|string
     */
    private $fiscalEndYear;

    /**
     * @var Address
     */
    private $businessAddress;

    /**
     * @var Address
     */
    private $mailAddress;

    /**
     * @var Owner
     */
    private $owner;

    /**
     * @var GeoLocation
     */
    private $geoLocation;

    /**
     * @var SIC|null
     */
    private $assignedSIC;

    /**
     * @return SIC
     */
    public function getAssignedSIC():SIC
    {
        return $this->assignedSIC;
    }

    /**
     * @return string
     */
    public function getConformedName()
    {
        return $this->conformedName;
    }

    /**
     * @return string
     */
    public function getIRSNumber()
    {
        return $this->irsNumber;
    }

    /**
     * @return string
     */
    public function getFiscalEndYear()
    {
        return $this->fiscalEndYear;
    }

    /**
     * @return Address
     */
    public function getBusinessAddress():Address
    {
        return $this->businessAddress;
    }

    /**
     * @return Address
     */
    public function getMailAddress():Address
    {
        return $this->mailAddress;
    }

    /**
     * @return Owner
     */
    public function getOwner():Owner
    {
        return $this->owner;
    }

    /**
     * @return string
     */
    public function getId():string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStateOfIncorporation()
    {
        return $this->stateOfIncorporation;
    }

    /**
     * @return GeoLocation
     */
    public function getGeoLocation():GeoLocation
    {
        return $this->geoLocation;
    }

    /**
     * @return string
     */
    public function getCIK():string
    {
        return $this->cik;
    }

    /**
     * @return array
     */
    public function getGeoPoint():array
    {
        return array(
            'lat' => $this->geoLocation->getLatitude(),
            'lon' => $this->geoLocation->getLongitude(),
        );
    }
}