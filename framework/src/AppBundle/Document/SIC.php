<?php

namespace AppBundle\Document;

/**
 * Class SIC
 *
 * @package AppBundle\Document
 */
class SIC implements SICInterface
{
    /**
     * @var
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $office;

    /**
     * @var string
     */
    protected $title;

    public function getId():string
    {
        return $this->id;
    }

    public function getCode():string
    {
        return $this->code;
    }

    public function getOffice():string
    {
        return $this->office;
    }

    public function getTitle():string
    {
        return $this->title;
    }
}