<?php

namespace Omnipay\Spreedly\Tests;

use Guzzle\Http\Exception\ClientErrorResponseException;
use Omnipay\Spreedly\Arr;
use Omnipay\Spreedly\Gateway;
use Omnipay\Spreedly\Message\AuthorizeRequest;
use Omnipay\Spreedly\Message\CaptureRequest;
use Omnipay\Spreedly\Message\CreateCardRequest;
use Omnipay\Spreedly\Message\CreateGatewayRequest;
use Omnipay\Spreedly\Message\CreatePaymentMethodRequest;
use Omnipay\Spreedly\Message\DeleteCardRequest;
use Omnipay\Spreedly\Message\DeletePaymentMethodRequest;
use Omnipay\Spreedly\Message\FetchCardRequest;
use Omnipay\Spreedly\Message\FetchPaymentMethodRequest;
use Omnipay\Spreedly\Message\ListCardsRequest;
use Omnipay\Spreedly\Message\ListGatewaysRequest;
use Omnipay\Spreedly\Message\ListPaymentMethodsRequest;
use Omnipay\Tests\GatewayTestCase;
use Omnipay\Common\CreditCard;

class GatewayTest extends GatewayTestCase
{
    /**
     * @var Gateway
     */
    protected $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setApiKey('API_KEY');
        $this->gateway->setApiSecret('API_SECRET');
        $this->gateway->setDefaultGateway('test');
        $this->gateway->setGatewaysTokens([
            [
                'type' => 'test',
                'token' => '1234',
            ],
            [
                'type' => 'fake',
                'token' => '1234',
            ],
            [
                'type' => 'conekta',
                'token' => '1234',
            ],
        ]);
    }

    public function testAuthorize()
    {
        $request = $this->gateway->authorize();

        $this->assertInstanceOf(AuthorizeRequest::class, $request);
    }

    public function testCapture()
    {
        $request = $this->gateway->capture();

        $this->assertInstanceOf(CaptureRequest::class, $request);
    }

    public function testCreateCard()
    {
        $request = $this->gateway->createCard();

        $this->assertInstanceOf(CreateCardRequest::class, $request);
    }

    public function testCreateGateway()
    {
        $request = $this->gateway->createGateway();

        $this->assertInstanceOf(CreateGatewayRequest::class, $request);
    }

    public function testCreatePaymentMethod()
    {
        $request = $this->gateway->createPaymentMethod();

        $this->assertInstanceOf(CreatePaymentMethodRequest::class, $request);
    }

    public function testDeleteCard()
    {
        $request = $this->gateway->deleteCard();

        $this->assertInstanceOf(DeleteCardRequest::class, $request);
    }

    public function testDeletePaymentMethod()
    {
        $request = $this->gateway->deletePaymentMethod();

        $this->assertInstanceOf(DeletePaymentMethodRequest::class, $request);
    }

    public function testFetchCard()
    {
        $request = $this->gateway->fetchCard();

        $this->assertInstanceOf(FetchCardRequest::class, $request);
    }

    public function testFetchPaymentMethod()
    {
        $request = $this->gateway->fetchPaymentMethod();

        $this->assertInstanceOf(FetchPaymentMethodRequest::class, $request);
    }

    public function testListCards()
    {
        $request = $this->gateway->listCards();

        $this->assertInstanceOf(ListCardsRequest::class, $request);
    }

    public function testListGateways()
    {
        $request = $this->gateway->listGateways();

        $this->assertInstanceOf(ListGatewaysRequest::class, $request);
    }

    public function testListPaymentMethods()
    {
        $request = $this->gateway->listPaymentMethods();

        $this->assertInstanceOf(ListPaymentMethodsRequest::class, $request);
    }

    public function testRetainCard()
    {
        $this->setMockHttpResponse('RetainCardSuccess.txt');

        $response = $this->gateway->retainCard([
            'payment_method_token' => '1rpKvP8zOUhj4Y9EDrIoIYQzzD5',
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('7Mod2PL9OM7AuHBmlPSRvKa02fE', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('1rpKvP8zOUhj4Y9EDrIoIYQzzD5', $response->getPaymentMethodToken());
    }

    public function testUpdateCard()
    {
        $this->setMockHttpResponse('UpdateCardSuccess.txt');

        $response = $this->gateway->updateCard([
            'payment_method_token' => '1rpKvP8zOUhj4Y9EDrIoIYQzzD5',
            'allow_blank_name' => false,
            'allow_expired_date' => false,
            'allow_blank_date' => false,
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('1rpKvP8zOUhj4Y9EDrIoIYQzzD5', $response->getTransactionReference());
    }

    public function testRetainPaymentMethod()
    {
        $this->setMockHttpResponse('RetainPaymentMethodSuccess.txt');

        $response = $this->gateway->retainPaymentMethod([
            'payment_method_token' => '1rpKvP8zOUhj4Y9EDrIoIYQzzD5',
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('7Mod2PL9OM7AuHBmlPSRvKa02fE', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('1rpKvP8zOUhj4Y9EDrIoIYQzzD5', $response->getPaymentMethodToken());
    }

    public function testUpdatePaymentMethod()
    {
        $this->setMockHttpResponse('UpdatePaymentMethodSuccess.txt');

        $response = $this->gateway->updatePaymentMethod([
            'payment_method_token' => '1rpKvP8zOUhj4Y9EDrIoIYQzzD5',
            'allow_blank_name' => false,
            'allow_expired_date' => false,
            'allow_blank_date' => false,
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('1rpKvP8zOUhj4Y9EDrIoIYQzzD5', $response->getTransactionReference());
    }

    public function testPurchase()
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

    public function testRefundFull()
    {
        $this->setMockHttpResponse('RefundFullSuccess.txt');

        $response = $this->gateway->refund([
            'transactionReference' => '1234',
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('TBuWJz3OyvaDgcHyEhmY1hv9InB', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('1.00', $response->getAmount());
        $this->assertEquals(100, $response->getAmountInteger());
    }

    public function testRefundPartial()
    {
        $this->setMockHttpResponse('RefundPartialSuccess.txt');

        $response = $this->gateway->refund([
            'transactionReference' => '1234',
            'amount' => '0.50',
            'currency' => 'USD',
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('RpJnRjeZFxJyUVLmFJ7hQAqKntx', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('0.50', $response->getAmount());
        $this->assertEquals(50, $response->getAmountInteger());
    }

    public function testVoid()
    {
        $this->setMockHttpResponse('VoidSuccess.txt');

        $response = $this->gateway->void([
            'transactionReference' => '1234',
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('9IstHnD1haMTBWkIjlYWb5TwuO0', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertNull($response->getAmount());
    }

    public function testVoidError()
    {
        $this->setMockHttpResponse('VoidError.txt');

        $response = $this->gateway->void([
            'transactionReference' => '1234',
        ])->send();

        $this->assertFalse($response->isSuccessful());
    }

    public function testAddGateway()
    {
        $this->setMockHttpResponse('CreateGatewaySuccess.txt');

        $gatewayToken = $this->gateway->addGateway([
            'type' => 'test',
            'config' => [],
        ]);

        $this->assertEquals('test', Arr::get($gatewayToken, 'type'));
        $this->assertEquals('6DqX57I6fHgIuUkVN2HGszjDSu1', Arr::get($gatewayToken, 'token'));
    }

    public function testLoadGateways()
    {
        $this->setMockHttpResponse('ListGatewaysSuccess.txt');

        $this->gateway->loadGateways();

        $gatewaysTokens = $this->gateway->getGatewaysTokens();
        $lastGatewayToken = array_pop($gatewaysTokens);

        $this->assertEquals('7NTzuQfnaNU2Jr4cVgOt7jfTVGq', $lastGatewayToken['token']);
    }

    public function testTooManyRequestsError()
    {
        $this->setMockHttpResponse('TooManyRequestsError.txt');

        $this->setExpectedException(ClientErrorResponseException::class);

        $this->gateway->listGateways()->send();
    }
}
