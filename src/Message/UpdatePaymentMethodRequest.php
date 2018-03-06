<?php
namespace Omnipay\Spreedly\Message;
/**
 * Authorize Request
 *
 * @method Response send()
 */
class UpdatePaymentMethodRequest extends AbstractRequest
{
    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('payment_method_token');

        $data = $this->fillExistingParameters([], [
            'allow_blank_name' => 'allow_blank_name',
            'allow_expired_date' => 'allow_expired_date',
            'allow_blank_date' => 'allow_blank_date',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
        ]);

        return ['payment_method' => $data];
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint . 'payment_methods/' . $this->getPaymentMethodToken();
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return 'PUT';
    }

    public function getPaymentMethodToken()
    {
        return $this->getParameter('payment_method_token');
    }

    public function setPaymentMethodToken($value)
    {
        return $this->setParameter('payment_method_token', $value);
    }

    public function getAllowBlankName()
    {
        return $this->getParameter('allow_blank_name');
    }

    public function setAllowBlankName($value)
    {
        return $this->setParameter('allow_blank_name', $value);
    }

    public function getAllowExpiredDate()
    {
        return $this->getParameter('allow_expired_date');
    }

    public function setAllowExpiredDate($value)
    {
        return $this->setParameter('allow_expired_date', $value);
    }

    public function getAllowBlankDate()
    {
        return $this->getParameter('allow_blank_date');
    }

    public function setAllowBlankDate($value)
    {
        return $this->setParameter('allow_blank_date', $value);
    }

    public function getFirstName()
    {
        return $this->getParameter('first_name');
    }

    public function setFirstName($value)
    {
        return $this->setParameter('first_name', $value);
    }

    public function getLastName()
    {
        return $this->getParameter('last_name');
    }

    public function setLastName($value)
    {
        return $this->setParameter('last_name', $value);
    }
}
