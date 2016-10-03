<?php

namespace AppBundle\Document;

/**
 * Interface GeoLocation
 *
 * @package AppBundle\Document
 */
interface GeoLocationInterface
{
    /**
     * @return string
     */
    public function getLatitude():string;

    /**
     * @return string
     */
    public function getLongitude():string;
}