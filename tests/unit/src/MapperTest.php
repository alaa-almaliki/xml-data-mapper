<?php

namespace Xml\Data;

use PHPUnit\Framework\TestCase;

class MapperTest extends TestCase
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var Mapper
     */
    protected $mapper;

    protected function setUp()
    {
        parent::setUp();
        $this->data = [
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

        $order = new OrderMock(new \Xml\Data\CallbackStorage());
        $order->setData($this->data['order']);
        $lines = [];

        foreach ($this->data['order']['items'] as $item) {
            $orderLine = new LineMock(new \Xml\Data\CallbackStorage());
            $orderLine->setData($item);
            $lines[] = $orderLine;
        }

        $shippingAddress = new ShippingAddressMock(new \Xml\Data\CallbackStorage());
        $shippingAddress->setData($this->data['order']['shipping_address']);

        $billingAddress = new BillingAddressMock(new \Xml\Data\CallbackStorage());
        $billingAddress->setData($this->data['order']['billing_address']);

        $payment = new PaymentMock(new \Xml\Data\CallbackStorage());
        $payment->setData($this->data['order']['payment']);

        $rootSubject = new RootSubjectMock(new \Xml\Data\CallbackStorage());

        $rootSubject->addSubject($order);
        $order->addSubjects($lines, 'lines');
        $order->addSubject($billingAddress);
        $order->addSubject($shippingAddress);
        $order->addSubject($payment);
        $this->mapper = Mapper::convert($rootSubject);
    }

    public function testMapper()
    {
        $this->mapper->toXml();
        $xmlNoOpenTagNoNamespace = $this->mapper->getXmlString();

        $this->mapper->withOpenTag();
        $xmlWithOpenTag = $this->mapper->getXmlString();

        $xmlWithOpenTagAndNamespace = $this->mapper
            ->addNamespace('xsi', "http://www.w3.org/1999/xhtml")
            ->addNamespace('xmlns', "http://www.w3.org/2000/10/XMLSchema")
            ->getXmlString();

        $this->mapper->write(dirname(dirname(__FILE__)) . '/resources/generated.xml');

        $this->assertInstanceOf(Mapper::class, $this->mapper);
        $this->assertEquals(
            file_get_contents(dirname(dirname(__FILE__)) . '/resources/xml-no-tag-namespace.xml'),
            $xmlNoOpenTagNoNamespace
        );

        $this->assertEquals(
            file_get_contents(dirname(dirname(__FILE__)) . '/resources/xml-with-open-tag.xml'),
            $xmlWithOpenTag
        );

        $this->assertEquals(
            file_get_contents(dirname(dirname(__FILE__)) . '/resources/xml-with-open-tag-and-namespace.xml'),
            $xmlWithOpenTagAndNamespace
        );

        $this->assertEquals(
            file_get_contents(dirname(dirname(__FILE__)) . '/resources/xml-with-open-tag-and-namespace.xml'),
            file_get_contents(dirname(dirname(__FILE__)) . '/resources/generated.xml')
        );
    }
}

class RootSubjectMock extends \Xml\Data\Subject
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

class OrderMock extends Subject
{
    protected $nodeName = 'order';

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

class BillingAddressMock extends Subject
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

class LineMock extends Subject
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

class PaymentMock extends Subject
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

class ShippingAddressMock extends Subject
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