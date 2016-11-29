<?php

namespace Omnipay\OnePay\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class QuocTeFetchRequestTest extends TestCase
{

    /**
     * @var QuocTeFetchRequest
     */
    private $request;

    /**
     * @var array
     */
    protected $options;


    public function setUp()
    {
        parent::setUp();

        $this->request = new QuocTeFetchRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->options = [
            'vpcMerchant'     => 'TESTONEPAY',
            'vpcAccessCode'   => '6BEB2546',
            'testMode'        => true,
            'vpcUser'         => 'op01',
            'vpcPassword'     => 'op123456',
            'transactionId' => 'GDEAXIEM_41382,4523317014'
        ];

        $this->request->initialize($this->options);
    }


    public function testGetData()
    {
        $this->request->initialize($this->options);

        $data = $this->request->getData();

        $this->assertSame('TESTONEPAY', $data['vpc_Merchant']);
        $this->assertSame('6BEB2546', $data['vpc_AccessCode']);
        $this->assertSame('1', $data['vpc_Version']);
        $this->assertSame('queryDR', $data['vpc_Command']);
        $this->assertSame('op01', $data['vpc_User']);
        $this->assertSame('op123456', $data['vpc_Password']);
        $this->assertSame('GDEAXIEM_41382,4523317014', $data['vpc_MerchTxnRef']);
    }
}
