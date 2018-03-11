<?php

namespace Xml\Data;

use PHPUnit\Framework\TestCase;

/**
 * Class AttributeTest
 * @package Xml\Data
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class AttributeTest extends TestCase
{
    /**
     * setup
     */
    protected function setUp()
    {
        parent::setUp();
    }

    public function testAttribute()
    {
        $attribute = Attribute::attribute('customer_name', 'full_name', 'Alaa Al-Maliki', function ($value) {
            return strtoupper($value);
        });

        $this->assertTrue($attribute instanceof Attribute);
        $this->assertTrue('customer_name' === $attribute->getOutputAttributeCode());
        $this->assertTrue('full_name' === $attribute->getInputAttributeCode());
        $this->assertTrue('ALAA AL-MALIKI' === $attribute->value());
    }
}