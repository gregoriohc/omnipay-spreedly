<?php

namespace Omnipay\Spreedly\Message;

use Omnipay\Spreedly\Message\Concerns\HasPagination;

/**
 * @method Response send()
 */
class ListPaymentMethodsRequest extends AbstractRequest
{
    use HasPagination;

    /**
     * @return array
     */
    public function getData()
    {
        $data = $this->fillPaginationParameters (array());

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
