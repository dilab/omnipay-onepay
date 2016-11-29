<?php

namespace Omnipay\OnePay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Response
 */
class Response extends AbstractResponse
{

    protected $transactionStatus = [
        '0'  => 'Giao dịch thành công - Approved',
        '1'  => 'Ngân hàng từ chối giao dịch - Bank Declined',
        '3'  => 'Mã đơn vị không tồn tại - Merchant not exist',
        '4'  => 'Không đúng access code - Invalid access code',
        '5'  => 'Số tiền không hợp lệ - Invalid amount',
        '6'  => 'Mã tiền tệ không tồn tại - Invalid currency code',
        '7'  => 'Lỗi không xác định - Unspecified Failure',
        '8'  => 'Số thẻ không đúng - Invalid card Number',
        '9'  => 'Tên chủ thẻ không đúng - Invalid card name',
        '10' => 'Thẻ hết hạn/Thẻ bị khóa - Expired Card',
        '11' => 'Thẻ chưa đăng ký sử dụng dịch vụ - Card Not Registed Service(internet banking)',
        '12' => 'Ngày phát hành/Hết hạn không đúng - Invalid card date',
        '13' => 'Vượt quá hạn mức thanh toán - Exist Amount',
        '21' => 'Số tiền không đủ để thanh toán - Insufficient fund',
        '99' => 'Người sủ dụng hủy giao dịch - User cancel',
        'X'  => 'Giao dịch thất bại - Failured'
    ];


    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        if ( ! is_array($data)) {
            parse_str($data, $this->data);
        } else {
            $this->data = $data;
        }
    }


    public function isSuccessful()
    {
        if (isset( $this->data['vpc_TxnResponseCode'] ) && $this->data['vpc_TxnResponseCode'] == '0') {
            $result = true;
        } elseif (isset( $this->data['vpc_ResponseCode'] ) && $this->data['vpc_ResponseCode'] == '0') {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }


    /**
     * To capture , refund , ...
     *
     * @return mixed
     */
    public function getTransactionReference()
    {
        foreach ([ 'vpc_MerchTxnRef', 'vpc_TransactionNo' ] as $key) {
            if (isset( $this->data[$key] )) {
                return $this->data[$key];
            }
        }
    }


    /**
     * @return string
     */
    public function getMessage()
    {
        if (isset( $this->data['vpc_TxnResponseCode'] )) {
            return $this->getResponseDescription($this->data['vpc_TxnResponseCode']);
        } elseif (isset( $this->data['vpc_ResponseCode'] )) {
            return $this->getResponseDescription($this->data['vpc_ResponseCode']);
        } else {
            return $this->data['vpc_Message'];
        }
    }


    protected function getResponseDescription($responseCode)
    {
        if (array_key_exists($responseCode, $this->transactionStatus)) {
            return $this->transactionStatus[$responseCode];
        }

        return $this->transactionStatus['X'];
    }
}
