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
        '0'  => 'Giao dịch thành công - Transaction is successful',
        '1'  => 'Ngân hàng phát hành thẻ không cấp phép. Vui lòng liên hệ ngân hàng - Bank system reject',
        '2'  => 'Ngân hàng phát hành thẻ không cấp phép. Vui lòng liên hệ ngân hàng - Bank Declined Transaction',
        '3'  => 'Cổng thanh toán không nhận được kết quả trả về từ ngân hàng phát hành thẻ - No Reply from Bank',
        '4'  => 'Thẻ hết hạn sử dụng - Expired Card',
        '5'  => 'Thẻ không đủ hạn mức hoặc tài khoản không đủ số dư thanh toán. - Insufficient funds',
        '6'  => 'Lỗi từ ngân hàng phát hành thẻ. - Error Communicating with Bank',
        '7'  => 'Lỗi phát sinh trong quá trình xử lý giao dịch - Payment Server System Error',
        '8'  => 'Ngân hàng phát hành thẻ không hỗ trợ giao dịch Internet - Transaction Type Not Supported',
        '9'  => 'Ngân hàng phát hành thẻ từ chối giao dịch - Bank declined transaction (Do not contact Bank)',
        'A'  => 'Thẻ hết hạn/Thẻ bị khóa - Transaction Aborted',
        'C'  => 'Thẻ chưa đăng ký sử dụng dịch vụ - Transaction Cancelled',
        'D'  => 'Ngày phát hành/Hết hạn không đúng - Deferred transaction has been received and is awaiting processing',
        'B'  => 'Không xác thực được 3D - 3D Secure Authentication failed',
        'W'  => 'Không xác thực được 3D - 3D Secure Authentication failed',
        'F'  => 'Không xác thực được 3D - 3D Secure Authentication failed',
        'I'  => 'Khong - Card Security Code verification failed',
        'R'  => 'Giao dịch quá số lần cho phép - Reached limit of retry attempts allowed',
        'S'  => 'Duplicate SessionID (OrderInfo)',
        'T'  => 'Address Verification Failed',
        'U'  => 'Card Security Code Failed',
        'V'  => 'Address Verification and Card Security Code Failed',
        '99' => 'Người dùng hủy giao dịch - User Cancel',
        'X'  => 'Giao dịch thất bại - Failured'
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
