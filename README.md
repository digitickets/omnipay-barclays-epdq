# Omnipay: Barclays ePDQ

**Barclays ePDQ driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/samvaughton/omnipay-barclays-epdq.png?branch=master)](https://travis-ci.org/samvaughton/omnipay-barclays-epdq)
[![Latest Stable Version](https://poser.pugx.org/samvaughton/omnipay-barclays-epdq/version.png)](https://packagist.org/packages/samvaughton/omnipay-barclays-epdq)
[![Total Downloads](https://poser.pugx.org/samvaughton/omnipay-barclays-epdq/d/total.png)](https://packagist.org/packages/samvaughton/omnipay-barclays-epdq)

[Omnipay](https://github.com/omnipay/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements WorldPay support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "samvaughton/omnipay-barclays-epdq": "~2.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* Barclays ePDQ Essential (Redirect)

For general usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository.

## Advanced Usage
### Creating a purchase request

The example below explains how you can create a purchase request then send it.

```php
// create a gateway instance using onminpay factory
$gateway = Omnipay::getFactory()->create('BarclaysEpdq\Essential');
$gateway->setClientId('reseller_pspid');
$gateway->setShaIn('sha_in_passphrase');
$gateway->setCurrency('GBP');

// create a purchase request
$purchase = $gateway->purchase();

$purchase->setTransactionId('ORDER-00001'); // Unique ID
$purchase->setAmount(5000); // 50£

/**
 * @var $request EssentialPurchaseResponse
 */
$response = $purchase->send();
 
// send the HTTP query with POST parameters
// you will be redirected to barclays payment server page
$response->redirect();
```

### Handling complete purchase request

When the user submit the payment form, the gateway will redirect you to the URL you have specified in prameters or in the backoffice. The code below gives an example how to handle the server feedback answer.

```php
/**
 * var $gateway Omnipay\BarclaysEpdq\EssentialGateway
 */
$gateway = Omnipay::getFactory()->create('BarclaysEpdq\Essential');

/**
 * var $request Omnipay\BarclaysEpdq\Message\EssentialCompletePurchaseRequest
 */
$request = $gateway->completePurchase();
// if you get parameters back with GET request you need to use setCallbackMethod
$request->setCallbackMethod('GET');
// validates the SHASIGN then store the array containing
// feedback values for a later use like generating invoices
$data = $request->getData();
```

## Extra Parameters

It is also possible to add more parameters and fine tune the create POST HTTP request

```php
/**
 * @var $request EssentialPurchaseResponse
 */
$response = $purchase->send();

// additional parameters resent as feedback parameter after the payment
// resulting in a redirection with the feedback parameters:
// https://www.yourwebsite.com/payment_accepted.php?[…standard.parameters…]
// &COMPLUS=123456789123456789123456789&SessionID=126548354&ShopperID=73541312
$feedback = new Feedback();
$feedback->setComplus('123456789123456789123456789');
$feedback->setParamplus('SessionID=126548354&ShopperID=73541312');
$response->setFeedback($feedback);

// Payment page layout configuration
$layout = new PageLayout(); 
// logo URL must be absolute and store on a secure server accessible via HTTPS
$layout->setTitle('Secure payment with our partner');
$layout->setLogo('https://www.mycompany/images/payment/logo.png');
$layout->setTextColor('#006400');
$response->setPageLayout($layout);

// Delivery & Invoicing Data
$delivery = new Delivery(); 
$delivery->setInvoicingFirstName('John');
$delivery->setInvoicingLastName('Doe');
$response->setDelivery($delivery);

// send the HTTP query with POST parameters
// you will be redirected to barclays payment server page
$response->redirect();
```

## Supported languages

The supported languages by barclays gateway are:

- ar_AR (Arabic)
- cs_CZ (Czech)
- dk_DK (Danish)
- de_DE (German)
- el_GR (Greek)
- en_US (English)
- es_ES (Spanish)
- fi_FI (Finnish)
- fr_FR (French)
- he_IL (Hebrew)
- hu_(HU hungarian)
- it_IT (Italian)
- ja_JP (Japanese)
- ko_KR (Korean)
- nl_BE (Flemish)
- nl_NL (Dutch)
- no_NO (Norwegian)
- pl_PL (Polish)
- pt_PT (Portugese)
- ru_RU (Russian)
- se_SE (Swedish)
- sk_SK (Slovak)
- tr_TR (Turkish)
- zh_CN (Simplified Chinese)

## Supported currencies

The supported currencies by barclays gateway are:

- AED, ANG, ARS, AUD, AWG, BGN, BRL, BYR, CAD, CHF, CNY, CZK, DKK, EEK, EGP, EUR, GBP, GEL, HKD, HRK, HUF, ILS, ISK, JPY, KRW, LTL, LVL, MAD, MXN, NOK, NZD, PLN, RON, RUB, SEK, SGD, SKK, THB, TRY, UAH, USD, XAF, XOF, XPF and ZAR 

## Tips for using this driver
Barclays ePDQ (Essential) is not the most intuitive gateway to use, so with that in mind, here are a couple of pointers for a slightly less painful integration experience:
* The driver defaults to using POST for the post-transaction server-to-server callback. Make sure you also set the callback method to POST in the Barclays back office. Alternatively, you can use GET by configuring the driver using the `setCallbackMethod()` method.
* Barclays only allow redirects to their payment page from URLs that you've already whitelisted. Make sure you've put the full URL of whichever page on your site does the redirect in the Barclays back office configuration.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.
