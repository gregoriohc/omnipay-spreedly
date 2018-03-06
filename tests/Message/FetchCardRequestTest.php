<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Spreedly\Message\FetchCardRequest;

class FetchCardRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('FetchCardRequest.txt');

        $request = new FetchCardRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'payment_method_token' => '1rpKvP8zOUhj4Y9EDrIoIYQzzD5',
        ]);

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('FetchCardSuccess.txt');

        $response = $this->gateway->fetchCard([
            'payment_method_token' => '1rpKvP8zOUhj4Y9EDrIoIYQzzD5',
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('1rpKvP8zOUhj4Y9EDrIoIYQzzD5', $response->getTransactionReference());
        $this->assertEquals('1rpKvP8zOUhj4Y9EDrIoIYQzzD5', $response->getPaymentMethodToken());
    }
}
