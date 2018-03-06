<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Spreedly\Message\VoidRequest;

class VoidRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('VoidRequest.txt');

        $request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array(
            'transactionReference' => '9IstHnD1haMTBWkIjlYWb5TwuO0',
        ));

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('VoidSuccess.txt');

        $response = $this->gateway->void(array(
            'transactionReference' => '1234',
        ))->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('9IstHnD1haMTBWkIjlYWb5TwuO0', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertNull($response->getAmount());
    }

    public function testRequestResponseError()
    {
        $this->setMockHttpResponse('VoidError.txt');

        $response = $this->gateway->void(array(
            'transactionReference' => '1234',
        ))->send();

        $this->assertFalse($response->isSuccessful());
    }
}
