<?php

namespace Omnipay\Spreedly\Tests\Message;

use Guzzle\Http\Message\EntityEnclosingRequest;
use Guzzle\Http\QueryString;
use Guzzle\Http\Url;
use Guzzle\Parser\ParserRegistry;
use Omnipay\Spreedly\Gateway;
use Omnipay\Tests\TestCase;

class TestCaseMessage extends TestCase
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

    /**
     * @param \Omnipay\Spreedly\Message\AbstractRequest $request
     * @return mixed
     */
    protected function setTestGateway($request)
    {
        $request->setGatewaysTokens([['type' => 'test', 'token' => '1234']]);
        $request->setGateway('test');

        return $request;
    }

    /**
     * @param string $file
     * @return bool|EntityEnclosingRequest
     */
    protected function mockHttpRequest($file)
    {
        return $this->requestFromMessage(file_get_contents(__DIR__ . '/../Mock/' . $file));
    }

    /**
     * @param string $message
     * @return bool|EntityEnclosingRequest
     */
    protected function requestFromMessage($message)
    {
        $data = ParserRegistry::getInstance()->getParser('message')->parseRequest($message);
        if (!$data) {
            return false;
        }

        $url = new Url($data['request_url']['scheme'], $data['request_url']['host'], null, null, $data['request_url']['port'], $data['request_url']['path'], QueryString::fromString($data['request_url']['query']));

        $request = new EntityEnclosingRequest($data['method'], $url, $data['headers']);
        $request->setBody($data['body']);
        $request->setScheme(strtolower($data['protocol']));
        $request->setProtocolVersion($data['version']);

        return $request;
    }
}
