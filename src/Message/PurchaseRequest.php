<?php

namespace Omnipay\Spreedly\Message;

use Omnipay\Spreedly\Concerns\HasOwnerData;
use Omnipay\Spreedly\Message\Concerns\HasGateway;
use Omnipay\Spreedly\Message\Concerns\HasGatewaySpecificFields;
use Omnipay\Spreedly\Message\Concerns\HasPaymentMethodData;

/**
 * @method Response send()
 */
class PurchaseRequest extends AbstractRequest
{
    use HasGateway, HasPaymentMethodData, HasGatewaySpecificFields, HasOwnerData;

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

        $data = $this->fillExistingParameters($data, array(
            'amount' => 'amount_integer',
            'currency_code' => 'currency',
            'email' => 'email',
            'retain_on_success' => 'retain_on_success',
        ));

        return array('transaction' => $data);
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
