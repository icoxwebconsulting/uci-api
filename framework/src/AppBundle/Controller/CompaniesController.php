<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOSRestBundleAnnotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

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
     * @return array
     *
     * @ApiDoc(
     *  section="Company",
     *  description="Search",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "beta" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getSearchAction()
    {
        return $this->get('uci.company')->search();
    }
}
