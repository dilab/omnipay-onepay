<?php

namespace Omnipay\OnePay;

use Omnipay\Tests\GatewayTestCase;

class QuocTeGatewayTest extends GatewayTestCase
{

    /**
     * @var QuocTeGateway
     */
    protected $gateway;

    /**
     * @var array
     */
    protected $options;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new QuocTeGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = [
            'vpcMerchant' => 'TESTONEPAY',
            'vpcAccessCode' => '6BEB2546',
            'secureHash' => 'A3EFDFABA8653DF2342E8DAC29B51AF0',
            'testMode' => true,
            'vpcUser' => 'op01',
            'vpcPassword' => 'op123456',
            'returnUrl' => 'http://www.google.com/app_dev.php/backend/process_transaction.html/1431785?client_key=94bc04c3760620d537b6717abd53ff3e&action=return',
            'amount' => '1000',
            'currency' => 'VND',
            'transactionId' => '1431786'
        ];
    }

    public function testCompletePurchaseSuccess()
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

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertInstanceOf('\Omnipay\OnePay\Message\NoiDiaCompletePurchaseResponse', $response);

        $this->assertFalse($response->isRedirect());

        $this->assertTrue($response->isSuccessful());

        $this->assertSame('1625746', $response->getTransactionReference());
    }

    public function testCompletePurchaseFailure()
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
            'vpc_SecureHash' => '123'
        ]);

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertInstanceOf('\Omnipay\OnePay\Message\NoiDiaCompletePurchaseResponse', $response);

        $this->assertFalse($response->isRedirect());

        $this->assertFalse($response->isSuccessful());

        $this->assertNotSame('1431785', $response->getTransactionReference());
    }

    public function testPurchaseSuccess()
    {
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertInstanceOf('\Omnipay\OnePay\Message\QuocTePurchaseResponse', $response);

        $this->assertTrue($response->isRedirect());

        //mock data after redirect request
        $this->setMockHttpResponse('QuocTePurchaseSuccess.txt');

        $response = $this->gateway->createResponse('\Omnipay\OnePay\Message\QuocTePurchaseResponse', ['vpc_TxnResponseCode' => 0], 'purchase');

        $this->assertTrue($response->isRedirect());

        $this->assertTrue($response->isSuccessful());

        $this->assertInstanceOf('\Omnipay\OnePay\Message\QuocTePurchaseResponse', $response);

        $this->assertTrue($response->isSuccessful());;

    }

    public function testPurchaseFailure()
    {
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertInstanceOf('\Omnipay\OnePay\Message\QuocTePurchaseResponse', $response);

        $this->assertTrue($response->isRedirect());

        //mock data after redirect request
        $this->setMockHttpResponse('QuocTePurchaseFailure.txt');

        $response = $this->gateway->createResponse('\Omnipay\OnePay\Message\QuocTePurchaseResponse', ['vpc_Message' => 'Field AgainLink value is invalid.'], 'purchase');

        $this->assertFalse($response->isSuccessful());

        $this->assertSame('Field AgainLink value is invalid.', $response->getMessage());
    }

    public function testFetchSuccess()
    {
        $this->setMockHttpResponse('QuocTeFetchSuccess.txt');

        $options = [
            'vpcMerchant' => 'TESTONEPAY',
            'vpcAccessCode' => '6BEB2546',
            'testMode' => true,
            'vpcUser' => 'op01',
            'vpcPassword' => 'op123456',
            'transactionId' => 'GDEAXIEM_41382,4523317014',
        ];

        $request = $this->gateway->fetchCheckout($options);

        $this->assertInstanceOf('\Omnipay\OnePay\Message\QuocTeFetchRequest', $request);

        $this->assertSame('GDEAXIEM_41382,4523317014', $request->getVpc_MerchTxnRef());

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());

        $this->assertSame('Transaction Successful', $response->getMessage());

        $this->assertSame('1431785', $response->getTransactionReference());
    }

    public function testFetchFailure()
    {
        $this->setMockHttpResponse('QuocTeFetchFailure.txt');

        $options = [
            'vpcMerchant' => 'TESTONEPAY',
            'vpcAccessCode' => 'D67342C2',
            'testMode' => true,
            'vpcUser' => 'op01',
            'vpcPassword' => 'op123456',
            'transactionId' => 'GDEAXIEM_41382,4523317014',
        ];

        $request = $this->gateway->fetchCheckout($options);

        $this->assertInstanceOf('\Omnipay\OnePay\Message\QuocTeFetchRequest', $request);

        $this->assertSame('GDEAXIEM_41382,4523317014', $request->getVpc_MerchTxnRef());

        $response = $request->send();

        $this->assertFalse($response->isSuccessful());

        $this->assertSame('Unable to be determined', $response->getMessage());
    }


}
