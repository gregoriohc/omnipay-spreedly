<?php

namespace Omnipay\Spreedly;

use Omnipay\Common\AbstractGateway;
use Omnipay\Spreedly\Concerns\HasSpreedlyConfig;

/**
 * Spreedly Gateway
 */
class Gateway extends AbstractGateway
{
    use HasSpreedlyConfig;

    public function getName()
    {
        return 'Spreedly';
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
     * @return Message\CreatePaymentMethodRequest
     */
    public function createCard(array $options = array())
    {
        return $this->createRequest(Message\CreatePaymentMethodRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\UpdatePaymentMethodRequest
     */
    public function updateCard(array $options = array())
    {
        return $this->createRequest(Message\UpdatePaymentMethodRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\RetainPaymentMethodRequest
     */
    public function retainCard(array $options = array())
    {
        return $this->createRequest(Message\RetainPaymentMethodRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\DeletePaymentMethodRequest
     */
    public function deleteCard(array $options = array())
    {
        return $this->createRequest(Message\DeletePaymentMethodRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\FetchPaymentMethodRequest
     */
    public function fetchCard(array $options = array())
    {
        return $this->createRequest(Message\FetchPaymentMethodRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\ListPaymentMethodsRequest
     */
    public function listCards(array $options = array())
    {
        return $this->createRequest(Message\ListPaymentMethodsRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\CreatePaymentMethodRequest
     */
    public function createPaymentMethod(array $options = array())
    {
        return $this->createRequest(Message\CreatePaymentMethodRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\UpdatePaymentMethodRequest
     */
    public function updatePaymentMethod(array $options = array())
    {
        return $this->createRequest(Message\UpdatePaymentMethodRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\RetainPaymentMethodRequest
     */
    public function retainPaymentMethod(array $options = array())
    {
        return $this->createRequest(Message\RetainPaymentMethodRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\DeletePaymentMethodRequest
     */
    public function deletePaymentMethod(array $options = array())
    {
        return $this->createRequest(Message\DeletePaymentMethodRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\FetchPaymentMethodRequest
     */
    public function fetchPaymentMethod(array $options = array())
    {
        return $this->createRequest(Message\FetchPaymentMethodRequest::class, $options);
    }

    /**
     * @param array $options
     * @return Message\ListPaymentMethodsRequest
     */
    public function listPaymentMethods(array $options = array())
    {
        return $this->createRequest(Message\ListPaymentMethodsRequest::class, $options);
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
     * @param array $gatewayToken
     * @return $this
     */
    public function addGatewayToken($gatewayToken)
    {
        $tokens = $this->getGatewaysTokens();

        $tokens[] = $gatewayToken;

        return $this->setGatewaysTokens($tokens);
    }

    /**
     * @param array $options
     * @return array|null
     */
    public function addGateway(array $options = array())
    {
        /** @var Message\CreateGatewayRequest $request */
        $request = $this->createGateway($options);

        /** @var Message\Response $response */
        $response = $request->send();

        if ($response->isSuccessful()) {
            $data = $response->getData();

            $gatewayToken = [
                'type' => $data['gateway_type'],
                'token' => $data['token'],
            ];

            $this->addGatewayToken($gatewayToken);

            return $gatewayToken;
        }

        return null;
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

        return $this;
    }
}
