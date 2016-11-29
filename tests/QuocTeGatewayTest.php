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
            'vpcMerchant'   => 'TESTONEPAY',
            'vpcAccessCode' => '6BEB2546',
            'secureHash'    => 'A3EFDFABA8653DF2342E8DAC29B51AF0',
            'testMode'      => true,
            'vpcUser'       => 'op01',
            'vpcPassword'   => 'op123456',
            'returnUrl'     => 'http://truonghoang.cool/app_dev.php/backend/process_transaction.html/1431785?client_key=94bc04c3760620d537b6717abd53ff3e&action=return',
            'amount'        => '1000',
            'currency'      => 'VND',
            'transactionId' => '1431786'
        ];
    }


    public function testPurchaseSuccess()
    {
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertInstanceOf('\Omnipay\OnePay\Message\QuocTePurchaseResponse', $response);

//        $this->assertEquals('https://mtf.onepay.vn/vpcpay/vpcpay.op?' . http_build_query($this->options, '', '&'), $response->getRedirectUrl());

        $this->assertTrue($response->isRedirect());

        //mock data after redirect request
        $this->setMockHttpResponse('QuocTePurchaseSuccess.txt');

        $response = $this->gateway->createResponse('\Omnipay\OnePay\Message\QuocTePurchaseResponse',
            [ 'vpc_TxnResponseCode' => 0 ], 'purchase');

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

        $response = $this->gateway->createResponse('\Omnipay\OnePay\Message\QuocTePurchaseResponse',
            [ 'vpc_Message' => 'Field AgainLink value is invalid.' ], 'purchase');

        $this->assertFalse($response->isSuccessful());

        $this->assertSame('Field AgainLink value is invalid.', $response->getMessage());
    }


    public function testFetchSuccess()
    {
        $this->setMockHttpResponse('QuocTeFetchSuccess.txt');

        $options = [
            'vpcMerchant'     => 'TESTONEPAY',
            'vpcAccessCode'   => '6BEB2546',
            'testMode'        => true,
            'vpcUser'         => 'op01',
            'vpcPassword'     => 'op123456',
            'vpc_MerchTxnRef' => 'GDEAXIEM_41382,4523317014',
        ];

        $request = $this->gateway->fetchCheckout($options);

        $this->assertInstanceOf('\Omnipay\OnePay\Message\QuocTeFetchRequest', $request);
        $this->assertSame('GDEAXIEM_41382,4523317014', $request->getVpc_MerchTxnRef());

        $response = $request->send();
        $this->assertTrue($response->isSuccessful());

        $this->assertSame('Transaction Successful', $response->getMessage());
    }


    public function testFetchFailure()
    {
        $this->setMockHttpResponse('QuocTeFetchFailure.txt');

        $options = [
            'vpcMerchant'     => 'TESTONEPAY',
            'vpcAccessCode'   => 'D67342C2',
            'testMode'        => true,
            'vpcUser'         => 'op01',
            'vpcPassword'     => 'op123456',
            'vpc_MerchTxnRef' => 'GDEAXIEM_41382,4523317014',
        ];

        $request = $this->gateway->fetchCheckout($options);

        $this->assertInstanceOf('\Omnipay\OnePay\Message\QuocTeFetchRequest', $request);
        $this->assertSame('GDEAXIEM_41382,4523317014', $request->getVpc_MerchTxnRef());

        $response = $request->send();
        $this->assertFalse($response->isSuccessful());

        $this->assertSame('Unable to be determined', $response->getMessage());
    }
}
