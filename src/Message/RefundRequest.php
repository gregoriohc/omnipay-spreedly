<?php

namespace Omnipay\Spreedly\Message;

use Omnipay\Spreedly\Message\Concerns\HasGateway;
use Omnipay\Spreedly\Message\Concerns\HasGatewaySpecificFields;

/**
 * @method Response send()
 */
class RefundRequest extends AbstractRequest
{
    use HasGateway, HasGatewaySpecificFields;

    /**
     * @return array|null
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $data = [];

        $this->validate('transactionReference');

        $data = $this->fillGatewaySpecificFields($data);

        if ($this->parameters->has('amount')) {
            $this->validate('amount', 'currency');

            $data = $this->fillExistingParameters($data, [
                'amount' => 'amount_integer',
                'currency_code' => 'currency',
            ]);

            return ['transaction' => $data];
        }

        return null;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint . 'transactions/' . $this->getTransactionReference() . '/credit';
    }
}
