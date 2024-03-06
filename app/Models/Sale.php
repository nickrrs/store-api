<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    
    public $incrementing = false;

    protected $fillable = ['id', 'amount', 'status'];

    public function salesProducts()
    {
        return $this->hasMany(SaleProduct::class, 'sales_id', 'sales_id');
    }
}
