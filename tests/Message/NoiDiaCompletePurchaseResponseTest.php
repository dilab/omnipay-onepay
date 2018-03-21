<?php

namespace Omnipay\OnePay\Message;

use Omnipay\Tests\TestCase;

class NoiDiaCompletePurchaseResponseTest extends TestCase
{
    /**
     * @var NoiDiaCompletePurchaseResponse
     */
    public $response;

    public function testIsSuccessReturnTrue()
    {
        $data = [
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

        $this->response = new NoiDiaCompletePurchaseResponse($this->getMockRequest(), $data);

        $this->assertTrue($this->response->isSuccessful());
        $this->assertFalse($this->response->isPending());
        $this->assertFalse($this->response->isRedirect());
    }

    public function testGetMessage()
    {
        $data = [
            'vpc_AdditionData' => 970436,
            'vpc_Amount' => 100,
            'vpc_Command' => 'pay',
            'vpc_CurrencyCode' => 'VND',
            'vpc_Locale' => 'vn',
            'vpc_MerchTxnRef' => '201803210919102006754784',
            'vpc_Merchant' => 'ONEPAY',
            'vpc_OrderInfo' => 'JSECURETEST01',
            'vpc_TransactionNo' => '1625746',
            'vpc_TxnResponseCode' => 3,
            'vpc_Version' => 2,
            'vpc_SecureHash' => '123',
            'computed_hash_value' => '345'
        ];

        $this->response = new NoiDiaCompletePurchaseResponse($this->getMockRequest(), $data);

        $this->assertFalse($this->response->isSuccessful());
        
        $this->assertEquals('Mã đơn vị không tồn tại - Merchant not exist',$this->response->getMessage());
    }

}
