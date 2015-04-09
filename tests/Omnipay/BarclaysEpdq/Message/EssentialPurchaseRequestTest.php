<?php

namespace Omnipay\BarclaysEpdq\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Item;
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
        $item = new Item();
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

}
