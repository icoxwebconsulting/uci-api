<?php

namespace AppBundle\Controller;

use AppBundle\Form\CompanySearchType;
use FOS\RestBundle\Controller\Annotations as FOSRestBundleAnnotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CompaniesController
 *
 * @package AppBundle\Controller
 *
 * @FOSRestBundleAnnotations\View()
 */
class CompaniesController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Response with all sics
     *
     * @param Request $request
     * @return array
     *
     * @ApiDoc(
     *  section="Company",
     *  description="Search",
     *  parameters={
     *      {"name"="text", "dataType"="string", "required"=false, "description"="search term"},
     *      {"name"="latitude", "dataType"="string", "required"=false, "description"="geo location latitude need to be used with longitude"},
     *      {"name"="longitude", "dataType"="string", "required"=false, "description"="geo location longitude need to be used with latitude"},
     *      {"name"="range", "dataType"="string", "required"=false, "description"="geo location range need to be used with latitude and longitude"},
     *      {"name"="city", "dataType"="string", "required"=false, "description"="filter by state"},
     *      {"name"="state", "dataType"="string", "required"=false, "description"="filter by city"},
     *      {"name"="zipCode", "dataType"="string", "required"=false, "description"="filter by zip code"}
     *  },
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "beta" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getSearchAction(Request $request)
    {
        $text = $request->query->get('text');
        $geoLocation = null;
        $latitude = $request->query->get('latitude');
        $longitude = $request->query->get('longitude');
        $range = $request->query->get('range');
        if ($latitude && $longitude && $range) {
            $geoLocation = array(
                "latitude" => $latitude,
                "longitude" => $longitude,
                "range" => $range,
            );
        }
        $address = null;
        $city = $request->query->get('city');
        $state = $request->query->get('state');
        $zipCode = $request->query->get('zipCode');
        if ($city || $state || $zipCode) {
            $address = array(
                "city" => $request->query->get('city'),
                "state" => $request->query->get('state'),
                "zipCode" => $request->query->get('zipCode'),

            );
        }

        return $this->get('uci.company')->search($text, $geoLocation, $address);
    }
}
