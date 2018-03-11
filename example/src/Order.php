<?php

class Order extends \Xml\Data\Subject
{
    protected $nodeName = 'order';

    public function addCallbacks()
    {
        $this->storage->registerCallback('first_name', function ($value) {
            return strtoupper($value);
        });

        $this->storage->registerCallback('last_name', function ($value) {
            return strtoupper($value);
        });
    }

    /**
     * @return array
     */
    public function getMappedAttributes()
    {
        return [
            'order_number' => 'order_id',
            'order_date' => 'created_at',
            'customer_id' => 'customer_id',
            'first_name' => 'customer_firstname',
            'last_name' => 'customer_lastname',
        ];
    }
}