<?php

namespace Omnipay\Spreedly\Message;

/**
 * @method Response send()
 */
class VoidRequest extends AbstractRequest
{
    /**
     * @return null
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('transactionReference');

        return null;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint . 'transactions/' . $this->getTransactionReference() . '/void';
    }
}
