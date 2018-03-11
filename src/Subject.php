<?php

namespace Xml\Data;

/**
 * Class Subject
 * @package Xml\Data
 * @author Alaa Al-Maliki <alaa.almaliki@gmail.com>
 */
abstract class Subject implements AttributesMapperInterface
{
    /**
     * @var CallbackStorage
     */
    protected $storage;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var string
     */
    protected $nodeName;

    /**
     * @var array
     */
    protected $subjects = [];

    /**
     * @return $this
     */
    protected function addCallbacks()
    {
        return $this;
    }

    /**
     * Object constructor.
     * @param CallbackStorage $storage
     * @param null|string $nodeName
     */
    public function __construct(CallbackStorage $storage, $nodeName = null)
    {
        $this->storage = $storage;

        if (null !== $nodeName) {
            $this->nodeName = $nodeName;
        }

        $this->addCallbacks();
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $customData = [];
        foreach ($this->getMappedAttributes() as $outputAttribute => $inputAttribute) {
            $callback = $this->getCallback($outputAttribute, $inputAttribute);
            $value = $this->getAttributeValue($outputAttribute, $inputAttribute, $data[$inputAttribute], $callback);
            $customData[$outputAttribute] = $value;
        }

        $this->data = $customData;
        return $this;
    }

    /**
     * @param Subject $subject
     * @param string|null $nodeName
     * @return $this
     * @throws \Exception
     */
    public function addSubject(Subject $subject, $nodeName = null)
    {
        if (null !== $nodeName) {
            $subject->setNodeName($nodeName);
        }

        if (!$subject->getNodeName()) {
            throw new \Exception('Subject must have a node name.');
        }

        $this->subjects[$subject->getNodeName()] = $subject;
        return $this;
    }

    /**
     * @param array $subjects
     * @param string $nodeName
     * @return $this
     */
    public function addSubjects(array $subjects, $nodeName)
    {
        $this->subjects[$nodeName] = $subjects;
        return $this;
    }

    /**
     * @return array
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * @return bool
     */
    public function hasSubjects()
    {
        return count($this->subjects) > 0;
    }

    /**
     * @return array
     */
    public function getData()
    {
        if ($this->hasSubjects()) {
            $this->recursiveData($this->subjects);
        }
        return $this->data;
    }

    /**
     * @param string $nodeName
     * @return $this
     */
    public function setNodeName($nodeName)
    {
        $this->nodeName = $nodeName;
        return $this;
    }

    /**
     * @return string
     */
    public function getNodeName()
    {
        return $this->nodeName;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->getData();
    }

    /**
     * @param string $outputAttribute
     * @param string $inputAttribute
     * @return callable|null
     */
    private function getCallback($outputAttribute, $inputAttribute)
    {
        $callback = $this->storage->getCallback($outputAttribute);
        if (null === $callback) {
            $callback = $this->storage->getCallback($inputAttribute);
        }

        return $callback;
    }

    /**
     * @param array $subjects
     * @param null|string $nodeName
     */
    private function recursiveData(array $subjects, $nodeName = null)
    {
        foreach ($subjects as $n => $subject) {
            if (is_array($subject)) {
                $this->recursiveData($subject, $n);
            } else {
                if (null !== $nodeName) {
                    $this->data[$nodeName][$n] = [$subject->getNodeName() => $subject->getData()];
                } else {
                    $this->data[$n] = $subject->getData();
                }
            }
        }
    }

    /**
     * @param string $outputAttribute
     * @param string $inputAttribute
     * @param string $value
     * @param callable|null $callback
     * @return string
     */
    private function getAttributeValue($outputAttribute, $inputAttribute, $value, $callback)
    {
        return Attribute::attribute($outputAttribute, $inputAttribute, $value, $callback)
            ->value();
    }
}