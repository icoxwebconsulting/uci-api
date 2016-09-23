<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOSRestBundleAnnotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Class SICsController
 *
 * @package AppBundle\Controller
 *
 * @FOSRestBundleAnnotations\View()
 */
class SicsController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Response with all sics
     *
     * @return array
     *
     * @ApiDoc(
     *  section="SIC",
     *  description="Response with all sics",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "beta" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getAction()
    {
        return $this->get('uci.sic')->all();
    }
}
