<?php

namespace Omnipay\Spreedly\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Helper;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Omnipay\Spreedly\Arr;
use Omnipay\Spreedly\BankAccount;

abstract class AbstractRequest extends BaseAbstractRequest
{
    protected $endpoint = 'https://core.spreedly.com/v1/';

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
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    public function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        $headers = array(
            'Content-Type' => 'application/json'
        );

        return $headers;
    }

    public function sendData($data)
    {
        $httpRequest = $this->httpClient->createRequest(
            $this->getHttpMethod(),
            $this->getEndpoint() . '.json',
            $this->getHeaders(),
            $data
        );

        $httpRequest->setAuth($this->getApiKey(), $this->getApiSecret());

        $httpResponse = $httpRequest->send();

        return $this->createResponse($httpResponse->json());
    }

    /**
     * Map data with existing parameters
     *
     * @param array $data
     * @param array $map
     * @return array
     */
    protected function fillExistingParameters($data, $map)
    {
        foreach ($map as $key => $parameter) {
            $value = null;
            $method = 'get'.ucfirst(Helper::camelCase($parameter));
            if (method_exists($this, $method)) {
                $value = $this->$method();
            } elseif ($this->parameters->has($parameter)) {
                $value = $this->parameters->get($parameter);
            }
            if (!is_null($value)) {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    abstract public function getEndpoint();

    /**
     * @return string
     * @throws InvalidRequestException
     */
    protected function getGatewayEndpoint()
    {
        return $this->endpoint . 'gateways/' . $this->getGatewayToken() . '/';
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
}
