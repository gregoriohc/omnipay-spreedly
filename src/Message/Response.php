<?php

namespace Omnipay\Spreedly\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

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
        if (array_key_exists('succeeded', $this->data)) {
            return $this->data['succeeded'] == true;
        }

        // Check for state parameter
        if (array_key_exists('state', $this->data)) {
            return $this->data['state'] == 'retained';
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
        if (array_key_exists('message', $this->data)) {
            return $this->data['message'];
        }

        return null;
    }

    /**
     * Response code
     *
     * @return null|string A response code from the payment gateway
     */
    public function getCode()
    {
        if (array_key_exists('state', $this->data)) {
            return $this->data['state'];
        }

        return null;
    }

    /**
     * Gateway Reference
     *
     * @return null|string A reference provided by the gateway to represent this transaction
     */
    public function getTransactionReference()
    {
        if (array_key_exists('token', $this->data)) {
            return $this->data['token'];
        }

        return null;
    }

    /**
     * Get the transaction ID as generated by the merchant website.
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->getTransactionReference();
    }

    /**
     * Amount
     *
     * @return null|integer The transaction amount
     */
    public function getAmount()
    {
        if (array_key_exists('amount', $this->data)) {
            return strval(floor($this->data['amount'] / 100)) . '.' . strval($this->data['amount'] % 100);
        }

        return null;
    }

    /**
     * Integer Amount
     *
     * @return null|integer The transaction integer amount
     */
    public function getAmountInteger()
    {
        if (array_key_exists('amount', $this->data)) {
            return $this->data['amount'];
        }

        return null;
    }

}
