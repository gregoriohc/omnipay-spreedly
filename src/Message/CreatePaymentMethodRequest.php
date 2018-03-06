<?php

namespace Omnipay\Spreedly\Message;

use Omnipay\Spreedly\Concerns\HasOwnerData;
use Omnipay\Spreedly\Message\Concerns\HasPaymentMethodData;

/**
 * @method Response send()
 */
class CreatePaymentMethodRequest extends AbstractPaymentMethodRequest
{
    use HasOwnerData, HasPaymentMethodData;

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidCreditCardException
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     * @throws \Omnipay\Spreedly\Exception\InvalidPaymentMethodException
     */
    public function getData()
    {
        $data = $this->validateAndGetPaymentMethodData();

        $data = $this->fillExistingParameters($data, array(
            'email' => 'email',
            'retained' => 'retained',
            'allow_blank_name' => 'allow_blank_name',
            'allow_expired_date' => 'allow_expired_date',
            'allow_blank_date' => 'allow_blank_date',
            'data' => 'extra',
        ));

        return array('payment_method' => $data);
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint . 'payment_methods';
    }

    public function getRetained()
    {
        return $this->getParameter('retained');
    }

    public function setRetained($value)
    {
        return $this->setParameter('retained', $value);
    }
}
