<?php

namespace Omnipay\Spreedly\Message;

use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testConstruct()
    {
        // response should decode URL format data
        $response = new Response($this->getMockRequest(), array('transaction' => array('example' => 'value', 'foo' => 'bar')));
        $this->assertEquals(array('example' => 'value', 'foo' => 'bar'), $response->getData());
    }
}
