<?php

class ShippingAddress extends \Xml\Data\Subject
{
    protected $nodeName = 'shipping_address';

    /**
     * @return array
     */
    public function getMappedAttributes()
    {
        return [
            'ship_to' => 'full_name',
            'shipping_street' => 'street',
            'shipping_city' => 'city',
            'shipping_country' => 'country',
            'shipping_postcode' => 'postcode'
        ];
    }
}