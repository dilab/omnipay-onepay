<?php

namespace Omnipay\OnePay\Message;

use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{

    public function testConstruct()
    {
        // response should decode URL format data
        $response = new Response($this->getMockRequest(), 'example=value&foo=bar');
        $this->assertEquals([ 'example' => 'value', 'foo' => 'bar' ], $response->getData());
    }


    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('NoiDiaPurchaseSuccess.txt');
        $response     = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('1431785', $response->getTransactionReference());
        $this->assertEquals('Approved', $response->getMessage());
    }


    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('NoiDiaPurchaseFailure.txt');
        $response     = new Response($this->getMockRequest(), $httpResponse->getBody());

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Field AgainLink value is invalid.', $response->getMessage());
    }
}
