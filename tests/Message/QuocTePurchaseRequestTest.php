<?php

namespace Omnipay\OnePay\Message;

use Omnipay\Tests\TestCase;

class QuocTePurchaseRequestTest extends TestCase
{

    /**
     * @var \Omnipay\OnePay\Message\QuocTePurchaseRequest
     */
    private $request;


    public function setUp()
    {
        $this->request = new QuocTePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->options = [
            'vpcMerchant' => 'TESTONEPAY',
            'vpcAccessCode' => '6BEB2546',
            'secureHash' => 'A3EFDFABA8653DF2342E8DAC29B51AF0',
            'testMode' => true,
            'vpcUser' => 'op01',
            'vpcPassword' => 'op123456',
            'returnUrl' => 'http://www.google.com/app_dev.php/backend/process_transaction.html/1431786?client_key=94bc04c3760620d537b6717abd53ff3e&action=return',
            'amount' => 1000.00,
            'currency' => 'VND',
            'transactionId' => '1431786'
        ];

        $this->request->initialize($this->options);
    }

    public function testGetData()
    {
        $expected = [
            'vpc_Merchant' => 'TESTONEPAY',
            'vpc_AccessCode' => '6BEB2546',
            'vpc_order_id' => '1431786',
            'Title' => 'VPC 3-Party',
            'vpc_Version' => '2',
            'vpc_Command' => 'pay',
            'virtualPaymentClientURL' => $this->testGetEndpoint(),
            'vpc_Amount' => 100000,
            'vpc_Locale' => $this->getHttpRequest()->getLocale(),
            'vpc_ReturnURL' => 'http://www.google.com/app_dev.php/backend/process_transaction.html/1431786?client_key=94bc04c3760620d537b6717abd53ff3e&action=return',
            'vpc_TicketNo' => $this->getHttpRequest()->getClientIp(),
            'AgainLink' => urlencode($this->getHttpRequest()->server->get('HTTP_REFERER'))
        ];

        $requetData = $this->request->getData();

        $this->assertSame('1431786', $requetData['vpc_MerchTxnRef']);
        $this->assertNotNull($requetData['vpc_OrderInfo']);

        // exclude by random property
        unset($requetData['vpc_OrderInfo']);
        unset($requetData['vpc_MerchTxnRef']);

        $this->assertEquals($expected, $requetData);
    }

    public function testSendData()
    {
        $this->testGetData();

        $data = $this->request->generateDataWithChecksum($this->request->getData());

        $this->assertArrayHasKey('vpc_SecureHash', $data);
    }

    public function testGetEndpoint()
    {
        $reflectionOfUser = new \ReflectionClass('\Omnipay\OnePay\Message\QuocTePurchaseRequest');

        $method = $reflectionOfUser->getMethod('getEndpoint');

        $method->setAccessible(true);

        $this->assertEquals('https://mtf.onepay.vn/vpcpay/vpcpay.op', $method->invokeArgs($this->request, []));

        return 'https://mtf.onepay.vn/vpcpay/vpcpay.op';
    }
}
