<?php

namespace Omnipay\Spreedly\Message;
use Omnipay\Spreedly\Message\Concerns\HasGateway;
use Omnipay\Spreedly\Message\Concerns\HasPaymentMethodData;

/**
 * @method Response send()
 */
class StorePaymentMethodRequest extends AbstractPaymentMethodRequest
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

        return ['transaction' => $data];
    }

    /**
     * @return string
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getEndpoint()
    {
        return $this->getGatewayEndpoint() . 'store';
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return 'POST';
    }
}
