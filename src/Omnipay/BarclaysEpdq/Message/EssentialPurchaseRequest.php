<?php

namespace Omnipay\BarclaysEpdq\Message;

use Omnipay\BarclaysEpdq\Item;
use Omnipay\BarclaysEpdq\PageLayout;
use Omnipay\BarclaysEpdq\Delivery;
use Omnipay\BarclaysEpdq\Feedback;
use Omnipay\Common\ItemBag;
use Omnipay\Common\Message\AbstractRequest;

/**
 * BarclaysEpdq Essential Purchase Request
 */
class EssentialPurchaseRequest extends AbstractRequest
{

    protected $liveEndpoint = 'https://payments.epdq.co.uk/ncol/prod/orderstandard_utf8.asp';
    protected $testEndpoint = 'https://mdepayments.epdq.co.uk/ncol/test/orderstandard_utf8.asp';

    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    /**
     * Your affiliation name in our system, chosen by yourself when opening your account with us.
     * This is a unique identifier and can’t ever be changed.
     *
     * @param string $value Max length of 30.
     * @return AbstractRequest
     */
    public function setClientId($value)
    {
        return $this->setParameter('clientId', substr($value, 0, 30));
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }

    public function getDeclineUrl()
    {
        return $this->getParameter('declineUrl');
    }

    public function setDeclineUrl($value)
    {
        return $this->setParameter('declineUrl', substr($value, 0, 200));
    }

    public function getExceptionUrl()
    {
        return $this->getParameter('exceptionUrl');
    }

    public function setExceptionUrl($value)
    {
        return $this->setParameter('exceptionUrl', substr($value, 0, 200));
    }

    /**
     * This method keeps the backward compatibility with setDeclineUrl and setExceptionUrl.
     * It fills returnUrl, declineUrl and exceptionUrl with the same value.
     *
     * @param string $value Max length of 200
     * @return $this
     */
    public function setReturnUrl($value)
    {
        $value = substr($value, 0, 200);
        $this->setParameter('returnUrl', $value);
        $this->setParameter('declineUrl', $value);
        $this->setParameter('exceptionUrl', $value);

        return $this;
    }

    public function getShaIn()
    {
        return $this->getParameter('shaIn');
    }

    public function setShaIn($value)
    {
        return $this->setParameter('shaIn', $value);
    }

    public function getShaOut()
    {
        return $this->getParameter('shaOut');
    }

    public function setShaOut($value)
    {
        return $this->setParameter('shaOut', $value);
    }

    public function getCallbackMethod()
    {
        return $this->getParameter('callbackMethod');
    }

    public function setCallbackMethod($value)
    {
        return $this->setParameter('callbackMethod', $value);
    }

    /**
     * Get the page layout configuration
     *
     * @return PageLayout
     */
    public function getPageLayout()
    {
        return $this->getParameter('pageLayout');
    }

    public function setPageLayout($value)
    {
        return $this->setParameter('pageLayout', $value);
    }

    /**
     * Get the delivery and invoicing data parameters
     *
     * @return Delivery
     */
    public function getDelivery()
    {
        return $this->getParameter('delivery');
    }

    public function setDelivery($value)
    {
        return $this->setParameter('delivery', $value);
    }

    /**
     * @return Feedback
     */
    public function getFeedback()
    {
        return $this->getParameter('feedback');
    }

    /**
     * @param Feedback $value
     * @return AbstractRequest
     */
    public function setFeedback($value)
    {
        return $this->setParameter('feedback', $value);
    }

    public function getData()
    {
        $this->validate('amount', 'clientId', 'currency', 'language');

        $data = array();

        $data['PSPID']          = $this->getClientId();

        $data['ORDERID']        = $this->getTransactionId();
        // Useful optional parameter which can be used as a variable in the post-payment feedback URL
        // eg. The URL can be set in the ePDQ control panel as something like:
        //     "https://www.example.com/callback/<PARAMVAR>"
        $data['PARAMVAR']       = $this->getTransactionId();
        $data['CURRENCY']       = $this->getCurrency();
        $data['LANGUAGE']       = $this->getLanguage();
        $data['AMOUNT']         = $this->getAmountInteger();

        $data['ACCEPTURL']      = $this->getReturnUrl();
        $data['CANCELURL']      = $this->getCancelUrl();
        $data['DECLINEURL']     = $this->getDeclineUrl();
        $data['EXCEPTIONURL']   = $this->getExceptionUrl();

        $card = $this->getCard();
        if ($card) {
            $data['CN']              = $card->getName();
            $data['COM']             = $card->getCompany();
            $data['EMAIL']           = $card->getEmail();
            $data['OWNERZIP']        = $card->getPostcode();
            $data['OWNERTOWN']       = $card->getCity();
            $data['OWNERCTY']        = $card->getCountry();
            $data['OWNERTELNO']      = $card->getPhone();
            $data['OWNERADDRESS']    = $card->getAddress1();
            $data['OWNERADDRESS2']   = $card->getAddress2();
        }

        $items = $this->getItems();
        if ($items) {
            $index = 0;
            foreach ($items as $n => $item) {
                // Ignore zero priced items as too many causes problems with Barclays EPDQ.
                if ($item->getPrice() <> 0) {
                    /**
                     * @var \Omnipay\BarclaysEpdq\Item $item
                     */
                    // item index always start from 1 not from 0
                    ++$index;
                    $data["ITEMNAME$index"]            = $item->getName();
                    $data["ITEMDESC$index"]            = $item->getDescription();
                    $data["ITEMQUANT$index"]           = $item->getQuantity();
                    $data["ITEMPRICE$index"]           = $this->formatCurrency($item->getPrice());
                    if (is_a($item, 'Omnipay\BarclaysEpdq\Item')) {
                        $data["ITEMID$index"]              = $item->getId();
                        $data["ITEMCOMMENTS$index"]        = $item->getComments();
                        $data["ITEMCATEGORY$index"]        = $item->getCategory();
                        $data["ITEMATTRIBUTES$index"]      = $item->getAttributes();
                        $data["ITEMDISCOUNT$index"]        = $this->formatCurrency($item->getDiscount());
                        $data["ITEMUNITOFMEASURE$index"]   = $item->getUnitOfMeasure();
                        $data["ITEMWEIGHT$index"]          = $item->getWeight();
                        $data["ITEMVAT$index"]             = $this->formatCurrency($item->getVat());
                        $data["ITEMVATCODE$index"]         = $item->getVatCode();
                        $data["ITEMFDMPRODUCTCATEG$index"] = $item->getFraudModuleCategory();
                        $data["ITEMQUANTORIG$index"]       = $item->getMaximumQuantity();
                    }
                }
            }
        }

        $feedback = $this->getFeedback();
        if ($feedback) {
            $data['COMPLUS']   = $feedback->getComPlus();
            $data['PARAMPLUS'] = $feedback->getParamPlus();
        }

        $pageLayout = $this->getPageLayout();
        if ($pageLayout) {
            $data['TITLE'] = $pageLayout->getTitle();
            $data['BGCOLOR'] = $pageLayout->getBackgroundColor();
            $data['TXTCOLOR'] = $pageLayout->getTextColor();
            $data['TBLBGCOLOR'] = $pageLayout->getTableBackgroundColor();
            $data['TBLTXTCOLOR'] = $pageLayout->getTableTextColor();
            $data['HDTBLBGCOLOR'] = $pageLayout->getHdTableBackgroundColor();
            $data['HDTBLTXTCOLOR'] = $pageLayout->getHdTableTextColor();
            $data['HDFONTTYPE'] = $pageLayout->getHdFontType();
            $data['BUTTONBGCOLOR'] = $pageLayout->getButtonBackgroundColor();
            $data['BUTTONTXTCOLOR'] = $pageLayout->getButtonTextColor();
            $data['FONTTYPE'] = $pageLayout->getFontType();
            $data['LOGO'] = $pageLayout->getLogo();
        }

        $delivery = $this->getDelivery();
        if ($delivery) {
            $data['ORDERSHIPMETH'] = $delivery->getDeliveryMethod();
            $data['ORDERSHIPCOST'] = $delivery->getDeliveryCost();
            $data['ORDERSHIPTAXCODE'] = $delivery->getDeliveryTaxCode();
            $data['CUID'] = $delivery->getCuid();
            $data['CIVILITY'] = $delivery->getCivility();
            $data['ECOM_CONSUMER_GENDER'] = $delivery->getGender();
            $data['ECOM_BILLTO_POSTAL_NAME_FIRST'] = $delivery->getInvoicingFirstName();
            $data['ECOM_BILLTO_POSTAL_NAME_LAST'] = $delivery->getInvoicingLastName();
            $data['ECOM_BILLTO_POSTAL_STREET_LINE1'] = $delivery->getInvoicingAddress1();
            $data['ECOM_BILLTO_POSTAL_STREET_LINE2'] = $delivery->getInvoicingAddress2();
            $data['ECOM_BILLTO_POSTAL_STREET_NUMBER'] = $delivery->getInvoicingStreetNumber();
            $data['ECOM_BILLTO_POSTAL_POSTALCODE'] = $delivery->getInvoicingPostalCode();
            $data['ECOM_BILLTO_POSTAL_CITY'] = $delivery->getInvoicingCity();
            $data['ECOM_BILLTO_POSTAL_COUNTRYCODE'] = $delivery->getInvoicingCountryCode();
            $data['ECOM_SHIPTO_POSTAL_NAME_PREFIX'] = $delivery->getDeliveryNamePrefix();
            $data['ECOM_SHIPTO_POSTAL_NAME_FIRST'] = $delivery->getDeliveryFirstName();
            $data['ECOM_SHIPTO_POSTAL_LAST_FIRST'] = $delivery->getDeliveryLastName();
            $data['ECOM_SHIPTO_POSTAL_STREET_LINE1'] = $delivery->getDeliveryAddress1();
            $data['ECOM_SHIPTO_POSTAL_STREET_LINE2'] = $delivery->getDeliveryAddress2();
            $data['ECOM_SHIPTO_POSTAL_STREET_NUMBER'] = $delivery->getDeliveryStreetNumber();
            $data['ECOM_SHIPTO_POSTAL_POSTALCODE'] = $delivery->getDeliveryPostalCode();
            $data['ECOM_SHIPTO_POSTAL_CITY'] = $delivery->getDeliveryCity();
            $data['ECOM_SHIPTO_POSTAL_COUNTRYCODE'] = $delivery->getDeliveryCountryCode();
            $data['ECOM_SHIPTO_ONLINE_EMAIL'] = $delivery->getDeliveryEmail();
            $data['ECOM_SHIPTO_TELECOM_FAX_NUMBER'] = $delivery->getDeliveryFax();
            $data['ECOM_SHIPTO_TELECOM_PHONE_NUMBER'] = $delivery->getDeliveryPhone();
            $data['ECOM_SHIPTO_DOB'] = $delivery->getDeliveryBirthDate();
        }

        $data = $this->cleanParameters($data);

        if ($this->getShaIn()) {
            $data['SHASIGN'] = $this->calculateSha($data, $this->getShaIn());
        }

        return $data;
    }

    protected function cleanParameters($data)
    {
        $clean = array();
        foreach ($data as $key => $value) {
            if (!is_null($value) && $value !== false && $value !== '') {
                $clean[strtoupper($key)] = $value;
            }
        }

        return $clean;
    }

    public function calculateSha($data, $shaKey)
    {
        ksort($data);

        $shaString = '';
        foreach ($data as $key => $value) {
            $shaString .= sprintf('%s=%s%s', strtoupper($key), $value, $shaKey);
        }

        return strtoupper(sha1($shaString));
    }

    public function sendData($data)
    {
        return $this->response = new EssentialPurchaseResponse($this, $data);
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * Set items for request
     *
     * Cast the items to instances of \Omnipay\BarclaysEpdq\Item
     *
     * @param array|\Omnipay\Common\ItemBag|\Omnipay\Common\Item[] $items
     * @return AbstractRequest
     */
    public function setItems($items)
    {
        $newItems = new ItemBag();
        foreach ($items as $item) {
            $newItems->add(new Item($item->getParameters()));
        }

        return parent::setItems($newItems);
    }
}
