# XML Data Mapper
A small library to generate xml from source data

# Installation

`composer require alaa-almaliki/xml-data-mapper`

# Example

see [Example](https://github.com/alaa-almaliki/xml-data-mapper/tree/master/example)

To run the example run the command  `php main.php`

### How it works

There is one class to be extended:

code

```php
// Check example folder to fully understand how it works

// the class order represents xml mode which contains xml text node

class Order extends \Xml\Data\Subject
{
    protected $nodeName = 'order'; // the node name is name of the xml node that contains text nodes
    
    /**
     * add callbacks
     */
    public function addCallbacks()
    {
        // you can manupilate the values presented in the xml
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
            'order_number' => 'order_id', // The mapping is done as 'xml_attribute' => 'data_source_attribute'
            'order_date' => 'created_at',
            'customer_id' => 'customer_id',
            'first_name' => 'customer_firstname',
            'last_name' => 'customer_lastname',
        ];
    }
}
```

Generating the XML 
```php
// Data source - where the keys are used for mapping - see class order above
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

$order = new Order(new \Xml\Data\CallbackStorage()); // order node
$order->setData($data['order']);
$lines = [];

foreach ($data['order']['items'] as $item) {
    $orderLine = new Line(new \Xml\Data\CallbackStorage()); // order line node
    $orderLine->setData($item);
    $lines[] = $orderLine;
}

$shippingAddress = new ShippingAddress(new \Xml\Data\CallbackStorage()); // shipping address node
$shippingAddress->setData($data['order']['shipping_address']);

$billingAddress = new BillingAddress(new \Xml\Data\CallbackStorage()); // billing address node
$billingAddress->setData($data['order']['billing_address']);

$payment = new Payment(new \Xml\Data\CallbackStorage()); // payment node
$payment->setData($data['order']['payment']);

$rootSubject = new RootSubject(new \Xml\Data\CallbackStorage()); // orders node - root

$rootSubject->addSubject($order); // add order node to the root node which is named order
$order->addSubjects($lines, 'lines'); // add lines nodes to the order node
$order->addSubject($billingAddress); // add billing address node to the order node
$order->addSubject($shippingAddress); // add shipping address node to the order node
$order->addSubject($payment); // add payment node to the order node

// generate the xml file
\Xml\Data\Mapper::convert($rootSubject)
    ->toXml()
    ->addNamespace('xsi', "http://www.w3.org/1999/xhtml")
    ->addNamespace('xmlns', "http://www.w3.org/2000/10/XMLSchema")
    ->withOpenTag()
    ->write(__DIR__ . '/resource/order.xml');        
```
# Running unit tests
`vendor/bin/phpunit -c phpunit.xml.dist`

# Contributions
Feel free to contribute if any bugs found or enhancements need to be done

# License
MIT