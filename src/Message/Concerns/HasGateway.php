<?php

namespace Omnipay\Spreedly\Message\Concerns;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Spreedly\Arr;

trait HasGateway
{
    /**
     * @return string
     */
    public function getGateway()
    {
        $gateway = $this->getParameter('gateway');

        if (is_null($this->getParameter('gateway'))) {
            $gateway = $this->getDefaultGateway();
        }

        if ($this->getTestMode()) {
            $gateway = 'test';
        }

        return $gateway;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setGateway($value)
    {
        return $this->setParameter('gateway', $value);
    }

    /**
     * @return string
     * @throws InvalidRequestException
     */
    public function getGatewayToken()
    {
        $gateway = $this->getGateway();

        $gatewaysTokens = $this->getGatewaysTokens();

        foreach ($gatewaysTokens as $gatewayToken) {
            if (Arr::get($gatewayToken, 'type') == $gateway) {
                return Arr::get($gatewayToken, 'token');
            }
        }

        throw new InvalidRequestException("Missing '$gateway' gateway token.");
    }

    /**
     * @return string
     * @throws InvalidRequestException
     */
    protected function getGatewayEndpoint()
    {
        return $this->endpoint . 'gateways/' . $this->getGatewayToken() . '/';
    }
}
