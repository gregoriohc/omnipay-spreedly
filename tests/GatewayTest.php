<?php

namespace Omnipay\Spreedly\Tests;

use Omnipay\Spreedly\Arr;
use Omnipay\Spreedly\Gateway;
use Omnipay\Tests\GatewayTestCase;

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
        $this->gateway->setGatewaysTokens(array(
            array(
                'type' => 'test',
                'token' => '1234',
            ),
            array(
                'type' => 'fake',
                'token' => '1234',
            ),
            array(
                'type' => 'conekta',
                'token' => '1234',
            ),
        ));
    }

    public function testAuthorize()
    {
        $request = $this->gateway->authorize();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\AuthorizeRequest', $request);
    }

    public function testCapture()
    {
        $request = $this->gateway->capture();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\CaptureRequest', $request);
    }

    public function testCreateCard()
    {
        $request = $this->gateway->createCard();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\CreatePaymentMethodRequest', $request);
    }

    public function testCreateGateway()
    {
        $request = $this->gateway->createGateway();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\CreateGatewayRequest', $request);
    }

    public function testCreatePaymentMethod()
    {
        $request = $this->gateway->createPaymentMethod();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\CreatePaymentMethodRequest', $request);
    }

    public function testDeleteCard()
    {
        $request = $this->gateway->deleteCard();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\DeletePaymentMethodRequest', $request);
    }

    public function testDeletePaymentMethod()
    {
        $request = $this->gateway->deletePaymentMethod();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\DeletePaymentMethodRequest', $request);
    }

    public function testFetchCard()
    {
        $request = $this->gateway->fetchCard();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\FetchPaymentMethodRequest', $request);
    }

    public function testFetchPaymentMethod()
    {
        $request = $this->gateway->fetchPaymentMethod();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\FetchPaymentMethodRequest', $request);
    }

    public function testListCards()
    {
        $request = $this->gateway->listCards();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\ListPaymentMethodsRequest', $request);
    }

    public function testListGateways()
    {
        $request = $this->gateway->listGateways();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\ListGatewaysRequest', $request);
    }

    public function testListPaymentMethods()
    {
        $request = $this->gateway->listPaymentMethods();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\ListPaymentMethodsRequest', $request);
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\PurchaseRequest', $request);
    }

    public function testRefund()
    {
        $request = $this->gateway->refund();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\RefundRequest', $request);
    }

    public function testRetainCard()
    {
        $request = $this->gateway->retainCard();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\RetainPaymentMethodRequest', $request);
    }

    public function testRetainPaymentMethod()
    {
        $request = $this->gateway->retainPaymentMethod();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\RetainPaymentMethodRequest', $request);
    }

    public function testUpdateCard()
    {
        $request = $this->gateway->updateCard();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\UpdatePaymentMethodRequest', $request);
    }

    public function testUpdatePaymentMethod()
    {
        $request = $this->gateway->updatePaymentMethod();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\UpdatePaymentMethodRequest', $request);
    }

    public function testVoid()
    {
        $request = $this->gateway->void();

        $this->assertInstanceOf('Omnipay\Spreedly\Message\VoidRequest', $request);
    }

    public function testAddGateway()
    {
        $this->setMockHttpResponse('CreateGatewaySuccess.txt');

        $gatewayToken = $this->gateway->addGateway(array(
            'type' => 'test',
            'config' => array(),
        ));

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

        $this->setExpectedException('Guzzle\Http\Exception\ClientErrorResponseException');

        $this->gateway->listGateways()->send();
    }
}
