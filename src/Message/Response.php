<?php

namespace Omnipay\Spreedly\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Spreedly\Arr;

/**
 * Response
 */
class Response extends AbstractResponse
{
    /**
     * The raw data contained in the response.
     *
     * @var mixed
     */
    protected $rawData;

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->rawData = $data;
        $this->data = array_shift($data);
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        // Check for succeeded parameter
        $succeeded = Arr::get($this->data, 'succeeded');
        if (!is_null($succeeded)) {
            return $succeeded == true;
        }

        // Check for state parameter
        $state = Arr::get($this->data, 'state');
        if (!is_null($state)) {
            return $state == 'retained';
        }

        // Check for errors array
        $errors = Arr::get($this->data, 'errors');
        if (!is_null($errors)) {
            return is_array($errors) && !count($errors);
        }

        // Check if it's an indexed array (lists)
        if (array_values($this->data) === $this->data) {
            return true;
        }

        return false;
    }

    /**
     * Raw data
     *
     * @return array Response raw data
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * Response Message
     *
     * @return null|string A response message from the payment gateway
     */
    public function getMessage()
    {
        return Arr::get($this->data, 'message');
    }

    /**
     * Response code
     *
     * @return null|string A response code from the payment gateway
     */
    public function getCode()
    {
        return Arr::get($this->data, 'state');
    }

    /**
     * Gateway Reference
     *
     * @return null|string A reference provided by the gateway to represent this transaction
     */
    public function getTransactionReference()
    {
        return Arr::get($this->data, 'token');
    }

    /**
     * Get the transaction ID as generated by the merchant website.
     *
     * @return string
     */
    public function getTransactionId()
    {
        return Arr::get($this->data, 'order_id');
    }

    /**
     * Amount
     *
     * @return null|integer The transaction amount
     */
    public function getAmount()
    {
        $amount = Arr::get($this->data, 'amount');

        if (!is_numeric($amount)) {
            return null;
        }

        return strval(floor($amount / 100)) . '.' . strval($amount % 100);
    }

    /**
     * Integer Amount
     *
     * @return null|integer The transaction integer amount
     */
    public function getAmountInteger()
    {
        return Arr::get($this->data, 'amount');
    }

    /**
     * Payment method token
     *
     * @return null|string The payment method token
     */
    public function getPaymentMethodToken()
    {
        return Arr::get($this->data, 'payment_method.token');
    }

    /**
     * Since token
     *
     * @return null|string The since token of a paginated list
     */
    public function getSinceToken()
    {
        if (is_array($this->data)) {
            if ($last = array_pop($this->data)) {
                return Arr::get($last, 'token');
            }
        }

        return null;
    }

}
