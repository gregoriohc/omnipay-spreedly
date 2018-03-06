<?php

namespace Omnipay\Spreedly\Tests\Message;

use Omnipay\Spreedly\BankAccount;
use Omnipay\Spreedly\Message\CreatePaymentMethodRequest;

class CreatePaymentMethodRequestTest extends TestCaseMessage
{
    public function testRequestGetData()
    {
        $mockRequest = $this->mockHttpRequest('CreatePaymentMethodRequest.txt');

        $request = new CreatePaymentMethodRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'bank_account' => [
                'first_name' => 'Jon',
                'last_name' => 'Doe',
                'routing_number' => '021000021',
                'number' => '9876543210',
                'type' => BankAccount::TYPE_CHECKING,
                'holder_type' => BankAccount::HOLDER_TYPE_PERSONAL,
            ],
            'email' => 'jon.doe@example.com',
            'extra' => [
                'my_payment_method_identifier' => 448,
                'extra_stuff' => [
                    'some_other_things' => 'Can be anything really',
                ]
            ],
        ]);

        $this->assertArrayAssocSame($request->getData(), json_decode($mockRequest->getBody(), true));
        $this->assertContains($request->getEndpoint(), $mockRequest->getUrl());
    }

    public function testRequestResponse()
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
}
