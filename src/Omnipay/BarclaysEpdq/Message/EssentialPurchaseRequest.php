<?php

namespace Omnipay\BarclaysEpdq\Message;

use Omnipay\Common\Currency;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;

/**
 * BarclaysEpdq Essential Purchase Request
 */
class EssentialPurchaseRequest extends AbstractRequest
{

    protected $liveEndpoint = 'https://payments.epdq.co.uk/ncol/prod/orderstandard.asp';
    protected $testEndpoint = 'https://mdepayments.epdq.co.uk/ncol/test/orderstandard.asp';

    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    public function getDeclineUrl()
    {
        return $this->getParameter('declineUrl');
    }

    public function setDeclineUrl($value)
    {
        return $this->setParameter('declineUrl', $value);
    }

    public function getExceptionUrl()
    {
        return $this->getParameter('exceptionUrl');
    }

    public function setExceptionUrl($value)
    {
        return $this->setParameter('exceptionUrl', $value);
    }

    public function getOrderId()
    {
        return $this->getParameter('orderId');
    }

    public function setOrderId($value)
    {
        return $this->setParameter('orderId', $value);
    }

    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }

    public function setReturnUrl($value)
    {
        return $this->setParameter('returnUrl', $value);
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

    public function getData()
    {
        $this->validate('amount', 'clientId', 'orderId', 'currency', 'language');

        $data = array();

        $data['PSPID']          = $this->getClientId();

        $data['ORDERID']        = $this->getOrderId();
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
            $data['OWNERCITY']       = $card->getCity();
            $data['OWNERTELNO']      = $card->getPhone();
            $data['OWNERADDRESS']    = $card->getAddress1();
        }

        $items = $this->getItems();
        if ($items) {
            foreach ($items as $n => $item) {
                $data["ITEMNAME$n"] = $item->getName();
                $data["ITEMDESC$n"] = $item->getDescription();
                $data["ITEMQUANT$n"] = $item->getQuantity();
                $data["ITEMPRICE$n"] = $this->formatCurrency($item->getPrice());
            }
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
        foreach($data as $key => $value) {
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
        foreach($data as $key => $value) {
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
}
