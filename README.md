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

// send the HTTP query with POST parameters
// you will be redirected to barclays payment server page
$purchase->send();
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
* It seems you can't set the callback URL using parameters in your initital redirect. It can only be done in the Barclays back office.
* Barclays only allow redirects to their payment page from URLs that you've already whitelisted. Make sure you've put the full URL of whichever page on your site does the redirect in the Barclays back office configuration.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.
