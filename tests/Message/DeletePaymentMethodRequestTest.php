<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Spreedly\Message\DeletePaymentMethodRequest;

class DeletePaymentMethodRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('DeletePaymentMethodRequest.txt');

        $request = new DeletePaymentMethodRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array(
            'payment_method_token' => 'FT6P5qwEI1MArhD8nydJpnHP1uV',
        ));

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('DeletePaymentMethodSuccess.txt');

        $response = $this->gateway->deletePaymentMethod(array(
            'payment_method_token' => 'FT6P5qwEI1MArhD8nydJpnHP1uV',
        ))->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('XN5Lm2COxcqP7xFKaZIWDI0CVuh', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('FT6P5qwEI1MArhD8nydJpnHP1uV', $response->getPaymentMethodToken());


        $this->setMockHttpResponse('DeletePaymentMethodSuccess.txt');

        $response = $this->gateway->deleteCard(array(
            'payment_method_token' => 'FT6P5qwEI1MArhD8nydJpnHP1uV',
        ))->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('XN5Lm2COxcqP7xFKaZIWDI0CVuh', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('FT6P5qwEI1MArhD8nydJpnHP1uV', $response->getPaymentMethodToken());
    }
}
