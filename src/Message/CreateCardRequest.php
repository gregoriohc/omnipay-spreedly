<?php
namespace Omnipay\Spreedly\Message;
/**
 * Authorize Request
 *
 * @method Response send()
 */
class CreateCardRequest extends AbstractRequest
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
