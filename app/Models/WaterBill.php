<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterBill extends Model
{
    use HasFactory;
    public $primaryKey = 'waterBill_Id';
    protected $fillable = [
        'house_id',      // Mã nhà trọ (foreign key từ bảng houses)
        'month',         // Tháng ghi nhận hóa đơn
        'units_used',    // Số m3 nước đã sử dụng
        'unit_price',    // Giá mỗi m3 nước
        'total_amount'   // Tổng số tiền nước phải trả
    ];

    // Quan hệ
    public function house()
    {
        return $this->belongsTo(House::class); // Hóa đơn nước thuộc về 1 nhà trọ
    }
}
