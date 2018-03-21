<?php

namespace Omnipay\OnePay\Message;

use Omnipay\Tests\TestCase;

class NoiDiaPurchaseCompleteRequestTest extends TestCase
{
    /**
     * @var \Omnipay\OnePay\Message\NoiDiaPurchaseRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new NoiDiaCompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

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

    public function testGetData()
    {
        $this->getHttpRequest()->request->replace([
            'vpc_AdditionData' => 970436,
            'vpc_Amount' => 100,
            'vpc_Command' => 'pay',
            'vpc_CurrencyCode' => 'VND',
            'vpc_Locale' => 'vn',
            'vpc_MerchTxnRef' => '201803210919102006754784',
            'vpc_Merchant' => 'ONEPAY',
            'vpc_OrderInfo' => 'JSECURETEST01',
            'vpc_TransactionNo' => '1625746',
            'vpc_TxnResponseCode' => 0,
            'vpc_Version' => 2,
            'vpc_SecureHash' => '0331F9D8E0CD9A6BC581B74721658DFD9A5A219145F92DED700C13E4843BB3B0'
        ]);

        $this->request = new NoiDiaCompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

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

        $data = $this->request->getData();

        $expected = [
            'vpc_AdditionData' => 970436,
            'vpc_Amount' => 100,
            'vpc_Command' => 'pay',
            'vpc_CurrencyCode' => 'VND',
            'vpc_Locale' => 'vn',
            'vpc_MerchTxnRef' => '201803210919102006754784',
            'vpc_Merchant' => 'ONEPAY',
            'vpc_OrderInfo' => 'JSECURETEST01',
            'vpc_TransactionNo' => '1625746',
            'vpc_TxnResponseCode' => 0,
            'vpc_Version' => 2,
            'vpc_SecureHash' => '0331F9D8E0CD9A6BC581B74721658DFD9A5A219145F92DED700C13E4843BB3B0',
            'computed_hash_value' => '0331f9d8e0cd9a6bc581b74721658dfd9a5a219145f92ded700c13e4843bb3b0'
        ];

        $this->assertEquals($expected, $data);
    }

    public function testSendData()
    {
        $this->assertInstanceOf(NoiDiaCompletePurchaseResponse::class, $this->request->sendData([
            'vpc_AdditionData' => 970436,
            'vpc_Amount' => 100,
            'vpc_Command' => 'pay',
            'vpc_CurrencyCode' => 'VND',
            'vpc_Locale' => 'vn',
            'vpc_MerchTxnRef' => '201803210919102006754784',
            'vpc_Merchant' => 'ONEPAY',
            'vpc_OrderInfo' => 'JSECURETEST01',
            'vpc_TransactionNo' => '1625746',
            'vpc_TxnResponseCode' => 0,
            'vpc_Version' => 2,
            'vpc_SecureHash' => '0331F9D8E0CD9A6BC581B74721658DFD9A5A219145F92DED700C13E4843BB3B0',
            'computed_hash_value' => '0331f9d8e0cd9a6bc581b74721658dfd9a5a219145f92ded700c13e4843bb3b0'
        ]));
    }

}
