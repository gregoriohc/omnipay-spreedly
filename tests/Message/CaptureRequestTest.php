<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Spreedly\Message\CaptureRequest;

class CaptureRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('CaptureRequest.txt');

        $request = new CaptureRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'amount' => '0.50',
            'currency' => 'USD',
        ]);
        $request->setTransactionReference('UAooev0WJDbSyuh0CqwHGi8WDML');

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('CaptureFullSuccess.txt');

        $response = $this->gateway->capture([
            'transactionReference' => '1234',
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('UAooev0WJDbSyuh0CqwHGi8WDML', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('1.00', $response->getAmount());
        $this->assertEquals(100, $response->getAmountInteger());

        $this->setMockHttpResponse('CapturePartialSuccess.txt');

        $response = $this->gateway->capture([
            'transactionReference' => '1234',
            'amount' => '0.50',
            'currency' => 'USD',
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('S3VrIobz0gC0AI771ml1CndOLs5', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('0.50', $response->getAmount());
        $this->assertEquals(50, $response->getAmountInteger());

        $this->setMockHttpResponse('CaptureFullSuccess.txt');

        $response = $this->gateway->capture([
            'gateway' => 'fake',
            'transactionReference' => '1234',
            'gateway_specific_fields' => [
                'foo' => '123',
                'bar' => 'abc',
            ],
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('UAooev0WJDbSyuh0CqwHGi8WDML', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('1.00', $response->getAmount());
        $this->assertEquals(100, $response->getAmountInteger());
    }
}
