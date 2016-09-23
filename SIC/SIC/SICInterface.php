<?php

namespace SIC\SIC;

/**
 * Interface SIC
 *
 * @package SIC\SIC
 */
interface SICInterface
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