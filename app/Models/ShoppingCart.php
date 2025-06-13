<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use HasFactory;
    
    protected $table = 'shopping_cart';

    protected $fillable = [
        'user_id',
        'product_id',
        'amount',
        'total_price',
    ];

    // Definir los tipos de datos para la tabla
    protected $casts = [
        'amount' => 'integer',
        'total_price' => 'decimal:2',
    ];

}
