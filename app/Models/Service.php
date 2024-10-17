<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    public $primaryKey ='service_id';
    protected $fillable = [
        'name',         // Tên dịch vụ
        'description',  // Mô tả dịch vụ
        'monthly_fee'   // Phí hàng tháng cho dịch vụ
    ];
}
