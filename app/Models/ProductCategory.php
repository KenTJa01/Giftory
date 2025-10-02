<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = "product_categories";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'catg_code', 'catg_name', 'unit', 'last_purch_price', 'last_sales_price', 'flag', 'created_by', 'updated_by'];

}
