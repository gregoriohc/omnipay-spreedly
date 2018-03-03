<?php

namespace Omnipay\Spreedly\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

/**
 * Abstract Request
 *
 */
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
        return $this->getParameter('gateway');
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
        if (empty($gateway)) {
            $gateway = $this->getDefaultGateway();
        }

        $tokens = $this->getParameter('gateways_tokens');

        if (isset($tokens[$gateway])) {
            return $tokens[$gateway]->getToken();
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
     * @return array
     * @throws InvalidRequestException
     * @throws \Omnipay\Common\Exception\InvalidCreditCardException
     */
    protected function validateAndGetPaymentMethodData()
    {
        $data = [];

        if ($this->parameters->has('token')) {
            $data['payment_method_token'] = $this->getToken();
        } elseif ($this->parameters->has('card')) {
            $card = $this->getCard();
            $card->validate();

            $data['credit_card'] = [
                'first_name' => $card->getFirstName(),
                'last_name' => $card->getLastName(),
                'number' => $card->getNumber(),
                'verification_value' => $card->getCvv(),
                'month' => $card->getExpiryMonth(),
                'year' => $card->getExpiryYear(),
            ];
        } else {
            // ToDo: Implement Android and Apple Pay
            throw new InvalidRequestException("Missing payment method.");
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
