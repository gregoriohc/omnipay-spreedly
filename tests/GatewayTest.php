<?php

namespace Omnipay\Spreedly\Tests;

use Guzzle\Http\Exception\ClientErrorResponseException;
use Omnipay\Spreedly\BankAccount;
use Omnipay\Spreedly\Gateway;
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
            'transactionReference' => '1234',
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
            'transactionReference' => '1234',
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

    public function testFetchCard()
    {
        $this->setMockHttpResponse('FetchCardSuccess.txt');

        $response = $this->gateway->fetchCard([
            'payment_method_token' => '1rpKvP8zOUhj4Y9EDrIoIYQzzD5',
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('1rpKvP8zOUhj4Y9EDrIoIYQzzD5', $response->getTransactionReference());
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

    public function testListCards()
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

    public function testCreatePaymentMethod()
    {
        $this->setMockHttpResponse('CreatePaymentMethodBankAccountSuccess.txt');

        $response = $this->gateway->createPaymentMethod([
            'bank_account' => [
                'first_name' => 'Jon',
                'last_name' => 'Doe',
                'number' => '9876543210',
                'routing_number' => '021000021',
                'type' => BankAccount::TYPE_CHECKING,
                'holder_type' => BankAccount::HOLDER_TYPE_PERSONAL,
            ],
            'email' => 'user@example.com',
            'retained' => false,
            'allow_blank_name' => false,
            'allow_expired_date' => false,
            'allow_blank_date' => false,
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('7cndznvjrSZ8BF7EmgHQVN3TRKL', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('QzbtsFbvQdzHbJDuDUJ9itBr7jP', $response->getPaymentMethodToken());
        $this->assertEquals('bank_account', $response->getPaymentMethodType());
    }

    public function testDeletePaymentMethod()
    {
        $this->setMockHttpResponse('DeletePaymentMethodSuccess.txt');

        $response = $this->gateway->deletePaymentMethod([
            'payment_method_token' => 'FT6P5qwEI1MArhD8nydJpnHP1uV',
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('XN5Lm2COxcqP7xFKaZIWDI0CVuh', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('FT6P5qwEI1MArhD8nydJpnHP1uV', $response->getPaymentMethodToken());
    }

    public function testFetchPaymentMethod()
    {
        $this->setMockHttpResponse('FetchPaymentMethodSuccess.txt');

        $response = $this->gateway->fetchPaymentMethod([
            'payment_method_token' => '1rpKvP8zOUhj4Y9EDrIoIYQzzD5',
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('1rpKvP8zOUhj4Y9EDrIoIYQzzD5', $response->getTransactionReference());
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

    public function testListPaymentMethods()
    {
        $this->setMockHttpResponse('ListPaymentMethodsSuccess.txt');

        /** @var \Omnipay\Spreedly\Message\Response $response */
        $response = $this->gateway->listPaymentMethods([
            'order' => 'asc',
        ])->send();

        $this->assertCount(2, $response->getData());

        $this->setMockHttpResponse('ListPaymentMethodsPage2Success.txt');

        /** @var \Omnipay\Spreedly\Message\Response $response */
        $response = $this->gateway->listGateways([
            'order' => 'asc',
            'since_token' => $response->getSinceToken(),
        ])->send();

        $this->assertCount(0, $response->getData());
        $this->assertNull($response->getSinceToken());
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

    public function testCreateGateway()
    {
        $this->setMockHttpResponse('CreateGatewaySuccess.txt');

        $response = $this->gateway->createGateway([
            'type' => 'test',
            'config' => [],
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('6DqX57I6fHgIuUkVN2HGszjDSu1', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testAddGateway()
    {
        $this->setMockHttpResponse('CreateGatewaySuccess.txt');

        $gatewayToken = $this->gateway->addGateway([
            'type' => 'test',
            'config' => [],
        ]);

        $this->assertEquals('test', $gatewayToken->getType());
        $this->assertEquals('6DqX57I6fHgIuUkVN2HGszjDSu1', $gatewayToken->getToken());
    }

    public function testListGateways()
    {
        $this->setMockHttpResponse('ListGatewaysSuccess.txt');

        /** @var \Omnipay\Spreedly\Message\Response $response */
        $response = $this->gateway->listGateways([
            'order' => 'asc',
        ])->send();

        $this->assertCount(2, $response->getData());

        $this->setMockHttpResponse('ListGatewaysPage2Success.txt');

        /** @var \Omnipay\Spreedly\Message\Response $response */
        $response = $this->gateway->listGateways([
            'order' => 'asc',
            'since_token' => $response->getSinceToken(),
        ])->send();

        $this->assertCount(0, $response->getData());
        $this->assertNull($response->getSinceToken());
    }

    public function testLoadGateways()
    {
        $this->setMockHttpResponse('ListGatewaysSuccess.txt');

        $this->gateway->loadGateways();

        $this->assertEquals('7NTzuQfnaNU2Jr4cVgOt7jfTVGq', $this->gateway->getGatewaysTokens()['test']->getToken());
    }

    public function testTooManyRequestsError()
    {
        $this->setMockHttpResponse('TooManyRequestsError.txt');

        $this->setExpectedException(ClientErrorResponseException::class);

        $this->gateway->listGateways()->send();
    }
}
