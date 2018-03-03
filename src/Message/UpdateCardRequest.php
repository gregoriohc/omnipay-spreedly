<?php
namespace Omnipay\Spreedly\Message;
/**
 * Authorize Request
 *
 * @method Response send()
 */
class UpdateCardRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('payment_method_token');

        $data = $this->fillExistingParameters([], [
            'allow_blank_name' => 'allow_blank_name',
            'allow_expired_date' => 'allow_expired_date',
            'allow_blank_date' => 'allow_blank_date',
        ]);

        return ['payment_method' => $data];
    }

    public function getEndpoint()
    {
        return $this->endpoint . 'payment_methods/' . $this->getPaymentMethodToken();
    }

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
}
