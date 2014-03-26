<?php

namespace Omnipay\BarclaysEpdq\Message;

use Omnipay\Tests\TestCase;

class EssentialCompletePurchaseResponseTest extends TestCase
{
    public function testCompletePurchaseSuccess()
    {
        $response = new EssentialCompletePurchaseResponse(
            $this->getMockRequest(),
            array(
                'STATUS' => '5',
                'PAYID' => 'abc123',
                'NCERROR' => '0'
            )
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());

        $this->assertSame('abc123', $response->getTransactionReference());
        $this->assertSame('0', $response->getNcError());

        $this->assertSame(5, $response->getStatusCode());
        $this->assertSame("Authorised", $response->getMessage());
    }

    public function testCompletePurchaseFailure()
    {
        $response = new EssentialCompletePurchaseResponse(
            $this->getMockRequest(),
            array(
                'STATUS' => '0',
                'PAYID' => null,
            )
        );

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());

        $this->assertSame(0, $response->getStatusCode());
        $this->assertSame("Incomplete or invalid", $response->getMessage());
    }

    public function testCompletePurchaseInvalid()
    {
        $response = new EssentialCompletePurchaseResponse($this->getMockRequest(), array());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }
}
