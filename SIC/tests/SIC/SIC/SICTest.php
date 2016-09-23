<?php

namespace tests\SIC\SIC;

use PHPUnit\Framework\TestCase;
use SIC\SIC\SIC;

/**
 * Class SICTest
 *
 * @package tests\SIC\SIC
 */
class SICTest extends TestCase
{
    public function testCanBeConstructed()
    {
        $id = uniqid();
        $code = "123";
        $office = "office";
        $title = "title";
        $sic = new SIC($id, $code, $office, $title);
        $this->assertEquals($id, $sic->getId());
        $this->assertEquals($code, $sic->getCode());
        $this->assertEquals($office, $sic->getOffice());
        $this->assertEquals($title, $sic->getTitle());
    }
}