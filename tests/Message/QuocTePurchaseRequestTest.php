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
        $this->request->setTransactionId($orderId = '12345');

        $expected = [
            'vpc_Merchant' => 'TESTONEPAY',
            'vpc_AccessCode' => '6BEB2546',
            'vpc_order_id' => $orderId,
            'Title' => 'VPC 3-Party',
            'vpc_Version' => '2',
            'vpc_Command' => 'pay',
            'vpc_MerchTxnRef' => $orderId,
            'vpc_OrderInfo' => $orderId,
            'virtualPaymentClientURL' => 'https://mtf.onepay.vn/vpcpay/vpcpay.op',
            'vpc_Amount' => 100000,
            'vpc_Locale' => $this->getHttpRequest()->getLocale(),
            'vpc_ReturnURL' => 'http://www.google.com/app_dev.php/backend/process_transaction.html/1431786?client_key=94bc04c3760620d537b6717abd53ff3e&action=return',
            'vpc_TicketNo' => $this->getHttpRequest()->getClientIp(),
            'AgainLink' => urlencode($this->getHttpRequest()->server->get('HTTP_REFERER'))
        ];

        $data = $this->request->getData();
        $this->assertEquals($expected, $data);

        // test promotion
        $this->request->setVpcPromotionList($vpcPromotionList = 'A57A96B7390309AD9FC02D4824C43B56');
        $this->request->setVpcPromotionAmountList($vpcPromotionAmountList = '80000000');
        $expected['vpc_Promotion_List'] = $vpcPromotionList;
        $expected['vpc_Promotion_Amount_List'] = $vpcPromotionAmountList;
        $expected['virtualPaymentClientURL'] = 'https://mtf.onepay.vn/promotion/vpcpr.op';
        $data = $this->request->getData();
        $this->assertEquals($expected, $data);
    }

    public function testSendData()
    {
        $this->testGetData();

        $data = $this->request->generateDataWithChecksum($this->request->getData());

        $this->assertArrayHasKey('vpc_SecureHash', $data);
    }

    public function testGetEndpoint()
    {
        $this->request->setTestMode(true);
        $this->assertEquals('https://mtf.onepay.vn/vpcpay/vpcpay.op', $this->request->getEndpoint());

        $this->request->setTestMode(false);
        $this->assertEquals('https://onepay.vn/vpcpay/vpcpay.op', $this->request->getEndpoint());

        $this->request->setTestMode(true);
        $this->request->setVpcPromotionList('12121112');
        $this->assertEquals('https://mtf.onepay.vn/promotion/vpcpr.op', $this->request->getEndpoint());

        $this->request->setTestMode(false);
        $this->request->setVpcPromotionList('12121112');
        $this->assertEquals('https://onepay.vn/promotion/vpcpr.op', $this->request->getEndpoint());
    }
}
