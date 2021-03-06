<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Spreedly\BankAccount;
use Omnipay\Spreedly\Message\CreatePaymentMethodRequest;

class CreatePaymentMethodRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('CreatePaymentMethodRequest.txt');

        $request = new CreatePaymentMethodRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array(
            'bank_account' => array(
                'first_name' => 'Jon',
                'last_name' => 'Doe',
                'routing_number' => '021000021',
                'number' => '9876543210',
                'type' => BankAccount::TYPE_CHECKING,
                'holder_type' => BankAccount::HOLDER_TYPE_PERSONAL,
            ),
            'email' => 'jon.doe@example.com',
            'extra' => array(
                'my_payment_method_identifier' => 448,
                'extra_stuff' => array(
                    'some_other_things' => 'Can be anything really',
                )
            ),
        ));

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());


        $mockRequest = $this->mockHttpRequest('CreateCardRequest.txt');

        $request = new CreatePaymentMethodRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array(
            'card' => new CreditCard(array(
                'firstName' => 'Joe',
                'lastName' => 'Jones',
                'number' => '5555555555554444',
                'cvv' => '423',
                'expiryMonth' => '3',
                'expiryYear' => '2032',
            )),
            'email' => 'joey@example.com',
        ));

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
    {
        $this->setMockHttpResponse('CreatePaymentMethodBankAccountSuccess.txt');

        $response = $this->gateway->createPaymentMethod(array(
            'bank_account' => array(
                'first_name' => 'Jon',
                'last_name' => 'Doe',
                'number' => '9876543210',
                'routing_number' => '021000021',
                'type' => BankAccount::TYPE_CHECKING,
                'holder_type' => BankAccount::HOLDER_TYPE_PERSONAL,
            ),
            'email' => 'user@example.com',
            'retained' => false,
            'allow_blank_name' => false,
            'allow_expired_date' => false,
            'allow_blank_date' => false,
        ))->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('7cndznvjrSZ8BF7EmgHQVN3TRKL', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
        $this->assertEquals('QzbtsFbvQdzHbJDuDUJ9itBr7jP', $response->getPaymentMethodToken());
        $this->assertEquals('bank_account', $response->getPaymentMethodType());


        $this->setMockHttpResponse('CreateCardSuccess.txt');

        $response = $this->gateway->createCard(array(
            'card' => new CreditCard(array(
                'firstName' => 'Example',
                'lastName' => 'User',
                'number' => '4111111111111111',
                'expiryMonth' => '12',
                'expiryYear' => '2020',
                'cvv' => '123',
            )),
            'email' => 'user@example.com',
            'retained' => false,
            'allow_blank_name' => false,
            'allow_expired_date' => false,
            'allow_blank_date' => false,
        ))->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('MsTBXc7aXHVnnTeIJX2LfgtfPqh', $response->getTransactionReference());
        $this->assertEquals('succeeded', $response->getCode());
    }
}
