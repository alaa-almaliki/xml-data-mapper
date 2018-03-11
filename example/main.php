<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/autoload.php';
require_once  dirname(dirname(__FILE__)) . '/src/Internal/functions.php';
$data = [
    'order' => [
        'order_id' => '1',
        'created_at' => '2018-03-04',
        'customer_id' => '123',
        'customer_firstname' => 'Alaa',
        'customer_lastname' => 'Al-Maliki',
        'shipping_address' => [
            'full_name' => 'Alaa Al-Maliki',
            'street' => '123 Wightfield Road',
            'city' => 'Manachester',
            'country' => 'United Kingdom',
            'postcode' => 'AB1 CD2'
        ],
        'billing_address' => [
            'full_name' => 'John Doe',
            'street' => '456 Crimson Avenue',
            'city' => 'London',
            'country' => 'United Kingdom',
            'postcode' => 'BQ1 DF2'
        ],
        'items' => [
            [
                'item_id' => '1',
                'qty' => 2,
                'name' => 'Blue Jeans',
                'sku' => 'BJ-123',
                'tax_code' => 'UK1',
                'total' => 12.98
            ],
            [
                'item_id' => '2',
                'qty' => 1,
                'name' => 'Small T-shirt',
                'sku' => 'SS-456',
                'tax_code' => 'UK1',
                'total' => 32.56
            ]
        ],
        'payment' => [
            'method' => 'by_card',
            'amount_paid' => 45.54,
        ]
    ]
];

$order = new Order(new \Xml\Data\CallbackStorage());
$order->setData($data['order']);
$lines = [];

foreach ($data['order']['items'] as $item) {
    $orderLine = new Line(new \Xml\Data\CallbackStorage());
    $orderLine->setData($item);
    $lines[] = $orderLine;
}

$shippingAddress = new ShippingAddress(new \Xml\Data\CallbackStorage());
$shippingAddress->setData($data['order']['shipping_address']);

$billingAddress = new BillingAddress(new \Xml\Data\CallbackStorage());
$billingAddress->setData($data['order']['billing_address']);

$payment = new Payment(new \Xml\Data\CallbackStorage());
$payment->setData($data['order']['payment']);

$rootSubject = new RootSubject(new \Xml\Data\CallbackStorage());

$rootSubject->addSubject($order);
$order->addSubjects($lines, 'lines');
$order->addSubject($billingAddress);
$order->addSubject($shippingAddress);
$order->addSubject($payment);

\Xml\Data\Mapper::convert($rootSubject)
    ->toXml()
    ->addNamespace('xsi', "http://www.w3.org/1999/xhtml")
    ->addNamespace('xmlns', "http://www.w3.org/2000/10/XMLSchema")
    ->withOpenTag()
    ->write(__DIR__ . '/resource/order.xml');


