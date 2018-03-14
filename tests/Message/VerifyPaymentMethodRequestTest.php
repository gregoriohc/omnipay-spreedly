<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Spreedly\Message\VerifyPaymentMethodRequest;

class VerifyPaymentMethodRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('VerifyPaymentMethodRequest.txt');

        $request = new VerifyPaymentMethodRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array(
            'token' => '56wyNnSmuA6CWYP7w0MiYCVIbW6',
            'retain_on_success' => true,
        ));
        $request = $this->setTestGateway($request);

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('VerifyPaymentMethodSuccess.txt');

        $response = $this->gateway->verifyPaymentMethod(array(
            'token' => '56wyNnSmuA6CWYP7w0MiYCVIbW6',
            'retain_on_success' => true,
        ))->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('OwElZY9NAa4QJGOK8gFmdeiHWqz', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('8YtLewbQEDe8RsIY4XCEjiEg1OB', $response->getPaymentMethodToken());


        $this->setMockHttpResponse('VerifyPaymentMethodSuccess.txt');

        $response = $this->gateway->verifyCard(array(
            'token' => '56wyNnSmuA6CWYP7w0MiYCVIbW6',
            'retain_on_success' => true,
        ))->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('OwElZY9NAa4QJGOK8gFmdeiHWqz', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('8YtLewbQEDe8RsIY4XCEjiEg1OB', $response->getPaymentMethodToken());
    }
}
