<?php

namespace Omnipay\OnePay\Message;

use Omnipay\Tests\TestCase;

class NoiDiaPurchaseResponseTest extends TestCase
{

    protected $response;


    public function testConstruct()
    {
        $expected = [
            'vpc_Merchant'    => 'ONEPAY',
            'vpc_AccessCode'  => 'D67342C2',
            'vpc_order_id'    => '1431785',
            'Title'           => 'VPC 3-Party',
            'vpc_Version'     => '2',
            'vpc_Command'     => 'pay',
            'vpc_MerchTxnRef' => '33333333333333333',
            'vpc_OrderInfo'   => "Order_1431785__11111",
            'vpc_Amount'      => '1000',
            'vpc_Locale'      => 'vn',
            'vpc_ReturnURL'   => 'http://truonghoang.cool/app_dev.php/backend/process_transaction.html/1431785?client_key=94bc04c3760620d537b6717abd53ff3e&action=return',
            'vpc_TicketNo'    => '192.168.0.1',
            'vpc_Currency'    => 'VND',
            'vpc_SecureHash'  => '44444444444444444'
        ];

        // response should decode URL format data
        $this->response = new NoiDiaPurchaseResponse($this->getMockRequest(), http_build_query($expected));

        $this->assertEquals($expected, $this->response->getData());
    }

}
