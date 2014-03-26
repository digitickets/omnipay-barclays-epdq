<?php

namespace Omnipay\BarclaysEpdq;

use Omnipay\Common\AbstractGateway;

/**
 * BarclaysEpdq Essential Gateway
 *
 * @link http://www.barclaycard.co.uk/business/epdq-cpi/technical-info
 */
class EssentialGateway extends AbstractGateway
{

    public function getName()
    {
        return 'BarclaysEpdq';
    }

    public function getDefaultParameters()
    {
        return array(
            'clientId' => '',
            'testMode' => false,
            'language' => 'en_US',
            'callbackMethod' => 'POST'
        );
    }

    /**
     * @param array $parameters
     * @return \Omnipay\BarclaysEpdq\Message\EssentialPurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\BarclaysEpdq\Message\EssentialPurchaseRequest',
            array_merge($this->parameters->all(), $parameters)
        );
    }

    /**
     * @param array $parameters
     * @return \Omnipay\BarclaysEpdq\Message\EssentialCompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest(
            '\Omnipay\BarclaysEpdq\Message\EssentialCompletePurchaseRequest',
            array_merge($this->parameters->all(), $parameters)
        );
    }

    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    public function getCallbackMethod()
    {
        return $this->getParameter('callbackMethod');
    }

    public function setCallbackMethod($value)
    {
        return $this->setParameter('callbackMethod', $value);
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
}
