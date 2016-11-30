<?php

namespace Omnipay\OnePay;

use Omnipay\Tests\GatewayTestCase;

class NoiDiaGatewayTest extends GatewayTestCase
{

    /**
     * @var NoiDiaGateway
     */
    protected $gateway;

    /**
     * @var array
     */
    protected $options;


    public function setUp()
    {
        parent::setUp();

        $this->gateway = new NoiDiaGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = [
            'vpcMerchant' => 'ONEPAY',
            'vpcAccessCode' => 'D67342C2',
            'secureHash' => 'A3EFDFABA8653DF2342E8DAC29B51AF0',
            'testMode' => true,
            'vpcUser' => 'op01',
            'vpcPassword' => 'op123456',
            'returnUrl' => 'http://truonghoang.cool/app_dev.php/backend/process_transaction.html/1431785?client_key=94bc04c3760620d537b6717abd53ff3e&action=return',
            'amount' => '1000',
            'currency' => 'VND',
            'transactionId' => '1431785'
        ];
    }


    public function testPurchaseSuccess()
    {
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertInstanceOf('\Omnipay\OnePay\Message\NoiDiaPurchaseResponse', $response);

        $this->assertTrue($response->isRedirect());

        //mock data after redirect request
        $this->setMockHttpResponse('NoiDiaPurchaseSuccess.txt');

        // process return data
        $response = $this->gateway->createResponse('\Omnipay\OnePay\Message\Response', ['vpc_TxnResponseCode' => 0], 'purchase');

        $this->assertTrue($response->isSuccessful());

        // send to complete
        $this->testFetchSuccess();
    }


    public function testPurchaseFailure()
    {
        $response = $this->gateway->purchase($this->options)->send();

        $this->assertInstanceOf('\Omnipay\OnePay\Message\NoiDiaPurchaseResponse', $response);

        $this->assertTrue($response->isRedirect());

        //mock data after redirect request
        $this->setMockHttpResponse('NoiDiaPurchaseFailure.txt');

        $response = $this->gateway->createResponse('\Omnipay\OnePay\Message\Response', ['vpc_Message' => 'Field AgainLink value is invalid.'], 'purchase');

        $this->assertFalse($response->isSuccessful());

        $this->assertSame('Field AgainLink value is invalid.', $response->getMessage());
    }


    public function testFetchSuccess()
    {
        $this->setMockHttpResponse('NoiDiaFetchSuccess.txt');

        $options = [
            'vpcMerchant' => 'ONEPAY',
            'vpcAccessCode' => 'D67342C2',
            'secureHash' => 'A3EFDFABA8653DF2342E8DAC29B51AF0',
            'vpcUser' => 'op01',
            'vpcPassword' => 'op123456',
            'testMode' => true,
            'transactionId' => '2413'
        ];

        $request = $this->gateway->fetchCheckout($options);

        $this->assertInstanceOf('\Omnipay\OnePay\Message\NoiDiaFetchRequest', $request);
        {
            $this->setMockHttpResponse('NoiDiaFetchSuccess.txt');

            $options = [
                'vpcMerchant' => 'ONEPAY',
                'vpcAccessCode' => 'D67342C2',
                'secureHash' => 'A3EFDFABA8653DF2342E8DAC29B51AF0',
                'vpcUser' => 'op01',
                'vpcPassword' => 'op123456',
                'testMode' => true,
                'transactionId' => '2413'
            ];

            $request = $this->gateway->fetchCheckout($options);

            $this->assertInstanceOf('\Omnipay\OnePay\Message\NoiDiaFetchRequest', $request);

            $this->assertSame('2413', $request->getVpc_MerchTxnRef());

            $response = $request->send();

            $this->assertTrue($response->isSuccessful());

            $this->assertSame('Approved', $response->getMessage());

            $this->assertSame('1431785', $response->getTransactionReference());

            return $response;
        }

        $this->assertSame('2413', $request->getVpc_MerchTxnRef());

        $response = $request->send();

        $this->assertTrue($response->isSuccessful());

        $this->assertSame('Approved', $response->getMessage());

        $this->assertSame('1431785', $response->getTransactionReference());

        return $response;
    }


    public function testFetchFailure()
    {
        $this->setMockHttpResponse('NoiDiaFetchFailure.txt');

        $options = [
            'vpcMerchant' => 'ONEPAY',
            'vpcAccessCode' => 'D67342C2',
            'secureHash' => 'A3EFDFABA8653DF2342E8DAC29B51AF0',
            'vpcUser' => 'op01',
            'vpcPassword' => 'op123456',
            'testMode' => true,
            'transactionId' => '2013042215193440019'
        ];

        $request = $this->gateway->fetchCheckout($options);

        $this->assertInstanceOf('\Omnipay\OnePay\Message\NoiDiaFetchRequest', $request);

        $this->assertSame('2013042215193440019', $request->getVpc_MerchTxnRef());

        $response = $request->send();

        $this->assertFalse($response->isSuccessful());

        $this->assertSame('Failured', $response->getMessage());

        return $response;
    }
}
