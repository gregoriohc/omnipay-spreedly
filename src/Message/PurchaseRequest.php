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

        $data['amount'] = $this->getAmountInteger();
        $data['currency_code'] = $this->getCurrency();

        return [
            'transaction' => $data,
        ];
    }

    public function getEndpoint()
    {
        return $this->getGatewayEndpoint() . 'purchase';
    }
}
