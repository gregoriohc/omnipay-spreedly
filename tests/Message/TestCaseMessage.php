<?php

namespace Omnipay\Spreedly\Message;

use Guzzle\Http\Message\EntityEnclosingRequest;
use Guzzle\Http\QueryString;
use Guzzle\Http\Url;
use Guzzle\Parser\ParserRegistry;
use Omnipay\Spreedly\GatewayToken;
use Omnipay\Tests\TestCase;

class TestCaseMessage extends TestCase
{
    /**
     * @var AbstractRequest
     */
    protected $request;

    protected function setTestGateway($request)
    {
        $this->request->setGatewaysTokens(['test' => new GatewayToken(['type' => 'test', 'token' => '1234'])]);
        $this->request->setGateway('test');

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
        $request->setScheme($data['protocol']);
        $request->setProtocolVersion($data['version']);

        return $request;
    }
}
