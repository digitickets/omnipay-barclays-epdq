<?php

namespace Omnipay\BarclaysEpdq;

use Symfony\Component\HttpFoundation\ParameterBag;
use Omnipay\Common\Helper;

/**
 * BarclaysEpdq Delivery & Invoicing Data
 */
class Delivery
{
    /**
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected $parameters;

    /**
     * Create a new BankAccount object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = null)
    {
        $this->initialize($parameters);
    }

    /**
     * Initialize the object with parameters.
     *
     * If any unknown parameters passed, they will be ignored.
     *
     * @param array $parameters An associative array of parameters
     *
     * @return $this
     */
    public function initialize($parameters = null)
    {
        $this->parameters = new ParameterBag();
        Helper::initialize($this, $parameters);
        return $this;
    }

    public function getParameters()
    {
        return $this->parameters->all();
    }

    protected function getParameter($key)
    {
        return $this->parameters->get($key);
    }

    protected function setParameter($key, $value)
    {
        $this->parameters->set($key, $value);
        return $this;
    }

    /**
     * Delivery method
     *
     * @return string
     */
    public function getDeliveryMethod()
    {
        return $this->getParameter('deliveryMethod');
    }

    /**
     * @param string $value the maximum accepted length is 25
     */
    public function setDeliveryMethod($value)
    {
        $this->setParameter('deliveryMethod', $value);
    }

    /**
     * Delivery cost
     *
     * @return int
     */
    public function getDeliveryCost()
    {
        return $this->getParameter('deliveryCost');
    }

    /**
     * @param int $value
     */
    public function setDeliveryCost($value)
    {
        $this->setParameter('deliveryCost', $value);
    }

    /**
     * Delivery tax code
     *
     * @return int
     */
    public function getDeliveryTaxCode()
    {
        return $this->getParameter('deliveryTaxCode');
    }

    /**
     * @param int $value
     */
    public function setDeliveryTaxCode($value)
    {
        $this->setParameter('deliveryTaxCode', $value);
    }

    /**
     * The Client Unique Identifier (CUI) is an identifier allocated by the merchant to his customer.
     * It can be a name, client number, e-mail address etc.
     *
     * @return string
     */
    public function getCuid()
    {
        return $this->getParameter('cuid');
    }

    /**
     * @param string $value the maximum accepted length is 50
     */
    public function setCuid($value)
    {
        $this->setParameter('cuid', $value);
    }

    /**
     * Civility of the customer (Mr., Mrs, Miss, Herr, etc.)
     *
     * @return string
     */
    public function getCivility()
    {
        return $this->getParameter('civility');
    }

    /**
     * @param string $value the maximum accepted length is 10
     */
    public function setCivility($value)
    {
        $this->setParameter('civility', $value);
    }

    /**
     * Gender
     * @return string
     */
    public function getGender()
    {
        return $this->getParameter('gender');
    }

    /**
     * @param string $value accepted values are M or F
     */
    public function setGender($value)
    {
        $this->setParameter('gender', $value);
    }

    /**
     * First name of the billed customer
     * @return string
     */
    public function getInvoicingFirstName()
    {
        return $this->getParameter('invoicingFirstName');
    }

    /**
     * @param string $value the maximum accepted length is 50
     */
    public function setInvoicingFirstName($value)
    {
        $this->setParameter('invoicingFirstName', $value);
    }

    /**
     * Last name of the billed customer
     * @return string
     */
    public function getInvoicingLastName()
    {
        return $this->getParameter('invoicingLastName');
    }

    /**
     * @param string $value the maximum accepted length is 35
     */
    public function setInvoicingLastName($value)
    {
        $this->setParameter('invoicingLastName', $value);
    }

    /**
     * Billing address, first line
     * @return string
     */
    public function getInvoicingAddress1()
    {
        return $this->getParameter('invoicingAddress1');
    }

    /**
     * @param string $value the maximum accepted length is 35
     */
    public function setInvoicingAddress1($value)
    {
        $this->setParameter('invoicingAddress1', $value);
    }

    /**
     * Billing address, second line
     * @return string
     */
    public function getInvoicingAddress2()
    {
        return $this->getParameter('invoicingAddress2');
    }

    /**
     * @param string $value the maximum accepted length is 35
     */
    public function setInvoicingAddress2($value)
    {
        $this->setParameter('invoicingAddress2', $value);
    }

    /**
     * House number of invoicing address
     * @return string
     */
    public function getInvoicingStreetNumber()
    {
        return $this->getParameter('invoicingStreetNumber');
    }

    /**
     * @param string $value the maximum accepted length is 10
     */
    public function setInvoicingStreetNumber($value)
    {
        $this->setParameter('invoicingStreetNumber', $value);
    }

    /**
     * Invoicing Postal Code
     * @return string
     */
    public function getInvoicingPostalCode()
    {
        return $this->getParameter('invoicingPostalCode');
    }

    /**
     * @param string $value the maximum accepted length is 10
     */
    public function setInvoicingPostalCode($value)
    {
        $this->setParameter('invoicingPostalCode', $value);
    }

    /**
     * Invoicing City
     * @return string
     */
    public function getInvoicingCity()
    {
        return $this->getParameter('invoicingCity');
    }

    /**
     * @param string $value the maximum accepted length is 25
     */
    public function setInvoicingCity($value)
    {
        $this->setParameter('invoicingCity', $value);
    }

    /**
     * Invoicing Country code
     * @return string
     */
    public function getInvoicingCountryCode()
    {
        return $this->getParameter('invoicingCountryCode');
    }

    /**
     * @param string $value the maximum accepted length is 2
     */
    public function setInvoicingCountryCode($value)
    {
        $this->setParameter('invoicingCountryCode', $value);
    }

    /**
     * Prefix of the Shipped customer
     *
     * @return string
     */
    public function getDeliveryNamePrefix()
    {
        return $this->getParameter('deliveryPrefix');
    }

    /**
     * @param string $value the maximum accepted length is 10
     */
    public function setDeliveryNamePrefix($value)
    {
        $this->setParameter('deliveryPrefix', $value);
    }

    /**
     * First name of shipped customer
     * @return string
     */
    public function getDeliveryFirstName()
    {
        return $this->getParameter('deliveryFirstName');
    }

    /**
     * @param string $value the maximum accepted length is 50
     */
    public function setDeliveryFirstName($value)
    {
        $this->setParameter('deliveryFirstName', $value);
    }

    /**
     * Last name of shipped customer
     * @return string
     */
    public function getDeliveryLastName()
    {
        return $this->getParameter('deliveryLastName');
    }

    /**
     * @param string $value the maximum accepted length is 50
     */
    public function setDeliveryLastName($value)
    {
        $this->setParameter('deliveryLastName', $value);
    }

    /**
     * Shipping state (ISO code *)
     * @return string
     */
    public function getDeliveryState()
    {
        return $this->getParameter('deliveryState');
    }

    /**
     * @param string $value the maximum accepted length is 2
     */
    public function setDeliveryState($value)
    {
        $this->setParameter('deliveryState', $value);
    }

    /**
     * Shipping address, first line
     * @return string
     */
    public function getDeliveryAddress1()
    {
        return $this->getParameter('deliveryAddress1');
    }

    /**
     * @param string $value the maximum accepted length is 35
     */
    public function setDeliveryAddress1($value)
    {
        $this->setParameter('deliveryAddress1', $value);
    }

    /**
     * Shipping address, second line
     * @return string
     */
    public function getDeliveryAddress2()
    {
        return $this->getParameter('deliveryAddress2');
    }

    /**
     * @param string $value the maximum accepted length is 35
     */
    public function setDeliveryAddress2($value)
    {
        $this->setParameter('deliveryAddress2', $value);
    }

    /**
     * House number of shipping address
     * @return string
     */
    public function getDeliveryStreetNumber()
    {
        return $this->getParameter('deliveryStreetNumber');
    }

    /**
     * @param string $value the maximum accepted length is 10
     */
    public function setDeliveryStreetNumber($value)
    {
        $this->setParameter('deliveryStreetNumber', $value);
    }

    /**
     * Shipment Postal Code
     * @return string
     */
    public function getDeliveryPostalCode()
    {
        return $this->getParameter('deliveryPostalCode');
    }

    /**
     * @param string $value the maximum accepted length is 10
     */
    public function setDeliveryPostalCode($value)
    {
        $this->setParameter('deliveryPostalCode', $value);
    }

    /**
     * Shippung City
     * @return string
     */
    public function getDeliveryCity()
    {
        return $this->getParameter('deliveryCity');
    }

    /**
     * @param string $value the maximum accepted length is 25
     */
    public function setDeliveryCity($value)
    {
        $this->setParameter('deliveryCity', $value);
    }

    /**
     * Shipping Country code
     * @return string
     */
    public function getDeliveryCountryCode()
    {
        return $this->getParameter('deliveryCountryCode');
    }

    /**
     * @param string $value the maximum accepted length is 2
     */
    public function setDeliveryCountryCode($value)
    {
        $this->setParameter('deliveryCountryCode', $value);
    }

    /**
     * E-mail address
     * @return string
     */
    public function getDeliveryEmail()
    {
        return $this->getParameter('deliveryEmail');
    }

    /**
     * @param string $value the maximum accepted length is 50
     */
    public function setEmail($value)
    {
        $this->setParameter('deliveryEmail', $value);
    }

    /**
     * Date of Birth
     * @return string
     */
    public function getDeliveryBirthDate()
    {
        return $this->getParameter('deliveryBirthDate');
    }

    /**
     * @param string $value accepted format is yyyy-MM-dd
     */
    public function setDeliveryBirthDate($value)
    {
        $this->setParameter('deliveryBirthDate', $value);
    }

    /**
     * Fax Number of shipping address
     * @return string
     */
    public function getDeliveryFax()
    {
        return $this->getParameter('deliveryFax');
    }

    /**
     * @param string $value the maximum accepted length is 20
     */
    public function setDeliveryFax($value)
    {
        $this->setParameter('deliveryFax', $value);
    }

    /**
     * Phone Number of shipping address
     * @return string
     */
    public function getDeliveryPhone()
    {
        return $this->getParameter('deliveryPhone');
    }

    /**
     * @param string $value the maximum accepted length is 20
     */
    public function setDeliveryPhone($value)
    {
        $this->setParameter('deliveryPhone', $value);
    }
}
