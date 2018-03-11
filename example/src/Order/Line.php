<?php

class Line extends \Xml\Data\Subject
{
    protected $nodeName = 'line';
    /**
     * @return array
     */
    public function getMappedAttributes()
    {
        return [
            'line_item_id' => 'item_id',
            'qty_ordered' => 'qty',
            'item_name' => 'name',
            'item_code' => 'sku',
            'tax_id' => 'tax_code',
            'gross_total' => 'total'
        ];
    }
}