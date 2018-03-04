<?php
namespace Omnipay\Spreedly\Message;
/**
 * Authorize Request
 *
 * @method Response send()
 */
class ListCardsRequest extends AbstractRequest
{
    public function getData()
    {
        return $this->fillPaginationParameters([]);
    }

    public function getEndpoint()
    {
        return $this->endpoint . 'payment_methods';
    }

    public function getHttpMethod()
    {
        return 'GET';
    }
}
