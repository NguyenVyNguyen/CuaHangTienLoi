<?php
class OrderStatusEnum {
    const Rejected = -2;
    const Cancelled = -1;
    const New = 1;
    const Accepted = 2;
    const Shipping = 3;
    const Completed = 4;

    public static function getDescription($status) {
        switch ($status) {
            case self::Rejected: return "Đơn hàng bị từ chối";
            case self::Cancelled: return "Đơn hàng đã bị hủy";
            case self::New: return "Đơn hàng vừa tạo";
            case self::Accepted: return "Đơn hàng đã được duyệt";
            case self::Shipping: return "Đơn hàng đang vận chuyển";
            case self::Completed: return "Hoàn tất";
            default: return "Không xác định";
        }
    }
}
?>