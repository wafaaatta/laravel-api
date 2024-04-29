<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Validator;
class Product extends Model
{
    use HasFactory;

    /**
     * 
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'price', 'stock', 'categories', 'image',
    ];

    /**
     * 
     *
     * @var array
     */
    protected $casts = [
        'categories' => 'json', 
    ];
}
