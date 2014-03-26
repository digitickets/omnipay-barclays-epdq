<?php

namespace Omnipay\BarclaysEpdq\Message;

use Omnipay\Tests\TestCase;

class EssentialPurchaseResponseTest extends TestCase
{

    public function testPurchaseSuccess()
    {
        $response = new EssentialPurchaseResponse($this->getMockRequest(), array(
            'amount' => 1000,
            'returnUrl' => 'https://www.example.com/return',
        ));

        $this->getMockRequest()->shouldReceive('getEndpoint')->once()->andReturn('https://payments.epdq.co.uk/ncol/prod/orderstandard.asp');

        $this->assertFalse($response->isSuccessful());

        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());

        $this->assertSame('https://payments.epdq.co.uk/ncol/prod/orderstandard.asp', $response->getRedirectUrl());

        $this->assertSame('POST', $response->getRedirectMethod());

        $this->assertTrue(is_array($response->getRedirectData()));
    }
}
