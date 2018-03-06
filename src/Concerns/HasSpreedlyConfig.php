<?php

namespace Omnipay\Spreedly\Concerns;

trait HasSpreedlyConfig
{
    public function getDefaultParameters()
    {
        return array(
            'api_key' => '', // (required) Environment key
            'api_secret' => '', // (required) Signing Secret
            'default_gateway' => '', // (required) Default gateway
            'gateways_tokens' => [], // (required) Default gateway
            'timeout' => 64, // (optional) Default 64 seconds (recommended by Spreedly)
            'connect_timeout' => 10, // (optional) Default 10 seconds
            'testMode' => false,
        );
    }

    public function getApiKey()
    {
        return $this->getParameter('api_key');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('api_key', $value);
    }

    public function getApiSecret()
    {
        return $this->getParameter('api_secret');
    }

    public function setApiSecret($value)
    {
        return $this->setParameter('api_secret', $value);
    }

    public function getDefaultGateway()
    {
        return $this->getParameter('default_gateway');
    }

    public function setDefaultGateway($value)
    {
        return $this->setParameter('default_gateway', $value);
    }

    public function getGatewaysTokens()
    {
        return $this->getParameter('gateways_tokens');
    }

    public function setGatewaysTokens($value)
    {
        return $this->setParameter('gateways_tokens', $value);
    }

    public function getTimeout()
    {
        return $this->getParameter('timeout');
    }

    public function setTimeout($value)
    {
        return $this->setParameter('timeout', $value);
    }

    public function getConnectTimeout()
    {
        return $this->getParameter('connect_timeout');
    }

    public function setConnectTimeout($value)
    {
        return $this->setParameter('connect_timeout', $value);
    }
}
