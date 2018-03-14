<?php

namespace Omnipay\Spreedly\Message;
use Omnipay\Spreedly\Message\Concerns\HasGateway;
use Omnipay\Spreedly\Message\Concerns\HasPaymentMethodData;

/**
 * @method Response send()
 */
class VerifyPaymentMethodRequest extends AbstractPaymentMethodRequest
{
    use HasGateway, HasPaymentMethodData;

    /**
     * @return null
     * @throws \Omnipay\Common\Exception\InvalidCreditCardException
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     * @throws \Omnipay\Spreedly\Exception\InvalidPaymentMethodException
     */
    public function getData()
    {
        $data = $this->validateAndGetPaymentMethodData();

        $data = $this->fillExistingParameters($data, array(
            'retain_on_success' => 'retain_on_success',
        ));

        return ['transaction' => $data];
    }

    /**
     * @return string
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getEndpoint()
    {
        return $this->getGatewayEndpoint() . 'verify';
    }
}
