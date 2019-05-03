<?php

namespace Omnipay\OnePay\Message;

use Omnipay\Tests\TestCase;

class QuocTeCompleteRequestTest extends TestCase
{
    /**
     * @var \Omnipay\OnePay\Message\NoiDiaPurchaseRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new QuocTeCompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->options = [
            'vpcMerchant' => 'ONEPAY',
            'vpcAccessCode' => 'D67342C2',
            'secureHash' => 'A3EFDFABA8653DF2342E8DAC29B51AF0',
            'testMode' => true,
            'vpcUser' => 'op01',
            'vpcPassword' => 'op123456',
            'returnUrl' => 'http://localhost:8123/dr.php',
            'amount' => 1.00,
            'currency' => 'VND',
            'transactionId' => 'JSECURETEST01'
        ];

        $this->request->initialize($this->options);
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
