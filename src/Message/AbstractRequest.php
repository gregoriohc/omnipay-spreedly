<?php

namespace Omnipay\Spreedly\Message;

use Guzzle\Common\Exception\RuntimeException;
use Guzzle\Http\Exception\BadResponseException;
use Omnipay\Common\Helper;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Omnipay\Spreedly\Concerns\HasSpreedlyConfig;

abstract class AbstractRequest extends BaseAbstractRequest
{
    use HasSpreedlyConfig;

    /**
     * @var string
     */
    protected $endpoint = 'https://core.spreedly.com/v1/';

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

    /**
     * @return string
     */
    abstract public function getEndpoint();

    /**
     * @param array|null $data
     * @return \Omnipay\Common\Message\ResponseInterface|Response
     */
    public function sendData($data)
    {
        $httpRequest = $this->httpClient->createRequest(
            $this->getHttpMethod(),
            $this->getEndpoint() . '.json',
            $this->getHeaders(),
            $data
        );

        $httpRequest->setAuth($this->getApiKey(), $this->getApiSecret());

        try {
            $httpResponse = $httpRequest->send();
        } catch (BadResponseException $e) {
            if (false !== strstr($e->getMessage(), '422')) {
                return $this->createResponse($e->getResponse()->json());
            }
            throw $e;
        }

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

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
}
