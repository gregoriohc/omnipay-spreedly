<?php
namespace Omnipay\Spreedly\Message;
/**
 * Authorize Request
 *
 * @method Response send()
 */
class ListGatewaysRequest extends AbstractRequest
{
    public function getData()
    {
        return $this->fillPaginationParameters([]);
    }

    public function getEndpoint()
    {
        return $this->endpoint . 'gateways';
    }

    public function getHttpMethod()
    {
        return 'GET';
    }
}
