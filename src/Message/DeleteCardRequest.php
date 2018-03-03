<?php
namespace Omnipay\Spreedly\Message;
/**
 * Authorize Request
 *
 * @method Response send()
 */
class DeleteCardRequest extends AbstractRequest
{
    public function getData()
    {
        return [];
    }

    public function getEndpoint()
    {
        return $this->getGatewayEndpoint() . '';
    }
}
