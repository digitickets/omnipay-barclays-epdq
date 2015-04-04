<?php

namespace Omnipay\BarclaysEpdq;

use Symfony\Component\HttpFoundation\ParameterBag;
use Omnipay\Common\Helper;

/**
 * BarclaysEpdq Additional Feedback Options
 */
class Feedback
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

    public function getComPlus()
    {
        return $this->getParameter('comPlus');
    }

    /**
     * Field for submitting a value you would like to be returned in the feedback request.
     *
     * @param $value
     */
    public function setComPlus($value)
    {
        $this->setParameter('comPlus', $value);
    }

    public function getParamPlus()
    {
        return $this->getParameter('paramPlus');
    }

    /**
     * Field for submitting some parameters and their values you would like to  be returned in the feedback request.
     * The field PARAMPLUS is not included in the feedback parameters as such; instead, the parameters/values you submit
     * in this field will be parsed and the resulting parameters added to the http request.
     *
     * @param $value
     */
    public function setParamPlus($value)
    {
        $this->setParameter('paramPlus', $value);
    }
}
