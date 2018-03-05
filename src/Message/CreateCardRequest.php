<?php
namespace Omnipay\Spreedly\Message;
/**
 * Authorize Request
 *
 * @method Response send()
 */
class CreateCardRequest extends CreatePaymentMethodRequest
{
    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('card');

        return parent::getData();
    }
}
