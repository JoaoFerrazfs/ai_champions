<?php

namespace Catalog\Infrastructure\Products\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
    ];

}
