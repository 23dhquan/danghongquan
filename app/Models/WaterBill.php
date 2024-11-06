<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterBill extends Model
{
    use HasFactory;
    protected $table = 'water_bills';
    public $primaryKey = 'water_bill_id';
    protected $fillable = [
        'house_id',
        'billing_date',
        'water_image',
        'amount',

    ];


    public function house()
    {
        return $this->belongsTo(House::class);
    }
}
