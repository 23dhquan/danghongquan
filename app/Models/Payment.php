<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    public  $primaryKey = 'payment_id';
    protected $fillable = [
        'tenant_id',      // Mã người thuê (foreign key từ bảng tenants)
        'amount',         // Số tiền thanh toán
        'payment_date',   // Ngày thanh toán
        'description'     // Mô tả chi tiết thanh toán
    ];

    // Quan hệ
    public function tenant()
    {
        return $this->belongsTo(Tenant::class); // Một khoản thanh toán thuộc về 1 người thuê
    }
}
