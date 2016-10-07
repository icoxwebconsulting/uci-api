<?php

namespace AppBundle\Services;

use AppBundle\Document\CompanySocialInfo;
use AppBundle\Document\SocialCredentials;
use AppBundle\Document\SocialProvider;
use Company\CompanyConfirmationHandler;
use Company\RegisterCompany;
use Company\RegisterCompanyPassword;
use Company\RegisterCompanyProfile;
use Company\RegisterSocialProvider;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Helpers\Mailer\Mailer;
use Helpers\Template\TemplateEngine;
use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Relationship\RegisterRelationship;
use Social\Provider\Providers;

/**
 * Class Company
 *
 * @package AppBundle\Services
 */
class Company
{
    /**
     * @var ObjectManager
     */
    private $documentManager;

    /**
     * Company constructor.
     *
     * @param ManagerRegistry $documentManager
     */
    public function __construct(ManagerRegistry $documentManager)
    {
        $this->documentManager = $documentManager->getManager();
    }

    public function search()
    {
        return $this->documentManager->getRepository('AppBundle:Company')->findAll();
    }
}