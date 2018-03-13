<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Spreedly\Message\StorePaymentMethodRequest;

class StorePaymentMethodRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('StorePaymentMethodRequest.txt');

        $request = new StorePaymentMethodRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array(
            'token' => '56wyNnSmuA6CWYP7w0MiYCVIbW6',
        ));
        $request = $this->setTestGateway($request);

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('StorePaymentMethodSuccess.txt');

        $response = $this->gateway->storePaymentMethod(array(
            'token' => '56wyNnSmuA6CWYP7w0MiYCVIbW6',
        ))->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('VWwymN7KGNOiHMbVnZDXLvw2XGV', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('HFGug6VypMM30wKAFGzDgoXx0CM', $response->getPaymentMethodToken());


        $this->setMockHttpResponse('StorePaymentMethodSuccess.txt');

        $response = $this->gateway->storeCard(array(
            'token' => '56wyNnSmuA6CWYP7w0MiYCVIbW6',
        ))->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('VWwymN7KGNOiHMbVnZDXLvw2XGV', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('HFGug6VypMM30wKAFGzDgoXx0CM', $response->getPaymentMethodToken());
    }
}
