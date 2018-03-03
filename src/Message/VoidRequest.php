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
        $this->validate('token');

        return null;
    }

    public function getEndpoint()
    {
        return $this->endpoint . 'transactions/' . $this->getToken() . '/void';
    }
}
