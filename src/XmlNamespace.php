<?php

namespace Xml\Data;

/**
 * Class XmlNamespace
 * @package Xml\Data
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
class XmlNamespace
{
    /**
     * @var array
     */
    protected $namespaces = [];

    /**
     * @param string $attribute
     * @param string $uri
     * @return $this
     */
    public function addNamespace($attribute, $uri)
    {
        $this->namespaces[$attribute] = $uri;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasNamespaces()
    {
        return count($this->namespaces) > 0;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $namespaces = '';
        foreach ($this->namespaces as $attribute => $uri) {
            $namespaces .= sprintf(' %s="%s"', $attribute, $uri);
        }

        return $namespaces;
    }
}