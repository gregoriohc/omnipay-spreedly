<?php

namespace Omnipay\Spreedly\Message;

/**
 * Authorize Request
 *
 * @method Response send()
 */
class PurchaseRequest extends AbstractRequest
{
    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidCreditCardException
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     * @throws \Omnipay\Spreedly\Exception\InvalidPaymentMethodException
     */
    public function getData()
    {
        $this->validate('amount', 'currency');

        $data = $this->validateAndGetPaymentMethodData();

        $data = $this->fillGatewaySpecificFields($data);

        $data = $this->fillExistingParameters($data, [
            'amount' => 'amount_integer',
            'currency_code' => 'currency',
        ]);

        return ['transaction' => $data];
    }

    /**
     * @return string
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getEndpoint()
    {
        return $this->getGatewayEndpoint() . 'purchase';
    }
}
