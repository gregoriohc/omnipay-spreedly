<?php

namespace Omnipay\Spreedly\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class AuthorizeRequestTest extends TestCase
{
    /**
     * @var AuthorizeRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '10.00',
                'currency' => 'USD',
                //'card' => $this->getValidCard(),
            )
        );
    }

    public function testGetData()
    {
        $card = new CreditCard($this->getValidCard());

        $this->request->setCard($card);

        $data = $this->request->getData();

        $this->assertSame(1000, $data['transaction']['amount']);
        $this->assertSame('USD', $data['transaction']['currency_code']);

        $this->assertSame($card->getFirstName(), $data['transaction']['credit_card']['first_name']);
        $this->assertSame($card->getLastName(), $data['transaction']['credit_card']['last_name']);
        $this->assertSame($card->getNumber(), $data['transaction']['credit_card']['number']);
        $this->assertSame($card->getCvv(), $data['transaction']['credit_card']['verification_value']);
        $this->assertSame($card->getExpiryMonth(), $data['transaction']['credit_card']['month']);
        $this->assertSame($card->getExpiryYear(), $data['transaction']['credit_card']['year']);
    }
}
