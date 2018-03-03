<?php

namespace Omnipay\Spreedly;

use Omnipay\Common\AbstractGateway;

/**
 * Spreedly Gateway
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Spreedly';
    }

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

    /**
     * @param array $options
     * @return Message\AuthorizeRequest
     */
    public function authorize(array $options = array())
    {
        return $this->createRequest(Message\AuthorizeRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\CaptureRequest
     */
    public function capture(array $options = array())
    {
        return $this->createRequest(Message\CaptureRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\PurchaseRequest
     */
    public function purchase(array $options = array())
    {
        return $this->createRequest(Message\PurchaseRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\RefundRequest
     */
    public function refund(array $options = array())
    {
        return $this->createRequest(Message\RefundRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\VoidRequest
     */
    public function void(array $options = array())
    {
        return $this->createRequest(Message\VoidRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\CreateCardRequest
     */
    public function createCard(array $options = array())
    {
        return $this->createRequest(Message\CreateCardRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\UpdateCardRequest
     */
    public function updateCard(array $options = array())
    {
        return $this->createRequest(Message\UpdateCardRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\DeleteCardRequest
     */
    public function deleteCard(array $options = array())
    {
        return $this->createRequest(Message\DeleteCardRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\ListGatewaysRequest
     */
    public function listGateways(array $options = array())
    {
        return $this->createRequest(Message\ListGatewaysRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\CreateGatewayRequest
     */
    public function createGateway(array $options = array())
    {
        return $this->createRequest(Message\CreateGatewayRequest::class, $options);
    }

    /**
     * @param GatewayToken|array $gatewayToken
     * @return $this
     */
    public function addGatewayToken($gatewayToken)
    {
        if (is_array($gatewayToken)) {
            $gatewayToken = new GatewayToken($gatewayToken);
        }

        $tokens = $this->getParameter('gateways_tokens');

        $tokens[$gatewayToken->getType()] = $gatewayToken;

        return $this->setParameter('gateways_tokens', $tokens);
    }

    /**
     * @param array $options
     * @return array|bool
     */
    public function addGateway(array $options = array())
    {
        /** @var Message\CreateGatewayRequest $request */
        $request = $this->createGateway($options);

        /** @var Message\Response $response */
        $response = $request->send();

        if ($response->isSuccessful()) {
            $data = $response->getData();

            $gatewayToken = new GatewayToken([
                'type' => $data['gateway_type'],
                'token' => $data['token'],
            ]);

            $this->addGatewayToken($gatewayToken);

            return $data;
        }

        return false;
    }

    /**
     * Load previously created gateways
     */
    public function loadGateways()
    {
        $response = $this->listGateways()->send();

        if ($response->isSuccessful()) {
            foreach ($response->getData() as $gateway) {
                $this->addGatewayToken([
                    'type' => $gateway['gateway_type'],
                    'token' => $gateway['token'],
                ]);
            }
        }
    }
}
