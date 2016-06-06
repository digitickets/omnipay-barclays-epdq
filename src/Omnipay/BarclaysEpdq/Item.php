<?php

namespace Omnipay\BarclaysEpdq;

/**
 * BarclaysEpdq Item
 */
class Item extends \Omnipay\Common\Item
{

    /**
     * Identifier of the item
     */
    public function getId()
    {
        return $this->getParameter('id');
    }

    /**
     * Set the item identifier
     *
     * @param string $value the maximum accepted length is 15
     * @return $this
     */
    public function setId($value)
    {
        return $this->setParameter('id', substr($value, 0, 15));
    }

    /**
     * Category of the item
     */
    public function getCategory()
    {
        return $this->getParameter('category');
    }

    /**
     * Set the item category
     *
     * @param string $value the maximum accepted length is 50
     * @return $this
     */
    public function setCategory($value)
    {
        return $this->setParameter('category', substr($value, 0, 50));
    }

    /**
     * Category of the item for use with FDMX aka Fraud Detection Module
     */
    public function getFraudModuleCategory()
    {
        return $this->getParameter('fraudModuleCategory');
    }

    /**
     * Set the item category for use with FDMX aka Fraud Detection Module
     *
     * @param string $value
     * @return $this
     */
    public function setFraudModuleCategory($value)
    {
        return $this->setParameter('fraudModuleCategory', $value);
    }

    /**
     * Comments of the item
     */
    public function getComments()
    {
        return $this->getParameter('comments');
    }

    /**
     * Set the item comments
     *
     * @param string $value the maximum accepted length is 255
     * @return $this
     */
    public function setComments($value)
    {
        return $this->setParameter('comments', substr($value, 0, 255));
    }

    /**
     * Attributes of the item
     */
    public function getAttributes()
    {
        return $this->getParameter('attributes');
    }

    /**
     * Set the item attributes
     *
     * @param string $value the maximum accepted length is 50
     * @return $this
     */
    public function setAttributes($value)
    {
        return $this->setParameter('attributes', $value);
    }

    /**
     * Discount of the item
     */
    public function getDiscount()
    {
        return $this->getParameter('discount');
    }

    /**
     * Set the item discount
     *
     * @param float $value
     * @return $this
     */
    public function setDiscount($value)
    {
        return $this->setParameter('discount', $value);
    }

    /**
     * Unit of measure of the item
     */
    public function getUnitOfMeasure()
    {
        return $this->getParameter('unitOfMeasure');
    }

    /**
     * Set the item unit of measure
     *
     * @param string $value
     * @return $this
     */
    public function setUnitOfMeasure($value)
    {
        return $this->setParameter('unitOfMeasure', $value);
    }

    /**
     * Weight of the item
     */
    public function getWeight()
    {
        return $this->getParameter('weight');
    }

    /**
     * Set the item weight
     *
     * @param float $value
     * @return $this
     */
    public function setWeight($value)
    {
        return $this->setParameter('weight', $value);
    }

    /**
     * VAT of the item
     */
    public function getVat()
    {
        return $this->getParameter('vat');
    }

    /**
     * Set the item VAT
     *
     * @param float $value
     * @return $this
     */
    public function setVat($value)
    {
        return $this->setParameter('vat', $value);
    }

    /**
     * VAT code of the item
     */
    public function getVatCode()
    {
        return $this->getParameter('vatCode');
    }

    /**
     * Set the item VAT code
     *
     * @param float $value
     * @return $this
     */
    public function setVatCode($value)
    {
        return $this->setParameter('vatCode', $value);
    }

    /**
     * Maximum quantity of the item
     */
    public function getMaximumQuantity()
    {
        return $this->getParameter('maximumQuantity');
    }

    /**
     * Set the item Maximum quantity
     *
     * @param float $value
     * @return $this
     */
    public function setMaximumQuantity($value)
    {
        return $this->setParameter('maximumQuantity', $value);
    }

    /**
     * @param string $value Max length of 16.
     * @return $this
     */
    public function setDescription($value)
    {
        return parent::setDescription(substr($value, 0, 16));
    }

    /**
     * @param string $value Max length of 40.
     * @return $this
     */
    public function setName($value)
    {
        return parent::setName(substr($value, 0, 40));
    }
}
