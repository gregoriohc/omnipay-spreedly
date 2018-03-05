<?php
namespace Omnipay\Spreedly\Message;
/**
 * Authorize Request
 *
 * @method Response send()
 */
class VoidRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('transactionReference');

        return null;
    }

    public function getEndpoint()
    {
        return $this->endpoint . 'transactions/' . $this->getTransactionReference() . '/void';
    }
}
