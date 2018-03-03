<?php

namespace Omnipay\Spreedly;

use Omnipay\Tests\GatewayTestCase;
use Omnipay\Common\CreditCard;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setApiKey('API_KEY');
        $this->gateway->setApiSecret('API_SECRET');
        $this->gateway->setDefaultGateway('test');
        $this->gateway->addGatewayToken([
            'type' => 'test',
        ]);
    }

    public function testAuthorize()
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');

        $response = $this->gateway->authorize([
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
        $this->assertEquals('RAK67yYv2ZRUyBRcYxLkh3PalNj', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('1.00', $response->getAmount());
    }

    public function testCaptureFull()
    {
        $this->setMockHttpResponse('CaptureFullSuccess.txt');

        $response = $this->gateway->capture([
            'token' => '1234',
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('UAooev0WJDbSyuh0CqwHGi8WDML', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('1.00', $response->getAmount());
        $this->assertEquals(100, $response->getAmountInteger());
    }

    public function testCapturePartial()
    {
        $this->setMockHttpResponse('CapturePartialSuccess.txt');

        $response = $this->gateway->capture([
            'token' => '1234',
            'amount' => '0.50',
            'currency' => 'USD',
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('S3VrIobz0gC0AI771ml1CndOLs5', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('0.50', $response->getAmount());
        $this->assertEquals(50, $response->getAmountInteger());
    }

    public function testCreateCard()
    {
        $this->setMockHttpResponse('CreateCardSuccess.txt');

        $response = $this->gateway->createCard([
            'card' => new CreditCard([
                'firstName' => 'Example',
                'lastName' => 'User',
                'number' => '4111111111111111',
                'expiryMonth' => '12',
                'expiryYear' => '2020',
                'cvv' => '123',
            ]),
            'email' => 'user@example.com',
            'retained' => false,
            'allow_blank_name' => false,
            'allow_expired_date' => false,
            'allow_blank_date' => false,
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('MsTBXc7aXHVnnTeIJX2LfgtfPqh', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
    }

    public function testDeleteCard()
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

    public function testPurchase()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->gateway->authorize([
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
    }

    public function testCreateGateway()
    {
        $this->setMockHttpResponse('CreateGatewaySuccess.txt');

        $response = $this->gateway->createGateway([
            'type' => 'test'
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('6DqX57I6fHgIuUkVN2HGszjDSu1', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testListGateways()
    {
        $this->setMockHttpResponse('ListGatewaysSuccess.txt');

        /** @var Message\Response $response */
        $response = $this->gateway->listGateways()->send();
    }
}
