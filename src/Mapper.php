<?php

namespace Xml\Data;

/**
 * Class Mapper
 * @package Xml\Data
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
final class Mapper
{
    /**
     * @var array
     */
    private $mappedData = [];

    /**
     * @var string
     */
    private $xml;

    /**
     * @var bool
     */
    private $withOpenTag = false;

    /**
     * @var XmlNamespace
     */
    private $xmlNamespace;

    /**
     * @var string
     */
    private $rootNode;

    /**
     * Mapper constructor.
     * @param XmlNamespace $xmlNamespace
     */
    public function __construct(XmlNamespace $xmlNamespace)
    {
        $this->xmlNamespace = $xmlNamespace;
    }

    /**
     * @param Subject $subject
     * @param null|string $nodeName
     * @return Mapper
     * @throws \Exception
     */
    static public function convert(Subject $subject, $nodeName = null)
    {
        if (null === $nodeName) {
            $nodeName = $subject->getNodeName();
        }

        if (!$nodeName) {
            throw new \Exception(get_class($subject) . ' Can not make model array with node name of null value');
        }

        $self = new self(new XmlNamespace());
        $self->mappedData[$nodeName] = $subject->getData();
        $self->rootNode = $nodeName;
        return $self;
    }

    /**
     * @return $this
     */
    public function withOpenTag()
    {
        $this->withOpenTag = true;
        return $this;
    }

    /**
     * @param string $attribute
     * @param string $uri
     * @return $this
     */
    public function addNamespace($attribute, $uri)
    {
        $this->xmlNamespace->addNamespace($attribute, $uri);
        return $this;
    }

    /**
     * @return Mapper
     */
    public function toXml()
    {
        $this->generateXml();
        return $this;
    }

    /**
     * @return string
     */
    public function getXmlString()
    {
        $this->generateXml();
        return $this->xml;
    }

    /**
     * @param string $filename
     * @return bool
     * @throws \Exception
     */
    public function write($filename)
    {
        return write_file($filename, $this->getXmlString());
    }

    /**
     * @return $this
     */
    private function generateXml()
    {
        $xml = $this->xml;
        if (!$xml)  {
            $xml =  array_to_xml($this->mappedData);
        }

        if ($this->xmlNamespace->hasNamespaces()) {
            $xml = sprintf(
                str_replace($this->getRootNode(true), '<' . $this->getRootNode() . '%s>', $xml),
                (string) $this->xmlNamespace
            );

            $this->xml = $xml;
        }

        if (true === $this->withOpenTag) {
            if (strpos($this->xml, $this->getOpenTag()) === false) {
                $this->xml = $this->getOpenTag() . PHP_EOL . $xml;
            }
        } else {
            $this->xml = $xml;
        }

        return $this;
    }

    /**
     * @return string
     */
    private function getOpenTag()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>';
    }

    /**
     * @param bool $withTag
     * @return string
     */
    private function getRootNode($withTag = false)
    {
        if ($withTag) {
            return "<{$this->rootNode}>";
        }

        return $this->rootNode;
    }
}
