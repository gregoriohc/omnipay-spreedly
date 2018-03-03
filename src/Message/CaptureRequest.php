<?php
namespace Omnipay\Spreedly\Message;
/**
 * Authorize Request
 *
 * @method Response send()
 */
class CaptureRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('token');

        if ($this->parameters->has('amount')) {
            $this->validate('amount', 'currency');

            return [
                'transaction' => [
                    'amount' => $this->getAmountInteger(),
                    'currency_code' => $this->getCurrency(),
                ],
            ];
        }

        return null;
    }

    public function getEndpoint()
    {
        return $this->endpoint . 'transactions/' . $this->getToken() . '/capture';
    }
}
