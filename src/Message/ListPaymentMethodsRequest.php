<?php
namespace Omnipay\Spreedly\Message;
/**
 * Authorize Request
 *
 * @method Response send()
 */
class ListPaymentMethodsRequest extends AbstractRequest
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
        return $this->endpoint . 'payment_methods';
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return 'GET';
    }
}
