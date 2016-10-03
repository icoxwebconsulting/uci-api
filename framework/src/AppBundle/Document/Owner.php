<?php

namespace AppBundle\Document;

/**
 * Class Owner
 *
 * @package AppBundle\Document
 */
class Owner implements OwnerInterface
{
    /**
     * @var null|string
     */
    private $conformedName;

    /**
     * @var null|string
     */
    private $cik;

    /**
     * @var Address|null
     */
    private $mailAddress;

    /**
     * @return string
     */
    public function getConformedName()
    {
        return $this->conformedName;
    }

    /**
     * @return string
     */
    public function getCIK()
    {
        return $this->cik;
    }

    /**
     * @return string
     */
    public function getMailAddress()
    {
        return $this->mailAddress;
    }
}