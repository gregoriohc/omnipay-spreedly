<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Spreedly\Message\AuthorizeRequest;

class AuthorizeRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('AuthorizeRequest.txt');

        $request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'amount' => '1.00',
            'currency' => 'USD',
        ]);
        $request = $this->setTestGateway($request);
        $request->setCard(new CreditCard([
            'firstName' => 'Joe',
            'lastName' => 'Smith',
            'number' => '4111111111111111',
            'expiryMonth' => 12,
            'expiryYear' => 2018,
            'cvv' => 123,
        ]));

        $this->assertSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');

        $response = $this->gateway->authorize([
            'amount' => '1.00',
            'currency' => 'USD',
            'card' => new CreditCard([
                'firstName' => 'Example',
                'lastName' => 'User',
                'number' => '4111111111111111',
                'expiryMonth' => '12',
                'expiryYear' => '2020',
                'cvv' => '123',
            ]),
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('RAK67yYv2ZRUyBRcYxLkh3PalNj', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('1.00', $response->getAmount());
    }
}
