<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Spreedly\Message\CreateCardRequest;

class CreateCardRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('CreateCardRequest.txt');

        $request = new CreateCardRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'card' => new CreditCard([
                'firstName' => 'Joe',
                'lastName' => 'Jones',
                'number' => '5555555555554444',
                'cvv' => '423',
                'expiryMonth' => '3',
                'expiryYear' => '2032',
            ]),
            'email' => 'joey@example.com',
        ]);

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('CreateCardSuccess.txt');

        $response = $this->gateway->createCard([
            'card' => new CreditCard([
                'firstName' => 'Example',
                'lastName' => 'User',
                'number' => '4111111111111111',
                'expiryMonth' => '12',
                'expiryYear' => '2020',
                'cvv' => '123',
            ]),
            'email' => 'user@example.com',
            'retained' => false,
            'allow_blank_name' => false,
            'allow_expired_date' => false,
            'allow_blank_date' => false,
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('MsTBXc7aXHVnnTeIJX2LfgtfPqh', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
    }
}
