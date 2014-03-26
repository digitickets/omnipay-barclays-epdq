<?php

namespace Omnipay\BarclaysEpdq\Message;

use Omnipay\Tests\TestCase;

class EssentialPurchaseRequestTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->request = new EssentialPurchaseRequest(
            $this->getHttpClient(),
            $this->getHttpRequest()
        );

        $this->request->initialize(array(

        ));
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
}
