<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParKingCard extends Model
{
    use HasFactory;
    public $primaryKey = 'parking_card_id';
    protected $fillable = [
        'tenant_id',    // Mã người thuê (foreign key từ bảng tenants)
        'card_number',   // Số thẻ gửi xe
        'start_date',    // Ngày bắt đầu sử dụng thẻ
        'end_date',      // Ngày kết thúc (nếu có)
        'monthly_fee'    // Phí gửi xe hàng tháng
    ];

    // Quan hệ
    public function tenant()
    {
        return $this->belongsTo(Tenant::class); // Một thẻ gửi xe thuộc về 1 người thuê
    }
}
