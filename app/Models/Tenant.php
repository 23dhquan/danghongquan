<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;
    public $primaryKey ='tenant_id';
    protected $table = 'tenants';
    public $timestamps = false;
    protected $fillable = [
        'user_id',      // Mã người dùng (foreign key từ bảng users)
        'house_id',
        'start_date',   // Ngày bắt đầu thuê
        'end_date'      // Ngày kết thúc thuê (nullable)
    ];

    // Quan hệ
    public function user()
    {
        return $this->belongsTo(User::class); // Người thuê là 1 người dùng
    }

    public function house()
    {
        return $this->belongsTo(House::class); // Người thuê 1 nhà trọ cụ thể
    }

    public function parkingCards()
    {
        return $this->hasMany(ParkingCard::class); // Một người thuê có nhiều thẻ gửi xe
    }

    public function payments()
    {
        return $this->hasMany(Payment::class); // Một người thuê có nhiều khoản thanh toán
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class); // Một người thuê có nhiều phiếu phạt
    }
}
