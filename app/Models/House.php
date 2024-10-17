<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;
    public $primaryKey ='house_id';
    public $timestamps = false;
    protected $fillable = [
        'name',         // Tên nhà trọ
        'area_id',      // Mã khu vực liên kết (foreign key)
        'price',        // Giá thuê nhà trọ
        'description',    // Mô tả nhà trọ
        'is_rented'
    ];

    public function getAreaName()
    {
        return Area::find($this->area_id)->name ?? 'N/A'; // Trả về 'N/A' nếu không tìm thấy
    }

    // Quan hệ
    public function area()
    {
        return $this->belongsTo(Area::class); // Một nhà trọ thuộc về 1 khu vực
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class); // Một nhà trọ có nhiều người thuê
    }
}
