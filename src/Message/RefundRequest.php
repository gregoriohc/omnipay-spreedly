<?php
namespace Omnipay\Spreedly\Message;
/**
 * Authorize Request
 *
 * @method Response send()
 */
class RefundRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('token');

        if ($this->parameters->has('amount')) {
            $this->validate('amount', 'currency');

            $data = $this->fillExistingParameters([], [
                'amount' => 'amount_integer',
                'currency_code' => 'currency',
            ]);

            return ['transaction' => $data];
        }

        return null;
    }

    public function getEndpoint()
    {
        return $this->endpoint . 'transactions/' . $this->getToken() . '/credit';
    }
}
