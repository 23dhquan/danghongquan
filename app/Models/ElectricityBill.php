<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectricityBill extends Model
{
    use HasFactory;
    public  $primaryKey = 'electricity_bill_id';
    protected $fillable = [
        'house_id',      // Mã nhà trọ (foreign key từ bảng houses)
        'month',         // Tháng ghi nhận hóa đơn
        'units_used',    // Số đơn vị điện đã sử dụng (kWh)
        'unit_price',    // Giá mỗi đơn vị điện
        'total_amount'   // Tổng số tiền điện phải trả
    ];

    // Quan hệ
    public function house()
    {
        return $this->belongsTo(House::class); // Hóa đơn điện thuộc về 1 nhà trọ
    }
}
