<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Spreedly\Message\DeleteCardRequest;

class DeleteCardRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('DeleteCardRequest.txt');

        $request = new DeleteCardRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'payment_method_token' => 'FT6P5qwEI1MArhD8nydJpnHP1uV',
        ]);

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('DeleteCardSuccess.txt');

        $response = $this->gateway->deleteCard([
            'payment_method_token' => 'FT6P5qwEI1MArhD8nydJpnHP1uV',
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('XN5Lm2COxcqP7xFKaZIWDI0CVuh', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('FT6P5qwEI1MArhD8nydJpnHP1uV', $response->getPaymentMethodToken());
    }
}