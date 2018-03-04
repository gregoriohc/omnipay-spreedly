<?php
namespace Omnipay\Spreedly\Message;
/**
 * Authorize Request
 *
 * @method Response send()
 */
class CreateCardRequest extends CreatePaymentMethodRequest
{
    public function getData()
    {
        $this->validate('card');

        return parent::getData();
    }
}
