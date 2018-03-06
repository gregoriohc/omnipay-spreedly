<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Spreedly\Message\ListCardsRequest;

class ListCardsRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('ListCardsRequest.txt');

        $request = new ListCardsRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('ListCardsSuccess.txt');

        /** @var \Omnipay\Spreedly\Message\Response $response */
        $response = $this->gateway->listCards([
            'order' => 'asc',
        ])->send();

        $this->assertCount(2, $response->getData());

        $this->setMockHttpResponse('ListCardsPage2Success.txt');

        /** @var \Omnipay\Spreedly\Message\Response $response */
        $response = $this->gateway->listGateways([
            'order' => 'asc',
            'since_token' => $response->getSinceToken(),
        ])->send();

        $this->assertCount(0, $response->getData());
        $this->assertNull($response->getSinceToken());
    }
}
