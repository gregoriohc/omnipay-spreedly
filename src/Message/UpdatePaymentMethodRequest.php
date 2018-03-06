<?php

namespace Omnipay\Spreedly\Message;
use Omnipay\Spreedly\Concerns\HasOwnerData;

/**
 * @method Response send()
 */
class UpdatePaymentMethodRequest extends AbstractPaymentMethodRequest
{
    use HasOwnerData;

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('payment_method_token');

        $data = $this->fillExistingParameters(array(), array(
            'allow_blank_name' => 'allow_blank_name',
            'allow_expired_date' => 'allow_expired_date',
            'allow_blank_date' => 'allow_blank_date',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'email' => 'email',
            'data' => 'extra',
        ));

        return array('payment_method' => $data);
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint . 'payment_methods/' . $this->getPaymentMethodToken();
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return 'PUT';
    }
}
