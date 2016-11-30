<?php
namespace Omnipay\OnePay\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * QuocTe Purchase Response
 */
class QuocTePurchaseResponse extends Response implements RedirectResponseInterface
{

    protected $liveEndpoint = 'https://onepay.vn/vpcpay/vpcpay.op';

    protected $testEndpoint = 'https://mtf.onepay.vn/vpcpay/vpcpay.op';

    protected $transactionStatus = [
        '0'  => 'Transaction is successful',
        '1'  => 'Bank system reject',
        '2'  => 'Bank Declined Transaction',
        '3'  => 'No Reply from Bank',
        '4'  => 'Expired Card',
        '5'  => 'Insufficient funds',
        '6'  => 'Error Communicating with Bank',
        '7'  => 'Payment Server System Error',
        '8'  => 'Transaction Type Not Supported',
        '9'  => 'Bank declined transaction (Do not contact Bank)',
        'A'  => 'Transaction Aborted',
        'C'  => 'Transaction Cancelled',
        'D'  => 'Deferred transaction has been received and is awaiting processing',
        'B'  => '3D Secure Authentication failed',
        'W'  => '3D Secure Authentication failed',
        'F'  => '3D Secure Authentication failed',
        'I'  => 'Card Security Code verification failed',
        'R'  => 'Reached limit of retry attempts allowed',
        'S'  => 'Duplicate SessionID (OrderInfo)',
        'T'  => 'Address Verification Failed',
        'U'  => 'Card Security Code Failed',
        'V'  => 'Address Verification and Card Security Code Failed',
        '99' => 'User Cancel',
        'X'  => 'Failured'
    ];


    public function isRedirect()
    {
        return true;
    }


    public function getRedirectUrl()
    {
        return $this->getCheckoutEndpoint() . '?' . http_build_query($this->data, '', '&');
    }


    public function getRedirectMethod()
    {
        return 'POST';
    }


    public function getRedirectData()
    {
        return null;
    }


    protected function getCheckoutEndpoint()
    {
        return $this->getRequest()->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
