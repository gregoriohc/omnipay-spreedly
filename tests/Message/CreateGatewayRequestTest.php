<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Spreedly\Message\CreateGatewayRequest;

class CreateGatewayRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('CreateGatewayRequest.txt');

        $request = new CreateGatewayRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array(
            'type' => 'stripe',
            'config' => array(
                'login' => 'your Stripe API secret',
            ),
        ));

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('CreateGatewaySuccess.txt');

        $response = $this->gateway->createGateway(array(
            'type' => 'test',
            'config' => array(),
        ))->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('6DqX57I6fHgIuUkVN2HGszjDSu1', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }
}
