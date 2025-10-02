<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    /**
     * Column info:
     *
     * id: integer
     * mov_date: date
     * site_id: integer
     * site_code: integer
     * location_id: integer
     * location_code: string
     * catg_id: integer
     * catg_code: string
     * quantity: integer
     * unit: string
     * mov_code: string
     * purch_price: numeric
     * sales_price: numeric
     * ref_no: string
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
    protected $table = "stock_movements";

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
        'mov_date',
        'site_id',
        'site_code',
        'location_id',
        'location_code',
        'catg_id',
        'catg_code',
        'quantity',
        'unit',
        'mov_code',
        'purch_price',
        'sales_price',
        'ref_no',
        'created_by',
        'updated_by',
    ];
}
