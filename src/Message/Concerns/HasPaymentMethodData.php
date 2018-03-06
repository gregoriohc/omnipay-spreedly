<?php

namespace Omnipay\Spreedly\Message\Concerns;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Spreedly\BankAccount;

trait HasPaymentMethodData
{
    /**
     * Get the bank account.
     *
     * @return BankAccount
     */
    public function getBankAccount()
    {
        return $this->getParameter('bank_account');
    }

    /**
     * Sets the card.
     *
     * @param BankAccount $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setBankAccount($value)
    {
        if ($value && !$value instanceof BankAccount) {
            $value = new BankAccount($value);
        }

        return $this->setParameter('bank_account', $value);
    }

    /**
     * @return array
     * @throws InvalidRequestException
     * @throws \Omnipay\Common\Exception\InvalidCreditCardException
     * @throws \Omnipay\Spreedly\Exception\InvalidPaymentMethodException
     */
    protected function validateAndGetPaymentMethodData()
    {
        $data = [];

        // Set data depending on payment method
        if ($this->parameters->has('token')) {
            // Token payment method
            $data['payment_method_token'] = $this->getToken();
        } elseif ($this->parameters->has('card')) {
            // Card payment method
            /** @var CreditCard $card */
            $card = $this->getCard();
            $card->validate();

            $data['credit_card'] = [
                'first_name' => $card->getFirstName(),
                'last_name' => $card->getLastName(),
                'number' => $card->getNumber(),
                'verification_value' => (string) $card->getCvv(),
                'month' => (string) $card->getExpiryMonth(),
                'year' => (string) $card->getExpiryYear(),
            ];
        } elseif ($this->parameters->has('bank_account')) {
            // Bank Account payment method
            /** @var BankAccount $bankAccount */
            $bankAccount = $this->getBankAccount();
            $bankAccount->validate();

            $data['bank_account'] = [
                'first_name' => $bankAccount->getFirstName(),
                'last_name' => $bankAccount->getLastName(),
                'bank_account_number' => (string) $bankAccount->getNumber(),
                'bank_routing_number' => (string) $bankAccount->getRoutingNumber(),
                'bank_account_type' => $bankAccount->getType(),
                'bank_account_holder_type' => $bankAccount->getHolderType(),
            ];
        } else {
            // ToDo: Implement other payment methods (Android and Apple Pay...)
            throw new InvalidRequestException("Missing payment method.");
        }

        return $data;
    }
}
