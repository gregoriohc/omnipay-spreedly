<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Spreedly\Message\ListPaymentMethodsRequest;

class ListPaymentMethodsRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('ListPaymentMethodsRequest.txt');

        $request = new ListPaymentMethodsRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('ListPaymentMethodsSuccess.txt');

        /** @var \Omnipay\Spreedly\Message\Response $response */
        $response = $this->gateway->listPaymentMethods([
            'order' => 'asc',
        ])->send();

        $this->assertCount(2, $response->getData());

        $this->setMockHttpResponse('ListPaymentMethodsPage2Success.txt');

        /** @var \Omnipay\Spreedly\Message\Response $response */
        $response = $this->gateway->listPaymentMethods([
            'order' => 'asc',
            'since_token' => $response->getSinceToken(),
        ])->send();

        $this->assertCount(0, $response->getData());
        $this->assertNull($response->getSinceToken());


        $this->setMockHttpResponse('ListPaymentMethodsSuccess.txt');

        /** @var \Omnipay\Spreedly\Message\Response $response */
        $response = $this->gateway->listCards([
            'order' => 'asc',
        ])->send();

        $this->assertCount(2, $response->getData());

        $this->setMockHttpResponse('ListPaymentMethodsPage2Success.txt');

        /** @var \Omnipay\Spreedly\Message\Response $response */
        $response = $this->gateway->listCards([
            'order' => 'asc',
            'since_token' => $response->getSinceToken(),
        ])->send();

        $this->assertCount(0, $response->getData());
        $this->assertNull($response->getSinceToken());
    }
}
