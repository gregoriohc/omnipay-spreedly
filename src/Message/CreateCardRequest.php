<?php
namespace Omnipay\Spreedly\Message;
/**
 * Authorize Request
 *
 * @method Response send()
 */
class CreateCardRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->validateAndGetPaymentMethodData();

        $data = $this->fillExistingParameters($data, [
            'email' => 'email',
            'retained' => 'retained',
            'allow_blank_name' => 'allow_blank_name',
            'allow_expired_date' => 'allow_expired_date',
            'allow_blank_date' => 'allow_blank_date',
        ]);

        return ['payment_method' => $data];
    }

    public function getEndpoint()
    {
        return $this->endpoint . 'payment_methods';
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getRetained()
    {
        return $this->getParameter('retained');
    }

    public function setRetained($value)
    {
        return $this->setParameter('retained', $value);
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
