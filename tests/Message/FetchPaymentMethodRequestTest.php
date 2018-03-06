<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Spreedly\Message\FetchPaymentMethodRequest;

class FetchPaymentMethodRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('FetchPaymentMethodRequest.txt');

        $request = new FetchPaymentMethodRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array(
            'payment_method_token' => '1rpKvP8zOUhj4Y9EDrIoIYQzzD5',
        ));

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('FetchPaymentMethodSuccess.txt');

        $response = $this->gateway->fetchPaymentMethod(array(
            'payment_method_token' => '1rpKvP8zOUhj4Y9EDrIoIYQzzD5',
        ))->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('1rpKvP8zOUhj4Y9EDrIoIYQzzD5', $response->getTransactionReference());
        $this->assertEquals('1rpKvP8zOUhj4Y9EDrIoIYQzzD5', $response->getPaymentMethodToken());


        $this->setMockHttpResponse('FetchPaymentMethodSuccess.txt');

        $response = $this->gateway->fetchCard(array(
            'payment_method_token' => '1rpKvP8zOUhj4Y9EDrIoIYQzzD5',
        ))->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('1rpKvP8zOUhj4Y9EDrIoIYQzzD5', $response->getTransactionReference());
        $this->assertEquals('1rpKvP8zOUhj4Y9EDrIoIYQzzD5', $response->getPaymentMethodToken());
    }
}
