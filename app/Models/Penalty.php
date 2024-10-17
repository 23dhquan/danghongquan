<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    use HasFactory;
    public  $primaryKey = 'penalty_id';
    protected $fillable = [
        'tenant_id',      // Mã người thuê (foreign key từ bảng tenants)
        'amount',         // Số tiền phạt
        'description',    // Mô tả lý do phạt
        'penalty_date'    // Ngày lập phiếu phạt
    ];

    // Quan hệ
    public function tenant()
    {
        return $this->belongsTo(Tenant::class); // Một phiếu phạt thuộc về 1 người thuê
    }
}
