<?php

namespace Xml\Data;

/**
 * Class Attribute
 * @package Xml\Data
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
final class Attribute
{
    /**
     * @var string
     */
    protected $outputAttributeCode;

    /**
     * @var string
     */
    protected $inputAttributeCode;

    /**
     * @var string
     */
    protected $value;

    /**
     * Attribute constructor.
     * @param string $outputAttributeCode
     * @param string $inputAttributeCode
     * @param $value
     */
    public function __construct($outputAttributeCode, $inputAttributeCode, $value)
    {
        $this->outputAttributeCode = $outputAttributeCode;
        $this->inputAttributeCode = $inputAttributeCode;
        $this->value = $value;
    }

    /**
     * @param string $outputAttributeCode
     * @param string $inputAttributeCode
     * @param string|int|bool $value
     * @param callable|null $callback
     * @return Attribute
     */
    static public function attribute($outputAttributeCode, $inputAttributeCode, $value, callable $callback = null)
    {
        if (null !== $callback) {
            $value = $callback($value);
        }

        return new self($outputAttributeCode, $inputAttributeCode, $value);
    }

    /**
     * @return string
     */
    public function getOutputAttributeCode()
    {
        return $this->outputAttributeCode;
    }

    /**
     * @return string
     */
    public function getInputAttributeCode()
    {
        return $this->inputAttributeCode;
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }
}
