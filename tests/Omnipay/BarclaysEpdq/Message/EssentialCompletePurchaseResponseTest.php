<?php

namespace Omnipay\BarclaysEpdq\Message;

use Omnipay\Tests\TestCase;

class EssentialCompletePurchaseResponseTest extends TestCase
{

    public function testKnownStatus()
    {
        $response = new EssentialCompletePurchaseResponse(
            $this->getMockRequest(),
            array(
                'STATUS' => '2',
                'PAYID' => 'abc123',
            )
        );

        $this->assertSame("Authorisation refused", $response->getMessage());
        $this->assertNull($response->getNcError());
    }

    public function testUnknownStatus()
    {
        $response = new EssentialCompletePurchaseResponse(
            $this->getMockRequest(),
            array(
                'STATUS' => '453464',
                'PAYID' => 'abc123',
                'NCERROR' => '0'
            )
        );

        $this->assertNull($response->getMessage());
        $this->assertNull($response->getNcErrorPlus());
    }

    public function testNcErrors()
    {
        $response = new EssentialCompletePurchaseResponse(
            $this->getMockRequest(),
            array(
                'STATUS' => '453464',
                'PAYID' => 'abc123',
                'NCERROR' => '0',
                'NCERRORPLUS' => 'Invalid card'
            )
        );

        $this->assertSame('Invalid card', $response->getNcErrorPlus());
    }

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
