<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Spreedly\Message\UpdateCardRequest;

class UpdateCardRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('UpdateCardRequest.txt');

        $request = new UpdateCardRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'payment_method_token' => '56wyNnSmuA6CWYP7w0MiYCVIbW6',
            'first_name' => 'Newfirst',
            'last_name' => 'Newlast',
        ]);

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('UpdateCardSuccess.txt');

        $response = $this->gateway->updateCard([
            'payment_method_token' => '1rpKvP8zOUhj4Y9EDrIoIYQzzD5',
            'allow_blank_name' => false,
            'allow_expired_date' => false,
            'allow_blank_date' => false,
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('1rpKvP8zOUhj4Y9EDrIoIYQzzD5', $response->getTransactionReference());
    }
}
