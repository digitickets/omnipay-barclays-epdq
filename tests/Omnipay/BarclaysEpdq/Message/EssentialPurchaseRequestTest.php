<?php

namespace Omnipay\BarclaysEpdq\Message;

use Omnipay\BarclaysEpdq\Item as BarclaysEpdqItem;
use Omnipay\Common\CreditCard;
use Omnipay\Common\Item as OmnipayItem;
use Omnipay\Common\ItemBag;
use Omnipay\Tests\TestCase;

class EssentialPurchaseRequestTest extends TestCase
{

    /**
     * @var EssentialPurchaseRequest
     */
    protected $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new EssentialPurchaseRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $this->requestStub = array(
            'clientId' => 'clientId',
            'amount' => 10.00,
            'currency' => 'GBP',
            'orderId' => '1111',
            'language' =>' en_US',
            'blankParam' => null
        );

        $this->request->initialize($this->requestStub);
    }

    public function testHashSuccess()
    {
        $shaIn = '4984352';
        $actualSha = 'E17DAAF601024E1E8C329FF180E58058603D2940';

        $stub = array(
            'ORDERID' => 234543,
            'AMOUNT' => 1000
        );

        $this->assertSame($actualSha, $this->request->calculateSha($stub, $shaIn));
    }

    public function testHashFailure()
    {
        $shaIn = 'incorrect-hash';
        $actualSha = 'E17DAAF601024E1E8C329FF180E58058603D2940';

        $stub = array(
            'ORDERID' => 234543,
            'AMOUNT' => 1000
        );

        $this->assertNotSame($actualSha, $this->request->calculateSha($stub, $shaIn));
    }

    public function testDataWithoutSha()
    {
        $data = $this->request->getData();

        $this->assertArrayNotHasKey('SHASIGN', $data);
    }

    public function testDataGetsCleaned()
    {
        $data = $this->request->getData();

        $this->assertArrayNotHasKey('blankParam', $data);
    }

    public function testDataWithSha()
    {
        $this->request->initialize(array_merge($this->requestStub, array(
            'shaIn' => 'password'
        )));

        $data = $this->request->getData();

        $this->assertArrayHasKey('SHASIGN', $data);
        $this->assertArrayNotHasKey('blankParam', $data);
    }

    public function testSettersAndGetters()
    {
        // backward compatiblity test
        $value = 'test-url';
        $this->assertSame($this->request, $this->request->setReturnUrl($value));
        $this->assertSame($value, $this->request->getReturnUrl());
        $this->assertSame($value, $this->request->getDeclineUrl());
        $this->assertSame($value, $this->request->getExceptionUrl());

        // new methods tests
        $declineUrl = 'decline-url';
        $this->assertSame($this->request, $this->request->setDeclineUrl($declineUrl));
        $this->assertSame($declineUrl, $this->request->getDeclineUrl());
        $this->assertNotSame($declineUrl, $this->request->getReturnUrl());

        $exceptionUrl = 'exception-url';
        $this->assertSame($this->request, $this->request->setExceptionUrl($exceptionUrl));
        $this->assertSame($exceptionUrl, $this->request->getExceptionUrl());
        $this->assertNotSame($exceptionUrl, $this->request->getReturnUrl());
    }

    public function testCardDetails()
    {
        $card = new CreditCard();
        $card->setName('Test Foo');
        $card->setEmail('foo@bar.com');
        $card->setCompany('Test Company');

        $this->request->setCard($card);

        $data = $this->request->getData();

        $this->assertSame("Test Foo", $data['CN']);
        $this->assertSame("foo@bar.com", $data['EMAIL']);
        $this->assertSame("Test Company", $data['COM']);
    }

    public function testItems()
    {
        // backward compatibity test using \Omnipay\Common\Item
        $item = new OmnipayItem();
        $item->setName('Foo 1');
        $item->setDescription('Bar description.');
        $item->setPrice(5.00);
        $item->setQuantity(2);

        $bag = new ItemBag(array(
            $item
        ));

        $this->request->setItems($bag);

        $data = $this->request->getData();

        foreach ($bag->all() as $key => $value) {
            /** @var Item $value */
            $this->assertSame($data["ITEMNAME$key"], $value->getName());
            $this->assertSame($data["ITEMDESC$key"], $value->getDescription());
            $this->assertSame($data["ITEMQUANT$key"], $value->getQuantity());
            $this->assertSame($data["ITEMPRICE$key"], $this->request->formatCurrency($value->getPrice()));
        }
    }

    public function testEpdqItems()
    {
        // epdq item implementation test using \Omnipay\BarclaysEpdq\Item
        $item = new BarclaysEpdqItem();
        $item->setName('Article 1');
        $item->setDescription('Lifetime subscription');
        $item->setPrice(99.98);
        $item->setQuantity(15);
        $item->setId('IS56302');
        $item->setComments('We be delivered in time after validation.');
        $item->setCategory('Discounted Items');
        $item->setAttributes('{heavy:no,virtual:yes}');
        $item->setUnitOfMeasure('years');
        $item->setDiscount(0.02);
        $item->setWeight(0.1);
        $item->setVat(12.3);
        $item->setVatCode(5.63);
        $item->setFraudModuleCategory('FX523R');
        $item->setMaximumQuantity(1.0);

        $bag = new ItemBag(array(
            $item
        ));

        $this->request->setItems($bag);

        $data = $this->request->getData();

        foreach ($bag->all() as $key => $value) {
            /** @var BarclaysEpdqItem $value */
            $this->assertSame($data["ITEMNAME$key"], $value->getName());
            $this->assertSame($data["ITEMDESC$key"], $value->getDescription());
            $this->assertSame($data["ITEMQUANT$key"], $value->getQuantity());
            $this->assertSame($data["ITEMPRICE$key"], $this->request->formatCurrency($value->getPrice()));
            $this->assertSame($data["ITEMID$key"], $item->getId());
            $this->assertSame($data["ITEMCOMMENTS$key"], $item->getComments());
            $this->assertSame($data["ITEMCATEGORY$key"], $item->getCategory());
            $this->assertSame($data["ITEMATTRIBUTES$key"], $item->getAttributes());
            $this->assertSame($data["ITEMUNITOFMEASURE$key"], $item->getUnitOfMeasure());
            $this->assertSame($data["ITEMDISCOUNT$key"], $this->request->formatCurrency($item->getDiscount()));
            $this->assertSame($data["ITEMWEIGHT$key"], $item->getWeight());
            $this->assertSame($data["ITEMVAT$key"], $this->request->formatCurrency($item->getVat()));
            $this->assertSame($data["ITEMVATCODE$key"], $item->getVatCode());
            $this->assertSame($data["ITEMFDMPRODUCTCATEG$key"], $item->getFraudModuleCategory());
            $this->assertSame($data["ITEMQUANTORIG$key"], $item->getMaximumQuantity());
        }
    }

}
