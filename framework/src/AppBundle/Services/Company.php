<?php

namespace AppBundle\Services;

use Elastica\Query\BoolQuery;
use Elastica\Query\GeoDistanceRange;
use Elastica\Query\Match;
use Elastica\Query\Terms;
use FOS\ElasticaBundle\Finder\TransformedFinder;

/**
 * Class Company
 *
 * @package AppBundle\Services
 */
class Company
{
    /**
     * @var TransformedFinder
     */
    private $finder;

    /**
     * Company constructor.
     *
     * @param TransformedFinder $finder
     */
    public function __construct(TransformedFinder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * Search
     *
     * @param string|null $text
     * @param array|null $geoLocation
     * @param array|null $address
     * @return array
     */
    public function search($text, $geoLocation, $address):array
    {
        $boolQuery = new BoolQuery();

        if ($text) {
            $idQuery = new Match();
            $idQuery->setFieldQuery('id', $text);
            $boolQuery->addShould($idQuery);

            $assignedSICCodeQuery = new Match();
            $assignedSICCodeQuery->setFieldQuery('assignedSIC.code', $text);
            $boolQuery->addShould($assignedSICCodeQuery);

            $assignedSICTitleQuery = new Match();
            $assignedSICTitleQuery->setFieldQuery('assignedSIC.title', $text);
            $boolQuery->addShould($assignedSICTitleQuery);

            $conformedNameQuery = new Match();
            $conformedNameQuery->setFieldQuery('conformedName', $text);
            $boolQuery->addShould($conformedNameQuery);
        }

        if ($geoLocation) {
            $geoPoint = array(
                'lat' => $geoLocation['latitude'],
                'lon' => $geoLocation['longitude'],
            );
            $range = array(
                GeoDistanceRange::RANGE_TO => $geoLocation['range'],
            );
            $geoLocationQuery = new GeoDistanceRange('getGeoPoint', $geoPoint, $range);
            $boolQuery->addFilter($geoLocationQuery);
        }

        if ($address) {
            if ($address['city']) {
                $cityQuery = new Terms();
                $cityQuery->setTerms('businessAddress.city', array($address['city']));
                $boolQuery->addMust($cityQuery);
            }

            if ($address['state']) {
                $stateQuery = new Terms();
                $stateQuery->setTerms('businessAddress.state', array($address['state']));
                $boolQuery->addFilter($stateQuery);
            }

            if ($address['zipCode']) {
                $zipCodeQuery = new Terms();
                $zipCodeQuery->setTerms('businessAddress.zip', array($address['zipCode']));
                $boolQuery->addFilter($zipCodeQuery);
            }
        }

        return $this->finder->find($boolQuery);
    }
}