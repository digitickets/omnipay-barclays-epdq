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
                'NCERROR' => '0',
                'currency' => 'GBP',
                'amount' => 19.99,
                'PM' => 'CreditCard',
                'ACCEPTANCE' => 'test123',
                'CARDNO' => 'XXXXXXXXXXXX5115',
                'ED' => '0320',
                'CN' => 'Bill States',
                'TRXDATE' => '12/25/17',
                'BRAND' => 'Visa',
                'IPCTY' => 'TN',
                'CCCTY' => 'FR',
                'ECI' => 7,
                'CVCCheck' => 'NO',
                'AAVCheck' => 'YES',
                'VC' => 'NO',
                'IP' => '1.1.1.1'
            )
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());

        $this->assertSame('abc123', $response->getTransactionReference());
        $this->assertSame('GBP', $response->getCurrency());
        $this->assertSame(19.99, $response->getAmount());
        $this->assertSame('CreditCard', $response->getPaymentMethod());
        $this->assertSame('test123', $response->getAcceptance());
        $this->assertSame('XXXXXXXXXXXX5115', $response->getCardNumber());
        $this->assertSame('0320', $response->getExpiryDate());
        $this->assertSame('Bill States', $response->getCardHolder());
        $this->assertSame('12/25/17', $response->getTransactionDate());
        $this->assertSame('Visa', $response->getCardBrand());
        $this->assertSame('TN', $response->getIPCountry());
        $this->assertSame(7, $response->getECI());
        $this->assertSame('NO', $response->getCVCCheck());
        $this->assertSame('YES', $response->getAAVCheck());
        $this->assertSame('NO', $response->getVirtualCard());
        $this->assertSame('1.1.1.1', $response->getIPAddress());
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
