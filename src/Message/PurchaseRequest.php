<?php

namespace Omnipay\Spreedly\Message;

/**
 * Authorize Request
 *
 * @method Response send()
 */
class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'currency');

        $data = $this->validateAndGetPaymentMethodData();

        $data = $this->fillExistingParameters($data, [
            'amount' => 'amount_integer',
            'currency_code' => 'currency',
        ]);

        return ['transaction' => $data];
    }

    public function getEndpoint()
    {
        return $this->getGatewayEndpoint() . 'purchase';
    }
}
