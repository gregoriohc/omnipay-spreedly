<?php

namespace Omnipay\Spreedly\Concerns;

trait HasCustomerData
{
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

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }
}
