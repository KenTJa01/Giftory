<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpnameHeader extends Model
{
    /**
     * Column info:
     *
     * id: integer
     * so_no: string
     * so_date: date
     * so_type: string
     * site_id: integer
     * site_code: integer
     * total_items: numeric
     * totals_qty: numeric
     * location_id: integer
     * location_code: string
     * flag: integer
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
    protected $table = "stock_opname_headers";

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
        'so_no',
        'so_date',
        'so_type',
        'site_id',
        'site_code',
        'total_items',
        'total_qty',
        'location_id',
        'location_code',
        'flag',
        'created_by',
        'updated_by',
    ];
}
