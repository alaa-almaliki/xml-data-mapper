<?php

class Payment extends \Xml\Data\Subject
{
    protected $nodeName = 'payment';

    /**
     * @return array
     */
    public function getMappedAttributes()
    {
        return [
            'payment_method' => 'method',
            'amount_paid' => 'amount_paid',
        ];
    }
}