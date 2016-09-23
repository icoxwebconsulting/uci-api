<?php

namespace SIC\SIC;

/**
 * Class SIC
 *
 * @package SIC\SIC
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

    /**
     * SIC constructor.
     *
     * @param string $id
     * @param string $code
     * @param string $office
     * @param string $title
     */
    public function __construct(string $id, string $code, string $office, string $title)
    {
        $this->id = $id;
        $this->code = $code;
        $this->office = $office;
        $this->title = $title;
    }

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