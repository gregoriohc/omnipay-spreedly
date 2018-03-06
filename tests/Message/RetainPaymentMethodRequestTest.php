<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Spreedly\Message\RetainPaymentMethodRequest;

class RetainPaymentMethodRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('RetainPaymentMethodRequest.txt');

        $request = new RetainPaymentMethodRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'payment_method_token' => '56wyNnSmuA6CWYP7w0MiYCVIbW6',
        ]);

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('RetainPaymentMethodSuccess.txt');

        $response = $this->gateway->retainPaymentMethod([
            'payment_method_token' => '1rpKvP8zOUhj4Y9EDrIoIYQzzD5',
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('7Mod2PL9OM7AuHBmlPSRvKa02fE', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('1rpKvP8zOUhj4Y9EDrIoIYQzzD5', $response->getPaymentMethodToken());
    }
}
