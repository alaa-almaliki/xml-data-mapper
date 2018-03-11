<?php

class RootSubject extends \Xml\Data\Subject
{
    protected $nodeName = 'orders';

    /**
     * @return array
     */
    public function getMappedAttributes()
    {
        return [];
    }
}