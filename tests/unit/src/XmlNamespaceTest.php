<?php

namespace Xml\Data;

use PHPUnit\Framework\TestCase;

/**
 * Class XmlNamespaceTest
 * @package Xml\Data
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class XmlNamespaceTest extends TestCase
{
    /**
     * @var XmlNamespace
     */
    private $namespace;

    /**
     *  sub
     */
    protected function setUp()
    {
        parent::setUp();

        $this->namespace = new XmlNamespace();
    }

    /**
     * Test namespace
     */
    public function testNamespace()
    {
        $this->namespace->addNamespace('xsi', 'http://www.w3.org/1999/xhtml')
            ->addNamespace('xmlns', 'http://www.w3.org/2000/10/XMLSchema');

        $this->assertTrue($this->namespace->hasNamespaces());

        $expected = ' xsi="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/2000/10/XMLSchema"';
        $this->assertEquals($expected, (string) $this->namespace);
    }
}