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
        '0' => 'Transaction Is Successful',
        '1' => 'Bank System Reject',
        '2' => 'Bank Declined Transaction',
        '3' => 'No Reply From Bank',
        '4' => 'Expired Card',
        '5' => 'Insufficient Funds',
        '6' => 'Error Communicating With Bank',
        '7' => 'Payment Server System Error',
        '8' => 'Transaction Type Not Supported',
        '9' => 'Bank Declined Transaction (Do Not Contact Bank)',
        'A' => 'Transaction Aborted',
        'C' => 'Transaction Cancelled',
        'D' => 'Deferred Transaction Has Been Received And Is Awaiting Processing',
        'B' => '3d Secure Authentication Failed',
        'W' => '3d Secure Authentication Failed',
        'F' => '3d Secure Authentication Failed',
        'I' => 'Card Security Code Verification Failed',
        'R' => 'Reached Limit Of Retry Attempts Allowed',
        'S' => 'Duplicate SessionID (OrderInfo)',
        'T' => 'Address Verification Failed',
        'U' => 'Card Security Code Failed',
        'V' => 'Address Verification And Card Security Code Failed',
        '99' => 'User Cancel',
        'X' => 'Failed'
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
