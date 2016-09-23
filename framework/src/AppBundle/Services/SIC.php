<?php

namespace AppBundle\Services;

use AppBundle\Document\SICSocialInfo;
use AppBundle\Document\SocialCredentials;
use AppBundle\Document\SocialProvider;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Helpers\Mailer\Mailer;
use Helpers\Template\TemplateEngine;
use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Relationship\RegisterRelationship;
use SIC\RegisterSIC;
use SIC\RegisterSICPassword;
use SIC\RegisterSICProfile;
use SIC\RegisterSocialProvider;
use SIC\SICConfirmationHandler;
use Social\Provider\Providers;

/**
 * Class SIC
 *
 * @package AppBundle\Services
 */
class SIC
{
    /**
     * @var ObjectManager
     */
    private $documentManager;

    /**
     * SIC constructor.
     *
     * @param ManagerRegistry $documentManager
     */
    public function __construct(ManagerRegistry $documentManager)
    {
        $this->documentManager = $documentManager->getManager();
    }

    public function all()
    {
        return $this->documentManager->getRepository('AppBundle:SIC')->findAll();
    }
}