<?php

namespace Omnipay\BarclaysEpdq\Message;

use Omnipay\Tests\TestCase;

class EssentialCompletePurchaseRequestTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->getHttpRequest()->query->add(array('testGET' => true));
        $this->getHttpRequest()->request->add(array('testPOST' => true));

        $this->request = new EssentialCompletePurchaseRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );
    }

    public function testRequestDataGETMethod()
    {
        $this->request->initialize(array('callbackMethod' => 'GET'));

        $this->assertArrayHasKey('testGET', $this->request->getRequestData());
        $this->assertArrayNotHasKey('testPOST', $this->request->getRequestData());
    }

    public function testRequestDataPOSTMethod()
    {
        $this->request->initialize(array('callbackMethod' => 'POST'));

        $this->assertArrayHasKey('testPOST', $this->request->getRequestData());
        $this->assertArrayNotHasKey('testGET', $this->request->getRequestData());
    }
}
