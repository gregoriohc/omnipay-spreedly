<?php

namespace Omnipay\Spreedly\Message;

/**
 * @method Response send()
 */
class FetchPaymentMethodRequest extends AbstractRequest
{
    /**
     * @return null
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('payment_method_token');

        return null;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint . 'payment_methods/' . $this->getPaymentMethodToken();
    }

    public function getHttpMethod()
    {
        return 'GET';
    }

    public function getPaymentMethodToken()
    {
        return $this->getParameter('payment_method_token');
    }

    public function setPaymentMethodToken($value)
    {
        return $this->setParameter('payment_method_token', $value);
    }
}
