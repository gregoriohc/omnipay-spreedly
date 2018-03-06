<?php

namespace Omnipay\Spreedly\Message;

/**
 * @method Response send()
 */
abstract class AbstractPaymentMethodRequest extends AbstractRequest
{
    public function getPaymentMethodToken()
    {
        return $this->getParameter('payment_method_token');
    }

    public function setPaymentMethodToken($value)
    {
        return $this->setParameter('payment_method_token', $value);
    }

    public function getAllowBlankName()
    {
        return $this->getParameter('allow_blank_name');
    }

    public function setAllowBlankName($value)
    {
        return $this->setParameter('allow_blank_name', $value);
    }

    public function getAllowExpiredDate()
    {
        return $this->getParameter('allow_expired_date');
    }

    public function setAllowExpiredDate($value)
    {
        return $this->setParameter('allow_expired_date', $value);
    }

    public function getAllowBlankDate()
    {
        return $this->getParameter('allow_blank_date');
    }

    public function setAllowBlankDate($value)
    {
        return $this->setParameter('allow_blank_date', $value);
    }

    public function getExtra()
    {
        return $this->getParameter('extra');
    }

    public function setExtra($value)
    {
        return $this->setParameter('extra', $value);
    }
}
