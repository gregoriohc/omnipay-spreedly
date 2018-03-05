<?php

namespace Omnipay\Spreedly\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Spreedly\Arr;

class AuthorizeRequestTest extends TestCaseMessage
{
    /**
     * @var AuthorizeRequest
     */
    protected $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'amount' => '1.00',
            'currency' => 'USD',
        ]);

        $this->request = $this->setTestGateway($this->request);
    }

    public function testGetData()
    {
        $mockRequest = $this->mockHttpRequest('AuthorizeRequest.txt');

        $card = new CreditCard([
            'firstName' => 'Joe',
            'lastName' => 'Smith',
            'number' => '4111111111111111',
            'expiryMonth' => 12,
            'expiryYear' => 2018,
            'cvv' => 123,
        ]);

        $this->request->setCard($card);

        $data = $this->request->getData();

        $mockData = json_decode($mockRequest->getBody(), true);

        $this->assertSame($data, $mockData);
    }
}
