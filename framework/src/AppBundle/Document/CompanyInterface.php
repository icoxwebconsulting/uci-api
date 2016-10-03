<?php

namespace AppBundle\Document;

/**
 * Interface Company
 *
 * @package AppBundle\Document
 */
interface CompanyInterface
{
    /**
     * @return string
     */
    public function getId():string;

    /**
     * @return string
     */
    public function getCode():string;

    /**
     * @return string
     */
    public function getOffice():string;

    /**
     * @return string
     */
    public function getTitle():string;
}