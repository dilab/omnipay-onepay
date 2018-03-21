<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 20/3/18
 * Time: 12:10 PM
 */

namespace Omnipay\OnePay\Message;


use Omnipay\Common\Message\AbstractResponse;

class NoiDiaCompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return strtoupper($this->data['vpc_SecureHash']) ==
            strtoupper($this->data['computed_hash_value']);
    }

    public function getMessage()
    {
        switch ($this->data['vpc_TxnResponseCode']) {
            case "0" :
                return "Giao dịch thành công - Approved";
                break;
            case "1" :
                return "Ngân hàng từ chối giao dịch - Bank Declined";
                break;
            case "3" :
                return "Mã đơn vị không tồn tại - Merchant not exist";
                break;
            case "4" :
                return "Không đúng access code - Invalid access code";
                break;
            case "5" :
                return "Số tiền không hợp lệ - Invalid amount";
                break;
            case "6" :
                return "Mã tiền tệ không tồn tại - Invalid currency code";
                break;
            case "7" :
                return "Lỗi không xác định - Unspecified Failure ";
                break;
            case "8" :
                return "Số thẻ không đúng - Invalid card Number";
                break;
            case "9" :
                return "Tên chủ thẻ không đúng - Invalid card name";
                break;
            case "10" :
                return "Thẻ hết hạn/Thẻ bị khóa - Expired Card";
                break;
            case "11" :
                return "Thẻ chưa đăng ký sử dụng dịch vụ - Card Not Registed Service(internet banking)";
                break;
            case "12" :
                return "Ngày phát hành/Hết hạn không đúng - Invalid card date";
                break;
            case "13" :
                return "Vượt quá hạn mức thanh toán - Exist Amount";
                break;
            case "21" :
                return "Số tiền không đủ để thanh toán - Insufficient fund";
                break;
            case "99" :
                return "Người sủ dụng hủy giao dịch - User cancel";
                break;
            default :
                return "Giao dịch thất bại - Failed";
        }
    }

    public function getTransactionReference()
    {
        if (isset($this->data['vpc_TransactionNo'])) {
            return $this->data['vpc_TransactionNo'];
        }

        return null;
    }

    public function getTransactionId()
    {
        if (isset($this->data['vpc_OrderInfo'])) {
            return $this->data['vpc_OrderInfo'];
        }

        return null;
    }
}