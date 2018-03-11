<?php

namespace Xml\Data;

use function foo\func;
use PHPUnit\Framework\TestCase;

/**
 * Class CallbackStorageTest
 * @package Xml\Data
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class CallbackStorageTest extends TestCase
{
    /**
     * Setup
     */
    protected function setUp()
    {
        parent::setUp();
    }

    public function testCallbackStorage()
    {
        $storage = new CallbackStorage();
        $storage->registerCallback('positive_number', function ($value) {
            return abs($value);
        });

        $storage->registerCallback('to_upper_case', function ($value) {
            return strtoupper($value);
        });

        $this->assertTrue(($storage->getCallbacks()) > 0);
        $this->assertTrue(is_callable($storage->getCallback('to_upper_case')));

        $storage->unregisterCallback('to_upper_case');
        $this->assertTrue(is_null($storage->getCallback('to_upper_case')));

        $storage->reset();
        $this->assertEmpty($storage->getCallbacks());
    }
}