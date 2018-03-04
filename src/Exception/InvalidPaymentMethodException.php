<?php

namespace Omnipay\Spreedly\Exception;

use Omnipay\Common\Exception\OmnipayException;

/**
 * Invalid Payment Method Exception
 *
 * Thrown when a payment method is invalid or missing required fields.
 */
class InvalidPaymentMethodException extends \Exception implements OmnipayException
{
}