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

    public function products()
    {
        return $this->belongsToMany(Product::class, 'sale_products', 'sale_id', 'product_id')
                    ->withPivot('amount');
    }
}
