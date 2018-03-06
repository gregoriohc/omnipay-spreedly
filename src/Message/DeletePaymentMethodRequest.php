<?php

namespace Omnipay\Spreedly\Message;

/**
 * @method Response send()
 */
class DeletePaymentMethodRequest extends AbstractPaymentMethodRequest
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
        return $this->endpoint . 'payment_methods/' . $this->getPaymentMethodToken() . '/redact';
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return 'PUT';
    }
}
