<?php

namespace Omnipay\Spreedly\Message;

use Omnipay\Spreedly\Message\Concerns\HasGatewaySpecificFields;
use Omnipay\Spreedly\Message\Concerns\HasPaymentMethodData;

/**
 * @method Response send()
 */
class AuthorizeRequest extends AbstractRequest
{
    use HasPaymentMethodData, HasGatewaySpecificFields;

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
        return $this->getGatewayEndpoint() . 'authorize';
    }
}
