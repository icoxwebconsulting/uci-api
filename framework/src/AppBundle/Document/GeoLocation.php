<?php

namespace AppBundle\Document;

/**
 * Class GeoLocation
 *
 * @package AppBundle\Document
 */
class GeoLocation implements GeoLocationInterface
{
    /**
     * @var string
     */
    private $latitude;

    /**
     * @var string
     */
    private $longitude;

    /**
     * @return string
     */
    public function getLatitude():string
    {
        return $this->latitude;
    }

    /**
     * @return string
     */
    public function getLongitude():string
    {
        return $this->longitude;
    }
}