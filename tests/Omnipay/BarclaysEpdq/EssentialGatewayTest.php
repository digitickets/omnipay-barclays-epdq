<?php

namespace Omnipay\BarclaysEpdq;

use Omnipay\Tests\GatewayTestCase;

class EssentialGatewayTest extends GatewayTestCase
{

    /**
     * @var EssentialGateway
     */
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new EssentialGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'clientId' => 'fake-id',
            'orderId' => '1395830608',
            'currency' => 'GBP',
            'amount' => '10.00',
            'returnUrl' => 'https://www.example.com/return',
            'shaOut' => '$Lue6v7IQiS.5xy?hMh'
        );
    }

    public function testSettersAndGetters()
    {
        $vars = array('shaIn', 'shaOut');


        foreach ($vars as $var) {
            $value = uniqid();

            $setMethod = sprintf("set%s", ucfirst($var));
            $getMethod = sprintf("get%s", ucfirst($var));

            $this->assertSame($this->gateway, $this->gateway->$setMethod($value));
            $this->assertSame($value, $this->gateway->$getMethod());
        }

        $value = 'test-url';
        $this->assertSame($this->gateway, $this->gateway->setReturnUrl($value));
        $this->assertSame($value, $this->gateway->getReturnUrl());
        $this->assertSame($value, $this->gateway->getDeclineUrl());
        $this->assertSame($value, $this->gateway->getExceptionUrl());
    }

    public function testPurchase()
    {
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertContains('https://payments.epdq.co.uk/ncol/prod/order', $response->getRedirectUrl());
    }

    public function testCompletePurchaseSuccess()
    {
        $this->getHttpRequest()->request->replace(array(
            'orderID'       => '1395830608',
            'currency'      => 'GBP',
            'amount'        => '10',
            'PM'            => 'CreditCard',
            'ACCEPTANCE'    => 'test123',
            'STATUS'        => '5',
            'CARDNO'        => 'XXXXXXXXXXXX1111',
            'ED'            => '0316',
            'CN'            => 'Sam',
            'TRXDATE'       => '03/26/14',
            'PAYID'         => '29575472',
            'NCERROR'       => '0',
            'BRAND'         => 'VISA',
            'SHASIGN'       => '425443996F67C928CDBF950DF9AD5E27EA1F330B'
        ));

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('29575472', $response->getTransactionReference());
        $this->assertSame('0', $response->getNcError());

        $this->assertSame(5, $response->getStatusCode());
        $this->assertSame("Authorised", $response->getMessage());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidResponseException
     */
    public function testCompletePurchaseInvalidShaComputation()
    {
        $this->getHttpRequest()->request->replace(
            array(
                'SHASIGN' => 'fake',
            )
        );

        $this->gateway->completePurchase($this->options)->send();
    }

    public function testCompletePurchaseError()
    {
        $this->getHttpRequest()->request->replace(array(
            'orderID'       => '1395830608',
            'currency'      => 'GBP',
            'amount'        => '10',
            'PM'            => 'CreditCard',
            'ACCEPTANCE'    => 'test123',
            'STATUS'        => '0',
            'CARDNO'        => 'XXXXXXXXXXXX1111',
            'ED'            => '0316',
            'CN'            => 'Sam',
            'TRXDATE'       => '03/26/14',
            'PAYID'         => '0',
            'NCERROR'       => '0',
            'BRAND'         => 'VISA',
            'SHASIGN'       => '229BEEA24B52D056399BCE3450FA4916E38179C6'
        ));

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertTrue(!$response->getTransactionReference());
    }
}
