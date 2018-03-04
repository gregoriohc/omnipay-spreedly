<?php
/**
 * Bank Account class
 */

namespace Omnipay\Spreedly;

use Omnipay\Common\Helper;
use Omnipay\Spreedly\Exception\InvalidPaymentMethodException;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Bank Account class
 *
 * This class defines and abstracts all of the bank account types used
 * throughout the Omnipay Spreedly system.
 *
 * Example:
 *
 * <code>
 *   // Define bank account parameters, which should look like this
 *   $parameters = [
 *       'firstName' => 'Bobby',
 *       'lastName' => 'Tables',
 *       'number' => '9876543210',
 *       'routingNumber' => '021000021',
 *       'type' => 'checking',
 *       'holderType' => 'personal',
 *   ];
 *
 *   // Create a bank account object
 *   $bankAccount = new BankAcount($parameters);
 * </code>
 *
 * The full list of bank account attributes that may be set via the parameter to
 * *new* is as follows:
 *
 * * firstName
 * * lastName
 * * number
 * * routingNumber
 * * type
 * * holderType
 *
 * If any unknown parameters are passed in, they will be ignored.  No error is thrown.
 */
class BankAccount
{
    const TYPE_CHECKING = 'checking';

    const HOLDER_TYPE_PERSONAL = 'personal';

    /**
     * Internal storage of all of the bank account parameters.
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    protected $parameters;

    /**
     * Create a new BankAccount object using the specified parameters
     *
     * @param array $parameters An array of parameters to set on the new object
     */
    public function __construct($parameters = null)
    {
        $this->initialize($parameters);
    }

    /**
     * Initialize the object with parameters.
     *
     * If any unknown parameters passed, they will be ignored.
     *
     * @param array $parameters An associative array of parameters
     * @return BankAccount provides a fluent interface.
     */
    public function initialize(array $parameters = null)
    {
        $this->parameters = new ParameterBag;

        Helper::initialize($this, $parameters);

        return $this;
    }

    /**
     * Get all parameters.
     *
     * @return array An associative array of parameters.
     */
    public function getParameters()
    {
        return $this->parameters->all();
    }

    /**
     * Get one parameter.
     *
     * @param string $key
     * @return mixed A single parameter value.
     */
    protected function getParameter($key)
    {
        return $this->parameters->get($key);
    }

    /**
     * Set one parameter.
     *
     * @param string $key Parameter key
     * @param mixed $value Parameter value
     * @return BankAccount provides a fluent interface.
     */
    protected function setParameter($key, $value)
    {
        $this->parameters->set($key, $value);

        return $this;
    }

    /**
     * Validate this bank account. If the bank account is invalid, InvalidBankAccountException is thrown.
     *
     * This method is called internally by gateways to avoid wasting time with an API call
     * when the bank account is clearly invalid.
     *
     * Generally if you want to validate the bank account yourself with custom error
     * messages, you should use your framework's validation library, not this method.
     *
     * @throws InvalidPaymentMethodException
     * @return void
     */
    public function validate()
    {
        $requiredParameters = array(
            'first_name' => 'bank account first name',
            'last_name' => 'bank account last name',
            'number' => 'bank account number',
            'routing_number' => 'bank account routing number',
        );

        foreach ($requiredParameters as $key => $val) {
            if (!$this->getParameter($key)) {
                throw new InvalidPaymentMethodException("The $val is required");
            }
        }
    }

    /**
     * Get Bank Account First Name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->getParameter('first_name');
    }

    /**
     * Set Bank Account First Name.
     *
     * @param string $value Parameter value
     * @return BankAccount provides a fluent interface.
     */
    public function setFirstName($value)
    {
        return $this->setParameter('first_name', $value);
    }

    /**
     * Get Bank Account Last Name.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->getParameter('last_name');
    }

    /**
     * Set Bank Account Last Name.
     *
     * @param string $value Parameter value
     * @return BankAccount provides a fluent interface.
     */
    public function setLastName($value)
    {
        return $this->setParameter('last_name', $value);
    }

    /**
     * Get Bank Account Number.
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->getParameter('number');
    }

    /**
     * Set Bank Account Number
     *
     * Non-numeric characters are stripped out of the bank account number, so
     * it's safe to pass in strings such as "4444-3333 2222 1111" etc.
     *
     * @param string $value Parameter value
     * @return BankAccount provides a fluent interface.
     */
    public function setNumber($value)
    {
        // strip non-numeric characters
        return $this->setParameter('number', preg_replace('/\D/', '', $value));
    }

    /**
     * Get Bank Account Routing Number.
     *
     * @return string
     */
    public function getRoutingNumber()
    {
        return $this->getParameter('routing_number');
    }

    /**
     * Set Bank Account Routing Number
     *
     * Non-numeric characters are stripped out of the bank account number, so
     * it's safe to pass in strings such as "4444-3333 2222 1111" etc.
     *
     * @param string $value Parameter value
     * @return BankAccount provides a fluent interface.
     */
    public function setRoutingNumber($value)
    {
        // strip non-numeric characters
        return $this->setParameter('routing_number', preg_replace('/\D/', '', $value));
    }

    /**
     * Get Bank Account Type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->getParameter('type');
    }

    /**
     * Set Bank Account Type.
     *
     * @param string $value Parameter value
     * @return BankAccount provides a fluent interface.
     */
    public function setType($value)
    {
        return $this->setParameter('type', $value);
    }

    /**
     * Get Bank Account Holder Type.
     *
     * @return string
     */
    public function getHolderType()
    {
        return $this->getParameter('holder_type');
    }

    /**
     * Set Bank Account Holder Type.
     *
     * @param string $value Parameter value
     * @return BankAccount provides a fluent interface.
     */
    public function setHolderType($value)
    {
        return $this->setParameter('holder_type', $value);
    }
}