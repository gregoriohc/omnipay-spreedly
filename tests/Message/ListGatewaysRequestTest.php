<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Spreedly\Message\ListGatewaysRequest;

class ListGatewaysRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('ListGatewaysRequest.txt');

        $request = new ListGatewaysRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('ListGatewaysSuccess.txt');

        /** @var \Omnipay\Spreedly\Message\Response $response */
        $response = $this->gateway->listGateways(array(
            'order' => 'asc',
        ))->send();

        $this->assertCount(2, $response->getData());

        $this->setMockHttpResponse('ListGatewaysPage2Success.txt');

        /** @var \Omnipay\Spreedly\Message\Response $response */
        $response = $this->gateway->listGateways(array(
            'order' => 'asc',
            'since_token' => $response->getSinceToken(),
        ))->send();

        $this->assertCount(0, $response->getData());
        $this->assertNull($response->getSinceToken());
    }
}
