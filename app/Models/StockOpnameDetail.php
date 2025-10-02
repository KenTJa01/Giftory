<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpnameDetail extends Model
{
    use HasFactory;
    /**
     * Column info:
     *
     * id: integer
     * so_id: integer
     * catg_id: integer
     * catg_code: string
     * catg_desc: string
     * before_quantity: numeric
     * after_quantity: numeric
     * variance_qty: numeric
     * unit: string
     * created_at: timestamp
     * updated_at: timestamp
     * created_by: integer
     * updated_by: integer
     */

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "stock_opname_details";

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'so_id',
        'catg_id',
        'catg_code',
        'catg_desc',
        'before_quantity',
        'after_quantity',
        'variance_qty',
        'unit',
        'created_by',
        'updated_by',
    ];
}
