<?php

namespace Omnipay\Spreedly\Message\Concerns;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Spreedly\BankAccount;

trait HasPagination
{
    public function getOrder()
    {
        return $this->getParameter('order');
    }

    public function setOrder($value)
    {
        return $this->setParameter('order', $value);
    }

    public function getSinceToken()
    {
        return $this->getParameter('since_token');
    }

    public function setSinceToken($value)
    {
        return $this->setParameter('since_token', $value);
    }

    /**
     * Map data with existing parameters
     *
     * @param array $data
     * @return array
     */
    protected function fillPaginationParameters($data)
    {
        if ($order = $this->getOrder()) {
            $data['order'] = $order;
        }

        if ($sinceToken = $this->getSinceToken()) {
            $data['since_token'] = $sinceToken;
        }

        return $data;
    }
}
