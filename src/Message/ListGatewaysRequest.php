<?php
namespace Omnipay\Spreedly\Message;
/**
 * Authorize Request
 *
 * @method Response send()
 */
class ListGatewaysRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData()
    {
        $data = $this->fillPaginationParameters([]);

        return count($data) ? $data : null;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint . 'gateways';
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return 'GET';
    }
}
