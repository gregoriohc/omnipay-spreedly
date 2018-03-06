<?php

namespace Omnipay\Spreedly\Message;

/**
 * @method Response send()
 */
class CreateGatewayRequest extends AbstractRequest
{

    public function getType()
    {
        return $this->getParameter('type');
    }

    public function setType($value)
    {
        return $this->setParameter('type', $value);
    }

    public function getConfig()
    {
        return $this->getParameter('config');
    }

    public function setConfig($value)
    {
        return $this->setParameter('config', $value);
    }

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('type');

        $config = (array) ($this->getConfig() ?: []);
        $config['gateway_type'] = $this->getType();

        return [
            'gateway' => $config,
        ];
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint . 'gateways';
    }


}
