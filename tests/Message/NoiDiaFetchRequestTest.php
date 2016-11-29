<?php

namespace Omnipay\OnePay\Message;

use Omnipay\Tests\TestCase;

class NoiDiaFetchRequestTest extends TestCase
{

    /**
     * @var NoiDiaFetchRequest
     */
    private $request;

    /**
     * @var array
     */
    protected $options;


    public function setUp()
    {
        parent::setUp();

        $this->request = new NoiDiaFetchRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->options = [
            'vpcMerchant'     => 'ONEPAY',
            'vpcAccessCode'   => 'D67342C2',
            'secureHash'      => 'A3EFDFABA8653DF2342E8DAC29B51AF0',
            'vpcUser'         => 'op01',
            'vpcPassword'     => 'op123456',
            'testMode'        => true,
            'vpc_MerchTxnRef' => '2413'
        ];

        $this->request->initialize($this->options);
    }


    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('ONEPAY', $data['vpc_Merchant']);
        $this->assertSame('D67342C2', $data['vpc_AccessCode']);
        $this->assertSame('1', $data['vpc_Version']);
        $this->assertSame('queryDR', $data['vpc_Command']);
        $this->assertSame('op01', $data['vpc_User']);
        $this->assertSame('op123456', $data['vpc_Password']);
        $this->assertSame('2413', $data['vpc_MerchTxnRef']);
    }
}
