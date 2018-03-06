<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Spreedly\Message\RefundRequest;

class RefundRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('RefundFullRequest.txt');

        $request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'transactionReference' => '1234',
        ]);

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());

        $mockRequest = $this->mockHttpRequest('RefundPartialRequest.txt');

        $request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'transactionReference' => '1234',
            'amount' => 0.50,
            'currency' => 'USD'
        ]);

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('RefundFullSuccess.txt');

        $response = $this->gateway->refund([
            'transactionReference' => '1234',
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('TBuWJz3OyvaDgcHyEhmY1hv9InB', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('1.00', $response->getAmount());
        $this->assertEquals(100, $response->getAmountInteger());

        $this->setMockHttpResponse('RefundPartialSuccess.txt');

        $response = $this->gateway->refund([
            'transactionReference' => '1234',
            'amount' => '0.50',
            'currency' => 'USD',
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('RpJnRjeZFxJyUVLmFJ7hQAqKntx', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('0.50', $response->getAmount());
        $this->assertEquals(50, $response->getAmountInteger());
    }
}
