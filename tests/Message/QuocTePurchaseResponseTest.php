<?php

namespace Omnipay\OnePay\Message;

use Omnipay\Tests\TestCase;

class QuocTePurchaseResponseTest extends TestCase
{

    protected $response;


    public function testConstruct()
    {
        $expected = [
            'vpc_Merchant' => 'TESTONEPAY',
            'vpc_AccessCode' => '6BEB2546',
            'vpc_order_id' => '1431786',
            'Title' => 'VPC 3-Party',
            'vpc_Version' => '2',
            'vpc_Command' => 'pay',
            'vpc_MerchTxnRef' => '3333333333333333344444',
            'vpc_OrderInfo' => "Order_1431785_22222",
            'vpc_Amount' => '1000',
            'vpc_Locale' => 'vn',
            'vpc_ReturnURL' => 'http://www.google.com/app_dev.php/backend/process_transaction.html/1431786?client_key=94bc04c3760620d537b6717abd53ff3e&action=return',
            'vpc_TicketNo' => '192.168.0.2',
            'vpc_SecureHash' => '44444444444444444'
        ];

        // response should decode URL format data
        $this->response = new NoiDiaPurchaseResponse($this->getMockRequest(), http_build_query($expected));

        $this->assertEquals($expected, $this->response->getData());
        $this->assertFalse($this->response->isSuccessful());
        $this->assertTrue($this->response->isRedirect());
        $this->assertNull($this->response->getRedirectData());
    }
}
