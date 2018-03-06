<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Spreedly\Message\PurchaseRequest;

class PurchaseRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('PurchaseRequest.txt');

        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'token' => '56wyNnSmuA6CWYP7w0MiYCVIbW6',
            'amount' => 1.00,
            'currency' => 'USD'
        ]);
        $request = $this->setTestGateway($request);

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->gateway->purchase([
            'amount' => '1.00',
            'currency' => 'USD',
            'card' => new CreditCard([
                'firstName' => 'Example',
                'lastName' => 'User',
                'number' => '4111111111111111',
                'expiryMonth' => '12',
                'expiryYear' => '2020',
                'cvv' => '123',
            ]),
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('8I4jCc5E0UR6MkO3zs8I88ERUHq', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('1.00', $response->getAmount());
        $this->assertEquals(100, $response->getAmountInteger());
        $this->assertNull($response->getTransactionId());
        $this->assertArrayHasKey('transaction', $response->getRawData());
    }
}
