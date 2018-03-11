<?php

class BillingAddress extends \Xml\Data\Subject
{
    protected $nodeName = 'billing_address';
    /**
     * @return array
     */
    public function getMappedAttributes()
    {
        return [
            'bill_to' => 'full_name',
            'billing_street' => 'street',
            'billing_city' => 'city',
            'billing_country' => 'country',
            'billing_postcode' => 'postcode'
        ];
    }
}