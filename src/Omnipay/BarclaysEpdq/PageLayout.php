<?php

namespace Omnipay\BarclaysEpdq;

use Symfony\Component\HttpFoundation\ParameterBag;
use Omnipay\Common\Helper;

/**
 * BarclaysEpdq Page Layout Options
 */
class PageLayout
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

    public function getTitle()
    {
        return $this->getParameter('title');
    }

    public function setTitle($value)
    {
        $this->setParameter('title', $value);
    }

    public function getBackgroundColor()
    {
        return $this->getParameter('bgColor');
    }

    public function setBackgroundColor($value)
    {
        $this->setParameter('bgColor', $value);
    }

    public function getTextColor()
    {
        return $this->getParameter('txtColor');
    }

    public function setTextColor($value)
    {
        $this->setParameter('txtColor', $value);
    }

    public function getTableBackgroundColor()
    {
        return $this->getParameter('tblBgColor');
    }

    public function setTableBackgroundColor($value)
    {
        $this->setParameter('tblBgColor', $value);
    }

    public function getTableTextColor()
    {
        return $this->getParameter('tblTxtColor');
    }

    public function setTableTextColor($value)
    {
        $this->setParameter('tblTxtColor', $value);
    }

    public function getHdTableBackgroundColor()
    {
        return $this->getParameter('hdTblBgColor');
    }

    public function setHdTableBackgroundColor($value)
    {
        $this->setParameter('hdTblBgColor', $value);
    }

    public function getHdTableTextColor()
    {
        return $this->getParameter('hdTblTxtColor');
    }

    public function setHdTableTextColor($value)
    {
        $this->setParameter('hdTblTxtColor', $value);
    }

    public function getHdFontType()
    {
        return $this->getParameter('hdFontType');
    }

    public function setHdFontType($value)
    {
        $this->setParameter('hdFontType', $value);
    }

    public function getButtonBackgroundColor()
    {
        return $this->getParameter('buttonBgColor');
    }

    public function setButtonBackgroundColor($value)
    {
        $this->setParameter('buttonBgColor', $value);
    }

    public function getButtonTextColor()
    {
        return $this->getParameter('buttonTxtColor');
    }

    public function setButtonTextColor($value)
    {
        $this->setParameter('buttonTxtColor', $value);
    }

    public function getFontType()
    {
        return $this->getParameter('fontType');
    }

    public function setFontType($value)
    {
        $this->setParameter('fontType', $value);
    }

    public function getLogo()
    {
        return $this->getParameter('logo');
    }

    public function setLogo($value)
    {
        $this->setParameter('logo', $value);
    }
}
