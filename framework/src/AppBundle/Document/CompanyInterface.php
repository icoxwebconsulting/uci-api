<?php

namespace AppBundle\Document;

/**
 * Interface Company
 *
 * @package AppBundle\Document
 */
interface CompanyInterface
{
    /**
     * @return SIC
     */
    public function getAssignedSIC():SIC;

    /**
     * @return string
     */
    public function getConformedName();

    /**
     * @return string
     */
    public function getIRSNumber();

    /**
     * @return string
     */
    public function getFiscalEndYear();

    /**
     * @return Address
     */
    public function getBusinessAddress():Address;

    /**
     * @return Address
     */
    public function getMailAddress():Address;

    /**
     * @return Owner
     */
    public function getOwner():Owner;

    /**
     * @return string
     */
    public function getId():string;

    /**
     * @return string
     */
    public function getStateOfIncorporation();

    /**
     * @return GeoLocation
     */
    public function getGeoLocation():GeoLocation;

    /**
     * @return string
     */
    public function getCIK():string;
}