<?php

namespace Omnipay\Spreedly\Message\Concerns;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Spreedly\Arr;

trait HasGatewaySpecificFields
{
    protected $gatewaySpecificFieldsConfig = array(
        'test' => array(
            'required' => array(),
            'optional' => array(),
        ),
        'fake' => array(
            'required' => array(
                'foo',
            ),
            'optional' => array(
                'bar',
            ),
        ),
        'conekta' => array(
            'required' => array(),
            'optional' => array(
                'device_fingerprint',
                'customer',
                'phone',
            ),
        ),
    );

    public function getGatewaySpecificFields()
    {
        return $this->getParameter('gateways_specific_fields');
    }

    public function setGatewaySpecificFields($value)
    {
        return $this->setParameter('gateways_specific_fields', $value);
    }

    /**
     * @param array $data
     * @return array
     * @throws InvalidRequestException
     */
    protected function fillGatewaySpecificFields($data)
    {
        $gateway = $this->getGateway();
        $gatewaySpecificFieldsData = $this->getGatewaySpecificFields();
        $gatewaySpecificFieldsConfig = Arr::get($this->gatewaySpecificFieldsConfig, $gateway);
        if ($gatewaySpecificFieldsData && $gatewaySpecificFieldsConfig) {
            foreach ($gatewaySpecificFieldsConfig['required'] as $field) {
                $value = Arr::get($gatewaySpecificFieldsData, $field);
                if (!is_null($value)) {
                    Arr::set($data, 'gateway_specific_fields.' . $gateway . '.' . $field, $value);
                } else {
                    throw new InvalidRequestException("Missing gateway specific field: $field.");
                }
            }
            foreach ($gatewaySpecificFieldsConfig['optional'] as $field) {
                $value = Arr::get($gatewaySpecificFieldsData, $field);
                if (!is_null($value)) {
                    Arr::set($data, 'gateway_specific_fields.' . $gateway . '.' . $field, $value);
                }
            }
        }

        return $data;
    }
}
