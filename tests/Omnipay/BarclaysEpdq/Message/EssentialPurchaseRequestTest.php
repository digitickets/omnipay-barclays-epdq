<?php

namespace Omnipay\BarclaysEpdq\Message;

use Omnipay\Tests\TestCase;

class EssentialPurchaseRequestTest extends TestCase
{

    public function testHash()
    {
        $request = new EssentialPurchaseRequest(
            \Mockery::mock('\Guzzle\Http\ClientInterface'),
            \Mockery::mock('\Symfony\Component\HttpFoundation\Request')
        );

        $shaIn = '4984352';
        $actualSha = 'E17DAAF601024E1E8C329FF180E58058603D2940';

        $stub = array(
            'ORDERID' => 234543,
            'AMOUNT' => 1000
        );

        $this->assertSame($actualSha, $request->calculateSha($stub, $shaIn));
    }

}
