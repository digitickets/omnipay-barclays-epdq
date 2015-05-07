<?php

namespace Omnipay\BarclaysEpdq\Message;

use Omnipay\BarclaysEpdq\Delivery;
use Omnipay\BarclaysEpdq\Feedback;
use Omnipay\BarclaysEpdq\Item as BarclaysEpdqItem;
use Omnipay\BarclaysEpdq\PageLayout;
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
        $card->setPostcode('13100');
        $card->setCity('Nicetown');
        $card->setCountry('TN');
        $card->setPhone('00999555666');
        $card->setAddress1('Home street');
        $card->setAddress2('Near the shop');

        $this->request->setCard($card);

        $data = $this->request->getData();

        $this->assertSame("Test Foo", $data['CN']);
        $this->assertSame("foo@bar.com", $data['EMAIL']);
        $this->assertSame("Test Company", $data['COM']);
        $this->assertSame("13100", $data['OWNERZIP']);
        $this->assertSame("Nicetown", $data['OWNERTOWN']);
        $this->assertSame("TN", $data['OWNERCTY']);
        $this->assertSame("00999555666", $data['OWNERTELNO']);
        $this->assertSame("Home street", $data['OWNERADDRESS']);
        $this->assertSame("Near the shop", $data['OWNERADDRESS2']);
    }

    public function testFeedbackData()
    {
        $feedback = new Feedback();
        $feedback->setComPlus('ORD_12369N');
        $feedback->setParamPlus('SessionID=126548354&ShopperID=73541312');

        $this->request->setFeedback($feedback);

        $data = $this->request->getData();

        $this->assertSame("ORD_12369N", $data['COMPLUS']);
        $this->assertSame("SessionID=126548354&ShopperID=73541312", $data['PARAMPLUS']);
    }

    public function testPageLayout()
    {
        $layout = new PageLayout();
        $layout->setBackgroundColor('#00FF00');
        $layout->setTitle('Payment page');
        $layout->setTableBackgroundColor('#EEFFFF');
        $layout->setTableTextColor('#221133');
        $layout->setHdTableBackgroundColor('#889900');
        $layout->setHdTableTextColor('#CCCCCC');
        $layout->setHdFontType('Verdana');
        $layout->setButtonBackgroundColor('#DD0033');
        $layout->setButtonTextColor('#553300');
        $layout->setFontType('Arial');
        $layout->setLogo('https://www.company/images/logo.png');

        $this->request->setPageLayout($layout);

        $data = $this->request->getData();

        $this->assertSame("#00FF00", $data['BGCOLOR']);
        $this->assertSame("Payment page", $data['TITLE']);
        $this->assertSame("#EEFFFF", $data['TBLBGCOLOR']);
        $this->assertSame("#221133", $data['TBLTXTCOLOR']);
        $this->assertSame("#889900", $data['HDTBLBGCOLOR']);
        $this->assertSame("#CCCCCC", $data['HDTBLTXTCOLOR']);
        $this->assertSame("Verdana", $data['HDFONTTYPE']);
        $this->assertSame("#DD0033", $data['BUTTONBGCOLOR']);
        $this->assertSame("#553300", $data['BUTTONTXTCOLOR']);
        $this->assertSame("Arial", $data['FONTTYPE']);
        $this->assertSame("https://www.company/images/logo.png", $data['LOGO']);
    }

    public function testDelivery()
    {
        $delivery = new Delivery();
        $delivery->setDeliveryMethod('By pigeon');
        $delivery->setDeliveryCost('25');
        $delivery->setDeliveryTaxCode('778');
        $delivery->setCuid('CLI-9963');
        $delivery->setCivility('Mr.');
        $delivery->setGender('M');
        $delivery->setInvoicingFirstName('Dan');
        $delivery->setInvoicingLastName('Proud');
        $delivery->setInvoicingLastName('Proud');
        $delivery->setInvoicingAddress1('9, My Street');
        $delivery->setInvoicingAddress2('Near the garage');
        $delivery->setInvoicingStreetNumber('5');
        $delivery->setInvoicingPostalCode('2074');
        $delivery->setInvoicingCity('Sometown');
        $delivery->setInvoicingCountryCode('TN');
        $delivery->setDeliveryNamePrefix('Ms.');
        $delivery->setDeliveryFirstName('Indra');
        $delivery->setDeliveryLastName('Brandt');
        $delivery->setDeliveryAddress1('There');
        $delivery->setDeliveryAddress2('Elsewhere');
        $delivery->setDeliveryStreetNumber('59');
        $delivery->setDeliveryPostalCode('13100');
        $delivery->setDeliveryCity('City');
        $delivery->setDeliveryCountryCode('FR');
        $delivery->setEmail('address@email.com');
        $delivery->setDeliveryFax('0020592');
        $delivery->setDeliveryPhone('00205977');
        $delivery->setDeliveryBirthDate('14/02/1980');

        $this->request->setDelivery($delivery);

        $data = $this->request->getData();

        $this->assertSame("By pigeon", $data['ORDERSHIPMETH']);
        $this->assertSame("25", $data['ORDERSHIPCOST']);
        $this->assertSame("778", $data['ORDERSHIPTAXCODE']);
        $this->assertSame("CLI-9963", $data['CUID']);
        $this->assertSame("Mr.", $data['CIVILITY']);
        $this->assertSame("M", $data['ECOM_CONSUMER_GENDER']);
        $this->assertSame("Dan", $data['ECOM_BILLTO_POSTAL_NAME_FIRST']);
        $this->assertSame("Proud", $data['ECOM_BILLTO_POSTAL_NAME_LAST']);
        $this->assertSame("9, My Street", $data['ECOM_BILLTO_POSTAL_STREET_LINE1']);
        $this->assertSame("Near the garage", $data['ECOM_BILLTO_POSTAL_STREET_LINE2']);
        $this->assertSame("5", $data['ECOM_BILLTO_POSTAL_STREET_NUMBER']);
        $this->assertSame("2074", $data['ECOM_BILLTO_POSTAL_POSTALCODE']);
        $this->assertSame("Sometown", $data['ECOM_BILLTO_POSTAL_CITY']);
        $this->assertSame("TN", $data['ECOM_BILLTO_POSTAL_COUNTRYCODE']);
        $this->assertSame("Ms.", $data['ECOM_SHIPTO_POSTAL_NAME_PREFIX']);
        $this->assertSame("Indra", $data['ECOM_SHIPTO_POSTAL_NAME_FIRST']);
        $this->assertSame("Brandt", $data['ECOM_SHIPTO_POSTAL_LAST_FIRST']);
        $this->assertSame("There", $data['ECOM_SHIPTO_POSTAL_STREET_LINE1']);
        $this->assertSame("Elsewhere", $data['ECOM_SHIPTO_POSTAL_STREET_LINE2']);
        $this->assertSame("59", $data['ECOM_SHIPTO_POSTAL_STREET_NUMBER']);
        $this->assertSame("13100", $data['ECOM_SHIPTO_POSTAL_POSTALCODE']);
        $this->assertSame("City", $data['ECOM_SHIPTO_POSTAL_CITY']);
        $this->assertSame("FR", $data['ECOM_SHIPTO_POSTAL_COUNTRYCODE']);
        $this->assertSame("address@email.com", $data['ECOM_SHIPTO_ONLINE_EMAIL']);
        $this->assertSame("0020592", $data['ECOM_SHIPTO_TELECOM_FAX_NUMBER']);
        $this->assertSame("00205977", $data['ECOM_SHIPTO_TELECOM_PHONE_NUMBER']);
        $this->assertSame("14/02/1980", $data['ECOM_SHIPTO_DOB']);
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
            $index = $key + 1;
            $this->assertSame($data["ITEMNAME$index"], $value->getName());
            $this->assertSame($data["ITEMDESC$index"], $value->getDescription());
            $this->assertSame($data["ITEMQUANT$index"], $value->getQuantity());
            $this->assertSame($data["ITEMPRICE$index"], $this->request->formatCurrency($value->getPrice()));
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
            $index = $key + 1;
            $this->assertSame($data["ITEMNAME$index"], $value->getName());
            $this->assertSame($data["ITEMDESC$index"], $value->getDescription());
            $this->assertSame($data["ITEMQUANT$index"], $value->getQuantity());
            $this->assertSame($data["ITEMPRICE$index"], $this->request->formatCurrency($value->getPrice()));
            $this->assertSame($data["ITEMID$index"], $item->getId());
            $this->assertSame($data["ITEMCOMMENTS$index"], $item->getComments());
            $this->assertSame($data["ITEMCATEGORY$index"], $item->getCategory());
            $this->assertSame($data["ITEMATTRIBUTES$index"], $item->getAttributes());
            $this->assertSame($data["ITEMUNITOFMEASURE$index"], $item->getUnitOfMeasure());
            $this->assertSame($data["ITEMDISCOUNT$index"], $this->request->formatCurrency($item->getDiscount()));
            $this->assertSame($data["ITEMWEIGHT$index"], $item->getWeight());
            $this->assertSame($data["ITEMVAT$index"], $this->request->formatCurrency($item->getVat()));
            $this->assertSame($data["ITEMVATCODE$index"], $item->getVatCode());
            $this->assertSame($data["ITEMFDMPRODUCTCATEG$index"], $item->getFraudModuleCategory());
            $this->assertSame($data["ITEMQUANTORIG$index"], $item->getMaximumQuantity());
        }
    }
}
