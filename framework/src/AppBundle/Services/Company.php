<?php

namespace AppBundle\Services;

use Elastica\Query\BoolQuery;
use Elastica\Query\GeoDistanceRange;
use Elastica\Query\Match;
use Elastica\Query\MatchAll;
use Elastica\Query\Nested;
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
        $query = new BoolQuery();

        if ($text) {
            $idQuery = new Match();
            $idQuery->setFieldQuery('id', $text);
            $idQuery->setFieldParam('id', 'analyzer', 'custom_analyzer');
            $query->addShould($idQuery);

            $conformedNameQuery = new Match();
            $conformedNameQuery->setFieldQuery('conformedName', $text);
            $query->addShould($conformedNameQuery);

            $sicQuery = new Nested();
            $sicQuery->setPath('assignedSIC');
            $sicInnerBoolQuery = new BoolQuery();
            $sicCodeMatchQuery = new Match();
            $sicCodeMatchQuery->setField('assignedSIC.code', $text);
            $sicInnerBoolQuery->addMust($sicCodeMatchQuery);
            $sicTitleMatchQuery = new Match();
            $sicTitleMatchQuery->setField('assignedSIC.title', $text);
            $sicInnerBoolQuery->addMust($sicTitleMatchQuery);
            $sicQuery->setQuery($sicInnerBoolQuery);
            $query->addShould($sicQuery);
        } else {
            $all = new MatchAll();
            $all->setParams(array('boost' => 1));
            $query->addMust($all);
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
            $query->addFilter($geoLocationQuery);
        }

        if ($address) {
            $addressQuery = new Nested();
            $addressQuery->setPath('businessAddress');
            $addressInnerBoolQuery = new BoolQuery();

            if ($address['city']) {
                $cityQuery = new Match();
                $cityQuery->setField('businessAddress.city_raw', $address['city']);
                $addressInnerBoolQuery->addMust($cityQuery);
            }

            if ($address['state']) {
                $stateQuery = new Match();
                $stateQuery->setField('businessAddress.state_raw', $address['state']);
                $addressInnerBoolQuery->addMust($stateQuery);
            }

            if ($address['zipCode']) {
                $zipQuery = new Match();
                $zipQuery->setField('businessAddress.zip', $address['zipCode']);
                $addressInnerBoolQuery->addMust($zipQuery);
            }

            $addressQuery->setQuery($addressInnerBoolQuery);
            $query->addFilter($addressQuery);
        }

        return $this->finder->find($query);
    }
}