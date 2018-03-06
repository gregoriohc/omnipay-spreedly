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
use Omnipay\Spreedly\Message\PurchaseRequest;
use Omnipay\Spreedly\Message\RefundRequest;
use Omnipay\Spreedly\Message\RetainCardRequest;
use Omnipay\Spreedly\Message\RetainPaymentMethodRequest;
use Omnipay\Spreedly\Message\UpdateCardRequest;
use Omnipay\Spreedly\Message\UpdatePaymentMethodRequest;
use Omnipay\Spreedly\Message\VoidRequest;
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

    public function testPurchase()
    {
        $request = $this->gateway->purchase();

        $this->assertInstanceOf(PurchaseRequest::class, $request);
    }

    public function testRefund()
    {
        $request = $this->gateway->refund();

        $this->assertInstanceOf(RefundRequest::class, $request);
    }

    public function testRetainCard()
    {
        $request = $this->gateway->retainCard();

        $this->assertInstanceOf(RetainCardRequest::class, $request);
    }

    public function testRetainPaymentMethod()
    {
        $request = $this->gateway->retainPaymentMethod();

        $this->assertInstanceOf(RetainPaymentMethodRequest::class, $request);
    }

    public function testUpdateCard()
    {
        $request = $this->gateway->updateCard();

        $this->assertInstanceOf(UpdateCardRequest::class, $request);
    }

    public function testUpdatePaymentMethod()
    {
        $request = $this->gateway->updatePaymentMethod();

        $this->assertInstanceOf(UpdatePaymentMethodRequest::class, $request);
    }

    public function testVoid()
    {
        $request = $this->gateway->void();

        $this->assertInstanceOf(VoidRequest::class, $request);
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
